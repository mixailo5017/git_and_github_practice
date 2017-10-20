var $ = require('jquery');
var filedrop = require('../_lib/filedrop');
var base64js = require('base64-js');

// Don't log FileDrop events to console
filedrop.logging = false;

var tipsy = require('../_lib/jquery.tipsy');
var cropper = require('cropper'); // https://github.com/fengyuanchen/cropper
var rekognition = require('./_rekognition');

var uploadUrl = '/signup/photo/upload',
    removeUrl = '/signup/photo/remove',
    fileName = '',
    $imgCrop = $('.crop-wrap > img'),
    $cropZone = $('.crop-zone'),
    zone = null,
    savingImage = false,
    cropArea = {},
    imageHolder = document.querySelector( '#pickphoto-imageholder' ),
    boundingBox = null,
    // Keep track of Next button state
    nxtBtnDisabled = false,
    $nxtBtn = $('#btnNext');

function getUploadImageDimensions(image) {
    // Before attempting to perform facial recognition, 
    // scale down the image to make sure it won't take forever
    
    var dimensions = {};
    dimensions.width = image.naturalWidth;
    dimensions.height = image.naturalHeight;

    if (image.naturalWidth > 1024) {
      var proportions = image.naturalWidth / image.naturalHeight;
      dimensions.width = 1024;
      dimensions.height = 1024 / proportions;
    }

    return dimensions;
}

function checkSizeThenCheckFaces(resolveCheckFaces) {
    // If image is too small to be processed by AWS Rekognition,
    // don't bother trying. Instead, ask for a bigger image
    if (imageHolder.naturalWidth < 80 || imageHolder.naturalHeight < 80) {
        displayError("Goodness, what a tiny image! We don't want you to look blurry — please click Remove Image and upload one that is at least 80 pixels in width and height.");
        return;
    }

    // Once image has been resized, proceed with remaining logic
    checkFaces(resolveCheckFaces);
}

function checkFaces(resolveCheckFaces) {
    var dimensions = getUploadImageDimensions(imageHolder);

    // Test whether image includes a face
    // If it does, re-enable the Next button
    var imageInBase64 = getImageInBase64(imageHolder, dimensions.width, dimensions.height);

    rekognition.detectFaceFromBlob(imageInBase64).then((faceData) => {
        if (faceData.foundFace) {
            reenableNext();
            boundingBox = faceData.boundingBox;
        } else {
            displayError("Oh dear! We looked hard but we couldn't see your face. Please could you try another image? Just click Remove Image and try again, or <a href='https://gvip.zendesk.com/hc/en-us/articles/115002480574-Why-do-I-need-to-upload-a-profile-picture-in-order-to-join-GViP-' target='_blank'>get help</a>.");
        };
        resolveCheckFaces();
    }).catch((err) => {
        console.log(err);
        // If AWS is erroring, we should allow user to proceed,
        // even at the risk of them getting away with uploading a picture without a face
        reenableNext();
    });
}

function repositionCropbox() {
    var canvasData = $imgCrop.cropper('getCanvasData');
    var cropBoxData = {};
    cropBoxData.left = Math.max(canvasData.width * (boundingBox.Left - (boundingBox.Width * 0.2)), 1);
    cropBoxData.top = Math.max(canvasData.height * (boundingBox.Top - (boundingBox.Height * 0.2)), 1);
    cropBoxData.width = Math.min(canvasData.width * (boundingBox.Width * 1.4), (canvasData.width - cropBoxData.left));
    cropBoxData.height = Math.min(canvasData.height * (boundingBox.Height * 1.4), (canvasData.height - cropBoxData.top));
    $imgCrop.cropper('setCropBoxData', cropBoxData);
}

function getImageInBase64(image, width, height) {
    var canvas = document.createElement('canvas');
    canvas.width = width;
    canvas.height = height;

    canvas.getContext('2d').drawImage(image, 0, 0, width, height);

    // Get raw image data
    var imageString = canvas.toDataURL('image/jpeg', 0.5).replace(/^data:image\/(png|jpe?g);base64,/, '');
    var imageBytes = base64js.toByteArray(imageString);
    return imageBytes;
}

function hasDraggable() {
    return 'draggable' in document.createElement('span');
}

function fixHeight() {
    setTimeout(function () {
        var rotateHeight = $('.cropper-container').height() + 60;
        $('.crop-here').css({ 'height': rotateHeight});
    }, 500);
}

function nextButtonClickHandler() {
    if (! nxtBtnDisabled ) showSavingImageMessageAndSaveImage();
    return false;
}

function displayError(msg) {
    $('.error-head').html(msg);
}

function saveCroppedImageResponse(resp) {

    if (resp.status === 'error') {
        displayError(resp.error);
    }
    if (resp.status === 'success') {
        window.location = '/signup/confirm';
    }
}

function loadCropper(imageUrl, loadCropperResolve) {
    $('#zone').removeClass('fd-zone');
    $('#zone').removeClass('filedrop');
    $('.fd-file, .drop-meta').hide();
    $('.crop-controls, .crop-confirm').show();
    $imgCrop.attr('src', imageUrl).cropper({
        zoomable: false,
        modal: true,
        aspectRatio: 1 / 1,
        ready: function (data) {
            if (typeof loadCropperResolve !== 'undefined') loadCropperResolve();
        }
    });
    setTimeout(function () {
        var aniHeight = $('.cropper-container').height();
        $('.crop-wrap').css({ 'height': aniHeight});
    }, 1000);
}

function triggerBasicCropper() {
    var $cropper = $('#cropper_image'),
        $crop_wrap = $('.crop-wrap');

    if ($cropper.length) {
        $crop_wrap.css({ 'opacity': 0 });
        fileName = $('#cropper_image').attr('src').replace('/images/signup/', '');
        $().hide();
        $('.drop-meta').hide();
        setTimeout(function () {
            $('.fd-file').hide();
            $crop_wrap.animate({ 'opacity': 1 }, 500);
        }, 500);

        loadCropper($cropper.attr('src'));
    }
}

function showBasicUpload() {  
    $('#basicUpload').show();
}

function removeImage() {
    $.post(removeUrl, {})
        .done(function () {
            window.location.reload();
        })
        .fail(function () {
            displayError('ohh no!');
        });
}

function saveImage(data, name) {

    if (!savingImage) {
        savingImage = true;
        var postData = {
            'fd-file': name,
            'raw-file': data
        };
        $.post(uploadUrl, $.param(postData, true))
            .done(function (resp) {
                saveCroppedImageResponse(resp);
                savingImage = false;

            })
            .fail(function () {
                $cropZone.removeClass('disabled');
                displayError('There was an error saving the image.');
                savingImage = false;
            });
    }
}

function loadFileDrop() {  
    $('#zone').show();

    // We can deal with iframe uploads using this URL:
    var options = {
        iframe: false
    }; //{url: '/signup/photo/upload', callbackParam: 'fd-callback'}}
    
    // 'zone' is an ID but you can also give a DOM node:
    zone = new filedrop.FileDrop('zone', options);
    zone.event('send', function (files) {

        files.each(function (file) {

            displayError('');

            var fs = Number(file.size / 1024),
                fileSize = Number(((fs / 1024) * 100) / 100),
                fileName1 = file.name,
                fileType = fileName1.replace(/^.*\./, '').toLowerCase();

            if (fileType !== 'png' && fileType !== 'jpg' && fileType !== 'jpeg') {
                displayError('Error: File must be a \'jpg\' or \'png\'');
            } else if (fileSize > 5) {
                displayError('Error: Upload file must be less than 5 MB');
            } else {
                $('.fd-file').hide();
                $('.progress').fadeIn('fast');
                file.event('progress', function (current, total) {
                    var width = current / total * 100 + '%';
                    filedrop.byID('bar_zone').style.width = width;
                });

                file.event('done', function (xhr) {
                    $('.progress').fadeOut('fast', function () {
                        var resp = jQuery.parseJSON(xhr.responseText);
                        if (typeof resp.status !== 'undefined' && resp.status === 'success') {

                            // trigger cropper
                            var loadCropperPromise = new Promise((loadCropperResolve, loadCropperReject) => {
                                loadCropper(resp.original, loadCropperResolve);
                            });
                            Promise.all([checkFacesPromise, loadCropperPromise]).then(() => {
                                if (boundingBox) repositionCropbox();
                            });
                            return;
                        }
                        if (typeof resp.status !== 'undefined' && resp.status === 'error') {
                            displayError('Error: ' + resp.error);
                             $('.fd-file').show();
                            return;
                        }

                        // general error message
                        displayError('Error: File could not be uploaded');
                        $('.fd-file').show();
                    });

                });

                file.sendTo(uploadUrl);

                // Without waiting for image to finish uploading to server, upload a scaled-down version
                // to AWS Rekognition and see whether it contains a face
                var checkFacesPromise = new Promise((resolveCheckFaces, rejectCheckFaces) => {
                    file.readDataURL(function (dataURL) {
                      imageHolder.addEventListener("load", () => {
                        checkSizeThenCheckFaces(resolveCheckFaces);
                      }, {once: true});
                      imageHolder.src = dataURL;
                    });
                });
            }
            file.event('error', function () {
                // need better error
                //alert('Error uploading ' + this.name + ': ' + xhr.status + ', ' + xhr.statusText);
                displayError('Error uploading ' + this.name);
                $('.fd-file').show();
            });

        });
    });
}

// Re-enables the Next button
function reenableNext() {
    $nxtBtn.removeClass('dk-green--disabled');
    $nxtBtn.attr('original-title', ''); // Removes the tooltip
    nxtBtnDisabled = false;
}

function showSavingImageMessageAndSaveImage() {
    var _top = $('.cropper-container').height() / 2;
    $('.progress').fadeIn('fast').prepend('Saving image please wait.').css({'top':_top}).children().css({'width':'100%'});
    $cropZone.addClass('disabled');
    saveImage($imgCrop.cropper('getCroppedCanvas').toDataURL(), fileName);
}

// Disables the Next button
function disableNext() {
    $nxtBtn.addClass('dk-green--disabled');

    // Manipulate its HTML so it is not clickable, etc.
    $nxtBtn.attr('title', "Please add a photo! It's the final step before confirming.");

    // Enable the nice tooltip
    $nxtBtn.tipsy();

    nxtBtnDisabled = true;
}


module.exports = function() {

    $(function () {
        'use strict'; 

        $cropZone.on('click', '[data-method]', function () {

            var $this = $(this),
                data = $this.data(),
                id = $this.attr('id');

            if ($cropZone.hasClass('disabled')) {
                return false;
            }

            if (data.method) {
                $imgCrop.cropper(data.method, data.option);
                if (data.method === 'rotate') {
                    fixHeight();
                }
            }

            if (id === 'removeImage') {
                $cropZone.addClass('disabled');
                removeImage();
            }
        });

        $nxtBtn.on('click', nextButtonClickHandler);

        // Tooltips
        $('#rotateLeft').tipsy({gravity: 's'});
        $('#rotateRight').tipsy({gravity: 's'});

        // IE9
        if ($('html').hasClass('ie9')) {
            $('#basicUpload input[type="file"]').on('change', function () {
                $('#upload_photo').fadeIn();
            });
        }

        var userAlreadyHasImage = $('#editExistingPhoto').length > 0;
        if (userAlreadyHasImage) { // Let them edit it
            $('#editExistingPhoto').on('click', triggerBasicCropper);
            $('#removeExistingPhoto').on('click', removeImage);
        } else { // Let them upload one. They cannot proceed until they do!
            disableNext();
            if (hasDraggable()) {
                loadFileDrop();
            } else {
                showBasicUpload();
            }
        }
    });
};
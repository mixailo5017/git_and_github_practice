var $ = require('jquery');
var filedrop = require('../_lib/filedrop');
// Don't log FileDrop events to console
filedrop.logging = false;

var tipsy = require('../_lib/jquery.tipsy');
var cropper = require('cropper'); // https://github.com/fengyuanchen/cropper

module.exports = function() {

    $(function () {
        'use strict'; 

        var uploadUrl = '/signup/photo/upload',
            removeUrl = '/signup/photo/remove',
            fileName = '',
            $imgCrop = $('.crop-wrap > img'),
            $cropZone = $('.crop-zone'),
            zone = null,
            savingImage = false;

        function hasDraggable() {
            return 'draggable' in document.createElement('span');
        }

        function fixHeight() {
            setTimeout(function () {
                var rotateHeight = $('.cropper-container').height() + 60;
                $('.crop-here').css({ 'height': rotateHeight});
            }, 500);
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

        function loadCropper(imageUrl) {
            $('#zone').removeClass('fd-zone');
            $('#zone').removeClass('filedrop');
            $('.fd-file, .drop-meta').hide();
            $('.crop-controls, .crop-confirm').show();
            $imgCrop.attr('src', imageUrl).cropper({
                zoomable: false,
                modal: true,
                aspectRatio: 1 / 1,
                //done: function (data) {}
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

        function doBasicUpload() {  
            $('#basicUpload').show();
            triggerBasicCropper();
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

                    if (fileType !== 'png' && fileType !== 'jpg' && fileType !== 'gif') {
                        displayError('Error: File must be a \'jpg\', \'png\', or \'gif\'');
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
                                    loadCropper(resp.original);
                                    //Enable Next Button
                                    reenableNext();
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

            if (id === 'saveImage') {
                var _top = $('.cropper-container').height() / 2;
                $('.progress').fadeIn('fast').prepend('Saving image please wait.').css({'top':_top}).children().css({'width':'100%'});
                $cropZone.addClass('disabled');
                saveImage($imgCrop.cropper('getCroppedCanvas').toDataURL(), fileName);
            }

            if (id === 'removeImage') {
                $cropZone.addClass('disabled');
                removeImage();
            }
        });

        // Keep track of Next button state
        var nxtBtnDisabled = false;
        var $nxtBtn, nxtBtnOriginalState;

        // Disables the Next button
        function disableNext() {
            // Find the button, store its original state
            $nxtBtn = $('#btnNext');
            nxtBtnOriginalState = $nxtBtn[0].outerHTML;

            // Manipulate its HTML so it is not clickable, etc.
            $nxtBtn.css('opacity', .2);
            $nxtBtn.css('cursor', 'default');
            $nxtBtn.attr('title', "Please add a photo! It's the final step before confirming.");
            $nxtBtn.removeAttr('href');

            // Enable the nice tooltip
            $nxtBtn.tipsy();

            nxtBtnDisabled = true;
        }

        disableNext();

        // Re-enables the Next button
        function reenableNext() {
            $nxtBtn[0].outerHTML = nxtBtnOriginalState;
            nxtBtnDisabled = false;
        }

        // Tooltips
        $('#rotateLeft').tipsy({gravity: 's'});
        $('#rotateRight').tipsy({gravity: 's'});

        // IE9
        if ($('html').hasClass('ie9')) {
            $('#basicUpload input[type="file"]').on('change', function () {
                $('#upload_photo').fadeIn();
            });
        }

        triggerBasicCropper();

        if (hasDraggable()) {
            loadFileDrop();
        } else {
            doBasicUpload();
        }

    });
};
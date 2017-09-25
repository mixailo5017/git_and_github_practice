var $ = require('jquery');
// require tipsy;

module.exports = function() {

    // Don't log FileDrop events to console
    window.fd = {logging: false};

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

        function doBasicUpload() {  //alert('doBasicUpload')
            $('#basicUpload').show();
            triggerBasicCropper();
        }

        function removeImage() {
            $.post(removeUrl, {})
                .success(function () {
                    //console.log( resp );
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
                $.post(uploadUrl, postData)
                    .success(function (resp) {
                        saveCroppedImageResponse(resp);
                        savingImage = false;

                    })
                    .fail(function () {
                        $cropZone.removeClass('disabled');
                        //console.log( resp );
                        displayError('There was an error saving the image.');
                        savingImage = false;
                    });
            }
        }

        function loadFileDrop() {  //alert('loadFileDrop')
            $('#zone').show();

            // We can deal with iframe uploads using this URL:
            var options = {
                iframe: false,
                logging: false
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
                            window.fd.byID('bar_zone').style.width = width;
                        });
                        file.event('done', function (xhr) {
                            $('.progress').fadeOut('fast', function () {
                                var resp = jQuery.parseJSON(xhr.responseText);
                                if (typeof resp.status !== 'undefined' && resp.status === 'success') {

                                    // trigger cropper
                                    //console.log( resp.original );
                                    loadCropper(resp.original);
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
            //console.log( data.method, data.option, id );
            if (data.method) {
                $imgCrop.cropper(data.method, data.option);
                if (data.method === 'rotate') {
                    fixHeight();
                }

                // if (data.method == 'setCropBoxData') {
                //     //console.log('Uhh....');
                // }
            }

            if (id === 'saveImage') {
                var _top = $('.cropper-container').height() / 2;
                $('.progress').fadeIn('fast').prepend('Saving image please wait.').css({'top':_top}).children().css({'width':'100%'});
                $cropZone.addClass('disabled');
                saveImage($imgCrop.cropper('getDataURL'), fileName);
            }

            if (id === 'removeImage') {
                $cropZone.addClass('disabled');
                removeImage();
            }
        });


        // function resetUpload() {
        //     $('.crop-here').css({ 'opacity': 0 });
        //     $('input.uploaded').off();
        //     $('input.start-over').off();
        //     setTimeout(function () {
        //         $('.crop-here').css({ 'width': '0%', 'height': '0'});
        //         $('input.input-file').val('Choose Photo');
        //         $('input.start-over').removeClass('start-over');
        //         $('input[name="next"]').removeClass('uploaded');
        //         $('#zone').removeClass('inactive');
        //     }, 500);
        // }



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
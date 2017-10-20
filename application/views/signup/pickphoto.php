<section class="main-content container">
    <h1 class="h1-xl">Upload Photo</h1>
    <p>It's time to put a face to your name.<br>Adding a photo is great way to inspire other experts and project developers to connect with you.</p>

    <?php $this->load->view('signup/_progress', array('step' => 'photo')) ?>

    <img id="pickphoto-imageholder">

    <div class="form-cta">
        <div class="interior">
            <!--            <h2 class="h3-std">Create Account</h2>-->
            <!--            <div class="down-arrow"></div>-->
            <!--            <p>Please select your method of input.</p>-->
            <div class="error-head"><?php if (! empty($error)) echo $error ?></div>
            <?php  echo form_open_multipart('', array('name' => 'signup_photo', 'class' => 'form'))  ?>
                <?php /*
                <p class="ready-to-upload" style="display: none">You can go ahead and Upload your photo now.</p>
                */ ?>
                <div id="zone" class="filedrop signup-info">
                    <p class="progress"><span id="bar_zone"></span></p>
                    <div class="crop-zone">
                        <?php $src = safe_image(SIGNUP_IMAGE_PATH, $signup['userphoto'], USER_NO_IMAGE_PATH . USER_IMAGE_PLACEHOLDER, array('max' => 198)) ?>
                        
                        <div class="crop-wrap"><img src="<?php echo $src; ?>" alt="Expert's photo"></div>
                        <div class="crop-controls" style="visibility: visible !important; display: none !important;">                            
                            <button class="btn ico lt-blue" data-method="rotate" data-option="-90" id="rotateLeft" original-title="Rotate Left" type="button"><span class="icon icon-rotate-left"></span></button>
                            <button class="btn ico lt-blue" data-method="rotate" data-option="90" id="rotateRight" original-title="Rotate Right" type="button"><span class="icon icon-rotate-right"></span></button>   
                        </div>
                        <div class="crop-confirm" style="visibility: visible !important; display: none !important;">
                            <button class="btn std" data-method="remove" data-option="true" id="removeImage" type="button">Remove Image</button>
                        </div>
                    </div>
                    <?php 
                        $alreadyHasPhoto = !empty($signup['userphoto']);
                    ?>
                    <div class="drop-meta">
                        <?php if ($alreadyHasPhoto): ?>
                            <button class="btn std" id="editExistingPhoto" type="button">Edit Image</button>
                            <button class="btn std" id="removeExistingPhoto" type="button">Remove Photo</button>
                        <?php else: ?>
                            <p class="inst">Drop an image file here to upload<br />or</p>
                            <input type="button" class="btn std input-file" data-id="userphoto" value="Select File" />
                        <?php endif; ?>
                        
                        <div id="basicUpload" style="display: none; padding: 10px;">
                            
                            <?php if ($alreadyHasPhoto): ?>
                                <!-- <input type="submit" name="remove_photo" value="Remove Image" /> -->
                                <img src="<?php echo SIGNUP_IMAGE_PATH . $signup['userphoto']?>" id="cropper_image" alt="user photo" style="display:none" />
                            <?php else: ?>
                                <div class="form-buttons centered">
                                    <div class="file-upload btn">
                                    <span>Select File</span>
                                        <input type="file" name="fd-file" />
                                    </div>
                                    <input type="submit" name="upload_photo" id="upload_photo" value="Upload Image" class="btn dk-green" />
                                </div>
                            <?php endif; ?>

                        </div>
                        <p class="subline">Supported file types: JPEG, PNG. Max file size is 5MB.</p>
                    </div>
                </div>
                
                <div class="form-buttons centered">
                    <a href="/signup/edit" class="btn std clear">Back</a>
                    <a href="/signup/confirm" class="btn std dk-green" id="btnNext">Next</a>
                </div>

            <?php echo form_close() ?>
        </div>
    </div>
</section>

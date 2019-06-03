<div id="forum_images_form" class="clearfix">
    <div class="contenttitle2">
        <h3>Upload Photo</h3>
    </div>
    <br/>

    <div class="clearfix" id="div_general_photo_form">
        <div style="width:150px;" class="clearfix">
            <div class="div_resize_img196">
                <img src="<?php echo forum_image($details['photo'], 198, array(
                    'allow_scale_larger' => false)); ?>" class="uploaded_img" alt="" style="margin: 0px;">
            </div>
        </div>

        <div class="" style="padding-left:10px;">
            <div class="comment no_margin_top">Select an image from your computer (5MB max):</div>
            <?php
            echo form_open_multipart(current_url(), array(
                'id' => 'forum_images_photo_form',
                'name' => 'general_photo_form',
                'method' => 'post'
            ));
            echo form_hidden('update', 'photo');
            echo form_upload(array('name' => 'photo_filename', 'id' => 'photo_filename'));
            ?>
            <div id="err_photo_filename" class="errormsg"></div>
            <div class="comment">Compatible file types: JPEG, GIF, PNG</div>
            <?php
            echo form_submit('submit', 'Upload Image','class = "light_green no_margin_left"');
            echo form_close();
            ?>
        </div>
    </div>

    <div class="contenttitle2">
        <h3>Upload Banner</h3>
    </div>
    <br/>
    <div class="clearfix" id="div_general_photo_form">
        <div style="width:600px;" class="clearfix">
            <div class="div_resize_img560">
                <img src="<?php echo safe_image(FORUM_IMAGE_PATH, $details['banner'], FORUM_NO_IMAGE_PATH . 'placeholder_forum_banner.png', array(
                    'max' => 600,
                    'rounded_corners' => null,
                    'fit' => 'contain',
                    'allow_scale_larger' => false)); ?>" class="uploaded_img" alt="" style="margin: 0px;">
            </div>
        </div>

        <div class="" style="padding-left:10px;">
            <div class="comment no_margin_top">Select an image from your computer (5MB max):</div>
            <?php
            echo form_open_multipart(current_url(), array(
                'id' => 'forum_images_banner_form',
                'name' => 'general_banner_form',
                'method' => 'post'
            ));
            echo form_hidden('update', 'banner');
            echo form_upload(array('name' => 'photo_filename', 'id' => 'photo_filename'));
            ?>
            <div id="err_photo_filename" class="errormsg"></div>
            <div class="comment">Compatible file types: JPEG, GIF, PNG</div>
            <?php
            echo form_submit('submit', 'Upload Image','class = "light_green no_margin_left"');
            echo form_close();
            ?>
        </div>
    </div>
</div>
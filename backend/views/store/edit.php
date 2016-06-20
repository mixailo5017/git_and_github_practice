<div class="centercontent">
    <div class="pageheader notab">
        <h1 class="pagetitle">Edit Store Item</h1>
        <span class="pagedesc">&nbsp;</span>
        <a class="right goback" style="margin-right:20px;" href="/admin.php/store"><span>Back</span></a>
    </div><!-- pageheader -->

    <div id="contentwrapper" class="contentwrapper">
        <div class="notibar_add" style="display:none">
            <a class="close"></a>
            <p></p>
        </div>

        <div class="widgetcontent">
            <div style="width:50%" id="store_item_form" class="clearfix">
                <div class="contenttitle2">
                    <h3>Upload Photo</h3>
                </div>
                <br/>

                <div class="clearfix" id="div_general_photo_form">
                    <div style="width:150px;" class="clearfix">
                        <div class="div_resize_img196">
                            <img src="<?php echo store_item_image($details['photo'], 50, array(
                                'allow_scale_larger' => false)); ?>" class="uploaded_img" alt="" style="margin: 0px;">
                        </div>
                    </div>

                    <div class="" style="padding-left:10px;">
                        <div class="comment no_margin_top">Select an image from your computer (5MB max):</div>
                        <?php
                        echo form_open_multipart(current_url(), array(
                            'id' => 'store_item_photo_form',
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
                    <h3>Details</h3>
                </div>
                <br/>
                <?php
                echo form_open_multipart(current_url(), array(
                    'id' => 'store_item_general_form',
                    'class' => 'store_item_general_form ajax_add_form'
                ));
                echo form_hidden('update', 'general');
                ?>
                <div id="div_general_photo_form">
                    <div class="field">
                        <?php echo form_label('Title:', 'title_label', array('class' => 'left_label')); ?>
                        <div class="fld">
                            <?php echo form_input(array(
                                'id'	=> 'title',
                                'value' => set_value('title', $details['title']),
                                'name'	=> 'title',
                                'placeholder' => 'Strategic Partnership'
                            )); ?>
                            <div class="errormsg" id="err_title"><?php echo form_error('title'); ?></div>
                        </div>
                    </div>
                    <div class="field">
                        <?php echo form_label('Item URL:', 'url_label', array('class' => 'left_label')); ?>
                        <div class="fld">
                            <?php echo form_input(array(
                                'id'	=> 'url',
                                'value' => set_value('url', $details['url']),
                                'name'	=> 'url',
                                'placeholder' => 'http://store.gvip.io/product/strategic-partnership/'
                            )); ?>
                            <div class="errormsg" id="err_url"><?php echo form_error('url'); ?></div>
                        </div>
                    </div>
                </div>
                <br/>
                <div>
                    <?php echo form_submit('submit', 'Update', 'class="light_green no_margin_left"'); ?>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div><!-- widgetcontent -->
    </div><!-- contentwrapper -->
</div><!-- centercontent -->

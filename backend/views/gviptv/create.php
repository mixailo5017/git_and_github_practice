<div class="centercontent">
    <div class="pageheader notab">
        <h1 class="pagetitle">Add New GViP TV Video</h1>
        <span class="pagedesc">&nbsp;</span>
    </div>

    <div id="contentwrapper" class="contentwrapper">
        <?php echo form_open_multipart(current_url(), array('id' => "new_forum_form")); ?>

        <div id="div_general_photo_form" style="width:675px;">
            <div class="field">
                <?php echo form_label('Title:', 'title_label', array('class' => 'left_label')); ?>
                <div class="fld">
                    <?php echo form_input(array(
                        'id'	=> 'title',
                        'value' => 'title',
                        'name'	=> 'title',
                        'placeholder' => 'Title'
                    )); ?>
                    <div class="errormsg" id="err_title"><?php echo form_error('title'); ?></div>
                </div>
            </div>

            <?php $categories = array('Tech','Investment', 'Leadership','Projects');?>

            <div class="field">
                <?php echo form_label('Category:', 'category_id_label', array('class' => 'left_label')); ?>
                <div class="fld">
                    <?php echo form_dropdown('category_id', $categories, set_value('category_id')); ?>
                    <div class="errormsg" id="err_category_id"><?php echo form_error('category_id'); ?></div>
                </div>
            </div>

            <div class="field">
                <?php echo form_label('Title:', 'title_label', array('class' => 'left_label')); ?>
                <div class="fld">
                    <?php echo form_input(array(
                        'id'	=> 'link',
                        'value' => 'link',
                        'name'	=> 'link',
                        'placeholder' => 'youtube.com/43tnjej3'
                    )); ?>
                    <div class="errormsg" id="err_title"><?php echo form_error('title'); ?></div>
                </div>
            </div>

            <div class="field">
                <?php echo form_label('Title:', 'title_label', array('class' => 'left_label')); ?>
                <div class="fld">
                    <?php echo form_input(array(
                        'id'	=> 'thumbnail',
                        'value' => 'thumbnail',
                        'name'	=> 'thumbnail',
                        'placeholder' => 'Cloudfront link'
                    )); ?>
                    <div class="errormsg" id="err_title"><?php echo form_error('title'); ?></div>
                </div>
            </div>

            <div class="field">
                <?php echo form_label('Title:', 'title_label', array('class' => 'left_label')); ?>
                <div class="fld">
                    <?php echo form_input(array(
                        'id'	=> 'thumbnail',
                        'value' => 'thumbnail',
                        'name'	=> 'thumbnail',
                        'placeholder' => 'Cloudfront link'
                    )); ?>
                    <div class="errormsg" id="err_title"><?php echo form_error('title'); ?></div>
                </div>
            </div>

            <div class="contenttitle2">
                <h3>Video Description</h3>
            </div>
            <br/>
            <div>
                <?php echo form_textarea(array(
                    'type' => 'text',
                    'class' => 'tinymce',
                    'id' => 'description',
                    'name' => 'description',
                    'value' => 'description',
                    'data-width' => '675',
                    'data-height' => '400'
                )); ?>
            </div>
        </div>

        <br/>

        <div>
            <?php
            echo form_submit('submit', 'Add New Video', 'class="light_green no_margin_left"');
            echo form_button('cancel', 'Cancel', 'class="light_gray no_margin_left" style="margin-left:10px;" onclick="window.location.href=\'/admin.php/gviptv\'"');
            ?>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

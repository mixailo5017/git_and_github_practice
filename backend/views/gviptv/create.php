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
                        'value' => 'Video Name',
                        'name'	=> 'title'
                    )); ?>
                    <div class="errormsg" id="err_title"><?php echo form_error('title'); ?></div>
                </div>
            </div>

            <?php
            $categories = [
                'Technology' => 'Technology',
                'Investment' => 'Investment',
                'Leadership' => 'Leadership',
                'Projects'   => 'Projects',
            ]

            ?>

            <div class="field">
                <?php echo form_label('Category:', 'category_id_label', array('class' => 'left_label')); ?>
                <div class="fld">
                    <?php echo form_dropdown('category_id', $categories, 'Technology'); ?>
                    <div class="errormsg" id="err_category_id"><?php echo form_error('category_id'); ?></div>
                </div>
            </div>

            <div class="field">
                <?php echo form_label('Video Link (Format Must Match Placeholder!) :', 'title_label', array('class' => 'left_label')); ?>
                <div class="fld">
                    <?php echo form_input(array(
                        'id'	=> 'link',
                        'value' => 'https://www.youtube.com/embed/O1Uwk8vyvNk',
                        'name'	=> 'link'
                    )); ?>
                    <div class="errormsg" id="err_title"><?php echo form_error('title'); ?></div>
                </div>
            </div>

            <div class="field">
                <?php echo form_label('Thumbnail (Format Must Match Placeholder!):', 'title_label', array('class' => 'left_label')); ?>
                <div class="fld">
                    <?php echo form_input(array(
                        'id'	=> 'thumbnail',
                        'value' => 'https://d2huw5an5od7zn.cloudfront.net/gviptv/images/POY.jpg',
                        'name'	=> 'thumbnail'
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
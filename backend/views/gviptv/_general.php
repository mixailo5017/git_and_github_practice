<div style="width:50%" id="general_tab_form" class="clearfix">
    <?php
    echo form_open_multipart(current_url(), array(
        'id' => 'forum_general_form',
        'class' => 'forum_general_form ajax_add_form'
    ));
    echo form_hidden('update', 'general');
    ?>

    <div class="contenttitle2">
        <h3>Video Details</h3>
    </div>
    <br/>
    <div id="div_general_photo_form">
        <div class="field">
            <?php echo form_label('Title:', 'title_label', array('class' => 'left_label')); ?>
            <div class="fld">
                <?php echo form_input(array(
                    'id'	=> 'title',
                    'value' => set_value('title', $details['title']),
                    'name'	=> 'title'
                )); ?>
                <div class="errormsg" id="err_title"><?php echo form_error('title'); ?></div>
            </div>
        </div>
        <div class="field" style="clear: both">
            <?php echo form_label('Category:', 'category_id_label', array('class' => 'left_label')); ?>
            <div class="fld">
                <?php
                $categories = [
                    'Technology' => 'Technology',
                    'Investment' => 'Investment',
                    'Leadership' => 'Leadership',
                    'Projects'   => 'Projects',
                ]
                ?>
                <?php echo form_dropdown('category', $categories, set_value('category', $details['category'])); ?>
                <div class="errormsg" id="err_category_id"><?php echo form_error('category_id'); ?></div>
            </div>
        </div>
        <div class="field">
            <?php echo form_label('Video Link (Format Must Match Placeholder!) :', 'register_url_label', array('class' => 'left_label')); ?>
            <div class="fld">
                <?php echo form_input(array(
                    'id'	=> 'link',
                    'value' => set_value('link', $details['link']),
                    'name'	=> 'link'
                )); ?>
                <div class="errormsg" id="err_register_url"><?php echo form_error('link'); ?></div>
            </div>
        </div>
        <div class="field">
            <?php echo form_label('Thumbnail (Format Must Match Placeholder!):', 'meeting_label_url', array('class' => 'left_label')); ?>
            <div class="fld">
                <?php echo form_input(array(
                    'id'	=> 'thumbnail',
                    'value' => set_value('thumbnail', $details['thumbnail']),
                    'name'	=> 'thumbnail'
                )); ?>
                <div class="errormsg" id="err_meeting_url"><?php echo form_error('thumbnail'); ?></div>
            </div>
        </div>
        <br>
    </div>

    <div class="contenttitle2">
        <h3>Forum Venue</h3>
    </div>
    <br/>

    <div class="contenttitle2">
        <h3>Forum Description</h3>
    </div>
    <br/>
    <div>
        <?php echo form_textarea(array(
            'type' => 'text',
            'class' => 'tinymce',
            'id' => 'description',
            'name' => 'description',
            'value' => set_value('description', $details['description'], false),
            'data-width' => '675',
            'data-height' => '400'
        )); ?>
    </div>
    <br/>
    <div>
        <?php echo form_submit('submit', 'Update General Info', 'class="light_green no_margin_left"'); ?>
    </div>
    <?php echo form_close(); ?>
</div>

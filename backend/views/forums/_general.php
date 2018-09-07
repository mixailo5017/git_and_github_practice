<div style="width:50%" id="general_tab_form" class="clearfix">
    <?php
    echo form_open_multipart(current_url(), array(
        'id' => 'forum_general_form',
        'class' => 'forum_general_form ajax_add_form'
    ));
    echo form_hidden('update', 'general');
    ?>

    <div class="contenttitle2">
        <h3>Forum Details</h3>
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
        <div class="field" style="float:left">
            <?php echo form_label('Start Date:', 'start_date_label', array('class' => 'left_label')); ?>
            <div class="fld">
                <?php echo form_input(array(
                    'id'	=> 'start_date',
                    'name'	=> 'start_date',
                    'value'	=> set_value('start_date', format_date($details['start_date'], 'm/d/Y')),
                    'class' => 'datepicker',
                    'style' => 'width:120px'
                )); ?>
                <div class="errormsg" id="err_start_date"><?php echo form_error('start_date'); ?></div>
            </div>
        </div>
        <div class="field" style="float:left;margin-left: 15px;">
            <?php echo form_label('End Date:', 'end_date_label', array('class' => 'left_label')); ?>
            <div class="fld">
                <?php echo form_input(array(
                    'id'	=> 'end_date',
                    'name'	=> 'end_date',
                    'value'	=> set_value('end_date', format_date($details['end_date'], 'm/d/Y')),
                    'class' => 'datepicker',
                    'style' => 'width:120px'
                )); ?>
                <div class="errormsg" id="err_end_date"><?php echo form_error('end_date'); ?></div>
            </div>
        </div>
        <div class="field" style="clear: both">
            <?php echo form_label('Category:', 'category_id_label', array('class' => 'left_label')); ?>
            <div class="fld">
                <?php echo form_dropdown('category_id', $categories, set_value('category_id', $details['category_id'])); ?>
                <div class="errormsg" id="err_category_id"><?php echo form_error('category_id'); ?></div>
            </div>
        </div>
        <div class="field">
            <?php echo form_label('Registration URL:', 'register_url_label', array('class' => 'left_label')); ?>
            <div class="fld">
                <?php echo form_input(array(
                    'id'	=> 'register_url',
                    'value' => set_value('register_url', $details['register_url']),
                    'name'	=> 'register_url'
                )); ?>
                <div class="errormsg" id="err_register_url"><?php echo form_error('register_url'); ?></div>
            </div>
        </div>
        <div class="field">
            <?php echo form_label('Meeting URL:', 'meeting_label_url', array('class' => 'left_label')); ?>
            <div class="fld">
                <?php echo form_input(array(
                    'id'	=> 'meeting_url',
                    'value' => set_value('meeting_url', $details['meeting_url']),
                    'name'	=> 'meeting_url'
                )); ?>
                <div class="errormsg" id="err_meeting_url"><?php echo form_error('meeting_url'); ?></div>
            </div>
        </div>
        <br>
        <div class="field">
            <div class="fld">
                <?php echo form_checkbox(array(
                    'id'	=> 'status',
                    'checked' => set_value('status', $details['status']),
                    'name'	=> 'status'
                )); ?>
                <?php echo form_label('Forum enabled', 'status_label', array('class' => 'left_label')); ?>
                <div class="errormsg" id="err_status"><?php echo form_error('status'); ?></div>
            </div>
        </div>
        <div class="field">
            <div class="fld">
                <?php echo form_checkbox(array(
                    'id'	=> 'is_featured',
                    'checked' => set_value('is_featured', $details['is_featured']),
                    'name'	=> 'is_featured'
                )); ?>
                <?php echo form_label('Featured forum', 'is_featured_label', array('class' => 'left_label')); ?>
                <div class="errormsg" id="err_is_featured"><?php echo form_error('is_featured'); ?></div>
            </div>
        </div>
    </div>

    <div class="contenttitle2">
        <h3>Forum Venue</h3>
    </div>
    <br/>

    <div id="div_general_photo_form">
    <div class="field">
        <?php echo form_label('Venue:', '', array('class' => 'left_label')); ?>
        <div class="fld">
            <?php echo form_input(array(
                'id'	=> 'venue',
                'value' => set_value('venue', $details['venue']),
                'name'	=> 'venue'
            )); ?>
            <div class="errormsg" id="err_venue"><?php echo form_error('venue'); ?></div>
        </div>
    </div>
        <div class="field">
            <?php echo form_label('Venue URL:', '', array('class' => 'left_label')); ?>
            <div class="fld">
                <?php echo form_input(array(
                    'id'	=> 'venue_url',
                    'value' => set_value('venue_url', $details['venue_url']),
                    'name'	=> 'venue_url'
                )); ?>
                <div class="errormsg" id="err_venue_url"><?php echo form_error('venue_url'); ?></div>
            </div>
        </div>
    <div class="field">
        <?php echo form_label('Address:', '', array('class' => 'left_label')); ?>
        <div class="fld">
            <?php echo form_input(array(
                'id'	=> 'venue_address',
                'value' => set_value('venue_address', $details['venue_address']),
                'name'	=> 'venue_address'
            )); ?>
            <div class="errormsg" id="err_venue_address"><?php echo form_error('venue_address'); ?></div>
        </div>
    </div>
    <div class="field" style="float:left;">
        <?php echo form_label('Latitude:', '', array('class' => 'left_label')); ?>
        <div class="fld">
            <?php echo form_input(array(
                'id'	=> 'venue_lat',
                'value' => set_value('venue_lat', $details['venue_lat']),
                'name'	=> 'venue_lat'
            )); ?>
            <div class="errormsg" id="err_venue_lat"><?php echo form_error('venue_lat'); ?></div>
        </div>
    </div>
    <div class="field" style="float:left;margin-left: 15px;">
        <?php echo form_label('Longitude:', '', array('class' => 'left_label')); ?>
        <div class="fld">
            <?php echo form_input(array(
                'id'	=> 'venue_lng',
                'value' => set_value('venue_lng', $details['venue_lng']),
                'name'	=> 'venue_lng'
            )); ?>
            <div class="errormsg" id="err_venue_lng"><?php echo form_error('venue_lng'); ?></div>
        </div>
    </div>
    <div style="clear:both;"></div>
    </div>

    <div class="contenttitle2">
        <h3>Forum Description</h3>
    </div>
    <br/>
    <div>
        <?php echo form_textarea(array(
            'type' => 'text',
            'class' => 'tinymce',
            'id' => 'content',
            'name' => 'content',
            'value' => set_value('content', $details['content'], false),
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
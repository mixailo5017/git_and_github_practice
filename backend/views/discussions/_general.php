<div id="general_tab_form" class="clearfix">
    <?php
    echo form_open(current_url(), array(
        'name' => 'discusion_general_form',
        'class' => 'ajax_add_form'
    ));
    echo form_hidden('update', 'general');
    ?>

    <div class="contenttitle2">
        <h3>Discussion Details</h3>
    </div>
    <br/>
    <div id="div_general_photo_form" class="edit-discussion discussion-container">
        <div class="field">
            <?php echo form_label('Title:', 'title_label', array('class' => 'left_label')); ?>
            <div class="fld">
                <?php echo form_input('title', set_value('title', $discussion['title'])) ?>
                <div class="errormsg" id="err_title"><?php echo form_error('title'); ?></div>
            </div>
        </div>
        <div class="field">
            <?php echo form_label('Description:', 'description_label', array('class' => 'left_label')); ?>
            <div class="fld">
                <?php echo form_textarea('description', set_value('description', $discussion['description'])) ?>
                <div class="errormsg" id="err_title"><?php echo form_error('description'); ?></div>
            </div>
        </div>
    </div>

    <br/>
    <div>
        <?php echo form_submit('submit', 'Update', 'class="light_green no_margin_left"'); ?>
    </div>
    <?php echo form_close(); ?>
</div>
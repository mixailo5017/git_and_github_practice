<div class="centercontent">
    <div class="pageheader notab">
        <h1 class="pagetitle">Add New Discussion</h1>
        <span class="pagedesc">&nbsp;</span>
    </div>

    <div id="contentwrapper" class="contentwrapper discussion-container">
        <?php echo form_open(current_url(), array('name' => 'create_form')); ?>

        <div id="div_general_photo_form">
            <div class="field">
                <?php echo form_label('Project:', 'project_id_label', array('class' => 'left_label')) ?>
                <div class="fld">
                    <?php echo form_dropdown('project_id', $projects, set_value('project_id')) ?>
                    <div class="errormsg" id="err_project_id"><?php echo form_error('project_id') ?></div>
                </div>
            </div>

            <div class="field">
                <?php echo form_label('Title:', 'title_label', array('class' => 'left_label')); ?>
                <div class="fld">
                    <?php echo form_input(array(
                        'value' => set_value('title'),
                        'name'	=> 'title',
                        'placeholder' => 'Main discussion'
                    )); ?>
                    <div class="errormsg" id="err_title"><?php echo form_error('title') ?></div>
                </div>
            </div>

            <div class="field">
                <?php echo form_label('Description:', 'description_label', array('class' => 'left_label')); ?>
                <div class="fld">
                    <?php echo form_textarea('description', set_value('description')) ?>
                    <div class="errormsg" id="err_title"><?php echo form_error('description') ?></div>
                </div>
            </div>

        </div>

        <br/>

        <div>
            <?php
            echo form_submit('submit', 'Add New Discussion', 'class="light_green no_margin_left"');
            echo form_button('cancel', 'Cancel', 'class="light_gray no_margin_left" style="margin-left:10px;" onclick="window.location.href=\'/admin.php/discussions\'"');
            ?>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
    jQuery("form[name=create_form] select").chosen();
</script>
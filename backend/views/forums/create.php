<div class="centercontent">
    <div class="pageheader notab">
        <h1 class="pagetitle">Add New Forum</h1>
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
                        'value' => set_value('title'),
                        'name'	=> 'title',
                        'placeholder' => 'Forum title (e.g. 8th Global Infrastructure Leadership Forum)'
                    )); ?>
                    <div class="errormsg" id="err_title"><?php echo form_error('title'); ?></div>
                </div>
            </div>

            <div class="field">
                <?php echo form_label('Category:', 'category_id_label', array('class' => 'left_label')); ?>
                <div class="fld">
                    <?php echo form_dropdown('category_id', $categories, set_value('category_id')); ?>
                    <div class="errormsg" id="err_category_id"><?php echo form_error('category_id'); ?></div>
                </div>
            </div>
        </div>

        <br/>

        <div>
        <?php
            echo form_submit('submit', 'Add New Forum', 'class="light_green no_margin_left"');
            echo form_button('cancel', 'Cancel', 'class="light_gray no_margin_left" style="margin-left:10px;" onclick="window.location.href=\'/admin.php/forums\'"');
        ?>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<div id="content" class="clearfix">
    <div id="col5" class="center_col add-new">
        <h1 class="col_top gradient"><?php echo lang('DiscussionNew') ?></h1>

        <?php echo form_open('', array('name' => 'new_discussion')) ?>
            <div>
                <?php echo form_label(lang('Title'), 'title', array('class' => 'left_label')) ?>
                <div class="fld">
                    <?php echo form_input('title', set_value('title')) ?>
                    <div class="errormsg" id="err_title"><?php echo form_error('title') ?></div>
                </div>
            </div>
            <br/>
            <div>
                <?php echo form_label(lang('Description'), 'description', array('class' => 'left_label')) ?>
                <div class="fld">
                    <?php echo form_textarea('description', set_value('description')) ?>
                    <div class="errormsg" id="err_description"><?php echo form_error('description') ?></div>
                </div>
            </div>

            <div>
                <?php echo form_submit('submit', lang('DiscussionAddNew'), 'class="light_green left mt"') ?>
                <button name="cancel" type="button" class="light_gray left mt lmol"
                        onclick="window.location.href='/projects/<?php echo $project['pid'] ?>'">Cancel</button>
            </div>
        <?php echo form_close() ?>
    </div>
</div>
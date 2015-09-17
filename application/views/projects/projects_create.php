<div id="content" class="clearfix">
	<div id="col5" class="center_col">
        <h1 class="col_top gradient"><?php echo lang('AddNewProject') ?></h1>
		<?php echo form_open('projects/create', array('id' => 'new_project')) ?>
			<div>
				<?php echo form_label(lang('NameofProject') . ':', 'project_name', array('class' => 'left_label')) ?>
				<div class="fld">
					<?php echo form_input('title', set_value('title'), 'placeholder="' . lang('createProjectEx') . '"'); ?>
					<div class="errormsg"><?php echo form_error('title'); ?></div>
				</div>
			</div>
			<br/>
			<div>
				<?php echo form_submit('create_project', lang('Create my project'), 'class="light_green left mt"') ?>
				<?php echo form_button('cancel', lang('Cancel'), 'class="light_gray left mt lmol" onclick="window.location.href=\'/projects\'"') ?>
			</div>
		<?php echo form_close() ?>
	</div>
</div>
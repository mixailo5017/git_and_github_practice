<div class="centercontent">
	<div class="pageheader notab">
        <h1 class="pagetitle">Create Project</h1>
        <span class="pagedesc">&nbsp;</span>
	</div>
	<div id="contentwrapper" class="contentwrapper">
		
		<?php echo form_open_multipart("projects/create",array("id"=>"new_project")); ?>
			<?php
				$opt = array(
					'lbl_projectname' => array(
		              	'class' => 'left_label'
	            	),
	            	'lbl_username' => array(
		              	'class' => 'left_label'
	            	),
	            	'title'	=> array(
	            		'name'  => 'title',
	            		'id'	=> 'project_name',
			            'value' => set_value("title"),
			            'placeholder' => 'Example: Expansion of Costa Rica\'s Pacific Port of Caldera'
			        ),
			        'submit' => array(
			        	'name' => 'create_project',
			        	'value' => 'Create my project',
			        	'class' => 'light_green left mt'
			        ),
			        'cancel' => array(
			        	'name' => 'cancel',
			        	'class' => 'light_gray left mt lmol',
			        	'onclick' => 'window.location.href=\'/admin.php/projects/view_all_projects\'',
			        	'content' => 'Cancel'
			        )
				);
			?>
			<div>
			<?php echo form_label("User:","project_users",$opt["lbl_username"]); ?>
			<div class="fld">
				<?php  
					$project_users_attr = 'id="project_users" class="chzn-select"';
					$project_users_options = get_all_projusers_dropdown();
					echo form_dropdown('project_users', $project_users_options,'',$project_users_attr);
				?>
				<div class="errormsg"><?php echo form_error("project_users"); ?></div>
			</div>
			</div>
			<?php echo br(); ?>

			<div>
				<?php echo form_label("Name of Project:","project_name",$opt["lbl_projectname"]); ?>
				<div class="fld">
					<?php echo form_input($opt["title"]); ?>
					<div class="errormsg"><?php echo form_error("title"); ?></div>
				</div>
			</div>
			<?php echo br(); ?>
			
				
			<div>
				<?php echo form_submit($opt["submit"]); ?>
				<?php echo form_button($opt["cancel"]); ?>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
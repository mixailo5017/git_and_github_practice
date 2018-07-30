<div id="profile_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all project_form" style="display: block;">

	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-1"><?php echo lang('Files');?></a></li>
	
	</ul>


	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-1">

		<div class="clearfix matrix_dropdown project_matrixfiles">
		
		<ul id="load_files_form">
				<?php
				
				foreach($project["files"] as $key=>$val)
				{
				?>
				<li class="" id="row_id_<?php echo $val["id"]; ?>">
					<div class="view clearfix">
					
					<?php if($val['file']!= ''){ ?>
			
						<a href="<?php echo PROJECT_IMAGE_PATH.$val['file'];?>" class="left files" target="_blank">
							<img src="/images/icons/<?php echo filetypeIcon($val['file']);?>" alt="file" title="file">
						</a>
					
					<?php } ?>

					
						
						<span class="left"></span>
						<span class="left middle">
							<strong><?php echo $val["file"]; ?></strong> (<?php echo ceil($val["filesize"])."KB"; ?>)<br>
							<?php echo $val["description"]; ?>
						</span>
						
						<span class="left middle">

							<?php $filedate = new DateTime($val['dateofuploading']); 
							
							echo $filedate->format('M d,Y');?>
							<br>
							
						</span>


						<a class="right delete" href="#projects/delete_project_files"><?php echo lang('Delete');?></a>

						<a class="right edit" id="edit_project_files_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

					</div>
					<div class="edit">
						<?php echo form_open('projects/update_project_files/'.$slug,array('id'=>'update_project_files_form_'.$val["id"],'name'=>'update_project_files_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>
						
						<?php 
							
							$opt['update_project_files_form'] = array(
								'lbl_filename' => array(
										'class' => 'left_label'
										),
								'project_files_filename'	=> array(
										'name' 		=> 'project_files_filename',
										'id' 		=> 'project_files_filename',
										'value'		=> $val["file"]
										),
								'lbl_description' => array(
										'class' => 'left_label'
										),
								'project_files_desc'	=> array(
										'name' 		=> 'project_files_desc',
										'id' 		=> 'project_files_desc',
										'value'		=> $val["description"]
										),
								'lbl_permission' => array(
										'class' => 'left_label'
										)
								);
	
							?>


							<?php echo form_hidden("hdn_project_files_id",$val["id"]); ?>
							<?php echo form_hidden("project_files_filenam_hidden",$val["file"]); ?>
							
							<?php echo form_label(lang('File').':', 'project_files_filename', $opt['update_project_files_form']['lbl_filename']);?>
							<div class="fld" style="width:500px;">
	
								<?php echo form_upload($opt['update_project_files_form']['project_files_filename']);?>
								<div class="errormsg"></div>
							</div>
							
							<?php echo form_label(lang('Description').':', 'project_files_desc', $opt['update_project_files_form']['lbl_description']);?>
							<div class="fld" style="width:500px;">
	
								<?php echo form_input($opt['update_project_files_form']['project_files_desc']);?>
								<div class="errormsg"></div>
							</div>
							
							<?php echo form_label(lang('Permission').':', 'project_files_permission', $opt['update_project_files_form']['lbl_permission']);?>
							<div class="fld" style="width:500px;">
	
								<?php
									$permissions_attr = 'id="project_files_permission"';
									$permissions_options = array(
										'All'		=> 'All',
										'Some' 		=> 'Some',
										'Other' 	=> 'Other'
									);
									echo form_dropdown('project_files_permissions', $permissions_options,$val['permission'],$permissions_attr);
								?>
								<div class="errormsg"></div>
							</div>
																
							<?php echo form_submit('ufiles_submit', lang('Update'),'class = "light_green btn_lml"');?>
							
							<?php echo form_close();?>
					</div>
				</li>
				<?php
				}
				?>
		
		</ul>
			<ul>
				<li>
					<div class="view">
						<?php //lable like=>+ Add New File?>
						<a class="edit project_row_add" href="javascript:void(0);" id="add_newfile" onclick="rowtoggle(this.id);"><?php echo "+ ".lang('AddNewFile');?></a>

					</div>

					<div class="edit add_new">
						
						<?php echo form_open('projects/add_project_files/'.$slug,array('id'=>'files_form','name'=>'files_form','method'=>'post','class'=>'ajax_form'));?>	
						<?php 
							$opt['files_form'] = array(
									'lbl_file' => array(
											'class' => 'left_label'
											),
									'project_files_filename'	=> array(
											'name' 		=> 'project_files_filename',
											'id' 		=> 'project_files_filename'
											),
									'lbl_desc' => array(
											'class' => 'left_label'
											),
									'project_files_desc'	=> array(
											'name' 		=> 'project_files_desc',
											'id' 		=> 'project_files_desc'
											),
									'lbl_permissions' => array(
											'class' => 'left_label'
											)
								);

						?>
						
						<?php echo form_label(lang('File').':', '', $opt['files_form']['lbl_file']);?>
						<div class="fld">
							<?php echo form_upload($opt['files_form']['project_files_filename']);?>
							<div id="err_project_files_filename" class="errormsg"></div>
						</div>
						<br>

						<?php echo form_label(lang('Description').':', '', $opt['files_form']['lbl_desc']);?>
						<div class="fld">
							<?php echo form_input($opt['files_form']['project_files_desc']);?>
							<div id="err_project_files_desc" class="errormsg"></div>
						</div>
						<br>

						<?php echo form_label(lang('Permissions').':', '', $opt['files_form']['lbl_permissions']);?>
						<?php
							$files_permission_attr = "id='files_permission'";
							$files_permission_options = array(
								"All"	=> lang("All"),
								"Some"	=> lang("Some"),
								"Other"	=> lang("Other")
							);
							echo form_dropdown("files_permission",$files_permission_options,'',$files_permission_attr);
						?>
						<br>

						<?php echo form_submit('files_submit', lang('AddNew'),'class = "light_green btn_lml"');?>
						
						<?php echo form_close(); ?>

					</div>

				</li>
			</ul>

		</div>

	</div>

</div>
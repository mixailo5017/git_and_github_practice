<div id="profile_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all project_form" style="display: block;">

	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-2"><?php echo lang('Engineering');?></a></li>
		<li class="ui-state-default ui-corner-top"><a href="#tabs-3"><?php echo lang('DesignIssues');?></a></li>
		<li class="ui-state-default ui-corner-top"><a href="#tabs-4"><?php echo lang('Environment');?></a></li>
		<li class="ui-state-default ui-corner-top"><a href="#tabs-5"><?php echo lang('OtherStudies');?></a></li>
		<li class="ui-state-default ui-corner-top"><a href="#tabs-6"><?php echo lang('Legal');?></a></li>
	</ul>


	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-2" style="">

		<div class="clearfix matrix_dropdown project_engineering" id="project_form">
			<ul id="load_engineering_form">
							<?php

							foreach($project["engineering"] as $key=>$val)
							{
							?>
							<li class="" id="row_id_<?php echo $val["id"]; ?>">
								<div class="view clearfix">

									<span class="left"><?php echo $val["role"]; ?></span>

									<span class="left middle">
										<strong><?php echo $val["contactname"]; ?></strong>
										<br>
										<?php echo $val["challenges"].", ".$val["innovations"]; ?>
									</span>

									<a class="right delete" href="#projects/delete_engineering"><?php echo lang('Delete');?></a>

									<a class="right edit" id="edit_engineering_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

									<?php if($val['schedule']!= ''){ ?>

										<a href="<?php echo PROJECT_IMAGE_PATH.$val['schedule'];?>" class="right files" target="_blank">
											<img src="/images/icons/<?php echo filetypeIcon($val['schedule']);?>" alt=<?php echo lang("file");?> title=<?php echo lang("file");?>>
										</a>

									<?php	}  ?>

								</div>

								<div class="edit">
									<?php echo form_open('projects/update_engineering/'.$slug,array('id'=>'update_engineering_form_'.$val["id"],'name'=>'update_engineering_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>

										<?php

										$opt['update_engineering_form'] = array(
														'lbl_company' => array(
																'class' => 'left_label'
																),
														'project_engineering_company'	=> array(
																'name' 		=> 'project_engineering_company',
																'id' 		=> 'project_engineering_company',
																'value'		=> $val["company"]
																),
														'lbl_role' => array(
																'class' => 'left_label'
																),
														'project_engineering_role'	=> array(
																'name' 		=> 'project_engineering_role',
																'id' 		=> 'project_engineering_role',
																'value'		=> $val["role"]
																),
														'lbl_cname' => array(
																'class' => 'left_label'
																),
														'project_engineering_cname'	=> array(
																'name' 		=> 'project_engineering_cname',
																'id' 		=> 'project_engineering_cname',
																'value'		=> $val["contactname"]
																),
														'lbl_challenges' => array(
																'class' => 'left_label'
																),
														'project_engineering_challenges'	=> array(
																'name' 		=> 'project_engineering_challenges',
																'id' 		=> 'project_engineering_challenges',
																'value'		=> $val["challenges"]
																),
														'lbl_innovations' => array(
																'class' => 'left_label'
																),
														'project_engineering_innovations'	=> array(
																'name' 		=> 'project_engineering_innovations',
																'id' 		=> 'project_engineering_innovations',
																'value'		=> $val["innovations"]
																),
														'lbl_schedule' => array(
																'class' => 'left_label'
																),
														'project_engineering_schedule'	=> array(
																'name' 		=> 'project_engineering_schedule',
																'id' 		=> 'project_engineering_schedule',
																'value'		=> $val["schedule"]
																),
														'lbl_permissions' => array(
																'class' => 'left_label'
																),
											);

										?>

										<?php echo form_hidden("hdn_project_engineering_id",$val["id"]); ?>
										<?php echo form_hidden("project_engineering_schedul_hidden",$val["schedule"]); ?>

										<?php echo form_label(lang('Company').':', 'project_engineering_company', $opt['update_engineering_form']['lbl_company']);?>
										<div class="fld" style="width:500px;">

											<?php echo form_input($opt['update_engineering_form']['project_engineering_company']);?>
											<div id="err_project_engineering_company" class="errormsg"></div>
										</div>

										<?php echo form_label(lang('Role').':', 'project_engineering_role', $opt['update_engineering_form']['lbl_role']);?>
										<div class="fld" style="width:500px;">

											<?php echo form_input($opt['update_engineering_form']['project_engineering_role']);?>
											<div id="err_project_engineering_role" class="errormsg"></div>
										</div>

										<?php echo form_label(lang('ContactName').':', 'project_engineering_cname', $opt['update_engineering_form']['lbl_cname']);?>
										<div class="fld" style="width:500px;">

											<?php echo form_input($opt['update_engineering_form']['project_engineering_cname']);?>
											<div id="err_project_engineering_cname" class="errormsg"></div>
										</div>

										<?php echo form_label(lang('Challenges').':', 'project_engineering_challenges', $opt['update_engineering_form']['lbl_challenges']);?>
										<div class="fld" style="width:500px;">

											<?php echo form_input($opt['update_engineering_form']['project_engineering_challenges']);?>
											<div id="err_project_engineering_challenges" class="errormsg"></div>
										</div>

										<?php echo form_label(lang('Innovations').':', 'project_engineering_innovations', $opt['update_engineering_form']['lbl_innovations']);?>
										<div class="fld" style="width:500px;">

											<?php echo form_input($opt['update_engineering_form']['project_engineering_innovations']);?>
											<div id="err_project_engineering_innovations" class="errormsg"></div>
										</div>

										<?php echo form_label(lang('Schedule').':', 'project_engineering_schedule', $opt['update_engineering_form']['lbl_schedule']);?>
										<div class="fld" style="width:500px;">

											<?php echo form_upload($opt['update_engineering_form']['project_engineering_schedule']);?>
											<div id="err_project_engineering_schedule" class="errormsg"></div>
										</div>

										<?php echo form_label(lang('Permissions').':', 'project_engineering_permissions', $opt['update_engineering_form']['lbl_permissions']);?>
										<?php
											$permissions_attr = 'id="project_engineering_permissions"';
											$permissions_options = array(
												'All'		=> lang('All'),
												'Some' 		=> lang('Some'),
												'Other' 	=> lang('Other')
											);
											echo form_dropdown('project_engineering_permissions', $permissions_options,$val['permission'],$permissions_attr);
										?>
										<br>

										<?php echo form_submit('uengineering_submit', lang('Update'),'class = "light_green btn_lml"');?>

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

						<a class="edit project_row_add" href="javascript:void(0);" id="add_engineering" onclick="rowtoggle(this.id);">+ <?php echo lang('AddEngineeringFundamental');//lable like=> + Add Engineering Fundamental?></a>

					</div>

					<div class="edit add_new">

						<?php echo form_open('projects/add_engineering/'.$slug,array('id'=>'engineering_form','name'=>'engineering_form','method'=>'post','class'=>'ajax_form'));?>

						<?php

						$opt['engineering_form'] = array(
										'lbl_company' => array(
												'class' => 'left_label'
												),
										'project_engineering_company'	=> array(
												'name' 		=> 'project_engineering_company',
												'id' 		=> 'project_engineering_company'
												),
										'lbl_role' => array(
												'class' => 'left_label'
												),
										'project_engineering_role'	=> array(
												'name' 		=> 'project_engineering_role',
												'id' 		=> 'project_engineering_role'
												),
										'lbl_cname' => array(
												'class' => 'left_label'
												),
										'project_engineering_cname'	=> array(
												'name' 		=> 'project_engineering_cname',
												'id' 		=> 'project_engineering_cname'
												),
										'lbl_challenges' => array(
												'class' => 'left_label'
												),
										'project_engineering_challenges'	=> array(
												'name' 		=> 'project_engineering_challenges',
												'id' 		=> 'project_engineering_challenges'
												),
										'lbl_innovations' => array(
												'class' => 'left_label'
												),
										'project_engineering_innovations'	=> array(
												'name' 		=> 'project_engineering_innovations',
												'id' 		=> 'project_engineering_innovations'
												),
										'lbl_schedule' => array(
												'class' => 'left_label'
												),
										'project_engineering_schedule'	=> array(
												'name' 		=> 'project_engineering_schedule',
												'id' 		=> 'project_engineering_schedule'
												),
										'lbl_permissions' => array(
												'class' => 'left_label'
												),
							);

						?>


						<?php echo form_label(lang('Company').':', 'project_engineering_company', $opt['engineering_form']['lbl_company']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_input($opt['engineering_form']['project_engineering_company']);?>
							<div id="err_project_engineering_company" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Role').':', 'project_engineering_role', $opt['engineering_form']['lbl_role']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_input($opt['engineering_form']['project_engineering_role']);?>
							<div id="err_project_engineering_role" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('ContactName').':', 'project_engineering_cname', $opt['engineering_form']['lbl_cname']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_input($opt['engineering_form']['project_engineering_cname']);?>
							<div id="err_project_engineering_cname" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Challenges').':', 'project_engineering_challenges', $opt['engineering_form']['lbl_challenges']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_input($opt['engineering_form']['project_engineering_challenges']);?>
							<div id="err_project_engineering_challenges" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Innovations').':', 'project_engineering_innovations', $opt['engineering_form']['lbl_innovations']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_input($opt['engineering_form']['project_engineering_innovations']);?>
							<div id="err_project_engineering_innovations" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Schedule').':', 'project_engineering_schedule', $opt['engineering_form']['lbl_schedule']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_upload($opt['engineering_form']['project_engineering_schedule']);?>
							<div id="err_project_engineering_schedule" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Permissions').':', 'project_engineering_permissions', $opt['engineering_form']['lbl_permissions']);?>
						<?php
							$permissions_attr = 'id="project_engineering_permissions"';
							$permissions_options = array(
								'All'		=> lang('All'),
								'Some' 		=> lang('Some'),
								'Other' 	=> lang('Other')
							);
							echo form_dropdown('project_engineering_permissions', $permissions_options,'',$permissions_attr);
						?>
						<br>

						<?php echo form_submit('engineering_submit', lang('AddNew'),'class = "light_green btn_lml"');?>

						<?php echo form_close();?>
					</div>

				</li>
			</ul>

		</div>

	</div>

	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-3" style="">

		<div class="clearfix matrix_dropdown project_design_issues">

			<ul id="load_design_issue_form">
			<?php
			foreach($project["design_issue"] as $key=>$val)
			{
			?>

				<li class="" id="row_id_<?php echo $val["id"];?>">


					<div class="view clearfix">
						<span class="left"><?php echo lang('DesignIssues');?></span>

						<span class="left middle">
							<strong><?php echo $val["title"]; ?></strong><br><?php echo $val["description"]; ?>
						</span>

						<a class="right delete" href="#projects/delete_design_issue"><?php echo lang('Delete');?></a>

						<a class="right edit" id="edit_design_issue_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

						<?php if($val['attachment']!= ''){ ?>

							<a href="<?php echo PROJECT_IMAGE_PATH.$val['attachment'];?>" class="right files" target="_blank">
								<img src="/images/icons/<?php echo filetypeIcon($val['attachment']);?>" alt=<?php echo lang("file");?> title=<?php echo lang("file");?>>
							</a>

						<?php	}  ?>


					</div>

					<div class="edit">

						<?php echo form_open('projects/update_design_issue/'.$slug,array('id'=>'update_design_issue_form_'.$val["id"],'name'=>'update_design_issue_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>
							<?php

								$opt['update_design_issues_form'] = array(
												'lbl_title' => array(
														'class' => 'left_label'
														),
												'project_design_issues_title'	=> array(
														'name' 		=> 'project_design_issues_title',
														'id' 		=> 'project_design_issues_title',
														'value'		=> $val['title']
														),
												'lbl_description' => array(
														'class' => 'left_label'
														),
												'project_design_issues_desc'	=> array(
														'name' 		=> 'project_design_issues_desc',
														'id' 		=> 'project_design_issues_desc',
														'value'		=> $val['description']
														),
												'lbl_attachment' => array(
														'class' => 'left_label'
														),
												'project_design_issues_attachment'	=> array(
														'name' 		=> 'project_design_issues_attachment',
														'id' 		=> 'project_design_issues_attachment',
														'value'		=> $val['attachment']
														),
												'lbl_permissions' => array(
												'class' => 'left_label'
												)
										);
							?>

						<?php echo form_hidden("hdn_project_design_issues_id",$val["id"]); ?>
						<?php echo form_hidden("project_design_issues_attachmen_hidden",$val["attachment"]); ?>

						<?php echo form_label(lang('Title').':', 'project_design_issues_title', $opt['update_design_issues_form']['lbl_title']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_input($opt['update_design_issues_form']['project_design_issues_title']);?>
							<div id="err_project_design_issues_title" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Description').':', 'project_design_issues_desc', $opt['update_design_issues_form']['lbl_description']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_input($opt['update_design_issues_form']['project_design_issues_desc']);?>
							<div id="err_project_design_issues_description" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Attachment').':', 'project_design_issues_attachment', $opt['update_design_issues_form']['lbl_attachment']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_upload($opt['update_design_issues_form']['project_design_issues_attachment']);?>
							<div id="err_project_design_issues_attachment" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Permissions').':', 'project_design_issues_permissions', $opt['update_design_issues_form']['lbl_permissions']);?>
						<?php
							$design_issue_attr = 'id="project_design_issues_permissions"';
							$design_issue_options = array(
								'All'		=> lang('All'),
								'Some' 		=> lang('Some'),
								'Other' 	=> lang('Other')
							);
							echo form_dropdown('project_design_issues_permissions', $design_issue_options,$val["permission"],$design_issue_attr);
						?>
						<br>

						<?php echo form_submit('udesign_submit', lang('Update'),'class = "light_green btn_lml"');?>

						<?php echo form_close();?>


					</div>

				</li>


			<?php } ?>

			</ul>


			<ul>



				<li>

					<div class="view">

						<a class="edit project_row_add" href="javascript:void(0);" id="add_designissue" onclick="rowtoggle(this.id);">+ <?php echo lang('AddDesignIssue');//lable like=>+ Add Design Issue?></a>

					</div>

					<div class="edit add_new">

						<?php echo form_open('projects/add_design_issue/'.$slug,array('id'=>'design_issue_form','name'=>'design_issue_form','method'=>'post','class'=>'ajax_form'));?>
							<?php
								$opt['design_issues_form'] = array(
												'lbl_title' => array(
														'class' => 'left_label'
														),
												'project_design_issues_title'	=> array(
														'name' 		=> 'project_design_issues_title',
														'id' 		=> 'project_design_issues_title'
														),
												'lbl_description' => array(
														'class' => 'left_label'
														),
												'project_design_issues_desc'	=> array(
														'name' 		=> 'project_design_issues_desc',
														'id' 		=> 'project_design_issues_desc'
														),
												'lbl_attachment' => array(
														'class' => 'left_label'
														),
												'project_design_issues_attachment'	=> array(
														'name' 		=> 'project_design_issues_attachment',
														'id' 		=> 'project_design_issues_attachment'
														),
												'lbl_permissions' => array(
												'class' => 'left_label'
												)
										);
							?>


						<?php echo form_label(lang('Title').':', 'project_design_issues_title', $opt['design_issues_form']['lbl_title']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_input($opt['design_issues_form']['project_design_issues_title']);?>
							<div id="err_project_design_issues_title" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Description').':', 'project_design_issues_desc', $opt['design_issues_form']['lbl_description']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_input($opt['design_issues_form']['project_design_issues_desc']);?>
							<div id="err_project_design_issues_description" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Attachment').':', 'project_design_issues_attachment', $opt['design_issues_form']['lbl_attachment']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_upload($opt['design_issues_form']['project_design_issues_attachment']);?>
							<div id="err_project_design_issues_attachment" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Permissions').':', 'project_design_issues_permissions', $opt['design_issues_form']['lbl_permissions']);?>
						<?php
							$design_issue_attr = 'id="project_design_issues_permissions"';
							$design_issue_options = array(
								'All'		=> lang('All'),
								'Some' 		=> lang('Some'),
								'Other' 	=> lang('Other')
							);
							echo form_dropdown('project_design_issues_permissions', $design_issue_options,'',$design_issue_attr);
						?>
						<br>

						<?php echo form_submit('design_submit', lang('AddNew'),'class = "light_green btn_lml"');?>

						<?php echo form_close();?>


					</div>

				</li>

			</ul>

		</div>

	</div>


	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-4" style="">

		<div class="clearfix matrix_dropdown project_environment">
		<ul id="load_environment_form">


		<?php
			foreach($project["environment"] as $key=>$val)
			{
			?>
				<li class="" id="row_id_<?php echo $val["id"];?>">
				<div class="view clearfix">

					<span class="left"><?php echo lang('EnvironmentFile');?></span>

					<span class="left middle">
						<strong><?php echo $val["title"];?></strong><br><?php echo $val["description"];?>
					</span>

						<a class="right delete" href="#projects/delete_environment"><?php echo lang('Delete');?></a>

						<a class="right edit" id="edit_environment_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

						<?php if($val['attachment']!= ''){ ?>

							<a href="<?php echo PROJECT_IMAGE_PATH.$val['attachment'];?>" class="right files" target="_blank">
								<img src="/images/icons/<?php echo filetypeIcon($val['attachment']);?>" alt=<?php echo lang("file");?> title=<?php echo lang("file");?>>
							</a>

						<?php	}  ?>



				</div>
				<div class="edit">
						<?php echo form_open('projects/update_environment/'.$slug,array('id'=>'update_environment_form_'.$val["id"],'name'=>'update_environment_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>
							<?php
								$opt['update_environment_form'] = array(
												'lbl_env_title' => array(
														'class' => 'left_label'
														),
												'project_environment_title'	=> array(
														'name' 		=> 'project_environment_title',
														'id' 		=> 'project_environment_title',
														'value'		=> $val["title"]
														),
												'lbl_env_description' => array(
														'class' => 'left_label'
														),
												'project_environment_desc'	=> array(
														'name' 		=> 'project_environment_desc',
														'id' 		=> 'project_environment_desc',
														'value'		=> $val["description"]
														),
												'lbl_env_attachment' => array(
														'class' => 'left_label'
														),
												'project_environment_attachment'	=> array(
														'name' 		=> 'project_environment_attachment',
														'id' 		=> 'project_environment_attachment',
														'value'		=> $val["attachment"]
														),
												'lbl_env_permissions' => array(
												'class' => 'left_label'
												)
										);
							?>

							<?php echo form_hidden("hdn_project_environment_id",$val["id"]); ?>
						<?php echo form_hidden("project_environment_attachmen_hidden",$val["attachment"]); ?>
						<?php echo form_label(lang('Title').':', 'project_environment_title', $opt['update_environment_form']['lbl_env_title']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_input($opt['update_environment_form']['project_environment_title']);?>
							<div id="err_project_environment_title" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Description').':', 'project_environment_desc', $opt['update_environment_form']['lbl_env_description']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_input($opt['update_environment_form']['project_environment_desc']);?>
							<div id="err_project_environment_description" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Attachment').':', 'project_environment_attachment', $opt['update_environment_form']['lbl_env_attachment']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_upload($opt['update_environment_form']['project_environment_attachment']);?>
							<div id="err_project_environment_attachment" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Permissions').':', 'project_environment_permissions', $opt['update_environment_form']['lbl_env_permissions']);?>
						<?php
							$project_environment_attr = 'id="project_environment_permissions"';
							$project_environment_options = array(
								'All'		=> lang('All'),
								'Some' 		=> lang('Some'),
								'Other' 	=> lang('Other')
							);
							echo form_dropdown('project_environment_permissions', $project_environment_options,$val["permission"],$project_environment_attr);
						?>
						<br>

						<?php echo form_submit('uenvironment_submit', lang('Update'),'class = "light_green btn_lml"');?>

						<?php echo form_close();?>
						</div>
						</li>
				<?php } ?>

		</ul>
		<ul>
				<li>
					<div class="view">

						<a class="edit project_row_add" href="javascript:void(0);" id="add_environment" onclick="rowtoggle(this.id);">+ <?php echo lang('AddEnvironmentFile');//lable like=>+ Add Environment File?></a>

					</div>

					<div class="edit add_new">

						<?php echo form_open('projects/add_environment/'.$slug,array('id'=>'environment_form','name'=>'environment_form','method'=>'post','class'=>'ajax_form'));?>
							<?php
								$opt['environment_form'] = array(
												'lbl_env_title' => array(
														'class' => 'left_label'
														),
												'project_environment_title'	=> array(
														'name' 		=> 'project_environment_title',
														'id' 		=> 'project_environment_title'
														),
												'lbl_env_description' => array(
														'class' => 'left_label'
														),
												'project_environment_desc'	=> array(
														'name' 		=> 'project_environment_desc',
														'id' 		=> 'project_environment_desc'
														),
												'lbl_env_attachment' => array(
														'class' => 'left_label'
														),
												'project_environment_attachment'	=> array(
														'name' 		=> 'project_environment_attachment',
														'id' 		=> 'project_environment_attachment'
														),
												'lbl_env_permissions' => array(
												'class' => 'left_label'
												)
										);
							?>

						<?php echo form_label(lang('Title').':', 'project_environment_title', $opt['environment_form']['lbl_env_title']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_input($opt['environment_form']['project_environment_title']);?>
							<div id="err_project_environment_title" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Description').':', 'project_environment_desc', $opt['environment_form']['lbl_env_description']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_input($opt['environment_form']['project_environment_desc']);?>
							<div id="err_project_environment_description" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Attachment').':', 'project_environment_attachment', $opt['environment_form']['lbl_env_attachment']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_upload($opt['environment_form']['project_environment_attachment']);?>
							<div id="err_project_environment_attachment" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Permissions').':', 'project_environment_permissions', $opt['environment_form']['lbl_env_permissions']);?>
						<?php
							$project_environment_attr = 'id="project_environment_permissions"';
							$project_environment_options = array(
								'All'		=> lang('All'),
								'Some' 		=> lang('Some'),
								'Other' 	=> lang('Other')
							);
							echo form_dropdown('project_environment_permissions', $project_environment_options,'',$project_environment_attr);
						?>
						<br>

						<?php echo form_submit('environment_submit', lang('AddNew'),'class = "light_green btn_lml"');?>

						<?php echo form_close();?>

					</div>

				</li>

			</ul>

		</div>

	</div>


	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-5" style="">
		<div class="clearfix matrix_dropdown project_studies">
            <ul id="load_project_studies_form">
                <?php foreach($project["studies"] as $key=>$val) { ?>
                <li class="" id="row_id_<?php echo $val["id"];?>">
                    <div class="view clearfix">
                        <span class="left"><?php echo lang('StudyFile');?></span>
                        <span class="left middle">
                            <strong><?php echo $val["title"]; ?></strong><br><?php echo $val["description"]; ?>
                        </span>

                        <a class="right delete" href="#projects/delete_studies"><?php echo lang('Delete');?></a>
                        <a class="right edit" id="edit_studies_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

                        <?php if ($val['attachment']!= ''){ ?>
                            <a href="<?php echo PROJECT_IMAGE_PATH.$val['attachment'];?>" class="right files" target="_blank">
                                <img src="/images/icons/<?php echo filetypeIcon($val['attachment']);?>" alt=<?php echo lang("file");?> title=<?php echo lang("file");?>>
                            </a>
                        <?php } ?>
                    </div>

                    <div class="edit add_new">
                        <?php echo form_open('projects/update_studies/' . $slug, array(
                            'id' => 'update_project_studies_form_' . $val["id"],
                            'name' => 'update_project_studies_form_' . $val["id"],
                            'method' => 'post',
                            'class' => 'ajax_form')) ?>
                            <?php
                                $opt['update_project_studies_form'] = array(
                                                'lbl_std_title' => array(
                                                        'class' => 'left_label'
                                                        ),
                                                'project_studies_title'	=> array(
                                                        'name' 		=> 'project_studies_title',
                                                        'id' 		=> 'project_studies_title',
                                                        'value'		=> $val["title"]
                                                        ),
                                                'lbl_std_description' => array(
                                                        'class' => 'left_label'
                                                        ),
                                                'project_studies_desc'	=> array(
                                                        'name' 		=> 'project_studies_desc',
                                                        'id' 		=> 'project_studies_desc',
                                                        'value'		=> $val["description"]
                                                        ),
                                                'lbl_std_attachment' => array(
                                                        'class' => 'left_label'
                                                        ),
                                                'project_studies_attachment'	=> array(
                                                        'name' 		=> 'project_studies_attachment',
                                                        'id' 		=> 'project_studies_attachment',
                                                        'value'		=> $val["attachment"]
                                                        ),
                                                'lbl_std_permissions' => array(
                                                'class' => 'left_label'
                                                )
                                        );
                            ?>

                            <?php echo form_hidden("hdn_project_studies_id", $val["id"]); ?>
                            <?php echo form_hidden("project_studies_attachmen_hidden", $val["attachment"]); ?>

                            <?php echo form_label(lang('Title').':', 'project_studies_title', $opt['update_project_studies_form']['lbl_std_title']);?>
                            <div class="fld" style="width:500px;">
                                <?php echo form_input($opt['update_project_studies_form']['project_studies_title']);?>
                                <div id="err_project_studies_title" class="errormsg"></div>
                            </div>

                            <?php echo form_label(lang('Description').':', 'project_studies_desc', $opt['update_project_studies_form']['lbl_std_description']);?>
                            <div class="fld" style="width:500px;">
                                <?php echo form_input($opt['update_project_studies_form']['project_studies_desc']);?>
                                <div id="err_project_studies_description" class="errormsg"></div>
                            </div>

                            <?php echo form_label(lang('Attachment').':', 'project_studies_attachment', $opt['update_project_studies_form']['lbl_std_attachment']);?>
                            <div class="fld" style="width:500px;">
                                <?php echo form_upload($opt['update_project_studies_form']['project_studies_attachment']);?>
                                <div id="err_project_studies_attachment" class="errormsg"></div>
                            </div>

                            <?php echo form_label(lang('Permissions').':', 'project_studies_permissions', $opt['update_project_studies_form']['lbl_std_permissions']);?>
                            <?php
                                $project_studies_attr = 'id="project_studies_permissions"';
                                $project_studies_options = array(
                                    'All'		=> lang('All'),
                                    'Some' 		=> lang('Some'),
                                    'Other' 	=> lang('Other')
                                );
                                echo form_dropdown('project_studies_permissions', $project_studies_options,$val["permission"],$project_studies_attr);
                            ?>
                            <br>

                            <?php echo form_submit('ustudies_submit', lang('Update'), 'class = "light_green btn_lml"');?>
                            <?php echo form_close();?>
                        </div>
                </li>
                <?php }	?>
            </ul>
            <ul>
                <li>
                    <div class="view">
                        <a class="edit project_row_add" href="javascript:void(0);" id="add_projstudies" onclick="rowtoggle(this.id);">+ <?php echo lang('AddStudyFile');//lable like=>+ Add Study File?></a>
                    </div>

					<div class="edit add_new">
						<?php echo form_open('projects/add_studies/' . $slug, array(
                            'id' => 'project_studies_form',
                            'name' => 'project_studies_form',
                            'method' => 'post',
                            'class' => 'ajax_form')) ?>
							<?php
								$opt['project_studies_form'] = array(
												'lbl_std_title' => array(
														'class' => 'left_label'
														),
												'project_studies_title'	=> array(
														'name' 		=> 'project_studies_title',
														'id' 		=> 'project_studies_title'
														),
												'lbl_std_description' => array(
														'class' => 'left_label'
														),
												'project_studies_desc'	=> array(
														'name' 		=> 'project_studies_desc',
														'id' 		=> 'project_studies_desc'
														),
												'lbl_std_attachment' => array(
														'class' => 'left_label'
														),
												'project_studies_attachment'	=> array(
														'name' 		=> 'project_studies_attachment',
														'id' 		=> 'project_studies_attachment'
														),
												'lbl_std_permissions' => array(
												'class' => 'left_label'
												)
										);
							?>

						<?php echo form_label(lang('Title').':', 'project_studies_title', $opt['project_studies_form']['lbl_std_title']);?>
						<div class="fld" style="width:500px;">
							<?php echo form_input($opt['project_studies_form']['project_studies_title']);?>
							<div id="err_project_studies_title" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Description').':', 'project_studies_desc', $opt['project_studies_form']['lbl_std_description']);?>
						<div class="fld" style="width:500px;">
							<?php echo form_input($opt['project_studies_form']['project_studies_desc']);?>
							<div id="err_project_studies_description" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Attachment').':', 'project_studies_attachment', $opt['project_studies_form']['lbl_std_attachment']);?>
						<div class="fld" style="width:500px;">
							<?php echo form_upload($opt['project_studies_form']['project_studies_attachment']);?>
							<div id="err_project_studies_attachment" class="errormsg"></div>
						</div>

						<?php echo form_label(lang('Permissions').':', 'project_studies_permissions', $opt['project_studies_form']['lbl_std_permissions']);?>
						<?php
							$project_studies_attr = 'id="project_studies_permissions"';
							$project_studies_options = array(
								'All'		=> lang('All'),
								'Some' 		=> lang('Some'),
								'Other' 	=> lang('Other')
							);
							echo form_dropdown('project_studies_permissions', $project_studies_options,'',$project_studies_attr);
						?>
						<br>
						<?php echo form_submit('studies_submit', lang('AddNew'),'class = "light_green btn_lml"');?>

						<?php echo form_close();?>
					</div>
				</li>
			</ul>
		</div>
	</div>


	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-6" style="">
		<div class="clearfix">
			<?php echo form_open('projects/add_legal/' . $slug, array(
                'id' => 'project_legal_form',
                'name' => 'project_legal_form',
                'method' => 'post',
                'class' => 'ajax_form topupdate')) ?>

				<?php
                    echo form_label(lang('Info').':', 'project_legal', array('class' => 'above_label'));
					$legalinfo = empty($project['fundamental_legal']) ? '' : $project['fundamental_legal'];
                    echo form_textarea(array('id' => 'project_legal', 'name' => 'project_legal', 'rows' => '10', 'cols' => '30', 'value' => $legalinfo));
                ?>
			<?php echo form_close();?>

		</div>

	</div>
</div>

<script>
	var isAdmin = true;
	var slug = '<?php echo $slug; ?>';
</script>

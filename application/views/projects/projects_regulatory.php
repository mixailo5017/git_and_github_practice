
<div id="profile_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all project_form" style="display: block;">

				<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
					<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-1"><?php echo lang('Regulatory');?></a></li>
				
				</ul>

		
				<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-1">

					<div class="clearfix matrix_dropdown project_regulatory">
						
						<ul id="load_regulatory_form">
							<?php
							
							foreach($project["regulatory"] as $key=>$val)
							{
							?>
							<li class="" id="row_id_<?php echo $val["id"]; ?>">
								<div class="view clearfix">
									
							<?php /* 		<span class="left"><?php echo $val["file"]; ?></span> */ ?>
                            <?php if($val['file']!= ''){ ?>
                            <a href="<?php echo PROJECT_IMAGE_PATH.$val["file"];?>" class="left files" target="_blank">
								<img src="/images/icons/<?php echo filetypeIcon($val["file"]);?>" alt="file" title="file">
							</a>
								

                            <?php } ?>

                            <?php if($val['description']!= ''){ ?>
								<span class="file-description"><?php echo $val["description"]; ?></span>
							<?php } ?>

									<a class="right delete" href="#projects/delete_regulatory"><?php echo lang('Delete');?></a>

									<a class="right edit" id="edit_regulatory_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

								</div>
								<div class="edit">
									<?php echo form_open('projects/update_regulatory/'.$slug,array('id'=>'update_regulatory_form_'.$val["id"],'name'=>'update_regulatory_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>
									
									<?php 
										
										$opt['update_regulatory_form'] = array(
											'lbl_filename' => array(
													'class' => 'left_label'
													),
											'project_regulatory_filename'	=> array(
													'name' 		=> 'project_regulatory_filename',
													'id' 		=> 'project_regulatory_filename',
													'value'		=> $val["file"]
													),
											'lbl_description' => array(
													'class' => 'left_label'
													),
											'project_regulatory_desc'	=> array(
													'name' 		=> 'project_regulatory_desc',
													'id' 		=> 'project_regulatory_desc',
													'value'		=> $val["description"]
													),
											'lbl_permission' => array(
													'class' => 'left_label'
													)
											);
				
										?>


										<?php echo form_hidden("hdn_project_regulatory_id",$val["id"]); ?>
										<?php echo form_hidden("project_regulatory_filenam_hidden",$val["file"]); ?>
										
										
										<?php echo form_label(lang('Description').':', 'project_regulatory_desc', $opt['update_regulatory_form']['lbl_description']);?>
										<div class="fld" style="width:500px;">
				
											<?php echo form_input($opt['update_regulatory_form']['project_regulatory_desc']);?>
											<div class="errormsg"></div>
										</div>
										<br>
										<?php echo form_label(lang('File').':', 'project_regulatory_filename', $opt['update_regulatory_form']['lbl_filename']);?>
										<div class="fld" style="width:500px;">
				
											<?php echo form_upload($opt['update_regulatory_form']['project_regulatory_filename']);?>
											<div class="errormsg"></div>
										</div>
										<br>
										
										<?php echo form_label(lang('Permission').':', 'project_regulatory_permission', $opt['update_regulatory_form']['lbl_permission']);?>
										<div class="fld" style="width:500px;">
				
											<?php
												$permissions_attr = 'id="project_regulatory_permission"';
												$permissions_options = array(
													'All'		=> lang('All'),
													'Some' 		=> lang('Some'),
													'Other' 	=> lang('Other')
												);
												echo form_dropdown('project_regulatory_permissions', $permissions_options,$val['permission'],$permissions_attr);
											?>
											<div class="errormsg"></div>
										</div>
																			
										<?php echo form_submit('uregulatory_submit',lang('Update'),'class = "light_green btn_lml"');?>
										
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
									<a class="edit project_row_add" href="javascript:void(0);" id="add_newfile" onclick="rowtoggle(this.id);">+ <?php echo lang('AddNew');?></a>
								</div>
						<div class="edit add_new">
										
									<?php echo form_open('projects/add_regulatory/'.$slug,array('id'=>'regulatory_form','name'=>'aregulatory_form','method'=>'post','class'=>'ajax_form'));?>
									
									<?php 
										
										$opt['regulatory_form'] = array(
											'lbl_filename' => array(
													'class' => 'left_label'
													),
											'project_regulatory_filename'	=> array(
													'name' 		=> 'project_regulatory_filename',
													'id' 		=> 'project_regulatory_filename'
													),
											'lbl_description' => array(
													'class' => 'left_label'
													),
											'project_regulatory_desc'	=> array(
													'name' 		=> 'project_regulatory_desc',
													'id' 		=> 'project_regulatory_desc'
													),
											'lbl_permission' => array(
													'class' => 'left_label'
													)
											);
				
										?>

										
										<?php echo form_label(lang('Description').':', 'project_regulatory_desc', $opt['regulatory_form']['lbl_description']);?>
										<div class="fld" style="width:500px;">
				
											<?php echo form_input($opt['regulatory_form']['project_regulatory_desc']);?>
											<div class="errormsg"></div>
										</div>
										<br>
										<?php echo form_label(lang('File').':', 'project_regulatory_filename', $opt['regulatory_form']['lbl_filename']);?>
										<div class="fld" style="width:500px;">
				
											<?php echo form_upload($opt['regulatory_form']['project_regulatory_filename']);?>
											<div class="errormsg"></div>
										</div>
										<br>
										<?php echo form_label(lang('Permission').':', 'project_regulatory_permission', $opt['regulatory_form']['lbl_permission']);?>
										<div class="fld" style="width:500px;">
				
											<?php
												$permissions_attr = 'id="project_regulatory_permission"';
												$permissions_options = array(
													'All'		=> lang('All'),
													'Some' 		=> lang('Some'),
													'Other' 	=> lang('Other')
												);
												echo form_dropdown('project_regulatory_permissions', $permissions_options,'',$permissions_attr);
											?>
											<div class="errormsg"></div>
										</div>
																			
										<?php echo form_submit('regulatory_submit',lang('AddNew'),'class = "light_green btn_lml"');?>
										
										<?php echo form_close();?>

									</div>
								</li>
						</ul>	
							
							<!--<table class="edit_table">

							<thead>
								<tr>
									<th>Title</th>
									<th>Responsible Agency</th>
									<th>Responsible Agency POC Name</th>
									<th>Target Completion Date</th>
									<th>Status</th>
								</tr>
							</thead>

							<tbody>

							<tr>
									<td></td>
							</tr>

							</tbody>

						</table>-->

					</div>

				</div>

			</div>
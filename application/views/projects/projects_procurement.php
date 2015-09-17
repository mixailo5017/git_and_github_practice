<?php //echo "<pre>"; print_r($project); ?>
<div id="profile_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all project_form" style="display: block;">

				<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
					<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-1"><?php echo lang('Machinery');?></a></li>
					<li class="ui-state-default ui-corner-top"><a href="#tabs-2"><?php echo lang('KeyTechnology');?></a></li>
					<li class="ui-state-default ui-corner-top"><a href="#tabs-3"><?php echo lang('KeyServices');?></a></li>
					
				</ul>

		
				<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-1">

					<div class="clearfix matrix_dropdown project_machinery">
						<ul id="load_machinery_form">
							
							<?php
							
							foreach($project["machinery"] as $key=>$val)
							{
							?>
							<li class="" id="row_id_<?php echo $val["id"]; ?>">
								<div class="view clearfix">
									
									<span class="left"><?php echo $val["name"]; ?></span>

									<a class="right delete" href="#projects/delete_machinery"><?php echo lang('Delete');?></a>

									<a class="right edit" id="edit_machinery_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang("Edit");?></a>

								</div>

								<div class="edit">
									<?php echo form_open('projects/update_machinery/'.$slug,array('id'=>'update_machinery_form_'.$val["id"],'name'=>'update_machinery_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>

										<?php 
										
										$opt['update_machinery_form'] = array(
												'lbl_name' => array(
													'class' => 'left_label'
												),
												'project_machinery_name'	=> array(
													'name' 		=> 'project_machinery_name',
													'id' 		=> 'project_machinery_name',
													'value'		=> $val["name"]
												),
												'lbl_process' => array(
													'class' => 'left_label'
												),
												'project_machinery_process'	=> array(
													'name' 		=> 'project_machinery_process',
													'id' 		=> 'project_machinery_process',
													'value'		=> $val["procurementprocess"]
												),
												'lbl_info' => array(
													'class' => 'left_label'
												),
												'project_machinery_financial_info'	=> array(
													'name' 		=> 'project_machinery_financial_info',
													'id' 		=> 'project_machinery_financial_info',
													'value'		=> $val["financialinfo"]
												),
												'lbl_permissions' => array(
													'class' => 'left_label'
												)
											);
				
										?>
										
										<?php echo form_hidden("hdn_project_machinery_id",$val["id"]); ?>
														
										<?php echo form_label(lang('Name').':', '', $opt['update_machinery_form']['lbl_name']);?>
										<div class="fld">
											<?php echo form_input($opt['update_machinery_form']['project_machinery_name']);?>
											<div id="err_project_machinery_name" class="errormsg"></div>
										</div>
										<?php echo br(); ?>
	
										<?php echo form_label(lang('ProcurementProcess').':', '', $opt['update_machinery_form']['lbl_process']);?>
										<div class="fld">
											<?php echo form_input($opt['update_machinery_form']['project_machinery_process']);?>
											<div id="err_project_machinery_process" class="errormsg"></div>
										</div>
										<?php echo br(); ?>
										
										<?php echo form_label(lang('FinancialInformation').':', '', $opt['update_machinery_form']['lbl_info']);?>
										<div class="fld">
											<?php echo form_input($opt['update_machinery_form']['project_machinery_financial_info']);?>
											<div id="err_project_machinery_financial_info" class="errormsg"></div>
										</div>
										<?php echo br(); ?>
										
										<?php echo form_label(lang('Permissions').':', '', $opt['update_machinery_form']['lbl_permissions']);?>
										<?php
											$machinery_permission_attr = "id='project_machinery_permission'";
											$machinery_permission_options = array(
												"All"	=> lang("All"),
												"Some"	=> lang("Some"),
												"Other"	=> lang("Other")
											);
											echo form_dropdown("project_machinery_permission",$machinery_permission_options,$val["permission"],$machinery_permission_attr);
										?>
										<?php echo br(); ?>										
										<?php echo form_submit('umachinery_submit',lang('Update'),'class = "light_green btn_lml"');?>
										
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
									
									<a class="edit project_row_add" href="javascript:void(0);" id="add_manchinery" onclick="rowtoggle(this.id);">+ <?php echo lang('AddMachinery');?></a>

								</div>

								<div class="edit add_new">
									<?php echo form_open('projects/add_machinery/'.$slug,array('id'=>'machinery_form','name'=>'machinery_form','method'=>'post','class'=>'ajax_form'));?>	
									<?php 
										$opt['machinery_form'] = array(
												'lbl_name' => array(
														'class' => 'left_label'
														),
												'project_machinery_name'	=> array(
														'name' 		=> 'project_machinery_name',
														'id' 		=> 'project_machinery_name'
														),
												'lbl_process' => array(
														'class' => 'left_label'
														),
												'project_machinery_process'	=> array(
														'name' 		=> 'project_machinery_process',
														'id' 		=> 'project_machinery_process'
														),
												'lbl_info' => array(
														'class' => 'left_label'
														),
												'project_machinery_financial_info'	=> array(
														'name' 		=> 'project_machinery_financial_info',
														'id' 		=> 'project_machinery_financial_info'
														),
												'lbl_permissions' => array(
														'class' => 'left_label'
														)
											);

									?>
									
									<?php echo form_label(lang('Name').':', '', $opt['machinery_form']['lbl_name']);?>
									<div class="fld">
										<?php echo form_input($opt['machinery_form']['project_machinery_name']);?>
										<div id="err_project_machinery_name" class="errormsg"></div>
									</div>
									<?php echo br(); ?>

									<?php echo form_label(lang('ProcurementProcess').':', '', $opt['machinery_form']['lbl_process']);?>
									<div class="fld">
										<?php echo form_input($opt['machinery_form']['project_machinery_process']);?>
										<div id="err_project_machinery_process" class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label(lang('FinancialInformation').':', '', $opt['machinery_form']['lbl_info']);?>
									<div class="fld">
										<?php echo form_input($opt['machinery_form']['project_machinery_financial_info']);?>
										<div id="err_project_machinery_financial_info" class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label(lang('Permissions').':', '', $opt['machinery_form']['lbl_permissions']);?>
									<?php
										$machinery_permission_attr = "id='project_machinery_permission'";
										$machinery_permission_options = array(
											"All"	=> lang("All"),
											"Some"	=> lang("Some"),
											"Other"	=> lang("Other")
										);
										echo form_dropdown("project_machinery_permission",$machinery_permission_options,'',$machinery_permission_attr);
									?>
									<?php echo br(); ?>

									<?php echo form_submit('machinery_submit', lang('AddNew'),'class = "light_green btn_lml"');?>
									
									<?php echo form_close(); ?>
								</div>

							</li>
						</ul>

					</div>

				</div>

		
				<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-2">
				
					<div class="clearfix matrix_dropdown project_procurement_technology">
					
						<ul id="load_procurement_technology_form">
							<?php
							
							foreach($project["procurement_technology"] as $key=>$val)
							{
							?>
							<li class="" id="row_id_<?php echo $val["id"]; ?>">
								<div class="view clearfix">
									
									<span class="left"><?php echo $val["name"]; ?></span>

									<a class="right delete" href="#projects/delete_procurement_technology"><?php echo lang('Delete');?></a>

									<a class="right edit" id="edit_procurement_technology_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

								</div>

								<div class="edit">
									<?php echo form_open('projects/update_procurement_technology/'.$slug,array('id'=>'update_procurement_technology_form_'.$val["id"],'name'=>'update_procurement_technology_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>

										<?php 
										
										$opt['update_procurement_technology_form'] = array(
											'lbl_name' => array(
													'class' => 'left_label'
													),
											'project_procurement_technology_name'	=> array(
													'name' 		=> 'project_procurement_technology_name',
													'id' 		=> 'project_procurement_technology_name',
													'value'		=> $val["name"]
													),
											'lbl_process' => array(
													'class' => 'left_label'
													),
											'project_procurement_technology_process'	=> array(
													'name' 		=> 'project_procurement_technology_process',
													'id' 		=> 'project_procurement_technology_process',
													'value'		=> $val["procurementprocess"]
													),
											'lbl_info' => array(
													'class' => 'left_label'
													),
											'project_procurement_technology_financial_info'	=> array(
													'name' 		=> 'project_procurement_technology_financial_info',
													'id' 		=> 'project_procurement_technology_financial_info',
													'value'		=> $val["financialinfo"]
													),
											'lbl_permissions' => array(
													'class' => 'left_label'
													)

											);
				
										?>
										
										<?php echo form_hidden("hdn_procurement_technology_id",$val["id"]); ?>
														
										<?php echo form_label(lang('Name').':', '', $opt['update_procurement_technology_form']['lbl_name']);?>
										<div class="fld">
											<?php echo form_input($opt['update_procurement_technology_form']['project_procurement_technology_name']);?>
											<div id="err_project_procurement_technology_name" class="errormsg"></div>
										</div>
										<?php echo br(); ?>
	
										<?php echo form_label(lang('ProcurementProcess').':', '', $opt['update_procurement_technology_form']['lbl_process']);?>
										<div class="fld">
											<?php echo form_input($opt['update_procurement_technology_form']['project_procurement_technology_process']);?>
											<div id="err_project_procurement_technology_process" class="errormsg"></div>
										</div>
										<?php echo br(); ?>
										
										<?php echo form_label(lang('FinancialInformation').':', '', $opt['update_procurement_technology_form']['lbl_info']);?>
										<div class="fld">
											<?php echo form_input($opt['update_procurement_technology_form']['project_procurement_technology_financial_info']);?>
											<div id="err_project_procurement_technology_financial_info" class="errormsg"></div>
										</div>
										<?php echo br(); ?>
										
										<?php echo form_label(lang('Permissions').':', '', $opt['update_procurement_technology_form']['lbl_permissions']);?>
										<?php
											$technology_permission_attr = "id='project_procurement_technology_permission'";
											$technology_permission_options = array(
												"All"	=> lang("All"),
												"Some"	=> lang("Some"),
												"Other"	=> lang("Other")
											);
											echo form_dropdown("project_procurement_technology_permission",$technology_permission_options,$val["permission"],$technology_permission_attr);
										?>
										<?php echo br(); ?>										
										<?php echo form_submit('utechnology_submit', lang('Update'),'class = "light_green btn_lml"');?>
										
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
									
									<a class="edit project_row_add" href="javascript:void(0);" id="add_keytech" onclick="rowtoggle(this.id);">+ <?php echo lang('AddKeyTechnology');?></a>

								</div>

								<div class="edit add_new">
									<?php echo form_open('projects/add_procurement_technology/'.$slug,array('id'=>'procurement_technology_form','name'=>'procurement_technology_form','method'=>'post','class'=>'ajax_form'));?>	
									<?php 
										$opt['procurement_technology_form'] = array(
												'lbl_name' => array(
														'class' => 'left_label'
														),
												'project_procurement_technology_name'	=> array(
														'name' 		=> 'project_procurement_technology_name',
														'id' 		=> 'project_procurement_technology_name'
														),
												'lbl_process' => array(
														'class' => 'left_label'
														),
												'project_procurement_technology_process'	=> array(
														'name' 		=> 'project_procurement_technology_process',
														'id' 		=> 'project_procurement_technology_process'
														),
												'lbl_info' => array(
														'class' => 'left_label'
														),
												'project_procurement_technology_financial_info'	=> array(
														'name' 		=> 'project_procurement_technology_financial_info',
														'id' 		=> 'project_procurement_technology_financial_info'
														),
												'lbl_permissions' => array(
														'class' => 'left_label'
														)
											);

									?>
									
									<?php echo form_label(lang('Name').':', '', $opt['procurement_technology_form']['lbl_name']);?>
									<div class="fld">
										<?php echo form_input($opt['procurement_technology_form']['project_procurement_technology_name']);?>
										<div id="err_project_procurement_technology_name" class="errormsg"></div>
									</div>
									<?php echo br(); ?>

									<?php echo form_label(lang('ProcurementProcess').':', '', $opt['procurement_technology_form']['lbl_process']);?>
									<div class="fld">
										<?php echo form_input($opt['procurement_technology_form']['project_procurement_technology_process']);?>
										<div id="err_project_procurement_technology_process" class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label(lang('FinancialInformation').':', '', $opt['procurement_technology_form']['lbl_info']);?>
									<div class="fld">
										<?php echo form_input($opt['procurement_technology_form']['project_procurement_technology_financial_info']);?>
										<div id="err_project_procurement_technology_financial_info" class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label(lang('Permissions').':', '', $opt['procurement_technology_form']['lbl_permissions']);?>
									<?php
										$technology_permission_attr = "id='project_procurement_technology_permission'";
										$technology_permission_options = array(
											"All"	=> lang("All"),
											"Some"	=> lang("Some"),
											"Other"	=> lang("Other")
										);
										echo form_dropdown("project_procurement_technology_permission",$technology_permission_options,'',$technology_permission_attr);
									?>
									<?php echo br(); ?>

									<?php echo form_submit('technology_submit', lang('AddNew'),'class = "light_green btn_lml"');?>
									
									<?php echo form_close(); ?>

								</div>

							</li>
						</ul>

					</div>
					
				</div>

		
				<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-3">
				
					<div class="clearfix matrix_dropdown project_procurement_services">
												
						<ul id="load_procurement_services_form">
							<?php
							
							foreach($project["procurement_services"] as $key=>$val)
							{
							?>
							<li class="" id="row_id_<?php echo $val["id"]; ?>">
								<div class="view clearfix">
									
									<span class="left"><?php echo $val["name"]; ?></span>

									<a class="right delete" href="#projects/delete_procurement_services"><?php echo lang('Delete');?></a>

									<a class="right edit" id="edit_procurement_services_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

								</div>

								<div class="edit">
									<?php echo form_open('projects/update_procurement_services/'.$slug,array('id'=>'update_procurement_services_form_'.$val["id"],'name'=>'update_procurement_services_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>

										<?php 
										
										$opt['update_procurement_services_form'] = array(
											'lbl_name' => array(
													'class' => 'left_label'
													),
											'project_procurement_services_name'	=> array(
													'name' 		=> 'project_procurement_services_name',
													'id' 		=> 'project_procurement_services_name',
													'value'		=> $val["name"]
													),
											'lbl_type' => array(
													'class' => 'left_label'
													),
											'project_procurement_services_type'	=> array(
													'name' 		=> 'project_procurement_services_type',
													'id' 		=> 'project_procurement_services_type',
													'value'		=> $val["type"]
													),
											'lbl_process' => array(
													'class' => 'left_label'
													),
											'project_procurement_services_process'	=> array(
													'name' 		=> 'project_procurement_services_process',
													'id' 		=> 'project_procurement_services_process',
													'value'		=> $val["procurementprocess"]
													),
											'lbl_info' => array(
													'class' => 'left_label'
													),
											'project_procurement_services_financial_info'	=> array(
													'name' 		=> 'project_procurement_services_financial_info',
													'id' 		=> 'project_procurement_services_financial_info',
													'value'		=> $val["financialinfo"]
													),
											'lbl_permissions' => array(
													'class' => 'left_label'
													)
											);
				
										?>
										
										<?php echo form_hidden("hdn_procurement_services_id",$val["id"]); ?>
										
										<?php echo form_label('Name:', '', $opt['update_procurement_services_form']['lbl_name']);?>
										<div class="fld">
											<?php echo form_input($opt['update_procurement_services_form']['project_procurement_services_name']);?>
											<div id="err_project_procurement_services_name" class="errormsg"></div>
										</div>
										<?php echo br(); ?>
	
										<?php echo form_label(lang('Name').':', '', $opt['update_procurement_services_form']['lbl_type']);?>
										<div class="fld">
											<?php echo form_input($opt['update_procurement_services_form']['project_procurement_services_type']);?>
											<div id="err_project_procurement_services_type" class="errormsg"></div>
										</div>
										<?php echo br(); ?>
	
										<?php echo form_label(lang('ProcurementProcess').':', '', $opt['update_procurement_services_form']['lbl_process']);?>
										<div class="fld">
											<?php echo form_input($opt['update_procurement_services_form']['project_procurement_services_process']);?>
											<div id="err_project_procurement_services_process" class="errormsg"></div>
										</div>
										<?php echo br(); ?>
										
										<?php echo form_label(lang('FinancialInformation').':', '', $opt['update_procurement_services_form']['lbl_info']);?>
										<div class="fld">
											<?php echo form_input($opt['update_procurement_services_form']['project_procurement_services_financial_info']);?>
											<div id="err_project_procurement_services_financial_info" class="errormsg"></div>
										</div>
										<?php echo br(); ?>
										
										<?php echo form_label(lang('Permissions').':', '', $opt['update_procurement_services_form']['lbl_permissions']);?>
										<?php
											$services_permission_attr = "id='project_procurement_services_permission'";
											$services_permission_options = array(
												"All"	=> lang("All"),
												"Some"	=> lang("Some"),
												"Other"	=> lang("Other")
											);
											echo form_dropdown("project_procurement_services_permission",$services_permission_options,$val["permission"],$services_permission_attr);
										?>
										<?php echo br(); ?>										
										<?php echo form_submit('uservices_submit', lang('Update'),'class = "light_green btn_lml"');?>
										
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
									
									<a class="edit project_row_add" href="javascript:void(0);" id="add_keyservices" onclick="rowtoggle(this.id);">+ <?php echo lang('AddKeyServices');?></a>

								</div>

								<div class="edit add_new">
									
									<?php echo form_open('projects/add_procurement_services/'.$slug,array('id'=>'procurement_services_form','name'=>'procurement_services_form','method'=>'post','class'=>'ajax_form'));?>	
									<?php 
										$opt['procurement_services_form'] = array(
												'lbl_name' => array(
														'class' => 'left_label'
														),
												'project_procurement_services_name'	=> array(
														'name' 		=> 'project_procurement_services_name',
														'id' 		=> 'project_procurement_services_name'
														),
												'lbl_type' => array(
														'class' => 'left_label'
														),
												'project_procurement_services_type'	=> array(
														'name' 		=> 'project_procurement_services_type',
														'id' 		=> 'project_procurement_services_type'
														),
												'lbl_process' => array(
														'class' => 'left_label'
														),
												'project_procurement_services_process'	=> array(
														'name' 		=> 'project_procurement_services_process',
														'id' 		=> 'project_procurement_services_process'
														),
												'lbl_info' => array(
														'class' => 'left_label'
														),
												'project_procurement_services_financial_info'	=> array(
														'name' 		=> 'project_procurement_services_financial_info',
														'id' 		=> 'project_procurement_services_financial_info'
														),
												'lbl_permissions' => array(
														'class' => 'left_label'
														)
											);

									?>
									
									<?php echo form_label(lang('Name').':', '', $opt['procurement_services_form']['lbl_name']);?>
									<div class="fld">
										<?php echo form_input($opt['procurement_services_form']['project_procurement_services_name']);?>
										<div id="err_project_procurement_services_name" class="errormsg"></div>
									</div>
									<?php echo br(); ?>

									<?php echo form_label(lang('Type').':', '', $opt['procurement_services_form']['lbl_type']);?>
									<div class="fld">
										<?php echo form_input($opt['procurement_services_form']['project_procurement_services_type']);?>
										<div id="err_project_procurement_services_type" class="errormsg"></div>
									</div>
									<?php echo br(); ?>

									<?php echo form_label(lang('ProcurementProcess').':', '', $opt['procurement_services_form']['lbl_process']);?>
									<div class="fld">
										<?php echo form_input($opt['procurement_services_form']['project_procurement_services_process']);?>
										<div id="err_project_procurement_services_process" class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label(lang('FinancialInformation').':', '', $opt['procurement_services_form']['lbl_info']);?>
									<div class="fld">
										<?php echo form_input($opt['procurement_services_form']['project_procurement_services_financial_info']);?>
										<div id="err_project_procurement_services_financial_info" class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label(lang('Permissions').':', '', $opt['procurement_services_form']['lbl_permissions']);?>
									<?php
										$services_permission_attr = "id='project_procurement_services_permission'";
										$services_permission_options = array(
											"All"	=> lang("All"),
											"Some"	=> lang("Some"),
											"Other"	=> lang("Other")
										);
										echo form_dropdown("project_procurement_services_permission",$services_permission_options,'',$services_permission_attr);
									?>
									<?php echo br(); ?>

									<?php echo form_submit('services_submit', lang('AddNew'),'class = "light_green btn_lml"');?>
									
									<?php echo form_close(); ?>
								
								</div>

							</li>
						</ul>
										
					</div>

				</div>

			</div>
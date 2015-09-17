<?php 
//echo "<pre>";
//print_r($project);
//echo "</pre>";
?>
<div id="profile_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all project_form" style="display: block;">

				<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
					<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-1"><?php echo lang('Public');?></a></li>
					<li class="ui-state-default ui-corner-top"><a href="#tabs-2"><?php echo lang('Political');?></a></li>
					<li class="ui-state-default ui-corner-top"><a href="#tabs-3"><?php echo lang('Companies');?></a></li>
					<li class="ui-state-default ui-corner-top"><a href="#tabs-4"><?php echo lang('Owners');?></a></li>
					
				</ul>

		
				<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-1">

					<div class="clearfix matrix_dropdown project_participants_public">
						
						
						<ul id="load_participants_public_form">
							<?php
							
							foreach($project["public"] as $key=>$val)
							{
							?>
	
						
							<li class="" id="row_id_<?php echo $val["id"];?>">
								
								<div class="view clearfix">
									
									<span class="left"><?php echo $val["type"];?></span>

									<span class="middle"><strong><?php echo $val["name"];?></strong><br><?php echo $val["description"];?></span>

									<a class="right delete" href="#projects/delete_participants_public"><?php echo lang('Delete');?></a>

									<a class="right edit" id="edit_participants_public_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

								</div>

								<div class="edit">

								<?php echo form_open('projects/update_participants_public/'.$slug,array('id'=>'update_participants_public_form_'.$val["id"],'name'=>'participants_public_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>

								<?php 
									
									$opt['update_participants_public_form'] = array(
													'lbl_name' => array(
															'class' => 'left_label'
															),
													'project_participants_public_name'	=> array(
															'name' 		=> 'project_participants_public_name',
															'id' 		=> 'project_participants_public_name',
															'value'		=> $val['name']
															),
													'lbl_type' => array(
															'class' => 'left_label'
															),
													'project_participants_public_type'	=> array(
															'name' 		=> 'project_participants_public_type',
															'id' 		=> 'project_participants_public_type',
															'value'		=> $val['type']
															),
													'lbl_description' => array(
															'class' => 'left_label'
															),
													'project_participants_public_desc'	=> array(
															'name' 		=> 'project_participants_public_desc',
															'id' 		=> 'project_participants_public_desc',
															'value'		=> $val['description']
															),
													'lbl_permissions' => array(
															'class' => 'left_label'
															),
										);
			
									?>
									
									<?php echo form_hidden("hdn_participants_public_id",$val["id"]); ?>
									<?php echo form_label(lang('Name').':', 'participants_public_name', $opt['update_participants_public_form']['lbl_name']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['update_participants_public_form']['project_participants_public_name']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Type').':', 'participants_public_type', $opt['update_participants_public_form']['lbl_type']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['update_participants_public_form']['project_participants_public_type']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Description').':', 'project_participants_public_desc', $opt['update_participants_public_form']['lbl_description']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['update_participants_public_form']['project_participants_public_desc']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Permissions').':', 'project_participants_public_permissions', $opt['update_participants_public_form']['lbl_permissions']);?>
									<?php
										$permissions_attr = 'id="project_participants_public_permissions"';
										$permissions_options = array(
											'All'		=> lang('All'),
											'Some' 		=> lang('Some'),
											'Other' 	=> lang('Other')
										);
										echo form_dropdown('project_participants_public_permissions', $permissions_options,'',$permissions_attr);
									?>
									<br>
									
									<?php echo form_submit('upublic_submit', lang('Update'),'class = "light_green btn_lml"');?>
									
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
									
									<a class="edit project_row_add" href="javascript:void(0);" id="add_publicparticipant" onclick="rowtoggle(this.id);">+ <?php echo lang('AddPublicParticipant');?></a>

								</div>

								<div class="edit add_new">
								
								<?php echo form_open('projects/add_participants_public/'.$slug,array('id'=>'participants_public_form','name'=>'participants_public_form','method'=>'post','class'=>'ajax_form'));?>

								<?php 
									
									$opt['participants_public_form'] = array(
													'lbl_name' => array(
															'class' => 'left_label'
															),
													'project_participants_public_name'	=> array(
															'name' 		=> 'project_participants_public_name',
															'id' 		=> 'project_participants_public_name'
															),
													'lbl_type' => array(
															'class' => 'left_label'
															),
													'project_participants_public_type'	=> array(
															'name' 		=> 'project_participants_public_type',
															'id' 		=> 'project_participants_public_type'
															),
													'lbl_description' => array(
															'class' => 'left_label'
															),
													'project_participants_public_desc'	=> array(
															'name' 		=> 'project_participants_public_desc',
															'id' 		=> 'project_participants_public_desc'
															),
													'lbl_permissions' => array(
															'class' => 'left_label'
															),
										);
			
									?>
									
									
									<?php echo form_label(lang('Name').':', 'participants_public_name', $opt['participants_public_form']['lbl_name']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['participants_public_form']['project_participants_public_name']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Type').':', 'participants_public_type', $opt['participants_public_form']['lbl_type']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['participants_public_form']['project_participants_public_type']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Description').':', 'project_participants_public_desc', $opt['participants_public_form']['lbl_description']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['participants_public_form']['project_participants_public_desc']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Permissions').':', 'project_participants_public_permissions', $opt['participants_public_form']['lbl_permissions']);?>
									<?php
										$permissions_attr = 'id="project_participants_public_permissions"';
										$permissions_options = array(
											'All'		=> lang('All'),
											'Some' 		=> lang('Some'),
											'Other' 	=> lang('Other')
										);
										echo form_dropdown('project_participants_public_permissions', $permissions_options,'',$permissions_attr);
									?>
									<br>
									
									<?php echo form_submit('public_submit', lang('AddNew'),'class = "light_green btn_lml"');?>
									
									<?php echo form_close();?>
			
								</div>

							</li>
						</ul>

					</div>

				</div>

		
				<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-2">
				
					<div class="clearfix matrix_dropdown project_participants_political">
					
					<ul id="load_participants_political_form">
							<?php
							
							foreach($project["political"] as $key=>$val)
							{
							?>
	
						
							<li class="" id="row_id_<?php echo $val["id"];?>">
								
								<div class="view clearfix">
									
									<span class="left"><?php echo $val["type"];?></span>

									<span class="middle"><strong><?php echo $val["name"];?></strong><br><?php echo $val["description"];?></span>

									<a class="right delete" href="#projects/delete_participants_political"><?php echo lang('Delete');?></a>

									<a class="right edit" id="edit_participants_political_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

								</div>

								<div class="edit">

								<?php echo form_open('projects/update_participants_political/'.$slug,array('id'=>'update_participants_political_form_'.$val["id"],'name'=>'participants_political_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>

								<?php 
									
									$opt['update_participants_political_form'] = array(
													'lbl_name' => array(
															'class' => 'left_label'
															),
													'project_participants_political_name'	=> array(
															'name' 		=> 'project_participants_political_name',
															'id' 		=> 'project_participants_political_name',
															'value'		=> $val['name']
															),
													'lbl_type' => array(
															'class' => 'left_label'
															),
													'project_participants_political_type'	=> array(
															'name' 		=> 'project_participants_political_type',
															'id' 		=> 'project_participants_political_type',
															'value'		=> $val['type']
															),
													'lbl_description' => array(
															'class' => 'left_label'
															),
													'project_participants_political_desc'	=> array(
															'name' 		=> 'project_participants_political_desc',
															'id' 		=> 'project_participants_political_desc',
															'value'		=> $val['description']
															),
													'lbl_permissions' => array(
															'class' => 'left_label'
															),
										);
			
									?>
									
										<?php echo form_hidden("hdn_participants_political_id",$val["id"]); ?>
									<?php echo form_label(lang('Name').':', 'participants_political_name', $opt['update_participants_political_form']['lbl_name']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['update_participants_political_form']['project_participants_political_name']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Type').':', 'participants_political_type', $opt['update_participants_political_form']['lbl_type']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['update_participants_political_form']['project_participants_political_type']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Description').':', 'project_participants_political_desc', $opt['update_participants_political_form']['lbl_description']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['update_participants_political_form']['project_participants_political_desc']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Permissions').':', 'project_participants_political_permissions', $opt['update_participants_political_form']['lbl_permissions']);?>
									<?php
										$permissions_attr = 'id="project_participants_political_permissions"';
										$permissions_options = array(
											'All'		=> lang('All'),
											'Some' 		=> lang('Some'),
											'Other' 	=> lang('Other')
										);
										echo form_dropdown('project_participants_political_permissions', $permissions_options,'',$permissions_attr);
									?>
									<br>
									
									<?php echo form_submit('upolitical_submit', lang('Update'),'class = "light_green btn_lml"');?>
									
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
									
									<a class="edit project_row_add" href="javascript:void(0);" id="add_participant" onclick="rowtoggle(this.id);">+ <?php echo lang('AddPoliticalParticipant');?></a>

								</div>

								<div class="edit add_new">
									
								<?php echo form_open('projects/add_participants_political/'.$slug,array('id'=>'participants_political_form','name'=>'participants_political_form','method'=>'post','class'=>'ajax_form'));?>

								<?php 
									
									$opt['participants_political_form'] = array(
													'lbl_name' => array(
															'class' => 'left_label'
															),
													'project_participants_political_name'	=> array(
															'name' 		=> 'project_participants_political_name',
															'id' 		=> 'project_participants_political_name'
															),
													'lbl_type' => array(
															'class' => 'left_label'
															),
													'project_participants_political_type'	=> array(
															'name' 		=> 'project_participants_political_type',
															'id' 		=> 'project_participants_political_type'
															),
													'lbl_description' => array(
															'class' => 'left_label'
															),
													'project_participants_political_desc'	=> array(
															'name' 		=> 'project_participants_political_desc',
															'id' 		=> 'project_participants_political_desc'
															),
													'lbl_permissions' => array(
															'class' => 'left_label'
															),
										);
			
									?>
									
									
									<?php echo form_label(lang('Name').':', 'participants_political_name', $opt['participants_political_form']['lbl_name']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['participants_political_form']['project_participants_political_name']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Type').':', 'participants_political_type', $opt['participants_political_form']['lbl_type']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['participants_political_form']['project_participants_political_type']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Description').':', 'project_participants_political_desc', $opt['participants_political_form']['lbl_description']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['participants_political_form']['project_participants_political_desc']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Permissions').':', 'project_participants_political_permissions', $opt['participants_political_form']['lbl_permissions']);?>
									<?php
										$permissions_attr = 'id="project_participants_political_permissions"';
										$permissions_options = array(
											'All'		=> lang('All'),
											'Some' 		=> lang('Some'),
											'Other' 	=> lang('Other')
										);
										echo form_dropdown('project_participants_political_permissions', $permissions_options,'',$permissions_attr);
									?>
									<br>
									
									<?php echo form_submit('political_submit', lang('AddNew'),'class = "light_green btn_lml"');?>
									
									<?php echo form_close();?>

								</div>

							</li>
						</ul>

					</div>

				</div>

		
				<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-3">
				
					<div class="clearfix matrix_dropdown project_participants_companies">
					
						<ul id="load_participants_company_form">
							<?php
							
							foreach($project["companies"] as $key=>$val)
							{
							?>
	
						
							<li class="" id="row_id_<?php echo $val["id"];?>">
								
								<div class="view clearfix">
									
									<span class="left"><?php echo $val["role"];?></span>

									<span class="middle"><strong><?php echo $val["name"];?></strong><br><?php echo $val["description"];?></span>

									<a class="right delete" href="#projects/delete_participants_companies"><?php echo lang('Delete');?></a>

									<a class="right edit" id="edit_participants_companies_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

								</div>

								<div class="edit">

								<?php echo form_open('projects/update_participants_companies/'.$slug,array('id'=>'update_participants_companies_form_'.$val["id"],'name'=>'participants_companies_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>

								<?php 
									
									$opt['update_participants_companies_form'] = array(
													'lbl_name' => array(
															'class' => 'left_label'
															),
													'project_participants_companies_name'	=> array(
															'name' 		=> 'project_participants_companies_name',
															'id' 		=> 'project_participants_companies_name',
															'value'		=> $val['name']
															),
													'lbl_role' => array(
															'class' => 'left_label'
															),
													'project_participants_companies_role'	=> array(
															'name' 		=> 'project_participants_companies_role',
															'id' 		=> 'project_participants_companies_role',
															'value'		=> $val['role']
															),
													'lbl_description' => array(
															'class' => 'left_label'
															),
													'project_participants_companies_desc'	=> array(
															'name' 		=> 'project_participants_companies_desc',
															'id' 		=> 'project_participants_companies_desc',
															'value'		=> $val['description']
															),
													'lbl_permissions' => array(
															'class' => 'left_label'
															),
										);
			
									?>
									
									<?php echo form_hidden("hdn_participants_companies_id",$val["id"]); ?>
									<?php echo form_label(lang('Name').':', 'participants_companies_name', $opt['update_participants_companies_form']['lbl_name']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['update_participants_companies_form']['project_participants_companies_name']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Role').':', 'participants_companies_role', $opt['update_participants_companies_form']['lbl_role']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['update_participants_companies_form']['project_participants_companies_role']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Description').':', 'project_participants_companies_desc', $opt['update_participants_companies_form']['lbl_description']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['update_participants_companies_form']['project_participants_companies_desc']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Permissions').':', 'project_participants_companies_permissions', $opt['update_participants_companies_form']['lbl_permissions']);?>
									<?php
										$permissions_attr = 'id="project_participants_companies_permissions"';
										$permissions_options = array(
											'All'		=> lang('All'),
											'Some' 		=> lang('Some'),
											'Other' 	=> lang('Other')
										);
										echo form_dropdown('project_participants_companies_permissions', $permissions_options,'',$permissions_attr);
									?>
									<br>
									
									<?php echo form_submit('ucompanies_submit', lang('Update'),'class = "light_green btn_lml"');?>
									
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
									
									<a class="edit project_row_add" href="javascript:void(0);" id="add_company" onclick="rowtoggle(this.id);">+ <?php echo lang('AddCompany');?></a>

								</div>

								<div class="edit add_new">
									
								<?php echo form_open('projects/add_participants_companies/'.$slug,array('id'=>'participants_companies_form','name'=>'participants_companies_form','method'=>'post','class'=>'ajax_form'));?>

								<?php 
									
									$opt['participants_companies_form'] = array(
													'lbl_name' => array(
															'class' => 'left_label'
															),
													'project_participants_companies_name'	=> array(
															'name' 		=> 'project_participants_companies_name',
															'id' 		=> 'project_participants_companies_name'
															),
													'lbl_role' => array(
															'class' => 'left_label'
															),
													'project_participants_companies_role'	=> array(
															'name' 		=> 'project_participants_companies_role',
															'id' 		=> 'project_participants_companies_role'
															),
													'lbl_description' => array(
															'class' => 'left_label'
															),
													'project_participants_companies_desc'	=> array(
															'name' 		=> 'project_participants_companies_desc',
															'id' 		=> 'project_participants_companies_desc'
															),
													'lbl_permissions' => array(
															'class' => 'left_label'
															),
										);
			
									?>
									
									
									<?php echo form_label(lang('Name').':', 'participants_companies_name', $opt['participants_companies_form']['lbl_name']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['participants_companies_form']['project_participants_companies_name']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Role').':', 'participants_companies_role', $opt['participants_companies_form']['lbl_role']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['participants_companies_form']['project_participants_companies_role']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Description').':', 'project_participants_companies_desc', $opt['participants_companies_form']['lbl_description']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['participants_companies_form']['project_participants_companies_desc']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Permissions').':', 'project_participants_companies_permissions', $opt['participants_companies_form']['lbl_permissions']);?>
									<?php
										$permissions_attr = 'id="project_participants_companies_permissions"';
										$permissions_options = array(
											'All'		=> lang('All'),
											'Some' 		=> lang('Some'),
											'Other' 	=> lang('Other')
										);
										echo form_dropdown('project_participants_companies_permissions', $permissions_options,'',$permissions_attr);
									?>
									<br>
									
									<?php echo form_submit('companies_submit', lang('AddNew'),'class = "light_green btn_lml"');?>
									
									<?php echo form_close();?>

								</div>

							</li>
						</ul>
											
					</div>

				</div>

		
				<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-4">
				
					<div class="clearfix matrix_dropdown project_participants_owners">
					
						<ul id="load_participants_owners_form">
							<?php
							
							foreach($project["owners"] as $key=>$val)
							{
							?>
	
						
							<li class="" id="row_id_<?php echo $val["id"];?>">
								
								<div class="view clearfix">
									
									<span class="left"><?php echo $val["type"];?></span>

									<span class="middle"><strong><?php echo $val["name"];?></strong><br><?php echo $val["description"];?></span>

									<a class="right delete" href="#projects/delete_participants_owners"><?php echo lang('Delete');?></a>

									<a class="right edit" id="edit_participants_owners_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

								</div>

								<div class="edit">

								<?php echo form_open('projects/update_participants_owners/'.$slug,array('id'=>'update_participants_owners_form_'.$val["id"],'name'=>'participants_owners_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>

								<?php 
									
									$opt['update_participants_owners_form'] = array(
													'lbl_name' => array(
															'class' => 'left_label'
															),
													'project_participants_owners_name'	=> array(
															'name' 		=> 'project_participants_owners_name',
															'id' 		=> 'project_participants_owners_name',
															'value'		=> $val['name']
															),
													'lbl_type' => array(
															'class' => 'left_label'
															),
													'project_participants_owners_type'	=> array(
															'name' 		=> 'project_participants_owners_type',
															'id' 		=> 'project_participants_owners_type',
															'value'		=> $val['type']
															),
													'lbl_description' => array(
															'class' => 'left_label'
															),
													'project_participants_owners_desc'	=> array(
															'name' 		=> 'project_participants_owners_desc',
															'id' 		=> 'project_participants_owners_desc',
															'value'		=> $val['description']
															),
													'lbl_permissions' => array(
															'class' => 'left_label'
															),
										);
			
									?>
									
									<?php echo form_hidden("hdn_participants_owners_id",$val["id"]); ?>
									<?php echo form_label(lang('Name').':', 'participants_owners_name', $opt['update_participants_owners_form']['lbl_name']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['update_participants_owners_form']['project_participants_owners_name']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Type').':', 'participants_owners_type', $opt['update_participants_owners_form']['lbl_type']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['update_participants_owners_form']['project_participants_owners_type']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Description').':', 'project_participants_owners_desc', $opt['update_participants_owners_form']['lbl_description']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['update_participants_owners_form']['project_participants_owners_desc']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Permissions').':', 'project_participants_owners_permissions', $opt['update_participants_owners_form']['lbl_permissions']);?>
									<?php
										$permissions_attr = 'id="project_participants_owners_permissions"';
										$permissions_options = array(
											'All'		=> lang('All'),
											'Some' 		=> lang('Some'),
											'Other' 	=> lang('Other')
										);
										echo form_dropdown('project_participants_owners_permissions', $permissions_options,'',$permissions_attr);
									?>
									<br>
									
									<?php echo form_submit('uowners_submit', lang('Update'),'class = "light_green btn_lml"');?>
									
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
									
									<a class="edit project_row_add" href="javascript:void(0);" id="add_owner" onclick="rowtoggle(this.id);">+ <?php echo lang('AddOwner');?></a>

								</div>

								<div class="edit add_new">
									
								<?php echo form_open('projects/add_participants_owners/'.$slug,array('id'=>'participants_owners_form','name'=>'participants_owners_form','method'=>'post','class'=>'ajax_form'));?>

								<?php 
									
									$opt['participants_owners_form'] = array(
													'lbl_name' => array(
															'class' => 'left_label'
															),
													'project_participants_owners_name'	=> array(
															'name' 		=> 'project_participants_owners_name',
															'id' 		=> 'project_participants_owners_name'
															),
													'lbl_type' => array(
															'class' => 'left_label'
															),
													'project_participants_owners_type'	=> array(
															'name' 		=> 'project_participants_owners_type',
															'id' 		=> 'project_participants_owners_type'
															),
													'lbl_description' => array(
															'class' => 'left_label'
															),
													'project_participants_owners_desc'	=> array(
															'name' 		=> 'project_participants_owners_desc',
															'id' 		=> 'project_participants_owners_desc'
															),
													'lbl_permissions' => array(
															'class' => 'left_label'
															),
										);
			
									?>
									
									
									<?php echo form_label(lang('Name').':', 'participants_owners_name', $opt['participants_owners_form']['lbl_name']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['participants_owners_form']['project_participants_owners_name']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Type').':', 'participants_owners_type', $opt['participants_owners_form']['lbl_type']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['participants_owners_form']['project_participants_owners_type']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Description').':', 'project_participants_owners_desc', $opt['participants_owners_form']['lbl_description']);?>
									<div class="fld" style="width:500px;">
			
										<?php echo form_input($opt['participants_owners_form']['project_participants_owners_desc']);?>
										<div class="errormsg"></div>
									</div>
									
									<?php echo form_label(lang('Permissions').':', 'project_participants_owners_permissions', $opt['participants_owners_form']['lbl_permissions']);?>
									<?php
										$permissions_attr = 'id="project_participants_owners_permissions"';
										$permissions_options = array(
											'All'		=> lang('All'),
											'Some' 		=> lang('Some'),
											'Other' 	=> lang('Other')
										);
										echo form_dropdown('project_participants_owners_permissions', $permissions_options,'',$permissions_attr);
									?>
									<br>
									
									<?php echo form_submit('owners_submit', lang('AddNew'),'class = "light_green btn_lml"');?>
									
									<?php echo form_close();?>

								</div>

							</li>
						</ul>
											
					</div>

				</div>

			</div>
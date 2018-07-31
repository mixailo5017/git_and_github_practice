<div id="profile_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all project_form" style="display: block;">

	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-1"><?php echo lang('FinancialStructure');?></a></li>
		<li class="ui-state-default ui-corner-top"><a href="#tabs-2"><?php echo lang('FundSources');?></a></li>
		<li class="ui-state-default ui-corner-top"><a href="#tabs-3"><?php echo lang('ReturnonInvestment');?></a></li>
		<li class="ui-state-default ui-corner-top"><a href="#tabs-4"><?php echo lang('CriticalParticipants');?></a></li>
	</ul>

	
	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-1">
	
	
		<div class="clearfix">
		
		<?php echo form_open('projects/add_financial/'.$slug,array('id'=>'financial_form','name'=>'financial_form','method'=>'post','class'=>'ajax_form topupdate'));?>
			<?php 
				if(count_if_set($project["financial"]) <= 0) {
					$project["financial"] = array("name"=>"","contactname"=>"","role_others"=>"","contactinfo"=>"","name_privacy"=>"","contactname_privacy"=>"","role_privacy"=>"","role"=>"","contactinfo_privacy"=>"","fs_info"=>"");
				}
				$opt["financial_form"] = array(
					'lbl_fs_name_premissions' => array(
						'class'	=> 'above_label'
					),
					'permissions_options' => array(
						'Private'	=> lang('Private'),
						'Specific'	=> lang('Specific'),
						'Similar Project Owners'	=> lang('SimilarProjectOwners')
					),
					'lbl_fs_name' => array(
						'class'	=> 'left_label'
					),
					'fs_name'	=> array(
						'name'	=> 'project_fs_name',
						'id'	=> 'project_fs_name',
						'value'	=> $project["financial"]["name"]
					),
					'lbl_fs_contact_premissions' => array(
						'class'	=> 'above_label'
					),
					'lbl_fs_contact' => array(
						'class'	=> 'left_label'
					),
					'fs_contact'	=> array(
						'name'	=> 'project_fs_contact',
						'id'	=> 'project_fs_contact',
						'value'	=> $project["financial"]["contactname"]
					),
					'lbl_fs_role_premissions' => array(
						'class'	=> 'above_label'
					),
					'lbl_fs_role' => array(
						'class'	=> 'left_label'
					),
					'fs_role'	=> array(
						'id'	=> 'project_fs_role_other',
						'name'	=> 'project_fs_role_other',
						'class'	=> 'role_other',
						'placeholder'	=> lang('Others'),
						'value'	=> $project["financial"]["role_others"]
					),
					'lbl_fs_info_premissions' => array(
						'class'	=> 'above_label'
					),
					'lbl_fs_info' => array(
						'class'	=> 'above_label'
					),
					'fs_info'	=> array(
						'id'	=> 'project_fs_info',
						'name'	=> 'project_fs_info',
						'rows'	=> '10',
						'cols'	=> '30',
						'value'	=> $project["financial"]["contactinfo"]
					)
				);	
			?>
			<div class="permissions_block">
				<div class="arrow"></div>
				<?php echo form_label(lang("Privacy").":","project_fs_name_permissions",$opt["financial_form"]["lbl_fs_name_premissions"]); ?>
				<?php 
					$fs_name_permissions_attr = "id='project_fs_name_permissions'";
					echo form_dropdown("project_fs_name_permissions",$opt["financial_form"]["permissions_options"],$project["financial"]["name_privacy"],$fs_name_permissions_attr);
				?>
			</div>

			<?php echo form_label(lang("Name").":","project_fs_name",$opt["financial_form"]["lbl_fs_name"]); ?>
			<div class="fld">
				<?php echo form_input($opt["financial_form"]["fs_name"]); ?>
                <div id="err_project_fs_name" class="errormsg"></div>
			</div>
			<br>

			<hr>

			<div class="permissions_block">
				<div class="arrow"></div>
				<?php echo form_label(lang("Privacy").":","project_fs_contact_permissions",$opt["financial_form"]["lbl_fs_contact_premissions"]); ?>
				<?php 
					$fs_contact_permissions_attr = "id='project_fs_contact_permissions'";
					echo form_dropdown("project_fs_contact_permissions",$opt["financial_form"]["permissions_options"],$project["financial"]["contactname_privacy"],$fs_contact_permissions_attr);
				?>
			</div>

			<?php echo form_label(lang("ContactName").":","project_fs_contact",$opt["financial_form"]["lbl_fs_contact"]); ?>
			<div class="fld">
			<?php echo form_input($opt["financial_form"]["fs_contact"]); ?>
            <div id="err_project_fs_contact" class="errormsg"></div>
			</div>
			<br>
			
			<hr>

			<div class="permissions_block">
				<div class="arrow"></div>
				<?php echo form_label(lang("Privacy").":","project_fs_role_permissions",$opt["financial_form"]["lbl_fs_role_premissions"]); ?>
				<?php 
					$fs_role_permissions_attr = "id='project_fs_role_permissions'";
					echo form_dropdown("project_fs_role_permissions",$opt["financial_form"]["permissions_options"],$project["financial"]["role_privacy"],$fs_role_permissions_attr);
				?>
			</div>

			<?php echo form_label(lang("Role").":","project_fs_role",$opt["financial_form"]["lbl_fs_role"]); ?>
			<?php
				$fs_role_attr = "id='project_fs_role' class='role'";
				$fs_role_options = array(
					'Public'	=> lang('Public'),
					'Private'	=> lang('Private'),
					'PPP'		=> lang('PPP'),
					'Concession'	=> lang('Concession'),
					'Design, Build'	=> lang('Designb'),
				);
				echo form_dropdown("project_fs_role",$fs_role_options,$project["financial"]["role"],$fs_role_attr);
			?>	
				<?php echo form_input($opt["financial_form"]["fs_role"]);  ?>
                <div id="err_project_fs_role_other" class="errormsg"></div>
			<hr>

			<div class="permissions_block">
				<div class="arrow"></div>
				<?php echo form_label(lang("Privacy").":","project_fs_info_permissions",$opt["financial_form"]["lbl_fs_info_premissions"]); ?>
				<?php 
					$fs_info_permissions_attr = "id='project_fs_info_permissions'";
					echo form_dropdown("project_fs_info_permissions",$opt["financial_form"]["permissions_options"],$project["financial"]["contactinfo_privacy"],$fs_info_permissions_attr);
				?>
			</div>

			<?php echo form_label(lang("ContactInformation").":","project_fs_info",$opt["financial_form"]["lbl_fs_info"]); ?>
			<?php echo form_textarea($opt["financial_form"]["fs_info"]); ?>
			<br>
			
			<?php echo form_close();?>

		</div>

	</div>


	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-2">
	

		<div class="clearfix matrix_dropdown project_fund_sources">
		
			
			<ul id="load_fund_sources_form">			
			
			<?php
				
				foreach($project["fund_sources"] as $key=>$val)
				{
				?>
			
					<li id="row_id_<?php echo $val["id"];?>" class="">
						
						<div class="view clearfix">
							
							<span class="left"><?php echo $val["role"];?></span>

							<span class="middle"><strong><?php echo $val["name"];?></strong><br><?php echo $val["description"];?></span>

							<span class="middle"><?php echo $val["amount"];?></span>
										
							<a href="#projects/delete_fund_sources" class="right delete"><?php echo lang('Delete');?></a>
	
							<a onclick="rowtoggle(this.id);" href="javascript:void(0);" id="edit_fund_sources_<?php echo $val["id"];?>" class="right edit"><?php echo lang('Edit');?></a>
	
						</div>
	
					<div class="edit">
						<?php echo form_open('projects/update_fund_sources/'.$slug,array('id'=>'update_fund_sources_form_'.$val["id"],'name'=>'update_fund_sources_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>
						
						<?php 
							$opt['update_fund_sources_form'] = array(
									'lbl_name' => array(
											'class' => 'left_label'
											),
									'project_fund_sources_name'	=> array(
											'name' 		=> 'project_fund_sources_name',
											'id' 		=> 'project_fund_sources_name',
											'value'		=> $val["name"]
											),
									'lbl_role' => array(
											'class' => 'left_label'
											),
									'project_fund_sources_role'	=> array(
											'name' 		=> 'project_fund_sources_role',
											'id' 		=> 'project_fund_sources_role',
											'value'		=> $val["role"]
											),
									'lbl_amount' => array(
											'class' => 'left_label'
											),
									'project_fund_sources_amount'	=> array(
											'name' 		=> 'project_fund_sources_amount',
											'id' 		=> 'project_fund_sources_amount',
											'value'		=> $val["amount"]
											),
									'lbl_description' => array(
											'class' => 'left_label'
											),
									'project_fund_sources_desc'	=> array(
											'name' 		=> 'project_fund_sources_desc',
											'id' 		=> 'project_fund_sources_desc',
											'value'		=> $val["description"]
											),
									'lbl_permissions' => array(
											'class' => 'left_label'
											)
								);

						?>

						<?php echo form_hidden_custom("hdn_project_fund_sources_id",$val["id"],FALSE,"class='project_new_row'"); ?>
						
						<?php echo form_label(lang('Name').':', '', $opt['update_fund_sources_form']['lbl_name']);?>
						<div class="fld">
							<?php echo form_input($opt['update_fund_sources_form']['project_fund_sources_name']);?>
							<div id="err_project_fund_sources_name" class="errormsg"></div>
						</div>
						<br>

						<?php echo form_label(lang('Role').':', '', $opt['update_fund_sources_form']['lbl_role']);?>
						<div class="fld">
							<?php echo form_input($opt['update_fund_sources_form']['project_fund_sources_role']);?>
							<div id="err_project_fund_sources_role" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('Amount').':', '', $opt['update_fund_sources_form']['lbl_amount']);?>
						<div class="fld">
							<?php echo form_input($opt['update_fund_sources_form']['project_fund_sources_amount']);?>
							<div id="err_project_fund_sources_amount" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('Description').':', '', $opt['update_fund_sources_form']['lbl_description']);?>
						<div class="fld">
							<?php echo form_input($opt['update_fund_sources_form']['project_fund_sources_desc']);?>
							<div id="err_project_fund_sources_desc" class="errormsg"></div>
						</div>
						<br>

						<?php echo form_label(lang('Permissions').':', '', $opt['update_fund_sources_form']['lbl_permissions']);?>
						<?php
							$fund_sources_permission_attr = "id='project_fund_sources_permission'";
							$fund_sources_permission_options = array(
								"All"	=> "All",
								"Some"	=> "Some",
								"Other"	=> "Other"
							);
							echo form_dropdown("project_fund_sources_permission",$fund_sources_permission_options,$val["permissions"],$fund_sources_permission_attr);
						?>
						<br>
						
						<?php echo form_submit('ufund_submit', lang('Update'),'class = "light_green btn_lml"');?>
						
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
						<?php //lable like=>+ Add Fund Source?>
						<a class="edit project_row_add" href="javascript:void(0);" id="add_fundsource" onclick="rowtoggle(this.id);">+ <?php echo lang('AddFundSource');?></a>

					</div>

					<div class="edit add_new">
					
					<?php echo form_open('projects/add_fund_sources/'.$slug,array('id'=>'fund_sources_form','name'=>'fund_sources_form','method'=>'post','class'=>'ajax_form'));?>

						<?php 
							$opt['fund_sources_form'] = array(
									'lbl_name' => array(
											'class' => 'left_label'
											),
									'project_fund_sources_name'	=> array(
											'name' 		=> 'project_fund_sources_name',
											'id' 		=> 'project_fund_sources_name'
											),
									'lbl_role' => array(
											'class' => 'left_label'
											),
									'project_fund_sources_role'	=> array(
											'name' 		=> 'project_fund_sources_role',
											'id' 		=> 'project_fund_sources_role'
											),
									'lbl_amount' => array(
											'class' => 'left_label'
											),
									'project_fund_sources_amount'	=> array(
											'name' 		=> 'project_fund_sources_amount',
											'id' 		=> 'project_fund_sources_amount'
											),
									'lbl_description' => array(
											'class' => 'left_label'
											),
									'project_fund_sources_desc'	=> array(
											'name' 		=> 'project_fund_sources_desc',
											'id' 		=> 'project_fund_sources_desc'
											),
									'lbl_permissions' => array(
											'class' => 'left_label'
											)
								);

						?>

						<?php echo form_label(lang('Name').':', '', $opt['fund_sources_form']['lbl_name']);?>
						<div class="fld">
							<?php echo form_input($opt['fund_sources_form']['project_fund_sources_name']);?>
							<div id="err_project_fund_sources_name" class="errormsg"></div>
						</div>
						<br>

						<?php echo form_label(lang('Role').':', '', $opt['fund_sources_form']['lbl_role']);?>
						<div class="fld">
							<?php echo form_input($opt['fund_sources_form']['project_fund_sources_role']);?>
							<div id="err_project_fund_sources_role" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('Amount').':', '', $opt['fund_sources_form']['lbl_amount']);?>
						<div class="fld">
							<?php echo form_input($opt['fund_sources_form']['project_fund_sources_amount']);?>
							<div id="err_project_fund_sources_amount" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('Description').':', '', $opt['fund_sources_form']['lbl_description']);?>
						<div class="fld">
							<?php echo form_input($opt['fund_sources_form']['project_fund_sources_desc']);?>
							<div id="err_project_fund_sources_desc" class="errormsg"></div>
						</div>
						<br>

						<?php echo form_label(lang('Permissions').':', '', $opt['fund_sources_form']['lbl_permissions']);?>
						<?php
							$fund_sources_permission_attr = "id='project_fund_sources_permission'";
							$fund_sources_permission_options = array(
								"All"	=> lang("All"),
								"Some"	=> lang("Some"),
								"Other"	=> lang("Other")
							);
							echo form_dropdown("project_fund_sources_permission",$fund_sources_permission_options,'',$fund_sources_permission_attr);
						?>
						<br>

						<?php echo form_submit('fund_submit', lang('AddNew'),'class = "light_green btn_lml"');?>
						
						<?php echo form_close();?>

					</div>

				</li>
			</ul>

		</div>
					
	</div>


	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-3">
	
		<div class="clearfix matrix_dropdown project_roi">
		
			<ul id="load_roi_form">			
			
			<?php
				
				foreach($project["roi"] as $key=>$val)
				{
				?>
			
					<li id="row_id_<?php echo $val["id"];?>" class="">
						
						<div class="view clearfix">
							
							<span class="left"><?php echo $val["type"];?></span>

							<span class="middle"><strong><?php echo $val["name"];?></strong><br><?php echo $val["approach"];?></span>

							<span class="middle"><?php echo $val["percent"];?></span>
										
							<a href="#projects/delete_roi" class="right delete"><?php echo lang('Delete');?></a>
	
							<a onclick="rowtoggle(this.id);" href="javascript:void(0);" id="edit_roi_<?php echo $val["id"];?>" class="right edit"><?php echo lang('Edit');?></a>
							
							<?php if($val['keystudy']!= ''){ ?>
							
							<a href="<?php echo PROJECT_IMAGE_PATH.$val['keystudy'];?>" class="right files" target="_blank">
								<img src="/images/icons/<?php echo filetypeIcon($val['keystudy']);?>" alt="file" title="file">
							</a>
							
							<?php	}  ?>

	
						</div>
	
					<div class="edit">
					
					<?php echo form_open('projects/update_roi/'.$slug,array('id'=>'update_roi_form_'.$val["id"],'name'=>'update_roi_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>
					
						<?php 
							$opt['update_roi_form'] = array(
									'lbl_name' => array(
											'class' => 'left_label'
											),
									'project_roi_name'	=> array(
											'name' 		=> 'project_roi_name',
											'id' 		=> 'project_roi_name',
											'value'		=> $val["name"]
											),
									'lbl_percent' => array(
											'class' => 'left_label'
											),
									'project_roi_percent'	=> array(
											'name' 		=> 'project_roi_percent',
											'id' 		=> 'project_roi_percent',
											'value'		=> $val["percent"]
											),
									'lbl_type' => array(
											'class' => 'left_label'
											),
									'project_roi_type'	=> array(
											'name' 		=> 'project_roi_type',
											'id' 		=> 'project_roi_type',
											'value'		=> $val["type"]
											),
									'lbl_approach' => array(
											'class' => 'left_label'
											),
									'project_roi_approach'	=> array(
											'name' 		=> 'project_roi_approach',
											'id' 		=> 'project_roi_approach',
											'value'		=> $val["approach"]
											),
									'lbl_key_study' => array(
											'class' => 'left_label'
											),
									'project_roi_keystudy'		=> array(
											'name'	=> 'project_roi_keystudy',
                                            'id'	=> 'project_roi_keystudy'
									),
									'lbl_permissions' => array(
											'class' => 'left_label'
									)

								);

						?>

						<?php echo form_hidden("hdn_project_roi_id",$val["id"]); ?>
						<?php echo form_hidden("project_roi_keystud_hidden",$val["keystudy"]); ?>
						
						<?php echo form_label(lang('Name').':', '', $opt['update_roi_form']['lbl_name']);?>
						<div class="fld">
							<?php echo form_input($opt['update_roi_form']['project_roi_name']);?>
							<div id="err_project_roi_name" class="errormsg"></div>
						</div>
						<br>

						<?php echo form_label(lang('Percent').':', '', $opt['update_roi_form']['lbl_percent']);?>
						<div class="fld">
							<?php echo form_input($opt['update_roi_form']['project_roi_percent']);?>
							<div id="err_project_roi_percent" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('Type').':', '', $opt['update_roi_form']['lbl_type']);?>
						<div class="fld">
							<?php echo form_input($opt['update_roi_form']['project_roi_type']);?>
							<div id="err_project_roi_type" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('Approach').':', '', $opt['update_roi_form']['lbl_approach']);?>
						<div class="fld">
							<?php echo form_input($opt['update_roi_form']['project_roi_approach']);?>
							<div id="err_project_roi_approach" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('KeyStudy').':', '', $opt['update_roi_form']['lbl_key_study']);?>
						<div class="fld">
							<?php echo form_upload($opt['update_roi_form']['project_roi_keystudy']);?>
							<div id="err_project_roi_keystudy" class="errormsg"></div>
						</div>
						<br>


						<?php echo form_label(lang('Permissions').':', '', $opt['update_roi_form']['lbl_permissions']);?>
						<?php
							$roi_permission_attr = "id='project_roi_permission'";
							$roi_permission_options = array(
								"All"	=> lang("All"),
								"Some"	=> lang("Some"),
								"Other"	=> lang("Other")
							);
							echo form_dropdown("project_roi_permission",$roi_permission_options,$val["permission"],$roi_permission_attr);
						?>
						<br>

						<?php echo form_submit('uroi_submit', lang('Update'),'class = "light_green btn_lml"');?>
						
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
						<?php //lable like=>+ Add Return on Investment?>
						<a class="edit project_row_add" href="javascript:void(0);" id="add_retinvestment" onclick="rowtoggle(this.id);">+ <?php echo lang('AddReturnonInvestment');?></a>
					</div>

					<div class="edit add_new">
					<?php echo form_open('projects/add_roi/'.$slug,array('id'=>'roi_form','name'=>'roi_form','method'=>'post','class'=>'ajax_form'));?>
						<?php
							$opt['roi_form'] = array(
									'lbl_name' => array(
											'class' => 'left_label'
											),
									'project_roi_name'	=> array(
											'name' 		=> 'project_roi_name',
											'id' 		=> 'project_roi_name'
											),
									'lbl_percent' => array(
											'class' => 'left_label'
											),
									'project_roi_percent'	=> array(
											'name' 		=> 'project_roi_percent',
											'id' 		=> 'project_roi_percent'
											),
									'lbl_type' => array(
											'class' => 'left_label'
											),
									'project_roi_type'	=> array(
											'name' 		=> 'project_roi_type',
											'id' 		=> 'project_roi_type'
											),
									'lbl_approach' => array(
											'class' => 'left_label'
											),
									'project_roi_approach'	=> array(
											'name' 		=> 'project_roi_approach',
											'id' 		=> 'project_roi_approach'
											),
									'lbl_key_study' => array(
											'class' => 'left_label'
											),
									'project_roi_keystudy'		=> array(
                                            'id'	=> 'project_roi_keystudy',
											'name'	=> 'project_roi_keystudy'
									),
									'lbl_permissions' => array(
											'class' => 'left_label'
									)

								);

						?>

						<?php echo form_hidden("hdn_project_roi_id",""); ?>
						
						<?php echo form_label(lang('Name').':', '', $opt['roi_form']['lbl_name']);?>
						<div class="fld">
							<?php echo form_input($opt['roi_form']['project_roi_name']);?>
							<div id="err_project_roi_name" class="errormsg"></div>
						</div>
						<br>

						<?php echo form_label(lang('Percent').':', '', $opt['roi_form']['lbl_percent']);?>
						<div class="fld">
							<?php echo form_input($opt['roi_form']['project_roi_percent']);?>
							<div id="err_project_roi_percent" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('Type').':', '', $opt['roi_form']['lbl_type']);?>
						<div class="fld">
							<?php echo form_input($opt['roi_form']['project_roi_type']);?>
							<div id="err_project_roi_type" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('Approach').':', '', $opt['roi_form']['lbl_approach']);?>
						<div class="fld">
							<?php echo form_input($opt['roi_form']['project_roi_approach']);?>
							<div id="err_project_roi_approach" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('KeyStudy').':', '', $opt['roi_form']['lbl_key_study']);?>
						<div class="fld">
							<?php echo form_upload($opt['roi_form']['project_roi_keystudy']);?>
							<div id="err_project_roi_keystudy" class="errormsg"></div>
						</div>
						<br>

						<?php echo form_label(lang('Permissions').':', '', $opt['roi_form']['lbl_permissions']);?>
						<?php
							$roi_permission_attr = "id='project_roi_permission'";
							$roi_permission_options = array(
								"All"	=> "All",
								"Some"	=> "Some",
								"Other"	=> "Other"
							);
							echo form_dropdown("project_roi_permission",$roi_permission_options,'',$roi_permission_attr);
						?>
						<br>

						<?php echo form_submit('roi_submit', lang('AddNew'),'class = "light_green btn_lml"');?>
						
						<?php echo form_close();?>

					</div>

				</li>
			</ul>

		</div>
	
		
	</div>


	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-4">
	
	


		<div class="clearfix matrix_dropdown project_critical_participants">
		
		<ul id="load_critical_participants_form">			
			
			<?php
				
				foreach($project["critical_participants"] as $key=>$val)
				{
				?>
			
					<li id="row_id_<?php echo $val["id"];?>" class="">
						
						<div class="view clearfix">
							
							<span class="left"><?php echo $val["role"];?></span>

							<span class="middle"><strong><?php echo $val["name"];?></strong><br><?php echo $val["description"];?></span>

										
							<a href="#projects/delete_critical_participants" class="right delete"></a><?php echo lang('Delete');?></a>
	
						<a onclick="rowtoggle(this.id);" href="javascript:void(0);" id="edit_critical_participant_<?php echo $val["id"];?>" class="right edit"><?php echo lang('Edit');?></a>
	
						</div>
	
					<div class="edit">
					<?php echo form_open('projects/update_critical_participants/'.$slug,array('id'=>'update_critical_participants_form_'.$val["id"],'name'=>'update_critical_participants_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>
						<?php 
							$opt['update_critical_participants_form'] = array(
								'lbl_name' => array(
										'class' => 'left_label'
										),
								'project_critical_participants_name'	=> array(
										'name' 		=> 'project_critical_participants_name',
										'id' 		=> 'project_critical_participants_name',
										'value'		=> $val["name"]
										),
								'lbl_role' => array(
										'class' => 'left_label'
										),
								'project_critical_participants_role'	=> array(
										'name' 		=> 'project_critical_participants_role',
										'id' 		=> 'project_critical_participants_role',
										'value'		=> $val["role"]
										),
								'lbl_desc' => array(
										'class' => 'left_label'
										),
								'project_critical_participants_desc'	=> array(
										'name' 		=> 'project_critical_participants_desc',
										'id' 		=> 'project_critical_participants_desc',
										'value'		=> $val["description"]
										),
								'lbl_permissions' => array(
										'class' => 'left_label'
								)

							);
						?>
						
						<?php echo form_hidden("hdn_project_critical_participants_id",$val["id"]); ?>

						<?php echo form_label(lang('Name').':', '', $opt['update_critical_participants_form']['lbl_name']);?>
						<div class="fld">
							<?php echo form_input($opt['update_critical_participants_form']['project_critical_participants_name']);?>
							<div id="err_project_critical_participants_name" class="errormsg"></div>
						</div>
						<br>

						<?php echo form_label(lang('Role').':', '', $opt['update_critical_participants_form']['lbl_role']);?>
						<div class="fld">
							<?php echo form_input($opt['update_critical_participants_form']['project_critical_participants_role']);?>
							<div id="err_project_critical_participants_role" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('Description').':', '', $opt['update_critical_participants_form']['lbl_desc']);?>
						<div class="fld">
							<?php echo form_input($opt['update_critical_participants_form']['project_critical_participants_desc']);?>
							<div id="err_project_critical_participants_desc" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('Permissions').':', '', $opt['update_critical_participants_form']['lbl_permissions']);?>
						<?php
							$critical_participants_permission_attr = "id='project_critical_participants_permission'";
							$critical_participants_permission_options = array(
								"All"	=> lang("All"),
								"Some"	=> lang("Some"),
								"Other"	=> lang("Other")
							);
							echo form_dropdown("project_critical_participants_permission",$critical_participants_permission_options,$val["permission"],$critical_participants_permission_attr);
						?>
						<br>

						<?php echo form_submit('ucritical_submit', lang('Update'),'class = "light_green btn_lml"');?>

						<?php echo form_close(); ?>

					</div>
					</li>
				<?php
				}
			?>
		</ul>

		<ul>								
				<li>
					<div class="view">
						<?php //lable like =>+ Add Participant?>
						<a class="edit project_row_add" href="javascript:void(0);" id="add_participant" onclick="rowtoggle(this.id);">+ <?php echo lang('AddParticipant');?></a>

					</div>

					<div class="edit add_new">
						
						<?php echo form_open('projects/add_critical_participants/'.$slug,array('id'=>'critical_participants_form','name'=>'critical_participants_form','method'=>'post','class'=>'ajax_form'));?>
						
						<?php 
							$opt['critical_participants_form'] = array(
								'lbl_name' => array(
										'class' => 'left_label'
										),
								'project_critical_participants_name'	=> array(
										'name' 		=> 'project_critical_participants_name',
										'id' 		=> 'project_critical_participants_name'
										),
								'lbl_role' => array(
										'class' => 'left_label'
										),
								'project_critical_participants_role'	=> array(
										'name' 		=> 'project_critical_participants_role',
										'id' 		=> 'project_critical_participants_role'
										),
								'lbl_desc' => array(
										'class' => 'left_label'
										),
								'project_critical_participants_desc'	=> array(
										'name' 		=> 'project_critical_participants_desc',
										'id' 		=> 'project_critical_participants_desc'
										),
								'lbl_permissions' => array(
										'class' => 'left_label'
								)

							);
						?>

						<?php echo form_label(lang('Name').':', '', $opt['critical_participants_form']['lbl_name']);?>
						<div class="fld">
							<?php echo form_input($opt['critical_participants_form']['project_critical_participants_name']);?>
							<div id="err_project_critical_participants_name" class="errormsg"></div>
						</div>
						<br>

						<?php echo form_label(lang('Role').':', '', $opt['critical_participants_form']['lbl_role']);?>
						<div class="fld">
							<?php echo form_input($opt['critical_participants_form']['project_critical_participants_role']);?>
							<div id="err_project_critical_participants_role" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('Description').':', '', $opt['critical_participants_form']['lbl_desc']);?>
						<div class="fld">
							<?php echo form_input($opt['critical_participants_form']['project_critical_participants_desc']);?>
							<div id="err_project_critical_participants_desc" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('Permissions').':', '', $opt['critical_participants_form']['lbl_permissions']);?>
						<?php
							$critical_participants_permission_attr = "id='project_critical_participants_permission'";
							$critical_participants_permission_options = array(
								"All"	=> "All",
								"Some"	=> "Some",
								"Other"	=> "Other"
							);
							echo form_dropdown("project_critical_participants_permission",$critical_participants_permission_options,'',$critical_participants_permission_attr);
						?>
						<br>

						<?php echo form_submit('critical_submit', lang('AddNew'),'class = "light_green btn_lml"');?>
						
						<?php echo form_close(); ?>

					</div>

				</li>
			</ul>

		</div>
		
		

	</div>

</div>
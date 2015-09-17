<div id="profile_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all project_form" style="display: block;">

	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-1">Financial Structure</a></li>
		<li class="ui-state-default ui-corner-top"><a href="#tabs-2">Fund Sources</a></li>
		<li class="ui-state-default ui-corner-top"><a href="#tabs-3">Return on Investment</a></li>
		<li class="ui-state-default ui-corner-top"><a href="#tabs-4">Critical Participants</a></li>
	</ul>

	
	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-1">
	
	<div style="display:table">
		<div class="clearfix" id="div_project_info_form" style="position:relative;width:100%;float:left;">
		
		<?php echo form_open('projects/add_financial/'.$slug,array('id'=>'financial_form','name'=>'financial_form','method'=>'post','class'=>'ajax_add_form'));?>
			<?php 
				if(count($project["financial"]) <= 0) {
					$project["financial"] = array("name"=>"","contactname"=>"","role_others"=>"","contactinfo"=>"","name_privacy"=>"","contactname_privacy"=>"","role_privacy"=>"","role"=>"","contactinfo_privacy"=>"","fs_info"=>"");
				}
				$opt["financial_form"] = array(
					'lbl_fs_name_premissions' => array(
						'class'	=> 'above_label'
					),
					'permissions_options' => array(
						'Private'	=> 'Private',
						'Specific'	=> 'Specific',
						'Similar Project Owners'	=> 'Similar Project Owners'
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
						'placeholder'	=> 'Other...',
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
			<div class="contenttitle2 floatleft">
	            <h3>Financial Form</h3>
	        </div>
	        <br/>
			<div class="field floatleft">
				<div class="fld floatleft" style="width:341px">
					<?php echo form_label("Name:","project_fs_name",$opt["financial_form"]["lbl_fs_name"]); ?>
					<?php echo form_input($opt["financial_form"]["fs_name"]); ?>
					<div class="errormsg"></div>
				</div>
				
				<div class="permissions_block floatleft">
					<div class="arrow"></div>
					<?php echo form_label("Privacy:","project_fs_name_permissions",$opt["financial_form"]["lbl_fs_name_premissions"]); ?>
					<?php 
						$fs_name_permissions_attr = "id='project_fs_name_permissions'";
						echo form_dropdown("project_fs_name_permissions",$opt["financial_form"]["permissions_options"],$project["financial"]["name_privacy"],$fs_name_permissions_attr);
					?>
				</div>
			</div>
			<br>
			<div class="field floatleft">
				<div class="floatleft" style="width:341px">
					<?php echo form_label("Contact Name:","project_fs_contact",$opt["financial_form"]["lbl_fs_contact"]); ?>
					<?php echo form_input($opt["financial_form"]["fs_contact"]); ?>
					<div class="errormsg"></div>
				</div>
				<div class="permissions_block floatleft">
					<div class="arrow"></div>
					<?php echo form_label("Privacy:","project_fs_contact_permissions",$opt["financial_form"]["lbl_fs_contact_premissions"]); ?>
					<?php 
						$fs_contact_permissions_attr = "id='project_fs_contact_permissions'";
						echo form_dropdown("project_fs_contact_permissions",$opt["financial_form"]["permissions_options"],$project["financial"]["contactname_privacy"],$fs_contact_permissions_attr);
					?>
				</div>
			</div>
			<br>
			<div class="field floatleft">
				<div class="floatleft" style="width:341px;">
				<?php echo form_label("Role:","project_fs_role",$opt["financial_form"]["lbl_fs_role"]); ?>
				<?php
					$fs_role_attr = "id='project_fs_role' class='role'";
					$fs_role_options = array(
						'Public'	=> 'Public',
						'Private'	=> 'Private',
						'PPP'		=> 'PPP',
						'Concession'	=> 'Concession',
						'Design, Build'	=> 'Design, Build',
					);
					
				?>
					<div class="floatleft" style="width:341px;">
						<?php echo form_dropdown("project_fs_role",$fs_role_options,$project["financial"]["role"],$fs_role_attr);?>
					</div>	
				<br/><br/><br>
					<div class="floatleft" style="width:341px;">
						<?php echo form_input($opt["financial_form"]["fs_role"]);  ?>
					</div>
				</div>
				<div class="permissions_block floatleft">
					<div class="arrow"></div>
					<?php echo form_label("Privacy:","project_fs_role_permissions",$opt["financial_form"]["lbl_fs_role_premissions"]); ?>
					<?php 
						$fs_role_permissions_attr = "id='project_fs_role_permissions'";
						echo form_dropdown("project_fs_role_permissions",$opt["financial_form"]["permissions_options"],$project["financial"]["role_privacy"],$fs_role_permissions_attr);
					?>
				</div>
			</div>
			<br>
			<div class="field floatleft">
				<div class="floatleft" style="width:341px"> 
				<?php echo form_label("Contact Information:","project_fs_info",$opt["financial_form"]["lbl_fs_info"]); ?>
				<?php echo form_textarea($opt["financial_form"]["fs_info"]); ?>
				</div>
				<div class="permissions_block floatleft">
					<div class="arrow"></div>
					<?php echo form_label("Privacy:","project_fs_info_permissions",$opt["financial_form"]["lbl_fs_info_premissions"]); ?>
					<?php 
						$fs_info_permissions_attr = "id='project_fs_info_permissions'";
						echo form_dropdown("project_fs_info_permissions",$opt["financial_form"]["permissions_options"],$project["financial"]["contactinfo_privacy"],$fs_info_permissions_attr);
					?>
				</div>
			</div>
			<br>
			<?php echo form_submit('submit', 'Update Financial Structure','class = "light_green btn_lbl"');?>
			<?php echo form_close();?>

		</div>
		
	</div>

	</div>


	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-2">
	

		<div class="clearfix matrix_dropdown project_fund_sources">
		
			<div id="tab_innerarea_list">
				<div class="view_list clearfix">
					<div class="contenttitle2">
			            <h3>Fund Source List</h3>
			        </div>
			        <div class="notibar" style="display:none">
					    <a class="close"></a>
					    <p></p>
					</div>
					
					 <div class="tableoptions">
					        	<button class="deletebutton radius3" title="Delete Selected" name="dyntable_fundsources" id="#/admin.php/projects/delete_fundsources">Delete Fund Sources</button> &nbsp;
						</div><!--tableoptions-->
					<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_fundsources">
					    <colgroup>
					        <col class="con0" style="width: 4%" />
					        <col class="con1" />
					        <col class="con0" />
					        <col class="con1" />
					        <col class="con0" />
					    </colgroup>
					    <thead>
					        <tr>
					          <th class="head0 nosort" align="center"><?php echo form_checkbox(array("id"=>"select_all_header","name"=>"select_all_header","class"=>"checkall")); ?></th>
					          	<th class="head1">ID</th>
					            <th class="head0">Name</th>
					            <th class="head1">Role</th>
					            <th class="head1">Amount</th>
					            <th class="head1">Description</th>
					            <th class="head1">Action</th>			                        
					        </tr>
					    </thead>
					    <tfoot>
					        <tr>
					          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
					            <th class="head1">ID</th>
					            <th class="head0">Name</th>
					            <th class="head1">Role</th>
					            <th class="head1">Amount</th>
					            <th class="head1">Description</th>
					            <th class="head1">Action</th>
					        </tr>
					    </tfoot>
					    <tbody>
					    	<?php 
					    	
					    	if(count($project["fund_sources"]) > 0)
							{
								foreach($project["fund_sources"] as $key=>$val)
								{
							?>
							<tr>
							  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$val['id']."","name"=>"select_".$val['id']."","value"=>$val['id'])); ?></span></td>
					            <td><?php echo $val['id']; ?></td>
					            <td><?php echo $val['name'];?></td>
					            <td><?php echo $val['role'];?></td>
					            <td><?php echo $val['amount'];?></td>
					            <td><?php echo $val['description'];?></td>
					            <td><a href="javascript:void(0);" onclick="load_project_edit_from('<?php echo $slug;?>',<?php echo $val['id'];?>,'project_fund_sources','add_project_fund_sources')">Edit</a></td>
					            
					        </tr>
					
							<?php
									
								}
							}
							?>
					    </tbody>
					</table>

			        
			    </div>
			   <div class="add_form" id="add_project_fund_sources">
					<div class="contenttitle2">
					    <h3>Add Fund Source</h3>
					</div>
					<?php echo form_open('projects/add_fund_sources/'.$slug,array('id'=>'fund_sources_form','name'=>'fund_sources_form','method'=>'post','class'=>'ajax_add_form'));?>

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

					<?php echo form_label('Name:', '', $opt['fund_sources_form']['lbl_name']);?>
					<div class="fld">
						<?php echo form_input($opt['fund_sources_form']['project_fund_sources_name']);?>
						<div id="err_project_fund_sources_name" class="errormsg"></div>
					</div>
					<?php echo br(); ?>

					<?php echo form_label('Role:', '', $opt['fund_sources_form']['lbl_role']);?>
					<div class="fld">
						<?php echo form_input($opt['fund_sources_form']['project_fund_sources_role']);?>
						<div id="err_project_fund_sources_role" class="errormsg"></div>
					</div>
					<?php echo br(); ?>
					
					<?php echo form_label('Amount:', '', $opt['fund_sources_form']['lbl_amount']);?>
					<div class="fld">
						<?php echo form_input($opt['fund_sources_form']['project_fund_sources_amount']);?>
						<div id="err_project_fund_sources_amount" class="errormsg"></div>
					</div>
					<?php echo br(); ?>
					
					<?php echo form_label('Description:', '', $opt['fund_sources_form']['lbl_description']);?>
					<div class="fld">
						<?php echo form_input($opt['fund_sources_form']['project_fund_sources_desc']);?>
						<div id="err_project_fund_sources_desc" class="errormsg"></div>
					</div>
					<?php echo br(); ?>

					<?php echo form_label('Permissions:', '', $opt['fund_sources_form']['lbl_permissions']);?>
					<?php
						$fund_sources_permission_attr = "id='project_fund_sources_permission'";
						$fund_sources_permission_options = array(
							"All"	=> "All",
							"Some"	=> "Some",
							"Other"	=> "Other"
						);
						echo form_dropdown("project_fund_sources_permission",$fund_sources_permission_options,'',$fund_sources_permission_attr);
					?>
					<?php echo br(); ?>

					<?php echo form_submit('submit', 'Add New','class = "light_green btn_lml"');?>
					
					<?php echo form_close();?>
				</div>
			</div>
			
		</div>
					
	</div>


	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-3">
	
		<div class="clearfix matrix_dropdown project_roi">
		
			<div id="tab_innerarea_list">
				<div class="view_list clearfix">
					<div class="contenttitle2">
			            <h3>ROI List</h3>
			        </div>
			        <div class="notibar" style="display:none">
					    <a class="close"></a>
					    <p></p>
					</div>
					
					 <div class="tableoptions">
					        	<button class="deletebutton radius3" title="Delete Selected" name="dyntable_roi" id="#/admin.php/projects/delete_roi">Delete ROI</button> &nbsp;
						</div><!--tableoptions-->
					<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_roi">
					    <colgroup>
					        <col class="con0" style="width: 4%" />
					        <col class="con1" />
					        <col class="con0" />
					        <col class="con1" />
					        <col class="con0" />
					    </colgroup>
					    <thead>
					        <tr>
					          <th class="head0 nosort" align="center"><?php echo form_checkbox(array("id"=>"select_all_header","name"=>"select_all_header","class"=>"checkall")); ?></th>
					          	<th class="head1">ID</th>
					            <th class="head0">Name</th>
					            <th class="head1">Pecent</th>
					            <th class="head1">Type</th>
					            <th class="head1">Approach</th>
					            <th class="head1">Key Study</th>
					            <th class="head1">Action</th>
					        </tr>
					    </thead>
					    <tfoot>
					        <tr>
					          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
					          	<th class="head1">ID</th>
					            <th class="head0">Name</th>
					            <th class="head1">Percent</th>
					            <th class="head1">Type</th>
					            <th class="head1">Approach</th>
					            <th class="head1">Key Study</th>
					            <th class="head1">Action</th>

					        </tr>
					    </tfoot>
					    <tbody>
					    	<?php 
					    	
					    	if(count($project["roi"]) > 0)
							{
								foreach($project["roi"] as $key=>$val)
								{
							?>
							<tr>
							  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$val['id']."","name"=>"select_".$val['id']."","value"=>$val['id'])); ?></span></td>
					            <td><?php echo $val['id']; ?></td>
					            <td><?php echo $val['name'];?></td>
					            <td><?php echo $val['percent'];?></td>
					            <td><?php echo $val['type'];?></td>
					            <td><?php echo $val['approach'];?></td>
					             <td>
						            <?php if($val['keystudy']!= ''){ ?>
											<a href="<?php echo PROJECT_IMAGE_PATH.$val['keystudy'];?>" class="left files" target="_blank">
												<img src="/images/icons/<?php echo filetypeIcon($val['keystudy']);?>" alt="file" title="file">
											</a>
									<?php } ?>
								</td>
					            <td><a href="javascript:void(0);" onclick="load_project_edit_from('<?php echo $slug;?>',<?php echo $val['id'];?>,'project_roi','add_project_roi')">Edit</a></td>
					        </tr>
					
							<?php
									
								}
							}
							?>
					    </tbody>
					</table>

			        
			    </div>
			   <div class="add_form" id="add_project_roi">
					<div class="contenttitle2">
					    <h3>Add Return on Investment:</h3>
					</div>
					<?php echo form_open_multipart('projects/add_roi/'.$slug,array('id'=>'roi_form','name'=>'roi_form','method'=>'post','class'=>'ajax_add_form'));?>
					
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
										'name'	=> 'project_roi_keystudy'
								),
								'lbl_permissions' => array(
										'class' => 'left_label'
								)

							);

					?>

					<?php echo form_hidden("hdn_project_roi_id",""); ?>
					
					<?php echo form_label('Name:', '', $opt['roi_form']['lbl_name']);?>
					<div class="fld">
						<?php echo form_input($opt['roi_form']['project_roi_name']);?>
						<div id="err_project_roi_name" class="errormsg"></div>
					</div>
					<?php echo br(); ?>

					<?php echo form_label('Percent:', '', $opt['roi_form']['lbl_percent']);?>
					<div class="fld">
						<?php echo form_input($opt['roi_form']['project_roi_percent']);?>
						<div id="err_project_roi_percent" class="errormsg"></div>
					</div>
					<?php echo br(); ?>
					
					<?php echo form_label('Type:', '', $opt['roi_form']['lbl_type']);?>
					<div class="fld">
						<?php echo form_input($opt['roi_form']['project_roi_type']);?>
						<div id="err_project_roi_type" class="errormsg"></div>
					</div>
					<?php echo br(); ?>
					
					<?php echo form_label('Approach:', '', $opt['roi_form']['lbl_approach']);?>
					<div class="fld">
						<?php echo form_input($opt['roi_form']['project_roi_approach']);?>
						<div id="err_project_roi_approach" class="errormsg"></div>
					</div>
					<?php echo br(); ?>
					
					<?php echo form_label('Key Study:', '', $opt['roi_form']['lbl_key_study']);?>
					<div class="fld">
						<?php echo form_upload($opt['roi_form']['project_roi_keystudy']);?>
						<div id="err_project_roi_keystudy" class="errormsg"></div>
					</div>
					<?php echo br(); ?>


					<?php echo form_label('Permissions:', '', $opt['roi_form']['lbl_permissions']);?>
					<?php
						$roi_permission_attr = "id='project_roi_permission'";
						$roi_permission_options = array(
							"All"	=> "All",
							"Some"	=> "Some",
							"Other"	=> "Other"
						);
						echo form_dropdown("project_roi_permission",$roi_permission_options,'',$roi_permission_attr);
					?>
					<?php echo br(); ?>

					<?php echo form_submit('submit', 'Add New','class = "light_green btn_lml"');?>
					
					<?php echo form_close();?>
					
				</div>
			</div>

		</div>
	
		
	</div>


	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-4">
	
	


		<div class="clearfix matrix_dropdown project_critical_participants">
		
		
		
		<div id="tab_innerarea_list">
			<div class="view_list clearfix">
				<div class="contenttitle2">
		            <h3>Participant List</h3>
		        </div>
		        
		        <div class="notibar" style="display:none">
					    <a class="close"></a>
					    <p></p>
					</div>
					
					 <div class="tableoptions">
					        	<button class="deletebutton radius3" title="Delete Selected" name="dyntable_criticalparticipant" id="#/admin.php/projects/delete_critical_participants">Delete Participants</button> &nbsp;
						</div><!--tableoptions-->
					<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_criticalparticipant">
					    <colgroup>
					        <col class="con0" style="width: 4%" />
					        <col class="con1" />
					        <col class="con0" />
					        <col class="con1" />
					        <col class="con0" />
					    </colgroup>
					    <thead>
					        <tr>
					          <th class="head0 nosort" align="center"><?php echo form_checkbox(array("id"=>"select_all_header","name"=>"select_all_header","class"=>"checkall")); ?></th>
					          	<th class="head1">ID</th>
					            <th class="head0">Name</th>
					            <th class="head1">Role</th>
					            <th class="head1">Description</th>
					            <th class="head1">permission</th>
					        </tr>
					    </thead>
					    <tfoot>
					        <tr>
					          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
					          	<th class="head1">ID</th>
					            <th class="head0">Name</th>
					            <th class="head1">Role</th>
					            <th class="head1">Description</th>
					            <th class="head1">permission</th>
					        </tr>
					    </tfoot>
					    <tbody>
					    	<?php 
					    	
					    	if(count($project["critical_participants"]) > 0)
							{
								foreach($project["critical_participants"] as $key=>$val)
								{
							?>
							<tr>
							  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$val['id']."","name"=>"select_".$val['id']."","value"=>$val['id'])); ?></span></td>
					            <td><?php echo $val['id']; ?></td>
					            <td><?php echo $val['name'];?></td>
					            <td><?php echo $val['role'];?></td>
					            <td><?php echo $val['description'];?></td>
					            <td><a href="javascript:void(0);" onclick="load_project_edit_from('<?php echo $slug;?>',<?php echo $val['id'];?>,'project_critical_participants','add_project_critical_participant')">Edit</a></td>
					        </tr>
					
							<?php
									
								}
							}
							?>
					    </tbody>
					</table>

		        
		    </div>
		   <div class="add_form" id="add_project_critical_participant">
				<div class="contenttitle2">
				    <h3>Add Participant:</h3>
				</div>
				
				<?php echo form_open('projects/add_critical_participants/'.$slug,array('id'=>'critical_participants_form','name'=>'critical_participants_form','method'=>'post','class'=>'ajax_add_form'));?>
				
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

				<?php echo form_label('Name:', '', $opt['critical_participants_form']['lbl_name']);?>
				<div class="fld">
					<?php echo form_input($opt['critical_participants_form']['project_critical_participants_name']);?>
					<div id="err_project_critical_participants_name" class="errormsg"></div>
				</div>
				<?php echo br(); ?>

				<?php echo form_label('Role:', '', $opt['critical_participants_form']['lbl_role']);?>
				<div class="fld">
					<?php echo form_input($opt['critical_participants_form']['project_critical_participants_role']);?>
					<div id="err_project_critical_participants_role" class="errormsg"></div>
				</div>
				<?php echo br(); ?>
				
				<?php echo form_label('Description:', '', $opt['critical_participants_form']['lbl_desc']);?>
				<div class="fld">
					<?php echo form_input($opt['critical_participants_form']['project_critical_participants_desc']);?>
					<div id="err_project_critical_participants_desc" class="errormsg"></div>
				</div>
				<?php echo br(); ?>
				
				<?php echo form_label('Permissions:', '', $opt['critical_participants_form']['lbl_permissions']);?>
				<?php
					$critical_participants_permission_attr = "id='project_critical_participants_permission'";
					$critical_participants_permission_options = array(
						"All"	=> "All",
						"Some"	=> "Some",
						"Other"	=> "Other"
					);
					echo form_dropdown("project_critical_participants_permission",$critical_participants_permission_options,'',$critical_participants_permission_attr);
				?>
				<?php echo br(); ?>

				<?php echo form_submit('submit', 'Add New','class = "light_green btn_lml"');?>
				
				<?php echo form_close(); ?>			
			</div>
			
		</div>
		</div>
	</div>
</div>
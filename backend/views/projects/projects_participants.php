<div id="profile_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all project_form" style="display: block;">

				<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
					<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-1">Public</a></li>
					<li class="ui-state-default ui-corner-top"><a href="#tabs-2">Political</a></li>
					<li class="ui-state-default ui-corner-top"><a href="#tabs-3">Companies</a></li>
					<li class="ui-state-default ui-corner-top"><a href="#tabs-4">Owners</a></li>
					
				</ul>

		
				<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-1">

					<div class="clearfix matrix_dropdown project_participants_public">
						
						<div id="tab_innerarea_list">
							<div class="view_list clearfix">
								<div class="contenttitle2">
						            <h3>Public Participant List</h3>
						        </div>
						        <div class="notibar" style="display:none">
								    <a class="close"></a>
								    <p></p>
								</div>
								
								 <div class="tableoptions">
								        	<button class="deletebutton radius3" title="Delete Selected" name="dyntable_public" id="#/admin.php/projects/delete_public_participant">Delete Participants</button> &nbsp;
									</div><!--tableoptions-->
								<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_public">
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
								            <th class="head1">Type</th>
								            <th class="head1">Description</th>
								            <th class="head1">Action</th>			                        
								        </tr>
								    </thead>
								    <tfoot>
								        <tr>
								          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
								            <th class="head1">ID</th>
								            <th class="head0">Name</th>
								            <th class="head1">Type</th>
								            <th class="head1">Description</th>
								            <th class="head1">Action</th>
								        </tr>
								    </tfoot>
								    <tbody>
								    	<?php 
								    	
								    	if(count($project["public"]) > 0)
										{
											foreach($project["public"] as $key=>$val)
											{
										?>
										<tr>
										  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$val['id']."","name"=>"select_".$val['id']."","value"=>$val['id'])); ?></span></td>
								            <td><?php echo $val['id']; ?></td>
								            <td><?php echo $val['name'];?></td>
								            <td><?php echo $val['type'];?></td>
								            <td><?php echo $val['description'];?></td>
								            <td><a href="javascript:void(0);" onclick="load_project_edit_from('<?php echo $slug;?>',<?php echo $val['id'];?>,'participants_public','add_participants_public')">Edit</a></td>
								        </tr>
								
										<?php
												
											}
										}
										?>
								    </tbody>
								</table>

						    </div>
						   <div class="add_form" id="add_participants_public">
								<div class="contenttitle2">
								    <h3>Add Public Participant:</h3>
								</div>
								
								<?php echo form_open('projects/add_participants_public/'.$slug,array('id'=>'participants_public_form','name'=>'participants_public_form','method'=>'post','class'=>'ajax_add_form'));?>

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
									
									
									<?php echo form_label('Name:', 'participants_public_name', $opt['participants_public_form']['lbl_name']);?>
									<div class="fld" >
			
										<?php echo form_input($opt['participants_public_form']['project_participants_public_name']);?>
										<div class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label('Type:', 'participants_public_type', $opt['participants_public_form']['lbl_type']);?>
									<div class="fld" >
			
										<?php echo form_input($opt['participants_public_form']['project_participants_public_type']);?>
										<div class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label('Description:', 'project_participants_public_desc', $opt['participants_public_form']['lbl_description']);?>
									<div class="fld" >
			
										<?php echo form_input($opt['participants_public_form']['project_participants_public_desc']);?>
										<div class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label('Permissions:', 'project_participants_public_permissions', $opt['participants_public_form']['lbl_permissions']);?>
									<div class="fld">
									<?php
										$permissions_attr = 'id="project_participants_public_permissions"';
										$permissions_options = array(
											'All'		=> 'All',
											'Some' 		=> 'Some',
											'Other' 	=> 'Other'
										);
										echo form_dropdown('project_participants_public_permissions', $permissions_options,'',$permissions_attr);
									?>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_submit('submit', 'Add New','class = "light_green btn_lml"');?>
									
									<?php echo form_close();?>
							</div>
						</div>						

					</div>

				</div>

		
				<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-2">
				
					<div class="clearfix matrix_dropdown project_participants_political">
					
						<div id="tab_innerarea_list">
							<div class="view_list clearfix">
								<div class="contenttitle2">
						            <h3>Political Participant List</h3>
						        </div>
						        
						        <div class="notibar" style="display:none">
								    <a class="close"></a>
								    <p></p>
								</div>
								
								 <div class="tableoptions">
								        	<button class="deletebutton radius3" title="Delete Selected" name="dyntable_political" id="#/admin.php/projects/delete_political_participant">Delete Political</button> &nbsp;
									</div><!--tableoptions-->
								<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_political">
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
								            <th class="head1">Type</th>
								            <th class="head1">Description</th>
								            <th class="head1">Action</th>			                        
								        </tr>
								    </thead>
								    <tfoot>
								        <tr>
								          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
								            <th class="head1">ID</th>
								            <th class="head0">Name</th>
								            <th class="head1">Type</th>
								            <th class="head1">Description</th>
								            <th class="head1">Action</th>
								        </tr>
								    </tfoot>
								    <tbody>
								    	<?php 
								    	
								    	if(count($project["political"]) > 0)
										{
											foreach($project["political"] as $key=>$val)
											{
										?>
										<tr>
										  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$val['id']."","name"=>"select_".$val['id']."","value"=>$val['id'])); ?></span></td>
						            <td><?php echo $val['id']; ?></td>
						            <td><?php echo $val['name'];?></td>
						            <td><?php echo $val['type'];?></td>
						            <td><?php echo $val['description'];?></td>
						            <td><a href="javascript:void(0);" onclick="load_project_edit_from('<?php echo $slug;?>',<?php echo $val['id'];?>,'participants_political','add_participants_political')">Edit</a></td>
						        </tr>
						
										<?php
												
											}
										}
										?>
								    </tbody>
								</table>
						    </div>
						   <div class="add_form" id="add_participants_political">
								<div class="contenttitle2">
								    <h3>Add Political Participant</h3>
								</div>
								
								<?php echo form_open('projects/add_participants_political/'.$slug,array('id'=>'participants_political_form','name'=>'participants_political_form','method'=>'post','class'=>'ajax_add_form'));?>

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
									
									
									<?php echo form_label('Name:', 'participants_political_name', $opt['participants_political_form']['lbl_name']);?>
									<div class="fld" >
			
										<?php echo form_input($opt['participants_political_form']['project_participants_political_name']);?>
										<div class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label('Type:', 'participants_political_type', $opt['participants_political_form']['lbl_type']);?>
									<div class="fld" >
			
										<?php echo form_input($opt['participants_political_form']['project_participants_political_type']);?>
										<div class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label('Description:', 'project_participants_political_desc', $opt['participants_political_form']['lbl_description']);?>
									<div class="fld" >
			
										<?php echo form_input($opt['participants_political_form']['project_participants_political_desc']);?>
										<div class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label('Permissions:', 'project_participants_political_permissions', $opt['participants_political_form']['lbl_permissions']);?>
									<div class="fld">
									<?php
										$permissions_attr = 'id="project_participants_political_permissions"';
										$permissions_options = array(
											'All'		=> 'All',
											'Some' 		=> 'Some',
											'Other' 	=> 'Other'
										);
										echo form_dropdown('project_participants_political_permissions', $permissions_options,'',$permissions_attr);
									?>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_submit('submit', 'Add New','class = "light_green btn_lml"');?>
									
									<?php echo form_close();?>
								
							</div>
						</div>
					</div>

				</div>

		
				<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-3">
				
					<div class="clearfix matrix_dropdown project_participants_companies">
					
						<div id="tab_innerarea_list">
							<div class="view_list clearfix">
								<div class="contenttitle2">
						            <h3>Company List</h3>
						        </div>
						        <div class="notibar" style="display:none">
								    <a class="close"></a>
								    <p></p>
								</div>
								
								 <div class="tableoptions">
								        	<button class="deletebutton radius3" title="Delete Selected" name="dyntable_company" id="#/admin.php/projects/delete_participants_companies">Delete Companies</button> &nbsp;
									</div><!--tableoptions-->
								<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_company">
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
								          	<th class="head1">Name</th>
								            <th class="head0">Role</th>
								            <th class="head1">Description</th>
								            <th class="head1">Action</th>
								        </tr>
								    </thead>
								    <tfoot>
								        <tr>
								          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
								          	<th class="head1">ID</th>
								          	<th class="head1">Name</th>
								            <th class="head0">Role</th>
								            <th class="head1">Description</th>
								            <th class="head1">Action</th>
								        </tr>
								    </tfoot>
								    <tbody>
								    	<?php 
								    	
								    	if(count($project["companies"]) > 0)
										{
											foreach($project["companies"] as $key=>$val)
											{
										?>
										<tr>
										  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$val['id']."","name"=>"select_".$val['id']."","value"=>$val['id'])); ?></span></td>
								            <td><?php echo $val['id']; ?></td>
								            <td><?php echo $val['name'];?></td>
								            <td><?php echo $val['role'];?></td>
								            <td><?php echo $val['description'];?></td>
								            <td><a href="javascript:void(0);" onclick="load_project_edit_from('<?php echo $slug;?>',<?php echo $val['id'];?>,'participants_companies','add_participants_companies')">Edit</a></td>
								        </tr>
								
										<?php
												
											}
										}
										?>
								    </tbody>
								</table>

						    </div>
						   <div class="add_form" id="add_participants_companies">
								<div class="contenttitle2">
								    <h3>Add Company</h3>
								</div>
								<?php echo form_open('projects/add_participants_companies/'.$slug,array('id'=>'participants_companies_form','name'=>'participants_companies_form','method'=>'post','class'=>'ajax_add_form'));?>

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
									
									
									<?php echo form_label('Name:', 'participants_companies_name', $opt['participants_companies_form']['lbl_name']);?>
									<div class="fld" >
			
										<?php echo form_input($opt['participants_companies_form']['project_participants_companies_name']);?>
										<div class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label('Role:', 'participants_companies_role', $opt['participants_companies_form']['lbl_role']);?>
									<div class="fld" >
			
										<?php echo form_input($opt['participants_companies_form']['project_participants_companies_role']);?>
										<div class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label('Description:', 'project_participants_companies_desc', $opt['participants_companies_form']['lbl_description']);?>
									<div class="fld" >
			
										<?php echo form_input($opt['participants_companies_form']['project_participants_companies_desc']);?>
										<div class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label('Permissions:', 'project_participants_companies_permissions', $opt['participants_companies_form']['lbl_permissions']);?>
									<div class="fld">
									<?php
										$permissions_attr = 'id="project_participants_companies_permissions"';
										$permissions_options = array(
											'All'		=> 'All',
											'Some' 		=> 'Some',
											'Other' 	=> 'Other'
										);
										echo form_dropdown('project_participants_companies_permissions', $permissions_options,'',$permissions_attr);
									?>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_submit('submit', 'Add New','class = "light_green btn_lml"');?>
									
									<?php echo form_close();?>
							</div>
						</div>
					
					</div>

				</div>

		
				<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-4">
				
					<div class="clearfix matrix_dropdown project_participants_owners">
					
						<div id="tab_innerarea_list">
							<div class="view_list clearfix">
								<div class="contenttitle2">
						            <h3>Owner List</h3>
						        </div>
						        					        
								<div class="notibar" style="display:none">
								    <a class="close"></a>
								    <p></p>
								</div>
								
								 <div class="tableoptions">
								        	<button class="deletebutton radius3" title="Delete Selected" name="dyntable_owner" id="#/admin.php/projects/delete_owners">Delete Owners</button> &nbsp;
									</div><!--tableoptions-->
								<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_owner">
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
								            <th class="head1">Type</th>
								            <th class="head1">Description</th>
								            <th class="head1">Action</th>			                        
								        </tr>
								    </thead>
								    <tfoot>
								        <tr>
								          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
								            <th class="head1">ID</th>
								            <th class="head0">Name</th>
								            <th class="head1">Type</th>
								            <th class="head1">Description</th>
								            <th class="head1">Action</th>
								        </tr>
								    </tfoot>
								    <tbody>
								    	<?php 
								    	
								    	if(count($project["owners"]) > 0)
										{
											foreach($project["owners"] as $key=>$val)
											{
										?>
										<tr>
										  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$val['id']."","name"=>"select_".$val['id']."","value"=>$val['id'])); ?></span></td>
								            <td><?php echo $val['id']; ?></td>
								            <td><?php echo $val['name'];?></td>
								            <td><?php echo $val['type'];?></td>
								            <td><?php echo $val['description'];?></td>
								            <td><a href="javascript:void(0);" onclick="load_project_edit_from('<?php echo $slug;?>',<?php echo $val['id'];?>,'participants_owners','add_participants_owners')">Edit</a></td>
								        </tr>
								
										<?php
												
											}
										}
										?>
								    </tbody>
								</table>

						    </div>
						   <div class="add_form" id="add_participants_owners">
								<div class="contenttitle2">
								    <h3>Add Owner</h3>
								</div>
								
								<?php echo form_open('projects/add_participants_owners/'.$slug,array('id'=>'participants_owners_form','name'=>'participants_owners_form','method'=>'post','class'=>'ajax_add_form'));?>

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
									
									
									<?php echo form_label('Name:', 'participants_owners_name', $opt['participants_owners_form']['lbl_name']);?>
									<div class="fld" >
			
										<?php echo form_input($opt['participants_owners_form']['project_participants_owners_name']);?>
										<div class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label('Type:', 'participants_owners_type', $opt['participants_owners_form']['lbl_type']);?>
									<div class="fld" >
			
										<?php echo form_input($opt['participants_owners_form']['project_participants_owners_type']);?>
										<div class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label('Description:', 'project_participants_owners_desc', $opt['participants_owners_form']['lbl_description']);?>
									<div class="fld" >
			
										<?php echo form_input($opt['participants_owners_form']['project_participants_owners_desc']);?>
										<div class="errormsg"></div>
									</div>
									<?php echo br(); ?>
									
									<?php echo form_label('Permissions:', 'project_participants_owners_permissions', $opt['participants_owners_form']['lbl_permissions']);?>
									<div class="fld">
									<?php
										$permissions_attr = 'id="project_participants_owners_permissions"';
										$permissions_options = array(
											'All'		=> 'All',
											'Some' 		=> 'Some',
											'Other' 	=> 'Other'
										);
										echo form_dropdown('project_participants_owners_permissions', $permissions_options,'',$permissions_attr);
									?>
									</div>
									<?php echo br(); ?>

									
									<?php echo form_submit('submit', 'Add New','class = "light_green btn_lml"');?>
									
									<?php echo form_close();?>
								
							</div>
						</div>
											
					</div>

				</div>

			</div>
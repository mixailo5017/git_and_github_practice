<?php //echo "<pre>"; print_r($project); ?>
<div id="profile_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all project_form" style="display: block;">

				<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
					<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-1">Machinery</a></li>
					<li class="ui-state-default ui-corner-top"><a href="#tabs-2">Key Technology</a></li>
					<li class="ui-state-default ui-corner-top"><a href="#tabs-3">Key Services</a></li>
					
				</ul>

		
				<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-1">

					<div class="clearfix matrix_dropdown project_machinery">
						<div id="tab_innerarea_list">
							<div class="view_list clearfix">
								<div class="contenttitle2">
						            <h3>Machinery List</h3>
						        </div>
						        
						        <div class="notibar" style="display:none">
								    <a class="close"></a>
								    <p></p>
								</div>
								
								 <div class="tableoptions">
								        	<button class="deletebutton radius3" title="Delete Selected" name="dyntable_machinery" id="#/admin.php/projects/delete_machinery">Delete Machinery</button> &nbsp;
									</div><!--tableoptions-->
								<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_machinery">
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
								            <th class="head1">Process</th>
								            <th class="head1">Financial Info</th>
								            <th class="head1">Action</th>			                        
								        </tr>
								    </thead>
								    <tfoot>
								        <tr>
								          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
								            <th class="head1">ID</th>
								            <th class="head0">Name</th>
								            <th class="head1">Process</th>
								            <th class="head1">Financial Info</th>
								            <th class="head1">Action</th>
								        </tr>
								    </tfoot>
								    <tbody>
								    	<?php 
								    	
								    	if(count($project["machinery"]) > 0)
										{
											foreach($project["machinery"] as $key=>$val)
											{
										?>
										<tr>
										  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$val['id']."","name"=>"select_".$val['id']."","value"=>$val['id'])); ?></span></td>
								            <td><?php echo $val['id']; ?></td>
								            <td><?php echo $val['name'];?></td>
								            <td><?php echo $val['procurementprocess'];?></td>
								            <td><?php echo $val['financialinfo'];?></td>
								            <td><a href="javascript:void(0);" onclick="load_project_edit_from('<?php echo $slug;?>',<?php echo $val['id'];?>,'project_machinery','add_project_machinery')">Edit</a></td>
								        </tr>
								
										<?php
												
											}
										}
										?>
								    </tbody>
								</table>

						    </div>
						   <div class="add_form" id="add_project_machinery">
								<div class="contenttitle2">
								    <h3>Add Machinery</h3>
								</div>
								<?php echo form_open('projects/add_machinery/'.$slug,array('id'=>'machinery_form','name'=>'machinery_form','method'=>'post','class'=>'ajax_add_form'));?>	
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
									
									<?php echo form_label('Name:', '', $opt['machinery_form']['lbl_name']);?>
									<div class="fld">
										<?php echo form_input($opt['machinery_form']['project_machinery_name']);?>
										<div id="err_project_machinery_name" class="errormsg"></div>
									</div>
									<br>

									<?php echo form_label('Procurement Process:', '', $opt['machinery_form']['lbl_process']);?>
									<div class="fld">
										<?php echo form_input($opt['machinery_form']['project_machinery_process']);?>
										<div id="err_project_machinery_process" class="errormsg"></div>
									</div>
									<br>
									
									<?php echo form_label('Financial Information:', '', $opt['machinery_form']['lbl_info']);?>
									<div class="fld">
										<?php echo form_input($opt['machinery_form']['project_machinery_financial_info']);?>
										<div id="err_project_machinery_financial_info" class="errormsg"></div>
									</div>
									<br>
									
									<?php echo form_label('Permissions:', '', $opt['machinery_form']['lbl_permissions']);?>
									<div class="fld">
									<?php
										$machinery_permission_attr = "id='project_machinery_permission'";
										$machinery_permission_options = array(
											"All"	=> "All",
											"Some"	=> "Some",
											"Other"	=> "Other"
										);
										echo form_dropdown("project_machinery_permission",$machinery_permission_options,'',$machinery_permission_attr);
									?>
									</div>
									<br>

									<?php echo form_submit('submit', 'Add New','class = "light_green btn_lml"');?>
									
									<?php echo form_close(); ?>
							</div>
						</div>

					</div>

				</div>

		
				<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-2">
				
					<div class="clearfix matrix_dropdown project_procurement_technology">
					
						
						<div id="tab_innerarea_list">
							<div class="view_list clearfix">
								<div class="contenttitle2">
						            <h3>Key Technology List</h3>
						        </div>
						        
								<div class="notibar" style="display:none">
								    <a class="close"></a>
								    <p></p>
								</div>
								
								 <div class="tableoptions">
								        	<button class="deletebutton radius3" title="Delete Selected" name="dyntable_keytech" id="#/admin.php/projects/delete_keytech">Delete Key Technology</button> &nbsp;
									</div><!--tableoptions-->
								<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_keytech">
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
								            <th class="head1">Procrutement Process</th>
								            <th class="head1">Financial Info</th>
								            <th class="head1">Action</th>			                        
								        </tr>
								    </thead>
								    <tfoot>
								        <tr>
								          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
								            <th class="head1">ID</th>
								            <th class="head0">Name</th>
								            <th class="head1">Procrutement Process</th>
								            <th class="head1">Financial Info</th>
								            <th class="head1">Action</th>
								          </tr>
								    </tfoot>
								    <tbody>
								    	<?php 
								    	
								    	if(count($project["procurement_technology"]) > 0)
										{
											foreach($project["procurement_technology"] as $key=>$val)
											{
										?>
										<tr>
										  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$val['id']."","name"=>"select_".$val['id']."","value"=>$val['id'])); ?></span></td>
								            <td><?php echo $val['id']; ?></td>
								            <td><?php echo $val['name'];?></td>
								            <td><?php echo $val['procurementprocess'];?></td>
								            <td><?php echo $val['financialinfo'];?></td>
								            <td><a href="javascript:void(0);" onclick="load_project_edit_from('<?php echo $slug;?>',<?php echo $val['id'];?>,'procurement_technology','add_procurement_technology')">Edit</a></td>
								        </tr>
								
										<?php
												
											}
										}
										?>
								    </tbody>
								</table>
								

						    </div>
						   <div class="add_form" id="add_procurement_technology">
								<div class="contenttitle2">
								    <h3>Add Key Technology</h3>
								</div>
								
								<?php echo form_open('projects/add_procurement_technology/'.$slug,array('id'=>'procurement_technology_form','name'=>'procurement_technology_form','method'=>'post','class'=>'ajax_add_form'));?>	
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
									
									<?php echo form_label('Name:', '', $opt['procurement_technology_form']['lbl_name']);?>
									<div class="fld">
										<?php echo form_input($opt['procurement_technology_form']['project_procurement_technology_name']);?>
										<div id="err_project_procurement_technology_name" class="errormsg"></div>
									</div>
									<br>

									<?php echo form_label('Procurement Process:', '', $opt['procurement_technology_form']['lbl_process']);?>
									<div class="fld">
										<?php echo form_input($opt['procurement_technology_form']['project_procurement_technology_process']);?>
										<div id="err_project_procurement_technology_process" class="errormsg"></div>
									</div>
									<br>
									
									<?php echo form_label('Financial Information:', '', $opt['procurement_technology_form']['lbl_info']);?>
									<div class="fld">
										<?php echo form_input($opt['procurement_technology_form']['project_procurement_technology_financial_info']);?>
										<div id="err_project_procurement_technology_financial_info" class="errormsg"></div>
									</div>
									<br>
									
									<?php echo form_label('Permissions:', '', $opt['procurement_technology_form']['lbl_permissions']);?>
									<div class="fld">
									<?php
										$technology_permission_attr = "id='project_procurement_technology_permission'";
										$technology_permission_options = array(
											"All"	=> "All",
											"Some"	=> "Some",
											"Other"	=> "Other"
										);
										echo form_dropdown("project_procurement_technology_permission",$technology_permission_options,'',$technology_permission_attr);
									?>
									</div>
									<br>

									<?php echo form_submit('submit', 'Add New','class = "light_green btn_lml"');?>
									
									<?php echo form_close(); ?>
								
							</div>
						</div>

					</div>
					
				</div>

		
				<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-3">
				
					<div class="clearfix matrix_dropdown project_procurement_services">
												
						<div id="tab_innerarea_list">
							<div class="view_list clearfix">
								<div class="contenttitle2">
						            <h3>Key Service List</h3>
						        </div>
						        

								<div class="notibar" style="display:none">
								    <a class="close"></a>
								    <p></p>
								</div>
								
								 <div class="tableoptions">
								        	<button class="deletebutton radius3" title="Delete Selected" name="dyntable_keyservices" id="#/admin.php/projects/delete_keyservices">Delete Key Services</button> &nbsp;
									</div><!--tableoptions-->
								<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_keyservices">
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
								            <th class="head0">Type</th>
								            <th class="head1">Procurement Process</th>
								            <th class="head1">Financial Info</th>
								            <th class="head1">Action</th>			                        
								        </tr>
								    </thead>
								    <tfoot>
								        <tr>
								          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
								           	<th class="head1">ID</th>
								            <th class="head0">Type</th>
								            <th class="head1">Procurement Process</th>
								            <th class="head1">Financial Info</th>
								            <th class="head1">Action</th>	
								        </tr>
								    </tfoot>
								    <tbody>
								    	<?php 
								    	
								    	if(count($project["procurement_services"]) > 0)
										{
											foreach($project["procurement_services"] as $key=>$val)
											{
										?>
										<tr>
										  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$val['id']."","name"=>"select_".$val['id']."","value"=>$val['id'])); ?></span></td>
								            <td><?php echo $val['id']; ?></td>
								            <td><?php echo $val['type'];?></td>
								            <td><?php echo $val['procurementprocess'];?></td>
								            <td><?php echo $val['financialinfo'];?></td>
								            <td><a href="javascript:void(0);" onclick="load_project_edit_from('<?php echo $slug;?>',<?php echo $val['id'];?>,'procurement_services','add_procurement_services')">Edit</a></td>
								        </tr>
								
										<?php
												
											}
										}
										?>
								    </tbody>
								</table>
								
						    </div>
						   <div class="add_form" id="add_procurement_services">
								<div class="contenttitle2">
								    <h3>Add Key Service</h3>
								</div>
								<?php echo form_open('projects/add_procurement_services/'.$slug,array('id'=>'procurement_services_form','name'=>'procurement_services_form','method'=>'post','class'=>'ajax_add_form'));?>	
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
									
									<?php echo form_label('Name:', '', $opt['procurement_services_form']['lbl_name']);?>
									<div class="fld">
										<?php echo form_input($opt['procurement_services_form']['project_procurement_services_name']);?>
										<div id="err_project_procurement_services_name" class="errormsg"></div>
									</div>
									<br>

									<?php echo form_label('Type:', '', $opt['procurement_services_form']['lbl_type']);?>
									<div class="fld">
										<?php echo form_input($opt['procurement_services_form']['project_procurement_services_type']);?>
										<div id="err_project_procurement_services_type" class="errormsg"></div>
									</div>
									<br>

									<?php echo form_label('Procurement Process:', '', $opt['procurement_services_form']['lbl_process']);?>
									<div class="fld">
										<?php echo form_input($opt['procurement_services_form']['project_procurement_services_process']);?>
										<div id="err_project_procurement_services_process" class="errormsg"></div>
									</div>
									<br>
									
									<?php echo form_label('Financial Information:', '', $opt['procurement_services_form']['lbl_info']);?>
									<div class="fld">
										<?php echo form_input($opt['procurement_services_form']['project_procurement_services_financial_info']);?>
										<div id="err_project_procurement_services_financial_info" class="errormsg"></div>
									</div>
									<br>
									
									<?php echo form_label('Permissions:', '', $opt['procurement_services_form']['lbl_permissions']);?>
									<div class="fld">
									<?php
										$services_permission_attr = "id='project_procurement_services_permission'";
										$services_permission_options = array(
											"All"	=> "All",
											"Some"	=> "Some",
											"Other"	=> "Other"
										);
										echo form_dropdown("project_procurement_services_permission",$services_permission_options,'',$services_permission_attr);
									?>
									</div>
									<br>

									<?php echo form_submit('submit', 'Add New','class = "light_green btn_lml"');?>
									
									<?php echo form_close(); ?>
								
							</div>
						</div>
										
					</div>

				</div>

			</div>
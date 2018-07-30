<div id="profile_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all project_form" style="display: block;">

	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-1">Engineering</a></li>
		<li class="ui-state-default ui-corner-top"><a href="#tabs-2">Maps / Geo-Tagging</a></li>
		<li class="ui-state-default ui-corner-top"><a href="#tabs-3">Design Issues</a></li>
		<li class="ui-state-default ui-corner-top"><a href="#tabs-4">Environment</a></li>
		<li class="ui-state-default ui-corner-top"><a href="#tabs-5">Other Studies</a></li>
		<li class="ui-state-default ui-corner-top"><a href="#tabs-6">Legal</a></li>
	</ul>


	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-1" style="">

			<div id="tab_innerarea_list">
							<div class="view_list clearfix">
								<div class="contenttitle2">
						            <h3>Engineering Fundamental List</h3>
						        </div>
						        <div class="notibar" style="display:none">
								    <a class="close"></a>
								    <p></p>
								</div>

								 <div class="tableoptions">
								        	<button class="deletebutton radius3" title="Delete Selected" name="dyntable_engineering" id="#/admin.php/projects/delete_engineering">Delete Engineering</button> &nbsp;
									</div><!--tableoptions-->
								<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_engineering">
								    <colgroup>
								        <col class="con0" style="width: 4%" />
								        <col class="con1" />
								        <col class="con0" />
								        <col class="con1" />
								        <col class="con0" />
								        <col class="con1" />
				                        <col class="con0" />
				                        <col class="con1" />
				                        <col class="con0" />
								    </colgroup>
								    <thead>
								        <tr>
								          <th class="head0 nosort" align="center"><?php echo form_checkbox(array("id"=>"select_all_header","name"=>"select_all_header","class"=>"checkall")); ?></th>
								          	<th class="head1">ID</th>
								            <th class="head0">Company</th>
								            <th class="head1">Role</th>
								            <th class="head0">Contact</th>
								            <th class="head1">Challenges</th>
								            <th class="head0">Innovation</th>
								            <th class="head1">Schedule</th>
								            <th class="head0 nosort">Action</th>
								        </tr>
								    </thead>
								    <tfoot>
								        <tr>
								          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
								            <th class="head1">ID</th>
								            <th class="head0">Company</th>
								            <th class="head1">Role</th>
								            <th class="head0">Contact</th>
								            <th class="head1">Challenges</th>
								            <th class="head0">Innovation</th>
								            <th class="head1">Schedule</th>
								            <th class="head0 nosort">Action</th>
								        </tr>
								    </tfoot>
								    <tbody>
								    	<?php

								    	if(count($project["engineering"]) > 0)
										{
											foreach($project["engineering"] as $key=>$val)
											{
										?>
										<tr>
										  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$val['id']."","name"=>"select_".$val['id']."","value"=>$val['id'])); ?></span></td>
								            <td><?php echo $val['id']; ?></td>
								            <td><?php echo $val['company'];?></td>
								            <td><?php echo $val['role'];?></td>
								            <td><?php echo $val['contactname'];?></td>
								            <td><?php echo $val['challenges'];?></td>
								            <td><?php echo $val['innovations'];?></td>
								            <td>
									            <?php if($val['schedule']!= ''){ ?>
														<a href="<?php echo PROJECT_IMAGE_PATH.$val['schedule'];?>" class="left files" target="_blank">
															<img src="/images/icons/<?php echo filetypeIcon($val['schedule']);?>" alt="file" title="file">
														</a>
												<?php } ?>
											</td>
								            <td><a href="javascript:void(0);" onclick="load_project_edit_from('<?php echo $slug;?>',<?php echo $val['id'];?>,'project_engineering','add_project_engineering')">Edit</a></td>
								        </tr>

										<?php

											}
										}
										?>
								    </tbody>
								</table>

						    </div>
						   <div class="add_form" id="add_project_engineering">
								<div class="contenttitle2">
								    <h3>Add Engineering Fundamental:</h3>
								</div>
								<?php echo form_open_multipart('projects/add_engineering/'.$slug,array('id'=>'engineering_form','name'=>'engineering_form','method'=>'post','class'=>'ajax_add_form'));?>
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


								<?php echo form_label('Company:', 'project_engineering_company', $opt['engineering_form']['lbl_company']);?>
								<div class="fld" >

									<?php echo form_input($opt['engineering_form']['project_engineering_company']);?>
									<div id="err_project_engineering_company" class="errormsg"></div>
								</div>
								<br>

								<?php echo form_label('Role:', 'project_engineering_role', $opt['engineering_form']['lbl_role']);?>
								<div class="fld" >

									<?php echo form_input($opt['engineering_form']['project_engineering_role']);?>
									<div id="err_project_engineering_role" class="errormsg"></div>
								</div>
								<br>

								<?php echo form_label('Contact Name:', 'project_engineering_cname', $opt['engineering_form']['lbl_cname']);?>
								<div class="fld" >

									<?php echo form_input($opt['engineering_form']['project_engineering_cname']);?>
									<div id="err_project_engineering_cname" class="errormsg"></div>
								</div>
								<br>

								<?php echo form_label('Challenges:', 'project_engineering_challenges', $opt['engineering_form']['lbl_challenges']);?>
								<div class="fld" >

									<?php echo form_input($opt['engineering_form']['project_engineering_challenges']);?>
									<div id="err_project_engineering_challenges" class="errormsg"></div>
								</div>
								<br>

								<?php echo form_label('Innovations:', 'project_engineering_innovations', $opt['engineering_form']['lbl_innovations']);?>
								<div class="fld" >

									<?php echo form_input($opt['engineering_form']['project_engineering_innovations']);?>
									<div id="err_project_engineering_innovations" class="errormsg"></div>
								</div>
								<br>

								<?php echo form_label('Schedule:', 'project_engineering_schedule', $opt['engineering_form']['lbl_schedule']);?>
								<div class="fld" >

									<?php echo form_upload($opt['engineering_form']['project_engineering_schedule']);?>
									<div id="err_project_engineering_schedule" class="errormsg"></div>
								</div>
								<br>

								<?php echo form_label('Permissions:', 'project_engineering_permissions', $opt['engineering_form']['lbl_permissions']);?>
								<div class="fld" >
								<?php
									$permissions_attr = 'id="project_engineering_permissions"';
									$permissions_options = array(
										'All'		=> 'All',
										'Some' 		=> 'Some',
										'Other' 	=> 'Other'
									);
									echo form_dropdown('project_engineering_permissions', $permissions_options,'',$permissions_attr);
								?>
								</div><br>

								<?php echo form_submit('engineering_submit', 'Add New','class = "light_green btn_lml"');?>

								<?php echo form_close();?>
							</div>
					</div>

	</div>


	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-2" style="">

		<div class="clearfix matrix_dropdown project_map_points">

			<div id="tab_innerarea_list">
				<div class="view_list clearfix">
					<div class="contenttitle2">
			            <h3>Map Point List</h3>
			        </div>
			        <div class="notibar" style="display:none">
					    <a class="close"></a>
					    <p></p>
					</div>

					 <div class="tableoptions">
					        <button class="deletebutton radius3" title="Delete Selected" name="dyntable_mappoint" id="#/admin.php/projects/delete_map_point">Delete Map Points</button> &nbsp;
					</div><!--tableoptions-->
					<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_mappoint">
					    <colgroup>
					        <col class="con0" style="width: 4%" />
					        <col class="con1" />
					        <col class="con0" />
					        <col class="con1" />
					        <col class="con0" />
					        <col class="con1" />
					    </colgroup>
					    <thead>
					        <tr>
					          <th class="head0 nosort" align="center"><?php echo form_checkbox(array("id"=>"select_all_header","name"=>"select_all_header","class"=>"checkall")); ?></th>
					          	<th class="head1">ID</th>
					            <th class="head0">Name</th>
					            <th class="head1">Latitude</th>
					            <th class="head0">Longitude</th>
					            <th class="head1 nosort">Action</th>
					        </tr>
					    </thead>
					    <tfoot>
					        <tr>
					          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
					            <th class="head1">ID</th>
					            <th class="head0">Name</th>
					            <th class="head1">Latitude</th>
					            <th class="head0">Longitude</th>
					            <th class="head1 nosort">Action</th>
					        </tr>
					    </tfoot>
					    <tbody>
					    	<?php

					    	if(count($project["map_point"]) > 0)
							{
								foreach($project["map_point"] as $key=>$val)
								{
							?>
							<tr>
							  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$val['id']."","name"=>"select_".$val['id']."","value"=>$val['id'])); ?></span></td>
					            <td><?php echo $val['id']; ?></td>
					            <td><?php echo $val['name'];?></td>
					            <td><?php echo $val['latitude'];?></td>
					            <td><?php echo $val['longitude'];?></td>
					            <td><a href="javascript:void(0);" onclick="load_project_edit_from('<?php echo $slug;?>',<?php echo $val['id'];?>,'project_map_point','add_project_map_point')">Edit</a></td>
					        </tr>

							<?php

								}
							}
							?>
					    </tbody>
					</table>

			    </div>
			   <div class="add_form" id="add_project_map_point">
					<div class="contenttitle2">
					    <h3>Add Point:</h3>
					</div>
						<?php echo form_open('projects/add_map_point/'.$slug,array('id'=>'map_points_form','name'=>'map_points_form','method'=>'post','class'=>'ajax_add_form'));?>
							<?php
								$opt['map_points_form'] = array(
												'lbl_mapname' => array(
														'class' => 'left_label'
														),
												'project_map_points_mapname'	=> array(
														'name' 		=> 'project_map_points_mapname',
														'id' 		=> 'project_map_points_mapname'
														),
												'project_map_points_latitude'	=> array(
														'name' 		=> 'project_map_points_latitude',
														'id' 		=> 'project_map_points_latitude'
														),
												'lbl_latitude' => array(
														'class' => 'left_label'
														),
												'project_map_points_longitude'	=> array(
														'name' 		=> 'project_map_points_longitude',
														'id' 		=> 'project_map_points_longitude'
														),
												'lbl_longitude' => array(
														'class' => 'left_label'
														)
										);
							?>


							<div class="points-map">
								<div style="width:350px; height:200px; background:#73b6e6;" class="points-map-itself olMap">

								</div><!-- /.points-map-itself -->
							</div><!-- /.points-map -->
							<br>
							<?php echo form_label('Name:', 'project_map_points_mapname', $opt['map_points_form']['lbl_mapname']);?>
							<div class="fld" >

								<?php echo form_input($opt['map_points_form']['project_map_points_mapname']);?>
								<div id="err_project_map_points_mapname" class="errormsg"></div>
							</div>
							<br>
							<?php echo form_label('Latitude:', 'project_map_points_latitude', $opt['map_points_form']['lbl_latitude']);?>
							<div class="fld" >

								<?php echo form_input($opt['map_points_form']['project_map_points_latitude']);?>
								<div id="err_project_map_points_latitude" class="errormsg"></div>
							</div>
							<br>

							<?php echo form_label('Longitude:', 'project_map_points_longitude', $opt['map_points_form']['lbl_longitude']);?>
							<div class="fld" >

								<?php echo form_input($opt['map_points_form']['project_map_points_longitude']);?>
								<div id="err_project_map_points_longitude" class="errormsg"></div>
							</div>

							<br>
							<?php echo form_submit('points_submit', 'Add New','class = "light_green btn_lml"');?>

							<?php echo form_close();?>
				</div>
			</div>

		</div>

	</div>



	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-3" style="">

		<div class="clearfix matrix_dropdown project_design_issues">

			<div id="tab_innerarea_list">
				<div class="view_list clearfix">
					<div class="contenttitle2">
			            <h3>Design Issue List</h3>
			        </div>
			        <div class="notibar" style="display:none">
					    <a class="close"></a>
					    <p></p>
					</div>

					 <div class="tableoptions">
					        	<button class="deletebutton radius3" title="Delete Selected" name="dyntable_design_issue" id="#/admin.php/projects/delete_design_issue">Delete Design Issue</button> &nbsp;
					</div><!--tableoptions-->
					<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_design_issue">
					    <colgroup>
					        <col class="con0" style="width: 4%" />
					        <col class="con1" />
					        <col class="con0" />
					        <col class="con1" />
					        <col class="con0" />
					        <col class="con1" />
					    </colgroup>
					    <thead>
					        <tr>
					          <th class="head0 nosort" align="center"><?php echo form_checkbox(array("id"=>"select_all_header","name"=>"select_all_header","class"=>"checkall")); ?></th>
					          	<th class="head1">ID</th>
					            <th class="head0">Title</th>
					            <th class="head1">Description</th>
					            <th class="head0">Attachment</th>
					            <th class="head1 nosort">Action</th>
					       </tr>
					    </thead>
					    <tfoot>
					        <tr>
					          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
					          	<th class="head1">ID</th>
					            <th class="head0">Title</th>
					            <th class="head1">Description</th>
					            <th class="head0">Attachment</th>
					            <th class="head1 nosort">Action</th>
					        </tr>
					    </tfoot>
					    <tbody>
					    	<?php

					    	if(count($project["design_issue"]) > 0)
							{
								foreach($project["design_issue"] as $key=>$val)
								{
							?>
							<tr>
							  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$val['id']."","name"=>"select_".$val['id']."","value"=>$val['id'])); ?></span></td>
					            <td><?php echo $val['id']; ?></td>
					            <td><?php echo $val['title'];?></td>
					            <td><?php echo $val['description'];?></td>
					            <td>
						            <?php if($val['attachment']!= ''){ ?>
											<a href="<?php echo PROJECT_IMAGE_PATH.$val['attachment'];?>" class="left files" target="_blank">
												<img src="/images/icons/<?php echo filetypeIcon($val['attachment']);?>" alt="file" title="file">
											</a>
									<?php } ?>
								</td>
					            <td><a href="javascript:void(0);" onclick="load_project_edit_from('<?php echo $slug;?>',<?php echo $val['id'];?>,'project_design_issue','add_project_design_issue')">Edit</a></td>
					        </tr>

							<?php

								}
							}
							?>
					    </tbody>
					</table>


			    </div>
			   <div class="add_form" id="add_project_design_issue">
					<div class="contenttitle2">
					    <h3>Add Design Issue:</h3>
					</div>
					<?php echo form_open_multipart('projects/add_design_issue/'.$slug,array('id'=>'design_issue_form','name'=>'design_issue_form','method'=>'post','class'=>'ajax_add_form'));?>
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


						<?php echo form_label('Title:', 'project_design_issues_title', $opt['design_issues_form']['lbl_title']);?>
						<div class="fld" >

							<?php echo form_input($opt['design_issues_form']['project_design_issues_title']);?>
							<div id="err_project_design_issues_title" class="errormsg"></div>
						</div>
						<br>

						<?php echo form_label('Description:', 'project_design_issues_desc', $opt['design_issues_form']['lbl_description']);?>
						<div class="fld" >

							<?php echo form_input($opt['design_issues_form']['project_design_issues_desc']);?>
							<div id="err_project_design_issues_description" class="errormsg"></div>
						</div>
						<br>

						<?php echo form_label('Attachment:', 'project_design_issues_attachment', $opt['design_issues_form']['lbl_attachment']);?>
						<div class="fld" >

							<?php echo form_upload($opt['design_issues_form']['project_design_issues_attachment']);?>
							<div id="err_project_design_issues_attachment" class="errormsg"></div>
						</div>
						<br>

						<?php echo form_label('Permissions:', 'project_design_issues_permissions', $opt['design_issues_form']['lbl_permissions']);?>
						<div class="fld"><?php
							$design_issue_attr = 'id="project_design_issues_permissions"';
							$design_issue_options = array(
								'All'		=> 'All',
								'Some' 		=> 'Some',
								'Other' 	=> 'Other'
							);
							echo form_dropdown('project_design_issues_permissions', $design_issue_options,'',$design_issue_attr);
						?>
						</div>
						<br>

						<?php echo form_submit('design_submit', 'Add new','class = "light_green btn_lbl"');?>

						<?php echo form_close();?>
				</div>
			</div>

		</div>

	</div>


	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-4" style="">

		<div class="clearfix matrix_dropdown project_environment">

		<div id="tab_innerarea_list">
			<div class="view_list clearfix">
				<div class="contenttitle2">
		            <h3>Environment List</h3>
		        </div>

			        <div class="notibar" style="display:none">
						    <a class="close"></a>
						    <p></p>
					</div>

					 <div class="tableoptions">
					        	<button class="deletebutton radius3" title="Delete Selected" name="dyntable_environment" id="#/admin.php/projects/delete_environment">Delete Environment</button> &nbsp;
					</div><!--tableoptions-->
					<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_environment">
					    <colgroup>
					        <col class="con0" style="width: 4%" />
					        <col class="con1" />
					        <col class="con0" />
					        <col class="con1" />
					        <col class="con0" />
					        <col class="con1" />
					    </colgroup>
					    <thead>
					        <tr>
					          <th class="head0 nosort" align="center"><?php echo form_checkbox(array("id"=>"select_all_header","name"=>"select_all_header","class"=>"checkall")); ?></th>
					          	<th class="head1">ID</th>
					            <th class="head0">Title</th>
					            <th class="head1">Description</th>
					            <th class="head0">Attachment</th>
					            <th class="head1 nosort">Action</th>
					       </tr>
					    </thead>
					    <tfoot>
					        <tr>
					          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
					          	<th class="head1">ID</th>
					            <th class="head0">Title</th>
					            <th class="head1">Description</th>
					            <th class="head0">Attachment</th>
					            <th class="head1 nosort">Action</th>
					        </tr>
					    </tfoot>
					    <tbody>
					    	<?php

					    	if(count($project["environment"]) > 0)
							{
								foreach($project["environment"] as $key=>$val)
								{
							?>
							<tr>
							  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$val['id']."","name"=>"select_".$val['id']."","value"=>$val['id'])); ?></span></td>
					            <td><?php echo $val['id']; ?></td>
					            <td><?php echo $val['title'];?></td>
					            <td><?php echo $val['description'];?></td>
					            <td>
						            <?php if($val['attachment']!= ''){ ?>
											<a href="<?php echo PROJECT_IMAGE_PATH.$val['attachment'];?>" class="left files" target="_blank">
												<img src="/images/icons/<?php echo filetypeIcon($val['attachment']);?>" alt="file" title="file">
											</a>
									<?php } ?>
								</td>

					            <td><a href="javascript:void(0);" onclick="load_project_edit_from('<?php echo $slug;?>',<?php echo $val['id'];?>,'project_environment','add_project_environment')">Edit</a></td>
					        </tr>

							<?php

								}
							}
							?>
					    </tbody>
					</table>


		    </div>
		   <div class="add_form" id="add_project_environment">
				<div class="contenttitle2">
				    <h3>Add Environment:</h3>
				</div>
				<?php echo form_open_multipart('projects/add_environment/'.$slug,array('id'=>'environment_form','name'=>'environment_form','method'=>'post','class'=>'ajax_add_form'));?>
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

			<?php echo form_label('Title:', 'project_environment_title', $opt['environment_form']['lbl_env_title']);?>
			<div class="fld" >

				<?php echo form_input($opt['environment_form']['project_environment_title']);?>
				<div id="err_project_environment_title" class="errormsg"></div>
			</div>
			<br>

			<?php echo form_label('Description:', 'project_environment_desc', $opt['environment_form']['lbl_env_description']);?>
			<div class="fld" >

				<?php echo form_input($opt['environment_form']['project_environment_desc']);?>
				<div id="err_project_environment_description" class="errormsg"></div>
			</div>
			<br>

			<?php echo form_label('Attachment:', 'project_environment_attachment', $opt['environment_form']['lbl_env_attachment']);?>
			<div class="fld" >

				<?php echo form_upload($opt['environment_form']['project_environment_attachment']);?>
				<div id="err_project_environment_attachment" class="errormsg"></div>
			</div>
			<br>

			<?php echo form_label('Permissions:', 'project_environment_permissions', $opt['environment_form']['lbl_env_permissions']);?>
			<div class="fld">
			<?php
				$project_environment_attr = 'id="project_environment_permissions"';
				$project_environment_options = array(
					'All'		=> 'All',
					'Some' 		=> 'Some',
					'Other' 	=> 'Other'
				);
				echo form_dropdown('project_environment_permissions', $project_environment_options,'',$project_environment_attr);
			?>
			</div>
			<br>

			<?php echo form_submit('environment_submit', 'Add new','class = "light_green btn_lbl"');?>

			<?php echo form_close();?>
			</div>
		</div>

		</div>

	</div>


	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-5" style="">

		<div class="clearfix matrix_dropdown project_studies">



		<div id="tab_innerarea_list">
			<div class="view_list clearfix">
				<div class="contenttitle2">
		            <h3>Study File List</h3>
		        </div>
		        <div class="notibar" style="display:none">
					    <a class="close"></a>
					    <p></p>
				</div>

				 <div class="tableoptions">
				        	<button class="deletebutton radius3" title="Delete Selected" name="dyntable_studies" id="#/admin.php/projects/delete_studies">Delete Studies</button> &nbsp;
				</div><!--tableoptions-->
				<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_studies">
				    <colgroup>
				        <col class="con0" style="width: 4%" />
				        <col class="con1" />
				        <col class="con0" />
				        <col class="con1" />
				        <col class="con0" />
				        <col class="con1" />
			    </colgroup>
				    <thead>
				        <tr>
				          <th class="head0 nosort" align="center"><?php echo form_checkbox(array("id"=>"select_all_header","name"=>"select_all_header","class"=>"checkall")); ?></th>
				          	<th class="head1">ID</th>
				            <th class="head0">Title</th>
				            <th class="head1">Description</th>
				            <th class="head0">Attachment</th>
				            <th class="head1 nosort">Action</th>
				       </tr>
				    </thead>
				    <tfoot>
				        <tr>
				          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
				          	<th class="head1">ID</th>
				            <th class="head0">Title</th>
				            <th class="head1">Description</th>
				            <th class="head0">Attachment</th>
				            <th class="head1 nosort">Action</th>
				        </tr>
				    </tfoot>
				    <tbody>
				    	<?php

				    	if(count($project["studies"]) > 0)
						{
							foreach($project["studies"] as $key=>$val)
							{
						?>
						<tr>
						  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$val['id']."","name"=>"select_".$val['id']."","value"=>$val['id'])); ?></span></td>
				            <td><?php echo $val['id']; ?></td>
				            <td><?php echo $val['title'];?></td>
				            <td><?php echo $val['description'];?></td>
				            <td>
						            <?php if($val['attachment']!= ''){ ?>
											<a href="<?php echo PROJECT_IMAGE_PATH.$val['attachment'];?>" class="left files" target="_blank">
												<img src="/images/icons/<?php echo filetypeIcon($val['attachment']);?>" alt="file" title="file">
											</a>
									<?php } ?>
								</td>
				            <td><a href="javascript:void(0);" onclick="load_project_edit_from('<?php echo $slug;?>',<?php echo $val['id'];?>,'project_studies','add_project_studies')">Edit</a></td>
				        </tr>

						<?php

							}
						}
						?>
				    </tbody>
				</table>


		    </div>
		   <div class="add_form" id="add_project_studies">
				<div class="contenttitle2">
				    <h3>Add Study File:</h3>
				</div>
				<?php echo form_open_multipart('projects/add_studies/'.$slug,array('id'=>'project_studies_form','name'=>'project_studies_form','method'=>'post','class'=>'ajax_add_form'));?>
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



			<?php echo form_label('Title:', 'project_studies_title', $opt['project_studies_form']['lbl_std_title']);?>
			<div class="fld" >

				<?php echo form_input($opt['project_studies_form']['project_studies_title']);?>
				<div id="err_project_studies_title" class="errormsg"></div>
			</div>
			<br>

			<?php echo form_label('Description:', 'project_studies_desc', $opt['project_studies_form']['lbl_std_description']);?>
			<div class="fld" >

				<?php echo form_input($opt['project_studies_form']['project_studies_desc']);?>
				<div id="err_project_studies_description" class="errormsg"></div>
			</div>
			<br>

			<?php echo form_label('Attachment:', 'project_studies_attachment', $opt['project_studies_form']['lbl_std_attachment']);?>
			<div class="fld" >

				<?php echo form_upload($opt['project_studies_form']['project_studies_attachment']);?>
				<div id="err_project_studies_attachment" class="errormsg"></div>
			</div>
			<br>

			<?php echo form_label('Permissions:', 'project_studies_permissions', $opt['project_studies_form']['lbl_std_permissions']);?>
			<div class="fld">
			<?php
				$project_studies_attr = 'id="project_studies_permissions"';
				$project_studies_options = array(
					'All'		=> 'All',
					'Some' 		=> 'Some',
					'Other' 	=> 'Other'
				);
				echo form_dropdown('project_studies_permissions', $project_studies_options,'',$project_studies_attr);
			?>
			</div>
			<br>

			<?php echo form_submit('studies_submit', 'Add new','class = "light_green btn_lbl"');?>

			<?php echo form_close();?>
			</div>
		</div>
	</div>

	</div>


	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-6" style="">

		<div class="clearfix">
			<div class="contenttitle2">
			    <h3>Legal Information:</h3>
			</div>
			<?php echo form_open('projects/add_legal/'.$slug,array('id'=>'project_legal_form','name'=>'project_legal_form','method'=>'post','class'=>'ajax_add_form topupdate'));?>

				<?php echo form_label('Info:', 'project_legal',array('class'=>'above_label'));

				if($project['fundamental_legal'])
				{
					$legalinfo = $project['fundamental_legal'];
				}
				else
				{
					$legalinfo = '';
				}
				?>
				<div class="fld">
				<?php echo form_textarea(array('id'=>'project_legal','name'=>'project_legal','rows'=>'10','cols'=>'30','value'=>$legalinfo));?>
				</div>
				<br>
				<br>
				<?php echo form_submit('legal_submit', 'Add Legal Information','class = "light_green btn_lbl"');?>


			<?php echo form_close();?>

		</div>

	</div>
</div>
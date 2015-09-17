<div id="profile_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all project_form" style="display: block;">

				<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
					<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-1">Regulatory</a></li>
				
				</ul>

		
				<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-1">

					<div class="clearfix matrix_dropdown project_regulatory">
						
						<div id="tab_innerarea_list">
							<div class="view_list clearfix">
								<div class="contenttitle2">
						            <h3>Regulatory List</h3>
						        </div>
						        <div class="notibar" style="display:none">
								    <a class="close"></a>
								    <p></p>
								</div>
								
								 <div class="tableoptions">
								        	<button class="deletebutton radius3" title="Delete Selected" name="dyntable_regulatory" id="#/admin.php/projects/delete_regulatory">Delete Regulatory Files</button> &nbsp;
									</div><!--tableoptions-->
								<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_regulatory">
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
								            <th class="head0">File</th>
								            <th class="head1">Description</th>
								            <th class="head1">Permission</th>
								            <th class="head1">Action</th>			                        
								        </tr>
								    </thead>
								    <tfoot>
								        <tr>
								          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
								            <th class="head1">ID</th>
								            <th class="head0">File</th>
								            <th class="head1">Description</th>
								            <th class="head1">Permission</th>
								            <th class="head1">Action</th>
								        </tr>
								    </tfoot>
								    <tbody>
								    	<?php 
								    	
								    	if(count($project["regulatory"]) > 0)
										{
											foreach($project["regulatory"] as $key=>$val)
											{
										?>
										<tr>
										  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$val['id']."","name"=>"select_".$val['id']."","value"=>$val['id'])); ?></span></td>
								            <td><?php echo $val['id']; ?></td>
								             <td>
									            <?php if($val['file']!= ''){ ?>
														<a href="<?php echo PROJECT_IMAGE_PATH.$val['file'];?>" class="left files" target="_blank">
															<img src="/images/icons/<?php echo filetypeIcon($val['file']);?>" alt="file" title="file">
														</a>
												<?php } ?>
											</td>
								            <td><?php echo $val['description'];?></td>
								            <td><?php echo $val['permission'];?></td>
								            <td><a href="javascript:void(0);" onclick="load_project_edit_from('<?php echo $slug;?>',<?php echo $val['id'];?>,'project_regulatory','add_project_regulatory')">Edit</a></td>
								        </tr>
								
										<?php
												
											}
										}
										?>
								    </tbody>
								</table>
							</div>
						   <div class="add_form" id="add_project_regulatory">
								<div class="contenttitle2">
								    <h3>Add Regulatory File:</h3>
								</div>
								
								<?php echo form_open_multipart('projects/add_regulatory/'.$slug,array('id'=>'regulatory_form','name'=>'aregulatory_form','method'=>'post','class'=>'ajax_add_form'));?>
									
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

									<?php echo form_label('File:', 'project_regulatory_filename', $opt['regulatory_form']['lbl_filename']);?>
									<div class="fld">
			
										<?php echo form_upload($opt['regulatory_form']['project_regulatory_filename']);?>
										<div class="errormsg"></div>
									</div>
									<br>
									<?php echo form_label('Description:', 'project_regulatory_desc', $opt['regulatory_form']['lbl_description']);?>
									<div class="fld">
			
										<?php echo form_input($opt['regulatory_form']['project_regulatory_desc']);?>
										<div class="errormsg"></div>
									</div>
									<br>
									
									<?php echo form_label('Permission:', 'project_regulatory_permission', $opt['regulatory_form']['lbl_permission']);?>
									<div class="fld">
			
										<?php
											$permissions_attr = 'id="project_regulatory_permission"';
											$permissions_options = array(
												'All'		=> 'All',
												'Some' 		=> 'Some',
												'Other' 	=> 'Other'
											);
											echo form_dropdown('project_regulatory_permissions', $permissions_options,'',$permissions_attr);
										?>
										<div class="errormsg"></div>
									</div>
									<br>
																		
									<?php echo form_submit('submit', 'Add New','class = "light_green btn_lml"');?>
									
									<?php echo form_close();?>
								
							</div>
						</div>

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
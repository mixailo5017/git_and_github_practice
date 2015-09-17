<div id="profile_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all project_form" style="display: block;">

	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-1">Files</a></li>
	
	</ul>


	<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-1">

		<div class="clearfix matrix_dropdown project_matrixfiles">
		
		<div id="tab_innerarea_list">
			<div class="view_list clearfix">
				<div class="contenttitle2">
		            <h3>File List</h3>
		        </div>
		        
				<div class="notibar" style="display:none">
				    <a class="close"></a>
				    <p></p>
				</div>
				
				 <div class="tableoptions">
				        	<button class="deletebutton radius3" title="Delete Selected" name="dyntable_file" id="#/admin.php/projects/delete_files">Delete Files</button> &nbsp;
					</div><!--tableoptions-->
				<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_file">
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
				            <th class="head0">Filename</th>
				            <th class="head1">Type</th>
				            <th class="head1">Description</th>			                        
				            <th class="head1">Action</th>
				        </tr>
				    </thead>
				    <tfoot>
				        <tr>
				          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
				          	<th class="head1">ID</th>
				            <th class="head0">Filename</th>
				            <th class="head1">Type</th>
				            <th class="head1">Description</th>			                        
				            <th class="head1">Action</th>
				        </tr>
				    </tfoot>
				    <tbody>
				    	<?php 
				    	
				    	if(count($project["files"]) > 0)
						{
							foreach($project["files"] as $key=>$val)
							{
						?>
						<tr>
						  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$val['id']."","name"=>"select_".$val['id']."","value"=>$val['id'])); ?></span></td>
				            <td><?php echo $val['id']; ?></td>
				            <td><?php echo $val['file'];?> (<?php echo ceil($val["filesize"])."KB"; ?>)</td>
				            <td>
					            <?php if($val['file']!= ''){ ?>
										<a href="<?php echo PROJECT_IMAGE_PATH.$val['file'];?>" class="left files" target="_blank">
											<img src="/images/icons/<?php echo filetypeIcon($val['file']);?>" alt="file" title="file">
										</a>
								<?php } ?>
							</td>
				            <td><?php echo $val['description'];?></td>
				            <td><a href="javascript:void(0);" onclick="load_project_edit_from('<?php echo $slug;?>',<?php echo $val['id'];?>,'project_files','add_project_files')">Edit</a></td>
				        </tr>
				
						<?php
								
							}
						}
						?>
				    </tbody>
				</table>
			
			</div>
		   <div class="add_form" id="add_project_files">
				<div class="contenttitle2">
				    <h3>Add New File</h3>
				</div>
				
				<?php echo form_open_multipart('projects/add_project_files/'.$slug,array('id'=>'files_form','name'=>'files_form','method'=>'post','class'=>'ajax_add_form'));?>	
				<?php 
					$opt['files_form'] = array(
							'lbl_file' => array(
									'class' => 'left_label'
									),
							'project_files_filename'	=> array(
									'name' 		=> 'project_files_filename',
									'id' 		=> 'project_files_filename'
									),
							'lbl_desc' => array(
									'class' => 'left_label'
									),
							'project_files_desc'	=> array(
									'name' 		=> 'project_files_desc',
									'id' 		=> 'project_files_desc'
									),
							'lbl_permissions' => array(
									'class' => 'left_label'
									)
						);

				?>
				
				<?php echo form_label('File:', '', $opt['files_form']['lbl_file']);?>
				<div class="fld">
					<?php echo form_upload($opt['files_form']['project_files_filename']);?>
					<div id="err_project_files_filename" class="errormsg"></div>
				</div>
				<?php echo br(); ?>

				<?php echo form_label('Description:', '', $opt['files_form']['lbl_desc']);?>
				<div class="fld">
					<?php echo form_input($opt['files_form']['project_files_desc']);?>
					<div id="err_project_files_desc" class="errormsg"></div>
				</div>
				<?php echo br(); ?>

				<?php echo form_label('Permissions:', '', $opt['files_form']['lbl_permissions']);?>
				<div class="fld">
				<?php
					$files_permission_attr = "id='files_permission'";
					$files_permission_options = array(
						"All"	=> "All",
						"Some"	=> "Some",
						"Other"	=> "Other"
					);
					echo form_dropdown("files_permission",$files_permission_options,'',$files_permission_attr);
				?>
				</div>
				<?php echo br(); ?>

				<?php echo form_submit('submit', 'Add New','class = "light_green btn_lml"');?>
				
				<?php echo form_close(); ?>
			</div>
		</div>

		</div>

	</div>

</div>
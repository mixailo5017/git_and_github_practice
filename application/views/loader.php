<?php

if($formname == 'project_files')
{
	if(count($project_files_data) > 0)
	{

			foreach($project_files_data as $key=>$val) 
			{
			?>
				<li class="" id="row_id_<?php echo $val["id"]; ?>">
					<div class="view clearfix">
						
						<?php if($val['file']!= ''){ ?>
			
						<a href="<?php echo PROJECT_IMAGE_PATH.$val['file'];?>" class="left files" target="_blank">
							<img src="/images/icons/<?php echo filetypeIcon($val['file']);?>" alt="<?php echo lang('file')?>" title="<?php echo lang('file')?>">
						</a>
					
					<?php } ?>

					
						
						<span class="left"></span>
						<span class="left middle">
							<strong><?php echo $val["file"]; ?></strong> (<?php echo ceil($val["filesize"])."KB"; ?>)<br>
							<?php echo $val["description"]; ?>
						</span>
						
						<span class="left middle">

							<?php $filedate = new DateTime($val['dateofuploading']); 
							
							echo $filedate->format('M d,Y');?>
							<br>
							
						</span>
		
						<a class="right delete" href="#projects/delete_project_files"><?php echo lang('Delete');?></a>
		
						<a class="right edit" id="edit_project_files_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>
		
					</div>
					<div class="edit">
						<?php echo form_open('projects/update_project_files/'.$slug,array('id'=>'update_project_files_form_'.$val["id"],'name'=>'update_project_files_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>
						
						<?php 
							
							$opt['update_project_files_form'] = array(
								'lbl_filename' => array(
										'class' => 'left_label'
										),
								'project_files_filename'	=> array(
										'name' 		=> 'project_files_filename',
										'id' 		=> 'project_files_filename',
										'value'		=> $val["file"]
										),
								'lbl_description' => array(
										'class' => 'left_label'
										),
								'project_files_desc'	=> array(
										'name' 		=> 'project_files_desc',
										'id' 		=> 'project_files_desc',
										'value'		=> $val["description"]
										),
								'lbl_permission' => array(
										'class' => 'left_label'
										)
								);
		
							?>
		
		
							<?php echo form_hidden("hdn_project_files_id",$val["id"]); ?>
							<?php echo form_hidden("project_files_filenam_hidden",$val["file"]); ?>
							
							<?php echo form_label(lang('File').':', 'project_files_filename', $opt['update_project_files_form']['lbl_filename']);?>
							<div class="fld" style="width:500px;">
		
								<?php echo form_upload($opt['update_project_files_form']['project_files_filename']);?>
								<div class="errormsg"></div>
							</div>
							
							<?php echo form_label(lang('Description').':', 'project_files_desc', $opt['update_project_files_form']['lbl_description']);?>
							<div class="fld" style="width:500px;">
		
								<?php echo form_input($opt['update_project_files_form']['project_files_desc']);?>
								<div class="errormsg"></div>
							</div>
							
							<?php echo form_label(lang('Permission').':', 'project_files_permission', $opt['update_project_files_form']['lbl_permission']);?>
							<div class="fld" style="width:500px;">
		
								<?php
									$permissions_attr = 'id="project_files_permission"';
									$permissions_options = array(
										'All'		=> lang('All'),
										'Some' 		=> lang('Some'),
										'Other' 	=> lang('Other')
									);
									echo form_dropdown('project_files_permissions', $permissions_options,$val['permission'],$permissions_attr);
								?>
								<div class="errormsg"></div>
							</div>
																
							<?php echo form_submit('ufiles_submit', lang('Update'),'class = "light_green btn_lml"');?>
							
							<?php echo form_close();?>
					</div>
				</li>
		<?php
			}
	}
}


if($formname == 'procurement_services')
{
	if(count($procurement_services_data) > 0)
	{

			foreach($procurement_services_data as $key=>$val) 
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
						
						<?php echo form_label(lang('Name').':', '', $opt['update_procurement_services_form']['lbl_name']);?>
						<div class="fld">
							<?php echo form_input($opt['update_procurement_services_form']['project_procurement_services_name']);?>
							<div id="err_project_procurement_services_name" class="errormsg"></div>
						</div>
						<br>

						<?php echo form_label(lang('Type').':', '', $opt['update_procurement_services_form']['lbl_type']);?>
						<div class="fld">
							<?php echo form_input($opt['update_procurement_services_form']['project_procurement_services_type']);?>
							<div id="err_project_procurement_services_type" class="errormsg"></div>
						</div>
						<br>

						<?php echo form_label(lang('ProcurementProcess').':', '', $opt['update_procurement_services_form']['lbl_process']);?>
						<div class="fld">
							<?php echo form_input($opt['update_procurement_services_form']['project_procurement_services_process']);?>
							<div id="err_project_procurement_services_process" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('FinancialInformation').':', '', $opt['update_procurement_services_form']['lbl_info']);?>
						<div class="fld">
							<?php echo form_input($opt['update_procurement_services_form']['project_procurement_services_financial_info']);?>
							<div id="err_project_procurement_services_financial_info" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('Permissions').':', '', $opt['update_procurement_services_form']['lbl_permissions']);?>
						<?php
							$services_permission_attr = "id='project_procurement_services_permission'";
							$services_permission_options = array(
								'All'		=> lang('All'),
								'Some' 		=> lang('Some'),
 								'Other' 	=> lang('Other')
							);
							echo form_dropdown("project_procurement_services_permission",$services_permission_options,$val["permission"],$services_permission_attr);
						?>
						<br>										
						<?php echo form_submit('uservices_submit', lang('Update'),'class = "light_green btn_lml"');?>
						
						<?php echo form_close();?>
				</div>
				
			</li>

			<?php
			}
	}
}


if($formname == 'procurement_technology')
{
	if(count($procurement_technology_data) > 0)
	{

			foreach($procurement_technology_data as $key=>$val) 
			{
			?>
			<li class="" id="row_id_<?php echo $val["id"]; ?>">
				<div class="view clearfix">
					
					<span class="left"><?php echo $val["name"]; ?></span>

					<a class="right delete" href="#projects/delete_procurement_technology"><?php echo lang('Delete')?></a>

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
						<br>

						<?php echo form_label(lang('ProcurementProcess').':', '', $opt['update_procurement_technology_form']['lbl_process']);?>
						<div class="fld">
							<?php echo form_input($opt['update_procurement_technology_form']['project_procurement_technology_process']);?>
							<div id="err_project_procurement_technology_process" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('FinancialInformation').':', '', $opt['update_procurement_technology_form']['lbl_info']);?>
						<div class="fld">
							<?php echo form_input($opt['update_procurement_technology_form']['project_procurement_technology_financial_info']);?>
							<div id="err_project_procurement_technology_financial_info" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label(lang('Permissions').':', '', $opt['update_procurement_technology_form']['lbl_permissions']);?>
						<?php
							$technology_permission_attr = "id='project_procurement_technology_permission'";
							$technology_permission_options = array(
								'All'		=> lang('All'),
								'Some' 		=> lang('Some'),
								'Other' 	=> lang('Other')
							);
							echo form_dropdown("project_procurement_technology_permission",$technology_permission_options,$val["permission"],$technology_permission_attr);
						?>
						<br>										
						<?php echo form_submit('utechnology_submit', lang('Update'),'class = "light_green btn_lml"');?>
						
						<?php echo form_close();?>
				</div>
				
			</li>

			<?php
			}
	}
}


if($formname == 'project_machinery')
{
	if(count($machinery_data) > 0)
	{

			foreach($machinery_data as $key=>$val) 
			{
			?>
			<li class="" id="row_id_<?php echo $val["id"]; ?>">
					<div class="view clearfix">
						
						<span class="left"><?php echo $val["name"]; ?></span>

						<a class="right delete" href="#projects/delete_machinery"><?php echo lang('Delete');?></a>

						<a class="right edit" id="edit_machinery_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

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
							<br>

							<?php echo form_label(lang('ProcurementProcess').':', '', $opt['update_machinery_form']['lbl_process']);?>
							<div class="fld">
								<?php echo form_input($opt['update_machinery_form']['project_machinery_process']);?>
								<div id="err_project_machinery_process" class="errormsg"></div>
							</div>
							<br>
							
							<?php echo form_label(lang('FinancialInformation').':', '', $opt['update_machinery_form']['lbl_info']);?>
							<div class="fld">
								<?php echo form_input($opt['update_machinery_form']['project_machinery_financial_info']);?>
								<div id="err_project_machinery_financial_info" class="errormsg"></div>
							</div>
							<br>
							
							<?php echo form_label(lang('Permissions').':', '', $opt['update_machinery_form']['lbl_permissions']);?>
							<?php
								$machinery_permission_attr = "id='project_machinery_permission'";
								$machinery_permission_options = array(
									'All'		=> lang('All'),
									'Some' 		=> lang('Some'),
									'Other' 	=> lang('Other')
								);
								echo form_dropdown("project_machinery_permission",$machinery_permission_options,$val["permission"],$machinery_permission_attr);
							?>
							<br>										
							<?php echo form_submit('umachinery_submit', lang('Update'),'class = "light_green btn_lml"');?>
							
							<?php echo form_close();?>
					</div>
								
				</li>

			<?php
			}
	}
}


if($formname == 'participants_owners')
{
	if(count($participants_owners_data) > 0)
	{

			foreach($participants_owners_data as $key=>$val) 
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
	}
}
if($formname == 'participants_companies')
{
	if(count($participants_companies_data) > 0)
	{

			foreach($participants_companies_data as $key=>$val) 
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
	}
}
if($formname == 'participants_political')
{
	if(count($participants_political_data) > 0)
	{

			foreach($participants_political_data as $key=>$val) 
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
	}
}
if($formname == 'participants_public')
{
	if(count($participants_public_data) > 0)
	{

			foreach($participants_public_data as $key=>$val) 
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
	}
}



if($formname == 'project_regulatory')
{
	if(count($regulatory_data) > 0)
	{

			foreach($regulatory_data as $key=>$val) 
			{
			?>

			
			<li class="" id="row_id_<?php echo $val["id"]; ?>">
				<div class="view clearfix">
					
					<?php /* <span class="left"><?php echo $val["file"]; ?></span> */ ?>
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
									'All'		=> 'All',
									'Some' 		=> 'Some',
									'Other' 	=> 'Other'
								);
								echo form_dropdown('project_regulatory_permissions', $permissions_options,$val['permission'],$permissions_attr);
							?>
							<div class="errormsg"></div>
						</div>
															
						<?php echo form_submit('uragulatory_submit', lang('Update'),'class = "light_green btn_lml"');?>
						
						<?php echo form_close();?>
				</div>
			</li>
			

<?php 
			}
	}
}


if($formname == 'project_critical_participants')
{
	if(count($critical_participants_data) > 0)
	{

			foreach($critical_participants_data as $key=>$val) 
			{
			?>
				<li id="row_id_<?php echo $val["id"];?>" class="">
										
						<div class="view clearfix">
							
							<span class="left"><?php echo $val["role"];?></span>
	
							<span class="middle"><strong><?php echo $val["name"];?></strong><br><?php echo $val["description"];?></span>
	
										
							<a href="#projects/delete_critical_participants" class="right delete"><?php echo lang('Delete');?></a>
	
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
								'All'		=> lang('All'),
								'Some' 		=> lang('Some'),
								'Other' 	=> lang('Other')
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
	}
}
if($formname == 'project_roi')
{
	if(count($roi_data) > 0)
	{

			foreach($roi_data as $key=>$val) 
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
								<img src="/images/icons/<?php echo filetypeIcon($val['keystudy']);?>" alt="<?php echo lang('file')?>" title="<?php echo lang('file')?>">
							</a>
						
						<?php } ?>

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
										'name'	=> 'project_roi_keystudy'
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


					<?php echo form_label('Permissions:', '', $opt['update_roi_form']['lbl_permissions']);?>
					<?php
						$roi_permission_attr = "id='project_roi_permission'";
						$roi_permission_options = array(
							'All'		=> lang('All'),
							'Some' 		=> lang('Some'),
							'Other' 	=> lang('Other')
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
	}
}
if($formname == 'project_fund_sources')
{
	if(count($fund_sources_data) > 0)
	{

			foreach($fund_sources_data as $key=>$val) 
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
								'All'		=> lang('All'),
								'Some' 		=> lang('Some'),
								'Other' 	=> lang('Other')
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
	}
}


if($formname == 'project_studies')
{
	if(count($studies_data) > 0)
	{

			foreach($studies_data as $key=>$val) 
			{
			?>

				<li class="" id="row_id_<?php echo $val["id"];?>">
				<div class="view clearfix">
					
					<span class="left"><?php echo lang('StudyFile');?></span>
		
					<span class="left middle">
						<strong><?php echo $val["title"]; ?></strong><br><?php echo $val["description"]; ?>
					</span>
		
						<a class="right delete" href="#projects/delete_studies"><?php echo lang('Delete');?></a>
		
						<a class="right edit" id="edit_studies_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>
						
						<?php if($val['attachment']!= ''){ ?>
				
							<a href="<?php echo PROJECT_IMAGE_PATH.$val['attachment'];?>" class="right files" target="_blank">
								<img src="/images/icons/<?php echo filetypeIcon($val['attachment']);?>" alt="<?php echo lang('file')?>" title="<?php echo lang('file')?>">
							</a>
						
						<?php } ?>		
					
				</div>
				
				<div class="edit add_new">
									
						<?php echo form_open('projects/update_studies/'.$slug,array('id'=>'update_project_studies_form_'.$val["id"],'name'=>'update_project_studies_form_'.$val['id'],'method'=>'post','class'=>'ajax_form'));?>
							<?php 
								$opt['update_project_studies_form'] = array(
												'lbl_std_title' => array(
														'class' => 'left_label'
														),
												'project_studies_title'	=> array(
														'name' 		=> 'project_studies_title',
														'id' 		=> 'project_studies_title',
														'value'		=> $val["title"]
														),
												'lbl_std_description' => array(
														'class' => 'left_label'
														),
												'project_studies_desc'	=> array(
														'name' 		=> 'project_studies_desc',
														'id' 		=> 'project_studies_desc',
														'value'		=> $val["description"]
														),
												'lbl_std_attachment' => array(
														'class' => 'left_label'
														),
												'project_studies_attachment'	=> array(
														'name' 		=> 'project_studies_attachment',
														'id' 		=> 'project_studies_attachment',
														'value'		=> $val["attachment"]
														),
												'lbl_std_permissions' => array(
												'class' => 'left_label'
												)
										);
							?>

						
						<?php echo form_hidden("hdn_project_studies_id",$val["id"]); ?>
						<?php echo form_hidden("project_studies_attachmen_hidden",$val["attachment"]); ?>

						<?php echo form_label(lang('Title').':', 'project_studies_title', $opt['update_project_studies_form']['lbl_std_title']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_input($opt['update_project_studies_form']['project_studies_title']);?>
							<div id="err_project_studies_title" class="errormsg"></div>
						</div>
						
						<?php echo form_label(lang('Description').':', 'project_studies_desc', $opt['update_project_studies_form']['lbl_std_description']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_input($opt['update_project_studies_form']['project_studies_desc']);?>
							<div id="err_project_studies_description" class="errormsg"></div>
						</div>
						
						<?php echo form_label(lang('Attachment').':', 'project_studies_attachment', $opt['update_project_studies_form']['lbl_std_attachment']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_upload($opt['update_project_studies_form']['project_studies_attachment']);?>
							<div id="err_project_studies_attachment" class="errormsg"></div>
						</div>
						
						<?php echo form_label(lang('Permissions').':', 'project_studies_permissions', $opt['update_project_studies_form']['lbl_std_permissions']);?>
						<?php
							$project_studies_attr = 'id="project_studies_permissions"';
							$project_studies_options = array(
								'All'		=> lang('All'),
								'Some' 		=> lang('Some'),
								'Other' 	=> lang('Other')
							);
							echo form_dropdown('project_studies_permissions', $project_studies_options,$val["permission"],$project_studies_attr);
						?>
						<br>

						<?php echo form_submit('ustudies_submit', lang('Update'),'class = "light_green btn_lbl"');?>
						
						<?php echo form_close();?>

					</div>
						
			</li>
	
			<?php }
	}
}


if($formname == 'project_environment')
{
	if(count($environment_data) > 0)
	{
			foreach($environment_data as $key=>$val) 
			{
			?>

				<li class="" id="row_id_<?php echo $val["id"];?>">
				<div class="view clearfix">
					
					<span class="left"><?php echo lang('EnvironmentFile');?></span>
		
					<span class="left middle">
						<strong><?php echo $val["title"]; ?></strong><br><?php echo $val["description"]; ?>
					</span>
		
						<a class="right delete" href="#projects/delete_environment"><?php echo lang('Delete');?></a>
		
						<a class="right edit" id="update_environment_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>
						
						<?php if($val['attachment']!= ''){ ?>
				
							<a href="<?php echo PROJECT_IMAGE_PATH.$val['attachment'];?>" class="right files" target="_blank">
								<img src="/images/icons/<?php echo filetypeIcon($val['attachment']);?>" alt="<?php echo lang('file')?>" title="<?php echo lang('file')?>">
							</a>
						
						<?php } ?>
		
					
				</div>
				<div class="edit">
				<?php echo form_open('projects/update_environment/'.$slug,array('id'=>'update_environment_form_'.$val["id"],'name'=>'update_environment_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>
							<?php 
								$opt['update_environment_form'] = array(
												'lbl_env_title' => array(
														'class' => 'left_label'
														),
												'project_environment_title'	=> array(
														'name' 		=> 'project_environment_title',
														'id' 		=> 'project_environment_title',
														'value'	=> $val['title']
														),
												'lbl_env_description' => array(
														'class' => 'left_label'
														),
												'project_environment_desc'	=> array(
														'name' 		=> 'project_environment_desc',
														'id' 		=> 'project_environment_desc',
														'value'	=> $val['description']
														),
												'lbl_env_attachment' => array(
														'class' => 'left_label'
														),
												'project_environment_attachment'	=> array(
														'name' 		=> 'project_environment_attachments',
														'id' 		=> 'project_environment_attachments',
														'value'	=> $val['attachment']
														),
												'lbl_env_permissions' => array(
												'class' => 'left_label'
												)
										);
							?>

							<?php echo form_hidden("hdn_project_environment_id",$val["id"]); ?>
							<?php echo form_hidden("project_environment_attachmen_hidden",$val["attachment"]); ?>

						
						<?php echo form_label(lang('Title').':', 'project_environment_title', $opt['update_environment_form']['lbl_env_title']);?>
						<div class="fld" style="width:500px;">
							<?php echo form_input($opt['update_environment_form']['project_environment_title']);?>
							<div id="err_project_environment_title" class="errormsg"></div>
						</div>
						
						<?php echo form_label(lang('Description').':', 'project_environment_desc', $opt['update_environment_form']['lbl_env_description']);?>
						<div class="fld" style="width:500px;">
							<?php echo form_input($opt['update_environment_form']['project_environment_desc']);?>
							<div id="err_project_environment_description" class="errormsg"></div>
						</div>
						
						<?php echo form_label(lang('Attachment').':', 'project_environment_attachment', $opt['update_environment_form']['lbl_env_attachment']);?>
						<div class="fld" style="width:500px;">
							<?php echo form_upload($opt['update_environment_form']['project_environment_attachment']);?>
							<div id="err_project_environment_attachment" class="errormsg"></div>
						</div>
						
						<?php echo form_label(lang('Permissions').':', 'project_environment_permissions', $opt['update_environment_form']['lbl_env_permissions']);?>
						<?php
							$project_environment_attr = 'id="project_environment_permissions"';
							$project_environment_options = array(
								'All'		=> 'All',
								'Some' 		=> 'Some',
								'Other' 	=> 'Other'
							);
							echo form_dropdown('project_environment_permissions', $project_environment_options,$val['permission'],$project_environment_attr);
						?>
						<br>

						<?php echo form_submit('uenvironment_submit', lang('Update'),'class = "light_green btn_lbl"');?>
						
						<?php echo form_close();?>
						
						</div>
						</li>						
	
			<?php }
	}
}

if($formname == 'project_design_issue')
{
	if(count($design_issue_data) > 0)
	{

			foreach($design_issue_data as $key=>$val) 
			{
			?>

				<li class="" id="row_id_<?php echo $val["id"];?>">
					
					
					<div class="view clearfix">	
						<span class="left"><?php echo lang('DesignIssues');?></span>
					
						<span class="left middle">
							<strong><?php echo $val["title"]; ?></strong><br><?php echo $val["description"]; ?>
						</span>
						
						<a class="right delete" href="#projects/delete_design_issue"><?php echo lang('Delete');?></a>

						<a class="right edit" id="edit_design_issue_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>
						
						<?php if($val['attachment']!= ''){ ?>
			
						<a href="<?php echo PROJECT_IMAGE_PATH.$val['attachment'];?>" class="right files" target="_blank">
							<img src="/images/icons/<?php echo filetypeIcon($val['attachment']);?>" alt="<?php echo lang('file')?>" title="<?php echo lang('file')?>">
						</a>
						
						<?php	}  ?>


					</div>

					<div class="edit">

						<?php echo form_open('projects/update_design_issue/'.$slug,array('id'=>'update_design_issues_form','name'=>'update_design_issues_form','method'=>'post','class'=>'ajax_form'));?>
							<?php 
								$opt['update_design_issues_form'] = array(
												'lbl_title' => array(
														'class' => 'left_label'
														),
												'project_design_issues_title'	=> array(
														'name' 		=> 'project_design_issues_title',
														'id' 		=> 'project_design_issues_title',
														'value'		=> $val['title']
														),
												'lbl_description' => array(
														'class' => 'left_label'
														),
												'project_design_issues_desc'	=> array(
														'name' 		=> 'project_design_issues_desc',
														'id' 		=> 'project_design_issues_desc',
														'value'		=> $val['description']
														),
												'lbl_attachment' => array(
														'class' => 'left_label'
														),
												'project_design_issues_attachment'	=> array(
														'name' 		=> 'project_design_issues_attachment',
														'id' 		=> 'project_design_issues_attachment',
														'value'		=> $val['attachment']
														),
												'lbl_permissions' => array(
												'class' => 'left_label'
												)
										);
							?>

						<?php echo form_hidden("hdn_project_design_issues_id",$val["id"]); ?>
						<?php echo form_hidden("project_design_issue_attachmen_hidden",$val["attachment"]); ?>

						<?php echo form_label(lang('Title').':', 'project_design_issues_title', $opt['update_design_issues_form']['lbl_title']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_input($opt['update_design_issues_form']['project_design_issues_title']);?>
							<div id="err_project_design_issues_title" class="errormsg"></div>
						</div>
						
						<?php echo form_label(lang('Description').':', 'project_design_issues_desc', $opt['update_design_issues_form']['lbl_description']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_input($opt['update_design_issues_form']['project_design_issues_desc']);?>
							<div id="err_project_design_issues_description" class="errormsg"></div>
						</div>
						
						<?php echo form_label(lang('Attachment').':', 'project_design_issues_attachment', $opt['update_design_issues_form']['lbl_attachment']);?>
						<div class="fld" style="width:500px;">

							<?php echo form_upload($opt['update_design_issues_form']['project_design_issues_attachment']);?>
							<div id="err_project_design_issues_attachment" class="errormsg"></div>
						</div>
						
						<?php echo form_label(lang('Permissions').':', 'project_design_issues_permissions', $opt['update_design_issues_form']['lbl_permissions']);?>
						<?php
							$design_issue_attr = 'id="project_design_issues_permissions"';
							$design_issue_options = array(
								'All'		=> 'All',
								'Some' 		=> 'Some',
								'Other' 	=> 'Other'
							);
							echo form_dropdown('project_design_issues_permissions', $design_issue_options,$val["permission"],$design_issue_attr);
						?>
						<br>

						<?php echo form_submit('udesign_submit', lang('Update'),'class = "light_green btn_lbl"');?>
						
						<?php echo form_close();?>
						

					</div>

				</li>
							
	
			<?php }
	}
}

if($formname == 'project_map_point')
{
	if(count($map_point_data) > 0)
	{
		foreach($map_point_data as $key=>$val) 
		{
		?>

				<li class="" id="row_id_403">
					
					<div class="view clearfix">
						
						<span class="left"><strong><?php echo $val["name"]; ?></strong></span>

						<span class="left middle"><strong><?php echo lang('Lat').':';?> </strong><?php echo $val["latitude"]; ?></span>

						<span class="left middle"><strong><?php echo lang('Lng').':';?> </strong><?php echo $val["longitude"]; ?></span>

						<a class="right delete" href="#projects/delete_map_point"><?php echo lang('Delete');?></a>

						<a class="right edit" id="edit_map_point_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

					</div>

					<div class="edit">

						<?php echo form_open('projects/update_map_point/'.$slug,array('id'=>'update_map_points_form_'.$val["id"],'name'=>'update_map_points_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>
							<?php 
								$opt['update_map_points_form'] = array(
												'lbl_mapname' => array(
														'class' => 'left_label'
														),
												'project_map_points_mapname'	=> array(
														'name' 		=> 'project_map_points_mapname',
														'id' 		=> 'project_map_points_mapname',
														'value'		=> $val['name']
														),
												'project_map_points_latitude'	=> array(
														'name' 		=> 'project_map_points_latitude',
														'id' 		=> 'project_map_points_latitude',
														'value'		=> $val['latitude']
														),
												'lbl_latitude' => array(
														'class' => 'left_label'
														),
												'project_map_points_longitude'	=> array(
														'name' 		=> 'project_map_points_longitude',
														'id' 		=> 'project_map_points_longitude',
														'value'		=> $val['longitude']
														),
												'lbl_longitude' => array(
														'class' => 'left_label'
														)
										);
							?>


							
							<?php echo form_label(lang('Name').':', 'project_map_points_mapname', $opt['update_map_points_form']['lbl_mapname']);?>
							<div class="fld" style="width:500px;">

								<?php echo form_input($opt['update_map_points_form']['project_map_points_mapname']);?>
								<div id="err_project_map_points_mapname" class="errormsg"></div>
							</div>

							<?php echo form_label(lang('Latitude').':', 'project_map_points_latitude', $opt['update_map_points_form']['lbl_latitude']);?>
							<div class="fld" style="width:500px;">

								<?php echo form_input($opt['update_map_points_form']['project_map_points_latitude']);?>
								<div id="err_project_map_points_latitude" class="errormsg"></div>
							</div>

							<?php echo form_label(lang('Longitude').':', 'project_map_points_longitude', $opt['update_map_points_form']['lbl_longitude']);?>
							<div class="fld" style="width:500px;">

								<?php echo form_input($opt['update_map_points_form']['project_map_points_longitude']);?>
								<div id="err_project_map_points_longitude" class="errormsg"></div>
							</div>
							
							<?php echo form_hidden("hdn_project_map_points_id",$val["id"]); ?>


							
							<?php echo form_submit('upoints_submit', lang('update'),'class = "light_green btn_lml"');?>
						
							<?php echo form_close();?>
						

					</div>

				</li>
							

		<?php }
	}
}

						
						
						


if($formname == 'project_engineering')
{
	if(count($engineering_data) > 0)
	{
		foreach($engineering_data as $key=>$val) 
		{
		?>
		<li class="" id="row_id_<?php echo $val["id"]; ?>">
		<div class="view clearfix">
			
			<span class="left"><?php echo $val["role"]; ?></span>

			<span class="left middle">
				<strong><?php echo $val["contactname"]; ?></strong>
				<br>
				<?php echo $val["challenges"].", ".$val["innovations"]; ?>
			</span>

			<a class="right delete" href="#projects/delete_engineering"><?php echo lang('Delete');?></a>

			<a class="right edit" id="edit_engineering_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>
			
			<?php if($val['schedule']!= ''){ ?>
			
			<a href="<?php echo PROJECT_IMAGE_PATH.$val['schedule'];?>" class="right files" target="_blank">
				<img src="/images/icons/<?php echo filetypeIcon($val['schedule']);?>" alt="<?php echo lang('file')?>" title="<?php echo lang('file')?>">
			</a>
			
			<?php	}  ?>


		</div>

		<div class="edit">
			<?php echo form_open('projects/update_engineering/'.$slug,array('id'=>'update_engineering_form_'.$val["id"],'name'=>'update_engineering_form_'.$val["id"],'method'=>'post','class'=>'ajax_form'));?>

				<?php 
				
				$opt['update_engineering_form'] = array(
								'lbl_company' => array(
										'class' => 'left_label'
										),
								'project_engineering_company'	=> array(
										'name' 		=> 'project_engineering_company',
										'id' 		=> 'project_engineering_company',
										'value'		=> $val["company"]
										),
								'lbl_role' => array(
										'class' => 'left_label'
										),
								'project_engineering_role'	=> array(
										'name' 		=> 'project_engineering_role',
										'id' 		=> 'project_engineering_role',
										'value'		=> $val["role"]
										),
								'lbl_cname' => array(
										'class' => 'left_label'
										),
								'project_engineering_cname'	=> array(
										'name' 		=> 'project_engineering_cname',
										'id' 		=> 'project_engineering_cname',
										'value'		=> $val["contactname"]
										),
								'lbl_challenges' => array(
										'class' => 'left_label'
										),
								'project_engineering_challenges'	=> array(
										'name' 		=> 'project_engineering_challenges',
										'id' 		=> 'project_engineering_challenges',
										'value'		=> $val["challenges"]
										),
								'lbl_innovations' => array(
										'class' => 'left_label'
										),
								'project_engineering_innovations'	=> array(
										'name' 		=> 'project_engineering_innovations',
										'id' 		=> 'project_engineering_innovations',
										'value'		=> $val["innovations"]
										),
								'lbl_schedule' => array(
										'class' => 'left_label'
										),
								'project_engineering_schedule'	=> array(
										'name' 		=> 'project_engineering_schedule',
										'id' 		=> 'project_engineering_schedule',
										'value'		=> $val["schedule"]
										),
								'lbl_permissions' => array(
										'class' => 'left_label'
										),
					);

				?>
				
				<?php echo form_hidden("hdn_project_engineering_id",$val["id"]); ?>
				<?php echo form_hidden("project_engineering_schedul_hidden",$val["schedule"]); ?>
				
				<?php echo form_label(lang('Company').':', 'project_engineering_company', $opt['update_engineering_form']['lbl_company']);?>
				<div class="fld" style="width:500px;">

					<?php echo form_input($opt['update_engineering_form']['project_engineering_company']);?>
					<div id="err_project_engineering_company" class="errormsg"></div>
				</div>

				<?php echo form_label(lang('Role').':', 'project_engineering_role', $opt['update_engineering_form']['lbl_role']);?>
				<div class="fld" style="width:500px;">

					<?php echo form_input($opt['update_engineering_form']['project_engineering_role']);?>
					<div id="err_project_engineering_role" class="errormsg"></div>
				</div>

				<?php echo form_label(lang('ContactName').':', 'project_engineering_cname', $opt['update_engineering_form']['lbl_cname']);?>
				<div class="fld" style="width:500px;">

					<?php echo form_input($opt['update_engineering_form']['project_engineering_cname']);?>
					<div id="err_project_engineering_cname" class="errormsg"></div>
				</div>

				<?php echo form_label(lang('Challenges').':', 'project_engineering_challenges', $opt['update_engineering_form']['lbl_challenges']);?>
				<div class="fld" style="width:500px;">

					<?php echo form_input($opt['update_engineering_form']['project_engineering_challenges']);?>
					<div id="err_project_engineering_challenges" class="errormsg"></div>
				</div>

				<?php echo form_label(lang('Innovations').':', 'project_engineering_innovations', $opt['update_engineering_form']['lbl_innovations']);?>
				<div class="fld" style="width:500px;">

					<?php echo form_input($opt['update_engineering_form']['project_engineering_innovations']);?>
					<div id="err_project_engineering_innovations" class="errormsg"></div>
				</div>

				<?php echo form_label(lang('Schedule').':', 'project_engineering_schedule', $opt['update_engineering_form']['lbl_schedule']);?>
				<div class="fld" style="width:500px;">

					<?php echo form_upload($opt['update_engineering_form']['project_engineering_schedule']);?>
					<div id="err_project_engineering_schedule" class="errormsg"></div>
				</div>
				
				<?php echo form_label(lang('Permissions').':', 'project_engineering_permissions', $opt['update_engineering_form']['lbl_permissions']);?>
				<?php
					$permissions_attr = 'id="project_engineering_permissions"';
					$permissions_options = array(
						'All'		=> 'All',
						'Some' 		=> 'Some',
						'Other' 	=> 'Other'
					);
					echo form_dropdown('project_engineering_permissions', $permissions_options,$val['permission'],$permissions_attr);
				?>
				<br>
				
				<?php echo form_submit('uengineering_submit', lang('Update'),'class = "light_green btn_lml"');?>
				
				<?php echo form_close();?>
		</div>
		
	</li>


		<?php }
	}
}




if($formname == 'project_executives')
{
	if(count($executive_data) > 0)
	{
		foreach($executive_data as $key=>$val) 
		{
?>
	<li class="" id="row_id_<?php echo $val["id"]; ?>">
			<div class="view clearfix">
				
				<span class="left"><?php echo $val["company"]; ?></span>

				<span class="left middle">
					<strong><?php echo $val["executivename"]; ?></strong>
					<br>
					<?php echo $val["role"].", ".$val["email"]; ?>
				</span>

				<a class="right delete" href="#projects/delete_executive"><?php echo lang('Delete');?></a>

				<a class="right edit" id="edit_executive_<?php echo $val["id"]; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

			</div>

			<div class="edit">
				<?php echo form_open_multipart("projects/update_executive/".$slug."",array("id"=>"update_executive_form_".$val["id"], "name"=>"update_executive_form_".$val["id"],"class"=>"ajax_form")); ?>
				<?php echo form_hidden("hdn_project_executives_id",$val["id"]); ?>

				<?php echo form_label("Name:","",array("class"=>"left_label")); ?>
				<div class="fld">
					<?php echo form_input(array("name"=>"project_executives_name","value"=>$val["executivename"])); ?>
					<div class="errormsg" id="err_project_executives_name"></div>
				</div>
				<br>
				
				<?php echo form_label(lang("Company").":","",array("class"=>"left_label")); ?>
				<div class="fld">
					<?php echo form_input(array("name"=>"project_executives_company","value"=>$val["company"])); ?>
					<div class="errormsg" id="err_project_executives_name"></div>
				</div>
				<br>
				
				<?php echo form_label(lang("Role").":","",array("class"=>"left_label")); ?>
				<div class="fld">
					<?php 
						$project_executives_role_attr = "id='project_executives_role_".$val["id"]."' onchange='project_executive_other(this)'";
						$project_executives_role_options = array(
							"Finance"		=> lang("Finance"),
							"Engineering"	=> lang("Engineering"),
							"Construction"	=> lang("Construction"),
							"Admin"			=> lang("Admin"),
							"Affairs"		=> lang("Affairs"),
							"Other"			=> lang("Other")
						);
						echo form_dropdown("project_executives_role",$project_executives_role_options,$val["role"],$project_executives_role_attr);
					?>
					<div class="errormsg"></div>
				</div>
				
				<?php if(isset($val["role_other"]) && $val["role_other"]!=''){ $dropdownStyle = 'display:block;';} else{$dropdownStyle = 'display:none;';}?>
				<div style="<?php echo $dropdownStyle;?> clear:both;" class="role_other">
					<?php echo form_label(lang("Other").":","",array("class"=>"left_label")); ?>
					<?php echo form_input(array("name"=>"project_executives_role_other","id"=>"project_executives_role_other","value"=>isset($val["role_other"])?$val["role_other"]:'')); ?>
					<div class="errormsg" id="err_project_executives_role_other"></div>
				</div>

				<div style="clear:both;">
					<?php echo form_label(lang("Email").":","",array("class"=>"left_label")); ?>
					<div class="fld">
						<?php echo form_input(array("name"=>"project_executives_email","id"=>"project_executives_email","value"=>$val["email"])); ?>
						<div class="errormsg" id="err_project_executives_name"></div>
					</div>
				</div>
				<br>
				
				<?php echo form_submit(array("name"=>"Update","class"=>"light_green btn_lml","value"=>lang("Update"))); ?>
				<?php echo form_reset(array("class"=>"light_red btn_sml","value"=>lang("Close"))); ?>
				<?php echo form_close(); ?>
			</div>
			
		</li>
<?php
		}
	}
}

if($formname == 'project_organization')
{
	if(count($organization_data) > 0)
	{
		foreach($organization_data as $key=>$val) 
		{
?>
	<li class="" id="row_id_<?php echo $val["id"]; ?>">
		<div class="view clearfix">
			
			<span class="left"><?php echo $val["company"]; ?></span>

			<span class="left middle">
				<strong><?php echo $val["contact"]; ?></strong>
				<br>
				<?php echo $val["role"].", ".$val["email"]; ?>
			</span>

			<a class="right delete" href="#projects/delete_organization"><?php echo lang('Delete');?></a>

			<a class="right edit" href="javascript:void(0);" id="edit_organization_<?php echo $val["id"];?>" onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

		</div>

		<div class="edit">
			<?php echo form_open_multipart("projects/update_organization/".$slug."",array("id"=>"update_organization_form_".$val["id"],"name"=>"update_organization_form_".$val["id"],"class"=>"ajax_form")); ?>
		<?php echo form_hidden("hdn_project_organizations_id",$val["id"]); ?>
		
		<?php echo form_label(lang("CompanyName").":","",array("class"=>"left_label")); ?>
		<div class="fld">
			<?php echo form_input(array("name"=>"project_organizations_company","id"=>"project_organizations_company","value"=>$val["company"])); ?>
			<div class="errormsg" id="err_project_organizations_company_name"></div>
		</div>
		<br>
		
		
		<?php echo form_label(lang("Role").":","",array("class"=>"left_label")); ?>
		<div class="fld">
			<?php 
				$project_organizations_role_attr = "";
				$project_organizations_role_options = array(
					"Sponser"	=> lang("Sponsor"),
					"Overseer"	=> lang("Overseer")
				);
				echo form_dropdown("project_organizations_role",$project_organizations_role_options,$val["role"],$project_organizations_role_attr);
			?>
			<div class="errormsg"></div>
		</div>
		
		<br>
		
		<?php echo form_label(lang("Contact").":","",array("class"=>"left_label")); ?>
		<div class="fld">
		<?php echo form_input(array("name"=>"project_organizations_contact","id"=>"project_organizations_contact","value"=>$val["contact"])); ?>
			<div class="errormsg"></div>
		</div>
		<br>
		
		<?php echo form_label(lang("Email").":","",array("class"=>"left_label")); ?>
		<div class="fld">
		<?php echo form_input(array("name"=>"project_organizations_email","id"=>"project_organizations_email","value"=>$val["email"])); ?>
			<div class="errormsg"></div>
		</div>
		<br>
		<?php echo form_submit(array("name"=>"Update","class"=>"light_green btn_lml","value"=>lang("Update"))); ?>
		<?php echo form_reset(array("class"=>"light_red btn_sml","value"=>lang("Close"))); ?>
		<?php echo form_close(); ?>
		</div>
		
	</li>
<?php
		}
	}
} 

if($formname == 'expertise_education_form' && $type=='view')
{
	if(count($education_data) > 0 )
	{
	foreach($education_data as $key=>$edu)
	{
		//$editlink 	= '/profile/form_load/expertise_education_form/edit/'.$edu['educationid'];
		$editlink 	= 'javascript:void(0);';
		$deletelink = '/profile/delete_education/'.$edu['educationid'];
		?>
		<div id="education_<?php echo $edu['educationid'];?>" class="edu_listing clearfix">
			<div class="clearfix">
				<p class="left"><strong><?php echo $edu['university'];?></strong> <?php echo '('.$edu['startyear'].' - '.$edu['gradyear'].')';?><br><?php echo $edu['degree'].' : '.$edu['major'];?></p>
					<a class="right delete" href="<?php echo $deletelink; ?>">Delete</a>
					<a class="right edit" href="<?php echo $editlink; ?>" id="education_edit_<?php echo $edu['educationid'];?>" onclick="edu_rowtoggle(this.id);">Edit</a>
			</div>
		
		<div style="display: none;" class="education_edit">
		
		
		<?php $formlink = "profile/update_education/".$edu['educationid'];?>
		
		<?php echo form_open($formlink,array('id'=>'expertise_education_form_'.$edu["educationid"],'name'=>'expertise_education_form_'.$edu["educationid"],'method'=>'post','class'=>'ajax_form'));
			
			$opt['expertise_education_form'] = array(
						'lbl_university' => array(
								'class' => 'left_label'
								),
						'education_university'	=> array(
								'name' 		=> 'education_university',
								'id'		=> 'education_university'.$edu["educationid"].'',
								'value'		=> $edu['university']
								),
						'lbl_major' => array(
								'class' => 'left_label'
								),
						'education_major'	=> array(
								'name' 		=> 'education_major',
								'id'		=> '',
								'value'		=> $edu['major']
								),
						'education_degree_other'	=> array(
								'name' 		=> 'education_degree_other',
								'id'		=> '',
								'value'		=> $edu['degree_other']
								),		
						'lbl_degree' => array(
								'class' => 'left_label'
								),
						'lbl_startyear' => array(
								'class' => 'left_label'
								),
						'lbl_gradyear' => array(
								'class' => ''
								),
						
						
					);
			
			?>
		
			<div class="top clearfix">						
				
				<strong class="left"><?php echo lang('EditEducation');?></strong>
				
				<div class="right">
				
					<?php echo form_label(lang('Visibility').':', 'education_visibility');?>
					<?php						
						$education_visibility_attr = 'id="education_visibility"';
						$education_visibility_options = array(
						'All'		=> lang('All'),
						'Some' 		=> lang('Some'),
						'Other' 	=> lang('Other')
					   );
						echo form_dropdown('education_visibility', $education_visibility_options,$edu['visibility'],$education_visibility_attr);
					?>

														
				</div>
				
			</div>
						
			<div class="inner">
			
				<div>
					<?php echo form_label(lang('UniversityName').':', 'education_university', $opt['expertise_education_form']['lbl_university']);?>
					<?php echo form_input($opt['expertise_education_form']['education_university']);?>
					<div id="err_education_university" class="errormsg" style="margin-left:120px;"></div>
				</div>
				
				<div>
					<?php echo form_label(lang('Degree').':', 'education_degree', $opt['expertise_education_form']['lbl_degree']);?>
					<div>
					<?php						
							$education_degree_attr = 'id="education_degree"';
							$education_degree_options = education_dropdown();
							echo form_dropdown('education_degree', $education_degree_options,$edu['degree'],$education_degree_attr);
					?>
					</div>
					<div id="err_education_degree" class="errormsg"></div>
				</div>
				
				<div <?php if($edu['degree'] != "Other") { ?>style="display:none" <?php } ?>>
					<?php echo form_label(lang('Other').':', 'education_degree_other', $opt['expertise_education_form']['lbl_major']);?>
					<div class="fld" style="width:370px;">
						<?php echo form_input($opt['expertise_education_form']['education_degree_other']);?>
						<div id="err_education_degree_other" class="errormsg"></div>
					</div>

				</div>
					
				<div>
					<?php echo form_label(lang('Major').':', 'education_major', $opt['expertise_education_form']['lbl_major']);?>
					<?php echo form_input($opt['expertise_education_form']['education_major']);?>
					<div id="err_education_major" class="errormsg"></div>

				</div>
				
				<div>
					<?php echo form_label(lang('Years').':', 'education_start_year', $opt['expertise_education_form']['lbl_startyear']);?>
					<?php						
							$education_start_year_attr = 'id="education_start_year"';
							$education_start_year_options = year_dropdown('- year -');
							echo form_dropdown('education_start_year', $education_start_year_options,$edu['startyear'],$education_start_year_attr);
					?>
					
					<?php echo form_label(lang('to').':', 'education_grad_year', $opt['expertise_education_form']['lbl_gradyear']);?>
					<?php						
							$education_grad_year_attr = 'id="education_grad_year"';
							$education_grad_year_options = year_dropdown('- year -');
							echo form_dropdown('education_grad_year', $education_grad_year_options,$edu['gradyear'],$education_grad_year_attr);
					?>
					
				</div>
				
				<div>
					<?php echo form_submit('edu_submit', lang('SaveEducation'),'class = "light_green no_margin_left"');?>
					<input type="button" value=<?php echo lang("Cancel");?> name="cancel" class="light_gray no_margin_left" onclick="javascript: $(this).parents('.education_edit').hide();">
				</div>
				
			</div><!-- end .inner -->
						
			<?php echo form_close();?>
					
		</div>
		
		
		</div>
		<!-- .edu_listing -->
	<?php }
	}
 }
if($formname == "project_comment" && $type == lang("view"))
{
	$totalcomment = count($project_comment_data);

	if($totalcomment > 0 )
	{
		$i = 1;
		echo heading(lang("Comments").":",3);
		foreach($project_comment_data as $comments)
		{
	?>
		<div id="comment_<?php echo $comments["id"]; ?>">
		<div><div class="fld" style="width:100%"><?php echo $comments["comment"]; ?></div><div style="float:left; position:absolute; right:15px;"><a href="javascript:void(0)" onclick="delete_maxtrix_action('projects/delete_comment','<?php echo $comments["id"]; ?>','comment_<?php echo $comments["id"]; ?>')"><?php echo lang('Delete');?></a></div></div>
		<div align="right" style="color:#AAAAAA"><?php echo DateFormat($comments["commentdate"],DATEFORMAT,TRUE); ?></div>
		<?php if($totalcomment != $i) { ?><hr style="margin:10px 0;"/><?php } ?>
		</div>
	<?php $i++; }
	}
} 


//print_r(subsectors());echo $secid; exit;
if($formname == "get_subsector_ddl" && $secid != '')
{

	$project_sector_sub_attr 		= 'id="project_sector_sub'.$secid.'" class="project_sub"';
	$subsector_options 	= array();
	$subsector_opt		= array();
	foreach(subsectors() as $key=>$value)
	{
		if($key != $secid)
		{
			continue;
		}
		foreach($value as $key2=>$value2)
		{
			$subsector_options[$value2] 	= $value2;
			$subsector_opt[$value2] 		= 'class="project_sector_sub_'.$key.'"';
		}
	}
	$subsector_first			= array('class'=>'hardcode','text'=>lang('SelectASub-Sector'),'value'=>'');
	$subsector_last				= array('class'=>'hardcode','value'=>'Other','text'=>'Other');
	echo form_custom_dropdown('member_sub_sector', $subsector_options,'',$project_sector_sub_attr,$subsector_opt,$subsector_first,$subsector_last);

}


if($formname == 'expertise_sector_form' && $type=='view')
{
	if(count($sector_data) > 0 )
	{
	foreach($sector_data as $key=>$sec)
	{
		$editlink 	= '/profile/form_load/'.$formname.'/edit/'.$sec['id'];
		$deletelink = '/profile/delete_expert_sector/'.$sec['id'];
		$formlink = "profile/update_expert_sector/".$sec['id'];
		
	?>
		
		<div class="sector_edit">
					
									
			<div id="sectorContainer">
			
			
			
			<div id="sector_row_<?php echo $sec["id"];?>" class="sector_row">
				<div class="clearfix">
					<p class="left"><strong><?php echo $sec['sector'];?></strong> <br/>(<?php echo $sec['subsector'];?>)</p>
						<a href="javascript:void(0);" id="delete_sector_<?php echo $sec["id"];?>" onclick="show_confirmation(this.id);" class="right delete"><?php echo lang('Delete');?></a>
						<a href="javascript:void(0);" id="edit_sector_<?php echo $sec["id"];?>" class="right edit" onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>
						
						<div class="delete_sector_<?php echo $sec["id"];?>" style="display:none;">
							<a class="right confirm_yes" href="javascript:void(0);" onclick="delete_maxtrix_action('<?php echo $deletelink;?>','','sector_row_'+<?php echo $sec["id"];?>);"><?php echo lang('Yes');?></a>
							<a class="right confirm_no" href="javascript:void(0);" id="reset_<?php echo $sec['id'];?>" onclick="reset_confirmation(this.id);"><?php echo lang('NO');?></a>
						</div>
	
				</div>
				<!-- end .view -->
				<div class="edit" style="display: none;">
				<?php echo form_open($formlink,array('id'=>'expertise_sector_form_'.$sec["id"],'name'=>'expertise_sector_form_'.$sec["id"],'method'=>'post','class'=>'ajax_form')); ?>
				<?php 
				
				$opt['expertise_sector_form'] = array(
														'lbl_sector_main' => array(
															'class' => 'left_label'
															),
														'lbl_sector_sub' => array(
															'class' => 'left_label'
															),
															'lbl_sub_sector_other' => array(
															'class' => 'left_label'
															),
	
														'member_sub_sector_other'=> array(
															'id'	 	=> 'profile_sector_sub_other',
															'name'		=> 'member_sub_sector_other',
															'value'		=> '',
															'disabled'	=> 'disabled'
															),
	
														);
							?>		
				<div class="clearfix">
					<?php echo form_label(lang('Sector').':', 'project_sector_main', $opt['expertise_sector_form']['lbl_sector_main']);?>
					<div class="fld">
					<?php
						$project_sector_main_attr	= 'id="project_sector_main'.$sec["id"].'" onchange="sectorbind('.$sec["id"].');"';
						//$project_sector_main_attr	= 'id="project_sector_main"';
						$sector_option = array();
						$sector_opt =array();
						foreach(sectors() as $key=>$value)
						{
							$sector_options[$value] = $value;
							$sector_opt[$value] 	= 'class="sector_main_'.$key.'"';
						}
						$sector_first			= array('class'=>'hardcode','text'=>lang('SelectASector'),'value'=>'');
						$sector_last			= array();
						
						echo form_custom_dropdown('member_sector', $sector_options,$sec['sector'],$project_sector_main_attr,$sector_opt,$sector_first,$sector_last);
					?>
					<div class="fld errormsg" style="clear:both;"></div>
					</div>
				</div>
				<br/>
				<div>
					<?php echo form_label(lang('Sub-Sector').':', 'project_sector_sub', $opt['expertise_sector_form']['lbl_sector_sub']);?>
					<div class="fld" id="dynamicSubsector_<?php echo $sec["id"];?>">
						<?php
							$project_sector_sub_attr 		= 'id="project_sector_sub'.$sec["id"].'" class="project_sub"';
							$subsector_options 	= array();
							$subsector_opt		= array();
							$selected_sector	= getsectorid("'".$sec['subsector']."'",1);
							
							foreach(subsectors() as $key=>$value)
							{
								foreach($value as $key2=>$value2)
								{
									if($key != $selected_sector)
									{
										continue;
									}
									$subsector_options[$value2] 	= $value2;
									$subsector_opt[$value2] 		= 'class="project_sector_sub_'.$key.'"';
								}
							}
							$subsector_first			= array('class'=>'hardcode','text'=>lang('SelectASub-Sector'),'value'=>'');
							$subsector_last				= array('class'=>'hardcode','value'=>'Other','text'=>'Other');
							echo form_custom_dropdown('member_sub_sector', $subsector_options,$sec['subsector'],$project_sector_sub_attr,$subsector_opt,$subsector_first,$subsector_last);
						?>
					</div>
					<div class="fld errormsg" style="clear:both; margin-left:120px;"></div>

					<div style="display:none">

						<?php echo form_label(lang('Sub-SectorOther').':', 'profile_sector_sub_other', $opt['expertise_sector_form']['lbl_sub_sector_other']);?>						
						<div class="fld" style="width:625px;">
							<?php echo form_input($opt['expertise_sector_form']['member_sub_sector_other']);?>
						</div>
					</div>
				</div>
					<!-- end .edit -->
					<div class="view clearfix">
							<?php echo form_submit('udateedu_submit', lang('UpdateSector'),'class = "light_green no_margin_left" id="btn_add_sector"  style="float:right;margin-right:10px;margin-bottom:10px;"');?>
					</div>
					<?php echo form_close();?>
				</div>
				
				</div>
			
			</div>
						
	
					
		</div>
		<!-- .edu_listing -->
	<?php }
	}
 }
if($formname == "forum_comment" && $type == "view")
{
	$totalcomment = $comments["totalcomment"];
	if($totalcomment > 0)
	{
		foreach($comments["comments"] as $comments) {
?>
		<div class="comment" id="comment_<?php echo $comments["id"]; ?>">
			<span class="comment_body">
				<p><?php echo $comments["comment"]; ?></p>
			</span>
			<?php //if($comments["uid"] == $uid) { ?>
				<a href="javascript:void(0)" class="edit_link"><?php echo lang('Edit');?></a>
				<div class="editCommentBox" style="display:none;">
					<?php echo form_textarea(array("name"=>"comment","id"=>"comment","rows"=>"8","cols"=>"70","value"=>$comments["comment"])); ?>
					<br>
					<?php echo form_submit(array("name"=>"cancel","value"=>lang("Cancel"),"class"=>"cancel_edit")); ?>
					<?php echo form_submit(array("name"=>"save","id"=>"submit","value"=>lang("Save"),"class"=>"submit_edit")); ?>
				</div>
				<a href="#" class="mod_link"><?php echo lang('Close');?></a>
			<?php //} ?>
		</div>

<?php
		}
	}
} 
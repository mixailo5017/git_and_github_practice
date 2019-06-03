<?php 

if($loadtype == 'project_files')
{
	if(count($project_files_data) > 0)
	{

			foreach($project_files_data as $key=>$val) 
			{
			?>
				<div class="contenttitle2">
		            <h3>Edit File</h3>
			    </div>

				<?php echo form_open('projects/update_project_files/'.$slug,array('id'=>'update_project_files_form_'.$val["id"],'name'=>'update_project_files_form_'.$val["id"],'method'=>'post','class'=>'ajax_add_form'));?>
				
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
					
					<?php echo form_label('File:', 'project_files_filename', $opt['update_project_files_form']['lbl_filename']);?>
					<div class="fld" >
	
						<?php echo form_upload($opt['update_project_files_form']['project_files_filename']);?>
						<div class="errormsg"></div>
					</div>
					<br>
					
					<?php echo form_label('Description:', 'project_files_desc', $opt['update_project_files_form']['lbl_description']);?>
					<div class="fld" >
	
						<?php echo form_input($opt['update_project_files_form']['project_files_desc']);?>
						<div class="errormsg"></div>
					</div>
					<br>
					
					<?php echo form_label('Permission:', 'project_files_permission', $opt['update_project_files_form']['lbl_permission']);?>
					<div class="fld" >
						<?php
							$permissions_attr = 'id="project_files_permission"';
							$permissions_options = array(
								'All'		=> 'All',
								'Some' 		=> 'Some',
								'Other' 	=> 'Other'
							);
							echo form_dropdown('project_files_permissions', $permissions_options,$val['permission'],$permissions_attr);
						?>
						<div class="errormsg"></div>
					</div>
														
					<?php echo form_submit('submit', 'Update','class = "light_green btn_lml"');?>
					
					<?php echo form_close();?>
		<?php
			}
	}
}


if($loadtype == 'procurement_services')
{
	if(count($procurement_services_data) > 0)
	{

			foreach($procurement_services_data as $key=>$val) 
			{
			?>
			
			<div class="contenttitle2">
	            <h3>Edit Procurement Service</h3>
		    </div>
		    	
			<?php echo form_open('projects/update_procurement_services/'.$slug,array('id'=>'update_procurement_services_form_'.$val["id"],'name'=>'update_procurement_services_form_'.$val["id"],'method'=>'post','class'=>'ajax_add_form'));?>

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
				<br>

				<?php echo form_label('Type:', '', $opt['update_procurement_services_form']['lbl_type']);?>
				<div class="fld">
					<?php echo form_input($opt['update_procurement_services_form']['project_procurement_services_type']);?>
					<div id="err_project_procurement_services_type" class="errormsg"></div>
				</div>
				<br>

				<?php echo form_label('Procurement Process:', '', $opt['update_procurement_services_form']['lbl_process']);?>
				<div class="fld">
					<?php echo form_input($opt['update_procurement_services_form']['project_procurement_services_process']);?>
					<div id="err_project_procurement_services_process" class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Financial Information:', '', $opt['update_procurement_services_form']['lbl_info']);?>
				<div class="fld">
					<?php echo form_input($opt['update_procurement_services_form']['project_procurement_services_financial_info']);?>
					<div id="err_project_procurement_services_financial_info" class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Permissions:', '', $opt['update_procurement_services_form']['lbl_permissions']);?>
				<div class="fld"><?php
					$services_permission_attr = "id='project_procurement_services_permission'";
					$services_permission_options = array(
						"All"	=> "All",
						"Some"	=> "Some",
						"Other"	=> "Other"
					);
					echo form_dropdown("project_procurement_services_permission",$services_permission_options,$val["permission"],$services_permission_attr);
				?>
				</div>
				<br>										
				<?php echo form_submit('submit', 'Update','class = "light_green btn_lml"');?>
				
				<?php echo form_close();?>
			<?php
			}
	}
}


if($loadtype == 'procurement_technology')
{
	if(count($procurement_technology_data) > 0)
	{

			foreach($procurement_technology_data as $key=>$val) 
			{
			?>
			<div class="contenttitle2">
	            <h3>Edit Procurement Technology</h3>
		    </div>

			<?php echo form_open('projects/update_procurement_technology/'.$slug,array('id'=>'update_procurement_technology_form_'.$val["id"],'name'=>'update_procurement_technology_form_'.$val["id"],'method'=>'post','class'=>'ajax_add_form'));?>

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
								
				<?php echo form_label('Name:', '', $opt['update_procurement_technology_form']['lbl_name']);?>
				<div class="fld">
					<?php echo form_input($opt['update_procurement_technology_form']['project_procurement_technology_name']);?>
					<div id="err_project_procurement_technology_name" class="errormsg"></div>
				</div>
				<br>

				<?php echo form_label('Procurement Process:', '', $opt['update_procurement_technology_form']['lbl_process']);?>
				<div class="fld">
					<?php echo form_input($opt['update_procurement_technology_form']['project_procurement_technology_process']);?>
					<div id="err_project_procurement_technology_process" class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Financial Information:', '', $opt['update_procurement_technology_form']['lbl_info']);?>
				<div class="fld">
					<?php echo form_input($opt['update_procurement_technology_form']['project_procurement_technology_financial_info']);?>
					<div id="err_project_procurement_technology_financial_info" class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Permissions:', '', $opt['update_procurement_technology_form']['lbl_permissions']);?>
				<div class="fld"><?php
					$technology_permission_attr = "id='project_procurement_technology_permission'";
					$technology_permission_options = array(
						"All"	=> "All",
						"Some"	=> "Some",
						"Other"	=> "Other"
					);
					echo form_dropdown("project_procurement_technology_permission",$technology_permission_options,$val["permission"],$technology_permission_attr);
				?>
				</div>
				<br>										
				<?php echo form_submit('submit', 'Update','class = "light_green btn_lml"');?>
				
				<?php echo form_close();?>
			
			<?php
			}
	}
}


if($loadtype == 'project_machinery')
{
	if(count($machinery_data) > 0)
	{

			foreach($machinery_data as $key=>$val) 
			{
			?>
				<div class="contenttitle2">
		            <h3>Edit Machinery</h3>
			    </div>

	
				<?php echo form_open('projects/update_machinery/'.$slug,array('id'=>'update_machinery_form_'.$val["id"],'name'=>'update_machinery_form_'.$val["id"],'method'=>'post','class'=>'ajax_add_form'));?>

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
									
					<?php echo form_label('Name:', '', $opt['update_machinery_form']['lbl_name']);?>
					<div class="fld">
						<?php echo form_input($opt['update_machinery_form']['project_machinery_name']);?>
						<div id="err_project_machinery_name" class="errormsg"></div>
					</div>
					<br>

					<?php echo form_label('Procurement Process:', '', $opt['update_machinery_form']['lbl_process']);?>
					<div class="fld">
						<?php echo form_input($opt['update_machinery_form']['project_machinery_process']);?>
						<div id="err_project_machinery_process" class="errormsg"></div>
					</div>
					<br>
					
					<?php echo form_label('Financial Information:', '', $opt['update_machinery_form']['lbl_info']);?>
					<div class="fld">
						<?php echo form_input($opt['update_machinery_form']['project_machinery_financial_info']);?>
						<div id="err_project_machinery_financial_info" class="errormsg"></div>
					</div>
					<br>
					
					<?php echo form_label('Permissions:', '', $opt['update_machinery_form']['lbl_permissions']);?>
					<div class="fld"><?php
						$machinery_permission_attr = "id='project_machinery_permission'";
						$machinery_permission_options = array(
							"All"	=> "All",
							"Some"	=> "Some",
							"Other"	=> "Other"
						);
						echo form_dropdown("project_machinery_permission",$machinery_permission_options,$val["permission"],$machinery_permission_attr);
					?>
					</div>
					<br>										
					<?php echo form_submit('submit', 'Update','class = "light_green btn_lml"');?>
					
					<?php echo form_close();?>
			<?php
			}
	}
}


if($loadtype == 'participants_owners')
{
	if(count($participants_owners_data) > 0)
	{

			foreach($participants_owners_data as $key=>$val) 
			{
			?>
			<div class="contenttitle2">
	            <h3>Edit Participant Owner</h3>
		    </div>

			<?php echo form_open('projects/update_participants_owners/'.$slug,array('id'=>'update_participants_owners_form_'.$val["id"],'name'=>'participants_owners_form_'.$val["id"],'method'=>'post','class'=>'ajax_add_form'));?>

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

                <?php echo form_hidden("hdn_participants_owners_id", $val["id"]) ?>

                <?php echo form_label('Name:', 'participants_owners_name', $opt['update_participants_owners_form']['lbl_name']);?>
				<div class="fld" >

					<?php echo form_input($opt['update_participants_owners_form']['project_participants_owners_name']);?>
					<div class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Type:', 'participants_owners_type', $opt['update_participants_owners_form']['lbl_type']);?>
				<div class="fld" >

					<?php echo form_input($opt['update_participants_owners_form']['project_participants_owners_type']);?>
					<div class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Description:', 'project_participants_owners_desc', $opt['update_participants_owners_form']['lbl_description']);?>
				<div class="fld" >

					<?php echo form_input($opt['update_participants_owners_form']['project_participants_owners_desc']);?>
					<div class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Permissions:', 'project_participants_owners_permissions', $opt['update_participants_owners_form']['lbl_permissions']);?>
				<div class="fld"><?php
					$permissions_attr = 'id="project_participants_owners_permissions"';
					$permissions_options = array(
						'All'		=> 'All',
						'Some' 		=> 'Some',
						'Other' 	=> 'Other'
					);
					echo form_dropdown('project_participants_owners_permissions', $permissions_options,'',$permissions_attr);
				?>
				</div>
				<br>
				
				<?php echo form_submit('submit', 'Update','class = "light_green btn_lml"');?>
				
				<?php echo form_close();?>

<?php 
			}
	}
}
if($loadtype == 'participants_companies')
{
	if(count($participants_companies_data) > 0)
	{

			foreach($participants_companies_data as $key=>$val) 
			{
			?>
			<div class="contenttitle2">
	            <h3>Edit Participant Company</h3>
		    </div>

			<?php echo form_open('projects/update_participants_companies/'.$slug,array('id'=>'update_participants_companies_form_'.$val["id"],'name'=>'participants_companies_form_'.$val["id"],'method'=>'post','class'=>'ajax_add_form'));?>
	
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

                <?php echo form_hidden("hdn_participants_companies_id", $val["id"]) ?>

                <?php echo form_label('Name:', 'participants_companies_name', $opt['update_participants_companies_form']['lbl_name']);?>
				<div class="fld" >
	
					<?php echo form_input($opt['update_participants_companies_form']['project_participants_companies_name']);?>
					<div class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Role:', 'participants_companies_role', $opt['update_participants_companies_form']['lbl_role']);?>
				<div class="fld" >
	
					<?php echo form_input($opt['update_participants_companies_form']['project_participants_companies_role']);?>
					<div class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Description:', 'project_participants_companies_desc', $opt['update_participants_companies_form']['lbl_description']);?>
				<div class="fld" >
	
					<?php echo form_input($opt['update_participants_companies_form']['project_participants_companies_desc']);?>
					<div class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Permissions:', 'project_participants_companies_permissions', $opt['update_participants_companies_form']['lbl_permissions']);?>
				<div class="fld"><?php
					$permissions_attr = 'id="project_participants_companies_permissions"';
					$permissions_options = array(
						'All'		=> 'All',
						'Some' 		=> 'Some',
						'Other' 	=> 'Other'
					);
					echo form_dropdown('project_participants_companies_permissions', $permissions_options,'',$permissions_attr);
				?>
				</div>
				<br>
				
				<?php echo form_submit('submit', 'Update','class = "light_green btn_lml"');?>
				
				<?php echo form_close();?>
		<?php 
			}
	}
}
if($loadtype == 'participants_political')
{
	if(count($participants_political_data) > 0)
	{

			foreach($participants_political_data as $key=>$val) 
			{
			?>
			<div class="contenttitle2">
	            <h3>Edit Political Praticipant</h3>
		    </div>
			<?php echo form_open('projects/update_participants_political/'.$slug,array('id'=>'update_participants_political_form_'.$val["id"],'name'=>'participants_political_form_'.$val["id"],'method'=>'post','class'=>'ajax_add_form'));?>

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

                <?php echo form_hidden("hdn_participants_political_id", $val["id"]) ?>

                <?php echo form_label('Name:', 'participants_political_name', $opt['update_participants_political_form']['lbl_name']);?>
				<div class="fld" >

					<?php echo form_input($opt['update_participants_political_form']['project_participants_political_name']);?>
					<div class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Type:', 'participants_political_type', $opt['update_participants_political_form']['lbl_type']);?>
				<div class="fld" >

					<?php echo form_input($opt['update_participants_political_form']['project_participants_political_type']);?>
					<div class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Description:', 'project_participants_political_desc', $opt['update_participants_political_form']['lbl_description']);?>
				<div class="fld" >

					<?php echo form_input($opt['update_participants_political_form']['project_participants_political_desc']);?>
					<div class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Permissions:', 'project_participants_political_permissions', $opt['update_participants_political_form']['lbl_permissions']);?>
				<div class="fld"><?php
					$permissions_attr = 'id="project_participants_political_permissions"';
					$permissions_options = array(
						'All'		=> 'All',
						'Some' 		=> 'Some',
						'Other' 	=> 'Other'
					);
					echo form_dropdown('project_participants_political_permissions', $permissions_options,'',$permissions_attr);
				?>
				</div>
				<br>
				
				<?php echo form_submit('submit', 'Update','class = "light_green btn_lml"');?>
				
				<?php echo form_close();?>
	
<?php 
			}
	}
}
if($loadtype == 'participants_public')
{
	if(count($participants_public_data) > 0)
	{

			foreach($participants_public_data as $key=>$val) 
			{
			?>
			<div class="contenttitle2">
	            <h3>Edit Public Participant</h3>
		    </div>

			<?php echo form_open('projects/update_participants_public/'.$slug,array('id'=>'update_participants_public_form_'.$val["id"],'name'=>'participants_public_form_'.$val["id"],'method'=>'post','class'=>'ajax_add_form'));?>

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

                <?php echo form_hidden("hdn_participants_public_id", $val["id"]) ?>

                <?php echo form_label('Name:', 'participants_public_name', $opt['update_participants_public_form']['lbl_name']);?>
				<div class="fld" >

					<?php echo form_input($opt['update_participants_public_form']['project_participants_public_name']);?>
					<div class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Type:', 'participants_public_type', $opt['update_participants_public_form']['lbl_type']);?>
				<div class="fld" >

					<?php echo form_input($opt['update_participants_public_form']['project_participants_public_type']);?>
					<div class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Description:', 'project_participants_public_desc', $opt['update_participants_public_form']['lbl_description']);?>
				<div class="fld" >

					<?php echo form_input($opt['update_participants_public_form']['project_participants_public_desc']);?>
					<div class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Permissions:', 'project_participants_public_permissions', $opt['update_participants_public_form']['lbl_permissions']);?>
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
				<br>
				
				<?php echo form_submit('submit', 'Update','class = "light_green btn_lml"');?>
				
				<?php echo form_close();?>			
<?php 
			}
	}
}



if($loadtype == 'project_regulatory')
{
	if(count($regulatory_data) > 0)
	{

			foreach($regulatory_data as $key=>$val) 
			{
			?>
			<div class="contenttitle2">
		            <h3>Edit Regulatory</h3>
		    </div>

			<?php echo form_open('projects/update_regulatory/'.$slug,array('id'=>'update_regulatory_form_'.$val["id"],'name'=>'update_regulatory_form_'.$val["id"],'method'=>'post','class'=>'ajax_add_form'));?>
			
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
				
				<?php echo form_label('File:', 'project_regulatory_filename', $opt['update_regulatory_form']['lbl_filename']);?>
				<div class="fld" >
	
					<?php echo form_upload($opt['update_regulatory_form']['project_regulatory_filename']);?>
					<div class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Description:', 'project_regulatory_desc', $opt['update_regulatory_form']['lbl_description']);?>
				<div class="fld" >
	
					<?php echo form_input($opt['update_regulatory_form']['project_regulatory_desc']);?>
					<div class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Permission:', 'project_regulatory_permission', $opt['update_regulatory_form']['lbl_permission']);?>
				<div class="fld" >
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
													
				<?php echo form_submit('submit', 'Update','class = "light_green btn_lml"');?>
				
				<?php echo form_close();?>
				<?php 
			}
	}
}


if($loadtype == 'project_critical_participants')
{
	if(count($critical_participants_data) > 0)
	{

			foreach($critical_participants_data as $key=>$val) 
			{
			?>
			<div class="contenttitle2">
	           <h3>Edit Critical Participant</h3>
		    </div>

			<?php echo form_open('projects/update_critical_participants/'.$slug,array('id'=>'update_critical_participants_form_'.$val["id"],'name'=>'update_critical_participants_form_'.$val["id"],'method'=>'post','class'=>'ajax_add_form'));?>
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

				<?php echo form_label('Name:', '', $opt['update_critical_participants_form']['lbl_name']);?>
				<div class="fld">
					<?php echo form_input($opt['update_critical_participants_form']['project_critical_participants_name']);?>
					<div id="err_project_critical_participants_name" class="errormsg"></div>
				</div>
				<br>

				<?php echo form_label('Role:', '', $opt['update_critical_participants_form']['lbl_role']);?>
				<div class="fld">
					<?php echo form_input($opt['update_critical_participants_form']['project_critical_participants_role']);?>
					<div id="err_project_critical_participants_role" class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Description:', '', $opt['update_critical_participants_form']['lbl_desc']);?>
				<div class="fld">
					<?php echo form_input($opt['update_critical_participants_form']['project_critical_participants_desc']);?>
					<div id="err_project_critical_participants_desc" class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Permissions:', '', $opt['update_critical_participants_form']['lbl_permissions']);?>
				<div class="fld">
				<?php
					$critical_participants_permission_attr = "id='project_critical_participants_permission'";
					$critical_participants_permission_options = array(
						"All"	=> "All",
						"Some"	=> "Some",
						"Other"	=> "Other"
					);
					echo form_dropdown("project_critical_participants_permission",$critical_participants_permission_options,$val["permission"],$critical_participants_permission_attr);
				?>
				</div>
				<br>

				<?php echo form_submit('submit', 'Update','class = "light_green btn_lml"');?>

				<?php echo form_close(); ?>

			<?php 
			}
	}
}
if($loadtype == 'project_roi')
{
	if(count($roi_data) > 0)
	{

			foreach($roi_data as $key=>$val) 
			{
			?>
			<div class="contenttitle2">
	            <h3>Edit ROI</h3>
		    </div>

				<?php echo form_open('projects/update_roi/'.$slug,array('id'=>'update_roi_form_'.$val["id"],'name'=>'update_roi_form_'.$val["id"],'method'=>'post','class'=>'ajax_add_form'));?>
				
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
					
					
					<?php echo form_label('Name:', '', $opt['update_roi_form']['lbl_name']);?>
					<div class="fld">
						<?php echo form_input($opt['update_roi_form']['project_roi_name']);?>
						<div id="err_project_roi_name" class="errormsg"></div>
					</div>
					<br>

					<?php echo form_label('Percent:', '', $opt['update_roi_form']['lbl_percent']);?>
					<div class="fld">
						<?php echo form_input($opt['update_roi_form']['project_roi_percent']);?>
						<div id="err_project_roi_percent" class="errormsg"></div>
					</div>
					<br>
					
					<?php echo form_label('Type:', '', $opt['update_roi_form']['lbl_type']);?>
					<div class="fld">
						<?php echo form_input($opt['update_roi_form']['project_roi_type']);?>
						<div id="err_project_roi_type" class="errormsg"></div>
					</div>
					<br>
					
					<?php echo form_label('Approach:', '', $opt['update_roi_form']['lbl_approach']);?>
					<div class="fld">
						<?php echo form_input($opt['update_roi_form']['project_roi_approach']);?>
						<div id="err_project_roi_approach" class="errormsg"></div>
					</div>
					<br>
					
					<?php echo form_label('Key Study:', '', $opt['update_roi_form']['lbl_key_study']);?>
					<div class="fld">
						<?php echo form_upload($opt['update_roi_form']['project_roi_keystudy']);?>
						<div id="err_project_roi_keystudy" class="errormsg"></div>
					</div>
					<br>


					<?php echo form_label('Permissions:', '', $opt['update_roi_form']['lbl_permissions']);?>
					<div class="fld">
					<?php
						$roi_permission_attr = "id='project_roi_permission'";
						$roi_permission_options = array(
							"All"	=> "All",
							"Some"	=> "Some",
							"Other"	=> "Other"
						);
						echo form_dropdown("project_roi_permission",$roi_permission_options,$val["permission"],$roi_permission_attr);
					?>
					</div>
					<br>

					<?php echo form_submit('submit', 'Update','class = "light_green btn_lml"');?>
					
					<?php echo form_close();?>
			<?php 
			}
	}
}
if($loadtype == 'project_fund_sources')
{
	if(count($fund_sources_data) > 0)
	{

			foreach($fund_sources_data as $key=>$val) 
			{
			?>
				<div class="contenttitle2">
		           <h3>Edit Fund Sources</h3>
			    </div>

					<?php echo form_open('projects/update_fund_sources/'.$slug,array('id'=>'update_fund_sources_form_'.$val["id"],'name'=>'update_fund_sources_form_'.$val["id"],'method'=>'post','class'=>'ajax_add_form'));?>
					
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
					
					<?php echo form_label('Name:', '', $opt['update_fund_sources_form']['lbl_name']);?>
					<div class="fld">
						<?php echo form_input($opt['update_fund_sources_form']['project_fund_sources_name']);?>
						<div id="err_project_fund_sources_name" class="errormsg"></div>
					</div>
					<br>

					<?php echo form_label('Role:', '', $opt['update_fund_sources_form']['lbl_role']);?>
					<div class="fld">
						<?php echo form_input($opt['update_fund_sources_form']['project_fund_sources_role']);?>
						<div id="err_project_fund_sources_role" class="errormsg"></div>
					</div>
					<br>
					
					<?php echo form_label('Amount:', '', $opt['update_fund_sources_form']['lbl_amount']);?>
					<div class="fld">
						<?php echo form_input($opt['update_fund_sources_form']['project_fund_sources_amount']);?>
						<div id="err_project_fund_sources_amount" class="errormsg"></div>
					</div>
					<br>
					
					<?php echo form_label('Description:', '', $opt['update_fund_sources_form']['lbl_description']);?>
					<div class="fld">
						<?php echo form_input($opt['update_fund_sources_form']['project_fund_sources_desc']);?>
						<div id="err_project_fund_sources_desc" class="errormsg"></div>
					</div>
					<br>

					<?php echo form_label('Permissions:', '', $opt['update_fund_sources_form']['lbl_permissions']);?>
					<div class="fld">
					<?php
						$fund_sources_permission_attr = "id='project_fund_sources_permission'";
						$fund_sources_permission_options = array(
							"All"	=> "All",
							"Some"	=> "Some",
							"Other"	=> "Other"
						);
						echo form_dropdown("project_fund_sources_permission",$fund_sources_permission_options,$val["permissions"],$fund_sources_permission_attr);
					?>
					</div>
					<br>
					
					<?php echo form_submit('submit', 'Update','class = "light_green btn_lml"');?>
					
					<?php echo form_close();?>
			<?php 
			}
	}
}


if($loadtype == 'project_studies')
{
	if(count($studies_data) > 0)
	{

			foreach($studies_data as $key=>$val) 
			{
			?>
			<div class="contenttitle2">
	            <h3>Edit Study</h3>
		    </div>

				<?php echo form_open('projects/update_studies/'.$slug,array('id'=>'update_project_studies_form_'.$val["id"],'name'=>'update_project_studies_form_'.$val['id'],'method'=>'post','class'=>'ajax_add_form'));?>
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
													'value'		=> $val["title"]
													),
											'lbl_std_attachment' => array(
													'class' => 'left_label'
													),
											'project_studies_attachment'	=> array(
													'name' 		=> 'project_studies_attachment',
													'id' 		=> 'project_studies_attachment',
													'value'		=> $val["title"]
													),
											'lbl_std_permissions' => array(
											'class' => 'left_label'
											)
									);
						?>

					
					<?php echo form_hidden("hdn_project_studies_id",$val["id"]); ?>
					<?php echo form_hidden("project_studies_attachmen_hidden",$val["attachment"]); ?>

					<?php echo form_label('Title:', 'project_studies_title', $opt['update_project_studies_form']['lbl_std_title']);?>
					<div class="fld" >

						<?php echo form_input($opt['update_project_studies_form']['project_studies_title']);?>
						<div id="err_project_studies_title" class="errormsg"></div>
					</div>
					<br>
					
					<?php echo form_label('Description:', 'project_studies_desc', $opt['update_project_studies_form']['lbl_std_description']);?>
					<div class="fld" >

						<?php echo form_input($opt['update_project_studies_form']['project_studies_desc']);?>
						<div id="err_project_studies_description" class="errormsg"></div>
					</div>
					<br>
					
					<?php echo form_label('Attachment:', 'project_studies_attachment', $opt['update_project_studies_form']['lbl_std_attachment']);?>
					<div class="fld" >

						<?php echo form_upload($opt['update_project_studies_form']['project_studies_attachment']);?>
						<div id="err_project_studies_attachment" class="errormsg"></div>
					</div>
					<br>
					
					<?php echo form_label('Permissions:', 'project_studies_permissions', $opt['update_project_studies_form']['lbl_std_permissions']);?>
					<div class="fld">
					<?php
						$project_studies_attr = 'id="project_studies_permissions"';
						$project_studies_options = array(
							'All'		=> 'All',
							'Some' 		=> 'Some',
							'Other' 	=> 'Other'
						);
						echo form_dropdown('project_studies_permissions', $project_studies_options,$val["permission"],$project_studies_attr);
					?>
					</div>
					<br>

					<?php echo form_submit('submit', 'Update','class = "light_green btn_lbl"');?>
					
					<?php echo form_close();?>
	
			<?php }
	}
}


if($loadtype == 'project_environment')
{
	if(count($environment_data) > 0)
	{

			foreach($environment_data as $key=>$val) 
			{
			?>
			<div class="contenttitle2">
	            <h3>Edit Environment</h3>
		    </div>

				<?php echo form_open('projects/update_environment/'.$slug,array('id'=>'update_environment_form_'.$val["id"],'name'=>'update_environment_form_'.$val["id"],'method'=>'post','class'=>'ajax_add_form'));?>
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

						
						<?php echo form_label('Title:', 'project_environment_title', $opt['update_environment_form']['lbl_env_title']);?>
						<div class="fld" >

							<?php echo form_input($opt['update_environment_form']['project_environment_title']);?>
							<div id="err_project_environment_title" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label('Description:', 'project_environment_desc', $opt['update_environment_form']['lbl_env_description']);?>
						<div class="fld" >

							<?php echo form_input($opt['update_environment_form']['project_environment_desc']);?>
							<div id="err_project_environment_description" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label('Attachment:', 'project_environment_attachment', $opt['update_environment_form']['lbl_env_attachment']);?>
						<div class="fld" >

							<?php echo form_upload($opt['update_environment_form']['project_environment_attachment']);?>
							<div id="err_project_environment_attachment" class="errormsg"></div>
						</div>
						<br>
						
						<?php echo form_label('Permissions:', 'project_environment_permissions', $opt['update_environment_form']['lbl_env_permissions']);?>
						<div class="fld"><?php
							$project_environment_attr = 'id="project_environment_permissions"';
							$project_environment_options = array(
								'All'		=> 'All',
								'Some' 		=> 'Some',
								'Other' 	=> 'Other'
							);
							echo form_dropdown('project_environment_permissions', $project_environment_options,$val['permission'],$project_environment_attr);
						?>
						</div>
						<br>

						<?php echo form_submit('submit', 'Add new','class = "light_green btn_lbl"');?>
						
						<?php echo form_close();?>
						
			<?php }
	}
}

if($loadtype == 'project_design_issue')
{
	if(count($design_issue_data) > 0)
	{

			foreach($design_issue_data as $key=>$val) 
			{
			?>		
			<div class="contenttitle2">
	            <h3>Edit Design Issue</h3>
	    	</div>

				<?php echo form_open('projects/update_design_issue/'.$slug,array('id'=>'update_design_issues_form','name'=>'update_design_issues_form','method'=>'post','class'=>'ajax_add_form'));?>
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
				<?php echo form_hidden("project_design_issues_attachmen_hidden",$val["attachment"]); ?>

				<?php echo form_label('Title:', 'project_design_issues_title', $opt['update_design_issues_form']['lbl_title']);?>
				<div class="fld" >

					<?php echo form_input($opt['update_design_issues_form']['project_design_issues_title']);?>
					<div id="err_project_design_issues_title" class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Description:', 'project_design_issues_desc', $opt['update_design_issues_form']['lbl_description']);?>
				<div class="fld" >

					<?php echo form_input($opt['update_design_issues_form']['project_design_issues_desc']);?>
					<div id="err_project_design_issues_description" class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Attachment:', 'project_design_issues_attachment', $opt['update_design_issues_form']['lbl_attachment']);?>
				<div class="fld" >

					<?php echo form_upload($opt['update_design_issues_form']['project_design_issues_attachment']);?>
					<div id="err_project_design_issues_attachment" class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Permissions:', 'project_design_issues_permissions', $opt['update_design_issues_form']['lbl_permissions']);?>
				<div class="fld">
				<?php
					$design_issue_attr = 'id="project_design_issues_permissions"';
					$design_issue_options = array(
						'All'		=> 'All',
						'Some' 		=> 'Some',
						'Other' 	=> 'Other'
					);
					echo form_dropdown('project_design_issues_permissions', $design_issue_options,$val["permission"],$design_issue_attr);
				?>
				</div>
				<br>

				<?php echo form_submit('submit', 'Update','class = "light_green btn_lbl"');?>
				
				<?php echo form_close();?>
			<?php }
	}
}

if($loadtype == 'project_map_point')
{
	if(count($map_point_data) > 0)
	{
		foreach($map_point_data as $key=>$val) 
		{
		?>
		<div class="contenttitle2">
           <h3>Edit Map Point</h3>
	    </div>

				<?php echo form_open('projects/update_map_point/'.$slug,array('id'=>'update_map_points_form_'.$val["id"],'name'=>'update_map_points_form_'.$val["id"],'method'=>'post','class'=>'ajax_add_form'));?>
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


					
					<?php echo form_label('Name:', 'project_map_points_mapname', $opt['update_map_points_form']['lbl_mapname']);?>
					<div class="fld" >

						<?php echo form_input($opt['update_map_points_form']['project_map_points_mapname']);?>
						<div id="err_project_map_points_mapname" class="errormsg"></div>
					</div>
					<br>

					<?php echo form_label('Latitude:', 'project_map_points_latitude', $opt['update_map_points_form']['lbl_latitude']);?>
					<div class="fld" >

						<?php echo form_input($opt['update_map_points_form']['project_map_points_latitude']);?>
						<div id="err_project_map_points_latitude" class="errormsg"></div>
					</div>
					<br>

					<?php echo form_label('Longitude:', 'project_map_points_longitude', $opt['update_map_points_form']['lbl_longitude']);?>
					<div class="fld" >

						<?php echo form_input($opt['update_map_points_form']['project_map_points_longitude']);?>
						<div id="err_project_map_points_longitude" class="errormsg"></div>
					</div>
					<br>
					
					<?php echo form_hidden("hdn_project_map_points_id",$val["id"]); ?>


					
					<?php echo form_submit('submit', 'update','class = "light_green btn_lml"');?>
				
					<?php echo form_close();?>
			<?php }
	}
}

						
						
						


if($loadtype == 'project_engineering')
{
	if(count($engineering_data) > 0)
	{
		foreach($engineering_data as $key=>$val) 
		{
		?>
		<div class="contenttitle2">
	       <h3>Edit Engineering</h3>
	    </div>

			<?php echo form_open('projects/update_engineering/'.$slug,array('id'=>'update_engineering_form_'.$val["id"],'name'=>'update_engineering_form_'.$val["id"],'method'=>'post','class'=>'ajax_add_form'));?>

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
				
				<?php echo form_label('Company:', 'project_engineering_company', $opt['update_engineering_form']['lbl_company']);?>
				<div class="fld" >

					<?php echo form_input($opt['update_engineering_form']['project_engineering_company']);?>
					<div id="err_project_engineering_company" class="errormsg"></div>
				</div>
				<br>

				<?php echo form_label('Role:', 'project_engineering_role', $opt['update_engineering_form']['lbl_role']);?>
				<div class="fld" >

					<?php echo form_input($opt['update_engineering_form']['project_engineering_role']);?>
					<div id="err_project_engineering_role" class="errormsg"></div>
				</div>
				<br>

				<?php echo form_label('Contact Name:', 'project_engineering_cname', $opt['update_engineering_form']['lbl_cname']);?>
				<div class="fld" >

					<?php echo form_input($opt['update_engineering_form']['project_engineering_cname']);?>
					<div id="err_project_engineering_cname" class="errormsg"></div>
				</div>
				<br>

				<?php echo form_label('Challenges:', 'project_engineering_challenges', $opt['update_engineering_form']['lbl_challenges']);?>
				<div class="fld" >

					<?php echo form_input($opt['update_engineering_form']['project_engineering_challenges']);?>
					<div id="err_project_engineering_challenges" class="errormsg"></div>
				</div>
				<br>

				<?php echo form_label('Innovations:', 'project_engineering_innovations', $opt['update_engineering_form']['lbl_innovations']);?>
				<div class="fld" >

					<?php echo form_input($opt['update_engineering_form']['project_engineering_innovations']);?>
					<div id="err_project_engineering_innovations" class="errormsg"></div>
				</div>
				<br>

				<?php echo form_label('Schedule:', 'project_engineering_schedule', $opt['update_engineering_form']['lbl_schedule']);?>
				<div class="fld" >

					<?php echo form_upload($opt['update_engineering_form']['project_engineering_schedule']);?>
					<div id="err_project_engineering_schedule" class="errormsg"></div>
				</div>
				<br>
				
				<?php echo form_label('Permissions:', 'project_engineering_permissions', $opt['update_engineering_form']['lbl_permissions']);?>
				<div class="fld">
				<?php
					$permissions_attr = 'id="project_engineering_permissions"';
					$permissions_options = array(
						'All'		=> 'All',
						'Some' 		=> 'Some',
						'Other' 	=> 'Other'
					);
					echo form_dropdown('project_engineering_permissions', $permissions_options,$val['permission'],$permissions_attr);
				?>
				</div>
				<br>
				
				<?php echo form_submit('submit', 'Update','class = "light_green btn_lml"');?>
				
				<?php echo form_close();?>
			<?php }
	}
}

if($loadtype == 'project_organization')
{
	if(count($organization_data) > 0)
	{
		foreach($organization_data as $key=>$val) 
		{
?>
		<div class="contenttitle2">
	            <h3>Edit Organization</h3>
	    </div>

		<?php echo form_open_multipart("projects/update_organization/".$slug."",array("id"=>"update_organization_form_".$val["id"],"name"=>"update_organization_form_".$val["id"],"class"=>"ajax_add_form")); ?>
		<?php echo form_hidden("hdn_project_organizations_id",$val["id"]); ?>
		
		<?php echo form_label("Company Name:","",array("class"=>"left_label")); ?>
		<div class="fld">
			<?php echo form_input(array("name"=>"project_organizations_company","id"=>"project_organizations_company","value"=>$val["company"])); ?>
			<div class="errormsg" id="err_project_organizations_company_name"></div>
		</div>
		<br>
		
		
		<?php echo form_label("Role:","",array("class"=>"left_label")); ?>
		<div class="fld">
			<?php 
				$project_organizations_role_attr = "";
				$project_organizations_role_options = array(
					"Sponser"	=> "Sponsor",
					"Overseer"	=> "Overseer"
				);
				echo form_dropdown("project_organizations_role",$project_organizations_role_options,$val["role"],$project_organizations_role_attr);
			?>
			<div class="errormsg"></div>
		</div>
		
		<br>
		
		<?php echo form_label("Contact:","",array("class"=>"left_label")); ?>
		<div class="fld">
		<?php echo form_input(array("name"=>"project_organizations_contact","id"=>"project_organizations_contact","value"=>$val["contact"])); ?>
			<div class="errormsg"></div>
		</div>
		<br>
		
		<?php echo form_label("Email:","",array("class"=>"left_label")); ?>
		<div class="fld">
		<?php echo form_input(array("name"=>"project_organizations_email","id"=>"project_organizations_email","value"=>$val["email"])); ?>
			<div class="errormsg"></div>
		</div>
		<br>
		<?php echo form_submit(array("name"=>"Update","class"=>"light_green btn_lml","value"=>"Update")); ?>
		<?php echo form_close(); ?>
<?php
		}
	}
} 



if($loadtype == 'project_executives')
{
	if(count($executive_data) > 0)
	{
		foreach($executive_data as $key=>$val) 
		{
					
?>
			<div class="contenttitle2">
	            <h3>Edit Executive</h3>
	        </div>
				<?php echo form_open_multipart("projects/update_executive/".$slug."",array("id"=>"update_executive_form_".$val["id"], "name"=>"update_executive_form_".$val["id"],"class"=>"ajax_add_form")); ?>
				<?php echo form_hidden("hdn_project_executives_id",$val["id"]); ?>

				<?php echo form_label("Name:","",array("class"=>"left_label")); ?>
				<div class="fld">
					<?php echo form_input(array("name"=>"project_executives_name","value"=>$val["executivename"])); ?>
					<div class="errormsg" id="err_project_executives_name"></div>
				</div>
				<br>
				
				<?php echo form_label("Company:","",array("class"=>"left_label")); ?>
				<div class="fld">
					<?php echo form_input(array("name"=>"project_executives_company","value"=>$val["company"])); ?>
					<div class="errormsg" id="err_project_executives_name"></div>
				</div>
				<br>
				
				<?php echo form_label("Role:","",array("class"=>"left_label")); ?>
				<div class="fld">
					<?php 
						$project_executives_role_attr = "";
						$project_executives_role_options = array(
							"Role 1"	=> "Role 1",
							"Role 2"	=> "Role 2"
						);
						echo form_dropdown("project_executives_role",$project_executives_role_options,$val["role"],$project_executives_role_attr);
					?>
					<div class="errormsg" id="err_project_executives_name"></div>
				</div>
				<br>
				
				<?php echo form_label("Email:","",array("class"=>"left_label")); ?>
				<div class="fld">
					<?php echo form_input(array("name"=>"project_executives_email","id"=>"project_executives_email","value"=>$val["email"])); ?>
					<div class="errormsg" id="err_project_executives_name"></div>
				</div>
				<br>
				
				<?php echo form_submit(array("name"=>"Update","class"=>"light_green btn_lml","value"=>"Update")); ?>
				<?php echo form_close(); ?>
<?php
		}
	}
}

if($loadtype== "get_subsector_ddl" && $secid != '')
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
	$subsector_first			= array('class'=>'hardcode','text'=>'- Select A Sub-Sector -','value'=>'');
	$subsector_last				= array('class'=>'hardcode','value'=>'Other','text'=>'Other');
	echo form_custom_dropdown('member_sub_sector', $subsector_options,'',$project_sector_sub_attr,$subsector_opt,$subsector_first,$subsector_last);

}

if($loadtype== "get_subsector_proj_ddl" && $secid != '')
{

	$project_sector_sub_attr 		= 'id="project_sector_sub" class="project_sub"';
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
	$subsector_first			= array('class'=>'hardcode','text'=>'- Select A Sub-Sector -','value'=>'');
	$subsector_last				= array('class'=>'hardcode','value'=>'Other','text'=>'Other');
	echo form_custom_dropdown('project_sector_sub', $subsector_options,'',$project_sector_sub_attr,$subsector_opt,$subsector_first,$subsector_last);

}


if($loadtype=='sector_edit')
{

if(count($sector_data) > 0 )
	{
	foreach($sector_data as $key=>$sec)
	{
		
	$formlink = "myaccount/update_expert_sector/".$sec['uid']."/".$sec['id'];
		
		echo form_open($formlink,array('id'=>'expertise_sector_form_'.$sec["id"],'name'=>'expertise_sector_form_'.$sec["id"],'method'=>'post','class'=>'ajax_add_form')); ?>
			<?php 
				$opt['expertise_sector_form'] = array(
											'lbl_sector_main' => array(
												'class' => 'left_label'
												),
											'lbl_sector_sub' => array(
												'class' => 'left_label'
												)
											);
				?>
													
			<div class="contenttitle2">
	            <h3>Edit Sector</h3>
	        </div>
			<?php  echo form_hidden('hdn_expert_sector_from_id',$sec["id"]);?>
			
			<div>
				<?php echo form_label('Sector:', 'project_sector_main', $opt['expertise_sector_form']['lbl_sector_main']); ?>			
				<div class="fld">
					<?php
						$project_sector_main_attr	= 'id="project_sector_main" onchange="sectorbind('.$sec["uid"].');"';
						$sector_option = array();
						$sector_opt =array();
						foreach(sectors() as $key=>$value)
						{
							$sector_options[$value] = $value;
							$sector_opt[$value] 	= 'class="sector_main_'.$key.'"';
						}
						$sector_first			= array('class'=>'hardcode','text'=>'- Select A Sector -','value'=>'');
						$sector_last			= array();
						
						echo form_custom_dropdown('member_sector', $sector_options,$sec['sector'],$project_sector_main_attr,$sector_opt,$sector_first,$sector_last);
					?>
					<div class="fld errormsg" style="clear:both;"></div>
				</div>
			</div>
			<br/>
			<div>
				<?php echo form_label('Sub-Sector:', 'project_sector_sub', $opt['expertise_sector_form']['lbl_sector_sub']);?>
				<div class="fld" id="dynamicSubsector">
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
					$subsector_first			= array('class'=>'hardcode','text'=>'- Select A Sub-Sector -','value'=>'');
					$subsector_last				= array('class'=>'hardcode','value'=>'Other','text'=>'Other');
					echo form_custom_dropdown('member_sub_sector', $subsector_options,$sec['subsector'],$project_sector_sub_attr,$subsector_opt,$subsector_first,$subsector_last);
				?>
					<div class="fld errormsg" style="clear:both;"></div>
				</div>
				<div style="display:none">
		
					<?php echo form_label('Sub-Sector Other:', 'profile_sector_sub_other', $opt['general_info_form']['lbl_sub_sector_other']);?>						
					<div class="fld" style="width:625px;">
						<?php echo form_input($opt['general_info_form']['member_sub_sector_other']);?>
					</div>
				</div>
			</div>
			<div class="view clearfix">
					<?php echo form_submit('submit', 'Update Sector','class = "light_green no_margin_left" id="btn_add_sector"');?>
			</div>
			
	<?php echo form_close();
		}
	}

}

if($loadtype=='education_edit')
{
			
	if(count($education_data) > 0 )
	{
	foreach($education_data as $key=>$edu)
	{
		$formlink = "myaccount/update_education/".$edu["uid"]."/".$edu['educationid'];?>
		
		<?php echo form_open($formlink,array('id'=>'expertise_education_form','name'=>'expertise_education_form_'.$edu["educationid"],'method'=>'post','class'=>'ajax_add_form'));
			
			$opt['expertise_education_form'] = array(
						'lbl_university' => array(
								'class' => 'left_label'
								),
						'education_university'	=> array(
								'name' 		=> 'education_university',
								'id'		=> '',
								'value'		=> $edu['university'],
								'class'		=> 'longinput'
								),
						'lbl_major' => array(
								'class' => 'left_label'
								),
						'education_major'	=> array(
								'name' 		=> 'education_major',
								'id'		=> '',
								'value'		=> $edu['major'],
								'class'		=> 'longinput'
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
						
						<div class="contenttitle2">
	                        <h3>Edit Education</h3>
	                    </div>
                    	<div class="right right_top_visibility">
						
							<?php echo form_label('Visibility:', 'education_visibility');?>
							<?php						
								$education_visibility_attr = 'id="education_visibility"';
								$education_visibility_options = array('all'=>'All','some'=>'Some','other'=>'Other');
								echo form_dropdown('education_visibility', $education_visibility_options,'',$education_visibility_attr);
							?>
						</div>
						
					</div>
						
			<div class="inner">
			
				<div style="margin-bottom:10px;">
					<?php echo form_label('University Name:', 'education_university', $opt['expertise_education_form']['lbl_university']);?>
					<div style="width:370px;" class="fld">
						<?php echo form_input($opt['expertise_education_form']['education_university']);?>
						<div id="err_education_university" class="errormsg"><?php echo form_error('education_university');?></div>
					</div>					
				</div>
				
				<div style="margin-bottom:10px;">
					<?php echo form_label('Degree:', 'education_degree', $opt['expertise_education_form']['lbl_degree']);?>
					<?php						
						$education_degree_attr = 'id="education_degree"';
						$education_degree_options = education_dropdown();
					?>
					
					<div style="width:370px;" class="fld">
						<?php	echo form_dropdown('education_degree', $education_degree_options,$edu['degree'],$education_degree_attr);?>
						<div id="err_education_degree" class="errormsg"><?php echo form_error('education_degree');?></div>
					</div>
				</div>
					
				<div style="margin-bottom:10px;">
					<?php echo form_label('Major:', 'education_major', $opt['expertise_education_form']['lbl_major']);?>
					<div style="width:370px;" class="fld">
						<?php echo form_input($opt['expertise_education_form']['education_major']);?>
						<div id="err_education_major" class="errormsg"><?php echo form_error('education_major');?></div>
					</div>

				</div>
				
				<div style="margin-bottom:10px;">
					<?php echo form_label('Years:', 'education_start_year', $opt['expertise_education_form']['lbl_startyear']);?>
					<?php						
							$education_start_year_attr = 'id="education_start_year"';
							$education_start_year_options = year_dropdown('- year -');
					?>
					<div style="width:370px;" class="fld">
					<?php echo form_dropdown('education_start_year', $education_start_year_options,$edu['startyear'],$education_start_year_attr);?>
					<?php echo form_label('to:', 'education_grad_year', $opt['expertise_education_form']['lbl_gradyear']);?>
					<?php						
							$education_grad_year_attr = 'id="education_grad_year"';
							$education_grad_year_options = year_dropdown('- year -');
							echo form_dropdown('education_grad_year', $education_grad_year_options,$edu['gradyear'],$education_grad_year_attr);
					?>
					</div>
				</div>
				
				<div>
					<?php echo form_submit('submit', 'Update Education','class = "light_green no_margin_left" id="update_education_submit"');?>
				</div>
				
			</div><!-- end .inner -->
						
			<?php echo form_close();?>
					
			<!-- .edu_listing -->
	<?php }
	}
} ?>
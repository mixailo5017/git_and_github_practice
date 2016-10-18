<div id="content" class="clearfix">
		<?php
			if($project["eststart"] != "1111-11-11" && $project["eststart"] != "" ) { $eststart = DateFormat($project["eststart"],DATEFORMAT,FALSE); } else { $eststart = ""; } 
			if($project["estcompletion"] != "1111-11-11" && $project["estcompletion"] != "") { $estcompletion = DateFormat($project["estcompletion"],DATEFORMAT,FALSE); } else { $estcompletion = ""; } 
			$opt["project_form"] = array(
					'title'	=> array(
	            		'id'	=> 'title_input',
			            'value' => $project["projectname"],
			            'name'	=> 'title_input'
			        ),
			        'lbl_photo_privacy' => array(
			        	'class' => 'above_label'
			        ),
			        'privacy_options' => array(
						'Private'	=> lang('Private'),
						'Specific'	=> lang('Specific'),
						'Similar Project Owners'	=> lang('SimilarProjectOwners')
					),
					'lbl_photo_description' => array(
						'class' => 'above_label'
					),
					'project_photo' => array(
						'name' 	=> 'project_photo',
						'id'	=> 'project_photo',
					),
					'photo_submit' => array(
						'name'	=> 'photo_submit',
						'value' => lang('Upload'),
						'class'	=> 'photo_submit light_green',
						'id'    => 'btn_upload_project_image'
					),
					'lbl_project_meta_permissions' => array(
						'class'	=> 'above_label'
					),
					'lbl_project_overview' => array(
						'class'	=> 'left_label'
					),
					'project_overview' => array(
						'name'	=> 'project_overview',
						'style'	=> 'width:345px;',
						'id'	=>'project_overview',
						'value'	=> $project["description"]
					),
					'lbl_project_keywords' => array(
						'class'	=> 'left_label'
					),
					'project_keywords' => array(
						'id'	=> 'project_keywords',
						'name'	=> 'project_keywords',
						'value'	=> $project["keywords"]
					),
					'lbl_project_country' => array(
						'class'	=> 'left_label'
					),
					'lbl_project_location' => array(
						'class'	=> 'left_label'
					),
					'project_location' => array(
						'id'	=> 'project_location',
						'name'	=> 'project_location',
						'value'	=> $project["location"]
					),
					'lbl_project_sector_main' => array(
						'class'	=> 'left_label'
					),
					'lbl_project_sector_sub' => array(
						'class'	=> 'left_label'
					),
					'lbl_project_sector_sub_other' => array(
						'class'	=> 'left_label'	
					),
					'project_sector_sub_other' => array(
						'id'	=> 'project_sector_sub_other',
						'name'	=> 'project_sector_sub_other',
						'disabled' => 'disabled',
						'value'	=> $project["subsector_other"]
					),
					'lbl_project_budget_max' => array(
						'class'	=> 'left_label'	
					),
					'project_budget_max' => array(
						'id'	=> 'project_budget_max',
						'name'	=> 'project_budget_max',
//						'value'	=> CURRENCY.$project["totalbudget"]
						'value'	=> $project["totalbudget"]
					),
					'lbl_project_financial' => array(
						'class'	=> 'left_label'
					),
					'lbl_project_fs_other' => array(
						'class'	=> 'left_label'	
					),
					'project_fs_other' => array(
						'id'	=> 'project_fs_other',
						'name'	=> 'project_fs_other',
						'disabled' => 'disabled',
						'value'	=> $project["financialstructure_other"]
					),
					'lbl_stage_date' => array(
						'class'	=> 'left_label'	
					),
					'lbl_stage_budget' => array(
						'class'	=> 'left_label'	
					),
					'lbl_stage_comments' => array(
						'class'	=> 'left_label'	
					),
					'lbl_project_eststart' => array(
						'class' => 'left_label'
					),
					'project_eststart' => array(
						'id'	=> 'project_estsrart_picker',
						'name'	=> 'project_estsrart',
						'value'	=> $eststart,
						'class' => 'sm_left datepicker_month_year',
						'style' => 'width:120px',
                        'placeholder' => lang('mdY')
					),
					'lbl_project_estcompletion' => array(
						'class' => 'left_label'
					),
					'project_estcompletion' => array(
						'id'	=> 'project_estcompletion_picker',
						'name'	=> 'project_estcompletion',
						'value'	=> $estcompletion,
						'class' => 'sm_left datepicker_month_year',
						'style' => 'width:120px',
                        'placeholder' => lang('mdY')
					),
					'lbl_project_developer' => array(
						'class'	=> 'left_label'	
					),
					'project_developer' => array(
						'id'	=> 'project_developer',
						'name'	=> 'project_developer',
						'value'	=> $project["developer"]
					),
					'lbl_project_sponsor' => array(
						'class'	=> 'left_label'	
					),
					'project_sponsor' => array(
						'id'	=> 'project_sponsor',
						'name'	=> 'project_sponsor',
						'value'	=> $project["sponsor"]
					)
				 );
		?>
		<div id="col4">
			<?php
				$leftnavlist = array(
					'<a href="/projects/edit/'.$slug.'">'.lang("ProjectInformation").'</a>',
					'<a href="/projects/edit_fundamentals/'.$slug.'">'.lang("Fundamentals").'</a>',
					'<a href="/projects/edit_financial/'.$slug.'">'.lang("Financial").'</a>',
					'<a href="/projects/edit_regulatory/'.$slug.'">'.lang("Regulatory").'</a>',
					'<a href="/projects/edit_participants/'.$slug.'">'.lang("Participants").'</a>',
					'<a href="/projects/edit_procurement/'.$slug.'">'.lang("Procurement").'</a>',
					'<a href="/projects/edit_files/'.$slug.'">'.lang("Files").'</a>'
				);
				
				/*
				// For AJAX dynamic Left Tabs
				$leftnavlist = array(
					'<a onclick="tabload(\'/projects/edit/'.$slug.'\');" href="javascript:void(0);">Project Information</a>',
					'<a onclick="tabload(\'/projects/load_tab/'.$slug.'\');" href="javascript:void(0);">Fundamentals</a>',
					'<a onclick="tabload(\'/projects/edit_financial/'.$slug.'\');" href="javascript:void(0);">Financial</a>',
					'<a onclick="tabload(\'/projects/edit_regulatory/'.$slug.'\');" href="javascript:void(0);">Regulatory</a>',
					'<a onclick="tabload(\'/projects/edit_participants/'.$slug.'\');" href="javascript:void(0);">Participants</a>',
					'<a onclick="tabload(\'/projects/edit_procurement/'.$slug.'\');" href="javascript:void(0);">Procurement</a>',
					'<a onclick="tabload(\'/projects/edit_files/'.$slug.'\');" href="javascript:void(0);">Files</a>'
				);*/

				$leftnavattrib = array(
					'id' => 'profile_nav'
				);
				$listattributes = array(
					'class="here"'
				);
				echo ul_custom($leftnavlist,$leftnavattrib,$listattributes);
			?>
		</div><!-- end #col4 -->

		<div id="col5">
			<?php echo form_open_multipart("projects/updatename/".$slug."",array("id"=>"project_name_form","class"=>"ajax_form")); ?>
			<?php echo heading(lang("ProjectName").": ".form_input($opt["project_form"]["title"])."<label class='errormsg' id='err_title_input'></label>",1,"class='col_top gradient'"); ?>
			<?php echo form_close(); ?>
		<div class="profile_links">

			<div id="form_submit">
				<a href="/projects/<?php echo $slug ?>" target="_blank"><?php echo lang('ViewMyProject');?></a>
				<a href="#" id="update_project" class="light_green"><?php echo lang('UpdateProject');?></a>
			</div>
		
		</div>
		<div id="tabContainer">
			<div id="profile_tabs">
			
				<?php
					$tablist = array(
						'<a href="#tabs-1">'.lang("General").'</a>',
						// '<a href="#tabs-2">'.lang("ProjectExecutives").'</a>',
						// '<a href="#tabs-3">'.lang("ProjectOrganizations").'</a>',
						//'<a href="#tabs-4">'.lang("CG/LA Assessment").'</a>'
						'<a href="#tabs-5">'.lang('Maps').'</a>'
					);
					
					echo ul($tablist);
				?>
		
				<div id="tabs-1" class="col5_tab">

					<div style="display: flex; flex-direction: column;"><?php /* Put contents of this tab into a flexbox so that content from two separate forms can intermingle on the screen but not in the HTML */ ?>
					
						<div class="clearfix" style="order: 2">
							
							<?php echo form_label(lang('ProjectPhoto') . ':', 'projectphoto', array('class' => 'left_label')) ?>
							
							<?php echo form_open_multipart("projects/upload_projectphoto/".$slug."",array("id"=>"project_form_upload","name"=>"project_form_upload","class"=>"ajax_form")); ?>
							
							<div class="permissions_block" style="display:none">
								<div class="arrow"></div>
								<?php echo form_label(lang("Privacy").":","project_photos_permissions",$opt["project_form"]["lbl_photo_privacy"]); ?>
								<?php echo form_dropdown('project_photos_permissions', $opt["project_form"]["privacy_options"],$project["project_photos_permissions"]); ?>
							</div>

							<div class="clearfix">
									
									<?php echo form_label(lang("SelectanImage").':<a title="'.lang('PhotoExplanation').'" class="tooltip"></a>',"",$opt["project_form"]["lbl_photo_description"]); ?>
									<div class="fld">
										<?php echo form_upload($opt["project_form"]["project_photo"]); ?>
										<div class="errormsg" id="err_project_photo"><?php echo $photoerror; ?></div>
									<?php echo form_hidden("project_phot_hidden",$project["projectphoto"]); ?>
									<span class="note"><?php echo lang('Compatiblefiletypes');?>: JPEG, GIF, PNG</span>
										
									</div>

									<div style="clear: both">
										<div class="image_placeholder">
		                                    <img src="<?php echo project_image($project['projectphoto'], 150) ?>" alt="Project's photo" class="uploaded_img">
										</div>
										<?php echo form_submit($opt["project_form"]["photo_submit"]);  ?>
									</div>
							</div>
							<?php echo form_close();?>
						</div>
						<br>

						<div class="clearfix" style="order: 1">
							<?php echo form_open_multipart("projects/edit/".$slug."",array("id"=>"project_form_main","class"=>"project_form topupdate")); ?>
							<div class="hiddenFields">
								<?php echo form_hidden("return","projects/edit/".$slug); ?>
								<?php echo form_hidden_custom("select_stage",$project["stage"],FALSE,"id='select_stage'"); ?>
								<?php echo form_hidden_custom("title_input_hidden",$project["projectname"],FALSE,"id='title_input_hidden'"); ?>
							</div>
							<?php echo form_label(lang("Sponsor").':<a title="'.lang('SponsorExplanation').'" class="tooltip"></a>',"project_sponsor",$opt["project_form"]["lbl_project_sponsor"]); ?>
							<div class="fld">
								<?php echo form_input($opt["project_form"]["project_sponsor"]); ?>
								<div class="errormsg" id="err_project_sponsor"><?php echo form_error("project_sponsor"); ?></div>
							</div>
							<br>
							
							<?php echo form_label(lang("Developer").':<a title="'.lang('DeveloperExplanation').'" class="tooltip"></a>',"project_developer",$opt["project_form"]["lbl_project_developer"]); ?>
							<div class="fld">
								<?php echo form_input($opt["project_form"]["project_developer"]); ?>
								<div class="errormsg" id="err_project_developer"><?php echo form_error("project_developer"); ?></div>
							</div>
							<br>

	                    	<?php echo form_label(lang('Website') . ':', 'website', array('class' => 'left_label')) ?>
	                    	<div class="fld">
	                    	    <?php echo form_input('website', set_value('website', $project['website'])) ?>
	                    	    <div class="errormsg" id="err_website"><?php echo form_error('website') ?></div>
	                    	</div>
	                    	<br>
						</div>

						<div class="clearfix" style="order: 3">
							<div class="permissions_block" style="display:none">
								<div class="arrow"></div>
								<?php echo form_label(lang("Privacy").":","project_meta_permissions",$opt["project_form"]["lbl_project_meta_permissions"]); ?>
								<?php echo form_dropdown('project_meta_permissions', $opt["project_form"]["privacy_options"], $project["project_meta_permissions"]); ?>
							</div>

							<?php echo form_label(lang("Description").'*:<a title="'.lang('DetailExplanation').'" class="tooltip"></a>',"project_overview",$opt["project_form"]["lbl_project_overview"]); ?>
							<div class="fld">
								<?php echo form_textarea($opt["project_form"]["project_overview"]); ?>
								<div class="errormsg" id="err_project_overview"><?php echo form_error("project_overview"); ?></div>
								<?php /*
								<div id="count_char" style="position:absolute; margin-top:-55px; right:13px; width:200px; color:#535760"><?php echo lang('Limit200');?></div> */ ?>
							</div>
							<br>
							
							<?php echo form_label(lang("Keywords").'*:<a title="'.lang('SeparateMessage').'" class="tooltip"></a>',"project_keywords",$opt["project_form"]["lbl_project_keywords"]); ?>
							<div class="fld">
								<?php echo form_input($opt["project_form"]["project_keywords"]); ?>
								<div class="errormsg" id="err_project_keywords"><?php echo form_error("project_keywords"); ?></div>
							</div>
							<br>

							<?php echo form_label(lang("Country").'*:<a title="'.lang('CountryExplanation').'" class="tooltip"></a>',"project_country",$opt["project_form"]["lbl_project_country"]); ?>
							<div class="fld">
								<?php  
									$project_country_attr = 'id="project_country"';
									$project_country_options = country_dropdown();
									echo form_dropdown('project_country', $project_country_options,$project["country"],$project_country_attr);
								?>
								<div class="errormsg" id="err_project_country"><?php echo form_error("project_country"); ?></div>
							</div>
							<br>

							<?php echo form_label(lang("Location").'*:<a title="'.lang('LocationExplanation').'" class="tooltip"></a>',"project_location",$opt["project_form"]["lbl_project_location"]); ?>
							<div class="fld">
								<?php echo form_input($opt["project_form"]["project_location"]); ?>
								<div class="errormsg" id="err_project_location"><?php echo form_error("project_location"); ?></div>
							</div>
							<br>
							
							<?php echo form_label(lang("Sector")."*:","project_sector_main",$opt["project_form"]["lbl_project_sector_main"]); ?>
							<div class="fld">
								<?php 
									$project_sector_main_attr	= 'id="project_sector_main"';
									$sector_options = array();
									$sector_opt = array();
									foreach(sectors() as $key=>$value)
									{
										$sector_options[$value] = $value;
										$sector_opt[$value] 	= 'class="sector_main_'.$key.'"';
									}
									$sector_first			= array('class'=>'hardcode','text'=>lang('SelectASector'),'value'=>'');
									//$sector_last			= array();
									$sector_last			= array('class'=>'hardcode','text'=>'Other','value'=>'Other');
									
									echo form_custom_dropdown('project_sector_main', $sector_options,$project["sector"],$project_sector_main_attr,$sector_opt,$sector_first,$sector_last);
								?>
								<div class="errormsg" id="err_project_sector"><?php echo form_error("project_sector_main"); ?></div>
							</div>
							<br>
								
							<?php echo form_label(lang("Sub-Sector")."*:","project_sector_sub",$opt["project_form"]["lbl_project_sector_sub"]); ?>
							<div class="fld">
								<?php 
									$project_sector_sub_attr	= 'id="project_sector_sub"';
									$subsector_options = array();
									$subsector_opt = array();
									foreach(subsectors() as $key=>$value)
									{
										foreach($value as $key2=>$value2)
										{
											$subsector_options[$value2] 	= $value2;
											$subsector_opt[$value2] 		= 'class="project_sector_sub_'.$key.'"';
										}
									}
									$subsector_first			= array('class'=>'hardcode','text'=>lang('SelectASub-Sector'),'value'=>'');
									$subsector_last				= array('class'=>'hardcode other','value'=>'Other','text'=>'Other');
									echo form_custom_dropdown('project_sector_sub', $subsector_options,$project["subsector"],$project_sector_sub_attr,$subsector_opt,$subsector_first,$subsector_last);
								?>
								<div class="errormsg" id="err_project_subsector"><?php echo form_error("project_sector_sub"); ?></div>
							</div>
							<div  style="display:none">
	                        	<?php echo form_label(lang("Other").":","project_sector_sub_other",$opt["project_form"]["lbl_project_sector_sub_other"]); ?>
								<?php echo form_input($opt["project_form"]["project_sector_sub_other"]); ?>
								<div class="errormsg" id="err_project_subsector_other"></div>
	                            <span id="selected_sub_sector" style="display:none"><?php echo $project["subsector"];?></span>
	                        </div>
							<br>

							<?php echo form_label(lang('Est.Start') . ':', 'project_eststart', array('class' => 'left_label')) ?>
							<div class="fld">
								<?php echo form_input('project_eststart', set_value('project_eststart'), 'placeholder="' . lang('mY') . '" id="project_eststart_picker" class="sm_left datepicker_month_year" style="width:120px"') ?>
								<div class="errormsg" id="err_project_eststart"><?php echo form_error('project_eststart') ?></div>
							</div>
							<br>
							
							<?php echo form_label(lang('Est.Completion') . ':', 'project_estcompletion', array('class' => 'left_label')) ?>
							<div class="fld">
								<?php echo form_input('project_estcompletion', set_value('project_estcompletion'), 'placeholder="' . lang('mY') . '" id="project_estcompletion_picker" class="sm_left datepicker_month_year" style="width:120px"'); ?>
								<div class="errormsg" id="err_project_estcompletion"><?php echo form_error('project_estcompletion') ?></div>
							</div>
							<br>

							<?php echo form_label(lang("Stage")."*:","project_stage",array("class"=>"left_label")); ?>
							<div class="fld">
								<?php 
									$project_stage_attr = 'id="project_stage"';
									$project_stage_options = array(
										''			=> lang('SelectAStage'),
										'conceptual'	=> lang('conceptual'),
										'feasibility'	=> lang('feasibility'),
										'planning'		=> lang('planning'),
										'procurement'	=> lang('procurement'),
										'construction'	=> lang('construction'),
										'om' 			=> lang('om'),
									);
									$project_stage_options = array_map("ucfirst", $project_stage_options);
									echo form_dropdown('project_stage', $project_stage_options,$project["stage"],$project_stage_attr);
								?>
								<div class="errormsg" id="err_project_stage"></div>
							</div>
							<br>

							<?php echo form_label(lang("TotalBudget") . ' ($MM)'.'*:<a title="'.lang('ProjectEditBudgetHelpMessage').'" class="tooltip"></a>',"project_budget_max",$opt["project_form"]["lbl_project_budget_max"]); ?>
							<div class="fld">
								<?php echo form_input($opt["project_form"]["project_budget_max"]); ?>
								<div class="errormsg" id="err_project_budget_max"><?php echo form_error("project_budget_max"); ?></div>
							</div>
							<br>

							<?php echo form_label(lang("FinancialStructure").":","project_financial",$opt["project_form"]["lbl_project_financial"]); ?>	
							<div class="fld">
								<?php
									$project_financial_attr = 'id="project_financial"';
									$project_financial_options = array(
										''			=> lang('SelectOne'),
										'Public'	=> lang('Public'),
										'Private'	=> lang('Private'),
										'PPP'		=> lang('PPP'),
										'Concession'=> lang('Concession'),
										'Design–Build' => lang('Designb'),
										'Other'		=> lang('Other')
									);
									echo form_dropdown('project_financial', $project_financial_options,$project["financialstructure"],$project_financial_attr);
								?>
								<div class="errormsg" id="err_project_financial"><?php echo form_error("project_financial"); ?></div>
							</div>
							<br>
							
							<div style="display:none">
								<?php echo form_label(lang("Other").":","project_fs_other",$opt["project_form"]["lbl_project_fs_other"]); ?>
								<?php echo form_input($opt["project_form"]["project_fs_other"]); ?>
								<div class="errormsg" id="err_project_fs_other"></div>
							</div>


							<div id="stage_accordion" class="accordion" style="display:none">
								
								<?php
									/*$stagearr = array(
										array('name' => 'Conceptual','id' => 'conceptual'),
										array('name' => 'Feasibility','id' => 'feasibility'),
										array('name' => 'Planning','id' => 'planning'),
										array('name' => 'Construction','id' => 'construction'),	
										array('name' => 'Operation & Maintenance','id' => 'om')	
									);
									foreach($stagearr as $key=>$value)
									{
								?>
								<?php echo heading('<a href="#">'.$value["name"].'</a>',3); ?>
								<div>
									
									<div class="stage_status">
										<?php echo form_label("Status"); ?>	
										<?php
											$stage_status_attr = 'class="stage_status_select" onchange="changestage(this)"';
											$stage_status_options = array(
												'Closed'	=> 'Closed',
												'Open'		=> 'Open',
												'Complete'	=> 'Complete'
											);
											echo form_dropdown('ps_'.$value["id"].'_status', $stage_status_options,$project["".$value["id"]."_status"],$stage_status_attr);
											
										?>
									</div>

									<?php echo form_label("Date","",$opt["project_form"]["lbl_stage_date"]); ?>	
										
									<?php echo form_input(array("id"=>"ps_".$value["id"]."_date_picker","class"=>"sm_left datepicker_month_year","value"=>$project["".$value["id"]."_date_from"]!="0000-00-00 00:00:00"?DateFormat($project["".$value["id"]."_date_from"],DATEFORMAT):"")); ?>	
										<?php echo form_hidden_custom("ps_".$value["id"]."_date",DateFormat($project["".$value["id"]."_date_from"],DATEFORMAT),FALSE,"id='ps_".$value["id"]."_date'"); ?>	
									<span>to</span>
									<?php echo form_input(array("id"=>"ps_".$value["id"]."_date2_picker","class"=>"sm_left datepicker_month_year","value"=>$project["".$value["id"]."_date_to"]!="0000-00-00 00:00:00"?DateFormat($project["".$value["id"]."_date_to"],DATEFORMAT):"")); ?>	
										<?php echo form_hidden_custom("ps_".$value["id"]."_date2",DateFormat($project["".$value["id"]."_date_to"],DATEFORMAT),FALSE,"id='ps_".$value["id"]."_date2'"); ?>	
									<br>	

									<?php echo form_label("Budget","",$opt["project_form"]["lbl_stage_budget"]); ?>
									<?php echo form_input(array("id"=>"ps_".$value["id"]."_budget","class"=>"sm_left","value"=>$project["".$value["id"]."_budget_from"]!="0"?$project["".$value["id"]."_budget_from"]:"","name"=>"ps_".$value["id"]."_budget")); ?><span>to</span>
									<?php echo form_input(array("id"=>"ps_".$value["id"]."_budget2","class"=>"sm_left","value"=>$project["".$value["id"]."_budget_to"]!=""?$project["".$value["id"]."_budget_to"]:"","name"=>"ps_".$value["id"]."_budget2")); ?>
									<br>

									<?php echo form_label("Comments","",$opt["project_form"]["lbl_stage_comments"]); ?>	
									<?php echo form_textarea(array("name"=>"ps_".$value["id"]."_comments","id"=>"ps_".$value["id"]."_comments","cols"=>"30","rows"=>"10","value"=>$project["".$value["id"]."_comments"]));?>

								</div>
								<?php
									}*/
								?>		
							</div>
						
						</div>
						<br>

						<div class="clearfix" id="form_submit" style="float: middle; margin: auto; order: 4">
							<?php echo form_submit(array("name"=>"Update","class"=>"light_green btn_lml","value"=>lang("UpdateProject"))); ?>
						</div>

						<?php echo form_close(); ?>
					</div>
				</div>


				<div class="col5_tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-5" style="">
						<div class="map_box clearfix">
							<style type="text/css">
								#project-map{
									width: 745px;
									height: 456px;
								}
							</style>
			
							<div id="project-map" style="height: 456px; width: 745px;"></div>
			
							<div class="clearfix">
								<p class="left coord"><span class="geo"></span>
									<strong>Project location:</strong> 
									<span class="address">
										<?php
										if($project['location']!= ''){
										?>
										<?php
											echo $project['location'];
			
										} else {
											echo "N/A";
										}
										echo "</span>";
			
										if ($project['location']!= '') {
										?>
											<a class="edit_location toggleEdit">Edit location</a>
											<a class="save_location" style="display: none;">Save</a>
											<a class="cancel_location toggleEdit" style="display: none;">Cancel</a>
										<?php
										}
										?>
								</p>
							</div>
						</div>
			
			
				</div>				

		
				<div id="tabs-2" class="col5_tab project_form" style="display:none">
					<div class="clearfix matrix_dropdown project_executives">
						<ul id="load_executive_form">
							<?php
							
							foreach($project["executive"] as $key=>$val)
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

									<?php echo form_label(lang("Name").":","",array("class"=>"left_label")); ?>
									<div class="fld">
										<?php echo form_input(array("name"=>"project_executives_name","id"=>"project_executives_name","value"=>$val["executivename"])); ?>
										<div class="errormsg"></div>
									</div>
									<br>
									
									<?php echo form_label(lang("Company").":","",array("class"=>"left_label")); ?>
									<div class="fld">
										<?php echo form_input(array("name"=>"project_executives_company","id"=>"project_executives_company","value"=>$val["company"])); ?>
										<div class="errormsg"></div>
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
											<div class="errormsg" ></div>
										</div>
									</div>
									
									<?php echo form_submit(array("name"=>"Update","class"=>"light_green btn_lml","value"=>lang("Update"))); ?>
									<?php echo form_reset(array("class"=>"light_red btn_sml","value"=>lang("Close"))); ?>
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
									<?php //lable like=> + Add Executives?>
									<a id="addnewExecutive" class="edit project_row_add" href="javascript:void(0);" onclick="rowtoggle(this.id);"> + <?php echo lang('AddExecutives');?></a>

								</div>

								<div class="edit add_new">
									<?php echo form_open_multipart("projects/add_executive/".$slug."",array("id"=>"executive_form","class"=>"ajax_form")); ?>
									<?php echo form_hidden("project_executives_row_","0",FALSE,"class='project_new_row' disabled='disabled'"); ?>

									<?php echo form_label(lang("Name").":","",array("class"=>"left_label")); ?>
									<div class="fld">
										<?php echo form_input(array("name"=>"project_executives_name","id"=>"project_executives_name")); ?>
										<div class="errormsg" id="err_project_executives_name"></div>
									</div>
									<br>
									
									<?php echo form_label(lang("Company").":","",array("class"=>"left_label")); ?>
									<div class="fld">
										<?php echo form_input(array("name"=>"project_executives_company","id"=>"project_executives_company")); ?>
										<div class="errormsg" id="err_project_executives_company"></div>
									</div>
									<br>
									
									<?php echo form_label(lang("Role").":","",array("class"=>"left_label")); ?>
									<div class="fld">
										<?php 
											$project_executives_role_attr = "id='project_executives_role' onchange='project_executive_other(this)'";
											$project_executives_role_options = array(
												"Finance"		=> lang("Finance"),
												"Engineering"	=> lang("Engineering"),
												"Construction"	=> lang("Construction"),
												"Admin"			=> lang("Admin"),
												"Affairs"		=> lang("Affairs"),
												"Other"			=> lang("Other")
											);
	
											echo form_dropdown("project_executives_role",$project_executives_role_options,'',$project_executives_role_attr);
										?>
										<div class="errormsg" id="err_project_executives_role"></div>
									</div>
									<div style="display:none;clear:both;" class="role_other">
										<?php echo form_label(lang("Other").":","",array("class"=>"left_label")); ?>
										<?php echo form_input(array("name"=>"project_executives_role_other","id"=>"project_executives_role_other")); ?>
										<div class="errormsg" id="err_project_executives_role_other"></div>
									</div>
									<div style="clear:both;">
									<?php echo form_label(lang("Email").":","",array("class"=>"left_label")); ?>
									<div class="fld">
										<?php echo form_input(array("name"=>"project_executives_email","id"=>"project_executives_email")); ?>
										<div class="errormsg" id="err_project_executives_email"></div>
									</div>
									</div>	
									
									<?php echo form_submit(array("name"=>"Add New","class"=>"light_green btn_lml","value"=>lang("AddNew"))); ?>
									<?php echo form_close(); ?>

								</div>
								
							</li>
						</ul>

					</div>
					
					
				</div>

		
				<div id="tabs-3" class="col5_tab project_form" style="display:none">
				
				

			<div class="proj_organization">
			<?php 
			echo form_open_multipart("projects/update_orgExpert/".$project['pid'],array("id"=>"update_orgExpert",'class'=>'ajax_form clearfix')); ?>
			<?php
				$opt = array(
					'lbl_project_expAdv' => array(
		              	'class' => 'left_label'
	            	),
	            	'submit' => array(
			        	'name' => 'update_Organization',
			        	'value' => lang('UpdateOrganizationOwner'),
			        	'class' => 'light_green btn_lml'
			        ),
			        
				);
			?>
				<?php echo form_label(lang('Organization').':', 'project_expAdv',$opt['lbl_project_expAdv']);?>
				<div class="fld">
					<?php 
						$project_expAdv_attr = "id='project_expAdv'";
						$project_expAdv_options = get_all_expertAdverts();
						$project_expAdv_orgid	= ($proj_org['orgid'])?($proj_org['orgid']):'';
						echo form_dropdown("project_expAdv",$project_expAdv_options['organization'],$project_expAdv_orgid,$project_expAdv_attr);
					?>
					<div class="errormsg" id="err_project_expAdv"></div>
				</div>
				<?php if(isset($proj_org['status'])&& $proj_org['status']=='1')
				{ 
					echo form_label(lang('Approved'),'',array('class'=>'left_label','style'=>'color:Green;font-weight:bold;width:70px;'));
				}
				elseif(isset($proj_org['status'])&& $proj_org['status']=='0')
				{ 
					echo form_label(lang('Pending'),'',array('class'=>'left_label','style'=>'color:Blue;font-weight:bold;width:70px;'));
				}
				else
				{
					echo form_hidden("hdn_project_organizations_action",'Add');
				}
				?>
			<?php echo form_submit($opt["submit"]); ?>
			<?php echo form_close(); ?>
			</div>
			<br/>

				
					<div class="clearfix matrix_dropdown project_organizations">
						<ul id="load_organization_form">

							<?php
							foreach($project["organization"] as $key=>$val)
							{
							?>
							<li class="" id="row_id_<?php echo $val["id"]; ?>">
								
								<div class="view clearfix">
									
									<span class="left"><?php echo $val["role"]; ?></span>

									<span class="left middle">
										<strong><?php echo $val["company"]; ?></strong>
										<br>
										<?php echo $val["contact"].", ".$val["email"]; ?>
									</span>

									<a class="right delete" href="#projects/delete_organization"><?php echo lang('Delete');?></a>

									<a class="right edit" id="edit_organization_<?php echo $val["id"];?>" href="javascript:void(0);" onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

								</div>

								<div class="edit">
									<?php echo form_open_multipart("projects/update_organization/".$slug."",array("id"=>"update_organization_form","name"=>"update_organization_form_".$val["id"],"class"=>"ajax_form")); ?>
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
												"Sponsor"	=> lang("Sponsor"),
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
									<?php echo form_input(array("name"=>"project_organizations_email","id"=>"project_organizations_contact","value"=>$val["email"])); ?>
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
							?>
							</ul>
							
							<ul>
							<li>
								<div class="view">
									
									<a href="javascript:void(0);" id="addOrganization" onclick="rowtoggle(this.id);" class="edit project_row_add"><?php echo "+ ".lang('AddOrganizations');?></a>

								</div>

								<div class="edit add_new">
									<?php echo form_open_multipart("projects/add_organization/".$slug."",array("id"=>"organization_form","class"=>"ajax_form")); ?>
									<?php echo form_hidden("hdn_project_organizations_id","0",FALSE,"class='project_new_row' disabled='disabled'"); ?>

									<?php echo form_label(lang("CompanyName").":","",array("class"=>"left_label")); ?>
									
									<div class="fld">
										<?php echo form_input(array("name"=>"project_organizations_company","id"=>"project_organizations_company")); ?>
										<div class="errormsg"></div>
									</div>
									<br>
									
									<?php echo form_label(lang("Role").":","",array("class"=>"left_label")); ?>
									<div class="fld">
										<?php 
											$project_organizations_role_attr = "";
											$project_organizations_role_options = array(
												"Sponsor"	=> lang("Sponsor"),
												"Overseer"	=> lang("Overseer")
											);
											echo form_dropdown("project_organizations_role",$project_organizations_role_options,'',$project_organizations_role_attr);
										?>
										<div class="errormsg"></div>
									</div>
									<br>
									
									<?php echo form_label(lang("Contact").":","",array("class"=>"left_label")); ?>
									<div class="fld">
										<?php echo form_input(array("name"=>"project_organizations_contact","id"=>"project_organizations_contact")); ?>
										<div class="errormsg"></div>
									</div>
									<br>
									
									<?php echo form_label(lang("Email").":","",array("class"=>"left_label")); ?>
									<div class="fld">
										<?php echo form_input(array("name"=>"project_organizations_email","id"=>"project_organizations_email")); ?>
										<div class="errormsg"></div>
									</div>
									<br>
									
									<?php echo form_submit(array("name"=>"Add New","class"=>"light_green btn_lml","value"=>lang("AddNew"))); ?>
									<?php echo form_close(); ?>
								</div>

							</li>
						</ul>

					</div>

				</div>

			</div></div><!-- end #tabs -->

			<div aria-labelledby="ui-dialog-title-dialog-message" class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable ui-resizable" role="dialog" 		style="display: none; z-index: 1002; outline: 0px none; position: absolute; height: auto; width: 300px; top: 1050px; left: 558px;" tabindex="-1">
				<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
					<span id="ui-dialog-title-dialog-message" class="ui-dialog-title"><?php echo lang('Message');?></span>
					<a class="ui-dialog-titlebar-close ui-corner-all" href=javascript:void(0); role="button">
						<span class="ui-icon ui-icon-closethick"><?php echo lang('close');?></span>
					</a>
				</div>
				<div id="dialog-message" class="ui-dialog-content ui-widget-content" scrollleft="0" scrolltop="0" style="width: auto; min-height: 12.8px; height: auto		;">
					<?php echo lang('updatedMessage');?></div>
				<div class="ui-resizable-handle ui-resizable-n"></div>
				<div class="ui-resizable-handle ui-resizable-e"></div>
				<div class="ui-resizable-handle ui-resizable-s"></div>
				<div class="ui-resizable-handle ui-resizable-w"></div>
				<div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se ui-icon-grip-diagonal-se" style="z-index: 1001;"></div>
				<div class="ui-resizable-handle ui-resizable-sw" style="z-index: 1002;"></div>
				<div class="ui-resizable-handle ui-resizable-ne" style="z-index: 1003;"></div>
				<div class="ui-resizable-handle ui-resizable-nw" style="z-index: 1004;"></div>
				<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
					<div class="ui-dialog-buttonset">
						<button aria-disabled="false" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" type="button">
						<span class="ui-button-text"><?php echo lang('Ok');?></span></button>
					</div>
				</div>
			</div>

		</div><!-- end #col5 -->

	</div><!-- end #content -->

	<div id="dialog-message"></div>

<script src="/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/js/selectize.min.js"></script>
<link rel="stylesheet" type="text/css" href="/css/selectize.css" />
<script>
	var mapCoords = [<?php echo $project['lat'],',', $project['lng'];?>];
	var isAdmin = true;
	var slug = '<?php echo $slug; ?>';
	var map_geom = <?php echo json_encode($map_geom); ?>;

	$('#project_keywords').selectize({
		plugins: ['remove_button'],
    	delimiter: ',',
    	persist: false,
    	create: function(input) {
    	    return {
    	        value: input,
    	        text: input
    	    }
    	}
    });

</script>

<style>
.ui-datepicker-calendar {
    display: none;
}
.selectize-input {
    width: 363px;
}
</style>
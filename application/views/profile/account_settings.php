<div id="content" class="clearfix">

	<div id="col4">
		<ul id="profile_nav">
			<li class="here"><a href=javascript:void(0);><?php echo lang('ProfileInformation');?></a></li>
			<!-- ExpertAdverts Start-->
			<?php if($usertype == '8')
			{?>
				<li><a href="/profile/edit_seats"><?php echo lang('EditSeats');?></a></li>
				<li><a href="/profile/edit_case_studies"><?php echo lang('EditCaseStudies');?></a></li>
				<li><a href="javascript:void(0);"><?php echo lang('StorePurchaseHistory');?></a></li>
				<li><a href="javascript:void(0);"><?php echo lang('LicenseInformation');?></a></li>
			<?php
			}
			else
			{
			?>
			<li><a href="/profile/my_projects"><?php echo lang('MyProjects');?></a></li>
			<?php
			}
			?>

			<!-- ExpertAdverts End-->
			<li><a href="/profile/account_settings_email"><?php echo lang('EmailPassword');?></a></li>
		</ul>
	</div><!-- end #col4 -->
	
	<div id="col5">
		<h1 class="col_top gradient">
            <?php echo lang('ProfileInformation') ?>&nbsp;
            <?php if (! empty($pci)) { ?>
            (<span style="font-weight: lighter; font-size: smaller;"><span class="profile_edit_pci"><?php echo $pci['pci'] ?></span>% complete</span>)
            <?php } ?>
        </h1>
		<div class="profile_links">
			<div id="form_submit">
				<a href="/expertise/<?php echo $users['uid'];?>" class="light_gray"><?php echo lang('ViewMyProfile'); ?></a>
			</div>
		</div>
	
		<div id="profile_tabs">
			<ul>
				<li><a href="#tabs-1"><?php echo lang('General');?></a></li>
				<!-- ExpertAdverts Start-->
				<?php if($usertype != MEMBER_TYPE_EXPERT_ADVERT){?>
					<li><a href="#tabs-2"><?php echo lang('ExpertiseEducation');?></a></li>
				<?php } ?>
				<!-- ExpertAdverts End-->
				<li><a href="#project-involvement"><?php echo lang('ProjectInvolvement');?></a></li>
			</ul>
	
			<div id="tabs-1" class="col5_tab">
				<div class="clearfix" id="div_general_photo_form">
					<h4><?php echo lang('UserPhoto');?>:</h4>
					<div style="width:150px;">
						<?php $img_options = array('fit' => 'contain');
                        if ($users['membertype'] == MEMBER_TYPE_EXPERT_ADVERT) {
                            $src = company_image($users['userphoto'], 150, $img_options);
                        } else {
                            $src = expert_image($users['userphoto'], 150, $img_options);
                        } ?>
					</div>
                    <div class="div_resize_img150" style="min-height:150px">
                        <img src="<?php echo $src ?>" alt="<?php echo lang("ProfilePhoto") ?>" style="margin:0px" class="uploaded_img">
                    </div>

                    <div class="clearfix" style="width:595px;">
						<p class="no_margin_top"><?php echo lang('SelectImage');?> (5MB max):</p>
						<?php echo form_open_multipart('profile/upload_userphoto',array('id'=>'general_photo_form','name'=>'general_photo_form','method'=>'post','class'=>'ajax_form'));?>
						<?php $opt['general_photo_form'] = array('photo_filename' => array('name' => 'photo_filename','id' => 'photo_filename'));?>
						
						<div class='hiddenFields'>
							<?php echo form_hidden("RET",current_url()); ?>
						</div>
	
						<?php echo form_upload($opt['general_photo_form']['photo_filename']);?>
							<div id="err_photo_filename" class="errormsg"></div>
							<p class="comment"><?php echo lang('Compatiblefiletypes');?>: JPEG, GIF, PNG</p>
							<?php echo form_submit('ptofile_submit',lang('UploadProfileImage'),'class = "light_green no_margin_left"');?>
						<?php echo form_close();?>
					</div>
				</div>
	
				<div class="clearfix" id="video_form_div" style="display:none">
					<h4>User Video:</h4>
					<div style="width:150px;">
						<img class="left" src="/images/site/profile_image_placeholder.png" alt=<?php echo lang("placeholder");?>>
					</div>
	
					<div class="clearfix" style="width:595px;">
						<p class="no_margin_top"><?php echo lang('Embeddedvideofrom').':';?></p>
						<?php echo form_open('profile/upload_uservideo',array('id'=>'general_video_form','name'=>'general_video_form','method'=>'post','class'=>'ajax_form'));?>
							<?php $opt['general_video_form'] = array('member_video' => array('name' => 'member_video','id' => 'member_video','value'=>$users['uservideo'],'placeholder'=>'Write the url')); ?>
							<div>
								<?php echo form_input($opt['general_video_form']['member_video']); ?>
								<div id="err_member_video" class="errormsg"></div>
								<?php echo form_submit('submit', lang('UploadVideo'),'class = "light_green no_margin_left"');?>
							</div>
						<?php echo form_close();?>
					</div>
				</div>
	
				<div class="clearfix" id="div_general_info_form">
					
					<h4><?php echo lang('ProfessionalInformation');?>:</h4>
					
					<?php echo form_open('profile/update',array('id'=>'general_info_form','name'=>'_general_info_form','method'=>'post','class'=>'ajax_form'));
						
					$opt['general_info_form'] = array(
												'lbl_firstname' => array(
														'class' => 'left_label_p'
														),
												'member_first_name'	=> array(
														'name' 		=> 'member_first_name',
														'id' 		=> 'member_first_name',
														'value' 	=> $users["firstname"]
														),
												'lbl_lastname' => array(
														'class' => 'left_label_p'
														),
												'member_last_name'	=> array(
														'name' 		=> 'member_last_name',
														'id' 		=> 'member_last_name',
														'value' 	=> $users["lastname"]
														),
												'lbl_title' => array(
														'class' => 'left_label_p'
														),
												'member_title'	=> array(
														'name' 		=> 'member_title',
														'id' 		=> 'member_title',
														'value' 	=> $users["title"]
														),
												'lbl_email' => array(
														'class' => 'left_label_p'
														),
												'member_email'	=> array(
														'name' 		=> 'member_email',
														'id' 		=> 'member_email',
														'value' 	=> $users["email"],
														'maxlength'		=> '100'
														),
												'lbl_organization' => array(
														'class' => 'left_label_p'
														),
												'member_organization'	=> array(
														'name' 		=> 'member_organization',
														'id' 		=> 'member_organization',
														'value' 	=> $users["organization"]
														),
												'lbl_org_employees' => array(
														'class' 	=> 'left_label_p'
														),
												'lbl_org_annual_revenue' => array(
														'class' 	=> 'left_label_p'
														),
												'lbl_sector_main' 	=> array(
														'class' 	=> 'left_label_p'
														),
												'lbl_sector_sub' 	=> array(
														'class'		=> 'left_label_p'
														),
												'lbl_sub_sector_other'=> array(
														'class' 	=> 'left_label_p'
														),
												'member_sub_sector_other'=> array(
														'id'	 	=> 'profile_sector_sub_other',
														'name'		=> 'member_sub_sector_other',
														'value'		=> '',
														'disabled'	=> 'disabled'
														),
												'lbl_country'=> array(
														'class' 	=> 'left_label_p'
														),
												'lbl_address' => array(
														'class' 	=> 'left_label_p'
														),
												'member_address'	=> array(
														'name' 		=> 'member_address',
														'value' 	=> $users["address"]
														),
												'lbl_city' => array(
														'class' 	=> 'left_label_p'
														),
												'member_city'	=> array(
														'name' 		=> 'member_city',
														'value' 	=> $users["city"]
														),
												'lbl_state' => array(
														'class' 	=> 'left_label_p'
														),
												'member_state'	=> array(
														'name' 		=> 'member_state',
														'value' 	=> $users["state"]
														),
												'lbl_postal_code' => array(
														'class' 	=> 'left_label_p'
														),
												'member_postal_code'	=> array(
														'name' 		=> 'member_postal_code',
														'value' 	=> $users["postal_code"]
														),
												'lbl_phone' => array(
														'class' 	=> 'left_label_p'
														),
												'member_phone'	=> array(
														'id'			=> 'member_phone',
														'name' 			=> 'member_phone',
														'value' 		=> $users["vcontact"],
														'maxlength'		=> '25'
														),
												'lbl_mission' => array(
														'class' 	=> 'left_label_p'
														),
												'member_mission'	=> array(
														'name' 		=> 'member_mission',
														'value' 	=> $users["mission"],
                                                        'class'     => 'tinymce',
                                                        'data-width' => '600',
														'style'		=>'margin-bottom:0px;'
												)


						);
					 ?>
			<!-- ExpertAdverts Start-->
			<?php 
			echo form_hidden('hdn_member_usertype',$usertype);
			
			if($usertype != MEMBER_TYPE_EXPERT_ADVERT) { ?>
                <div>
                    <?php echo form_label(lang('FirstName').':', 'member_first_name', $opt['general_info_form']['lbl_firstname']);?>
                    <div class="fld w587">
                        <?php echo form_input($opt['general_info_form']['member_first_name']);?>
                        <div id="err_member_first_name" class="errormsg"></div>
                    </div>
                </div>

                <div>
                    <?php echo form_label(lang('LastName').':', 'member_last_name', $opt['general_info_form']['lbl_lastname']);?>
                    <div class="fld w587">
                        <?php echo form_input($opt['general_info_form']['member_last_name']);?>
                        <div id="err_member_last_name" class="errormsg"></div>
                    </div>
                </div>

                <div>
                    <?php echo form_label(lang('Title').':', 'member_title', $opt['general_info_form']['lbl_title']);?>
                    <div class="fld w587">
                        <?php echo form_input($opt['general_info_form']['member_title']);?>
                        <div id="err_member_title" class="errormsg"></div>
                    </div>
                </div>
                <div>
                    <?php echo form_label(lang('Organization').':', 'member_organization', $opt['general_info_form']['lbl_organization']);?>
                    <div class="fld w587">
                        <?php echo form_input($opt['general_info_form']['member_organization']);?>
                        <div id="err_member_organization" class="errormsg"></div>
                    </div>
                </div>
                <div>
                    <?php echo form_label(lang('OrgStructure').':', 'member_public', array("class"=>"left_label_p"));?>
                    <div class="fld w587">
                        <?php
                            $member_public_options = array(
                                ''			=> lang('select'),
                                'public'	=> lang('Public'),
                                'private'	=> lang('Private')
                            );
                            echo form_dropdown('member_public', $member_public_options, $users["public_status"], 'id="member_public"');
                        ?>
                        <div id="err_member_public" class="errormsg"></div>
                    </div>
                </div>
			<?php } else { ?>
				<div>
					<?php echo form_label(lang('Organization').':', 'member_organization', $opt['general_info_form']['lbl_organization']);?>
					<div class="fld w587">
						<?php echo form_input($opt['general_info_form']['member_organization']);?>
						<div id="err_member_organization" class="errormsg"></div>
					</div>
				</div>
				<div>
					<?php echo form_label(lang('Phone').':', 'member_phone', $opt['general_info_form']['lbl_phone']);?>
					<div class="fld w587">
						<?php echo form_input($opt['general_info_form']['member_phone']);?>
						<div id="err_member_phone" class="errormsg"></div>
					</div>
				</div>
				
				<div>
					<?php echo form_label(lang('Mission').' :', 'member_mission', $opt['general_info_form']['lbl_mission']);?>
					<div class="fld w587">
						<?php echo form_textarea($opt['general_info_form']['member_mission']);?>
						<div id="err_member_phone" class="errormsg"></div>
					</div>
				</div>
			<?php } ?>
			<!-- ExpertAdverts End-->

                <div>
                    <?php echo form_label(lang('TotalEmployees').':', 'member_org_employees', $opt['general_info_form']['lbl_org_employees']);?>
                    <div class="fld w587">
                        <?php
                            $member_org_employees_attr = 'id="member_org_employees"';
                            $member_org_employees_options = array(
                                ''			=> lang('select'),
                                '1-50' 		=> '1-50',
                                '50-100' 	=> '50-100',
                                '100-200' 	=> '100-200',
                                '200-500'	=> '200-500',
                                '500' 		=> '>500',
                            );
                            echo form_dropdown('member_org_employees', $member_org_employees_options,$users['totalemployee'],$member_org_employees_attr);
                        ?>
                    </div>
                </div>

                <div>
                    <?php echo form_label(lang('Annualrevenue').':', 'member_org_annual_revenue', $opt['general_info_form']['lbl_org_employees']);?>
                    <div class="fld w587">
                        <?php
                            $member_org_annual_revenue_attr = 'id="member_org_annual_revenue"';
                            $member_org_annual_revenue_options = array(
                                ''			=> lang('select'),
                                '0' 		=> '0 &ndash; 2.5 USD$ MM',
                                '2.5' 		=> '2.5 &ndash; 5.0 USD$ MM',
                                '5' 		=> '5.0 &ndash; 15.0 USD$ MM',
                                '15'		=> '15.0 &ndash; 50.0 USD$ MM',
                                '50' 		=> '50.0 &ndash; 200.0 USD$ MM',
                                '200' 		=> '>200.0 USD$ MM'
                            );
                            echo form_dropdown('member_org_annual_revenue', $member_org_annual_revenue_options,$users['annualrevenue'],$member_org_annual_revenue_attr);
                        ?>
                    </div>
                </div>

                <div>
                    <?php echo form_label(lang('Discipline').':', 'member_discipline', array('class'=>'left_label_p'));?>
                    <div class="fld w587">
                    <?php
                        $member_discipline_attr = 'id="member_discipline"';
                        $member_discipline_options =  discipline_dropdown();
                        echo form_dropdown('member_discipline', $member_discipline_options,$users['discipline'],$member_discipline_attr);
                    ?>
                    </div>
                </div>

                <div>
                    <?php echo form_label(lang('Country').':', 'member_country', $opt['general_info_form']['lbl_country']);?>
                    <div class="fld w587">
                        <?php
                            $member_country_attr = 'id="member_country"';
                            $member_country_options = country_dropdown();
                            echo form_dropdown('member_country', $member_country_options,$users['country'],$member_country_attr);
                        ?>
                    </div>
                </div>

                <div>
                    <?php echo form_label(lang('address').':', 'member_address', $opt['general_info_form']['lbl_address']);?>
                    <div class="fld w587">
                        <?php echo form_input($opt['general_info_form']['member_address']);?>
                    </div>
                </div>

                <div>
                    <?php echo form_label(lang('City').':', 'member_city', $opt['general_info_form']['lbl_city']);?>
                    <div class="fld w587">
                        <?php echo form_input($opt['general_info_form']['member_city']);?>
                    </div>
                </div>

                <div>
                    <?php echo form_label(lang('State').':', 'member_state', $opt['general_info_form']['lbl_state']);?>
                    <div class="fld w587">
                        <?php echo form_input($opt['general_info_form']['member_state']);?>
                    </div>
                </div>

                <div>
                    <?php echo form_label(lang('postal_code').':', 'member_postal_code', $opt['general_info_form']['lbl_postal_code']);?>
                    <div class="fld w587">
                        <?php echo form_input($opt['general_info_form']['member_postal_code']);?>
                    </div>
                </div>

                <?php echo form_submit('submit', lang('UpdatePersonalInformation'),'class = "light_green"');?>
	
                <?php echo form_close();?>
					
					<div id="expertise_sector_form_div" class="clearfix">
						<h4><?php echo lang('expertise').':';?></h4>
						
						
						<div id="load_expertise_sector_form" class="clearfix">
						
						<?php 
						if(count($sector) > 0)
						{
						foreach($sector as $key=>$sec)
						{
							
							$editlink 	= '/profile/form_load/expertise_sector_form/edit/'.$sec['id'];
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
																			'class' => 'left_label_p'
																			),
																		'lbl_sector_sub' => array(
																			'class' => 'left_label_p'
																			)
																		);
											?>								
									<div class="clearfix">
									<?php echo form_label(lang('Sector').':', 'project_sector_main', $opt['expertise_sector_form']['lbl_sector_main']);
									 echo form_hidden('hdn_expert_sector_from_id',$sec["id"]);
									?>
									
									<div class="fld">
									<?php
										$project_sector_main_attr	= 'id="project_sector_main'.$sec["id"].'" onchange="sectorbind('.$sec["id"].');"';
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
										$subsector_last				= array('class'=>'hardcode','value'=>'Other','text'=>lang('Other'));
										echo form_custom_dropdown('member_sub_sector', $subsector_options,$sec['subsector'],$project_sector_sub_attr,$subsector_opt,$subsector_first,$subsector_last);
									?>
										
									</div>
									<div class="fld errormsg" style="clear:both; margin-left:120px;"></div>
									<div style="display:none">
				
										<?php echo form_label(lang('Sub-SectorOther').':', 'profile_sector_sub_other', $opt['general_info_form']['lbl_sub_sector_other']);?>						
										<div class="fld w587">
											<?php echo form_input($opt['general_info_form']['member_sub_sector_other']);?>
										</div>
									</div>
								</div>
								<div class="view clearfix">
										<?php echo form_submit('submit', lang('UpdateSector'),'class = "light_green no_margin_left" id="btn_add_sector"  style="float:right;margin-right:10px;margin-bottom:10px;"');?>
								</div>
								</div>
								<!-- end .edit -->
								
								</div>
							
							</div>
										
							<?php echo form_close();?>
									
						</div>

						<?php
							}
						}
						?>
						</div>
							
						<?php echo form_open('profile/add_expert_sector',array('id'=>'expertise_sector_form','name'=>'expertise_sector_form','method'=>'post','class'=>'ajax_form'));
						
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
										)
	
									);
						?>								
								<div id="sectorContainer">
								<div class="sector_row">
									
									<!-- end .view -->
									<div class="edit clearfix" style="display: block;">
									<div>
										<?php
											echo form_hidden_custom('hdn_expert_sector_number',count($sector),false,'id="hdn_expert_sector_number"');
										?>						
										<div style="text-align:center;" class="errormsg"></div>
									</div>

									<div>
										<?php echo form_label(lang('Sector').':', 'project_sector_main', $opt['expertise_sector_form']['lbl_sector_main']);?>
										<div class="fld">
										<?php
											$project_sector_main_attr	= 'id="project_sector_main"';
											$sector_option = array();
											$sector_opt =array();
											foreach(sectors() as $key=>$value)
											{
												$sector_options[$value] = $value;
												$sector_opt[$value] 	= 'class="sector_main_'.$key.'"';
											}
											$sector_first			= array('class'=>'hardcode','text'=>lang('SelectASector'),'value'=>'');
											$sector_last			= array();
											
											echo form_custom_dropdown('member_sector', $sector_options,'',$project_sector_main_attr,$sector_opt,$sector_first,$sector_last);
										?>
										<div class="fld errormsg" style="clear:both;"></div>
										</div>
									</div>
									<br/>
									<div>
										<?php echo form_label(lang('Sub-Sector').':', 'project_sector_sub', $opt['expertise_sector_form']['lbl_sector_sub']);?>
										<div class="fld">
										<?php
											$project_sector_sub_attr 		= 'id="project_sector_sub"';
											$subsector_options 	= array();
											$subsector_opt		= array();
											foreach(subsectors() as $key=>$value)
											{
												foreach($value as $key2=>$value2)
												{
													$subsector_options[$value2] 	= $value2;
													$subsector_opt[$value2] 		= 'class="project_sector_sub_'.$key.'"';
												}
											}
											$subsector_first			= array('class'=>'hardcode','text'=>lang('SelectASub-Sector'),'value'=>'');
											$subsector_last				= array('class'=>'hardcode','value'=>'Other','text'=>lang('Other'));
											echo form_custom_dropdown('member_sub_sector', $subsector_options,'',$project_sector_sub_attr,$subsector_opt,$subsector_first,$subsector_last);
										?>
										<div class="fld errormsg" style="clear:both;"></div>
										</div>
										<div style="display:none">
				
											<?php echo form_label(lang('Sub-SectorOther').':', 'profile_sector_sub_other', $opt['expertise_sector_form']['lbl_sub_sector_other']);?>						
											<div class="fld w587">
												<?php echo form_input($opt['expertise_sector_form']['member_sub_sector_other']);?>
											</div>
										</div>
									</div>
									</div>
									<!-- end .edit -->
									<div class="view clearfix">
											<?php echo form_submit('submit', lang('SaveSector'),'class = "light_green no_margin_left" id="btn_add_sector"  style="float:right;margin-right:10px;margin-bottom:10px;"');?>


											</div>
									</div>
								
								</div>
								
								
							<!-- end .sector_row -->
						<?php echo form_close();?>

					</div>
	
				</div><!-- end #general_info_form -->
	
			</div>
	
			<!-- ExpertAdverts Start-->
			<?php if($usertype != '8'){?>
			<div id="tabs-2" class="col5_tab">
			
				<div class="clearfix" id="expertise_info_form">
			
				<?php echo form_open('profile/update_expertise',array('id'=>'expertise_info_form','name'=>'expertise_info_form','method'=>'post','class'=>'ajax_form')); ?>
				
				<?php 
				
				$opt['expertise_info_form'] = array(
												'lbl_focuskeyword' => array(
														'class' => 'block'
														),
												'member_areas_keywords'	=> array(
														'name' 		=> 'member_areas_keywords',
														'value' 	=> $expertise["areafocus"],
														'id'		=> ''
														),
												'lbl_expertise' => array(
														'class' => 'block'
														),
												'member_expertise'	=> array(
														'name' 		=> 'member_expertise',
														'value' 	=> $expertise["summary"],
														'id'		=> ''
														),
												'lbl_progoals' => array(
														'class' => 'block'
														),
												'member_pro_goals'	=> array(
														'name' 		=> 'member_pro_goals',
														'value' 	=> $expertise["progoals"],
														'id'		=> ''
														),
												'lbl_success' => array(
														'class' => 'block'
														),
												'member_success'	=> array(
														'name' 		=> 'member_success',
														'value' 	=> $expertise["success"],
														'id'		=> ''
														)
	
	
	
											);
	
						?>
						<?php echo form_label(lang('AreasofFocusKeywords').':<a title="'.lang('note1').'" class="tooltip"></a>', 'member_areas_keywords', $opt['expertise_info_form']['lbl_focuskeyword']);?>
						<p class="comment"><?php echo lang('expertise_info_1');?></p>
						<?php echo form_textarea($opt['expertise_info_form']['member_areas_keywords']);?>
	
						<?php echo form_label(lang('SummaryofExpertise').':<a title="'.lang('note2').'" class="tooltip"></a>', 'member_expertise', $opt['expertise_info_form']['lbl_expertise']);?>
						<p class="comment"><?php echo lang('expertise_info_2');?></p>
						<?php echo form_textarea($opt['expertise_info_form']['member_expertise']);?>
	
						<?php echo form_label(lang('ProfessionalGoals').':<a title="'.lang('note3').'" class="tooltip"></a>', 'member_pro_goals', $opt['expertise_info_form']['lbl_progoals']);?>
						<p class="comment"><?php echo lang('expertise_info_3');?></p>
						<?php echo form_textarea($opt['expertise_info_form']['member_pro_goals']);?>
	
						<?php echo form_label(lang('ProfessionalSuccess').':<a title="'.lang('note4').'" class="tooltip"></a>', 'member_success', $opt['expertise_info_form']['lbl_success']);?>
						<p class="comment"><?php echo lang('expertise_info_4');?></p>
						<?php echo form_textarea($opt['expertise_info_form']['member_success']);?>
	
						<div>
						<?php echo form_submit('submit', lang('UpdateExpertise'),'class = "light_green no_margin_left no_margin_top"');?>
						</div>
	
					<?php echo form_close();?>
					
				</div>
	
				<hr/>
				
				<div class="clearfix" id="div_expertise_education_form">
	
					<div id="education_list">
	
						<label class="block"><?php echo lang('Education');?></label>
						
	
					<div class="clearfix" id="load_expertise_education_form">
					
					<?php 
					if(count_if_set($education) >0)
					{
						foreach($education as $key=>$edu)
						{
							//$editlink 	= '/profile/form_load/expertise_education_form/edit/'.$edu['educationid'];
							$editlink 	= 'javascript:void(0);';
							$deletelink = '/profile/delete_education/'.$edu['educationid'];
							?>
							<div id="education_<?php echo $edu['educationid'];?>" class="edu_listing clearfix">
								<div class="clearfix">
									<p class="left"><strong><?php echo $edu['university'];?></strong> <?php echo '('.$edu['startyear'].' - '.$edu['gradyear'].')';?><br><?php echo $edu['degree'].' : '.$edu['major'];?></p>
										<a class="right delete" href="<?php echo $deletelink; ?>"><?php echo lang('Delete');?></a>
										<a class="right edit" href="<?php echo $editlink; ?>" id="education_edit_<?php echo $edu['educationid'];?>" onclick="edu_rowtoggle(this.id);"><?php echo lang('Edit');?></a>
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
													'value'		=> isset($edu['degree_other']) ? $edu['degree_other'] : ''
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
											$education_visibility_options = array('All'=>lang('All'),'Some'=>lang('Some'),'Other'=>lang('Other'));
											echo form_dropdown('education_visibility', $education_visibility_options,$edu['visibility'],$education_visibility_attr);
										?>
					
																			
									</div>
									
								</div>
											
								<div class="inner">
								
									<div>
										<?php echo form_label(lang('UniversityName').':', 'education_university', $opt['expertise_education_form']['lbl_university']);?>
										<div><?php echo form_input($opt['expertise_education_form']['education_university']);?>
										<div id="err_education_university" class="errormsg" style="margin-left:120px;"></div>
										</div>
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
										<?php echo form_submit('submit', lang('SaveEducation'),'class = "light_green"');?>
										<input type="button" value=<?php echo lang("Cancel");?> name="cancel" class="light_gray no_margin_left" onclick="javascript: $(this).parents('.education_edit').hide();">
									</div>
									
								</div><!-- end .inner -->
											
								<?php echo form_close();?>
										
							</div>
							
							
							</div>
							<!-- .edu_listing -->
						<?php }
					}	
					?>
							
					</div>
					
					</div><!-- end #education_list -->
	
					<div id="education_add">
						
						<?php echo form_open('profile/add_education',array('id'=>'expertise_education_form','name'=>'expertise_education_form','method'=>'post','class'=>'ajax_form')); ?>
						<?php 
				
						$opt['expertise_education_form'] = array(
								'lbl_university' => array(
										'class' => 'left_label'
										),
								'education_university'	=> array(
										'name' 		=> 'education_university',
										'id'		=> 'education_university'
										),
								'lbl_major' => array(
										'class' => 'left_label'
										),
								'education_major'	=> array(
										'name' 		=> 'education_major',
										'id'		=> 'education_major'
										),
								'education_degree_other'	=> array(
										'name' 		=> 'education_degree_other',
										'id'		=> 'education_degree_other'
										),		
								'lbl_degree' => array(
										'class' => 'left_label'
										),
								'lbl_startyear' => array(
										'class' => 'left_label'
										),
								'lbl_gradyear' => array(
										'class' => ''
										)											
							);?>
	
	
						
							<div class="top clearfix">						
								
								<strong class="left"><?php echo lang('AddMoreEducation');?></strong>
								
								<div class="right">
								
									<?php echo form_label(lang('Visibility').':', 'education_visibility');?>
									<?php						
										$education_visibility_attr = 'id="education_visibility"';
										$education_visibility_options = array('all'=>lang('All'),'some'=>lang('Some'),'other'=>lang('Other'));
										echo form_dropdown('education_visibility', $education_visibility_options,'',$education_visibility_attr);
									?>
	
																		
								</div>
								
							</div>
							
							<div class="inner">
							
								<div>
									<?php echo form_label(lang('UniversityName').':', 'education_university', $opt['expertise_education_form']['lbl_university']);?>
									<div class="fld" style="width:370px;">
										<?php echo form_input($opt['expertise_education_form']['education_university']);?>
										<div id="err_education_university" class="errormsg"></div>
									</div>
								</div>
								
								<div>
									<?php echo form_label(lang('Degree').':', 'education_degree', $opt['expertise_education_form']['lbl_degree']);?>
									<div class="fld" style="width:370px;">
										<?php						
												$education_degree_attr = 'id="education_degree"';
												$education_degree_options = education_dropdown();
												echo form_dropdown('education_degree', $education_degree_options,'',$education_degree_attr);
										?>
										<div id="err_education_degree" class="errormsg"></div>
									</div>
								</div>
								
								<div style="display:none">
									<?php echo form_label(lang('Other').':', 'education_degree_other', $opt['expertise_education_form']['lbl_major']);?>
									<div class="fld" style="width:370px;">
										<?php echo form_input($opt['expertise_education_form']['education_degree_other']);?>
										<div id="err_education_degree_other" class="errormsg"></div>
									</div>
		
								</div>

									
								<div>
									<?php echo form_label(lang('Major').':', 'education_major', $opt['expertise_education_form']['lbl_major']);?>
									<div class="fld" style="width:370px;">
										<?php echo form_input($opt['expertise_education_form']['education_major']);?>
										<div id="err_education_major" class="errormsg"></div>
									</div>
		
								</div>
								
								<div>
									<?php echo form_label(lang('Years').':', 'education_start_year', $opt['expertise_education_form']['lbl_startyear']);?>
									<div class="fld" style="width:370px;">
									<?php						
											$education_start_year_attr = 'id="education_start_year"';
											$education_start_year_options = year_dropdown('- year -');
											echo form_dropdown('education_start_year', $education_start_year_options,'',$education_start_year_attr);
									?>
									
									<?php echo form_label(lang('to').':', 'education_grad_year', $opt['expertise_education_form']['lbl_gradyear']);?>
									<?php						
											$education_grad_year_attr = 'id="education_grad_year"';
											$education_grad_year_options = year_dropdown('- year -');
											echo form_dropdown('education_grad_year', $education_grad_year_options,'',$education_grad_year_attr);
									?>
									</div>
									
								</div>
								
								<div>
									<?php echo form_submit('submit', lang('SaveEducation'),'class = "light_green"');?>
								</div>
								
							</div><!-- end .inner -->
							
						<?php echo form_close();?>
						
					</div><!-- end #education_add -->
	
				</div>
	
			</div>
			<?php } ?>
			<!-- ExpertAdverts End-->

			
	
		
			<div id="project-involvement" class="col5_tab project_form">
			
				<div class="clearfix">
				
					<p><strong><?php echo lang('MyProjectInvolvement');?></strong></p>
	
					
					<?php
					
					if($project['totalproj']> 0)
					{
						foreach($project['proj'] as $projkey=>$projval)
						{?>
						
						<div class="project clearfix">

                            <img alt="<?php echo $projval['projectname'];?>" class="left img_border" width="48px" src="<?php echo project_image($projval["projectphoto"], 50); ?>" height="50">

							<p class="left"><strong><?php echo $projval['projectname']; ?></strong></p>
							
							<?php if($usertype == '8')
							{
								$chkExp1 = '';
								$chkExp2 = '';
								
								if(isset($projval['exp_status']) && $projval['exp_status']=='1')
								{ $chkExp1 = 'style="background-position: 0px -48px;"';}
								else
								{ $chkExp2 = 'style="background-position: 0px -48px;"';}
							?>
								<div class="right" style="width:80px;padding-top:20px;">
										<a id="cancel_prj_<?php echo $projval['pid']; ?>" href="javascript:void(0);" class="radio" onclick="reject_projExpadv_req('/profile/reject_projExpadv/',<?php echo $projval['pid']; ?>,<?php echo $users['uid'];?>)" <?php echo $chkExp2;?> >&nbsp;</a>
										<span style="float:left; padding-top:3px"><?php echo lang('Cancel');?></span>
								</div>
								<div class="right" style="width:80px; padding-top:20px;">
										<a id="accept_prj_<?php echo $projval['pid']; ?>" href="javascript:void(0);" class="radio" onclick="accept_projExpadv_req('/profile/accept_projExpadv/',<?php echo $projval['pid']; ?>,<?php echo $users['uid'];?>)" <?php echo $chkExp1;?>>&nbsp;</a>
										<span style="float:left; padding-top:3px"><?php echo lang('Accept');?></span>
								</div>
							<?php }else{ ?>
								<span class="right">
									<a class="open_project" href="/projects/edit/<?php echo $projval['slug']; ?>"><?php echo lang('EditProject');?></a>
									<a class="view" href="/projects/<?php echo $projval['slug']; ?>"><?php echo lang('ViewProject');?></a>
								</span>
							<?php } ?>
							
							
						</div><!-- .project -->
						
						<?php } 
					}
					else
					{
						?>
						<div class="clear">&nbsp;</div>
						<div align="center"><?php echo lang('Noprojectsfoundtodisplay.')?></div>
						<div class="clear">&nbsp;</div>
						<?php
					}	
					?>
					
				</div>
				<!-- END EDIT -->
					
					
				</div>
	
			</div>
	
		</div><!-- end #tabs -->
	
	
	
	<div aria-labelledby="ui-dialog-title-dialog-message" class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable ui-resizable" role="dialog" style="display: none; z-index: 1002; outline: 0px none; position: absolute; height: auto; width: 300px; top: 1050px; left: 558px;" tabindex="-1">
		<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
			<span id="ui-dialog-title-dialog-message" class="ui-dialog-title"><?php echo lang('Message');?></span>
			<a class="ui-dialog-titlebar-close ui-corner-all" href=javascript:void(0); role="button">
				<span class="ui-icon ui-icon-closethick"><?php echo lang('close');?></span>
			</a>
		</div>
		<div id="dialog-message" class="ui-dialog-content ui-widget-content" scrollleft="0" scrolltop="0" style="width: auto; min-height: 12.8px; height: auto;">
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
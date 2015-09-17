<div class="centercontent">
    
    <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $headertitle; ?></h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
  
        
        <div id="contentwrapper" class="contentwrapper">
      
		 <?php
	      	 
						$opt["expertadvert_form"] = array(
								'expadvert_organizationname'	=> array(
				            		'id'	=> 'expadvert_organizationname',
						            'value' => '',
						            'name'	=> 'expadvert_organizationname',
						        ),
						        'lbl_expadvert_organizationname'	=> array(
				            		'class' => 'left_label',
						        ),
						        'lbl_expadvert_number_of_seat'	=> array(
				            		'class' => 'left_label',
						        ),
						        'expadvert_number_of_seat'	=> array(
				            		'id'	=> 'expadvert_number_of_seat',
						            'value' => '',
						            'name'	=> 'expadvert_number_of_seat',
					 	            'style' => 'width:120px',	
						        ),
						        'lbl_expadvert_license_no'	=> array(
				            		'class' => 'left_label',
						        ),
						        'expadvert_license_no'	=> array(
				            		'id'	=> 'expadvert_license_no',
						            'value' => substr(time(),1),
						            'name'	=> 'expadvert_license_no',
						            'readonly'=>'readonly',
						        ),
						        'lbl_expadvert_license_cost'=> array(
				            		'class' => 'left_label',
						        ),
						        'expadvert_license_cost'	=> array(
				            		'id'	=> 'expadvert_license_cost',
						            'value' => '',
						            'name'	=> 'expadvert_license_cost',
						        ),
						        'lbl_expadvert_license_cname'	=> array(
				            		'class' => 'left_label',
						        ),
						        'expadvert_license_cname'	=> array(
				            		'id'	=> 'expadvert_license_cname',
						            'value' => '',
						            'name'	=> 'expadvert_license_cname',
						        ),
						        'lbl_expadvert_license_cemail'=> array(
				            		'class' => 'left_label',
						        ),
						        'expadvert_license_cemail'	=> array(
				            		'id'	=> 'expadvert_license_cemail',
						            'value' => '',
						            'name'	=> 'expadvert_license_cemail',
						        ),
 
						       'lbl_expadvert_licensestart' => array(
									'class' => 'left_label'
								),
								'expadvert_licensestart' => array(
									'id'	=> 'expadvert_licensestart_picker',
									'name'	=> 'expadvert_licensestart',
									'value'	=> '',
									'class' => 'datepicker_month_year hasDatepicker',
									'style' => 'width:120px'
								),
								'lbl_expadvert_licenseend' => array(
									'class' => 'left_label'
								),
								'expadvert_licenseend' => array(
									'id'	=> 'expadvert_licenseend_picker',
									'name'	=> 'expadvert_licenseend',
									'value'	=> '',
									'class' => 'datepicker_month_year hasDatepicker',
									'style' => 'width:120px'
								)
							);
					?>

        
			<div class="floatleft" id="div_expert_advert_form" style="position:relative;width:48%; border: 1px solid #DDDDDD;padding: 10px 15px 15px;margin-bottom:20px;">

								<div class="notibar_add" style="display:none">
							            <a class="close"></a>
							            <p></p>
							        </div>
					
								<div class="contenttitle2">
						            <h3>Add New Expert Advert:</h3>
						        </div>
						        <br/>
			
								<div class="clearfix ">
									
									<?php echo form_open_multipart("members/add_expadvert/",array("id"=>"expertadvert_form","class"=>"expertadvert_form ajax_add_form")); ?>
									
									<div class="field">
										Organization Name:
										<?php echo form_input($opt["expertadvert_form"]["expadvert_organizationname"]); ?>
										<div class="errormsg" id="err_title_input"><?php echo form_error("expadvert_organizationname"); ?></div>
									</div>
						
									<div class="hiddenFields">
										<?php //echo form_hidden("return","projects/edit/".$slug); ?>
									</div>
									
									<div class="field" style="display:table;margin-right:30px; float:left;">
			
										<?php echo form_label("Number Of Seat:","expadvert_number_of_seat",$opt["expertadvert_form"]["lbl_expadvert_number_of_seat"]); ?>
										<div class="fld">
											<?php echo form_input($opt["expertadvert_form"]["expadvert_number_of_seat"]); ?>
											<div class="errormsg" id="err_expadvert_number_of_seat"><?php echo form_error("expadvert_number_of_seat"); ?></div>
										</div>
									
									</div>
									
									<div class="field" style="display:table;margin-right:30px; float:left; width:65%;">
			
										<?php echo form_label("License Number:","expadvert_license_no",$opt["expertadvert_form"]["lbl_expadvert_license_no"]); ?>
										<div class="fld">
											<?php echo form_input($opt["expertadvert_form"]["expadvert_license_no"]); ?>
											<div class="errormsg" id="err_expadvert_license_no"><?php echo form_error("expadvert_license_no"); ?></div>
										</div>
									
									</div>
									
									<div class="field" style="display:table;width:100%;">
			
										<?php echo form_label("License Cost:","expadvert_license_cost",$opt["expertadvert_form"]["lbl_expadvert_license_cost"]); ?>
										<div class="fld">
											<?php echo form_input($opt["expertadvert_form"]["expadvert_license_cost"]); ?>
											<div class="errormsg" id="err_expadvert_license_cost"><?php echo form_error("expadvert_license_cost"); ?></div>
										</div>
									
									</div>
									
									<div class="field" style="display:table;float:left; margin-right:30px;">
										<?php echo form_label("License Start Date:","expadvert_licensestart",$opt["expertadvert_form"]["lbl_expadvert_licensestart"]); ?>
										<div class="fld">
											<?php echo form_input(array("id"=>"licensestart","name"=>'expadvert_licensestart',"value"=>"")); ?>
											<div class="errormsg" id="err_expadvert_licensestart"><?php echo form_error("expadvert_licensestart"); ?></div>
										</div>
									</div>
									<div class="field" style="display:table;float:left;">
										<?php echo form_label("License End Date:","expadvert_licenseend",$opt["expertadvert_form"]["lbl_expadvert_licenseend"]); ?>
										<div class="fld">
											<?php echo form_input(array("id"=>"licenseend","name"=>'expadvert_licenseend',"value"=>"")); ?>
											<div class="errormsg" id="err_expadvert_licenseend"><?php echo form_error("expadvert_licenseend"); ?></div>
										</div>
									</div>
									
									<div class="field" style="display:table;width:100%;">
			
										<?php echo form_label("Account Contact Name:","expadvert_license_cname",$opt["expertadvert_form"]["lbl_expadvert_license_cname"]); ?>
										<div class="fld">
											<?php echo form_input($opt["expertadvert_form"]["expadvert_license_cname"]); ?>
											<div class="errormsg" id="err_expadvert_license_cname"><?php echo form_error("expadvert_license_cname"); ?></div>
										</div>
									
									</div>
									
									<div class="field" style="display:table;width:100%;">
			
										<?php echo form_label("Account Contact Email:","expadvert_license_cemail",$opt["expertadvert_form"]["lbl_expadvert_license_cemail"]); ?>
										<div class="fld">
											<?php echo form_input($opt["expertadvert_form"]["expadvert_license_cemail"]); ?>
											<div class="errormsg" id="err_expadvert_license_cname"><?php echo form_error("expadvert_license_cemail"); ?></div>
										</div>
									
									</div>
									
									<div>
										<?php
										
											/*if(isset($project['isforum'])&&$project['isforum']=='1')
											{
												$isforumCheck = TRUE;
											}
											else
											{
												$isforumCheck = FALSE;
											}
											
											$is_forum_data = array(
											    'name'        => 'project_isforum',
											    'id'          => 'project_isforum',
											    'value'       => '1',
											    'checked'     => $isforumCheck,
											);
											echo form_checkbox($is_forum_data);*/?>
										<?php //echo form_label('Forum Attending ', 'project_isforum');?>						
									</div>
			
									<?php echo form_submit('expertadvert_submit', 'Add Expert Advert','class = "light_green no_margin_left"');?>
			
								<?php echo form_close(); ?>
								</div>
								
							</div>
        	
        	
        </div><!--contentwrapper-->
        
	</div>
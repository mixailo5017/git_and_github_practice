<div class="centercontent">
    <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $title; ?></h1>
            <a class="right goback" style="margin-right:20px;" href="/admin.php/members"><span>Back</span></a>
            <?php
            	$usertype = (! $users["membertype"]) ? '0' : $users["membertype"];

                if (isset($users["membertype"]) && $users["membertype"] == MEMBER_TYPE_EXPERT_ADVERT) {
					$fullname = $users['organization'];
				} else {
					$fullname = $users['firstname'].' '.$users['lastname'];
				}
            ?>
            <div class="pagetitle2"><?php echo "(".$fullname.")";?></div>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->

        <div id="contentwrapper" class="contentwrapper">
             <div class="widgetcontent">
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">General</a></li>
                        <?php if($users["membertype"] != MEMBER_TYPE_EXPERT_ADVERT){?>
                            <li><a href="#tabs-2">Expertise</a></li>
                        <?php } ?>
                        <li><a href="#tabs-3">Sector</a></li>
                        <li><a href="#tabs-4">Education</a></li>
                        <?php if($users["membertype"] != MEMBER_TYPE_EXPERT_ADVERT){?>
                            <li><a href="#project-involvement">Project Involvement</a></li>
                        <?php } ?>
                        <?php if($users["membertype"] == MEMBER_TYPE_EXPERT_ADVERT) {?>
                            <li><a href="#expert-advert-seats">Experts</a></li>
                        <?php } ?>
                        <?php if ($users['uid'] != sess_var('admin_uid')) { ?>
                        <li><a href="#account">Account Settings</a></li>
                        <?php } ?>
                    </ul>

                    <div id="tabs-1">
                        <div id="general_info_tab" style="display:table; width:100%;">
                            <div style="width:44%; float:left;">
                            <div class="floatleft clearfix" id="div_general_photo_form" style="position:relative;">
                                <div class="contenttitle2">
                                    <h3>Upload Photo--</h3>
                                </div>
                                <br/>
                                <div style="width:150px;" class="clearfix floatleft"><div class="div_resize_img150">
                                <?php 
                                    if($users['membertype']== '8') {
                                        $src = company_image($users["userphoto"], 150, array('crop'=>false));
                                    } else {
                                        $src = expert_image($users["userphoto"], 150, array('crop'=>false));
                                    }
                                ?>
                                    <img class="uploaded_img" alt="placeholder" style="margin:0px" src="<?php echo $src;?>" width="150">
                                </div></div>

                                <div class="floatleft" style="padding-left:10px;">
                                    <div class="comment no_margin_top">Select an image from your computer (5MB max):</div>
                                        <?php echo form_open_multipart('myaccount/upload_userphoto/'.$users['uid'],array('id'=>'general_photo_form','name'=>'general_photo_form','method'=>'post','class'=>'ajax_form'));?>
                                        <?php $opt['general_photo_form'] = array('photo_filename' => array('name' => 'photo_filename','id' => 'photo_filename'));?>

                                        <div class='hiddenFields'>
                                            <?php echo form_hidden("RET",current_url()); ?>
                                        </div>

                                        <?php echo form_upload($opt['general_photo_form']['photo_filename']);?>
                                            <div id="err_photo_filename" class="errormsg"></div>
                                            <div class="comment">Compatible file types: JPEG, GIF, PNG</div>
                                                <?php echo form_submit('submit', 'Upload Profile Image', 'class = "light_green no_margin_left"');?>
                                        <?php echo form_close();?>
                                              
                                        <?php echo form_open('myaccount/delete_userphoto/'.$users['uid'],array('id'=>'delete_photo_form', 'name'=>'delete_photo_form', 'method'=>'post', 'class'=>'ajax_form', 'style'=>'padding-top:10px'));?>
                                            <?php echo form_submit('submit', 'Delete Profile Image', 'class = "light_green no_margin_left"');?>
                                        <?php echo form_close();?>
                                    </div>
                                </div>

                        <?php if(isset($usertype) && $usertype == '8' && '1'=='1'){

                        if($users["licensestart"] != "1111-11-11") { $licensestart = DateFormat($users["licensestart"],DATEFORMAT,FALSE); } else { $licensestart = ""; }
                        if($users["licenseend"] != "1111-11-11") { $licenseend = DateFormat($users["licenseend"],DATEFORMAT,FALSE); } else { $licenseend = ""; }

                            $opt["expertadvert_form"] = array(
                                    'expadvert_organizationname'	=> array(
                                        'id'	=> 'expadvert_organizationname',
                                        'value' => $users['organization'],
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
                                        'value' => $users['numberofseat'],
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
                                        'value' => $users['licensecost'],
                                        'name'	=> 'expadvert_license_cost',
                                    ),
                                    'lbl_expadvert_license_cname'	=> array(
                                        'class' => 'left_label',
                                    ),
                                    'expadvert_license_cname'	=> array(
                                        'id'	=> 'expadvert_license_cname',
                                        'value' => $users['accountname'],
                                        'name'	=> 'expadvert_license_cname',
                                    ),
                                    'lbl_expadvert_license_cemail'=> array(
                                        'class' => 'left_label',
                                    ),
                                    'expadvert_license_cemail'	=> array(
                                        'id'	=> 'expadvert_license_cemail',
                                        'value' => $users['email'],
                                        'name'	=> 'expadvert_license_cemail',
                                    ),

                                   'lbl_expadvert_licensestart' => array(
                                        'class' => 'left_label'
                                    ),
                                    'expadvert_licensestart' => array(
                                        'id'	=> 'expadvert_licensestart_picker',
                                        'name'	=> 'expadvert_licensestart',
                                        'value'	=> $licensestart,
                                        'class' => 'datepicker_month_year hasDatepicker',
                                        'style' => 'width:120px'
                                    ),
                                    'lbl_expadvert_licenseend' => array(
                                        'class' => 'left_label'
                                    ),
                                    'expadvert_licenseend' => array(
                                        'id'	=> 'expadvert_licenseend_picker',
                                        'name'	=> 'expadvert_licenseend',
                                        'value'	=> $licenseend,
                                        'class' => 'datepicker_month_year hasDatepicker',
                                        'style' => 'width:120px'
                                    )
                                );
                        ?>


                        <div class="floatleft clearfix" id="div_expert_advert_form" style="position:relative; width:85%; border: 1px solid #DDDDDD;padding: 10px 15px 15px;margin-bottom:20px;">
                            <div class="notibar_add" style="display:none">
                                    <a class="close"></a>
                                    <p></p>
                                </div>

                            <div class="contenttitle2">
                                <h3>License Expert Advert:</h3>
                            </div>
                            <br/>

                            <div class="clearfix ">

                                <?php echo form_open_multipart("myaccount/update_expadvert/".$users['uid'],array("id"=>"expertadvert_form","class"=>"expertadvert_form ajax_add_form")); ?>

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
                                        <?php echo form_input(array("id"=>"licensestart","name"=>'expadvert_licensestart',"value"=>$licensestart)); ?>
                                        <div class="errormsg" id="err_expadvert_licensestart"><?php echo form_error("expadvert_licensestart"); ?></div>
                                    </div>
                                </div>
                                <div class="field" style="display:table;float:left;">
                                    <?php echo form_label("License End Date:","expadvert_licenseend",$opt["expertadvert_form"]["lbl_expadvert_licenseend"]); ?>
                                    <div class="fld">
                                        <?php echo form_input(array("id"=>"licenseend","name"=>'expadvert_licenseend',"value"=>$licenseend)); ?>
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

                                <?php echo form_submit('expertadvert_submit', 'Update License Expert Advert','class = "light_green no_margin_left"');?>

                            <?php echo form_close(); ?>
                            </div>

                        </div>
                        <?php
                            // Display only when usertype is not expert advert.
                        } ?>
                        </div>


                                        <div class="floatleft" id="div_general_info_form" style="position:relative;width:48%">

                                            <div class="contenttitle2">
                                                <h3>Professional Information:</h3>
                                            </div>
                                            <br/>

                                            <?php echo form_open('myaccount/update/'.$users['uid'],array('id'=>'general_info_form','name'=>'_general_info_form','method'=>'post','class'=>'ajax_form_'));

                                            $opt['general_info_form'] = array(
                                                                        'lbl_firstname' => array(
                                                                                'class' => 'left_label'
                                                                                ),
                                                                        'member_first_name'	=> array(
                                                                                'name' 		=> 'member_first_name',
                                                                                'id' 		=> 'member_first_name',
                                                                                'class'		=> 'longinput',
                                                                                'value' 	=> $users["firstname"]
                                                                                ),
                                                                        'lbl_lastname' => array(
                                                                                'class' => 'left_label'
                                                                                ),
                                                                        'member_last_name'	=> array(
                                                                                'name' 		=> 'member_last_name',
                                                                                'id' 		=> 'member_last_name',
                                                                                'class'		=> 'longinput',
                                                                                'value' 	=> $users["lastname"]
                                                                                ),
                                                                        'lbl_title' => array(
                                                                                'class' => 'left_label'
                                                                                ),
                                                                        'member_title'	=> array(
                                                                                'name' 		=> 'member_title',
                                                                                'id' 		=> 'member_title',
                                                                                'class'		=> 'longinput',
                                                                                'value' 	=> $users["title"]
                                                                                ),
                                                                        'lbl_organization' => array(
                                                                                'class' => 'left_label'
                                                                                ),
                                                                        'member_organization'	=> array(
                                                                                'name' 		=> 'member_organization',
                                                                                'id' 		=> 'member_organization',
                                                                                'class'		=> 'longinput',
                                                                                'value' 	=> $users["organization"]
                                                                                ),
                                                                        'lbl_org_employees' => array(
                                                                                'class' 	=> 'left_label'
                                                                                ),
                                                                        'lbl_org_annual_revenue' => array(
                                                                                'class' 	=> 'left_label'
                                                                                ),
                                                                        'lbl_sector_main' 	=> array(
                                                                                'class' 	=> 'left_label'
                                                                                ),
                                                                        'lbl_sector_sub' 	=> array(
                                                                                'class'		=> 'left_label'
                                                                                ),
                                                                        'lbl_sub_sector_other'=> array(
                                                                                'class' 	=> 'left_label'
                                                                                ),
                                                                        'member_sub_sector_other'=> array(
                                                                                'id'	 	=> 'profile_sector_sub_other',
                                                                                'name'		=> 'member_sub_sector_other',
                                                                                'value'		=> '',
                                                                                'class'		=> 'longinput',
                                                                                'disabled'	=> 'disabled'
                                                                                ),
                                                                        'lbl_country'=> array(
                                                                                'class' 	=> 'left_label'
                                                                                ),
                                                                        'lbl_address'=> array(
                                                                                'class' 	=> 'left_label'
                                                                                ),
                                                                        'member_address'	=> array(
                                                                                'name' 		=> 'member_address',
                                                                                'class'		=> 'longinput',
                                                                                'value' 	=> $users["address"]
                                                                                ),
                                                                        'lbl_city' => array(
                                                                                'class' 	=> 'left_label'
                                                                                ),
                                                                        'member_city'	=> array(
                                                                                'name' 		=> 'member_city',
                                                                                'class'		=> 'longinput',
                                                                                'value' 	=> $users["city"]
                                                                                ),
                                                                        'lbl_state' => array(
                                                                                'class' 	=> 'left_label'
                                                                                ),
                                                                        'member_state'	=> array(
                                                                                'name' 		=> 'member_state',
                                                                                'class'		=> 'longinput',
                                                                                'value' 	=> $users["state"]
                                                                                ),
                                                                        'lbl_postal_code' => array(
                                                                                'class' 	=> 'left_label'
                                                                                ),
                                                                        'member_postal_code'	=> array(
                                                                                'name' 		=> 'member_postal_code',
                                                                                'class'		=> 'longinput',
                                                                                'value' 	=> $users["postal_code"]
                                                                                ),
                                                                        'lbl_phone' => array(
                                                                                'class' 	=> 'left_label'
                                                                                ),
                                                                        'member_phone'	=> array(
                                                                                'name' 			=> 'member_phone',
                                                                                'value' 		=> $users["vcontact"],
                                                                                'maxlength'		=> '25'
                                                                                ),
                                                                        'lbl_mission' => array(
                                                                                'class' 	=> 'left_label'
                                                                                ),
                                                                        'member_mission'	=> array(
                                                                                'name' 		=> 'member_mission',
                                                                                'value' 	=> $users["mission"],
                                                                                'style'		=>'margin-bottom:0px;'
                                                                        )

                                                );
                                             ?>
                                            <!-- ExpertAdverts Start-->
                                            <?php
                                            echo form_hidden('hdn_member_usertype',$usertype);

                                            if($usertype != '8')
                                            {?>
                                                <div class="field">
                                                    <?php echo form_label('First Name:', 'member_first_name', $opt['general_info_form']['lbl_firstname']);?>
                                                    <div class="fld" >
                                                        <?php echo form_input($opt['general_info_form']['member_first_name']);?>
                                                        <div id="err_member_first_name" class="errormsg">
                                                            <?php echo form_error('member_first_name');?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="field">
                                                    <?php echo form_label('Last Name:', 'member_last_name', $opt['general_info_form']['lbl_lastname']);?>
                                                    <div class="fld" >
                                                        <?php echo form_input($opt['general_info_form']['member_last_name']);?>
                                                        <div id="err_member_last_name" class="errormsg">
                                                            <?php echo form_error('member_last_name');?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="field">
                                                    <?php echo form_label('Title:', 'member_title', $opt['general_info_form']['lbl_title']);?>
                                                    <div class="fld" >
                                                        <?php echo form_input($opt['general_info_form']['member_title']);?>
                                                        <div id="err_member_title" class="errormsg">
                                                            <?php echo form_error('member_title');?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="field">
                                                    <?php echo form_label('Organization:', 'member_organization', $opt['general_info_form']['lbl_organization']);?>
                                                    <div class="fld" >
                                                        <?php echo form_input($opt['general_info_form']['member_organization']);?>
                                                        <div id="err_member_organization" class="errormsg">
                                                        <?php echo form_error('member_organization');?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <?php echo form_label('Org Structure:', 'member_public', array("class"=>"left_label"));?>
                                                    <div class="fld">
                                                        <?php
                                                            $member_public_attr = 'id="member_public"';
                                                            $member_public_options = array(
                                                                'public'	=> 'Public',
                                                                'private'	=> 'Private'
                                                            );
                                                            echo form_dropdown('member_public', $member_public_options,$users["public_status"],$member_public_attr);
                                                        ?>
                                                        <div id="err_member_public" class="errormsg"></div>
                                                    </div>
                                                </div>
                                    <?php } else
                                    { ?>
                                        <div>
                                            <?php echo form_label('Organization:', 'member_organization', $opt['general_info_form']['lbl_organization']);?>
                                            <div class="fld">
                                                <?php echo form_input($opt['general_info_form']['member_organization']);?>
                                                <div id="err_member_organization" class="errormsg"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <?php echo form_label('Phone:', 'member_phone', $opt['general_info_form']['lbl_phone']);?>
                                            <div class="fld">
                                                <?php echo form_input($opt['general_info_form']['member_phone']);?>
                                                <div id="err_member_phone" class="errormsg"></div>
                                            </div>
                                        </div>

                                        <div>
                                            <?php echo form_label('Mission :', 'member_mission', $opt['general_info_form']['lbl_mission']);?>
                                            <div class="fld">
                                                <?php echo form_textarea($opt['general_info_form']['member_mission']);?>
                                                <div id="err_member_phone" class="errormsg"></div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!-- ExpertAdverts End-->

                                                <div class="field">
                                                    <?php echo form_label('Total Employees:', 'member_org_employees', $opt['general_info_form']['lbl_org_employees']);?>
                                                    <div class="fld" >
                                                        <?php
                                                            $member_org_employees_attr = 'id="member_org_employees"';
                                                            $member_org_employees_options = array(
                                                                ''			=> '- Select -',
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

                                                <div class="field">
                                                    <?php echo form_label('Annual revenue:', 'member_org_annual_revenue', $opt['general_info_form']['lbl_org_employees']);?>
                                                    <div class="fld">
                                                        <?php
                                                            $member_org_annual_revenue_attr = 'id="member_org_annual_revenue"';
                                                            $member_org_annual_revenue_options = array(
                                                                ''			=> '- Select -',
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


                                                <div class="field">
                                                    <?php echo form_label('Discipline:', 'member_discipline', array('class'=>'left_label'));?>
                                                    <div class="fld" >
                                                    <?php
                                                        $member_discipline_attr = 'id="member_discipline"';
                                                        $member_discipline_options =  discipline_dropdown();
                                                        echo form_dropdown('member_discipline', $member_discipline_options,$users['discipline'],$member_discipline_attr);
                                                    ?>
                                                    </div>
                                                </div>

                                                <div class="field">
                                                    <?php echo form_label('Country:', 'member_country', $opt['general_info_form']['lbl_country']);?>
                                                    <div class="fld" >
                                                        <?php
                                                            $member_country_attr = 'id="member_country"';
                                                            $member_country_options = country_dropdown();
                                                            echo form_dropdown('member_country', $member_country_options,$users['country'],$member_country_attr);
                                                        ?>
                                                    </div>
                                                </div>

                                                <div class="field">
                                                    <?php echo form_label('Address:', 'member_address', $opt['general_info_form']['lbl_address']);?>
                                                    <div class="fld" >
                                                        <div class="fld" >
                                                        <?php echo form_input($opt['general_info_form']['member_address']);?>
                                                    </div>
                                                    </div>
                                                </div>

                                                <div class="field">
                                                    <?php echo form_label('City:', 'member_city', $opt['general_info_form']['lbl_city']);?>
                                                    <div class="fld" >
                                                        <?php echo form_input($opt['general_info_form']['member_city']);?>
                                                    </div>
                                                </div>

                                                <div class="field">
                                                    <?php echo form_label('State:', 'member_state', $opt['general_info_form']['lbl_state']);?>
                                                    <div class="fld" >
                                                        <?php echo form_input($opt['general_info_form']['member_state']);?>
                                                    </div>
                                                </div>

                                                <div class="field">
                                                    <?php echo form_label('Postal Code:', 'member_postal_code', $opt['general_info_form']['lbl_postal_code']);?>
                                                    <div class="fld" >
                                                        <?php echo form_input($opt['general_info_form']['member_postal_code']);?>
                                                    </div>
                                                </div>

                                                <?php echo form_submit('submit', 'Update Personal Information','class = "light_green"');?>

                                            <?php echo form_close();?>

                                        </div>









                                        <!-- end #general_info_form -->
                                    </div>
                    </div>
							
						
						<?php if($usertype != '8'){ 
						// Display only when usertype is not expert advert.
						?>
						
								<div id="tabs-2" >
								
									<div class="clearfix" id="expertise_info_form">
								
									<?php echo form_open('myaccount/update_expertise/'.$users['uid'],array('id'=>'expertise_info_form','name'=>'expertise_info_form','method'=>'post','class'=>'stdform stdform2')); ?>
									
									<?php 
									
									$opt['expertise_info_form'] = array(
																	'lbl_focuskeyword' => array(
																			'class' => 'block'
																			),
																	'member_areas_keywords'	=> array(
																			'name' 		=> 'member_areas_keywords',
																			'value' 	=> $expertise["areafocus"],
																			'id'		=> '',
																			'cols'		=> 40,
																			'rows'		=> 5
																			),
																	'lbl_expertise' => array(
																			'class' => 'block'
																			),
																	'member_expertise'	=> array(
																			'name' 		=> 'member_expertise',
																			'value' 	=> $expertise["summary"],
																			'id'		=> '',
																			'cols'		=> 40,
																			'rows'		=> 5
																			),
																	'lbl_progoals' => array(
																			'class' => 'block'
																			),
																	'member_pro_goals'	=> array(
																			'name' 		=> 'member_pro_goals',
																			'value' 	=> $expertise["progoals"],
																			'id'		=> '',
																			'cols'		=> 40,
																			'rows'		=> 5
																			),
																	'lbl_success' => array(
																			'class' => 'block'
																			),
																	'member_success'	=> array(
																			'name' 		=> 'member_success',
																			'value' 	=> $expertise["success"],
																			'id'		=> '',
																			'cols'		=> 40,
																			'rows'		=> 5
																			)
							
																);
							
									?>
									
									<p style="border-top:1px #dddddd solid;">
			                        	<?php echo form_label('Areas of Focus Keywords:<a title="EXAMPLE: Engineering, Project Management, etc." class="tooltip"></a>', 'member_areas_keywords', $opt['expertise_info_form']['lbl_focuskeyword']);?>
			                            <span class="field">Separate each keyword with a comma: "Engineering, Project Management"<br>
			                            <?php echo form_textarea($opt['expertise_info_form']['member_areas_keywords']);?></span>
			                        </p>
			                        
									<p>
			                        	<?php echo form_label('Summary of Expertise:<a title="EXAMPLE: 20 years successfully structuring concessions. Predominantly, my work has focused on highways obtaining project financing in a variety of forms, navigating regulatory regimes and designing legal frameworks to ensure maximum liability for developers." class="tooltip"></a>', 'member_expertise', $opt['expertise_info_form']['lbl_expertise']);?>
										<span class="field">What makes him/her an expert in your field?<br>
			                            <?php echo form_textarea($opt['expertise_info_form']['member_expertise']);?></span>
			                        </p>
			                        
			                        <p>
			                        	<?php echo form_label('Biography:<a title="EXAMPLE: My goal is to become one of the worlds most respected experts in my expertise field." class="tooltip"></a>', 'member_pro_goals', $opt['expertise_info_form']['lbl_progoals']);?>
										<span class="field">Separate each keyword with a comma: "Engineering, Project Management"<br>
			                            <?php echo form_textarea($opt['expertise_info_form']['member_pro_goals']);?></span>
			                        </p>
			                        
			                        <p>
			                        	<?php echo form_label('Greatest Professional Success:<a title="EXAMPLE: Played an integral role in the design of my country\'s first metro project. Was promoted to project manager at the age of 27, the youngest person to hold that position within the ministry." class="tooltip"></a>', 'member_success', $opt['expertise_info_form']['lbl_success']);?>
										<span class="field">
			                            <?php echo form_textarea($opt['expertise_info_form']['member_success']);?></span>
			                        </p>
			                        
			                        <p class="stdformbutton">
			                        	<?php echo form_submit('submit', 'Update Expertise','class = "light_green no_margin_left no_margin_top"');?>
									</p>
									
									<?php echo form_close();?>
										
									</div>
								
								</div>
					<?php } ?>
							<div id="tabs-3" >
								
								<div id="expertise_sector_form" class="clearfix">
										
									<div id="sector_list" style="display:table; width:100%;">
							
									<div class="clearfix floatleft" id="load_expertise_sector_form" style="width:60%;position:relative;">
										
										<div class="contenttitle2">
								            <h3>Sector List</h3>
								        </div>	
								        <div class="notibar" style="display:none">
								            <a class="close"></a>
								            <p></p>
								        </div>
								
										 <div class="tableoptions">
								                	<button class="deletebutton radius3" title="Delete Selected" name="dyntable2" id="#/admin.php/myaccount/delete_expert_sector">Delete Expertise Sector</button> &nbsp;
								                	</div><!--tableoptions-->
								                <table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable2">
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
								                            <th class="head0">Sector</th>
								                            <th class="head1">Sub sector</th>
								                            <th class="head1 nosort">Action</th>
								                        </tr>
								                    </thead>
								                    <tfoot>
								                        <tr>
								                          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
								                            <th class="head1">ID</th>
								                            <th class="head0">Sector</th>
								                            <th class="head1">Sub sector</th>
								                            <th class="head1 nosort">Action</th>
								                        </tr>
								                    </tfoot>
								                    <tbody>
								                    	<?php 
								                    	if(count($sector) > 0)
														{
														foreach($sector as $key=>$sec)
														{
																
														?>
														<tr>
														  	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$sec['id']."","name"=>"select_".$sec['id']."","value"=>$sec['id'])); ?></span></td>
								                            <td><?php echo $sec['id']; ?></td>
								                            <td><?php echo $sec['sector']; ?></td>
								                            <td><?php echo $sec['subsector'];?></td>
								                            <td><a href="javascript:void(0);" onclick="edit_sector('<?php echo $sec['uid'];?>',<?php echo $sec['id'];?>,'sector_edit')">Edit</a></td>
								                        </tr>
								
														<?php
																
															}
														}
														?>
								                    </tbody>
								                </table>					
									</div>
										
									<div id="sector_add" style="width:36%; position:relative;" class="floatleft">
											
											
											<?php echo form_open('myaccount/add_expert_sector/'.$users['uid'],array('id'=>'expertise_add_sector_form','name'=>'expertise_sector_form','method'=>'post','class'=>'ajax_form'));
											
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
													<?php echo form_hidden('hdn_userid',$users['uid']);?>
							
													
													<div class="contenttitle2">
								                        <h3>Add New Sector</h3>
								                    </div>
								                    
								                   <div>
														<?php
															echo form_hidden_custom('hdn_expert_sector_number',count($sector),false,'id="hdn_expert_sector_number"');
														?>						
													</div>
						
													<div>
														<?php echo form_label('Sector:', 'project_sector_main', $opt['expertise_sector_form']['lbl_sector_main']);?>
														<div class="fld">
															<?php
																$project_sector_main_attr	= 'id="project_sector_main" onchange="sectorbind('.$users['uid'].');"';
																$sector_option = array();
																$sector_opt =array();
																foreach(sectors() as $key=>$value)
																{
																	$sector_options[$value] = $value;
																	$sector_opt[$value] 	= 'class="sector_main_'.$key.'"';
																}
																$sector_first			= array('class'=>'hardcode','text'=>'- Select A Sector -','value'=>'');
																$sector_last			= array();
																
																echo form_custom_dropdown('member_sector', $sector_options,'',$project_sector_main_attr,$sector_opt,$sector_first,$sector_last);
															?>
															<div class="fld errormsg" style="clear:both;"><?php echo form_error('member_sector');?></div>
														</div>
													</div>
													<br/>
													<div class="fld">
														<?php echo form_label('Sub-Sector:', 'project_sector_sub', $opt['expertise_sector_form']['lbl_sector_sub']);?>
														<div  id="dynamicSubsector"  class="fld">
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
															$subsector_first			= array('class'=>'hardcode','text'=>'- Select A Sub-Sector -','value'=>'');
															$subsector_last				= array('class'=>'hardcode','value'=>'Other','text'=>'Other');
															echo form_custom_dropdown('member_sub_sector', $subsector_options,'',$project_sector_sub_attr,$subsector_opt,$subsector_first,$subsector_last);
														?>
														<div class="fld errormsg" style="clear:both;"><?php echo form_error('member_sub_sector');?></div>
														</div>
														<div style="display:none">
								
															<?php echo form_label('Sub-Sector Other:', 'profile_sector_sub_other', $opt['expertise_sector_form']['lbl_sub_sector_other']);?>						
															<div class="fld" >
																<?php echo form_input($opt['expertise_sector_form']['member_sub_sector_other']);?>
															</div>
														</div>
													</div>
													<!-- end .edit -->
													<div class="view clearfix">
														<?php echo form_submit('submit', 'Save Sector','class = "light_green no_margin_left" id="btn_add_sector"');?>
													</div>
													
											<?php echo form_close();?>
											
										</div><!-- end #education_add -->
										
									</div>
								
								</div>
									
								</div>
								
								<div id="tabs-4" >
								
									<div id="education_list" style="display:table; width:100%;">
							
									<div class="clearfix floatleft" id="load_expertise_education_form" style="width:60%;position:relative;">
										
										<div class="contenttitle2">
								            <h3>Education List</h3>
								        </div>	
								        <div class="notibar" style="display:none">
								            <a class="close"></a>
								            <p></p>
								        </div>
								
										 <div class="tableoptions">
								                	<button class="deletebutton radius3" title="Delete Selected" name="dyntable3" id="#/admin.php/myaccount/delete_education">Delete Educations</button> &nbsp;
								                	</div><!--tableoptions-->
								                <table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable3">
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
								                            <th class="head0">University</th>
								                            <th class="head1">Date</th>
								                            <th class="head0">Degree</th>
								                            <th class="head1 nosort">Action</th>
								                        </tr>
								                    </thead>
								                    <tfoot>
								                        <tr>
								                          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
								                            <th class="head1">ID</th>
								                            <th class="head0">University</th>
								                            <th class="head1">Date</th>
								                            <th class="head0">Degree</th>
								                            <th class="head1 nosort">Action</th>
								                        </tr>
								                    </tfoot>
								                    <tbody>
								                    	<?php 
								                    	if(count($education) > 0)
														{
															foreach($education as $key=>$edu)
															{
																
														?>
														<tr>
								                          	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$edu['educationid']."","name"=>"select_".$edu['educationid']."","value"=>$edu['educationid'])); ?></span></td>
								                            <td><?php echo $edu['educationid']; ?></td>
								                            <td><?php echo $edu['university']; ?></td>
								                            <td><?php echo '('.$edu['startyear'].' - '.$edu['gradyear'].')';?></td>
								                            <td><?php echo $edu['degree'].' : '.$edu['major'];?></td>
								                            <td><a onclick="edit_myeducation('<?php echo $edu['uid'];?>',<?php echo $edu['educationid'];?>,'education_edit')">Edit</a></td>
								                        </tr>
								
														<?php
																
															}
														}
														?>
								                    </tbody>
								                </table>					
									</div>
										
									<div id="education_add" style="width:36%; position:relative;" class="floatleft">
											
											<?php echo form_open('myaccount/add_education',array('id'=>'expertise_education_form','name'=>'expertise_education_form','method'=>'post','class'=>'ajax_form')); ?>
											<?php 
									
											$opt['expertise_education_form'] = array(
													'lbl_university' => array(
															'class' => 'left_label'
															),
													'education_university'	=> array(
															'name' 		=> 'education_university',
															'id'		=> 'education_university',
															'class'		=> 'longinput'
															),
													'lbl_major' => array(
															'class' => 'left_label'
															),
													'education_major'	=> array(
															'name' 		=> 'education_major',
															'id'		=> 'education_major',
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
															)											
												);?>
							
							
											<?php echo form_hidden('hdn_userid',$users['uid']);?>
							
												<div class="top clearfix">						
													
													<div class="contenttitle2">
								                        <h3>Add New Education</h3>
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
														<div class="fld">
															<?php echo form_input($opt['expertise_education_form']['education_university']);?>
															<div id="err_education_university" class="errormsg"><?php echo form_error('education_university');?></div>
														</div>
													</div>
													
													<div style="margin-bottom:10px;">
														<?php echo form_label('Degree:', 'education_degree', $opt['expertise_education_form']['lbl_degree']);?>
														<div class="fld">
															<?php						
																	$education_degree_attr = 'id="education_degree"';
																	$education_degree_options = education_dropdown();
																	echo form_dropdown('education_degree', $education_degree_options,'',$education_degree_attr);
															?>
															<div id="err_education_degree" class="errormsg"><?php echo form_error('education_degree');?></div>
														</div>
													</div>
														
													<div style="margin-bottom:10px;">
														<?php echo form_label('Major:', 'education_major', $opt['expertise_education_form']['lbl_major']);?>
														<div class="fld">
															<?php echo form_input($opt['expertise_education_form']['education_major']);?>
															<div id="err_education_major" class="errormsg"><?php echo form_error('education_major');?></div>
														</div>
							
													</div>
													
													<div style="margin-bottom:10px;">
														<?php echo form_label('Years:', 'education_start_year', $opt['expertise_education_form']['lbl_startyear']);?>
														<div class="fld">
														<?php						
																$education_start_year_attr = 'id="education_start_year"';
																$education_start_year_options = year_dropdown('- year -');
																echo form_dropdown('education_start_year', $education_start_year_options,'',$education_start_year_attr);
														?>
														
														<?php echo form_label('to:', 'education_grad_year', $opt['expertise_education_form']['lbl_gradyear']);?>
														<?php						
																$education_grad_year_attr = 'id="education_grad_year"';
																$education_grad_year_options = year_dropdown('- year -');
																echo form_dropdown('education_grad_year', $education_grad_year_options,'',$education_grad_year_attr);
														?>
														</div>
														
													</div>
													
													<div>
														<?php echo form_submit('submit', 'Save Education','class = "light_green no_margin_left"');?>
													</div>
													
												</div><!-- end .inner -->
												
											<?php echo form_close();?>
											
										</div><!-- end #education_add -->
										
									</div>
								
								</div>
						
						<?php if($users["membertype"] != '8'): ?>
									
								<div id="project-involvement" class="widgetbox" >
								
								
									<div class="title"><h3>User's Project Involvement</h3></div>
									<div class="widgetoptions">
									    <div class="right"><a href="/admin.php/projects/view_all_projects">View All Project</a></div>
									    <a href="/admin.php/projects/create">Add New Project</a>
									</div>
									<div class="widgetcontent userlistwidget nopadding">
									    <ul>
										
											<?php
											
											if($project['totalproj']> 0)
											{
												foreach($project['proj'] as $projkey=>$projval)
												{?>
												
										        <li style="min-height:54px;">
										            <div class="avatar">
										            <?php
														
														$imgurl = $projval["projectphoto"]!=""?$projval["projectphoto"]:"placeholder_project.jpg";
														$imgpath = $projval["projectphoto"]!=""?PROJECT_IMAGE_PATH:PROJECT_NO_IMAGE_PATH; 
										
										
														$image_properties = array(
														          'src' 	 => $imgpath.'50_50_'.$imgurl,
														          'alt' 	 => $projval['projectname'],
														          'class'	 => 'left img_border',
														          'width'	 => '48px'
														        );
														
														echo img($image_properties);
													?>
										            </div>
										            <div class="info">
										                <a href="/projects/<?php echo $projval['slug']; ?>"><?php echo $projval['projectname']; ?></a> <br><br>
										            </div><!--info-->
										            <div class="right">
											            <a class="open_project" href="/admin.php/projects/edit/<?php echo $projval['slug']; ?>">Edit Project</a>&nbsp;&nbsp; | &nbsp;&nbsp;
											            <a class="view" href="/projects/<?php echo $projval['slug']; ?>">View Project</a>
										            </div>
												</li>
										
												<?php } 
											}
											else
											{
												?>
												<li>
													<div class="clear">&nbsp;</div>
													<div align="center">No projects found to display.</div>
													<div class="clear">&nbsp;</div>
												</li>
												<?php
											}	
											?>
										</ul>
										<div class="more">&nbsp;</div>
									</div><!--widgetcontent-->
								</div>
								
						<?php endif; ?>

						<?php if($users["membertype"] == '8'): if( $non_experts ):

						?>

							<?php echo form_open('myaccount/update_seats/'.$users['uid'],array('id'=>'seat_uodate_form','name'=>'seat_uodate_form','method'=>'post','class'=>'ajax_form_')); ?>
								<div id="expert-advert-seats" class="widgetbox" >
								
								
									<div class="title"><h3>Experts Advert Seats</h3></div>
									

									<?php for ($i = 0; $i <= (int) $users['numberofseat'] - 1; $i++) {
									 	$c = $i + 1;
										$selected = isset($seats[$i]) ? $seats[$i]['uid'] : false;
									?>
										<p>
											<label for="seat_<?php echo $c ?>">Expert Seat <?php echo $c ?></label><br>
											<?php echo form_dropdown('seats[]', $non_experts, $selected, 'id="seat_'.$c.'" class="chzn-select"'); ?>
										</p>
									<?php } ?>
									<input type="submit" name="update" value="Update Seats" >
								</div>
							<?php echo form_close(); ?>
						<?php endif; endif; ?>
                    <?php if ($users['uid'] != sess_var('admin_uid')) { ?>
					<div id="account">
                        <?php $this->load->view('myaccount/_account', array('user' => $users)); ?>
                    </div>
                    <?php } ?>
                    <!--#tabs-->
                 </div><!--widgetcontent-->
            </div>
        </div><!--contentwrapper-->
        
	</div>
	 <?php if (! empty ($selectedtab))
	    { ?>
	        <script>
	        jQuery(function() {
		        jQuery("#tabs").tabs("select", "<?php echo $selectedtab ?>");
		    });
	        </script>
	 <?php } ?>

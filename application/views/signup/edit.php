<section class="main-content container">
    <h1 class="h1-xl">Add Profile Info</h1>
    <p>You're moving right along! Please add your profile information.</p>

    <?php $this->load->view('signup/_progress', array('step' => 'edit')) ?>

    <div class="form-cta">
        <div class="interior">

<!--            <h2 class="h3-std">Create Account</h2>-->
<!--            <div class="down-arrow"></div>-->
<!--            <p>Please add your profile information.</p>-->

            <?php echo form_open('', array('name' => 'signup_edit', 'class' => 'form')) ?>

                <div class="error-head"><?php if (! empty($error)) echo $error ?></div>

                <div class="anchor">
                    <label for="firstname" class="left_label">First Name:</label>
                    <input type="text" name="firstname" value="<?php echo set_value('firstname', $signup['firstname']) ?>" id="firstname" placeholder="">
                    <div class="errormsg"><?php echo form_error('firstname') ?></div>
                </div>

                <div class="anchor">
                    <label for="lastname" class="left_label">Last Name:</label>
                    <input type="text" name="lastname" value="<?php echo set_value('lastname', $signup['lastname']) ?>" id="lastname" placeholder="">
                    <div class="errormsg"><?php echo form_error('lastname') ?></div>
                </div>

                <div class="anchor">
                    <label for="title" class="left_label">Job Title:</label>
                    <input type="text" name="title" value="<?php echo set_value('title', $signup['title']) ?>" id="title" placeholder="">
                    <div class="errormsg"><?php echo form_error('title') ?></div>
                </div>

                <div class="anchor">
                    <label for="organization" class="left_label">Organization:</label>
                    <input type="text" name="organization" value="<?php echo set_value('organization', $signup['organization']) ?>" id="organization" placeholder="">
                    <div class="errormsg"><?php echo form_error('organization') ?></div>
                </div>

                <div class="anchor">
                    <label for="OrgStructure" class="left_label">Org Structure:</label>
                    <?php
                        $member_public_options = array(
                            ''          => lang('select'),
                            'public'    => lang('Public'),
                            'private'   => lang('Private')
                        );
                        echo form_dropdown('public_status', $member_public_options, $signup["public_status"], 'id="public_status"');
                    ?>
                    <div class="errormsg OrgStructure"><?php echo form_error('OrgStructure') ?></div>
                </div>


                    <div id="expertise_sector_form_div" class="clearfix">
                        <h4><?php echo lang('expertise').':';?></h4>
                        
                        
                        <div id="load_expertise_sector_form" class="clearfix">
                        
                        <?php 
                        if(count($sector) > 0)
                        {
                        foreach($sector as $key=>$sec)
                        {
                            
                            $editlink   = '/profile/form_load/expertise_sector_form/edit/'.$sec['id'];
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
                                        $project_sector_main_attr   = 'id="project_sector_main'.$sec["id"].'" onchange="sectorbind('.$sec["id"].');"';
                                        $sector_option = array();
                                        $sector_opt =array();
                                        foreach(sectors() as $key=>$value)
                                        {
                                            $sector_options[$value] = $value;
                                            $sector_opt[$value]     = 'class="sector_main_'.$key.'"';
                                        }
                                        $sector_first           = array('class'=>'hardcode','text'=>lang('SelectASector'),'value'=>'');
                                        $sector_last            = array();
                                        
                                        echo form_custom_dropdown('member_sector', $sector_options,$sec['sector'],$project_sector_main_attr,$sector_opt,$sector_first,$sector_last);
                                    ?>
                                    <div class="fld errormsg" style="clear:both;"></div>
                                    </div>
                                </div>
                                <div>
                                    <?php echo form_label(lang('Sub-Sector').':', 'project_sector_sub', $opt['expertise_sector_form']['lbl_sector_sub']);?>
                                    <div class="fld" id="dynamicSubsector_<?php echo $sec["id"];?>">
                                    <?php
                                        $project_sector_sub_attr        = 'id="project_sector_sub'.$sec["id"].'" class="project_sub"';
                                        $subsector_options  = array();
                                        $subsector_opt      = array();
                                        $selected_sector    = getsectorid("'".$sec['subsector']."'",1);
                                        
                                        foreach(subsectors() as $key=>$value)
                                        {
                                            foreach($value as $key2=>$value2)
                                            {
                                                if($key != $selected_sector)
                                                {
                                                    continue;
                                                }
                                                $subsector_options[$value2]     = $value2;
                                                $subsector_opt[$value2]         = 'class="project_sector_sub_'.$key.'"';
                                            }
                                        }
                                        $subsector_first            = array('class'=>'hardcode','text'=>lang('SelectASub-Sector'),'value'=>'');
                                        $subsector_last             = array('class'=>'hardcode','value'=>'Other','text'=>lang('Other'));
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
                                        'id'        => 'profile_sector_sub_other',
                                        'name'      => 'member_sub_sector_other',
                                        'value'     => '',
                                        'disabled'  => 'disabled'
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
                                            $project_sector_main_attr   = 'id="project_sector_main"';
                                            $sector_option = array();
                                            $sector_opt =array();
                                            foreach(sectors() as $key=>$value)
                                            {
                                                $sector_options[$value] = $value;
                                                $sector_opt[$value]     = 'class="sector_main_'.$key.'"';
                                            }
                                            $sector_first           = array('class'=>'hardcode','text'=>lang('SelectASector'),'value'=>'');
                                            $sector_last            = array();
                                            
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
                                            $project_sector_sub_attr        = 'id="project_sector_sub"';
                                            $subsector_options  = array();
                                            $subsector_opt      = array();
                                            foreach(subsectors() as $key=>$value)
                                            {
                                                foreach($value as $key2=>$value2)
                                                {
                                                    $subsector_options[$value2]     = $value2;
                                                    $subsector_opt[$value2]         = 'class="project_sector_sub_'.$key.'"';
                                                }
                                            }
                                            $subsector_first            = array('class'=>'hardcode','text'=>lang('SelectASub-Sector'),'value'=>'');
                                            $subsector_last             = array('class'=>'hardcode','value'=>'Other','text'=>lang('Other'));
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


                <div class="anchor">
                    <label for="country" class="left_label">Country:</label>
                    <?php echo form_dropdown('country', country_dropdown(), set_value('country', $signup['country']), 'id="country"') ?>
                    <div class="errormsg country"><?php echo form_error('country') ?></div>
                </div>

                <div class="anchor">
                    <label for="city" class="left_label">City:</label>
                    <input type="text" name="city" value="<?php echo set_value('city', $signup['city']) ?>" id="city" placeholder="">
                    <div class="errormsg"><?php echo form_error('city') ?></div>
                </div>

                <div class="anchor">
                    <label for="email" class="left_label">Email:</label>
                    <input type="email" name="email" value="<?php echo set_value('email', $signup['email']) ?>" id="email" placeholder="" >
                    <div class="errormsg" id="hint"><?php echo form_error('email') ?></div>
                </div>

                <div class="anchor">
                    <label for="password" class="left_label">Password:</label>
                    <input type="password" name="password" value="<?php echo set_value('password', $signup['password']) ?>" id="password" placeholder="" >
                    <div class="errormsg"><?php echo form_error('password') ?></div>
                </div>

                <div class="anchor">
                    <label for="password_confirmation" class="left_label">Confirm password:</label>
                    <input type="password" name="password_confirmation" value="<?php echo set_value('password_confirmation', $signup['password']) ?>" id="password_confirmation" placeholder="">
                    <div class="errormsg"><?php echo form_error('password_confirmation') ?></div>
                </div>

                <div class="form-buttons">
                    <a href="/signup" class="btn std clear">Back</a>
                    <input type="submit" name="submit" class="btn std dk-green" value="Next" />
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</section>
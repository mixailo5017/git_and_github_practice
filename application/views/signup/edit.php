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

                <div class="anchor">
                    <label for="sector" class="left_label">Sector:</label>
                    <?php 
                        $project_sector_main_attr   = 'id="sector"';
                        $sector_options = array();
                        $sector_opt = array();
                        foreach(sectors() as $key=>$value)
                        {
                            $sector_options[$value] = $value;
                            $sector_opt[$value]     = 'class="sector_main_'.$key.'"';
                        }
                        $sector_first           = array('class'=>'hardcode','text'=>lang('SelectASector'),'value'=>'');
                        //$sector_last          = array();
                        $sector_last            = array('class'=>'hardcode','text'=>'Other','value'=>'Other');
                        
                        echo form_custom_dropdown('sector', $sector_options,$signup["sector"],$project_sector_main_attr,$sector_first,$sector_last);
                    ?>
                    <div class="errormsg" id="err_project_sector"><?php echo form_error("sector"); ?></div>
                </div>

                <div class="anchor">
                    <label for="subsector" class="left_label">Subsector:</label>
                    <?php 
                        $project_sector_sub_attr    = 'id="project_sector_sub"';
                        $subsector_options = array();
                        $subsector_opt = array();
                        foreach(subsectors() as $key=>$value)
                        {
                            foreach($value as $key2=>$value2)
                            {
                                $subsector_options[$value2]     = $value2;
                                $subsector_opt[$value2]         = 'class="project_sector_sub_'.$key.'"';
                            }
                        }
                        $subsector_first            = array('class'=>'hardcode','text'=>lang('SelectASub-Sector'),'value'=>'');
                        $subsector_last             = array('class'=>'hardcode','value'=>'Other','text'=>'Other');
                        echo form_custom_dropdown('subsector', $subsector_options,$signup["subsector"],$project_sector_sub_attr,$subsector_opt,$subsector_first,$subsector_last);
                    ?>
                    <div class="errormsg" id="err_project_subsector"><?php echo form_error("subsector"); ?></div>
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
                    <div class="errormsg" id="suggestion"><?php echo form_error('email') ?></div>
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
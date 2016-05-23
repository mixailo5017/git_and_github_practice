<section class="main-content container">
    <h1 class="h1-xl">Add Profile Info</h1>
    <p>You're moving right along! Please share a little about yourself (all fields mandatory).</p>

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
                    <input type="text" name="firstname" value="<?php echo set_value('firstname', $signup['firstname']) ?>" id="firstname" placeholder="" data-validation="[NOTEMPTY]" data-validation-label="First Name" required>
                    <div class="errormsg"><?php echo form_error('firstname') ?></div>
                </div>

                <div class="anchor">
                    <label for="lastname" class="left_label">Last Name:</label>
                    <input type="text" name="lastname" value="<?php echo set_value('lastname', $signup['lastname']) ?>" id="lastname" placeholder="" data-validation="[NOTEMPTY]" data-validation-label="Last Name" required>
                    <div class="errormsg"><?php echo form_error('lastname') ?></div>
                </div>

                <div class="anchor">
                    <label for="email" class="left_label">Email:</label>
                    <input type="email" name="email" value="<?php echo set_value('email', $signup['email']) ?>" id="email" placeholder="" data-validation="[NOTEMPTY, EMAIL]" data-validation-label="Email" required>
                    <div class="errormsg">
                        <?php echo form_error('email') ?>
                        <label id="company-hint"></label>
                        <label id="spelling-hint"></label>
                    </div>
                </div>
                
                <div class="anchor">
                    <label for="discipline" class="left_label">Discipline:</label>
                    <?php
                        $discipline_attr = 'id="discipline" required data-validation="[NOTEMPTY]" data-validation-label="Discipline"';
                        $discipline_options =  discipline_dropdown();
                        echo form_dropdown('discipline', $discipline_options,set_value('discipline', $signup['discipline']),$discipline_attr);
                    ?>
                    <div class="errormsg dropdown"><?php echo form_error('discipline') ?></div>
                </div>

                <div class="anchor">
                    <label for="project_sector_sub" class="left_label">Sector(s):</label>
                    <?php
                        $project_sector_sub_attr        = 'id="project_sector_sub_select2" multiple="multiple" required data-validation="[NOTEMPTY]" data-validation-label="Sector(s)"';
                        
                        $sector_option = array();
                        $sector_opt =array();
                        foreach(sectors() as $key=>$value)
                        {
                            $sector_option[$value] = $value;
                            $sector_opt[$value]     = 'class="sector_main_'.$key.'"';
                        }


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
                        $last = array('class'=>'hardcode','value'=>'Other','text'=>lang('Other'));

                        echo form_category_dropdown('sub-sector[]', $sector_option, $subsector_options, $signup['sub-sector'], $project_sector_sub_attr,$sector_opt,$subsector_opt, $last);
                    ?>
                    <div class="errormsg dropdown"><?php echo form_error('sub-sector[]') ?></div>
                </div>

                <div class="anchor">
                    <label for="title" class="left_label">Job Title:</label>
                    <input type="text" name="title" value="<?php echo set_value('title', $signup['title']) ?>" id="title" placeholder="" required data-validation="[NOTEMPTY]" data-validation-label="Job Title">
                    <div class="errormsg"><?php echo form_error('title') ?></div>
                </div>

                <div class="anchor">
                    <label for="organization" class="left_label">Organization:</label>
                    <input type="text" name="organization" value="<?php echo set_value('organization', $signup['organization']) ?>" id="organization" placeholder="" required data-validation="[NOTEMPTY]" data-validation-label="Organization">
                    <div class="errormsg"><?php echo form_error('organization') ?></div>
                </div>

                <div class="anchor">
                    <label for="public_status" class="left_label">Org Structure:</label>
                    <?php
                        $member_public_options = array(
                            ''          => lang('select'),
                            'public'    => lang('Public'),
                            'private'   => lang('Private')
                        );
                        echo form_dropdown('public_status', $member_public_options, set_value('public_status', $signup['public_status']), 'id="public_status" required data-validation="[NOTEMPTY]" data-validation-label="Organization Structure"');
                    ?>
                    <div class="errormsg dropdown"><?php echo form_error('public_status') ?></div>
                </div>

                <div class="anchor">
                    <label for="city" class="left_label">City:</label>
                    <input type="text" name="city" value="<?php echo set_value('city', $signup['city']) ?>" id="city" placeholder="" required data-validation="[NOTEMPTY]" data-validation-label="City">
                    <div class="errormsg"><?php echo form_error('city') ?></div>
                </div>

                <div class="anchor">
                    <label for="country" class="left_label">Country:</label>
                    <?php echo form_dropdown('country', country_dropdown(), set_value('country', $signup['country']), 'id="country" required data-validation="[NOTEMPTY]" data-validation-label="Country"') ?>
                    <div class="errormsg dropdown"><?php echo form_error('country') ?></div>
                </div>

                <div class="anchor">
                    <label for="password" class="left_label">Password:</label>
                    <input type="password" name="password" value="<?php echo set_value('password', $signup['password']) ?>" id="password" placeholder="" required pattern=".{6,}" title="Let's keep you safe! Please use at least six characters." data-validation="[L>=6]" data-validation-label="Password">
                    <div class="errormsg"><?php echo form_error('password') ?></div>
                </div>

                <div class="anchor">
                    <label for="password_confirmation" class="left_label">Confirm password:</label>
                    <input type="password" name="password_confirmation" value="<?php echo set_value('password_confirmation', $signup['password']) ?>" id="password_confirmation" placeholder="" required pattern=".{6,}" data-validation="[V==password]" data-validation-label="Password Confirmation">
                    <div class="errormsg"><?php echo form_error('password_confirmation') ?></div>
                </div>

                <div class="form-buttons">
                    <a href="/signup" class="btn std clear">Back</a>
                    <input type="submit" name="btnSubmit" class="btn std dk-green" value="Next" />
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</section>
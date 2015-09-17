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
                    <label for="country" class="left_label">Country:</label>
                    <?php echo form_dropdown('country', country_dropdown(), set_value('country', $signup['country']), 'id="country"') ?>
                    <div class="errormsg country"><?php echo form_error('country') ?></div>
                </div>

                <div class="anchor">
                    <label for="email" class="left_label">Email:</label>
                    <input type="email" name="email" value="<?php echo set_value('email', $signup['email']) ?>" id="email" placeholder="" >
                    <div class="errormsg"><?php echo form_error('email') ?></div>
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
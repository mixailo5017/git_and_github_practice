<section class="main-content container">
    <h1 class="h1-xl">Let's reset your password</h1>
    <p>Please provide the email that you used when you signed up for your GViP account and a new password.</p>
    <div class="form-cta">
        <div class="interior">
<!--            <h2 class="h3-std">Reset the password</h2>-->
<!--            <div class="down-arrow"></div>-->
            <?php echo form_open('', array('name' => 'reset_password_form', 'class' => 'form')) ?>
            <input type="hidden" name="token" value="<?php echo $token ?>">

            <div class="error-head"><?php if (! empty($error)) echo $error ?></div>

            <div class="anchor">
                <label for="email" class="left_label">Email:</label>
                <input type="text" name="email" value="<?php echo set_value('email', '') ?>" id="email" placeholder="" />
                <div class="errormsg"><?php echo form_error('email') ?></div>
            </div>
            <div class="anchor">
                <label for="password" class="left_label">New password:</label>
                <input type="password" name="password" value="<?php echo set_value('password', '') ?>" id="password" placeholder="" />
                <div class="errormsg"><?php echo form_error('password') ?></div>
            </div>
            <div class="anchor">
                <label for="password_confirmation" class="left_label">Password confirmation:</label>
                <input type="password" name="password_confirmation" value="<?php echo set_value('password_confirmation', '') ?>" id="password_confirmation" placeholder="" />
                <div class="errormsg"><?php echo form_error('password_confirmation') ?></div>
            </div>
            <div class="sign-in-meta">
                <div class="sign-in-buttons">
                    <input type="submit" value="Reset password" class="btn std lt-blue">
                </div>
            </div>
            <?php echo form_close() ?>
        </div>
    </div>
</section>
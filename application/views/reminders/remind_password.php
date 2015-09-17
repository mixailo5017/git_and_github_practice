<section class="main-content container">
    <h1 class="h1-xl">Forgot your password?</h1>
    <p>Please provide the email that you used when you signed up for your GViP account.<br>
        We will send you a message that will allow you to reset your password.</p>

    <div class="form-cta">
        <div class="interior">
<!--            <h2 class="h3-std">Change the password</h2>-->
<!--            <div class="down-arrow"></div>-->
            <?php echo form_open('', array('name' => 'remind_password_form', 'class' => 'form')) ?>
            <div class="error-head"><?php if (! empty($error)) echo $error ?></div>
            <div class="anchor">
                <label for="email" class="left_label">Email:</label>
                <input type="text" name="email" value="<?php echo set_value('email', '') ?>" placeholder="" />
                <div class="errormsg"><?php echo form_error('email'); ?></div>
            </div>
            <div class="sign-in-meta">
                <div class="sign-in-buttons">
                    <input type="submit" value="Send verification email" class="btn lt-blue">
                </div>
            </div>
            <?php echo form_close() ?>
        </div>
    </div>
</section>
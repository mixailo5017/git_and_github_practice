<section class="main-content container">
    <h1 class="h1-xl">Let's Get Started</h1>
    <p>First we need a little bit of information from you.</p>
<!--        <br>You can pull your email and info from LinkedIn or type it in.-->

    <?php $this->load->view('signup/_progress', array('step' => 'start')) ?>

    <div class="form-cta">
        <div class="interior">
<!--            <h2 class="h3-std">Create Account</h2>-->
<!--            <div class="down-arrow"></div>-->
<!--            <p>Please select your method of input.</p>-->
            <?php echo form_open('', array('name' => 'signup_start', 'class' => '')) ?>
                <div class="error-head"><?php if (! empty($error)) echo $error ?></div>

                <h2 class="h3-std">Are you a project developer?</h2>
                <div class="reg-start">
                    <label>
                        <span class="input"><input type="radio" name="is_developer" value="1" <?php echo set_radio('is_developer', '1', $signup['is_developer'] == true) ?> />
                        </span> <span class="dialogue">Yes. I belong to a project owner organization and manage one or more projects.</span>
                    </label>
                    <br>
                    <label>
                        <span class="input"><input type="radio" name="is_developer" value="0" <?php echo set_radio('is_developer', '0', $signup['is_developer'] == false) ?> />
                        </span> <span class="dialogue">No. I help make projects happen from the other side.</span>
                    </label>
                    <div class="errormsg"><?php echo form_error('is_developer') ?></div>
                </div>
                <br>
                <p>Everyone gets the full GViP experience.<br>Understanding your role helps us customize GViP to your needs.</p>
                <div class="form-buttons centered">
                    <input type="submit" name="manual" class="btn std dk-green" value="Continue" />
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</section>

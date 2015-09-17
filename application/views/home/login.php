<section class="main-content container">
    <h1 class="h1-xl">Sign In</h1>
<!--    <p>Welcome to GViP, a tool for project developers and industry experts alike, to access the global expertise and resources they need, right when they need them.</p>-->

    <div class="form-cta">
        <div class="interior">
<!--            <h2 class="h3-std">Sign in to GViP</h2>-->
<!--            <div class="down-arrow"></div>-->
            <?php $action = empty($_SERVER['QUERY_STRING']) ? current_url() : current_url() . '?' . $_SERVER['QUERY_STRING'];
            echo form_open($action, array('name' => 'login_form', 'class' => 'form')) ?>
                <div class="error-head"><?php if (! empty($error)) echo $error ?></div>
                <div class="anchor">
                    <label for="email" class="left_label">Email:</label>
                    <input type="email" name="email" value="<?php echo set_value('email', '') ?>" placeholder="" />
                    <div class="errormsg"><?php echo form_error('email') ?></div>
                </div>
                <div class="anchor">
                    <label for="password" class="left_label">Password:</label>
                    <input type="password" name="password" value="<?php echo set_value('password', '') ?>" placeholder="" />
                    <div class="errormsg"><?php echo form_error('password') ?></div>
                </div>
                <div class="form-meta">
                    <div class="remember"><input type="checkbox" name="remember" <?php echo set_checkbox('remember', 'checked', true) ?> />Remember Me</div>
                    <div class="forgot"><a href="/password/remind">Forgot your password?</a></div>
                    <div class="form-buttons">
                        <input type="submit" value="Sign In" class="btn std lt-blue">
                    </div>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</section>
<form action="" method="post">
    <div id="div_general_photo_form" style="width:50%">
        <div class="contenttitle2">
            <h3>General</h3>
        </div>
        <div class="field">Email: <?php echo $user['email'] ?></div>
        <div class="field">Joined: <?php echo format_date($user['registerdate'], 'm/d/Y') ?></div>
        <div class="field">Last login: <?php echo ! empty($user['lastlogin']) ? date('m/d/Y H:i:s', (int) $user['lastlogin']) : 'Haven\'t logged in yet.'?></div>
    </div>
    <div id="div_general_photo_form" style="width:50%">
        <div class="contenttitle2">
            <h3>Reset Password and (or) Email</h3>
        </div>

        <div class="field">
            <label for="password" class="left_label">New Password</label>
            <div class="fld">
                <input type="password" name="password" id="password" value="<?php echo form_error('password') || form_error('password_confirmation') ? set_value('password') : '' ?>">
                <div class="errormsg"><?php echo form_error('password') ?></div>
            </div>
        </div>

        <div class="field">
            <label for="password_confirmation" class="left_label">Verify Password</label>
            <div class="fld">
                <input type="password" name="password_confirmation" id="password_confirmation" value="<?php echo form_error('password') || form_error('password_confirmation') ? set_value('password_confirmation') : '' ?>">
                <div class="errormsg"><?php echo form_error('password_confirmation') ?></div>
            </div>
        </div>

        <br>

        <div class="field">
            <label for="email" class="left_label">New Email</label>
            <div class="fld">
                <input type="text" name="email" id="email" value="<?php echo form_error('email') ? set_value('email') : '' ?>">
                <div class="errormsg"><?php echo form_error('email') ?></div>
            </div>
        </div>

        <div>
            <input type="submit" name="reset" value="Reset" class="light_green no_margin_left"
                   onClick="return confirm('CAUTION: You may make the account inaccesible for the end user!\n\nAre you sure you want to proceed?')">
        </div>
    </div>
</form>

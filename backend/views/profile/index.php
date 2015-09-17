<div class="centercontent">
    <div class="pageheader notab">
        <h1 class="pagetitle">My Profile</h1>
        <span class="pagedesc">&nbsp;</span>
    </div>

    <div id="contentwrapper" class="contentwrapper">
        <form action="" method="post">
            <div id="div_general_photo_form" style="width:50%">
                <div class="contenttitle2">
                    <h3>General</h3>
                </div>

                <div class="field">Email: <?php echo $user['email'] ?></div>

                <div class="field">
                    <label for="firstname" class="left_label">First Name</label>
                    <div class="fld">
                        <input type="text" name="firstname" id="firstname" value="<?php echo set_value('firstname', $user['firstname']) ?>">
                        <div class="errormsg"><?php echo form_error('firstname') ?></div>
                    </div>
                </div>

                <div class="field">
                    <label for="lastname" class="left_label">Last Name</label>
                    <div class="fld">
                        <input type="text" name="lastname" id="lastname" value="<?php echo set_value('lastname', $user['lastname']) ?>">
                        <div class="errormsg"><?php echo form_error('lastname') ?></div>
                    </div>
                </div>

                <div>
                    <input type="submit" name="update" value="Update" class="light_green no_margin_left">
                </div>
            </div>

            <div id="div_general_photo_form" style="width:50%">
                <div class="contenttitle2">
                    <h3>Reset Password and (or) Email</h3>
                </div>

                <div class="field">
                    <label for="current_password" class="left_label">Current Password</label>
                    <div class="fld">
                        <input type="password" name="current_password" id="current_password" value="">
                        <div class="errormsg"><?php echo form_error('current_password') ?></div>
                    </div>
                </div>

                <div class="field">
                    <label for="password" class="left_label">New Password</label>
                    <div class="fld">
                        <input type="password" name="password" id="password" value="<?php echo set_value('password') ?>">
                        <div class="errormsg"><?php echo form_error('password') ?></div>
                    </div>
                </div>

                <div class="field">
                    <label for="password_confirmation" class="left_label">Verify Password</label>
                    <div class="fld">
                        <input type="password" name="password_confirmation" id="password_confirmation" value="<?php echo set_value('password_confirmation') ?>">
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
                    <input type="submit" name="reset" value="Reset" class="light_green no_margin_left">
                </div>
            </div>
        </form>
    </div>
</div>

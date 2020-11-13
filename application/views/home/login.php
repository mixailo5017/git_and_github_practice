<style>
    body {
        background: white;
    }

    .main-content {
        /* background: white; */
        min-height: 50em;
        background-image: url('https://d2huw5an5od7zn.cloudfront.net/1039');
        background-size: cover;
        background-size: 100%;
        background-repeat: no-repeat;
        bottom: 0;
        height: 90vh;
        margin-bottom: 2.5em;
    }

    .page_row {

        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-direction: row !important;

    }

    .col {

        margin: 0 3em;
    }


    #form {
        margin-top: 4em;
        width: 28%;

    }

    .form-ct {
        background: white;
        border: 0.1px solid rgba(28, 28, 28, 0.1) !important;
        border-radius: 10px;
        box-shadow: 0 4px 5px 0 rgba(0, 0, 0, 0.14), 0 1px 10px 0 rgba(0, 0, 0, 0.12),
            0 2px 4px -1px rgba(0, 0, 0, 0.3);
    }

    .guest-btn {
        margin-top: 2em;
        margin-left: 10%;
        width: 80%;
        border: 1px solid #258184;
        color: white;
        background: #5dc1f5;
        padding: 1.1em 0 0.8em 0;
        font-size: 1em;
        font-weight: 600;
        border-radius: 5px;
    }

    .guest-btn:hover {
        background: #4793bb;
        transition: 300ms;
        cursor: pointer;
    }

    .header {
        font-size: 2rem;
        margin: 0.5em 0;
        font-weight: lighter !important
    }



    .interior {
        border: none !important;
    }

    .anchor {
        display: flex !important;
        flex-direction: column;
        justify-content: space-between;
        align-items: flex-start;
    }

    .anchor * {
        width: 100% !important;
        margin: 0.3em 0;
    }

    .anchor label {
        text-align: start;
        font-size: 1.2rem;
    }

    .anchor input {
        border-radius: 7px !important;
        font-size: 1.1rem;
    }

    .form-meta {
        margin: 0 !important;
        width: 100% !important;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: flex-start;
    }


    .form-check {
        width: 100% !important;
        border: 1px solid black;
    }

    .form-buttons * {
        width: 100%;
    }

    .remember {
        display: flex !important;
        width: 100% !important;
        justify-content: flex-start;
        align-items: flex-end;
    }

    .remember input {
        border: 1px solid black;
        height: 22px;
        width: 22px;
    }

    .remember label {
        text-align: left;
        flex-basis: 80% !important;
        font-size: 1.6rem;
        cursor: pointer;
    }

    .forgot {
        margin-top: 0.5rem;
    }

    @media only screen and (max-width:852px) {
        .main-content {
            background: white;
            min-height: 50em;

        }

        .page_row {
            display: flex !important;
            align-items: center;
            flex-direction: column !important;


        }

        .login-text__container {
            align-self: center !important;
            width: initial !important;
        }

        .login-text__head {

            line-height: 1.3;
        }

        .login-text__head {
            font-size: 1.8rem !important;
            font-weight: lighter !important;
        }


        #form {
            margin-top: 4em;
            min-width: 28vw;
            width: 90%;

        }

        .form-ct {
            background: white;
            border: 0.1px solid rgba(28, 28, 28, 0.1) !important;
            border-radius: 10px;
            box-shadow: 0 4px 5px 0 rgba(0, 0, 0, 0.14), 0 1px 10px 0 rgba(0, 0, 0, 0.12),
                0 2px 4px -1px rgba(0, 0, 0, 0.3);
        }

    }
</style>

<section class="main-content ">

    <div class="container-fluid">
        <div class="page_row">
            <div class="col ">
                <div class="img_container">
                    <img src="https://d2huw5an5od7zn.cloudfront.net/GViP_newlogo.png" alt="">
                </div>
            </div>

            <div id="form" class="col">

                <div class="form-ct">
                    <div class="header">
                        SIGN IN
                    </div>
                    <div class="interior">

                        <?php $action = empty($_SERVER['QUERY_STRING']) ? current_url() : current_url() . '?' . $_SERVER['QUERY_STRING'];
                        echo form_open($action, array('name' => 'login_form', 'class' => 'form')) ?>
                        <div class="error-head"><?php if (!empty($error)) echo $error ?></div>
                        <div class="anchor">
                            <label for="email" class="left_label">Email:</label>
                            <input type="email" class="input-field" name="email" value="<?php echo set_value('email', '') ?>" placeholder="" />
                            <div class="errormsg"><?php echo form_error('email') ?></div>
                        </div>
                        <div class="anchor">
                            <label for="password" class="left_label">Password:</label>
                            <input type="password" class="input-field" name="password" value="<?php echo set_value('password', '') ?>" placeholder="" />
                            <div class="errormsg"><?php echo form_error('password') ?></div>
                        </div>

                        <div class="form-meta">

                            <div class="remember">
                                <input type="checkbox" id="remember" name="remember" <?php echo set_checkbox('remember', 'checked', true) ?> />
                                <label for="remember" for="remember">Remember Me</label>
                            </div>


                            <div class="form-buttons">
                                <input type="submit" value="Sign In" class="btn std lt-blue">
                            </div>
                        </div>
                        <div class="forgot"><a href="/password/remind">Forgot your password?</a></div>
                        <?php echo form_close() ?>
                    </div>
                </div>

                <div class="guest-btn">
                    Continue as Guest
                </div>
            </div>


</section>
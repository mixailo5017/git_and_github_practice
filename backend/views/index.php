<?php echo doctype("html5"); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php 
		$meta = array(
			array("name"=>"Content-type","content"=>"text/html; charset=utf-8","type"=>"equiv"),
			array("name"=>"viewport","content"=>"width=device-width, initial-scale=1.0")
			);
		echo meta($meta); 
	?>
	<title><?php echo $headerdata['title'] ?></title>
	<?php echo link_tag('themes/css/style.default.css'); ?>
	<script type="text/javascript" src="/themes/js/plugins/jquery-1.7.min.js"></script>
	<script type="text/javascript" src="/themes/js/plugins/jquery-ui-1.8.16.custom.min.js"></script>
	<script type="text/javascript" src="/themes/js/plugins/jquery.cookie.js"></script>
	<script type="text/javascript" src="/themes/js/plugins/jquery.uniform.min.js"></script>
	<script type="text/javascript" src="/themes/js/custom/general.js"></script>
	<script type="text/javascript" src="/themes/js/custom/index.js"></script>
	<!--[if IE 9]>
		<?php echo link_tag(array('href'=>'themes/css/style.ie9.css','rel'=>'stylesheet','media'=>'screen'));	?>
	<![endif]-->
	<!--[if IE 8]>
		<?php echo link_tag(array('href'=>'themes/css/style.ie8.css','rel'=>'stylesheet','media'=>'screen'));	?>
	<![endif]-->
	<!--[if lt IE 9]>
		<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
	<![endif]-->
</head>

<body class="<?php echo $headerdata['bodyclass']; ?>">
	<div class="loginbox">
    	<div class="loginboxinner">
        	
            <div class="logo">
            	<h1><span>GViP Admin Interface</span></h1>
                <p>Sign in using your <strong>admin</strong> user account.</p>
            </div><!--logo-->
            
            <br clear="all" /><?php echo br(); ?>
            
            <div class="nousername">
				<div class="loginmsg">The password you entered is incorrect.</div>
            </div><!--nousername-->
            
            <div class="nopassword">
				<div class="loginmsg">The password you entered is incorrect.</div>
                <div class="loginf">
                    <div class="thumb">
                    	<?php echo img(array("src"=>"themes/images/thumbs/avatar1.png","alt"=>"")); ?>
                    </div>
                    <div class="userlogged">
                        <h4></h4>
                        <a href="<?php echo index_page(); ?>">Not <span></span>?</a> 
                    </div>
                </div><!--loginf-->
            </div><!--nopassword-->

            <?php if ($login_failed) { ?>
            <div class="loginmsg">The existing username and/or password are invalid.</div>
            <?php } ?>
            
            <?php echo form_open('', array('id' => 'login', 'name' => 'login'));
				$opt = array(
					'username' => array(
		              'name'        => 'username',
		              'id'			=> 'username',
		              'value'       => set_value('username'),
		              'maxlength'   => '100',
		              'size'        => '50',
		              'placeholder' => 'Username'
	            	),
	            	'password' => array(
		              'name'        => 'password',
		              'id'			=> 'password',
		              'value'       => set_value('password'),
		              'maxlength'   => '100',
		              'size'        => '50',
		              'placeholder' => 'Password'
	            	),
	            	'login' => array(
	            		'name' 		=> 'signin',
	            		'id'		=> 'signin',
	            		'value'		=> 'Sign In',
	            		'content'	=> 'Sign In'
	            	),
	            	'keep_logged' => array(
	            		'name'		=> 'keep_logged_in',
	            		'value'		=> '1',
	            		'id'		=> 'keep_logged_in'
	            	)
					);
		   ?>
            	
                <div class="username">
                	<div class="usernameinner">
                    	<?php echo form_input($opt['username']);?>
                    </div>
                </div>
                <div class="errormsg" id="err_username">
					<?php echo form_error('username'); ?>
				</div>
                
                <div class="password">
                	<div class="passwordinner">
                    	<?php echo form_password($opt['password']);?>
                    </div>
                </div>
                <div class="errormsg" id="err_password">
					<?php echo form_error('password'); ?>
					<div class="clear">&nbsp;</div>
				</div>
                
                <button>Sign In</button>
                <?php //echo form_button($opt["login"]);?>
                
                <div class="keep" style="display:none"><?php echo form_checkbox($opt["keep_logged"]);?> Keep me logged in</div>
            
            <?php form_close();?>
            
        </div><!--loginboxinner-->
    </div><!--loginbox-->
</body>
</html>
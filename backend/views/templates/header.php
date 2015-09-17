<!DOCTYPE html>
<!--[if IE 9]><html class="ie9"> <![endif]-->
<!--[if gt IE 9]><!--> <html> <!--<![endif]-->
<head>
	<?php 
		$meta = array(
			array("name"=>"Content-type","content"=>"text/html; charset=utf-8","type"=>"equiv"),
			array("name"=>"viewport","content"=>"width=device-width, initial-scale=1.0")
			);
		echo meta($meta); 
	?>
	<title><?php echo $title; ?></title>
	<?php echo link_tag('themes/css/style.default.css'); ?>
	<script type="text/javascript" src="/themes/js/plugins/jquery-1.7.min.js"></script>
	<script type="text/javascript" src="/themes/js/plugins/jquery-ui-1.8.16.custom.min.js"></script>
	<script type="text/javascript" src="/themes/js/plugins/jquery.cookie.js"></script>
	<script type="text/javascript" src="/themes/js/plugins/jquery.uniform.min.js"></script>
	<?php if (isset($js) && count($js) > 0) {
		foreach($js as $js) { ?>
	    <script type="text/javascript" src="<?php echo $js; ?>"></script>
        <?php }
	} ?>
	<script type="text/javascript" src="/themes/js/custom/general.js"></script>
	<?php
	if (isset($pagejs) AND count($pagejs) > 0) {
		foreach($pagejs as $pagejs) { ?>
	    <script type="text/javascript" src="<?php echo $pagejs; ?>"></script>
	    <?php }
	} ?>
	<?php if (isset($conditionaljs) && count($conditionaljs) > 0) {
		foreach($conditionaljs as $key=>$val) { ?>
	<!--<?php echo $key; ?>><script type="text/javascript" src="<?php echo $val; ?>"></script><![endif]-->
    	<?php }
	} ?>
	
	<!--[if IE 9]>
		<?php echo link_tag(array('href'=>'themes/css/style.ie9.css','rel'=>'stylesheet','media'=>'screen'));	?>
	<![endif]-->
	<!--[if IE 8]>
		<?php echo link_tag(array('href'=>'themes/css/style.ie8.css','rel'=>'stylesheet','media'=>'screen'));	?>
	<![endif]-->
	<!--[if lt IE 9]>
		<script src="://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
	<![endif]-->
</head>

<body class="withvernav">

<div class="bodywrapper">
    <div class="topheader">
        <div class="left">
        	<h1 class="logo">VIP</h1>
            <span class="slogan">My VIP Workplace Admin</span>
            <br clear="all" />
        </div><!--left-->
        
        <div class="right">
        	<div class="notification" style="display:none;">
                <a class="count" href="javascript:void(0)"><span>9</span></a>
        	</div>
            
            <?php $userinfo = get_logged_userinfo(sess_var("admin_uid")); ?>
            
            <div class="userinfo">
                <img alt="<?php echo sess_var("admin_name");?>'s photo" src="<?php echo expert_image($userinfo["userphoto"], 27); ?>" width="27">
            	
                <span><?php echo $userinfo['firstname'] . ' ' . $userinfo['lastname'] ?></span>
            </div><!--userinfo-->

            <div class="userinfodrop">
            	<div class="avatar">
                	<a href="">
                        <img alt="<?php echo sess_var("admin_name");?>'s photo" src="<?php echo expert_image($userinfo["userphoto"], 90); ?>" width="27">
                    </a>
                    <div class="changetheme">
                    	Change theme:
                        <br>
                    	<a class="default"></a>
                        <a class="blueline"></a>
                        <a class="greenline"></a>
                        <a class="contrast"></a>
                        <a class="custombg"></a>
                    </div>
                </div><!--avatar-->
				<div class="userdata">
                    <h4><?php echo $userinfo['firstname'] . ' ' . $userinfo['lastname'] ?></h4>
                    <span class="email" style="min-width:150px;"><?php echo $userinfo['email'] ?></span>

                    <ul>
                        <li><a href="/admin.php/profile">My Profile</a></li>
                        <li><a href="/admin.php/dashboard/logout">Sign Out</a></li>
<!--                    <li><a href="/admin.php/myaccount/--><?php //echo $userinfo['uid'] ?><!--">Edit Profile</a></li>-->
                    </ul>
                </div><!--userdata-->
            </div><!--userinfodrop-->
        </div><!--right-->
    </div><!--topheader-->

    <div class="header">
    	<ul class="headermenu">
        	<li <?php if($this->uri->segment(1) == "dashboard") { ?> class="current" <?php } ?>><a href="/admin.php/dashboard"><span class="icon icon-flatscreen"></span>Dashboard</a></li>
            <li <?php if($this->uri->segment(1) == "members") { ?> class="current" <?php } ?>><a href="/admin.php/members"><span class="icon icon-pencil"></span>Members</a></li>
            <li <?php if($this->uri->segment(1) == "projects") { ?> class="current" <?php } ?>><a href="/admin.php/projects/view_all_projects"><span class="icon icon-project"></span>Projects</a></li>
            <li <?php if($this->uri->segment(1) == "forums") { ?> class="current" <?php } ?>><a href="/admin.php/forums"><span class="icon icon-project"></span>Forums</a></li>
            <li <?php if($this->uri->segment(1) == "store") { ?> class="current" <?php } ?>><a href="/admin.php/store"><span class="icon icon-project"></span>Store</a></li>
            <li <?php if($this->uri->segment(1) == "updates") { ?> class="current" <?php } ?>><a href="/admin.php/updates"><span class="icon icon-speech"></span>Comments</a></li>
            <li <?php if($this->uri->segment(1) == "discussions") { ?> class="current" <?php } ?>><a href="/admin.php/discussions"><span class="icon icon-speech"></span>Discussions</a></li>

            <?php if (false) { // Hide reports for now ?>
            <li <?php if($this->uri->segment(1) == "googleapi") { ?> class="current" <?php } ?>><a href="/admin.php/googleapi/reports"><span class="icon icon-chart"></span>Reports</a></li>
            <?php } ?>
        </ul>
    </div><!--header-->

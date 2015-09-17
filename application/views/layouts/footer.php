<footer class="main-footer">
	<div class="container">
	    <div class="logo-cgla">
	    <a href="http://cg-la.com"><img src="/images/new/cgla-footer-logo.png" width="74" height="29" /></a></div>
	    <address>
	        <p><?php echo SITE_NAME ?> &reg; is a registered trademark of <a href="<?php echo CGLA_SITE ?>"> <?php echo CGLA_NAME ?></a> &copy; <?php echo date('Y') ?> <br />
                <?php if (! App::is_down_for_maintenence() || App::is_ip_allowed_when_down()) { ?>
                <a href="/terms">Terms Of Service</a> | <a href="/privacy">Privacy Policy</a> |
                <?php } ?>
                <a href="//store.globalvipprojects.com" target="_blank">GViP Store</a>
            </p>
	    </address>
	    <div class="logo-gvip"><a href="/"><img src="/images/new/logo-invert.png" /></a></div>
	</div>
</footer>

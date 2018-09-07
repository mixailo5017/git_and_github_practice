	<?php if (logged_in()) {
        // $this->load->view('templates/_concierge');
	} ?>

	<footer>
		<p><?php echo SITE_NAME . ' &reg; ' . lang('copyright') ?> <a href="<?php echo CGLA_SITE ?>"> <?php echo CGLA_NAME ?></a> &copy; <?php echo date('Y') ?> | <a href="/terms"><?php echo lang('TermsOfService') ?></a> | <a href="/privacy"><?php echo lang('PrivacyPolicy') ?></a></p>
	</footer>

    </div> <!-- wrapper -->

	<script>
		 var lang = new Array();
		 <?php foreach ($lang['js'] as $key => $val) { ?>
				lang['<?php echo $key ?>'] = "<?php echo addslashes($val);?>";
		 <?php }
		 
		 // Give JS the Mapbox access token, required on many different pages across the site ?>
	     GVIP.mapBoxAccessToken = '<?php echo $this->config->item('mapbox')['access_token'] ?>';
	</script>

	<?php // Data to provide to JavaScript ?>
	<?php $this->load->view('templates/_js_searchbox') ?>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="/js/jquery-1.7.1.min.js"><\/script>')</script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
	<script>window.jQuery.ui || document.write('<script src="/js/jquery-ui-1.8.18.min.js"><\/script>')</script>
	<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=mai7xr5o658n95pijgmghfedm0228bki140we2d11pevudr5"></script> 
	<script type="text/javascript">
		setTimeout(function(){var a=document.createElement("script");
		var b=document.getElementsByTagName("script")[0];
		a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0018/3749.js?"+Math.floor(new Date().getTime()/3600000);
		a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
	</script>

	<script type="text/javascript" src="/js/plugins.js<?php echo asset_version('plugins.js') ?>"></script>
	<script type="text/javascript" src="/js/script.js<?php echo asset_version('script.js') ?>"></script>

	<?php if (isset($footer_extra)) echo $footer_extra; ?>

</body>
</html>

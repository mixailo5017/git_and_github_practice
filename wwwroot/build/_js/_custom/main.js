$(function() {
	// prettyphoto provides photo/video overlays on How To page 
	var $prettyPhoto = $("a[rel^='prettyPhoto']");
	if ($prettyPhoto.length > 0) {
		$("a[rel^='prettyPhoto']").prettyPhoto({social_tools:""});
	}

	require('./_nav_mobile.js')();

	require('./_searchbox.js')();
});

global.changeLanguage = require('./_changeLanguage.js');
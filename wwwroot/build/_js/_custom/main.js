global.$ = global.jQuery = require("jquery");

$(function() {
	
	require('./_nav_mobile.js')();

	require('./_searchbox.js')();

	var magnific = require('magnific-popup');

	require('./_video_lightbox.js')();
});

global.changeLanguage = require('./_changeLanguage.js');
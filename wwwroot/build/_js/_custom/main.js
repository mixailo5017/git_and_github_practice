global.$ = global.jQuery = require("jquery");

$(function() {
	
	require('./_nav_mobile.js')();

	require('./_searchbox.js')();

	require('magnific-popup')();
});

global.changeLanguage = require('./_changeLanguage.js');
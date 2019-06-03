require("babel-polyfill");
var $ = require('jquery');

$(function() {
	
	require('./_nav_mobile.js')();

	require('./_searchbox.js')();

	var magnific = require('magnific-popup');

	require('./_video_lightbox.js')();

	// Specifically for the signup edit page
	var onSignupEdit = $("form[name='signup_edit']").length > 0;
	if (onSignupEdit) {
		require('./_signup_edit.js')();
	}

	// Specifically for the signup pickphoto page
	var onSignupPickphoto = $('#zone.signup-info').length > 0;
	if (onSignupPickphoto) {
		require('./_signup_pickphoto.js')();
	}

});

global.changeLanguage = require('./_changeLanguage.js');
global.segmentAnalytics = require('./_segment_analytics.js');
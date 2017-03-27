(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

var changeLanguage = function(language, callback) {

    var posting = $.post('/language', {
        language: language
    }, "json");

    posting.done(function(data) {
        if (typeof callback == 'function') {
            callback();
        }
        location.reload();
    }).fail(function() {
        //
    }).always(function(e) {
        //
    });
}

module.exports = changeLanguage;
},{}],2:[function(require,module,exports){
(function (global){
$(function() {
	var $prettyPhoto = $("a[rel^='prettyPhoto']");
	if ($prettyPhoto.length > 0) {
		$("a[rel^='prettyPhoto']").prettyPhoto({social_tools:""});
	}

	if($('.m-navbar .nav-main')) {
		var docWidth = $(window).width(),
			userTimer,
			mobileMenuState = 'closed',
			ddOpen = false,
			$mw = $('.m-wrap'),
			$wrapper = $('.wrapper'),
			$mainHeader = $('.main-header'),
			$userPro = $('.user-profile'),
			$userMenu = $('.user-menu'),
			$mainMenu = $('.icon-menu'),
			$mmAlt = $('.icon-menu, .iicon-menu'),
			$intMenu = $('.nav-main'),
			$langIcon = $('.active-language'),
			$langMenu = $('.m-language'),
			$dropDown = $('.m-dropdown'),
			$mnav = $('.m-nav');
			$activeLang = $('.m-language .active img').attr('src');

		$(window).on('resize', function() {
			docWidth = $(window).width();
			if(docWidth <= 1024) {
				$userMenu.show();
			} else {
				$userMenu.hide();
			}
		});

		$langIcon.attr('src', $activeLang);

		$userPro.on('click', function() {
			if(docWidth <= 1024) {
				
				if(mobileMenuState === 'closed') {
					$wrapper.animate({
						'right': '50%'
					}, 250);
					$userMenu.animate({
						'right': '0%'
					}, 250);
					$userPro.addClass('active');
					mobileMenuState = 'user';
				} else if(mobileMenuState === 'user') {
					$wrapper.animate({
						'right': '0%'
					}, 250);
					$userMenu.animate({
						'right': '-50%'
					}, 250);
					$userPro.removeClass('active');
					mobileMenuState = 'closed';
				}

				
			}
		});

		$mmAlt.on('click', function() {
			if(docWidth <= 1024) {
				if(mobileMenuState === 'closed') {
					$wrapper.animate({
						'right': '-50%'
					}, 250);
					$intMenu.animate({
						'left': '0%'
					}, 250);
					$mmAlt.addClass('active');
					$mw.addClass('h-lock');
					mobileMenuState = 'main';
				} else if(mobileMenuState === 'main') {
					$wrapper.animate({
						'right': '0%'
					}, 250);
					$intMenu.animate({
						'left': '-50%'
					}, 250, function() {
						$mmAlt.removeClass('active');
						$mw.removeClass('h-lock');
					});
					mobileMenuState = 'closed';
				}	
			}
		});

		$dropDown.on('click', function() {
			// Clear old Dropdowns
			if($mnav.find($('.dropdown-menu')).hasClass('active')) {
				// So that you can close it if you click it.
				if(!$(this).find($('.dropdown-menu'))) {
					$mnav.find($('.dropdown-menu')).removeClass('active');
				}
			}

			if(docWidth <= 1024) {
				if($(this).parent().parent().attr('class') !== 'm-nav nav-main') {
					if($(this).find($('.dropdown-menu')).is(':hidden')) {
						$(this).find($('.dropdown-menu')).addClass('active');
						$(this).addClass('open');
						ddOpen = true;
					} else if($(this).find($('.dropdown-menu')).is(':visible')) {
						$(this).find($('.dropdown-menu')).removeClass('active');
						$(this).removeClass('open');
						$(this).blur();
						ddOpen = false;
					}
				}
			}
		});

		$(document).on('click touchstart', function(e) {
			if(docWidth <= 1024) {
				if(ddOpen === true) {
					if(!$(e.target).closest('.m-dropdown').length) {
						$dropDown.find($('.dropdown-menu')).removeClass('active');
						$dropDown.removeClass('open');
						ddOpen = false;
					}
				}
			}
		});

		function trimHTML(inputHTML, maxChars) {
			var regex = /<\/?em>/;
			var splitCompany = inputHTML.split(regex);
			var characterCount = 0;
			var insideEm = false;
			var trimmedHTML = '';

			if (splitCompany[0] === '') {
			    insideEm = true;
			}

			splitCompany.forEach(function(element, index, array) {
			  if (element.length === 0) {
			  	return;
			  }
			  if (characterCount < maxChars) {
			  	if (insideEm) {
			    	trimmedHTML += '<em>';
			    }
			    trimmedHTML += element.substring(0, (maxChars - characterCount));
			    if (insideEm) {
			    	trimmedHTML += '</em>';
			    }
			    insideEm = !insideEm;
			    characterCount += element.length;
			    if (characterCount > maxChars) {
			    	trimmedHTML += '…';
			    }
			  }
			});

			return trimmedHTML;

		}

		var client = algoliasearch("61EU8IS2O1", "fdcec7b6178f9a9c128ae03d9b7f5f40");
		var members = client.initIndex(algoliaIndexMembers);
		var projects = client.initIndex(algoliaIndexProjects);
		//initialize autocomplete on search input (ID selector must match)
		$('#aa-search-input').autocomplete(
			{
				hint: false,
				debug: true,
				keyboardShortcuts: ['/']
			}, 
			[
				{
				  source: $.fn.autocomplete.sources.hits(members, { hitsPerPage: 3 }),
				  //value to be displayed in input control after user's suggestion selection
				  displayKey: function(suggestion) {
				  	return suggestion.firstname + ' ' + suggestion.lastname;
				  },
				  //hash of templates used when rendering dataset
				  templates: {
				    header: '<div class="aa-suggestions-category">' + lang['Experts'] + '</div>',
				    //'suggestion' templating function used to render a single suggestion
				    suggestion: function(suggestion) {
				      var maxChars = 35;
				      var organizationDisplayHTML = suggestion._highlightResult.organization.value;
				      if (suggestion.organization.length > maxChars) {
				      	organizationDisplayHTML = trimHTML(organizationDisplayHTML, maxChars);
				      }
				      return '<img src="' + suggestion.image + '"><span>' +
				        suggestion._highlightResult.firstname.value + ' ' +
				        suggestion._highlightResult.lastname.value + '</span> <span>' +
				        organizationDisplayHTML + '</span>';
				    },
				    empty: '<div class="aa-suggestion aa-suggestion-empty">' + lang['NoResultsFound'] + '&nbsp;<a href="/expertise/">Advanced Search</a></div>'
				  }
				},
				{
				  source: $.fn.autocomplete.sources.hits(projects, { hitsPerPage: 3 }),
				  //value to be displayed in input control after user's suggestion selection
				  displayKey: 'projectname',
				  //hash of templates used when rendering dataset
				  templates: {
				    header: '<div class="aa-suggestions-category">' + lang['Projects'] + '</div>',
				    //'suggestion' templating function used to render a single suggestion
				    suggestion: function(suggestion) {
				      if (typeof suggestion._highlightResult.country != 'undefined') {
				      	var country = suggestion._highlightResult.country.value;
				      } else {
				      	var country = '–';
				      }
				      return '<img src="' + suggestion.image + '"><span>' +
				        suggestion._highlightResult.projectname.value + '</span><span>' +
				        country + '</span>';
				    },
				    empty: '<div class="aa-suggestion aa-suggestion-empty">' + lang['NoResultsFound'] + '&nbsp;<a href="/projects/">Advanced Search</a></div>',
				    footer: '<div class="aa-suggestions-footer">Powered by <img src="/images/Algolia_logo_bg-white.svg" width="48" height="17"></div>'
				  }
				}
			])
			.on('autocomplete:selected', function(event, suggestion, dataset) {
				window.location.href = suggestion.uri;
			});
	}
});

global.changeLanguage = require('./_changeLanguage.js');
}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})

},{"./_changeLanguage.js":1}]},{},[2])

//# sourceMappingURL=main.js.map

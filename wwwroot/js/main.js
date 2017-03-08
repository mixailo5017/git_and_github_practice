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

		var client = algoliasearch("<?php echo env('ALGOLIA_APPLICATION_ID') ?>", "<?php echo env('ALGOLIA_API_KEY') ?>");
		var index = client.initIndex('dev_members');
		//initialize autocomplete on search input (ID selector must match)
		$('#aa-search-input').autocomplete(
		{hint: false,
			debug: true}, [
		{
		  source: $.fn.autocomplete.sources.hits(index, { hitsPerPage: 5 }),
		  //value to be displayed in input control after user's suggestion selection
		  displayKey: 'name',
		  //hash of templates used when rendering dataset
		  templates: {
		    //'suggestion' templating function used to render a single suggestion
		    suggestion: function(suggestion) {
		      return '<a href="/expertise/' +
		      	suggestion.uid + '"><span>' +
		        suggestion._highlightResult.firstname.value + ' ' +
		        suggestion._highlightResult.lastname.value + '</span> <span>' +
		        suggestion._highlightResult.organization.value + '</span></a>';
		    }
		  }
		}
		]);

	}
});

function killDropDown() {

}

function changeLanguage(language, callback) {

    var posting = $.post('/language', { language: language }, "json");

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
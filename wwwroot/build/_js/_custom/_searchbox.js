var searchbox = function() {

	var algoliasearch = require('algoliasearch');
	var autocomplete = require('autocomplete.js');
	var trimHTML = require('./_trimHTML.js');
	var client = algoliasearch("61EU8IS2O1", "fdcec7b6178f9a9c128ae03d9b7f5f40");
	var members = client.initIndex(algoliaIndexMembers);
	var projects = client.initIndex(algoliaIndexProjects);
	//initialize autocomplete on search input (ID selector must match)
	autocomplete('#aa-search-input', 
		{
			hint: false,
			debug: true,
			keyboardShortcuts: ['/']
		}, 
		[
			{
			  source: autocomplete.sources.hits(members, { hitsPerPage: 3 }),
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
			    empty: '<div class="aa-suggestion aa-suggestion-empty">' + lang['NoResultsFound'] + '&nbsp;<a href="/expertise/">' + lang['AdvancedSearch'] + '</a></div>'
			  }
			},
			{
			  source: autocomplete.sources.hits(projects, { hitsPerPage: 3 }),
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
			    empty: '<div class="aa-suggestion aa-suggestion-empty">' + lang['NoResultsFound'] + '&nbsp;<a href="/projects/">' + lang['AdvancedSearch'] + '</a></div>',
			    footer: '<div class="aa-suggestions-footer">Powered by <img src="/images/Algolia_logo_bg-white.svg" width="48" height="17"></div>'
			  }
			}
		])
		.on('autocomplete:selected', function(event, suggestion, dataset) {
			window.location.href = suggestion.uri;
		});
};

module.exports = searchbox;
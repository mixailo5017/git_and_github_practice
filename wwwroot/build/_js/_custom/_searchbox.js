var searchbox = function() {

	var algoliasearch = require('algoliasearch');
	var autocomplete = require('autocomplete.js');
	var trimHTML = require('./_trimHTML.js');
	var client = algoliasearch("61EU8IS2O1", "fdcec7b6178f9a9c128ae03d9b7f5f40");
	var members = client.initIndex(algoliaIndexMembers);
	var projects = client.initIndex(algoliaIndexProjects);

	// Returns the relevant Algolia source function given an index
	function hitsSource(index) {
		return autocomplete.sources.hits(index, { hitsPerPage: 3 });
	}

	//initialize autocomplete on search input (ID selector must match)
	autocomplete('#aa-search-input', 
		{
			hint: false,
			// debug: true,
			keyboardShortcuts: ['/']
		}, 
		[
			{
			  // Custom source function: returns up to three hits from Algolia,
			  // plus a fourth row linking to the Advanced Search page
			  source: function(query, callback) {
				  var memberHitsSource = hitsSource(members);
				  memberHitsSource(query, function(suggestions) {
				    var dummySuggestion = {
						dummy: true,
						uri: '/expertise?' + $.param({searchtext: '"' + query + '"'}),
						query: query
					};
				    if (suggestions.length === 0) {
						dummySuggestion.noResults = true;
						dummySuggestion.uri = '/expertise';
					}
				    // Add the dummy suggestion to the bottom
					suggestions.push(dummySuggestion);
				    callback(suggestions, query);
				  });
				},
			  //value to be displayed in input control after user's suggestion selection
			  displayKey: function(suggestion) {
			  	if (suggestion.dummy) {
			  		if (suggestion.noResults) {
			  			return lang['NoResultsFound'] + ' ' + lang['AdvancedSearch'];
			  		}
			  		return lang['FindMoreExperts'].replace('%s', "'" + suggestion.query + "'");
			  	}

			  	return suggestion.firstname + ' ' + suggestion.lastname;
			  },
			  //hash of templates used when rendering dataset
			  templates: {
			    header: '<div class="aa-suggestions-category">' + lang['Experts'] + '</div>',
			    //'suggestion' templating function used to render a single suggestion
			    suggestion: function(suggestion, query) {
			      if (suggestion.dummy) {
			      	if (suggestion.noResults) {
			      		return '<div>' + lang['NoResultsFound'] + ' <a href="/expertise">' + lang['AdvancedSearch'] + '</a></div>';
			      	}
			      	return lang['FindMoreExperts'].replace('%s', "'" + query + "'");
			      }

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
			  source: function(query, callback) {
				  var projectHitsSource = hitsSource(projects);
				  projectHitsSource(query, function(suggestions) {
				    // Add the dummy suggestion to the bottom
					var dummySuggestion = {
						projectname: lang['FindMoreProjects'].replace('%s', "'" + query + "'"),
						dummy: true,
						uri: '/projects?' + $.param({searchtext: '"' + query + '"'}),
						query: query
					};
					if (suggestions.length === 0) {
						dummySuggestion.projectname = lang['NoResultsFound'] + ' ' + lang['AdvancedSearch'];
						dummySuggestion.noResults = true;
						dummySuggestion.uri = '/projects';
					}
					suggestions.push(dummySuggestion);
				    callback(suggestions, query);
				  });
				},
			  //value to be displayed in input control after user's suggestion selection
			  displayKey: 'projectname',
			  //hash of templates used when rendering dataset
			  templates: {
			    header: '<div class="aa-suggestions-category">' + lang['Projects'] + '</div>',
			    //'suggestion' templating function used to render a single suggestion
			    suggestion: function(suggestion, query) {
			      if (suggestion.dummy) {
			      	if (suggestion.noResults) {
			      		return '<div>' + lang['NoResultsFound'] + ' <a href="/projects">' + lang['AdvancedSearch'] + '</a></div>';
			      	}

			      	return lang['FindMoreProjects'].replace('%s', "'" + query + "'");
			      }

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
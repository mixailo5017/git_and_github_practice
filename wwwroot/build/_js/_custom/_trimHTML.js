'use strict';

var trimHTML = function(inputHTML, maxChars) {
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
};

module.exports = trimHTML;

var $ = require('jquery');
var mailcheck = require('mailcheck');
var select2 = require('select2');
require('../_lib/jquery.validation');

module.exports = function() {
  var $email = $('#email');
  var $spellingHint = $("#spelling-hint");
  var $companyHint =$("#company-hint");

  var companyHint = "We recommend using your professional email address.";

  $email.focus(function() {
    $spellingHint.empty();
    $companyHint.empty();
  });

  $email.on('change',function() {
      $(this).mailcheck({
          suggested: function(element, suggestion) {
              var hint = "Also, did you mean <b><i><a href='#' class='suggestion'>" +
                  "<span class='address'>" + suggestion.address + "</span>"
                  + "@<span class='domain'>" + suggestion.domain + 
                  "</i></b></a></span>?";
                
              $spellingHint.hide().html(hint).fadeIn(500);
              $companyHint.hide().html(companyHint).fadeIn(500);
          },
          empty: function (element) {
              var companyDomain = $email.val().substr($email.val().indexOf("@") + 1);
              if (mailcheck.defaultDomains.indexOf(companyDomain) >= 0 || mailcheck.defaultSecondLevelDomains.indexOf(companyDomain.substr(companyDomain.indexOf("."))) >= 0) {
                  console.log("found a personal email address, spelled correctly");
                  $companyHint.hide().html(companyHint).fadeIn(500);
              }
              else $companyHint.empty();

          }
      });
  });

  $spellingHint.on('click', '.suggestion', function() {
      // On click, fill in the field with the suggestion and remove the hint
      $email.val($(".suggestion").text());
      $spellingHint.fadeOut(300, function() {
          $(this).empty();
      });
      $("#discipline").focus();
      $email.change();
      return false;
  });

  /**
   * Matching algorithm for Select2. Used for sector/subsector input box on user signup form.
   * Taken from https://stackoverflow.com/questions/21992727/display-result-matching-optgroup-using-select2
   * 
   * @param  {[type]} params [description]
   * @param  {[type]} data   [description]
   * @return {[type]}        [description]
   */
  function modelMatcher (params, data) {
    data.parentText = data.parentText || "";

    // Always return the object if there is nothing to compare
    if ($.trim(params.term) === '') {
      return data;
    }

    // Do a recursive check for options with children
    if (data.children && data.children.length > 0) {
      // Clone the data object if there are children
      // This is required as we modify the object to remove any non-matches
      var match = $.extend(true, {}, data);

      // Check each child of the option
      for (var c = data.children.length - 1; c >= 0; c--) {
        var child = data.children[c];
        child.parentText += data.parentText + " " + data.text;

        var matches = modelMatcher(params, child);

        // If there wasn't a match, remove the object in the array
        if (matches == null) {
          match.children.splice(c, 1);
        }
      }

      // If any children matched, return the new object
      if (match.children.length > 0) {
        return match;
      }

      // If there were no matching children, check just the plain object
      return modelMatcher(params, match);
    }

    // If the typed-in term matches the text of this term, or the text from any
    // parent term, then it's a match.
    var original = (data.parentText + ' ' + data.text).toUpperCase();
    var term = params.term.toUpperCase();


    // Check if the text contains the term
    if (original.indexOf(term) > -1) {
      return data;
    }

    // If it doesn't contain the term, don't return anything
    return null;
  }

  $(document).ready(function() {
      $('#project_sector_sub_select2').select2({
          maximumSelectionLength: 6,
          placeholder: '- Type to Search -',
          matcher: modelMatcher,
          templateSelection: template
      });
      $('#public_status').select2({
          minimumResultsForSearch: Infinity
      });
      $('#discipline').select2();
      $('#country').select2();
  });

  // Function to return label values for subsector dropdown box. Adds name of sector in front of subsector
  function template(data, container) {
    return data.id.replace(':',' — ');
  }


  var errorLabelsFromServer = $('div.errormsg label').filter(function () { // Filter removes any label elements that do not have any text inside them
      return !!$(this).text();
      });
  var errorObjects = []; // Will hold the errors that must be removed from the DOM, and re-added via jQuery Validation
  errorLabelsFromServer.each(function(index) {
    var inputName = $(this).parent().prevAll("input, select").attr('name');
    var errorObject = {};
    errorObject[inputName] = $(this).text();
    errorObjects.push(errorObject);
    $(this).empty();
  });

  var formToValidate = $('form.form');
  var validationSettings = {
        submit: {
            settings: {
                errorListClass: 'errormsg'
            },
            callback: {
                // add optional callback here e.g. for error handling
            }
        },
        dynamic: {
            settings: {
                trigger: "focusout"
            }
        }
    };
  formToValidate.validate(validationSettings);

  for (var key in errorObjects) {
    formToValidate.addError(errorObjects[key]);
  }

  validationSettings.submit.settings.scrollToError = true;
  formToValidate.validate(validationSettings);

  // jQuery validation doesn't automatically pick up on interaction with Select2. This code listens for the event generated by Select2 when it is opened and manually calls jQuery Validation's remove method
  $('select').on("select2:open", function (e) { 
      var nameToRemove = $(this).attr('name');
      formToValidate.removeError(nameToRemove);
    });

};
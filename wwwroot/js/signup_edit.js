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
            if (Mailcheck.defaultDomains.indexOf(companyDomain) >= 0 || Mailcheck.defaultSecondLevelDomains.indexOf(companyDomain.substr(companyDomain.indexOf("."))) >= 0) {
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
    $("#password").focus();
    $email.change();
    return false;
});


// Project Sub-Sector Dynamic List Population
var $project_sector_sub = $('#project_sector_sub option').not('.hardcode');
var $project_sector_sub_holder = $project_sector_sub.clone();
$project_sector_sub.remove().not('.hardcode');
$('#project_sector_main').on("change",function() {
    $('#project_sector_sub').removeAttr('disabled');
    $('#project_sector_sub').focus();
    var thisClass = $(this).find('option:selected').attr('class').replace('sector_main','project_sector_sub');
    $('#project_sector_sub option').not('.hardcode').remove()
    $('#project_sector_sub option:first').after( $project_sector_sub_holder.filter('.' + thisClass) );
    // if($('#selected_sub_sector').length>0)
    // {
    //     if($('#selected_sub_sector').text()=="Other"){
    //         $('#project_sector_sub').val("Other");
    //     }
    // }
}).trigger("change");


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
        placeholder: '- Select Sector -',
        matcher: modelMatcher
    });
    $('#public_status').select2({
        minimumResultsForSearch: Infinity
    });
    $('#country').select2({
        minimumResultsForSearch: Infinity
    });
});
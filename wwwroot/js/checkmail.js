var $email = $('#email');
var $hint = $("#hint");
$email.on('blur',function() {
    // $hint.css('display', 'none').empty();
    $(this).mailcheck({
        suggested: function(element, suggestion) {
            if(!$hint.html()) {
                // First error - fill in/show entire hint element
                var hint = "Did you mean <span class='suggestion'>" +
                    "<span class='address'>" + suggestion.address + "</span>"
                    + "@<a href='#' class='domain'><b><i>" + suggestion.domain + 
                    "</i></b></a></span>?";
                  
                $hint.html(hint + "<br>Using company specific emails is recommanded.").fadeIn(150);
            } else {
                // Subsequent errors
                $(".address").html(suggestion.address);
                $(".domain").html(suggestion.domain);
            }
        },
        empty: function (element) {
            if (!$hint.html()) {
                var companyDomain = $email.val().substr($email.val().indexOf("@") + 1);
                if (Mailcheck.defaultDomains.indexOf(companyDomain) >= 0 || 
                    Mailcheck.defaultSecondLevelDomains.indexOf(companyDomain.substr(companyDomain.indexOf("."))) >= 0) {
                    $hint.html('Using company specific emails is recommanded.');
                    $hint.fadeOut(3000, function() {
                        $hint.css('display', 'none').empty();
                    });
                }
            }
        }
    });
});
$hint.on('click', '.domain', function() {
    // On click, fill in the field with the suggestion and remove the hint
    $email.val($(".suggestion").text());
    $hint.fadeOut(200, function() {
        $(this).empty();
    });
    return false;
});



// // Sector Subsector filters on listviews
// function loadSubsectors($subsectors, sector) {
//     $subsectors.html("");
//     $subsectors.append("<option value=\"\">" + subsectors["first"] + "</option>");
//     $.each(subsectors[sector], function(index, value) {
//         $subsectors.append("<option value=\"" + value + "\">" + value + "</option>")
//     });
// }
// $('form[name=search_form] select[name=sector]').change(function() {
//     var $this = $(this),
//         $subsectors = $('form[name=search_form] select[name=subsector]');
//     loadSubsectors($subsectors, $this.val());
// });

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
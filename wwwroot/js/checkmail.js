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
          var companyDomain = $email.val().substr($email.val().indexOf("@") + 1);
          if (Mailcheck.defaultDomains.indexOf(companyDomain) >= 0 || 
            Mailcheck.defaultSecondLevelDomains.indexOf(companyDomain.substr(companyDomain.indexOf("."))) >= 0) {
            $hint.html('Using company specific emails is recommanded.');
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
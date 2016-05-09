
$('#email').on('click', function() {
  $(this).mailcheck({
    suggested: function(element, suggestion) {
      console.log("suggestion ", suggestion.full);
      $('#suggestion').html("Did you mean <b><i>" + suggestion.full + "</b></i>?");
    },
    empty: function(element) {
      $('#suggestion').html('No Suggestions :(');
    }
  });
});

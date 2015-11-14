(function($) {
  'use strict';
  
  function last3Words(text) {
    var arr = text.split(/[\s,]+/) ;
    return arr.slice(Math.max(arr.length - 3, 0)).join(' ');
  }

  $(function() {
    $('#inputbox').keypress(function(e) {
      if (e.key == ' ' || e.key == 'Tab' || e.key == 'Enter') {
        // blank inserted
        // Process suggestions
        var text = $(this).val();
        var phrase = last3Words(text);
        
        // Get suggestions from server
        var url = ['../predictor.php?phrase=', phrase].join('');
        $.getJSON(url, function(suggestions) {
          //console.log(suggestions);
          for (var i = 0; i < 3; i++) {
            var sug_id = ['#sug', i + 1].join('');
            var sug = $(sug_id);
            if (i < suggestions.length) {
              sug.text(suggestions[i]);
            }
            else {
              sug.html('&nbsp;');
            }
          }
        })
        .fail(function() {
          // Javascript promise: Error occured
          console.error('Error occured.');
        });
      }
    });
  });
})(jQuery);

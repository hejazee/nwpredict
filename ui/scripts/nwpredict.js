(function($) {
  'use strict';
  
  function last3Words(text) {
    var arr = text.split(/[\s,]+/) ;
    return arr.slice(Math.max(arr.length - 3, 0)).join(' ');
  }
  
  /**
   * Insert text in textarea at caret.
   */
  function insertAtCaret(areaId, text) {
		var txtarea = document.getElementById(areaId);
		if (!txtarea) { return; }

		var scrollPos = txtarea.scrollTop;
		var strPos = 0;
		var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
			"ff" : (document.selection ? "ie" : false ) );
		if (br == "ie") {
			txtarea.focus();
			var range = document.selection.createRange();
			range.moveStart ('character', -txtarea.value.length);
			strPos = range.text.length;
		} else if (br == "ff") {
			strPos = txtarea.selectionStart;
		}

		var front = (txtarea.value).substring(0, strPos);
		var back = (txtarea.value).substring(strPos, txtarea.value.length);
		txtarea.value = front + text + back;
		strPos = strPos + text.length;
		if (br == "ie") {
			txtarea.focus();
			var ieRange = document.selection.createRange();
			ieRange.moveStart ('character', -txtarea.value.length);
			ieRange.moveStart ('character', strPos);
			ieRange.moveEnd ('character', 0);
			ieRange.select();
		} else if (br == "ff") {
			txtarea.selectionStart = strPos;
			txtarea.selectionEnd = strPos;
			txtarea.focus();
		}

		txtarea.scrollTop = scrollPos;
	}
  
  /**
   * Get suggestions from predictor and show in suggestions list
   */
  function doSuggest(text) {
    //var text = $(this).val();
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

  $(function() {
    $('#inputbox').keypress(function(e) {
      if (e.key == ' ' || e.key == 'Tab' || e.key == 'Enter') {
        // blank inserted
        // Process suggestions
        var text = $(this).val();
        doSuggest(text);
      }
    });
    
    // Click on suggenstions should insert text into textbox
    $('#suggestions div').click(function() {
      var text = $(this).text();
      insertAtCaret('inputbox', text + ' ');
      doSuggest($('#inputbox').val());
    });
  });
})(jQuery);

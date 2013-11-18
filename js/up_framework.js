;(function($){

  // Color Picker adapted from Rachel Baker's color picker example
  // http://rachelbaker.me/how-to-use-the-new-wordpress-color-picker-in-a-plugin/
  function pickColor(color) {
      $(".colorPickerWrapper").find('input[type="text"]').val(color);
  }

  function toggle_text() {
      colorPicker = $(".colorPickerWrapper").find('input');
      if ("" === colorPicker.val().replace("#", "")) {
          colorPicker.val(default_color);
          pickColor(default_color);
      } else {
        pickColor(colorPicker.val());
      }
  }

  var default_color = "fbfbfb";

	$(document).ready(function($){

    var colorPicker = $(".colorPickerWrapper").find('input');
    colorPicker.wpColorPicker({
        change: function(event, ui) {
            pickColor(colorPicker.wpColorPicker("color"));
        },
        clear: function() {
            pickColor("");
        }
    });

    colorPicker.click(toggle_text);
    if( colorPicker.length ) {
      toggle_text();
    }

    $('.imageWrapper').each(function(i){
    	if( $(this).find('input[type="text"]').val() ){
				$(this).find('.image_preview').html('<img src="'+$(this).find('input[type="text"]').val()+'" alt="">');
      }
		});

	});

})(jQuery);

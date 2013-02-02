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
      } else pickColor(colorPicker.val());
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
    if( colorPicker.length ) toggle_text();

    $('.imageWrapper').each(function(i){
    	if( $(this).find('input[type="text"]').val() )
				$(this).find('.image_preview').html('<img src="'+$(this).find('input[type="text"]').val()+'" alt="">');
		});

		$('.add_text_list').find('a').live('click', function(e){

			var $clone = $(this).parents('td').find('div.text_list').find('.entry').eq(0).clone();

			$(this).parents('td').find('div.text_list').append($clone).find('input:last').val('');

		    return false;
		});

		$('.text_list').find('.delete_text_list').live('click',function(e){

			if( $(this).parents('.text_list').find('.entry').length > 1 )
				$(this).parents('.entry').remove();
			else
				$(this).parents('.entry').find('input').val('');

		    return false;

		});

	});

})(jQuery);

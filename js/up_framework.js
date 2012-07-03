;(function($){

	$(document).ready(function($){

		$(".colorPickerWrapper").find('.clear').click(function(e){
			e.preventDefault();
			jQuery(this).parent().find('input').val('').css('backgroundColor','#f9f9f9');
		});

		$colorpicker_inputs = $('input.popup-colorpicker');
		
		$colorpicker_inputs.each(
			function(){
			   var $input = $(this);
			   var sIdSelector = "#" + $(this).attr('id') + "picker";
			   var oFarb = $.farbtastic(
				  sIdSelector,
				  function( color ){		             
					 
					 $input.css({
					backgroundColor: color,
					color: oFarb.hsl[2] > 0.5 ? '#000' : '#fff'
				  }).val( color );
				  
				  
				  if( oFarb.bound == true ){
					 $input.change();
				  }else{
					 oFarb.bound = true;
				  }
				  }
			   );
			   oFarb.setColor( $input.val() );
	
			}
		);
		
		$colorpicker_inputs.each(function(e){
			$(this).parent().find('.popup-guy').hide();
		});
	
		
		$colorpicker_inputs.live('focus',function(e){
			$(this).parent().find('.popup-guy').show();
			$(this).parents('table').find('.colorPickerWrapper').css({
			  zIndex : '0'
			});
			$(this).parents('.colorPickerWrapper').css({
				zIndex : '9999'
			})
		});
	
		$colorpicker_inputs.live('blur',function(e){
			$(this).parent().find('.popup-guy').hide();
			$(this).parents('li').css({
				zIndex : '0'
			})
		});
		
		// Awesomeness for Typography previews
	
		$('.type-typography').toggleClass('compact');
		$('.type-typography').find('.compact_font_preview').append('<span class="toggle">edit</span>');
	

		$('.type-typography').each(function($i){
			
			$(this).find('.toggle').css('top',$(this).find('.compact_font_preview').height() );
			$(this).append('<div class="compact_font_preview"><span class="selector">'+$(this).find('.font-selector').val()+'</span><span class="type_title">'+$(this).find('.title label').text()+'</span></div>');
			$(this).find(".compact_font_preview").attr('style',$(this).find("#font-preview").attr('style'));
			
		});
		
		$('.compact_font_preview,.toggle').click(function(e){
			$(this).parents('.typography').toggleClass('compact');
			$(this).parents('.feature-set').find('.typography');
			$(this).parents('.typography').find('.compact_font_preview').attr('style',$(this).find('#font-preview').attr('style'));
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
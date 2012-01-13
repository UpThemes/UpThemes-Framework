function activate_save_animation(e){
	
	//jQuery('.button-zone').addClass('formChanged');
	//jQuery('.button-zone button').addClass('save-me-fool');
	//jQuery('.formState').fadeIn( 400 );

}

jQuery(document).ready(function($){
		
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
		$(this).parents('li').css({
			position : 'relative',
			zIndex : '9999'
		})
		$('#tabber').css({overflow:'visible'});
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

	$(document).ready(function(e){

		$('.type-typography').each(function($i){
			
			$(this).find('.toggle').css('top',$(this).find('.compact_font_preview').height() );
			$(this).append('<div class="compact_font_preview"><span class="selector">'+$(this).find('.font-selector').val()+'</span><span class="type_title">'+$(this).find('.title label').text()+'</span></div>');
			$(this).find(".compact_font_preview").attr('style',$(this).find("#font-preview").attr('style'));
			
		});
		
		$('.compact_font_preview,.toggle').click(function(e){
			$(this).parents('.typography').toggleClass('compact');
			$(this).parents('.feature-set').find('.typography');
			$(this).parents('.typography').find(".compact_font_preview").attr('style',$(this).find("#font-preview").attr('style'));
		});
	
	});

})
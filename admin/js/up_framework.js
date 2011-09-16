/*

jQuery history awesomeness added by rad master Doug Neiner
http://pixelgraphics.us

*/

function activate_save_animation(e){
	
	jQuery('.button-zone').addClass('formChanged');
	jQuery('.button-zone button').addClass('save-me-fool');
	jQuery('.formState').fadeIn( 400 );

}

jQuery(document).ready(function($){

	$('button[type="reset"]').each(function(i){
		
		$(this).replaceWith($("<a class='button' href='"+document.location+"'>"+$(this).text()+"</a>"));
	
	});

	$('li#toplevel_page_upthemes a').live('click', function(){
		scroll(0,0);
	});
	
	$('li#toplevel_page_upthemes li.wp-first-item').remove();
	
	$('textarea.click-copy').click(function(){
		$(this).select();
	});

	$nav = $("#up_nav");
	$tabber = $('#tabber').children().hide().end();
	
	$.History.bind( function(path){
		path = '#' + path.substr(1);
		change_tab( path );
	});
	
	function change_tab( id ){
		var $a     = $nav.find('a[href*=' + id + ']'),
			$t     = $tabber.find( id ),
			clicked_tab_ref_height;
		
		$('form#theme-options').attr('action', '#/'+id.replace("#",""));
		
		if(id == '#import-export'){
			$('button#up_save').fadeOut();
		}
		else{$('button#up_save').fadeIn();}
		
		if(!$t.is(':visible')){
			$nav.find('li.selected').removeClass('selected');
			$a.closest('li').andSelf().addClass('selected');
			
			clicked_tab_ref_height = $t.css({position: 'absolute', opacity: 0, display: 'block'}).height();
			$t.css({position: 'relative', opacity: 1, display: 'none'});
			
			var fadeOut = function(e){
				$tabber.stop().animate({
					height: clicked_tab_ref_height
				},400,function(){
					$(this).height('auto');
			//Callback after new tab content's height animation
			$t.fadeTo(500, 1);
				});
			}
			
			var $visible = $tabber.children(':visible');
			if($visible.length) {
				$tabber.height( $tabber.height() );
				$visible.fadeOut(400, fadeOut);
			} else {
				fadeOut();
			}
		}
	}
	
	$nav.find('li a').click(function(evt){
		var id = $(this).attr('href').substr(1);
		$.History.setHash('/' + id);
		evt.preventDefault();
	})

	// var hashSelector = 'a[href*=' + document.location.hash + ']';
	if(!document.location.hash) {
		$('#up_nav li:first a').click();
		$('html').scrollTop(0);
	}
		
	$('#upthemes_framework input, #upthemes_framework select,#upthemes_framework textarea[class!=click-copy][class!=up_import_code]').live('change', function(e){
		activate_save_animation(e);
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
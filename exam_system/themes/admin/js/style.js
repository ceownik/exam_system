/**
* 
**/

;(function($)
{
	$(document).ready(function() {
		
		bindHideValue( $('.hideValue') );
		
		
		
		
		// color even rows in forms
		if( $("form.color-rows").html()!=null ) {
			var rows = $(this).find('div.row');
			var count = 0;
			
			rows.each(function(i,e){
				((++count)%2) ? $(this).addClass('odd') : $(this).addClass('even');
			});
		}
		
		
		
		
		
		// append datepickers
		$('.datepicker').datetimepicker({
			'dateFormat'	: 'yy-mm-dd',
			'firstDay'		: 1
		});
		
		
		
		
		// shortcuts menu
		$("#shortcuts").find('.parent').each(function(i,e){
			// on hover
//			$(this).parent().hover(function(){
//				var ul = $(this).children('ul');
//				ul.stop().show();
//			},
//			function(){
//				var ul = $(this).children('ul');
//				ul.stop().hide();
//			});
			
			// on click
			$(this).parent().click(function(){
				var ul = $(this).children('ul');
				ul.slideToggle(100);
			});
		});
		
		
		
		
		
		$("#flash-messages .msg").click(function(){
			$(this).stop().fadeOut('slow');
		});
		
		
	});

	function bindHideValue( fields ) {
		
		fields.each(function(i,e){
			
			var initValue = $(this).attr('value');
			
			$(this).focusin(function(){
				if( $(this).attr('value') == initValue ) {
					$(this).attr('value', '');
				}
			});
			
			$(this).focusout(function(){
				if( $(this).attr('value').match(/^[ ]*$/) )
					$(this).attr('value', initValue);
			});
		});
		
		
		
		
	}
	
})(jQuery);

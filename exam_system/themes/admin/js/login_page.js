/**
 * login page fix
 */

;(function($){
	
	$(window).resize(function(){
		var wHeight = $(window).innerHeight();
		var wWidth = $(window).innerWidth();
		
		var fHeight = $("#footer").outerHeight();
		
		$("#main").css({
			'height':wHeight-fHeight
		});
		
		$("#footer").css({'width':wWidth-30});
		
		var cHeight = $("#content").outerHeight();
		
		if( $("#main").innerHeight() > cHeight ) {
			$("#content").css({
				'margin-top':Math.floor( ($("#main").innerHeight()-cHeight) / 3 )
			});
		}
		else {
			$("#content").css({
				'margin-top':0
			});
		}
	});
	
	$("#content").resize(function(){$(window).resize()});
	
	$(document).ready(function(){
		$(".input-text.login").focus();
		$("#footer").appendTo('body');
		$(window).resize();
	});
	
	
})(jQuery);
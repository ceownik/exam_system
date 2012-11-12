
function bindTinyMce(fieldList, width, height) {
	
	width = width ? width : "500px";
	height = height ? height : "200px";
	
	
	tinyMCE.init({
			theme : "advanced",
			mode: "exact",
			elements : fieldList,
			
			theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,|,sub,sup,|,charmap,fullscreen,cleanup,code",
			plugins : "fullscreen",

			language : "pl",
			
			height: height,
			width: width,
			
			'paste_text_sticky': true,
			'paste_text_sticky_default': true,
			
			content_css : "/css/tiny_mce_content.css"
		});
}

function openDialog($id, $dialogId) {

	$($id).live('click', function($event){
		$event.preventDefault();

		var href = $(this).attr('href');
		$('#'+$dialogId).html('');
		$('#'+$dialogId).dialog({
			draggable: false,
			position: ["center",20]
		});
		$('#'+$dialogId).dialog('open');
		$.ajax({
			url: href,
			cache: false,
			dataType: 'json',
			type: "GET",
			success: function(data){
				var $step = data.status;
				if($step == 'render') {
					$('#'+$dialogId).html(data.html);
				} else {
					$('#'+$dialogId).dialog('close');
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$('#'+$dialogId).dialog('close');
				console.log(jqXHR);
			}
		});
	});	
}

function openTinyMceDialog($id, $dialogId, fieldList, width, height) {

	$($id).live('click', function($event){
		$event.preventDefault();

		var href = $(this).attr('href');
		$('#'+$dialogId).html('');
		$('#'+$dialogId).dialog({
			draggable: false,
			position: ["center",20]
		});
		$('#'+$dialogId).dialog('open');
		$.ajax({
			url: href,
			cache: false,
			dataType: 'json',
			type: "GET",
			success: function(data){
				var $step = data.status;
				if($step == 'render' || $step == 'validate') {
					$('#'+$dialogId).html(data.html);
					bindTinyMce(fieldList, width, height);
				} else {
					$('#'+$dialogId).dialog('close');
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$('#'+$dialogId).dialog('close');
				console.log(jqXHR);
			}
		});
	});	
}

function questionSetMenu() {
	
	var items = $('#content').find('div.content-submenu');
	
//	items.find('.parent').click(function(){
//		return false;
//	});
//	
	items.find('.parent').hover(function(){
			$(this).parent().find('.content-submenu-sub').show();
		},
		function(){
		}
	);
	
	items.hover(function() {
		},
		function() {
			$(this).find('.content-submenu-sub').hide();
		}
	);
}

function questionShowDescription() {
	
	var items = $('#content').find('div.position-body');
	
	items.live('click', function(event){
		event.preventDefault();
		
		var description = $(this).parent().children('.position-description');
		
		description.stop().slideToggle();
	});
}

function questionSetGridShowDescription() {
	var items = $('#content').find('.grid-view .description');
	
	items.live('click', function() {
		var item = $(this).children('div');
		if(item.hasClass('visible')) {
			item.removeClass('visible');
		} else {
			item.addClass('visible');
		}
	});
}

function gridShowDescription() {
	var items = $('#content').find('.grid-view .description');
	
	items.live('click', function() {
		var item = $(this).children('div');
		if(item.hasClass('visible')) {
			item.removeClass('visible');
		} else {
			item.addClass('visible');
		}
	});
}

function updateQuestionQuantity(testId, dropdown, baseUrl) {
	var first = 0;
	$(dropdown).live('change', function($event){
		
		var groupId = $(this).parents('div.group').attr('id');
		groupId = groupId.substr(groupId.length - 1);
		var type = $(this).val();
		
		var quantity = $(this).parents('div.group').find('.question-quantity');
		var value = quantity.val();
		
		
		$.ajax({
			url: baseUrl + '/admin/exam/getQuestionCount',
			cache: false,
			dataType: 'json',
			type: "POST",
			data: {
				testId: testId,
				groupId: groupId,
				type: type
			},
			success: function(data){
				//console.log(data);
				quantity.html('');
				var html = '';
				for(var i=0; i<=data.count; i++)  {
					html = '<option value="'+i+'">'+i+'</option>';
					if(first<2 && i==value) {
						first++;
						html = '<option value="'+i+'" selected>'+i+'</option>';
					}
					quantity.html(quantity.html()+html);
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				console.log(jqXHR);
			}
		});
		
	});	
	$(dropdown).change();
}
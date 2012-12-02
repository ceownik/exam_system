
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
		
		var answers = $(this).parents('div.group').find('.answers-count');
		var answersValue = answers.val();
		
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
				answers.html('');
				if(type==1 || type=='') 
					var loopTo = data.answersCount.minWrong + 1;
				else if(type==2)
					var loopTo = data.answersCount.minWrong + data.answersCount.minCorrect;
				for(i=2; i<=loopTo; i++) {
					html = '<option value="'+i+'">'+i+'</option>';
					if(i==answersValue)
						html = '<option value="'+i+'" selected>'+i+'</option>';
					answers.html(answers.html() + html);
				}console.log(typeof type);
			},
			error: function(jqXHR, textStatus, errorThrown){
				console.log(jqXHR);
			}
		});
		
	});	
	$(dropdown).change();
}

function performTest(baseUrl, testId, timeLeft, totalTime) {
	window.test = {};
	test.changed = false;
	test.saved = true;
	test.timeLeft = timeLeft;
	test.totalTime = totalTime;
	test.interval = null;
	test.form = $('#content').find('#test-form');
	test.msg = $('#content').find('.message-wrapper');
	test.timer = $('#content').find('.timer-wrapper .time');
	test.id = testId;
	test.interval = setInterval(function(){setTimer()}, 1000);
	test.ajaxInterval = 10000;
	test.form.change(function(){
		test.changed = true;
		return true;
	});
	
	var clearLinks = $('#content').find('a.clear');
	clearLinks.click(function(){
		$(this).parents('table').find('input').attr('checked', false);
		test.form.change();
		return false;
	});
	
	$('.save-icon').click(function() {
		test.changed=true;
		testLooper(baseUrl, false);
		return false;
	});
	
	$('.end-icon').click(function() {
		var action = confirm('Czy na pewno zakończyć test?');
		if(action) {
			test.changed = true;
			testLooper(baseUrl, false, true);
		}
		return false;
	});
	
	testLooper(baseUrl);
}

function testLooper(baseUrl, doLoop, endTest ) {
	
	doLoop = typeof doLoop !== 'undefined' ? doLoop : true;
	endTest = typeof endTest !== 'undefined' ? endTest : false;
	
	if(test.changed==true) {
		test.msg.stop(true, true).html('zapisywanie zmian').show().delay(1000).fadeOut(1000);
		test.saved = false;
	}
	test.changed = false;
	var postData = {
		id: test.id,
		test: test.form.serialize(),
		changed: test.changed,
		endTest: false
	};
	if(endTest==true) {
		postData.endTest = true;
	}
	
	$.ajax({
		url: baseUrl + '/exam/execute/submitTest',
		cache: false,
		dataType: 'json',
		type: "POST",
		data: postData,
		success: function(data){
			//console.log(data);
			if(data.status=='success') {
				if(!test.saved) {
					if(Math.abs(test.timeLeft - data.time_left)>5) {
						test.timeLeft = data.time_left;
					}
					if(data.time_left < 20) {
						test.ajaxInterval = 1000;
					}
					test.msg.stop(true, true).removeClass('error').addClass('notice').html('zmiany zostały zapisane').show().delay(1500).fadeOut(1000);
					test.saved = true;
					if(endTest==true) {
						alert('Test został zakończony');
						location.reload();
					}
				}
				if(doLoop) {
					setTimeout(function(){testLooper(baseUrl)}, test.ajaxInterval);	
				}
			} else if(data.status=='end' || data.status=='error') {
				alert(data.msg);
				location.reload();
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log('error');
			console.log(jqXHR);
			
			test.msg.stop(true, true).removeClass('notice').addClass('error').html('wystąpił błąd podczas wysyłania zmian na serwera').show().delay(2000).fadeOut(1000);
			if(doLoop) {
				setTimeout(function(){testLooper(baseUrl)}, test.ajaxInterval);	
			}
		}
	});
}

function setTimer() {
	if(test.timeLeft>0)
		--test.timeLeft;
	var hour = Math.floor(test.timeLeft/3600);
	var min = Math.floor((test.timeLeft-(hour*3600))/60);
	var sec = test.timeLeft - (hour * 3600) - (min * 60);
	if (hour   < 10) {hour   = "0"+hour;}
	if (min < 10) {min = "0"+min;}
	if (sec < 10) {sec = "0"+sec;}	
	
	test.timer.html(hour +':'+ min +':'+ sec);
	
	changeColor();
}

function changeColor() {
	var p = (test.timeLeft/test.totalTime);
	
	test.timer.parent().css({
		'background-color':getColor(p)
	});
}

function getColor(p) {
	var color = hsv2rgb(p*0.4, 0.9, 0.9);
	return 'rgb('+color['red']+', '+color['green']+', '+color['blue']+')';
}

function hsv2rgb(h,s,v) {
	// Adapted from http://www.easyrgb.com/math.html
	// hsv values = 0 - 1, rgb values = 0 - 255
	var r, g, b;
	var RGB = new Array();
	if(s==0){
		RGB['red']=RGB['green']=RGB['blue']=Math.round(v*255);
	} else {
		// h must be < 1
		var var_h = h * 6;
		if (var_h==6) var_h = 0;
		//Or ... var_i = floor( var_h )
		var var_i = Math.floor( var_h );
		var var_1 = v*(1-s);
		var var_2 = v*(1-s*(var_h-var_i));
		var var_3 = v*(1-s*(1-(var_h-var_i)));
		if(var_i==0){ 
			var_r = v; 
			var_g = var_3; 
			var_b = var_1;
		}else if(var_i==1){ 
			var_r = var_2;
			var_g = v;
			var_b = var_1;
		}else if(var_i==2){
			var_r = var_1;
			var_g = v;
			var_b = var_3
		}else if(var_i==3){
			var_r = var_1;
			var_g = var_2;
			var_b = v;
		}else if (var_i==4){
			var_r = var_3;
			var_g = var_1;
			var_b = v;
		}else{ 
			var_r = v;
			var_g = var_1;
			var_b = var_2
		}
		//rgb results = 0 ÷ 255  
		RGB['red']=Math.round(var_r * 255);
		RGB['green']=Math.round(var_g * 255);
		RGB['blue']=Math.round(var_b * 255);
	}
	return RGB;  
};
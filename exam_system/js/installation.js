(function($){
	
	var step = 1;
	window.baseUrl = '';
	
	window.installation = '';
	
	$(document).ready(function(){
		if($('.main').find('.installation')) {
			installation = $('.installation');
			
			baseUrl = installation.find('.base-url').html();
			
			loadMainView(baseUrl, installation);
		}
	});
})(jQuery);


function loadMainView(baseUrl, dir) {
	$.ajax({
		type: "POST",
		url: baseUrl,
		dataType: "json",
		data: { 
			'request' : 'loadMainView'
		},
		error: function(jqXHR, textStatus, errorThrown) {
			onAjaxError(jqXHR, textStatus, errorThrown);
		}
	  }).done(function( msg ) {
		if(msg.status == 'success') {
			dir.find('.installation-content').html(msg.html);
			dir.find('.step-title').html(msg.title);
			
			dir.find('.check-connection').click(function(){connectionCheckCallback(); return false;});
		}
	  });
}

function onAjaxError(jqXHR, textStatus, errorThrown) {
	alert('an error has occured');
	
	console.log(jqXHR);
	console.log(textStatus);
	console.log(errorThrown);
}

function connectionCheckCallback() {
	$('.main').find('.msg').removeClass('success').removeClass('error');
	$('.main').find('.msg').html('Sprawdzanie...');
	
	
	if($('#host').val() == '' ||
		$('#database').val() == '' ||
		$('#user').val() == '' ) {
		$('.main').find('.msg').addClass('error');
		$('.main').find('.msg').html('Proszę wypełnić wszystkie pola.');
		return;
	}
	
	
	$.ajax({
		type: "POST",
		url: baseUrl,
		dataType: "json",
		data: { 
			'request' : 'checkDbConnection',
			'data' : $('.main').find('#database-form').serialize()
		},
		error: function(jqXHR, textStatus, errorThrown) {
			onAjaxError(jqXHR, textStatus, errorThrown);
		}
	  }).done(function( msg ) {
		if(msg.status == 'success') {
			$('.main').find('.msg').removeClass('success').removeClass('error');
			
			$('.main').find('.msg').html(msg.msg);
			
			if(msg.connection == true) {
				$('.main').find('.msg').addClass('success');
				setTimeout(function(){applicationSettings();}, 500);
			}
			else {
				$('.main').find('.msg').addClass('error');
			}
		}
	  });
	return;
}

function applicationSettings() {
	$.ajax({
		type: "POST",
		url: baseUrl,
		dataType: "json",
		data: { 
			'request' : 'loadSettingsView'
		},
		error: function(jqXHR, textStatus, errorThrown) {
			onAjaxError(jqXHR, textStatus, errorThrown);
		}
	  }).done(function( msg ) {
		if(msg.status == 'success') {
			var dir = installation;
			dir.find('.installation-content').html(msg.html);
			dir.find('.step-title').html(msg.title);
			
			dir.find('.install').click(function(){install(); return false;});
		}
	  });
}


function install() {
	$('.main').find('.msg').removeClass('success').removeClass('error');
	$('.main').find('.msg').html('Sprawdzanie...');
	
	
	if($('#login').val() == '' ||
		$('#email').val() == '' ||
		$('#password_user').val() == '' ||
		$('#password_repeat').val() == '' ) {
		$('.main').find('.msg').addClass('error');
		$('.main').find('.msg').html('Proszę wypełnić wszystkie pola.');
		return;
	}
	
	
	var emailfilter = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;
	
	
	if($('#email').val().match(emailfilter) == null ) {
		$('.main').find('.msg').addClass('error');
		$('.main').find('.msg').html('Proszę wprowadzić poprawny adres email.');
		return;
	}
	
	if($('#password_user').val() != $('#password_repeat').val()) {
		$('.main').find('.msg').addClass('error');
		$('.main').find('.msg').html('Podane hasła nie są takie same.');
		return;
	}
	
	var dir = installation;
	
	$.ajax({
		type: "POST",
		url: baseUrl,
		dataType: "json",
		data: { 
			'request' : 'installApplication',
			'data' : $('.main').find('#settings-form').serialize()
		},
		error: function(jqXHR, textStatus, errorThrown) {
			onAjaxError(jqXHR, textStatus, errorThrown);
		}
	  }).done(function( msg ) {
		if(msg.status == 'success') {
			dir.find('.step-title').html(msg.title);
			dir.find('.installation-content').html(msg.html);
		}
		else if(msg.status == 'validation') {
			$('.main').find('.msg').addClass('error');
			$('.main').find('.msg').html(msg.msg);
		}
		else if(msg.status == 'error') {
			dir.find('.step-title').html(msg.title);
			dir.find('.installation-content').html(msg.html);
		}
	  });
	return;
	// set super admin login, password, email, 
	// aplication title
}
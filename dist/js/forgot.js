function runData() {
	$.ajax({
		url: "./api/data",
		headers: {
			"Api": $.cookie("BSK_API"),
			"Key": $.cookie("BSK_KEY"),
			"Accept": "application/json"
		},
		method: 'GET',
		dataType: "JSON",
		data: "cover",
		success: function (result) {
			var list = '';
			$.each(result.data, function (i, val) {
				list += '<div class="fadeOut item auth-cover-img overlay-wrap" style="background-image:url(' + val.image + ');">';
				list += '<div class="auth-cover-info py-xl-0 pt-100 pb-50">';
				list += '<div class="auth-cover-content text-center w-xxl-75 w-sm-90 w-xs-100">';
				list += '<h1 class="display-3 text-white">' + val.title + '</h1>';
				list += '<p class="text-white">' + val.info + '</p>';
				list += '</div>';
				list += '</div>';
				list += '<div class="bg-overlay bg-trans-dark-50"></div>';
				list += '</div>';
			});
			$('#owl_demo_1').html(list).owlCarousel({
				items: 1,
				animateOut: 'fadeOut',
				loop: true,
				margin: 10,
				autoplay: true,
				mouseDrag: false,
				dots: false
			});
		}
	});
};

function getURL(sParam) {
	var sPageURL = window.location.search;
	var sURLVariables = sPageURL.substring(1).split('&');
	for (var i = 0; i < sURLVariables.length; i++) {
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == sParam) {
			return decodeURIComponent(sParameterName[1]);
		}
	}
}

function runReset() {
	$('button[name="forgot"]').val(getURL('id'));
	$('#formReset').validate({
		errorElement: "span",
		errorClass: 'help-block',
		ignore: "required",
		rules: {
			rpassword: {
				required: true,
				minlength: 5,
				equalTo: "#new"
			}
		},
		highlight: function (element) {
			$(element).closest('.help-block').removeClass('valid');
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		success: function (label, element) {
			label.addClass('help-block valid');
			$(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
		},
		submitHandler: function (form) {
			$.ajax({
				url: "./api/forgot",
				method: "POST",
				dataType: "JSON",
				data: $(form).serialize() + '&key=' + getURL('key') + '&token=' + getURL('token'),
				beforeSend: function () {
					$('button[type=submit]', '#formReset').attr('disabled', true);
				},
				success: function (response) {
					$('#formReset').trigger('reset');
					$('button[type=submit]', '#formReset').attr('disabled', false);
					$.toast({
						heading: "Reset Password",
						text: response.data,
						position: 'bottom-right',
						icon: response.message
					});
				}
			});
		}
	});
}
(function () {
	'use strict';
	runData();
	runReset();
})();
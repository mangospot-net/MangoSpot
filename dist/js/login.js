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
}
(function () {
	'use strict';
	runData();
})();
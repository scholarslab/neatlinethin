$(document).ready(function() {

	// NAV

	$('.exhibits-nav').click(function() {
		$('.exhibits-nav').toggleClass('exhibits-on');
		if($('.exhibits-nav').hasClass('exhibits-on')) {
			$('#exhibits-sub-nav').slideDown();
		} else {
			$('#exhibits-sub-nav').slideUp();
		}
	});

	$('.collections-nav').click(function() {
		$('.collections-nav').toggleClass('collections-on');
		if($('.collections-nav').hasClass('collections-on')) {
			$('#collections-sub-nav').slideDown();
		} else {
			$('#collections-sub-nav').slideUp();
		}
	});
	
});
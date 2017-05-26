jQuery(function($) {
    $('.js-checkbox_date_to').change(function() {
	if ($(this).is(':checked')) {
		$('.js-input_date_to').attr('disabled', 'disabled');
	} else {
		$('.js-input_date_to').removeAttr('disabled').focus();
	}
    });
});
jQuery(document).ready(function () {
	jQuery('.toggle_link a img').css('vertical-align', 'bottom');
	jQuery('.toggle_link').css('margin-bottom', '5px');
	jQuery('.toggle').hide();
	jQuery('.minus_image').hide();
	jQuery('.toggle_link').click(function(e) {
		e.preventDefault();
		if (jQuery('.toggle').is(":visible")) {
			jQuery('.toggle').hide();
			jQuery('.minus_image').hide();
			jQuery('.plus_image').show();
		} else {
			jQuery('.toggle').show();
			jQuery('.plus_image').hide();
			jQuery('.minus_image').show();
		}
	});
	
	var border_width = jQuery('.toggle').css('border-width');
	if (border_width != '0px') {
		jQuery('.toggle').css('padding', '5px');
	} else {
		jQuery('.toggle').css('padding', '0');
	}
});
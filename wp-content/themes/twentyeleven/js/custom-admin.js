(function($) { $(document).ready( function() {

// Initialise state
	$(".pods-form-ui-row-name-start-date").hide();
	$(".pods-form-ui-row-name-end-date").hide();

// On article type change function
	$("select#pods-form-ui-pods-field-article-type").change(function() {
		if($(this).find("option:selected").text() == 'Event') {
			$(".pods-form-ui-row-name-start-date").show();
			$(".pods-form-ui-row-name-end-date").show();
		} else {
			$(".pods-form-ui-row-name-start-date").hide();
			$(".pods-form-ui-row-name-end-date").hide();
		}
	});


});})(jQuery);


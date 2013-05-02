(function($) { $(document).ready( function() {

function pod_admin_visibility() {
                if($("select#pods-form-ui-pods-field-article-type").find("option:selected").text() == 'Event') {
                        $(".pods-form-ui-row-name-start-date").show();
                        $(".pods-form-ui-row-name-end-date").show();
                        $(".pods-form-ui-row-name-location").show();
                } else {
                        $(".pods-form-ui-row-name-start-date").hide();
                        $(".pods-form-ui-row-name-end-date").hide();
                        $(".pods-form-ui-row-name-location").hide();
                }
}


// Initialise state
pod_admin_visibility();

// On article type change function
	$("select#pods-form-ui-pods-field-article-type").change(function() {
		pod_admin_visibility();
	});


});})(jQuery);


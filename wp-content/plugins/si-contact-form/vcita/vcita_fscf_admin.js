		
	/* --- On Load Functions - admin --- */
	
	/** 
	 * Go over each of FSC-vCita widgets in the page and load them
	 */
	jQuery(document).ready(function($) {
		if ($("#vCitaSectionAnchor").data('user-changed')) {
			location.hash = "#vCitaSettings";
		}
	});

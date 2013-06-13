
	/* --- vCita Constants --- */
	
	var VC_REQUIRED_MIN_SPACE_WIDTH = 125;
	var VC_MAX_SPACE_WIDTH = 200;
	var VC_REQUIRED_BOTTOM_HORIZONTAL_WIDTH = 330;
	var VC_BOTTOM_HORIZONTAL_HEIGHT = 120;
	var VC_BOTTOM_VERTICAL_HEIGHT = 225;

	
	/* --- On Load Functions --- */
	
	/** 
	 * Go over each of FSC-vCita widgets in the page and load them
	 */
	jQuery(document).ready(function($) {
		$(".fscf_vcita_container").each (function() {
			VC_FSCF_widget_load($(this));
		});
	});

	
	/* --- Public function --- */
	
	/** 
	 * Get a name for the cookie associated with this id
	 */
	function VC_FSCF_cookie_name(uid) {
		return "vc_widget_" + uid;
	}

	/**
	 * Set the value of the cookie with expiration date of one year.
	 */
	function VC_FSCF_set_cookie(uid, value) {
		var date = new Date();
		date.setTime(date.getTime()+(1*365*86400*1000)); // Expires in one year
		var expires = "; expires="+date.toGMTString();
		document.cookie = VC_FSCF_cookie_name(uid) + "=" + value + expires + "; path=/";
	}

	/** 
	 * Read the content of the cookie based on the uid
	 */
	function VC_FSCF_read_cookie(uid) {
		var nameEQ = VC_FSCF_cookie_name(uid) + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	}

	/** 
	 * Get the value for the confirmation token of the current user
	 */
	function VC_FSCF_get_owner_token(uid) {
		var content = VC_FSCF_read_cookie(uid);
		var token = "";

		if (content != null && content.length > 0) {
			var attributes = content.split("|||");

			for (i = 0; i < attributes .length; i++) {
				var attribute = attributes[i].split("=");

				if (attribute[0] == 'confirmation_token' && attribute.length > 1) {
					token = attribute[1];
				}
			}
			
		}
		
		return token;
	}
	
	/**
	 * Return the confirmation token for the given uid and container. 
	 * In case the token is specified on the container, it means it also should be saved in the 
	 * cookie for later use.
	 */
	function VC_FSCF_get_confirmation_token(container, uid) {
		var confirmation_token = container.attr("confirmation_token");
		
		if (confirmation_token != null && confirmation_token != "") {	
			VC_FSCF_set_cookie(uid, "confirmation_token=" + confirmation_token);
		} else {
			confirmation_token = VC_FSCF_get_owner_token(uid);
		}
		
		return confirmation_token;
	}
	
	/**
	 * Check by using cookies if the expert is associated with this domain.
	 */
	function VC_FSCF_is_admin() {
		var generic_expert = VC_FSCF_read_cookie('generic-expert');
		
		return generic_expert != null && generic_expert == "true"
	}
	
	/** 
	 * Build the url of the widget, use the cookie to add a parameter regarding the user token.
	 */
	function VC_FSCF_populate_frame(container, widget_position, widget_orientation, width, height) {
		var divClass = "";
		var uid = container.attr("vcita_uid");
		var custom_style = "color:" + container.css("color") + ";";
		var url = "http://www.vcita.com/";
		
		// Build the url according to available paramters 
		
		if (typeof uid !== 'undefined' && uid != "") {
			var confirmation_token = VC_FSCF_get_confirmation_token(container, uid);
			url += uid + "/buttons?invite=wp-fscf";
			
			if (confirmation_token != null && confirmation_token != "") {
				url += "&confirmation_token=" + confirmation_token;
			}
			
		} else {
			var is_admin = "&expert=" + (VC_FSCF_is_admin(container) ? "true" : "false");
			url += "/experts/buttons_preview?invite=wp-fscf" + is_admin;
		}
		
		if (container.attr("custom_style") != null && container.attr("custom_style") != "") {
			custom_style += container.attr("custom_style");
		}
		
		url += "&custom_style=" + encodeURIComponent(custom_style);
		
		if (widget_position == "right") {
			divClass = "vcita-widget-right";
		} else {
			divClass = "vcita-widget-bottom";
		}
		
		container.attr("class", divClass);

		container.html("<iframe style='border:none !important;' src='" + url + "&position=" + widget_position + "&orientation=" + widget_orientation + "'" + 
					   "width='" + width + "' height='" + height + "' frameborder='0' allowtransparency='true'></iframe>");
	}

	/** 
	 * Load the vCita Set meeting buttons into the available place marked by the container.
	 * The function checks how much space is availble (increasing the parent width if there is available space)
	 * and places the widget in the right side of the widget or in the bottom.
	 */
	function VC_FSCF_widget_load(container) {
		var widget_position = "";
		var widget_orientation = "vertical";
		var height = "";
		var width = "";

		// Check if there is extra room in the Fast Secure container 
		if (container.parent().parent().outerWidth(true) > container.parent().outerWidth(true)) {
			// Overcome a problem in which increasing the container size will also increase the form element 
			// Which will resolve that no extra room will be left for our buttons - happens in IE7
			container.parent().find(".fsc_data_container").css("width", container.parent().find(".fsc_data_container").outerWidth(true));

			container.parent().css("width", container.parent().parent().outerWidth(true));
		}

		var available_space = container.parent().outerWidth(true) - container.parent().children().outerWidth(true);
		
		// Check the available size - right or down
		if (available_space > VC_REQUIRED_MIN_SPACE_WIDTH) {
			var formHeight = container.parent().children().outerHeight(true) 
			width = ((available_space < VC_MAX_SPACE_WIDTH) ? available_space : VC_MAX_SPACE_WIDTH) + "px";
			height = formHeight  + "px";
			widget_position = "right";
							
		} else {
			widget_position = "bottom";

			if (container.parent().outerWidth(true) >= VC_REQUIRED_BOTTOM_HORIZONTAL_WIDTH ) {
				width = VC_REQUIRED_BOTTOM_HORIZONTAL_WIDTH + "px";
				height =  VC_BOTTOM_HORIZONTAL_HEIGHT + "px";
				widget_orientation = "horizontal";
			} else {
				width = "100%";
				height =  VC_BOTTOM_VERTICAL_HEIGHT + "px";
			}
		}
		
		VC_FSCF_populate_frame(container, widget_position, widget_orientation, width, height); 
	}
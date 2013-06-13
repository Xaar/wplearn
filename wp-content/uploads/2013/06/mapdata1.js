var simplemaps_continentmap_mapdata = {

	main_settings:{
		//General settings
		width: 800,
		background_color: '#FAFAFA',	
		background_transparent: 'yes',
		label_color: '#d5ddec',		
		border_color: '#FFFFFF',
		zoom: 'yes',
		pop_ups: 'on_click', //on_click, on_hover, or detect
	
		//Country defaults
		state_description:   'Country description',
		state_color: '#DDDDDD',
		state_hover_color: '#888888',
		state_url: 'http://simplemaps.com',
		all_states_inactive: 'no',
		
		//Location defaults
		location_description:  'Location description',
		location_color: '#FF0067',
		location_opacity: .8,
		location_url: 'http://simplemaps.com',
		location_size: 35,
		location_type: 'circle', //or circle
		all_locations_inactive: 'no',
		
		//Advanced settings - safe to ignore these
		url_new_tab: 'no',  
		initial_zoom: -1,  //-1 is zoomed out, 0 is for the first continent etc	
		initial_zoom_solo: 'yes',
		auto_load: 'yes',
	},

	state_specific:{	
		SA: {
		name: 'South America',
		description: 'default',
		color: 'default',
		hover_color: 'default',
		url: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
	},
	
	NA: {
		name: 'North America',
		description: 'default',
		color: 'default',
		hover_color: 'default',
		url: 'default' 
	},	
	
	EU: {
		name: 'Europe',
		description: 'default',
		color: 'default',
		hover_color: 'default',
		url: 'default' 
	},		
	
	AF: {
		name: 'Africa',
		description: 'default',
		color: 'default',
		hover_color: 'default',
		url: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
	},
	
	NS: {
		name: 'North Asia',
		description: 'default',
		color: 'default',
		hover_color: 'default',
		url: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
	},
	
	SS: {
		name: 'South Asia',
		description: 'default',
		color: 'default',
		hover_color: 'default',
		url: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
	},	
	
	ME: {
		name: 'Middle East',
		description: 'EWG International<br>P.O.Box 18475<br>Jebel Ali --<br>Dubai UAE<br><span class="email">kassemtofailli@gmail.com</span>',
		color: 'default',
		hover_color: 'default',
		url: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
	},	
	
	OC: {
		name: 'Oceania',
		description: 'default',
		color: 'default',	
		hover_color: 'default',
		url: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
	}
},
	
	locations:{
		0: {
			name: "Middle East",
			lat: 25.009011, 
			lng: 55.073967,
			description: 'EWG International<br>P.O.Box 18475<br>Jebel Ali --<br>Dubai UAE<br><span class="email">kassemtofailli@gmail.com</span>',
			color: 'default',
			url: 'default',
			size: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
		},


	} //end of simplemaps_worldmap_mapdata

}





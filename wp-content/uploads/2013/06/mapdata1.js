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
		state_description: '',
		state_color: '#DDDDDD',
		state_hover_color: '#888888',
		state_url: '',
		all_states_inactive: 'no',
		
		//Location defaults
		location_description:  'Location description',
		location_color: '#BB0000',
		location_opacity: 0.6,
		location_url: '',
		location_size: 30,
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
		name: '', //'South America',
		description: 'default',
		color: 'default',
		hover_color: 'default',
		url: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
	},
	
	NA: {
		name: '', //'North America', 
		description: 'default',
		color: 'default',
		hover_color: 'default',
		url: 'default' 
	},	
	
	EU: {
		name: '', //'Europe',
		description: 'default',
		color: 'default',
		hover_color: 'default',
		url: 'default' 
	},		
	
	AF: {
		name: '', //'Africa',
		description: 'default',
		color: 'default',
		hover_color: 'default',
		url: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
	},
	
	NS: {
		name: '', //'North Asia',
		description: 'default',
		color: 'default',
		hover_color: 'default',
		url: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
	},
	
	SS: {
		name: '', //'South Asia',
		description: 'default',
		color: 'default',
		hover_color: 'default',
		url: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
	},	
	
	ME: {
		name: '', //'Middle East',
		description: 'default',
		color: 'default',
		hover_color: 'default',
		url: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
	},	
	
	OC: {
		name: '', //'Oceania',
		description: 'default',
		color: 'default',	
		hover_color: 'default',
		url: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
	}
},
	
	locations:{
		0: {
			name: "Japan",
			lat: 35.707043,
			lng: 139.764402,
			description: 'Nihon Light Inc<br>3-42-1 Hongo<br>Bunkyo-ku<br>Tokyo<br>113-0033<br>Japan<br><span class="email">hiroshi@nlsinc.co.jp</span>',
			color: 'default',
			url: 'default',
			size: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
		},
                1: {
                        name: "Indonesia",
                        lat: -6.137153,
                        lng: 106.861206,
                        description: 'PT INTERGASTRA<br>Jl. P. Jayakarta<br>24/31-35<br>Jakarta<br>10730<br>Indonesia<br><span class="email">karli@intergastra.co.id</span>',
                        color: 'default',
                        url: 'default',
                        size: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
                },
                2: {
                        name: "China",
                        lat: 39.90403,
                        lng: 116.407526,
                        description: 'V-MedEd<br>Beijing<br>China<br><span class="email">michelle.xiao@vmeded.com</span>',
                        color: 'default',
                        url: 'default',
                        size: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
                },
                3: {
                        name: "Australia",
                        lat: -37.808353,
                        lng: 144.904492,
                        description: 'Inition - Asia Pacific<br>Factory 31<br>91 Moreland Street<br>Footscray<br>VIC<br>3011<br>Australia<br><span class="email">christopher.sutton@inition.com.au</span>',
                        color: 'default',
                        url: 'default',
                        size: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
                },
                4: {
                        name: "Middle East",
                        lat: 25.009011,
                        lng: 55.073967,
                        description: 'EWG International<br>P.O.Box 18475<br>Jebel Ali --<br>Dubai UAE<br><span class="email">kassemtofailli@gmail.com</span>',
                        color: 'default',
                        url: 'default',
                        size: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
                },
                5: {
                        name: "Singapore & Malaysia",
                        lat: 1.340914,
                        lng: 103.890475,
                        description: 'United BMEC Pte Ltd<br>No 2 Kim Chuan Drive<br>06-01 CSI Distribution Centre<br>Singapore 537080<br><span class="email">chngken.bmec@uwhpl.com</span>',
                        color: 'default',
                        url: 'default',
                        size: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
                },
                6: {
                        name: "India",
                        lat: 28.601261,
                        lng: 77.076593,
                        description: 'Prakash Medicos<br>WZ-428C Nangal Raya<br>New Delhi<br>110046<br><span class="email">prakashmedicos@gmail.com</span>',
                        color: 'default',
                        url: 'default',
                        size: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
                },
                7: {
                        name: "Thailand",
                        lat: 13.708056,
                        lng: 100.583889,
                        description: 'Berli Jucker Public Company Limited<br>Berli Jucker House 99<br>Soi Rubia<br>Sukhumvit 42 Road<br>Kwaeng Phrakanong<br>Khet Klongtoey<br>Bangkok<br><span class="email">pornchak@bjc.co.th</span>',
                        color: 'default',
                        url: 'default',
                        size: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
                },
                8: {
                        name: "Northern Ireland",
                        lat: 53.373772,
                        lng: -6.289068,
                        description: 'Cardiac services<br>128 Slaney Road<br>Glasnevin<br>Dublin<br><span class="email">m-reynolds@cardiac-services.com</span>',
                        color: 'default',
                        url: 'default',
                        size: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
                },
                9: {
                        name: "South Korea",
                        lat: 37.512402,
                        lng: 126.939253,
                        description: 'KyongDo Medical Simulation Corp<br>ShinWol B/D #202<br>347-2  ShinDaeBang-Dong<br>Dongjak-Gu, Seoul<br>Korea 156-847<br><span class="email">leekd9595@hanmail.net</span>',
                        color: 'default',
                        url: 'default',
                        size: 'default' //Note:  You must omit the comma after the last property in an object to prevent errors in internet explorer.
                }
	} //end of simplemaps_worldmap_mapdata
}





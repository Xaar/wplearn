<?php
/*
Plugin Name: Purify WordPress Menues
Plugin URI: http://stehle-internet.de/downloads/purify-wordpress-menues-plugin-informations-and-download/
Description: The plugin 'Purify WordPress Menues' cleans up the HTML output of WordPress menues to only the CSS classes you want. This plugin deletes the CSS classes you do not need in a navigation menu and page menu. You can select and deselect in detail any CSS class Wordpress would add to menu items via wp_nav_menu() and wp_page_menu(). The default setting is to print only the CSS classes for the current menu item. If you deactivate the plugin, your settings remains. If you delete the plugin, your settings will be deleted, too.
Version: 1.2
Author: Martin Stehle
Author URI: http://stehle-internet.de/
Author Email: m.stehle@gmx.de
License: GPLv2 or later. See license.txt for details
Copyright: 2013 Martin Stehle ( m.stehle@gmx.de )
Requirements: WordPress >= 3.0

*/

class Purify_WP_Menues {
	/*
		=================================================================================
		Construct
		=================================================================================
	*/

	/**
	* Declare class wide properties ("globals")
	*
	* @since   1.0
	*/
	private static $plugin_version = '1.2';
	private static $main_options_page_slug;
	private static $options_pages_slug;
	private static $plugin_options_names;
	private static $settings_fields_slug;
	private static $stored_settings;
	private static $settings_db_slug = 'purify_wp_menu_options_set';
	private static $text_domain_slug = 'purify_wp_menues';

	/**
	* Call needed functions at plugin start
	*
	* @since   1.0
	* @uses    set_global_vars()
	* @uses    $settings_db_slug
	* @uses    $stored_settings
	*/
	public function init_execution () {
		// load options once. If the options are not in the DB return an empty array
		self::$stored_settings = get_option( self::$settings_db_slug );
		/**
		* to do: 
		* What to do if 
		* maybe the plugin was activated successfully and
		* maybe add_option() in save_default_settings() had worked
		* but get_option() would return FALSE?
		*/
		// Admin area
		if ( is_admin() ) {
			add_action( 'admin_init', array( __CLASS__, 'load_language' ) );
			add_action( 'admin_init', array( __CLASS__, 'register_options' ) );
			add_action( 'admin_menu', array( __CLASS__, 'add_settings_page' ) );
			add_action( 'admin_menu', array( __CLASS__, 'load_admin_css' ) );
			self::set_global_vars();

		// Frontend
		} else {
			// add the reasons why you have installed this plugin
			add_filter( 'nav_menu_css_class', array( __CLASS__, 'purify_menu_item_classes' ), 10, 1 );
			add_filter( 'page_css_class',     array( __CLASS__, 'purify_page_item_classes' ), 10, 1 );
			if ( 0 == self::$stored_settings['pwpm_print_menu_item_id'] ) {
				add_filter( 'nav_menu_item_id',   array( __CLASS__, 'purify_menu_item_id' ), 10, 0 );
			}
		} // end if()
	} // end init_execution()

	/* 
		=================================================================================
		Admin Functions
		=================================================================================
	*/

	/**
	* Functions on activating the plugin
	* Run the functions when admin activates the plugin
	*
	* @since   1.0
	* @uses    check_requirements()
	* @uses    set_global_vars()
	* @uses    save_default_settings()
	*/
	public function init_activation () {
		// check if WP installation matches the plugin's requirement
		self::check_requirements();
		// build array with the options names
		self::set_global_vars();
		// store default settings
		self::save_default_settings();
	}
	
	/**
	* Check plugin's requirements and display a message if failure
	*
	* @since   1.0
	* @uses    load_language()
	* @uses    $text_domain_slug
	* @return  void             No value returned to exit the function if requirements are matched, else the function calls to die
	*/
	private function check_requirements () {
		// check requirements
		if ( version_compare( $GLOBALS['wp_version'], '3.0', '>=' ) ) {
			return;
		}
		// if requirements are not matched:
		// deactivate plugin
		deactivate_plugins( plugin_basename( __FILE__ ), false, is_network_admin() );
		// load language file for a message in the language of the WP installation
		self::load_language();
		// stop WP request and display the message with backlink. Is there a proper way than wp_die()?
		wp_die( 
			// message in browser viewport
			sprintf( '<p>%s</p>', __( 'The plugin Purify WordPress Menues requires WordPress version 3.0.0 or higher. Therefore, WordPress did not activate it. If you want to use this plugin update the Wordpress files to the latest version.', self::$text_domain_slug ) ),
			// title in title tag
			'Wordpress &rsaquo; Plugin Activation Error', 
			array( 
				// HTML status code returned
				'response'  => 200, 
				// display a back link in the returned page
				'back_link' => true 
			)
		);
	}

	/**
	* Store default values on first use of the plugin
	*
	* @since   1.0
	* @uses    $main_options_page_slug
	* @uses    $settings_fields_slug
	* @uses    $text_domain_slug
	* @uses    $plugin_options_names
	*/
	private function set_global_vars () {
		// scalar variables
		self::$main_options_page_slug = 'purify_wp_menu_options_page';
		self::$settings_fields_slug = 'purify_wp_menu_options_group';
		// arrays
		self::$plugin_options_names = array(
			'pwpm_print_menu_item_id',
			'pwpm_backward_compatibility_with_wp_page_menu',
			'pwpm_do_not_print_parent_as_ancestor',
			'pwpm_print_current_menu_ancestor',
			'pwpm_print_current_menu_item',
			'pwpm_print_current_menu_parent',
			'pwpm_print_current_object_any_parent',
			'pwpm_print_current_object_any_ancestor',
			'pwpm_print_current_page_item',
			'pwpm_print_current_page_parent',
			'pwpm_print_current_page_ancestor',
			'pwpm_print_current_type_any_parent',
			'pwpm_print_current_type_any_ancestor',
			'pwpm_print_menu_item',
			'pwpm_print_menu_item_home',
			'pwpm_print_menu_item_id_as_class',
			'pwpm_print_menu_item_object_category',
			'pwpm_print_menu_item_object_page',
			'pwpm_print_menu_item_object_tag',
			'pwpm_print_menu_item_object_custom',
			'pwpm_print_menu_item_object_any',
			'pwpm_print_menu_item_type_post_type',
			'pwpm_print_menu_item_type_taxonomy',
			'pwpm_print_page_item',
			'pwpm_print_page_item_id'
		);
	} // end set_global_vars()

	/**
	* Store default values on first use of the plugin
	*
	* @since   1.0
	* @uses    $plugin_options_names
	* @uses    $settings_db_slug
	* @return  void             No value returned to exit the function if there are stored settings
	*/
	private function save_default_settings () {
		if ( ! current_user_can( 'manage_options' ) )  {
			// use WordPress standard message for this case
			wp_die( __( 'You do not have sufficient permissions to manage options for this site.' ) );
		}
		// try to get plugin options from the database, else return empty array
		$defaults = get_option( self::$settings_db_slug, array() );
		// if there are no stored plugin options in the database, exit
		if ( ! empty( $defaults ) ) {
			return;
		}
		// set default values
		foreach ( self::$plugin_options_names as $name ) {
			$defaults[ $name ] = 0;
		}
		// set some options to 1
		$defaults['pwpm_print_current_page_item'] = 1;
		$defaults['pwpm_print_current_menu_item'] = 1;
		// store default values in the db as a single and serialized entry
		add_option( self::$settings_db_slug, $defaults );
		/** 
		* to do: finish check
		* // test if the options are stored successfully
		* if ( false === get_option( self::$settings_db_slug ) ) {
		* 	// warn if there is something wrong with the options
		* 	something like: printf( '<div class="error"><p>%s</p></div>', __( 'The settings for plugin Purify WP Menues are not stored in the database. Is the database server ok?', 'purify_wp_menues' ) );
		* }
		*/
	} // end save_default_settings()
	// end default values storage

	/**
	* Add menu page
	*
	* @since   1.0
	* @uses    $options_pages_slug
	* @uses    $text_domain_slug
	*/
	public function add_settings_page () {
		self::$options_pages_slug = add_options_page(
			// text to be displayed in the title tags of the page
			__( 'Purify WP Menues Options', self::$text_domain_slug ),
			// text to be used for the menu
			'Purify WP Menues',
			// capability required for this menu to be displayed to the user
			'manage_options',
			// slug name to refer to this menu by (if there would be options sub pages ...)
			'pwpm_settings_menu',
			// callback function to output the content for this page
			array( __CLASS__, 'print_main_options_form' )
		); // end add_options_page()
	} // end add_settings_page()

	/**
	* Print the menu page
	* Run if the plugin's options page is called
	*
	* @since   1.0
	* @uses    $text_domain_slug
	* @uses    $settings_fields_slug
	* @uses    $main_options_page_slug
	*/
	public function print_main_options_form () {
		if ( ! current_user_can( 'manage_options' ) )  {
			// use WordPress standard message for this case
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h2><?php _e( 'Purify WP Menues Options', self::$text_domain_slug ); ?></h2>
			<form method="post" action="options.php">
				<?php settings_fields( self::$settings_fields_slug ); ?>
				<?php do_settings_sections( self::$main_options_page_slug ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php	
	} // end print_main_options_form()

	/**
	* Check and return correct values for the settings
	*
	* @since   1.0
	*
	* @param   array    $input    Options and their values after submitting the form
	* @uses    $plugin_options_names
	* @return  array              Options and their sanatized values
	*/
	public function sanitize_options ( $input ) {
		foreach (self::$plugin_options_names as $name ) {
			// if option is set assign '1', else '0'
			$input[ $name ] = isset( $input[ $name ] ) ? 1 : 0 ;
		}
		return $input;
	} // end sanitize_options()

	/**
	* Define and register the options
	* Run on admin_init()
	*
	* @since   1.0
	* @uses    $text_domain_slug
	* @uses    $main_options_page_slug
	* @uses    $plugin_options_names
	* @uses    $settings_fields_slug
	* @uses    $settings_db_slug
	*/
	public function register_options () {
		$section_suffix = 'pwpm_section_';
		// add form sections headings, order by appereance
		$sections_values = array(
			'', // trick: any value for array index 0. it will not be computed later
			__( 'Special Settings', self::$text_domain_slug ), // array index 1
			__( 'Current Page Navigation Menu Items', self::$text_domain_slug ), // index 2 etc.
			__( 'Current Page Parent Menu Items', self::$text_domain_slug ),
			__( 'Current Page Ancestor Menu Items', self::$text_domain_slug ), 
			__( 'Site Front Page Menu Items', self::$text_domain_slug ),
			__( 'All Other Navigation Menu Items', self::$text_domain_slug ),
			__( 'Page Menu Items', self::$text_domain_slug )
		);
		// add section to settings
		$c = count( $sections_values );
		for ($i = 1; $i < $c; $i++) {
			add_settings_section(
				// 'id' attribute of tags
				$section_suffix . $i, 
				// title of the section.
				$sections_values[ $i ],
				// callback function that fills the section with the desired content
				array( __CLASS__, 'print_section_' . $i ),
				// menu page on which to display this section
				self::$main_options_page_slug
			); // end add_settings_section()
		} // end for()

		// set form options strings
		$options_values = array(
			'pwpm_print_menu_item_id' => array(
				'title'   => __( '#menu-item-{id}', self::$text_domain_slug ),
				'label'   => __( 'The id of the menu item is added to every menu item of navigation menues.', self::$text_domain_slug ),
				'section' => $section_suffix.'1'
			),
			'pwpm_backward_compatibility_with_wp_page_menu' => array(
				'title'   => __( 'Maintain backward compatibility with wp_page_menu().', self::$text_domain_slug ),
				'label'   => __( 'Adds the CSS classes of page menues to navigation menues.', self::$text_domain_slug ),
				'section' => $section_suffix.'1'
			),
			'pwpm_do_not_print_parent_as_ancestor' => array(
				'title'   => __( 'Do not print parent as ancestor.', self::$text_domain_slug ),
				'label'   => __( 'Does not classified the menu item which is the current parent as anchestor.', self::$text_domain_slug ),
				'section' => $section_suffix.'1'
			),
			'pwpm_print_current_menu_ancestor' => array(
				'title'   => __( '.current-menu-ancestor', self::$text_domain_slug ),
				'label'   => __( 'This class is added to menu items that correspond to a hierarchical ancestor of the currently rendered page.', self::$text_domain_slug ),
				'section' => $section_suffix.'4'
			),
			'pwpm_print_current_menu_item' => array(
				'title'   => __( '.current-menu-item', self::$text_domain_slug ),
				'label'   => __( 'This class is added to menu items that correspond to the currently rendered page.', self::$text_domain_slug ),
				'section' => $section_suffix.'2'
			),
			'pwpm_print_current_menu_parent' => array(
				'title'   => __( '.current-menu-parent', self::$text_domain_slug ),
				'label'   => __( 'This class is added to menu items that correspond to the hierarchical parent of the currently rendered page.', self::$text_domain_slug ),
				'section' => $section_suffix.'3'
			),
			'pwpm_print_current_object_any_ancestor' => array(
				'title'   => __( '.current-{object}-ancestor', self::$text_domain_slug ),
				'label'   => __( 'This class is added to menu items that correspond to a hierachical ancestor of the currently rendered object, where {object} corresponds to the the value used for .menu-item-object-{object}.', self::$text_domain_slug ),
				'section' => $section_suffix.'4'
			),
			'pwpm_print_current_object_any_parent' => array(
				'title'   => __( '.current-{object}-parent', self::$text_domain_slug ),
				'label'   => __( 'This class is added to menu items that correspond to the hierachical parent of the currently rendered object, where {object} corresponds to the the value used for .menu-item-object-{object}.', self::$text_domain_slug ),
				'section' => $section_suffix.'3'
			),
			'pwpm_print_current_page_item' => array(
				'title'   => __( '.current_page_item', self::$text_domain_slug ),
				'label'   => __( 'This class is added to page menu items that correspond to the currently rendered static page.', self::$text_domain_slug ),
				'section' => $section_suffix.'7'
			),
			'pwpm_print_current_page_parent' => array(
				'title'   => __( '.current_page_parent', self::$text_domain_slug ),
				'label'   => __( 'This class is added to page menu items that correspond to the hierarchical parent of the currently rendered static page.', self::$text_domain_slug ),
				'section' => $section_suffix.'7'
			),
			'pwpm_print_current_page_ancestor' => array(
				'title'   => __( '.current_page_ancestor', self::$text_domain_slug ),
				'label'   => __( 'This class is added to page menu items that correspond to a hierarchical ancestor of the currently rendered static page.', self::$text_domain_slug ),
				'section' => $section_suffix.'7'
			),
			'pwpm_print_current_type_any_ancestor' => array(
				'title'   => __( '.current-{type}-ancestor', self::$text_domain_slug ),
				'label'   => __( 'This class is added to menu items that correspond to a hierachical ancestor of the currently rendered type, where {type} corresponds to the the value used for .menu-item-type-{type}.', self::$text_domain_slug ),
				'section' => $section_suffix.'4'
			),
			'pwpm_print_current_type_any_parent' => array(
				'title'   => __( '.current-{type}-parent', self::$text_domain_slug ),
				'label'   => __( 'This class is added to menu items that correspond to the hierachical parent of the currently rendered type, where {type} corresponds to the the value used for .menu-item-type-{type}.', self::$text_domain_slug ),
				'section' => $section_suffix.'3'
			),
			'pwpm_print_menu_item' => array(
				'title'   => __( '.menu-item', self::$text_domain_slug ),
				'label'   => __( 'This class is added to every menu item.', self::$text_domain_slug ),
				'section' => $section_suffix.'6'
			),
			'pwpm_print_menu_item_home' => array(
				'title'   => __( '.menu-item-home', self::$text_domain_slug ),
				'label'   => __( 'This class is added to menu items that correspond to the site front page.', self::$text_domain_slug ),
				'section' => $section_suffix.'5'
			),
			'pwpm_print_menu_item_id_as_class' => array(
				'title'   => __( '.menu-item-{id}', self::$text_domain_slug ),
				'label'   => __( 'This class with the item id is added to every menu item.', self::$text_domain_slug ),
				'section' => $section_suffix.'6'
			),
			'pwpm_print_menu_item_object_any' => array(
				'title'   => __( '.menu-item-object-{object}', self::$text_domain_slug ),
				'label'   => __( 'This class is added to every menu item, where {object} is either a post type or a taxonomy.', self::$text_domain_slug ),
				'section' => $section_suffix.'6'
			),
			'pwpm_print_menu_item_object_category' => array(
				'title'   => __( '.menu-item-object-category', self::$text_domain_slug ),
				'label'   => __( 'This class is added to menu items that correspond to a category.', self::$text_domain_slug ),
				'section' => $section_suffix.'6'
			),
			'pwpm_print_menu_item_object_custom' => array(
				'title'   => __( '.menu-item-object-{custom}', self::$text_domain_slug ),
				'label'   => __( 'This class is added to menu items that correspond to a custom post type or a custom taxonomy.', self::$text_domain_slug ),
				'section' => $section_suffix.'6'
			),
			'pwpm_print_menu_item_object_page' => array(
				'title'   => __( '.menu-item-object-page', self::$text_domain_slug ),
				'label'   => __( 'This class is added to menu items that correspond to static pages.', self::$text_domain_slug ),
				'section' => $section_suffix.'6'
			),
			'pwpm_print_menu_item_object_tag' => array(
				'title'   => __( '.menu-item-object-tag', self::$text_domain_slug ),
				'label'   => __( 'This class is added to menu items that correspond to a tag.', self::$text_domain_slug ),
				'section' => $section_suffix.'6'
			),
			'pwpm_print_menu_item_type_post_type' => array(
				'title'   => __( '.menu-item-type-post_type', self::$text_domain_slug ),
				'label'   => __( 'This class is added to menu items that correspond to post types, i.e. static pages or custom post types.', self::$text_domain_slug ),
				'section' => $section_suffix.'6'
			),
			'pwpm_print_menu_item_type_taxonomy' => array(
				'title'   => __( '.menu-item-type-taxonomy', self::$text_domain_slug ),
				'label'   => __( 'This class is added to menu items that correspond to taxonomies, i.e. categories, tags, or custom taxonomies.', self::$text_domain_slug ),
				'section' => $section_suffix.'6'
			),
			'pwpm_print_page_item' => array(
				'title'   => __( '.page_item', self::$text_domain_slug ),
				'label'   => __( 'This class is added to page menu items that correspond to a static page.', self::$text_domain_slug ),
				'section' => $section_suffix.'7'
			),
			'pwpm_print_page_item_id' => array(
				'title'   => __( '.page-item-{id}', self::$text_domain_slug ),
				'label'   => __( 'This class is added to page menu items that correspond to a static page, where ID is the static page ID.', self::$text_domain_slug ),
				'section' => $section_suffix.'7'
			)
		); // end assigning to $options_values
		// add form options
		foreach (self::$plugin_options_names as $name ) {
			add_settings_field(
				// form field name for use in the 'id' attribute of tags
				$name,
				// title of the form field
				$options_values[ $name ]['title'],
				// callback function to render the form field
				array( __CLASS__, 'print_option_checkbox' ),
				// menu page on which to display this field for do_settings_section()
				self::$main_options_page_slug,
				// section where the form field appears
				$options_values[ $name ]['section'],
				// arguments passed to the callback function print_option_checkbox() 
				array(
					'id'    => $name,
					'label' => $options_values[ $name ]['label']
				)
			); // end add_settings_field()
		} // end foreach()

		// finally register all options. They will be stored in the database in the wp_options table under the options name self::$settings_db_slug.
		register_setting( 
			// group name in settings_fields()
			self::$settings_fields_slug,
			// name of the option to sanitize and save in the db
			self::$settings_db_slug,
			// callback function that sanitizes the option's value.
			array( __CLASS__, 'sanitize_options' )
		); // end register_setting()
	} // end register_options()

	/**
	* Print the explanation for section 1
	*
	* @since   1.0
	* @uses    $text_domain_slug
	*/
	public function print_section_1 () {
		printf( "<p>%s</p>\n", __( 'In this section you control some basic settings.', self::$text_domain_slug ) );
	}

	/**
	* Print the explanation for section 2
	*
	* @since   1.0
	* @uses    $text_domain_slug
	*/
	public function print_section_2 () {
		printf( "<p>%s</p>\n", __( 'In this section you control the CSS class Wordpress adds to the current menu item.', self::$text_domain_slug ) );
	}

	/**
	* Print the explanation for section 3
	*
	* @since   1.0
	* @uses    $text_domain_slug
	*/
	public function print_section_3 () {
		printf( "<p>%s</p>\n", __( 'In this section you control the CSS classes Wordpress adds to the hierarchical parent of the current menu item.', self::$text_domain_slug ) );
	}

	/**
	* Print the explanation for section 4
	*
	* @since   1.0
	* @uses    $text_domain_slug
	*/
	public function print_section_4 () {
		printf( "<p>%s</p>\n", __( 'In this section you control the CSS classes Wordpress adds to the hierarchical anchestors of the current menu item.', self::$text_domain_slug ) );
	}

	/**
	* Print the explanation for section 5
	*
	* @since   1.0
	* @uses    $text_domain_slug
	*/
	public function print_section_5 () {
		printf( "<p>%s</p>\n", __( 'In this section you control the CSS class Wordpress adds to the menu item of the front page.', self::$text_domain_slug ) );
	}

	/**
	* Print the explanation for section 6
	*
	* @since   1.0
	* @uses    $text_domain_slug
	*/
	public function print_section_6 () {
		printf( "<p>%s</p>\n", __( 'In this section you control all other classes Wordpress adds to menu items.', self::$text_domain_slug ) );
	}

	/**
	* Print the explanation for section 7
	*
	* @since   1.0
	* @uses    $text_domain_slug
	*/
	public function print_section_7 () {
		printf( "<p>%s</p>\n", __( 'In this section you control the CSS classes Wordpress adds to items in a page menu via wp_page_nav(). Menues via wp_nav_menu() only have this classes if the backward compatibility option is checked.', self::$text_domain_slug ) );
	}

	/**
	* Print the explanation for section 8
	*
	* @since   1.0
	* @uses    $text_domain_slug
	*/
	public function print_section_8 () {
		printf( "<p>%s</p>\n", __( 'In this section you control the CSS class Wordpress adds to the hierarchical parent of the current item in a page menu.', self::$text_domain_slug ) );
	}

	/**
	* Print the explanation for section 9
	*
	* @since   1.0
	* @uses    $text_domain_slug
	*/
	public function print_section_9 () {
		printf( "<p>%s</p>\n", __( 'In this section you control the CSS class Wordpress adds to the hierarchical anchestors of the current item in a page menu.', self::$text_domain_slug ) );
	}

	/**
	* Print the explanation for section 10
	*
	* @since   1.0
	* @uses    $text_domain_slug
	*/
	public function print_section_10 () {
		printf( "<p>%s</p>\n", __( 'In this section you control all other classes Wordpress adds to items in a page menu.', self::$text_domain_slug ) );
	}

	/**
	* Print the option checkbox
	*
	* @since   1.0
	*
	* @param   array    $args    Strings accessible by key
	* @uses    $stored_settings
	* @uses    $settings_db_slug
	*/
	public function print_option_checkbox ( $args ) {
		$options = self::$stored_settings;
		$id = $args['id'];
		printf( '<label for="%s"><input type="checkbox" id="%s" name="%s[%s]" value="1" ', $id, $id, self::$settings_db_slug, $id );
		echo checked( '1', $options[ $id ], false );
		printf( ' /> %s</label>', $args['label'] );
	}

	/**
	* Delete options from the database while deleting the plugin files
	* Run before deleting the plugin
	*
	* @since   1.0
	* @uses    $settings_db_slug
	* @uses    $wpdb
	*/
	public function delete_settings () {
		// remove settings
		delete_option( self::$settings_db_slug ); 
		// clean DB
		global $wpdb;
		$wpdb->query( "OPTIMIZE TABLE `" .$wpdb->options. "`" );
	}
	// end delete options while deleting the plugin

	/**
	* Register CSS to settings page of this plugin
	*
	* @since   1.0
	* @uses    $plugin_version
	*/
	public function apply_admin_css () {
		// register CSS only to this plugin's option page
		wp_register_style( 'pwpm-plugin-page-css', plugins_url( 'css/settings.css', __FILE__ ), array(), self::$plugin_version, 'all' );
		// enqueue
		wp_enqueue_style( 'pwpm-plugin-page-css' );
	}

	/**
	* Add link to  CSS of settings page of this plugin
	*
	* @since   1.0
	* @uses    $options_pages_slug
	*/
	public function load_admin_css () {
		add_action( 'admin_print_styles-' . self::$options_pages_slug, array( __CLASS__, 'apply_admin_css' ) );
	}
	// end CSS addition

	/**
	* Load language file
	*
	* @since   1.0
	* @uses    $text_domain_slug
	*/
	public function load_language () {
		load_plugin_textdomain( self::$text_domain_slug, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	// end load language

	/*
		=================================================================================
		Frontend Functions
		=================================================================================
	*/

	/**
	* Clean the CSS classes of items in navigation menues
	*
	* @since   1.0
	*
	* @param   array    $css_classes    Strings wp_nav_menu() builded for a single menu item
	* @uses    $stored_settings
	* @uses    purify_page_item_classes()
	* @return  array|string             Empty string if param is not an array, else the array with strings for the menu item
	*/
	public function purify_menu_item_classes ( $css_classes ) {
		if ( ! is_array( $css_classes ) ) {
			return '';
		}

		$item_is_parent = false;
		$classes = array();
		$options = self::$stored_settings;

		foreach ( $css_classes as $class ) {

			// This class is added to every menu item. 
			if ( $options['pwpm_print_menu_item'] && 'menu-item' == $class ) {
				$classes[] = 'menu-item';
				continue;
			}

			// This class with the item id is added to every menu item. 
			if ( $options['pwpm_print_menu_item_id_as_class'] && preg_match( '/menu-item-[0-9]+/', $class, $matches ) ) {
				$classes[] = $matches[0]; # 'menu-item-' . $item->ID;
				continue;
			}

			// This class is added to menu items that correspond to a category. 
			if ( $options['pwpm_print_menu_item_object_category'] && 'menu-item-object-category' == $class ) {
				$classes[] = 'menu-item-object-category';
				continue;
			}

			// This class is added to menu items that correspond to a tag. 
			if ( $options['pwpm_print_menu_item_object_tag'] && 'menu-item-object-tag' == $class ) {
				$classes[] = 'menu-item-object-tag';
				continue;
			}

			// This class is added to menu items that correspond to static pages. 
			if ( $options['pwpm_print_menu_item_object_page'] && 'menu-item-object-page' == $class ) {
				$classes[] = 'menu-item-object-page';
				continue;
			}

			// This class is added to every menu item, where {object} is either a post type or a taxonomy.
			if ( $options['pwpm_print_menu_item_object_any'] && preg_match( '/menu-item-object-[^-]+/', $class, $matches ) ) {
				$classes[] = $matches[0];
				continue;
			}

			/* double of menu_item_object_any? */
			// This class is added to menu items that correspond to a custom post type or a custom taxonomy. 
			if ( $options['pwpm_print_menu_item_object_custom'] && preg_match( '/menu-item-object-[^-]+/', $class, $matches ) ) {
				$classes[] = $matches[0];
				continue;
			}

			// This class is added to menu items that correspond to post types { i.e. static pages or custom post types. 
			if ( $options['pwpm_print_menu_item_type_post_type'] && 'menu-item-type-post_type' == $class ) {
				$classes[] = 'menu-item-type-post_type';
				continue;
			}

			// This class is added to menu items that correspond to taxonomies { i.e. categories, tags, or custom taxonomies. 
			if ( $options['pwpm_print_menu_item_type_taxonomy'] && 'menu-item-type-taxonomy' == $class ) {
				$classes[] = 'menu-item-type-taxonomy';
				continue;
			}

			// This class is added to menu items that correspond to the currently rendered page. 
			if ( $options['pwpm_print_current_menu_item'] && 'current-menu-item' == $class ) {
				$classes[] = 'current-menu-item';
				continue;
			}

			// This class is added to menu items that correspond to the hierarchical parent of the currently rendered page. 
			if ( $options['pwpm_print_current_menu_parent'] && 'current-menu-parent' == $class ) {
				$classes[] = 'current-menu-parent';
				$item_is_parent = true;
				continue;
			}

			// This class is added to menu items that correspond to the hierachical parent of the currently rendered type, where {type} corresponds to the the value used for .menu-item-type-{type}. 
			if ( $options['pwpm_print_current_type_any_parent'] && preg_match( '/current-( post_type|taxonomy )-parent/', $class, $matches ) ) {
				$classes[] = $matches[0];
				$item_is_parent = true;
				continue;
			}

			// This class is added to menu items that correspond to the hierachical parent of the currently rendered object, where {object} corresponds to the the value used for .menu-item-object-{object}. 
			if ( $options['pwpm_print_current_object_any_parent'] && preg_match( '/current-[^-]+-parent/', $class, $matches ) ) {
				$classes[] = $matches[0];
				$item_is_parent = true;
				continue;
			}

			// This class is added to menu items that correspond to a hierarchical ancestor of the currently rendered page. 
			if ( $options['pwpm_print_current_menu_ancestor'] && 'current-menu-ancestor' == $class ) {
				$classes[] = 'current-menu-ancestor';
				continue;
			}

			// This class is added to menu items that correspond to a hierachical ancestor of the currently rendered type, where {type} corresponds to the the value used for .menu-item-type-{type}. 
			if ( $options['pwpm_print_current_type_any_ancestor'] && preg_match( '/current-( post_type|taxonomy )-ancestor/', $class, $matches ) ) {
				$classes[] = $matches[0];
				continue;
			}

			// This class is added to menu items that correspond to a hierachical ancestor of the currently rendered object, where {object} corresponds to the the value used for .menu-item-object-{object}. 
			if ( $options['pwpm_print_current_object_any_ancestor'] && preg_match( '/current-[^-]+-ancestor/', $class, $matches ) ) {
				$classes[] = $matches[0];
				continue;
			}

			// This class is added to menu items that correspond to the site front page. 
			if ( $options['pwpm_print_menu_item_home'] && 'menu-item-home' == $class ) {
				$classes[] = 'menu-item-home';
				// last statement before loop end does not need a continue
			}

		} // end foreach()

		// delete ancestor classes if users does not wish them on parent items
		if ( $options['pwpm_do_not_print_parent_as_ancestor'] && $item_is_parent ) {
			// regular expression search on array values
			$keys = array();
			foreach ( $classes as $key => $val ) {
				if ( preg_match( '/current-[^-]+-ancestor/', $val ) ) {
					$keys[] = $key;
				}
			}
			// delete ancestor classes if found
			if ( $keys ) {
				foreach ( $keys as $key ) {
					unset( $classes[ $key ] );
				}
			}
		} // end if()

		// Backward Compatibility with wp_page_menu() 
		// the following classes are added to maintain backward compatibility with the wp_page_menu() function output
		if ( $options['pwpm_backward_compatibility_with_wp_page_menu'] ) {
			$classes = array_merge( $classes, self::purify_page_item_classes( $css_classes ) );
		}

		// Returns the new set of css classes for the item
		return array_intersect( $css_classes, $classes );

	} // end purify_menu_item_classes()

	/**
	* Clean the id attribute of items in navigation menues
	*
	* @since   1.0
	*
	* @uses    $stored_settings
	* @return  string                     Empty string if param should not be returned, else the param itself
	*/
	public function purify_menu_item_id () {
		return '';
	} // end purify_menu_item_id()

	/**
	* Clean the CSS classes of items in page menues
	*
	* @since   1.0
	*
	* @param   array    $css_classes    Strings wp_page_menu() builded for a single item
	* @uses    $stored_settings
	* @return  array|string             Empty string if param is not an array, else the array with strings for the menu item
	*/
	public function purify_page_item_classes( $css_classes ) {
		if ( ! is_array( $css_classes ) ) {
			return '';
		}

		$options = self::$stored_settings;
		$item_is_parent = false;
		$classes = array();

		foreach ( $css_classes as $class ) {
			// This class is added to menu items that correspond to a static page. 
			if ( $options['pwpm_print_page_item'] && 'page_item' == $class ) {
				$classes[] = 'page_item';
				continue;
			}

			// This class is added to menu items that correspond to a static page, where $ID is the static page ID. 
			if ( $options['pwpm_print_page_item_id'] && preg_match( '/page-item-[0-9]+/', $class, $matches ) ) {
				$classes[] = $matches[0];
				continue;
			}

			// This class is added to menu items that correspond to the currently rendered static page. 
			if ( $options['pwpm_print_current_page_item'] && 'current_page_item' == $class ) {
				$classes[] = 'current_page_item';
				continue;
			}

			// This class is added to menu items that correspond to the hierarchical parent of the currently rendered static page. 
			if ( $options['pwpm_print_current_page_parent'] && 'current_page_parent' == $class ) {
				$classes[] = 'current_page_parent';
				$item_is_parent = true;
				continue;
			}

			// This class is added to menu items that correspond to a hierarchical ancestor of the currently rendered static page. 
			if ( $options['pwpm_print_current_page_ancestor'] && 'current_page_ancestor' == $class ) {
				$classes[] = 'current_page_ancestor';
				// last, no continue;
			}
		} // end foreach

		// delete ancestor class if users does not wish it on parent items
		if ( $options['pwpm_do_not_print_parent_as_ancestor'] && $item_is_parent ) {
			// regular expression search on array values
			$key = array_search( 'current_page_ancestor', $classes );
			// delete ancestor classes if found
			unset( $classes[ $key ] );
		}

		// Returns the classes for the item
		return array_intersect( $css_classes, $classes );
	} // end purify_page_item_classes()

} // end class Purify_WP_Menues()

$class_name = 'Purify_WP_Menues';
// function on activating the plugin
register_activation_hook( __FILE__,  array( $class_name, 'init_activation' ) );
// function on deleting the plugin
register_uninstall_hook( __FILE__,  array( $class_name, 'delete_settings' ) );
// function on requesting the plugin
add_action( 'plugins_loaded',  array( $class_name, 'init_execution' ) );

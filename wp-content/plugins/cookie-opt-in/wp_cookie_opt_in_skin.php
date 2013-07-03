<?php
/*
Plugin Name: Cookie-Opt-In - Example skin
Plugin URI: http://wordpress.clearsite.nl
Description: Example skin for the Cookie_opt_in plugin
Version: 1.4.4
Author: Clearsite Webdesigners | Remon Pel
Author URI: http://clearsite.nl/author/rmpel
*/
if (!class_exists('CookieOptIn')) {
  require_once ('wp_cookie_opt_in.php');
}

add_filter('do_not_load_cookie_opt_in_visual_effects', 'wp_cookie_opt_in_skin_no_visuals');
function wp_cookie_opt_in_skin_no_visuals() {
  return true;
}

add_filter('init', 'wp_cookie_opt_in_skin_init');
function wp_cookie_opt_in_skin_init() {
  if (!is_admin()) {
    wp_enqueue_script('cookie-opt-in-if', plugins_url('js/cls-cookie-opt-in-if.js', __FILE__), array('jquery', 'cookie-opt-in'), $ver = 1, $in_footer = true);
    wp_enqueue_style('cookie-opt-in', plugins_url('css/cls-style.css', __FILE__));
  }
}

/* augmenting the settings panel */
add_action('wp_cookie_opt_in_admin', 'wp_cookie_opt_in_skin_admin_hook');
function wp_cookie_opt_in_skin_admin_hook($args) {
  // admin-template variables are handed down to us;
  list($section, $subsection, $langshow, $langtag, $systag, $cookie_opt_in_language, $cookie_opt_in_system) = $args;

  // $section   : either 'general', or a cookie-type (functional, social, tracking, advertisement)
  // $subsection: the 'top' or 'bottom' of the section. the section 'general' also has subsection 'textual',
  //              which is at the end of the 'textual' part of the general settings.
  // $langshow  : In a multi-language WordPress (WPML) this variable is true when an Admin-Interface language is chosen.
  //              If not, this variable is false and only system settings are shown.
  //              In a single-language install, this variable is always true.
  // $langtag   : In a multi-language WordPress (WPML) this contains a language-specific flag
  // $systag    : When a flag is shown, its hard to see the system settings, so this will then contain a sytem-settings-icon
  // $cookie_opt_in_language and $cookie_opt_in_settings contain the current settings of the system.

  // augment the settings tree with our own new items
  if (!@$cookie_opt_in_language['view_details']) $cookie_opt_in_language['view_details'] = 'View details';

  // display the extra form elements.
  switch ($section) {
    case 'general':
      // this hooks in the general part of the admin panel
      switch ($subsection) {
        case 'textual':
          // this is the textual part of the general settings section
        ?>
        <tr>
          <th class="left">
            <?php print $langtag; ?>
            <?php _e('View details link', 'cookie_opt_in'); ?>
          </th>
          <td>
            <input type="text" name="cookie_opt_in_language[view_details]" value="<?php print esc_attr($cookie_opt_in_language['view_details']); ?>" />
          </td>
          <td colspan="2" class="right">
            <?php _e('This is the text of the anchor that shows the details.', 'cookie_opt_in'); ?>
          </td>
        </tr>
        <?php
        break;
      }
    break;
    case 'functional':
    // this hooks in a specific part of the admin form; the functional cookies section
      switch($subsection) {
        case 'top':
        // this is printed just below the on/off switch for this section
        break;
        case 'bottom':
        // this is printed just before the end of this section
        break;
      }
    break;
  }
}
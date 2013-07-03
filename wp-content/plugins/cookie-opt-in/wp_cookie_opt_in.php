<?php
/*
Plugin Name: Cookie-Opt-In
Plugin URI: http://wordpress.clearsite.nl
Description: In the EU you must have explicit permission to place cookies other than functionally required and you must provide information on every cookie you place.
Version: 1.4.4
Author: Clearsite Webdesigners | Remon Pel
Author URI: http://clearsite.nl/author/rmpel
*/

// init hooks
add_action('init', array('CookieOptIn', 'init'));
add_action('admin_init', array('CookieOptIn', 'admin_init'));
add_action('wp_head', array('CookieOptIn', 'wp_head'));
add_action('admin_menu', array('CookieOptIn', 'admin_menu'));
#add_action('login_head', array('CookieOptIn', 'login_head'));
add_action('admin_head', array('CookieOptIn', 'admin_head'));

// this hooks must absolutely be last, otherwise removing an action won't work.
add_action('init', array('CookieOptIn', 'just_in_time_init'), 100000000000);

add_filter('eu_cookie_consent', array('CookieOptIn', 'eu_cookie_consent'));

if (function_exists('is_admin') && is_admin() && $_POST && $_POST['is_cookie_opt_in'])
{
  add_action('init', array('CookieOptIn', 'admin_post'));
}

class CookieOptIn {
  /**
   * easy access to the sitepress global
   * @return object|null the Sitepress object if it exists
   */
  private function wpml() {
    global $sitepress;
    return $sitepress;
  }

  /**
   * Is the website WPML-Enabled?
   * @return boolean true for WPML is active
   */
  public function is_ml() {
    if (self::wpml()) return true;
  }

  /**
   * Get the language(s) based on the second parameter
   * if $what = admin ; return the language selected for content in the back-end.
   * if $what = front ; return the language selected by the visitor.
   * if $what = list ; return a list of languages available in the system.
   * if $what = default ; return the language set in the CONFIG file.
   * if WPML is not active, admin, front and list will return the default language.
   * @param  string $what What language(s) to get
   * @return string|array the language(s) requested
   */
  public function language($what) {
    switch ($what) {
      case 'default':
        list($wordpress_language, $dialect) = explode('-', get_bloginfo('language'));
        return $wordpress_language;
      break;
      case 'admin':
        if (self::is_ml()) return self::wpml()->get_current_language();
        return self::language('default');
      break;
      case 'front':
        if (self::is_ml()) return ICL_LANGUAGE_CODE;
        return self::language('default');
      break;
      case 'list':
        if (self::is_ml()) {
          $languages = self::wpml()->get_active_languages();
        }
        else {
          $l = self::language('default');
          $languages = array(
            $l => array(
              'id' => '0',
              'code' => $l,
              'english_name' => __('WordPress default language', 'cookie_opt_in'),
              'active' => 1,
              'display_name' => __('WordPress default language', 'cookie_opt_in'),
              'encode_url' => 0,
              'native_name' => __('WordPress default language', 'cookie_opt_in'),
            )
          );
        }
        return $languages;
      break;
    }
  }

  public static function init() {
    self::maybe_upgrade();

    load_plugin_textdomain('cookie_opt_in', false, dirname( plugin_basename( __FILE__ ) ) .'/lang/' );
    // wp init
    wp_enqueue_script('cookie-opt-in', plugins_url('js/cookie-opt-in.js', __FILE__), array('jquery'), $ver = 1, $in_footer = true);
    $return = apply_filters('do_not_load_cookie_opt_in_visual_effects', false);
    if (!$return) {
      wp_enqueue_script('cookie-opt-in-if', plugins_url('js/cookie-opt-in-if.js', __FILE__), array('jquery', 'cookie-opt-in'), $ver = 1, $in_footer = true);
      wp_enqueue_style('cookie-opt-in', plugins_url('css/style.css', __FILE__));
    }
  }

  public static function admin_init() {
    // admin init
    if (isset($_GET['page']) && in_array($_GET['page'], array('cookie-opt-in', 'cookie-opt-in-admin', 'cookie-opt-in-settings', 'cookie-opt-in-dev', 'cookie-opt-in-actions'))) {
      wp_enqueue_style('cookie-opt-in-admin', plugins_url('css/admin.css', __FILE__));
    }
  }

  public static function admin_menu() {
    // admin menu
    add_menu_page(__('Cookie-Opt-In', 'cookie_opt_in'), __('Cookie-Opt-In', 'cookie_opt_in'), 'manage_options', 'cookie-opt-in', array('CookieOptIn', 'admin_info'), plugins_url('css/clearsite-icon.png', __FILE__));
    add_submenu_page('cookie-opt-in', __('Cookie-Opt-In Info', 'cookie_opt_in'), __('Information', 'cookie_opt_in'), 'manage_options', 'cookie-opt-in', array('CookieOptIn', 'admin_info'));
    add_submenu_page('cookie-opt-in', __('Cookie-Opt-In Developers Info', 'cookie_opt_in'), __('Developers info', 'cookie_opt_in'), 'manage_options', 'cookie-opt-in-dev', array('CookieOptIn', 'admin_info_dev'));
    add_submenu_page('cookie-opt-in', __('Cookie-Opt-In Settings', 'cookie_opt_in'), __('Settings', 'cookie_opt_in'), 'manage_options', 'cookie-opt-in-settings', array('CookieOptIn', 'admin_page'));
    add_submenu_page('cookie-opt-in', __('Cookie-Opt-In Actions Overview', 'cookie_opt_in'), __('Actions overview', 'cookie_opt_in'), 'manage_options', 'cookie-opt-in-actions', array('CookieOptIn', 'admin_actions'));
  }

  function admin_info() {
    $settings = self::settings();
    require (dirname(__FILE__) .'/templates/admin_info.php');
  }

  function admin_info_dev() {
    $settings = self::settings();
    require (dirname(__FILE__) .'/templates/admin_developers.php');
  }

  function admin_actions() {
    if ($_GET['refresh_action_cache']) {
      delete_option('wp_cookie_opt_in_action_table');
      $refresh_done = time();
    }
    else {
      $_actiontable = get_option('wp_cookie_opt_in_action_table', false);
      if ($_actiontable) {
        $actiontable = array();
        $sort = array();
        foreach ($_actiontable as $action => $_level1) {
          foreach ($_level1 as $priority => $_level2) {
            foreach ($_level2 as $idx => $function) {
              $function = $function['function'];
              $row = array();
              $row[] = $action;
              $row[] = is_array($function) ? ( is_object($function[0]) ? '[OBJECT]' : implode('::', $function)) : $function;
              $row[] = $priority;
              $__action = '<code>'. $action .':' . (is_array($function) ? ( is_object($function[0]) ? 'IDX#'. $idx : implode(',', $function) ) : $function) .':'. $priority .'</code>';
              if (is_array($function) && is_object($function[0])) {
                $__action = '[WARNING]';
              }
              $row[] = $__action;
              $sort[] = implode(',', $row);
              $actiontable[] = $row;
            }
          }
        }
      }
      if ($sort) array_multisort($sort, $actiontable);
    }
    require (dirname(__FILE__) .'/templates/admin_actions.php');
  }

  function admin_page() {
    error_reporting(E_ALL);
    $cookie_opt_in_system = self::settings('cookie_opt_in_system');
    $cookie_opt_in_language = self::settings('cookie_opt_in_language');
    require (dirname(__FILE__) .'/templates/admin_page.php');
  }

  public static function admin_post() {
    // admin post
    if (wp_verify_nonce($_POST['is_cookie_opt_in'], 'is_cookie_opt_in')) {
			if (!empty($_POST['cookie_opt_in_system']['cookies_to_destroy'])) {
				foreach ($_POST['cookie_opt_in_system']['cookies_to_destroy'] as $key => $value) {
					$_POST['cookie_opt_in_system']['cookies_to_destroy'][$key] = array_values(array_filter(explode("\n", str_replace("\r", "\n", $value))));
				}
			}
      foreach ($_POST['cookie_opt_in_system']['un_action'] as $key => $value) {
        $_POST['cookie_opt_in_system']['un_action'][$key] = array_values(array_filter(explode("\n", str_replace("\r", "\n", $value))));
      }
      update_option('cookie_opt_in_system', $_POST['cookie_opt_in_system']);
      update_option('cookie_opt_in_language_'. self::language('admin'), $_POST['cookie_opt_in_language']);
    }
    wp_redirect(remove_query_arg('bla'));
    exit;
  }

  public static function admin_head() {
    // admin head
    print '<script type="text/javascript">var cookie_opt_in_settings = false;</script>';
  }

  public static function login_head() {
    // admin head
    return self::wp_head();
  }

  public static function wp_head() {
    // front head
    $settings = self::settings(null, self::language('front'));
    $settings['default_cookie'] = '';
    $settings['destroy'] = array();
    $settings['cookie_types'] = array();
    foreach (array('site_has_functional_cookies', 'site_has_advertisement_cookies', 'site_has_tracking_cookies', 'site_has_social_cookies') as $i) {
      if ($settings[$i]) {
        $settings['default_cookie'] .= substr($i,9,1) . $settings[str_replace('site_has_', 'default_value_', $i)];
        $settings['cookie_types'][] = $i;
      }
      if (!self::visitor_accepts($shorttag = substr($i,9,-8))) {
        $settings['destroy'] = array_merge($settings['destroy'], (array)$settings['cookies_to_destroy'][$shorttag]);
      }
    }

    unset($settings['cookies_to_destroy']);
    unset($settings['un_action']);
    unset($settings['un_action_unchangeable']);

    if ($settings['preference_cookie_expires'] == 'never') {
      // $settings['preference_cookie_expires'] = strtotime('31 december 2149');
      // if (!$settings['preference_cookie_expires']) $settings['preference_cookie_expires'] = strtotime('31 december 2037');
      // No longer use 2149, it may be responsible for not working on 32-bit OS. Maybe... Can't be sure, we're 64 bit for ages.
      $settings['preference_cookie_expires'] = strtotime('31 december 2037');
    }
    elseif ($settings['preference_cookie_expires'] == 'session_end') {
      $settings['preference_cookie_expires'] = false;
    }
    elseif ($settings['preference_cookie_expires'] <= 315576000) { // <= ten years ? assume ttl
      $settings['preference_cookie_expires'] += current_time('timestamp');
    }
    elseif ($settings['preference_cookie_expires'] > 315576000) { // > ten years ? assume timestamp or date
      $settings['preference_cookie_expires'] = is_int($settings['preference_cookie_expires']) ? $settings['preference_cookie_expires'] : strtotime($settings['preference_cookie_expires']);
    }

    if ($settings['preference_cookie_expires']) {
      $settings['preference_cookie_expires'] = date('r', $settings['preference_cookie_expires']);
    }

    $settings['label_ok'] = __('Ok', 'cookie_opt_in');
    $settings['label_deny'] = __('Deny', 'cookie_opt_in');
    $settings['label_allow'] = __('Allow', 'cookie_opt_in');

    $settings = array_filter($settings);

    $settings['baseurl'] = plugins_url('', __FILE__);

    $settings = apply_filters('cookie_opt_in_settings', $settings);

    print '<script type="text/javascript">var cookie_opt_in_settings = '. json_encode($settings) .';</script>';
  }

  public static function settings($get_just_this=null, $lang=null) {
    // language independent settings
    $cookie_opt_in_system = get_option('cookie_opt_in_system', array());
    foreach (array(
      'all_or_nothing' => 0,
      'if_no_cookie' => 'defer',
      'require_permission_to_access_site' => 0,
      'site_has_advertisement_cookies' => 0,
      'site_has_tracking_cookies' => 0,
      'site_has_social_cookies' => 0,
      'site_has_functional_cookies' => 1,
      'default_value_advertisement_cookies' => 0,
      'default_value_tracking_cookies' => 0,
      'default_value_social_cookies' => 0,
      'default_value_functional_cookies' => 1,
      'preference_cookie_name' => 'ClearsiteCookieLawObidingCookiePreferencesCookie',
      'un_action' => array(),
    ) as $key => $value) {
      if (!array_key_exists($key, $cookie_opt_in_system)) $cookie_opt_in_system[$key] = $value;
    }

    $required_system = array(
      'lang' => self::language('front'),
      'always_on' => array('site_has_functional_cookies' => true ),
      'un_action_unchangeable' => array(
        'tracking' => array(
          'wp_head:GA_Filter,spool_analytics:10',
          'wp_head:GA_Filter,spool_analytics:2',
          'login_head:GA_Filter,spool_analytics:20',
          'wp_footer:AGA_Filter,spool_analytics_async_foot:99',
          'wp_head:AGA_Filter,spool_analytics_async_head:1',
          'wp_head:AGA_Filter,spool_analytics:20',
          'login_head:AGA_Filter,spool_analytics:20',
          'wp_head:AGA_Filter,XFN_Head:2',

        ),
        'advertisement' => array(
          'wp_head:GA_Filter,spool_adsense,1',
        ),
      ),
    );

    $cookie_opt_in_system = array_merge($cookie_opt_in_system, $required_system);
    if ($get_just_this == 'cookie_opt_in_system') return $cookie_opt_in_system;



    // language specific settings
    // check for a requested language - the visitor picks his own language and is usually found in ICL_LANGUAGE_CODE.
    if (!$lang) {
      // there is no language requested in the function call, so we are undoubtedly on the settings page
      $lang = self::language('admin');
    }
    // verify the language
    $available_languages = self::language('list');
    if (!isset($available_languages[$lang])) {
      // the language is not found in the list
      // retrieve the default language
      $lang = self::language('default');
    }

    $cookie_opt_in_language = get_option('cookie_opt_in_language_'. $lang, array());
    foreach (array(
      'anchor_title' => __('Cookie preferences', 'cookie_opt_in'),
      'label_advertisement_cookies' => __('Allow advertisement cookies', 'cookie_opt_in'),
      'label_tracking_cookies' => __('Allow tracking cookies', 'cookie_opt_in'),
      'label_social_cookies' => __('Allow social cookies', 'cookie_opt_in'),
      'label_functional_cookies' => __('Allow functional cookies', 'cookie_opt_in'),
      'brief_info_on_advertisement_cookies' => __('This website uses cookies for advertisement purposes, for example: to give you offers specifically suited to your wishes.', 'cookie_opt_in'),
      'brief_info_on_tracking_cookies' => __('This website uses cookies for tracking purposes like Google Analytics.', 'cookie_opt_in'),
      'brief_info_on_social_cookies' => __('This website uses cookies for tracking purposes on social networks like Facebook, Google+ etc.', 'cookie_opt_in'),
      'brief_info_on_functional_cookies' => __('This website uses cookies for storing session information. These cookies will self-destruct when you close your browser. The cookies are required for the website to function.', 'cookie_opt_in'),
      'more_info_url_advertisement_cookies' => '',
      'more_info_url_tracking_cookies' => '',
      'more_info_url_social_cookies' => '',
      'more_info_url_functional_cookies' => '',
      'more_info_url' => '',
      'more_info_same_window' => 'yes',
      'more_info_text' => __('More info', 'cookie_opt_in'),
      'form_title' => __('We need your permission', 'cookie_opt_in'),
      'always_on_remark' => __('This website cannot operate without these cookies. By law, these cookies are permitted.', 'cookie_opt_in'),
    ) as $key => $value) {
      if (!array_key_exists($key, $cookie_opt_in_language)) $cookie_opt_in_language[$key] = $value;
    }
    if ($get_just_this == 'cookie_opt_in_language') return $cookie_opt_in_language;

    $settings = array_merge($cookie_opt_in_language, $cookie_opt_in_system);

    return $settings;

    /* for translation purposes */
    $for_tx = array(
      __('advertisement', 'cookie_opt_in'),
      __('tracking', 'cookie_opt_in'),
      __('social', 'cookie_opt_in'),
      __('functional', 'cookie_opt_in'),
      __('Advertisement Cookies', 'cookie_opt_in'),
      __('Tracking Cookies', 'cookie_opt_in'),
      __('Social Cookies', 'cookie_opt_in'),
      __('Functional Cookies', 'cookie_opt_in'),
      __('View details', 'cookie_opt_in'),
    );
  }

  /**
   * returns true if the visitor accepts a certain kind of cookie. If the cookie is not present, the setting 'if_no_cookie' is used.
   * This setting will determine a yes or a no based in the value; always yes, always no or based on the specific default value.
   * @param  string $type the cookie type to check
   * @return boolean cookie type accepted
   */
  public static function visitor_accepts($type) {
    $settings = self::settings();
    if (!isset($_COOKIE[ $settings['preference_cookie_name'] ])) {
      switch ($settings['if_no_cookie']) {
        case 'allow':
          return true;
        break;
        case 'deny':
          return false;
        break;
        case 'defer':
          return $settings['default_value_'. $type .'_cookies'];
        break;
      }
    }
    $cookie = $_COOKIE[ $settings['preference_cookie_name'] ];
    $reg = '/'. substr($type, 0, 1) . '([01])' .'/';
    preg_match($reg, $cookie, $match);
    return $match[1] == '1';
  }

  public static function eu_cookie_consent($type) {
    $settings = self::settings();
    $active_tag = "site_has_{$type}_cookies";
    if (!$settings[$active_tag]) {
      // configuration says no,
      // plugin requesting access says yes
      // update configuration
      $settings[$active_tag] = 1;
      update_option('cookie_opt_in_settings', $settings);
    }
    if (!CookieOptIn::visitor_accepts($type)) return 'denied';
  }

  public static function just_in_time_init() {
    global $wp_filter;
    if (COIA_STORE_FILTER)
    if (!is_admin() && !get_option('wp_cookie_opt_in_action_table', null)) update_option('wp_cookie_opt_in_action_table', $wp_filter);
    // attempt removal of known plugins
    $settings = self::settings();
    foreach ($settings['un_action_unchangeable'] as $key => $values) {
      if (!isset($settings['un_action']) || !isset($settings['un_action'][$key]) || !is_array($settings['un_action'][$key])) $settings['un_action'][$key] = array();
      $settings['un_action'][$key] = array_merge($settings['un_action'][$key], $values);
    }
    foreach ($settings['un_action'] as $key => $values) {
      if (!self::visitor_accepts($key)) {
        foreach ((array)$values as $value) {
          // without the @ this might cause E_NOTICE messages if the right-hand side does not produce enough array-elements.
          @list($action, $function, $priority) = explode(':', trim($value));
          @list($class,$method) = explode(',', $function);
          @list($_idx, $idx) = explode('IDX#', $function);
          if ($idx) {
            // OOOOH, we need to hack this away - NOT THE PREFERRED WAY
            unset($wp_filter[$action][$priority][$idx]);
          }
          elseif ($method) {
            // print "\nremoving method $action - $class :: $method";
            remove_action($action, array($class, $method), $priority);
          }
          else {
            // print "\nremoving function $action - $function";
            remove_action($action, $function, $priority);
          }
        }
      }
    }
  }

  private function maybe_upgrade() {
    $current_settings = get_option('cookie_opt_in_settings', array());
    #var_Dump($current_settings);exit;
    if (!$current_settings) {
      // no settings, so nothing to upgrade!
      // maybe we already upgraded
      // maybe we have a fresh install
      // Who knows? It doesn't matter
      // Nothing to see here, move along.
    }
    else {
      // rewrite settings into the new structure
      $new_settings_all_languages = array();
      $new_settings_for_language = array();

      foreach (array('all_or_nothing', 'require_permission_to_access_site', 'site_has_advertisement_cookies',
                     'site_has_tracking_cookies', 'site_has_social_cookies', 'site_has_functional_cookies',
                     'default_value_advertisement_cookies', 'default_value_tracking_cookies', 'default_value_social_cookies',
                     'default_value_functional_cookies', 'preference_cookie_name', 'preference_cookie_expires',
                     'always_on', 'un_action') as $i) {
        $new_settings_all_languages[$i] = $current_settings[$i];
        unset($current_settings[$i]);
      }

      $new_settings_for_language = array_filter($current_settings);
      $all_languages = self::language('list');
      update_option('cookie_opt_in_system', $new_settings_all_languages);
      foreach ($all_languages as $lang => $data) {
        update_option('cookie_opt_in_'. $lang, $new_settings_for_language);
      }
      delete_option('cookie_opt_in_settings');
    }
  }
}

function coia($type) {
  return CookieOptIn::eu_cookie_consent($type) != 'denied';
}

function coi_urlencode($unencoded) {
  return urlencode($unencoded);
}

function coia_donate_html($class='') {
  switch (CookieOptIn::language('default')) {
    case 'nl':
      $lang = 'nl_NL';
    break;
    default:
      $lang = 'en_US';
    break;
  }

  return apply_filters('coia_donate_html', coia_donate_html_wrap('
  <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
'. ($lang == 'nl_NL' ? '<input type="image" src="https://www.paypalobjects.com/nl_NL/NL/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal, de veilige en complete manier van online betalen.">
' : '<input type="image" src="https://www.paypalobjects.com/en_US/NL/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
') .'<img alt="" border="0" src="https://www.paypalobjects.com/nl_NL/i/scr/pixel.gif" width="1" height="1">
  </form>', $class));
}

function coia_donate_html_wrap($html, $class='') {
  return '
<div id="coia-donations" class="'. $class .'">
  <h2>'. __('Donations', 'cookie_opt_in') .'</h2>
  <p>'. __('Although Clearsite Webdesigners are webdesigners by trade, this plugin is presented to the community free of charge. To keep this plugin up to date, funds are needed. If you like this plugin and continue to use it, please consider donating.', 'cookie_opt_in') .'</p>
  <p>'. __('Thank you very much for your support.', 'cookie_opt_in') .'</p>'. $html .'
  <p style="text-align: right;"><a href="http://www.clearsite.nl/" target="_blank"><img src="'. plugins_url('css/clearsite.png', __FILE__) .'" alt="Clearsite.nl" width="300" /></a></p>
</div>';
}

add_filter('body_class', 'coia_body_class');
function coia_body_class($body_classes) {
  if (defined('WPLANG')) $body_classes[] = 'lang-'. WPLANG;
  return $body_classes;
}

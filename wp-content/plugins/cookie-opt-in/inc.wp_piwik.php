<?php
/* This code is designed to work only if wp_piwik is found.
   This will handle the acceptance/denial of cookies for Piwik in javascript and does therefore not use the COIA function
   It DOES however determine the existance of the cookie_opt_in javascript by the existance of that function */

add_action('init', 'coia_patch_piwik');
function coia_patch_piwik() {
  if (!class_exists('wp_piwik')) return false; // don't try if piwik doesn't exist
  if (!function_exists('coia')) return false; // don't try if coia doesn't exist

  // get the global wp_piwik object
  $piwik = &$GLOBALS['wp_piwik'];
  // read the settings; this will NOT use default settings - this is a private var. Ergo: you MUST have SAVED the settings of PiWik at least ONCE before this will work.
  $aryGlobalSettings = get_option('wp-piwik_global-settings', array());
  // unload the footer code
  remove_action('wp_footer', array($piwik, 'footer'));
  // add the patched footer code only if the settings tell us to do so.
  if ($aryGlobalSettings['add_tracking_code']) add_action('wp_footer', 'coia_piwik_patched_footer',100000);
}

function coia_piwik_patched_footer() {
  // get the global wp_piwik object
  $piwik = &$GLOBALS['wp_piwik'];

  // get the default footer code
  ob_start();
  $piwik::footer();
  $footer = ob_get_contents();
  ob_end_clean();

  // patch the code to work only if the visitor accepts cookies
  $tr = array('try {' => 'if (cookie_opt_in.get().value.t == "1") try {');
  $footer = strtr($footer, $tr);

  // print the code
  print $footer;
}

/* PiWik specific code ends here */
<?php
add_action('init', 'cls_replace_yoast_ga', 100000);
function cls_replace_yoast_ga() {
  remove_action('wp_head', array('GA_Filter','spool_analytics'),10);
  add_action('wp_footer', 'cls_replaced_yoast_ga', 10);
}

function cls_replaced_yoast_ga() {
  ob_start();
  GA_Filter::spool_analytics();
  $ga = ob_get_contents();
  ob_end_clean();
  if (function_exists('coia')) $ga = str_replace('(function() {', 'if (cookie_opt_in.get().value.t == "1") (function() {', $ga);
  print $ga;
}
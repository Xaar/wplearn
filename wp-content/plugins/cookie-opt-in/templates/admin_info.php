<?php
  if (function_exists('__')) $file = __('en.admin_info.php', 'cookie_opt_in');
  if (function_exists('t')) $file = t('en.admin_info.php');
  include($file);
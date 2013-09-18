<?php
  $types_of_cookies = array('functional', 'advertisement', 'tracking', 'social');
?>
<div class='wrap'>
  <h2><?php _e('Cookie-Opt-In Settings', 'cookie_opt_in');
    $systag = $langtag = '';
    $langshow = true;

    if (CookieOptIn::is_ml()) {
      $systag = '<img src="'. admin_url('images/generic.png') .'" alt="System setting" />';
      $langtag = '<img src="'. ICL_PLUGIN_URL . '/res/flags/'. CookieOptIn::language('admin') .'.png" alt="Setting for language '. CookieOptIn::language('admin') .'" />';

      print ' - ';
      if (CookieOptIn::language('admin') == 'all') {
        _e('Language independent settings', 'cookie_opt_in');
        print '</h2>';
        print '<div id="message" class="error below-h2"><p>';
        _e('Language specific settings are hidden, please pick a language using the WPML-Admin-Language-Selector above.', 'cookie_opt_in');
        print '</p></div>';
        $langshow = false;
      }
      else {
        _e('Language specific settings for', 'cookie-opt-in');
        print ' '. $langtag . strtoupper(CookieOptIn::language('admin'));
        print '</h2>';
        print '<div id="message" class="updated below-h2"><p>';
        print sprintf(__('Regardless of selected language, settings marked %s are for ALL languages, settings marked %s are for the selected language: %s.', 'cookie_opt_in'), $systag, $langtag, strtoupper(CookieOptIn::language('admin')));
        print '</p></div>';
      }
    }
    else {
      print '</h2>';
    }
  ?>
  <div id="cookie">
    <form method="post">
      <input type="hidden" name="is_cookie_opt_in" value="<?php print wp_create_nonce('is_cookie_opt_in'); ?>" />
      <table cellspacing="0">
        <tr>
          <th id="col1" class="h lefth">
            <?php _e('General settings', 'cookie_opt_in'); ?>
          </th>
          <th id="col2" class="h middleh">&nbsp;</th>
          <th id="col3" class="h middleh">&nbsp;</th>
          <th id="col4" class="h righth">&nbsp;</th>
        </tr>
<?php do_action('wp_cookie_opt_in_admin', array('general', 'top', $langshow, $langtag, $systag, $cookie_opt_in_language, $cookie_opt_in_system)); ?>
<?php if ($langshow) : ?>
        <tr>
          <th class="left">
            <?php print $langtag; ?>
            <?php _e('Form Title', 'cookie_opt_in'); ?>
          </th>
          <td>
            <input type="text" name="cookie_opt_in_language[form_title]" value="<?php print esc_attr($cookie_opt_in_language['form_title']); ?>" />
          </td>
          <td colspan="2" class="right">
            <?php _e('This is the title above the form that asks the user to choose what is and is not allowed', 'cookie_opt_in'); ?>
          </td>
        </tr>
<?php endif; ?>
<?php if ($langshow) : ?>
        <tr>
          <th class="left">
            <?php print $langtag; ?>
            <?php _e('Anchor Title', 'cookie_opt_in'); ?>
          </th>
          <td>
            <input type="text" name="cookie_opt_in_language[anchor_title]" value="<?php print esc_attr($cookie_opt_in_language['anchor_title']); ?>" />
          </td>
          <td colspan="2" class="right">
            <?php _e('This is the title of the preferences link', 'cookie_opt_in'); ?>
          </td>
        </tr>
<?php endif; ?>
<?php do_action('wp_cookie_opt_in_admin', array('general', 'textual', $langshow, $langtag, $systag, $cookie_opt_in_language, $cookie_opt_in_system)); ?>
        <tr>
          <th class="left">
            <?php print $systag; ?>
            <?php _e('Consent mode.', 'cookie_opt_in'); ?>
          </th>
          <td>
            <select name="cookie_opt_in_system[all_or_nothing]">
              <option value="1"<?php if ($cookie_opt_in_system['all_or_nothing']) print ' selected="selected"'; ?>><?php _e('Allow/Deny all cookies', 'cookie_opt_in'); ?></option>
              <option value="0"<?php if (!$cookie_opt_in_system['all_or_nothing']) print ' selected="selected"'; ?>><?php _e('Pick individual', 'cookie_opt_in'); ?></option>
            </select>
          </td>
          <td colspan="2" class="right">
            <?php _e('You can either have the visitor pick individual functionalities or just Allow/Deny.', 'cookie_opt_in'); ?>
          </td>
        </tr>
        <tr>
          <th class="left">
            <?php print $systag; ?>
            <?php _e('If no cookie exists...', 'cookie_opt_in'); ?>
          </th>
          <td>
            <select name="cookie_opt_in_system[if_no_cookie]">
              <option value="allow"<?php if ($cookie_opt_in_system['if_no_cookie'] == 'allow') print ' selected="selected"'; ?>><?php _e('Allow all functionality until user has voted (opt-out)', 'cookie_opt_in'); ?></option>
              <option value="defer"<?php if ($cookie_opt_in_system['if_no_cookie'] == 'defer') print ' selected="selected"'; ?>><?php _e('Use the \'default value\' as determined below', 'cookie_opt_in'); ?></option>
              <option value="deny"<?php if ($cookie_opt_in_system['if_no_cookie'] == 'deny') print ' selected="selected"'; ?>><?php _e('Deny all functionality until user has voted (opt-in)', 'cookie_opt_in'); ?></option>
            </select>
          </td>
          <td colspan="2" class="right">
            <?php _e('You can either have the visitor pick individual functionalities or just Allow/Deny.', 'cookie_opt_in'); ?>
          </td>
        </tr>
<?php if ($langshow) : ?>
        <tr>
          <th class="left">
            <?php print $langtag; ?>
            <?php _e('More info link text.', 'cookie_opt_in'); ?>
          </th>
          <td>
            <input type="text" name="cookie_opt_in_language[more_info_text]" value="<?php print esc_attr($cookie_opt_in_language['more_info_text']); ?>" />
          </td>
          <th>
            <?php print $langtag; ?>
            <?php _e('Generic more-info link url.', 'cookie_opt_in'); ?>
          </th>
          <td class="right">
            <input type="text" name="cookie_opt_in_language[more_info_url]" value="<?php print esc_attr($cookie_opt_in_language['more_info_url']); ?>" />
						<br />
						<input type="checkbox" name="cookie_opt_in_language[more_info_same_window]" value="yes"<?php if ($cookie_opt_in_language['more_info_same_window'] == "yes") { print ' checked="checked"'; } ?> id="more_info_same_window" />
						<label for="more_info_same_window"><?php _e('Open in the same window', 'cookie_opt_in') ?></label>
          </td>
        </tr>
<?php endif; ?>
        <tr>
          <th class="left">
            <?php print $systag; ?>
            <?php _e('The name of the cookie.', 'cookie_opt_in'); ?>
          </th>
          <td>
            <input type="text" name="cookie_opt_in_system[preference_cookie_name]" value="<?php print esc_attr($cookie_opt_in_system['preference_cookie_name']); ?>" />
          </td>
          <td colspan="2" class="right">
            <?php _e('Usually this does not have to be changed.', 'cookie_opt_in'); ?>
          </td>
        </tr>
<?php if ($langshow) : ?>
        <tr>
          <th class="left">
            <?php print $langtag; ?>
            <?php _e('Inform-only remark.', 'cookie_opt_in'); ?>
          </th>
          <td>
            <textarea name="cookie_opt_in_language[always_on_remark]"><?php print $cookie_opt_in_language['always_on_remark']; ?></textarea>
          </td>
          <td colspan="2" class="right">
            <?php _e('Remark for cookie-types that do not require permission but they do require the visitor to be informed.', 'cookie_opt_in'); ?>
          </td>
        </tr>
<?php endif; ?>
        <tr>
          <th class="left">
            <?php print $systag; ?>
            <?php _e('The TTL of the cookie. The cookie expires...', 'cookie_opt_in'); ?>
          </th>
          <td>
            <select id="expiry" name="cookie_opt_in_system[preference_cookie_expires]">
              <option value="never"><?php _e('never', 'cookie_opt_in'); ?></option>
              <option value="session_end"><?php _e('at browser close', 'cookie_opt_in'); ?></option>
              <option value="<?php print      5*365.24*24*60*60; ?>"><?php _e('in 5 years', 'cookie_opt_in'); ?></option>
              <option value="<?php print      1*365.24*24*60*60; ?>"><?php _e('in 1 year', 'cookie_opt_in'); ?></option>
              <option value="<?php print    0.5*365.24*24*60*60; ?>"><?php _e('in 6 months', 'cookie_opt_in'); ?></option>
              <option value="<?php print (1/12)*365.24*24*60*60; ?>"><?php _e('in 1 month', 'cookie_opt_in'); ?></option>
              <option value="<?php print (1/52)*365.24*24*60*60; ?>"><?php _e('in 1 week', 'cookie_opt_in'); ?></option>
              <option value="<?php print               24*60*60; ?>"><?php _e('in 1 day', 'cookie_opt_in'); ?></option>
              <option value="<?php print               12*60*60; ?>"><?php _e('in 12 hours', 'cookie_opt_in'); ?></option>
              <option value="<?php print                6*60*60; ?>"><?php _e('in 6 hours', 'cookie_opt_in'); ?></option>
              <option value="<?php print                3*60*60; ?>"><?php _e('in 3 hours', 'cookie_opt_in'); ?></option>
              <option value="<?php print                1*60*60; ?>"><?php _e('in 1 hour', 'cookie_opt_in'); ?></option>
              <option value="<?php print                  30*60; ?>"><?php _e('in 30 minutes', 'cookie_opt_in'); ?></option>
              <option value="<?php print                  15*60; ?>"><?php _e('in 15 minutes', 'cookie_opt_in'); ?></option>
            </select>
            <script>jQuery("#expiry option[value=<?php print $cookie_opt_in_system['preference_cookie_expires']; ?>]").attr('selected',true)</script>
          </td>
          <td colspan="2" class="right">
            <?php _e('When does the preference expire?', 'cookie_opt_in'); ?><br />
            <?php _e("Selecting 'at browser close', a user must set his preference every new visit. Closing the browser will dismiss the preferences.", 'cookie_opt_in'); ?><br />
            <?php _e("With 'never', a user sets his preference once for a hundred years (or the time the cookies are reset).", 'cookie_opt_in'); ?><br />
            <?php _e("With an expiry time selected, the preference remains for that amount of time.", 'cookie_opt_in'); ?>
          </td>
        </tr>
<?php do_action('wp_cookie_opt_in_admin', array('general', 'bottom', $langshow, $langtag, $systag, $cookie_opt_in_language, $cookie_opt_in_system)); ?>
<?php
          if (!isset($cookie_opt_in_system['un_action_unchangeable'])) $cookie_opt_in_system['un_action_unchangeable'] = array();

// HERE the loop for all cookie types begins
          foreach ($types_of_cookies as $cookie_type) {
            // TODO remove all code depending on $i and make it depending on $cookie_type
            $i = 'site_has_'. $cookie_type .'_cookies';
            // TODO remove all code depending on $_section and make it depenging on $cookie_type
            $_section = $cookie_type;
            $section = ucwords("{$cookie_type} cookies");
            if (!isset($cookie_opt_in_system['un_action_unchangeable'][$_section]) || !is_array($cookie_opt_in_system['un_action_unchangeable'][$_section])) $cookie_opt_in_system['un_action_unchangeable'][$_section] = array();
            if (!isset($cookie_opt_in_system['un_action'][$_section]) || !is_array($cookie_opt_in_system['un_action'][$_section])) $cookie_opt_in_system['un_action'][$_section] = array();
        ?>
        <tr class="closer">
          <td class="left">&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td class="right">&nbsp;</td>
        </tr>
        <tr class="spacer">
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <th colspan="4" class="h"><?php _e('Section', 'cookie_opt_in'); ?>: <?php _e($section, 'cookie_opt_in'); ?></th>
        </tr>
        <tr>
          <th class="left">
            <?php print $systag; ?>
            <?php print sprintf(__('Site has %s?', 'cookie_opt_in'), $section); ?>
          </th>
          <td>
            <select name="cookie_opt_in_system[<?php print $i; ?>]">
              <option value="1"<?php if ($cookie_opt_in_system[$i]) print ' selected="selected"'; ?>><?php _e('Yes', 'cookie_opt_in'); ?></option>
              <option value="0"<?php if (!$cookie_opt_in_system[$i]) print ' selected="selected"'; ?>><?php _e('No', 'cookie_opt_in'); ?></option>
            </select>
          </td>
          <td colspan="2" class="right">
            <?php _e('If Yes, set the following options.', 'cookie_opt_in'); ?>
          </td>
        </tr>
<?php do_action('wp_cookie_opt_in_admin', array($_section, 'top', $langshow, $langtag, $systag, $cookie_opt_in_language, $cookie_opt_in_system));?>
        <tr>
          <th class="left">
            <?php print $systag; ?>
            <?php _e('Default value for new visitors.', 'cookie_opt_in'); ?><br /><?php _e('Remember the rules!!!', 'cookie_opt_in'); ?>
          </th>
          <td>
            <select name="cookie_opt_in_system[<?php print $j = str_replace('site_has_', 'default_value_', $i); ?>]">
              <option value="1"<?php if ($cookie_opt_in_system[$j]) print ' selected="selected"'; ?>><?php _e('Yes', 'cookie_opt_in'); ?></option>
              <option value="0"<?php if (!$cookie_opt_in_system[$j]) print ' selected="selected"'; ?>><?php _e('No', 'cookie_opt_in'); ?></option>
            </select>
          </td>
<?php if ($langshow) : ?>
          <th>
            <?php print $langtag; ?>
            <?php _e('Indicative label', 'cookie_opt_in'); ?>
          </th>
          <td class="right">
            <input type="text" name="cookie_opt_in_language[<?php print $j = str_replace('site_has_', 'label_', $i); ?>]" value="<?php print esc_attr($cookie_opt_in_language[$j]); ?>" />
          </td>
<?php else: ?>
          <th></th>
          <td class="right"></td>
<?php endif; ?>
        </tr>
<?php if ($langshow) : ?>
        <tr>
          <th class="left">
            <?php print $langtag; ?>
            <?php _e('Long description', 'cookie_opt_in'); ?>
          </th>
          <td colspan="3" class="right">
            <textarea name="cookie_opt_in_language[<?php print $j = str_replace('site_has_', 'brief_info_on_', $i); ?>]"><?php print $cookie_opt_in_language[$j]; ?></textarea>
          </td>
        </tr>
        <tr>
          <th class="left">
            <?php print $langtag; ?>
            <?php _e('Page for more info', 'cookie_opt_in'); ?>
          </th>
          <td colspan="3" class="right">
            <input type="text" name="cookie_opt_in_language[<?php @print $j = str_replace('site_has_', 'more_info_', $i); ?>]" value="<?php print @esc_attr($cookie_opt_in_language[$j]); ?>" />
          </td>
        </tr>
<?php endif; ?>
        <tr>
          <th class="left">
            <?php print $systag; ?>
            <?php _e('If denied by visitor, try to un-register the following actions. Please be careful.', 'cookie_opt_in'); ?><br />
            <?php _e('Format', 'cookie_opt_in'); ?>: <code><?php _e('action_name', 'cookie_opt_in'); ?></code>:<code><?php _e('function', 'cookie_opt_in'); ?></code>:<code><?php _e('PRIO', 'cookie_opt_in'); ?></code>.<br />
            <?php _e('For a class method', 'cookie_opt_in'); ?>: <code><?php _e('action_name', 'cookie_opt_in'); ?></code>:<code><?php _e('classname', 'cookie_opt_in'); ?></code>,<code><?php _e('method', 'cookie_opt_in'); ?></code>:<code><?php _e('PRIO', 'cookie_opt_in'); ?></code>.<br />
            <?php _e('Example', 'cookie_opt_in'); ?>: <code>wp_head:my_head_function:10</code>.
          </th>
          <td colspan="2">
            <textarea name="cookie_opt_in_system[un_action][<?php print $j = $_section ?>]"><?php print implode("\n", (array)$cookie_opt_in_system['un_action'][$j]); ?></textarea>
          </td><?php
            $s = coi_urlencode(sprintf(__('Found working action removal for CookieOptIn section %s', 'cookie_opt_in'), $section));
            $b = coi_urlencode(sprintf(__("For section: %s", 'cookie_opt_in'), $section) .";\n\n". implode("\n", (array)$cookie_opt_in_system['un_action'][$_section]) . "\n\n". get_bloginfo('url'));
          ?>
          <td class="right">
            <?php _e('FOUND A WORKING REMOVAL??', 'cookie_opt_in'); ?>
            <?php print sprintf(__('Please <a href="%s">MAIL us</a>.', 'cookie_opt_in'), 'mailto:support@clearsite.nl?subject='. $s .'&amp;body='. $b); ?>
            <?php _e('We thank you in advance.', 'cookie_opt_in'); ?>
          </td>
        </tr><?php if ($cookie_opt_in_system['un_action_unchangeable'][$_section]) { ?>
        <tr>
          <th class="left"><?php _e('The following actions are known to us and ability to remove these actions is verified.', 'cookie_opt_in'); ?></th><?php
            $count = count($cookie_opt_in_system['un_action_unchangeable'][$_section]);
            $slice = array();
            foreach ($cookie_opt_in_system['un_action_unchangeable'][$_section] as $k => $item) $slice[ $k%3 ][] = $item; ?>
          <td><?php if (isset($slice[0])) { ?><code><?php print implode('</code><br /><code>', (array)$slice[0]); ?></code><?php } ?></td>
          <td><?php if (isset($slice[1])) { ?><code><?php print implode('</code><br /><code>', (array)$slice[1]); ?></code><?php } ?></td>
          <td class="right"><?php if (isset($slice[2])) { ?><code><?php print implode('</code><br /><code>', (array)$slice[2]); ?></code><?php } ?></td>
        </tr><?php } ?>
        <?php
          do_action('wp_cookie_opt_in_admin', array($_section, 'bottom', $langshow, $langtag, $systag, $cookie_opt_in_language, $cookie_opt_in_system));

        // HERE the loop ends

        } ?>
        <tr class="closer">
          <td class="left">&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td class="right">&nbsp;</td>
        </tr>
        <tr class="spacer">
          <td colspan="4">&nbsp;</td>
        </tr>
      </table>
      <input type="submit" value="<?php _e('Save settings', 'cookie_opt_in'); ?>" class="button-primary action" />
    </form>
  </div><?php print coia_donate_html('on-bottom'); ?>
</div>

<div class='wrap'>
<?php
  if ($refresh_done) {
    print '<script>function reloadpage() { document.location="'. remove_query_arg('refresh_action_cache') .'"; }; setTimeout("reloadpage()", 20);</script>';
  }
  else {
?>
  <h2><?php _e('Cookie-Opt-In Action reference.', 'cookie_opt_in'); ?></h2>
  <div id="cookie">
    <?php if ($_actiontable) {
      $href = add_query_arg('refresh_action_cache', time());
      print sprintf(__('Actions have been cached, to delete the cache and rebuild on next visit, <a href="%s">click here</a>', 'cookie_opt_in'), $href);
    ?>
    <p><?php _e('PLEASE NOTE', 'cookie_opt_in'); ?>:</p>
    <p><?php _e('This information mathes the FRONT-end, NOT the back-end. It is refreshed on page load, just before the page is shown.', 'cookie_opt_in'); ?></p>
    <p><?php _e('Removed actions (by settings) are NOT present in this list if you chose not to accept cookies on your front-end.', 'cookie_opt_in'); ?>!</p>
    <p><?php _e('Actions marked with [WARNING] cannot be overruled, these actions have references to object-instances and must be dealt with in different ways. Very sorry about that.', 'cookie_opt_in'); ?></p>
    <table>
      <tr>
        <th><?php _e('Registered action', 'cookie_opt_in'); ?></th><th><?php _e('Function or method called', 'cookie_opt_in'); ?></th><th><?php _e('Priority', 'cookie_opt_in'); ?></th><th><?php _e('Use this in the settings', 'cookie_opt_in'); ?></th>
      </tr><?php foreach ($actiontable as $row) { ?>
      <tr><?php foreach ($row as $cell) { ?>
        <td><?php print $cell; ?></td>
      <?php } ?>
      </tr>
    <?php } ?>
    </table>
<?php
    }
    else {
      print __('No actions have been cached. Please visit your website and return here.', 'cookie_opt_in');
    }
  ?>
  </div><?php print coia_donate_html('on-bottom'); ?>
<?php } ?>
</div>
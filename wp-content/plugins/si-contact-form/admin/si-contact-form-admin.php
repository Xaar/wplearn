<?php
/*
Fast Secure Contact Form
Mike Challis
http://www.642weather.com/weather/scripts.php
*/
//do not allow direct access
if ( strpos(strtolower($_SERVER['SCRIPT_NAME']),strtolower(basename(__FILE__))) ) {
 header('HTTP/1.0 403 Forbidden');
 exit('Forbidden');
}

  // the admin settings page
  // This code is inside function si_contact_options_page

   if ( function_exists('current_user_can') && !current_user_can('manage_options') )
             die(__('You do not have permissions for managing this option', 'si-contact-form'));

 // multi-form ctf_form_num
  $form_num = $this->si_contact_form_num();

  if($form_num == '') {
        $form_id = 1;
  }else{
        $form_id = $form_num;
  }

  // get options
  $si_contact_gb = $this->si_contact_get_options($form_num);

  // a couple language options need to be translated now.
  $this->si_contact_update_lang();

     // action copy settings
	if ( isset($_POST['ctf_action'])
    && $_POST['ctf_action'] == __('Copy Settings', 'si-contact-form')
    && isset($_POST['si_contact_copy_what'])
    && isset($_POST['si_contact_this_form'])
    && is_numeric($_POST['si_contact_this_form'])
    && isset($_POST['si_contact_destination_form'])
    && check_admin_referer( 'si-contact-form-copy_settings', 'copy_settings')  ) {

     require_once WP_PLUGIN_DIR . '/si-contact-form/admin/si-contact-form-settings-copy.php';

     // refresh settings to initialize the restored backup
     $si_contact_gb = $this->si_contact_get_options($form_num);

  } // end action copy settings

    // action backup restore
	if (isset($_POST['ctf_action'])
      && $_POST['ctf_action'] == __('Restore Settings', 'si-contact-form')
      && isset($_POST['si_contact_backup_type'])
      && check_admin_referer( 'si-contact-form-restore_settings', 'restore_settings') ) {

     echo $this->si_contact_form_backup_restore($_POST['si_contact_backup_type']);

     // refresh settings to initialize the restored backup
     $si_contact_gb = $this->si_contact_get_options($form_num);

  } // end action backup restore

	// Send a test mail if necessary
	if (isset($_POST['ctf_action'])
      && $_POST['ctf_action'] == __('Send Test', 'si-contact-form')
      && isset($_POST['si_contact_to'])
      && check_admin_referer( 'si-contact-form-email_test','email_test') ) {

      require_once WP_PLUGIN_DIR . '/si-contact-form/admin/si-contact-form-do-test-mail.php';

	} // end Send a test mail if necessary

	
	/* --- vCita Admin Actions --- */
	if (isset($_POST['vcita_disconnect'])) {
	    $si_contact_gb = $this->si_contact_get_options($form_num);
	    $si_contact_opt = $this->vcita_disconnect_form($si_contact_opt);
	    $vcita_user_changed = true;
	    
	    update_option("si_contact_form$form_num", $si_contact_opt);
	}
	
 // preview forms, viewable to logged in admin only
 if ( isset($_GET['show_form']) && is_numeric($_GET['show_form']) && !isset($_POST['ctf_action']) ) {

  $form = $_GET['show_form'];
  $form_num = '';
  $form_id = 1;
  if ( isset($form) && is_numeric($form) && $form <= $si_contact_gb['max_forms'] ) {
     $form_num = (int)$form;
     $form_id = (int)$form;
     if ($form_num == 1)
        $form_num = '';
  }
  
   // show form number links
   ?>

  <div class="wrap">
   <div id="main">
   <h2><?php _e('Fast Secure Contact Form', 'si-contact-form'); ?></h2>

   <h3><?php _e('Usage', 'si-contact-form'); ?></h3>


<p>
<?php _e('Add the shortcode in a Page, Post, or Text Widget', 'si-contact-form'); ?>. <a href="<?php echo plugins_url( 'si-contact-form/screenshot-4.gif' ); ?>" target="_new"><?php _e('help', 'si-contact-form'); ?></a>
<br />
<?php _e('Shortcode for this form:', 'si-contact-form'); echo " [si-contact-form form='$form_id']"; ?>
</p>


<h3><?php _e('Preview', 'si-contact-form'); ?></h3>

   <div class="form-tab"><?php echo __('Preview Multi-Forms:', 'si-contact-form').' '. sprintf(__('(form %d)', 'si-contact-form'),$form_id); ?></div>
   <div class="clear"></div>
   <fieldset>
   <h3><?php
  // multi-form selector
  for ($i = 1; $i <= $si_contact_gb['max_forms']; $i++) {
     if($i == 1) {
         if ($form_id == 1) {
             echo '<b>'.sprintf(__('Form: %d', 'si-contact-form'),1).'</b>';
             echo ' <small><a href="' . admin_url(  "plugins.php?page=si-contact-form/si-contact-form.php" ) . '">('. __('edit', 'si-contact-form'). ')</a></small>';
        } else {
             echo '<a href="' . admin_url(  'plugins.php?show_form='.$i.'&amp;page=si-contact-form/si-contact-form.php' ) . '">'. sprintf(__('Form: %d', 'si-contact-form'),1). '</a>';
        }
     } else {
        if ($form_id == $i) {
             echo ' | <b>' . sprintf(__('Form: %d', 'si-contact-form'),$i).'</b>';
             echo ' <small><a href="' . admin_url(  'plugins.php?ctf_form_num='.$i.'&amp;page=si-contact-form/si-contact-form.php' ) . '">('. __('edit', 'si-contact-form'). ')</a></small>';
        } else {
             echo ' | <a href="' . admin_url(  'plugins.php?show_form='.$i.'&amp;ctf_form_num='.$i.'&amp;page=si-contact-form/si-contact-form.php' ) . '">'. sprintf(__('Form: %d', 'si-contact-form'),$i). '</a>';
        }
     }
  }
  ?>
  </h3>

  <br />

  <?php
   echo $this->si_contact_form_short_code( array( 'form' => "$form" ) );

  echo '
  </fieldset>
  </div>
  </div>
  ';
 }// end preview forms

 $vcita_dismissed = false;
 
 if ( isset($_GET['vcita_dismiss']) && $_GET['vcita_dismiss'] == "true") {
     $si_contact_gb = $this->si_contact_get_options($form_num);
 
     $si_contact_gb = $this->vcita_dismiss_pending_notification($si_contact_gb, $form_num);
     
     $vcita_dismissed = true;
 }
 
 if ((isset($_POST['submit']) || isset($_POST['vcita_create']))
   && !isset($_POST['ctf_action'])
   && check_admin_referer( 'si-contact-form-options_update','options_update') ) {

   // post changes to the options array
   $optionarray_gb_update = array(
         'donated' =>                 (isset( $_POST['si_contact_donated'] ) ) ? 'true' : 'false',
         'max_forms' =>     ( is_numeric(trim($_POST['si_contact_max_forms'])) && trim($_POST['si_contact_max_forms']) < 100 ) ? absint(trim($_POST['si_contact_max_forms'])) : $si_contact_gb['max_forms'],
         'max_fields' =>          absint(trim($si_contact_gb['max_fields'])),
         'vcita_auto_install' =>         strip_tags(trim($_POST['si_contact_vcita_auto_install'])), /* --- vCita Global Settings --- */
         'vcita_dismiss' =>              strip_tags(trim($_POST['si_contact_vcita_dismiss'])), /* --- vCita Global Settings --- */
		 'ctf_version' =>                strip_tags(trim($_POST['si_contact_ctf_version'])),
         );

   if(isset($si_contact_gb['2.6.3'] ))
                 $optionarray_gb_update['2.6.3'] = $si_contact_gb['2.6.3'];

   $optionarray_update = array(
         'form_name' =>           strip_tags(trim($_POST['si_contact_form_name'])),  // can be empty
         'welcome' =>                        trim($_POST['si_contact_welcome']),  // can be empty, can have HTML
         'email_to' =>                     ( trim($_POST['si_contact_email_to']) != '' ) ? strip_tags(trim($_POST['si_contact_email_to'])) : $si_contact_option_defaults['email_to'], // use default if empty
         'php_mailer_enable' =>        strip_tags($_POST['si_contact_php_mailer_enable']),
         'email_from' =>          strip_tags(trim($_POST['si_contact_email_from'])), // optional
         'email_from_enforced' =>         (isset( $_POST['si_contact_email_from_enforced'] ) ) ? 'true' : 'false',
         'email_bcc' =>           strip_tags(trim($_POST['si_contact_email_bcc'])),
         'email_reply_to' =>      strip_tags(trim($_POST['si_contact_email_reply_to'])),
         'email_subject' =>     ( trim($_POST['si_contact_email_subject']) != '' ) ? strip_tags(trim($_POST['si_contact_email_subject'])) : '',
         'email_subject_list' =>  trim($_POST['si_contact_email_subject_list']),
         'name_format' =>              strip_tags($_POST['si_contact_name_format']),
         'name_type' =>                strip_tags($_POST['si_contact_name_type']),
         'email_type' =>               strip_tags($_POST['si_contact_email_type']),
         'subject_type' =>             strip_tags($_POST['si_contact_subject_type']),
         'message_type' =>             strip_tags($_POST['si_contact_message_type']),
         'preserve_space_enable' =>       (isset( $_POST['si_contact_preserve_space_enable'] ) ) ? 'true' : 'false',
         'max_fields' =>        ( is_numeric(trim($_POST['si_contact_max_fields'])) && trim($_POST['si_contact_max_fields']) < 200 ) ? absint(trim($_POST['si_contact_max_fields'])) : $si_contact_gb['max_fields'],
         'double_email' =>              (isset( $_POST['si_contact_double_email'] ) ) ? 'true' : 'false', // true or false
         'name_case_enable' =>          (isset( $_POST['si_contact_name_case_enable'] ) ) ? 'true' : 'false',
         'sender_info_enable' =>        (isset( $_POST['si_contact_sender_info_enable'] ) ) ? 'true' : 'false',
         'domain_protect' =>            (isset( $_POST['si_contact_domain_protect'] ) ) ? 'true' : 'false',
         'email_check_dns' =>           (isset( $_POST['si_contact_email_check_dns'] ) ) ? 'true' : 'false',
         'email_html' =>                (isset( $_POST['si_contact_email_html'] ) ) ? 'true' : 'false',
         'akismet_disable' =>           (isset( $_POST['si_contact_akismet_disable'] ) ) ? 'true' : 'false',
         'akismet_send_anyway' =>    strip_tags($_POST['si_contact_akismet_send_anyway']),
         'captcha_enable' =>            (isset( $_POST['si_contact_captcha_enable'] ) ) ? 'true' : 'false',
         'captcha_difficulty' =>     strip_tags($_POST['si_contact_captcha_difficulty']),
         'captcha_small' =>             (isset( $_POST['si_contact_captcha_small'] ) ) ? 'true' : 'false',
         'captcha_no_trans' =>          (isset( $_POST['si_contact_captcha_no_trans'] ) ) ? 'true' : 'false',
         'enable_audio' =>              (isset( $_POST['si_contact_enable_audio'] ) ) ? 'true' : 'false',
         'enable_audio_flash' =>        (isset( $_POST['si_contact_enable_audio_flash'] ) ) ? 'true' : 'false',
         'captcha_perm' =>              (isset( $_POST['si_contact_captcha_perm'] ) ) ? 'true' : 'false',
         'captcha_perm_level' =>     strip_tags($_POST['si_contact_captcha_perm_level']),
         'honeypot_enable' =>           (isset( $_POST['si_contact_honeypot_enable'] ) ) ? 'true' : 'false',
         'redirect_enable' =>           (isset( $_POST['si_contact_redirect_enable'] ) ) ? 'true' : 'false',
         'redirect_seconds' =>( is_numeric(trim($_POST['si_contact_redirect_seconds'])) && trim($_POST['si_contact_redirect_seconds']) < 61 ) ? absint(trim($_POST['si_contact_redirect_seconds'])) : $si_contact_option_defaults['redirect_seconds'],
         'redirect_url' =>               ( trim($_POST['si_contact_redirect_url']) != '' ) ? strip_tags(trim($_POST['si_contact_redirect_url'])) : $si_contact_option_defaults['redirect_url'], // use default if empty
         'redirect_query' =>            (isset( $_POST['si_contact_redirect_query'] ) ) ? 'true' : 'false',
         'redirect_ignore' =>   strip_tags(trim($_POST['si_contact_redirect_ignore'])),
         'redirect_rename' =>   strip_tags(trim($_POST['si_contact_redirect_rename'])),
         'redirect_add' =>      strip_tags(trim($_POST['si_contact_redirect_add'])),
         'redirect_email_off' =>        (isset( $_POST['si_contact_redirect_email_off'] ) ) ? 'true' : 'false',
         'silent_send' =>            strip_tags($_POST['si_contact_silent_send']),
         'silent_url' =>        strip_tags(trim($_POST['si_contact_silent_url'])),
         'silent_ignore' =>     strip_tags(trim($_POST['si_contact_silent_ignore'])),
         'silent_rename' =>     strip_tags(trim($_POST['si_contact_silent_rename'])),
         'silent_add' =>        strip_tags(trim($_POST['si_contact_silent_add'])),
         'silent_email_off' =>          (isset( $_POST['si_contact_silent_email_off'] ) ) ? 'true' : 'false',
         'export_enable' =>             (isset( $_POST['si_contact_export_enable'] ) ) ? 'true' : 'false',
         'export_ignore' =>     strip_tags(trim($_POST['si_contact_export_ignore'])),
         'export_rename' =>     strip_tags(trim($_POST['si_contact_export_rename'])),
         'export_add' =>        strip_tags(trim($_POST['si_contact_export_add'])),
         'export_email_off' =>          (isset( $_POST['si_contact_export_email_off'] ) ) ? 'true' : 'false',
         'border_enable' =>             (isset( $_POST['si_contact_border_enable'] ) ) ? 'true' : 'false',
         'ex_fields_after_msg' =>       (isset( $_POST['si_contact_ex_fields_after_msg'] ) ) ? 'true' : 'false',
         'date_format' =>            strip_tags($_POST['si_contact_date_format']),
         'cal_start_day' => ( preg_match('/^[0-6]?$/',$_POST['si_contact_cal_start_day']) ) ? trim($_POST['si_contact_cal_start_day']) : $si_contact_option_defaults['cal_start_day'],
         'time_format' =>               strip_tags($_POST['si_contact_time_format']),
         'attach_types' =>      trim(str_replace('.','',$_POST['si_contact_attach_types'])),
         'attach_size' =>       ( preg_match('/^([[0-9.]+)([kKmM]?[bB])?$/',$_POST['si_contact_attach_size']) ) ? trim($_POST['si_contact_attach_size']) : $si_contact_option_defaults['attach_size'],
         'textarea_html_allow' =>       (isset( $_POST['si_contact_textarea_html_allow'] ) ) ? 'true' : 'false',
         'enable_areyousure' =>         (isset( $_POST['si_contact_enable_areyousure'] ) ) ? 'true' : 'false',
         'auto_respond_enable' =>       (isset( $_POST['si_contact_auto_respond_enable'] ) ) ? 'true' : 'false',
         'auto_respond_html' =>         (isset( $_POST['si_contact_auto_respond_html'] ) ) ? 'true' : 'false',
         'auto_respond_from_name' =>     ( trim($_POST['si_contact_auto_respond_from_name']) != '' ) ? strip_tags(trim($_POST['si_contact_auto_respond_from_name'])) : $si_contact_option_defaults['auto_respond_from_name'], // use default if empty
         'auto_respond_from_email' =>    ( trim($_POST['si_contact_auto_respond_from_email']) != '' && $this->ctf_validate_email($_POST['si_contact_auto_respond_from_email'])) ? trim($_POST['si_contact_auto_respond_from_email']) : $si_contact_option_defaults['auto_respond_from_email'], // use default if empty
         'auto_respond_reply_to' =>      ( trim($_POST['si_contact_auto_respond_reply_to']) != '' && $this->ctf_validate_email($_POST['si_contact_auto_respond_reply_to'])) ? trim($_POST['si_contact_auto_respond_reply_to']) : $si_contact_option_defaults['auto_respond_reply_to'], // use default if empty
         'auto_respond_message' =>         trim($_POST['si_contact_auto_respond_message']),  // can be empty, can have HTML
         'auto_respond_subject' => strip_tags(trim($_POST['si_contact_auto_respond_subject'])),  // can be empty
         'req_field_indicator' =>  trim($_POST['si_contact_req_field_indicator']), // can have HTML
         'req_field_label_enable' =>       (isset( $_POST['si_contact_req_field_label_enable'] ) ) ? 'true' : 'false',
         'req_field_indicator_enable' =>   (isset( $_POST['si_contact_req_field_indicator_enable'] ) ) ? 'true' : 'false',
         'form_style' =>            ( trim($_POST['si_contact_form_style']) != '' ) ? strip_tags(trim($_POST['si_contact_form_style'])) : $si_contact_option_defaults['form_style'],
         'border_style' =>          ( trim($_POST['si_contact_border_style']) != '' ) ? strip_tags(trim($_POST['si_contact_border_style'])) : $si_contact_option_defaults['border_style'],
         'required_style' =>        ( trim($_POST['si_contact_required_style']) != '' ) ? strip_tags(trim($_POST['si_contact_required_style'])) : $si_contact_option_defaults['required_style'],
         'notes_style' =>           ( trim($_POST['si_contact_notes_style']) != '' ) ? strip_tags(trim($_POST['si_contact_notes_style'])) : $si_contact_option_defaults['notes_style'],
         'title_style' =>           ( trim($_POST['si_contact_title_style']) != '' ) ? strip_tags(trim($_POST['si_contact_title_style'])) : $si_contact_option_defaults['title_style'],
         'select_style' =>          ( trim($_POST['si_contact_select_style']) != '' ) ? strip_tags(trim($_POST['si_contact_select_style'])) : $si_contact_option_defaults['select_style'],
         'field_style' =>           ( trim($_POST['si_contact_field_style']) != '' ) ? strip_tags(trim($_POST['si_contact_field_style'])) : $si_contact_option_defaults['field_style'],
         'field_div_style' =>       ( trim($_POST['si_contact_field_div_style']) != '' ) ? strip_tags(trim($_POST['si_contact_field_div_style'])) : $si_contact_option_defaults['field_div_style'],
         'error_style' =>           ( trim($_POST['si_contact_error_style']) != '' ) ? strip_tags(trim($_POST['si_contact_error_style'])) : $si_contact_option_defaults['error_style'],
         'captcha_div_style_sm' =>  ( trim($_POST['si_contact_captcha_div_style_sm']) != '' ) ? strip_tags(trim($_POST['si_contact_captcha_div_style_sm'])) : $si_contact_option_defaults['captcha_div_style_sm'],
         'captcha_div_style_m' =>   ( trim($_POST['si_contact_captcha_div_style_m']) != '' ) ? strip_tags(trim($_POST['si_contact_captcha_div_style_m'])) : $si_contact_option_defaults['captcha_div_style_m'],
         'captcha_input_style' =>   ( trim($_POST['si_contact_captcha_input_style']) != '' ) ? strip_tags(trim($_POST['si_contact_captcha_input_style'])) : $si_contact_option_defaults['captcha_input_style'],
         'submit_div_style' =>      ( trim($_POST['si_contact_submit_div_style']) != '' ) ? strip_tags(trim($_POST['si_contact_submit_div_style'])) : $si_contact_option_defaults['submit_div_style'],
         'button_style' =>          ( trim($_POST['si_contact_button_style']) != '' ) ? strip_tags(trim($_POST['si_contact_button_style'])) : $si_contact_option_defaults['button_style'],
         'reset_style' =>           ( trim($_POST['si_contact_reset_style']) != '' ) ? strip_tags(trim($_POST['si_contact_reset_style'])) : $si_contact_option_defaults['reset_style'],
         'powered_by_style' =>      ( trim($_POST['si_contact_powered_by_style']) != '' ) ? strip_tags(trim($_POST['si_contact_powered_by_style'])) : $si_contact_option_defaults['powered_by_style'],
         'redirect_style' =>      ( trim($_POST['si_contact_redirect_style']) != '' ) ? strip_tags(trim($_POST['si_contact_redirect_style'])) : $si_contact_option_defaults['redirect_style'],
         'field_size' =>         ( is_numeric(trim($_POST['si_contact_field_size'])) && trim($_POST['si_contact_field_size']) > 14 ) ? absint(trim($_POST['si_contact_field_size'])) : $si_contact_option_defaults['field_size'], // use default if empty
         'captcha_field_size' => ( is_numeric(trim($_POST['si_contact_captcha_field_size'])) && trim($_POST['si_contact_captcha_field_size']) > 4 ) ? absint(trim($_POST['si_contact_captcha_field_size'])) : $si_contact_option_defaults['captcha_field_size'],
         'text_cols' =>           absint(trim($_POST['si_contact_text_cols'])),
         'text_rows' =>           absint(trim($_POST['si_contact_text_rows'])),
         'aria_required' =>       (isset( $_POST['si_contact_aria_required'] ) ) ? 'true' : 'false',
         'auto_fill_enable' =>    (isset( $_POST['si_contact_auto_fill_enable'] ) ) ? 'true' : 'false',
         'title_border' =>        strip_tags(trim($_POST['si_contact_title_border'])),
         'title_dept' =>          strip_tags(trim($_POST['si_contact_title_dept'])),
         'title_select' =>        strip_tags(trim($_POST['si_contact_title_select'])),
         'title_name' =>          strip_tags(trim($_POST['si_contact_title_name'])),
         'title_fname' =>         strip_tags(trim($_POST['si_contact_title_fname'])),
         'title_lname' =>         strip_tags(trim($_POST['si_contact_title_lname'])),
         'title_mname' =>         strip_tags(trim($_POST['si_contact_title_mname'])),
         'title_miname' =>        strip_tags(trim($_POST['si_contact_title_miname'])),
         'title_email' =>         strip_tags(trim($_POST['si_contact_title_email'])),
         'title_email2' =>        strip_tags(trim($_POST['si_contact_title_email2'])),
         'title_email2_help' =>   strip_tags(trim($_POST['si_contact_title_email2_help'])),
         'title_subj' =>          strip_tags(trim($_POST['si_contact_title_subj'])),
         'title_mess' =>          strip_tags(trim($_POST['si_contact_title_mess'])),
         'title_capt' =>          strip_tags(trim($_POST['si_contact_title_capt'])),
         'title_submit' =>        strip_tags(trim($_POST['si_contact_title_submit'])),
         'title_reset' =>         strip_tags(trim($_POST['si_contact_title_reset'])),
         'title_areyousure' =>    strip_tags(trim($_POST['si_contact_title_areyousure'])),
         'text_message_sent' =>   trim($_POST['si_contact_text_message_sent']), // can have HTML
         'tooltip_required' =>    strip_tags($_POST['si_contact_tooltip_required']), // can be a space
         'tooltip_captcha' =>     strip_tags(trim($_POST['si_contact_tooltip_captcha'])),
         'tooltip_audio' =>       strip_tags(trim($_POST['si_contact_tooltip_audio'])),
         'tooltip_refresh' =>     strip_tags(trim($_POST['si_contact_tooltip_refresh'])),
         'tooltip_filetypes' =>   strip_tags(trim($_POST['si_contact_tooltip_filetypes'])),
         'tooltip_filesize' =>    strip_tags(trim($_POST['si_contact_tooltip_filesize'])),
         'enable_reset' =>       (isset( $_POST['si_contact_enable_reset'] ) ) ? 'true' : 'false',
         'enable_credit_link' => (isset( $_POST['si_contact_enable_credit_link'] ) ) ? 'true' : 'false',
         'error_contact_select' => strip_tags(trim($_POST['si_contact_error_contact_select'])),
         'error_name'           => strip_tags(trim($_POST['si_contact_error_name'])),
         'error_email'          => strip_tags(trim($_POST['si_contact_error_email'])),
         'error_email2'         => strip_tags(trim($_POST['si_contact_error_email2'])),
         'error_field'          => strip_tags(trim($_POST['si_contact_error_field'])),
         'error_subject'        => strip_tags(trim($_POST['si_contact_error_subject'])),
         'error_message'        => strip_tags(trim($_POST['si_contact_error_message'])),
         'error_input'          => strip_tags(trim($_POST['si_contact_error_input'])),
         'error_captcha_blank'  => strip_tags(trim($_POST['si_contact_error_captcha_blank'])),
         'error_captcha_wrong'  => strip_tags(trim($_POST['si_contact_error_captcha_wrong'])),
         'error_spambot'        => strip_tags(trim($_POST['si_contact_error_spambot'])),
         'error_correct'        => strip_tags(trim($_POST['si_contact_error_correct'])),
         'vcita_enabled'        => (isset($_POST['si_contact_vcita_enable_meeting_scheduler']) ) ? 'true' : 'false', /* --- vCita Parameters --- */
         'vcita_approved'		=> (isset($_POST['si_contact_vcita_approved']) ) ? 'true' : 'false',
         'vcita_email'		=>     ( $_POST['si_contact_vcita_email'] != '' && $this->ctf_validate_email($_POST['si_contact_vcita_email'])) ? $_POST['si_contact_vcita_email'] : '',  /* Keep the old email, let the dedicated logic change it */
         'vcita_confirm_tokens'	=> strip_tags(trim($_POST['si_contact_vcita_confirm_tokens'])),
         'vcita_initialized'	=> strip_tags(trim($_POST['si_contact_vcita_initialized'])),
         'vcita_uid'	=>         strip_tags(trim($_POST['si_contact_vcita_uid'])),
         'vcita_first_name'	=>     strip_tags(trim($_POST['si_contact_vcita_first_name'])),
         'vcita_last_name'	=>     strip_tags(trim($_POST['si_contact_vcita_last_name'])),
           
    );
    
    // optional extra fields
    for ($i = 1; $i <= $optionarray_update['max_fields']; $i++) {
        $optionarray_update['ex_field'.$i.'_label'] = (isset($_POST['si_contact_ex_field'.$i.'_label'])) ? strip_tags(trim($_POST['si_contact_ex_field'.$i.'_label'])) : '';
        $optionarray_update['ex_field'.$i.'_type'] = (isset($_POST['si_contact_ex_field'.$i.'_type'])) ? strip_tags(trim($_POST['si_contact_ex_field'.$i.'_type'])) : 'text';
        $optionarray_update['ex_field'.$i.'_default'] = ( isset($_POST['si_contact_ex_field'.$i.'_default']) && is_numeric(trim($_POST['si_contact_ex_field'.$i.'_default'])) && trim($_POST['si_contact_ex_field'.$i.'_default']) >= 0 ) ? absint(trim($_POST['si_contact_ex_field'.$i.'_default'])) : '0'; // use default if empty
        $optionarray_update['ex_field'.$i.'_default_text'] = (isset($_POST['si_contact_ex_field'.$i.'_default_text'])) ? trim($_POST['si_contact_ex_field'.$i.'_default_text']) : '';
        $optionarray_update['ex_field'.$i.'_max_len'] = ( isset($_POST['si_contact_ex_field'.$i.'_max_len']) && is_numeric(trim($_POST['si_contact_ex_field'.$i.'_max_len'])) && trim($_POST['si_contact_ex_field'.$i.'_max_len']) > 0 ) ? absint(trim($_POST['si_contact_ex_field'.$i.'_max_len'])) : '';
        $optionarray_update['ex_field'.$i.'_label_css'] = (isset($_POST['si_contact_ex_field'.$i.'_label_css'])) ? strip_tags(trim($_POST['si_contact_ex_field'.$i.'_label_css'])) : '';
        $optionarray_update['ex_field'.$i.'_input_css'] = (isset($_POST['si_contact_ex_field'.$i.'_input_css'])) ? strip_tags(trim($_POST['si_contact_ex_field'.$i.'_input_css'])) : '';
        $optionarray_update['ex_field'.$i.'_attributes'] = (isset($_POST['si_contact_ex_field'.$i.'_attributes'])) ? trim($_POST['si_contact_ex_field'.$i.'_attributes']) : '';
        $optionarray_update['ex_field'.$i.'_regex'] = (isset($_POST['si_contact_ex_field'.$i.'_regex'])) ? strip_tags(trim($_POST['si_contact_ex_field'.$i.'_regex'])) : '';
        $optionarray_update['ex_field'.$i.'_regex_error'] = (isset($_POST['si_contact_ex_field'.$i.'_regex_error'])) ? strip_tags(trim($_POST['si_contact_ex_field'.$i.'_regex_error'])) : '';
        $optionarray_update['ex_field'.$i.'_req'] = (isset( $_POST['si_contact_ex_field'.$i.'_req'] ) ) ? 'true' : 'false';
        $optionarray_update['ex_field'.$i.'_notes'] = (isset($_POST['si_contact_ex_field'.$i.'_notes'])) ? trim($_POST['si_contact_ex_field'.$i.'_notes']) : ''; // can have html
        $optionarray_update['ex_field'.$i.'_notes_after'] = (isset($_POST['si_contact_ex_field'.$i.'_notes_after'])) ? trim($_POST['si_contact_ex_field'.$i.'_notes_after']) : ''; // can have html
        if ($optionarray_update['ex_field'.$i.'_label'] != '' && !in_array($optionarray_update['ex_field'.$i.'_type'], array('checkbox','checkbox-multiple','radio','select','select-multiple'))) {
                $optionarray_update['ex_field'.$i.'_default'] = '0';
        }
        if ($optionarray_update['ex_field'.$i.'_label'] == '' && $optionarray_update['ex_field'.$i.'_type'] != 'fieldset-close') {
          $optionarray_update['ex_field'.$i.'_type'] = 'text';
          $optionarray_update['ex_field'.$i.'_default'] = '0';
          $optionarray_update['ex_field'.$i.'_default_text'] = '';
          $optionarray_update['ex_field'.$i.'_max_len'] = '';
          $optionarray_update['ex_field'.$i.'_label_css'] = '';
          $optionarray_update['ex_field'.$i.'_input_css'] = '';
          $optionarray_update['ex_field'.$i.'_attributes'] = '';
          $optionarray_update['ex_field'.$i.'_regex'] = '';
          $optionarray_update['ex_field'.$i.'_regex_error'] = '';
          $optionarray_update['ex_field'.$i.'_req'] = 'false';
          $optionarray_update['ex_field'.$i.'_notes'] = '';
          $optionarray_update['ex_field'.$i.'_notes_after'] = '';
        }
    }

    if (isset($_POST['si_contact_reset_styles'])) {
      // reset styles feature
      $optionarray_update = $this->si_contact_copy_styles($si_contact_option_defaults,$optionarray_update);
    }

    if (isset($_POST['si_contact_reset_styles_left'])) {
        $style_resets_arr = array(
         'border_enable' => 'false',
         'form_style' => 'width:550px;',
         'border_style' => 'border:1px solid black; padding:10px;',
         'required_style' => 'text-align:left;',
         'notes_style' => 'padding-left:146px; text-align:left; clear:left;',
         'title_style' => 'width:138px; text-align:right; float:left; clear:left; padding-top:8px; padding-right:10px;',
         'field_style' => 'text-align:left; float:left; padding:2px; margin:0;',
         'field_div_style' => 'text-align:left; float:left; padding-top:10px;',
         'error_style' => 'text-align:left; color:red;',
         'select_style' => 'text-align:left;',
         'captcha_div_style_sm' => 'float:left; width:162px; height:50px; padding-top:5px;',
         'captcha_div_style_m' => 'float:left; width:362px; height:65px; padding-top:5px;',
         'captcha_input_style' => 'text-align:left; float:left; padding:2px; margin:0; width:50px;',
         'submit_div_style' => 'padding-left:146px; text-align:left; float:left; clear:left; padding-top:8px;',
         'button_style' => 'cursor:pointer; margin:0;',
         'reset_style' => 'cursor:pointer; margin:0;',
         'powered_by_style' => 'padding-left:146px; float:left; clear:left; font-size:x-small; font-weight:normal; padding-top:5px;',
         'redirect_style' => 'text-align:left;',
         'field_size' => '39',
         'captcha_field_size' => '6',
         'text_cols' => '30',
         'text_rows' => '10',
         );

         // reset left styles feature
         foreach($style_resets_arr as $key => $val) {
           $optionarray_update[$key] = $val;
         }
    }

    // unencode < > & " ' (less than, greater than, ampersand, double quote, single quote).
    foreach($optionarray_update as $key => $val) {
           $optionarray_update[$key] = str_replace('&lt;','<',$val);
           $optionarray_update[$key] = str_replace('&gt;','>',$val);
           $optionarray_update[$key] = str_replace('&#39;',"'",$val);
           $optionarray_update[$key] = str_replace('&quot;','"',$val);
           $optionarray_update[$key] = str_replace('&amp;','&',$val);
    }

	/* --- vCita Update details - Start --- */
	
    $email_changed = isset($_POST['si_contact_vcita_email_new']) && $optionarray_update['vcita_email'] != trim($_POST['si_contact_vcita_email_new']);
    $vcita_user_changed = isset($_POST['vcita_create']);    // why not validating email address?
    
    if (isset($_POST['si_contact_vcita_email_new']) && trim($_POST['si_contact_vcita_email_new']) != "" && 
        ($email_changed || $optionarray_update['vcita_initialized'] == 'false')) {
        
	    $optionarray_update['vcita_approved'] = 'true';
	    $optionarray_update['vcita_enabled'] = 'true';
	    $optionarray_update['vcita_email'] = trim($_POST['si_contact_vcita_email_new']);
	    
	    $optionarray_update = $this->vcita_generate_or_validate_user($optionarray_update);
	    $vcita_user_changed = true;
	    
	} elseif ($optionarray_update['vcita_approved'] == 'true' && $optionarray_update['vcita_enabled'] == 'true' && !empty($optionarray_update['vcita_uid'])) {
	    $optionarray_update = $this->vcita_check_user($optionarray_update);
	}
	
	/* --- vCita Update details - End --- */
	
    // save updated options to the database
    update_option("si_contact_form$form_num", $optionarray_update);

    // get the options from the database
    $si_contact_opt = get_option("si_contact_form$form_num");

    // save updated global options to the database
    update_option("si_contact_form_gb", $optionarray_gb_update);

    $redirect_to_form_1 = 0;
    if ( $optionarray_gb_update['max_forms'] != $si_contact_gb['max_forms'] ) {
       if ($optionarray_gb_update['max_forms'] < $si_contact_gb['max_forms']) {
         // delete all multi-forms higher than set number
         for ($i = $optionarray_gb_update['max_forms'] + 1; $i <= 100; $i++) {
            delete_option("si_contact_form$i");
         }
       }
      // max_forms settings has changed, need to redirect to form 1 later on
      $redirect_to_form_1 = 1;
    }

    // get the global options from the database
    $si_contact_gb = get_option("si_contact_form_gb");

    // strip slashes on get options array
    foreach($si_contact_opt as $key => $val) {
           $si_contact_opt[$key] = $this->ctf_stripslashes($val);
    }

    if (function_exists('wp_cache_flush')) {
	     wp_cache_flush();
	}

    if ($redirect_to_form_1) {
       // max_forms settings has changed, need to redirect to form 1
       $ctf_redirect_url = admin_url(  "plugins.php?page=si-contact-form/si-contact-form.php" );
       $ctf_redirect_timeout = 1;
 echo <<<EOT

<script type="text/javascript" language="javascript">
//<![CDATA[
var ctf_redirect_seconds=$ctf_redirect_timeout;
var ctf_redirect_time;
function ctf_redirect() {
  document.title='Redirecting in ' + ctf_redirect_seconds + ' seconds';
  ctf_redirect_seconds=ctf_redirect_seconds-1;
  ctf_redirect_time=setTimeout("ctf_redirect()",1000);
  if (ctf_redirect_seconds==-1) {
    clearTimeout(ctf_redirect_time);
    document.title='Redirecting ...';
    self.location='$ctf_redirect_url';
  }
}
function ctf_addOnloadEvent(fnc){
  if ( typeof window.addEventListener != "undefined" )
    window.addEventListener( "load", fnc, false );
  else if ( typeof window.attachEvent != "undefined" ) {
    window.attachEvent( "onload", fnc );
  }
  else {
    if ( window.onload != null ) {
      var oldOnload = window.onload;
      window.onload = function ( e ) {
        oldOnload( e );
        window[fnc]();
      };
    }
    else
      window.onload = fnc;
  }
}
ctf_addOnloadEvent(ctf_redirect);
//]]>
</script>
EOT;

echo '
<div id="message" class="updated"><p><strong>
<img src="'.plugins_url( 'si-contact-form/ctf-loading.gif' ) .'" alt="'.esc_attr(__('Redirecting to Form 1', 'si-contact-form')).'" />&nbsp;&nbsp;
'.esc_html(__('Redirecting to Form 1', 'si-contact-form')).' ...
</strong></p></div>
';

    }


  } // end if (isset($_POST['submit']))


if ( !isset($_GET['show_form']) && !isset($_POST['fsc_action']) ) {

  // update translation for this setting (when switched from English to something else)
  if ($si_contact_opt['welcome'] == '<p>Comments or questions are welcome.</p>') {
       $si_contact_opt['welcome'] = __('<p>Comments or questions are welcome.</p>', 'si-contact-form');
  }

?>
<?php if ( !empty($_POST )  && !isset($_POST['ctf_action'])) : ?>
<div id="message" class="updated"><p><strong><?php _e('Options saved.', 'si-contact-form'); ?></strong></p></div>
<?php endif; ?>


<?php 

  /* --- vCita Check for changes in normal page view - Start  --- */

  if (empty($_POST )) {
    // Check if the current user is unconfirmed - if so, check his current status
    if ($si_contact_opt['vcita_enabled'] == 'true' && $si_contact_opt['vcita_approved'] == 'true' && !empty($si_contact_opt['vcita_uid'])) {
        $si_contact_opt = $this->vcita_check_user($si_contact_opt);
        update_option("si_contact_form$form_num", $si_contact_opt);
    }

  }
  
  /* --- vCita Check for changes in normal page view - End  --- */
?>

<div class="wrap">
 <div id="main">

<h2><?php _e('Fast Secure Contact Form Options', 'si-contact-form'); ?></h2>

<?php
   
  /* --- vCita Header Error Messages - Start --- */
  
  if ($vcita_dismissed) {
      // Put visible notification that vCita was removed.
      echo "<div class='fsc-success'>vCita Meeting Scheduler has been disabled</div><div style='clear:both;display:block'></div>";
  } else {
      $this->vcita_print_admin_page_notification($si_contact_gb, $si_contact_opt, $form_num, true);
  }
  
  /* --- vCita Header Error Messages - End --- */
  
?>
  
<script type="text/javascript">
    function toggleVisibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
</script>

<?php

// for testing echo 'post var count: ' . count($_POST).'<br />';

$av_fld_arr  = array(); // used to show available field tags this form
$av_fld_subj_arr  = array(); // used to show available field tags for this form  subject

if ($si_contact_opt['name_type'] != 'not_available') {
   switch ($si_contact_opt['name_format']) {
      case 'name':
         $av_fld_arr[] = 'from_name';
      break;
      case 'first_last':
         $av_fld_arr[] = 'first_name';
         $av_fld_arr[] = 'last_name';
      break;
      case 'first_middle_i_last':
         $av_fld_arr[] = 'first_name';
         $av_fld_arr[] = 'middle_initial';
         $av_fld_arr[] = 'last_name';
      break;
      case 'first_middle_last':
         $av_fld_arr[] = 'first_name';
         $av_fld_arr[] = 'middle_name';
         $av_fld_arr[] = 'last_name';
      break;
   }
}
// email
$autoresp_ok = 1; // used in autoresp settings below
if ($si_contact_opt['email_type'] != 'not_available') {
        $av_fld_arr[] = 'from_email';
}else{
   $autoresp_ok = 0;
}
        // optional extra fields
for ($i = 1; $i <= $si_contact_opt['max_fields']; $i++) {
    if ( $si_contact_opt['ex_field'.$i.'_label'] != '' && $si_contact_opt['ex_field'.$i.'_type'] != 'fieldset-close') {
      if ($si_contact_opt['ex_field'.$i.'_type'] == 'fieldset') {
      } else if ($si_contact_opt['ex_field'.$i.'_type'] == 'attachment' && $si_contact_opt['php_mailer_enable'] != 'php') {
            $av_fld_arr[] = "ex_field$i";
      } else {  // text, textarea, date, password, email, url, hidden, time, select, select-multiple, radio, checkbox, checkbox-multiple
            $av_fld_arr[] = "ex_field$i";
            if ($si_contact_opt['ex_field'.$i.'_type'] == 'email')
              $autoresp_ok = 1;
      }
    }
} // end for
//if ($si_contact_opt['email_type'] != 'not_available')
        $av_fld_subj_arr = $av_fld_arr;
//if ($si_contact_opt['subject_type'] != 'not_available')
   $av_fld_arr[] = 'subject';
if ($si_contact_opt['message_type'] != 'not_available')
   $av_fld_arr[] = 'message';
   $av_fld_arr[] = 'full_message';
if (function_exists('akismet_verify_key'))
   $av_fld_arr[] = 'akismet';

$av_fld_arr[] = 'date_time';
$av_fld_subj_arr[] = 'form_label';

if (function_exists('get_transient')) {
  require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

  // First, try to access the data, check the cache.
  if (false === ($api = get_transient('si_contact_form_info'))) {
    // The cache data doesn't exist or it's expired.

    $api = plugins_api('plugin_information', array('slug' => stripslashes( 'si-contact-form' ) ));

    if ( !is_wp_error($api) ) {
      // cache isn't up to date, write this fresh information to it now to avoid the query for xx time.
      $myexpire = 60 * 15; // Cache data for 15 minutes
      set_transient('si_contact_form_info', $api, $myexpire);
    }
  }
  if ( !is_wp_error($api) ) {
	  $plugins_allowedtags = array('a' => array('href' => array(), 'title' => array(), 'target' => array()),
								'abbr' => array('title' => array()), 'acronym' => array('title' => array()),
								'code' => array(), 'pre' => array(), 'em' => array(), 'strong' => array(),
								'div' => array(), 'p' => array(), 'ul' => array(), 'ol' => array(), 'li' => array(),
								'h1' => array(), 'h2' => array(), 'h3' => array(), 'h4' => array(), 'h5' => array(), 'h6' => array(),
								'img' => array('src' => array(), 'class' => array(), 'alt' => array()));
	  //Sanitize HTML
	  foreach ( (array)$api->sections as $section_name => $content )
		$api->sections[$section_name] = wp_kses($content, $plugins_allowedtags);
	  foreach ( array('version', 'author', 'requires', 'tested', 'homepage', 'downloaded', 'slug') as $key )
		$api->$key = wp_kses($api->$key, $plugins_allowedtags);

      if ( ! empty($api->downloaded) ) {
        echo sprintf(__('Downloaded %s times', 'si-contact-form'),number_format_i18n($api->downloaded));
        echo '.';
      }
?>

      <?php if ( ! empty($api->rating) ) : ?>
	  <div class="fsc-star-holder" title="<?php echo esc_attr(sprintf(__('(Average rating based on %s ratings)', 'si-contact-form'),number_format_i18n($api->num_ratings))); ?>">
	  <div class="fsc-star fsc-star-rating" style="width: <?php echo esc_attr($api->rating) ?>px"></div>
	  <div class="fsc-star fsc-star5"><img src="<?php echo plugins_url( 'si-contact-form/star.png' ); ?>" alt="<?php _e('5 stars', 'si-contact-form') ?>" /></div>
	  <div class="fsc-star fsc-star4"><img src="<?php echo plugins_url( 'si-contact-form/star.png' ); ?>" alt="<?php _e('4 stars', 'si-contact-form') ?>" /></div>
	  <div class="fsc-star fsc-star3"><img src="<?php echo plugins_url( 'si-contact-form/star.png' ); ?>" alt="<?php _e('3 stars', 'si-contact-form') ?>" /></div>
	  <div class="fsc-star fsc-star2"><img src="<?php echo plugins_url( 'si-contact-form/star.png' ); ?>" alt="<?php _e('2 stars', 'si-contact-form') ?>" /></div>
	  <div class="fsc-star fsc-star1"><img src="<?php echo plugins_url( 'si-contact-form/star.png' ); ?>" alt="<?php _e('1 star', 'si-contact-form') ?>" /></div>
	  </div>
	  <small><?php echo sprintf(__('(Average rating based on %s ratings)', 'si-contact-form'),number_format_i18n($api->num_ratings)); ?> <a target="_blank" href="http://wordpress.org/support/view/plugin-reviews/si-contact-form"> <?php _e('rate', 'si-contact-form') ?></a></small>
      <br />
    <?php endif;

  } // if ( !is_wp_error($api)
 }// end if (function_exists('get_transient'

$fsc_update = '';
if (isset($api->version)) {
 if ( version_compare($api->version, $ctf_version, '>') ) {
     $fsc_update = ', <a href="'.admin_url( 'plugins.php' ).'">'.sprintf(__('a newer version is available: %s', 'si-contact-form'),$api->version).'</a>';
     echo '<div id="message" class="updated">';
     echo '<a href="'.admin_url( 'plugins.php' ).'">'.sprintf(__('A newer version of Fast Secure Contact Form is available: %s', 'si-contact-form'),$api->version).'</a>';
     echo "</div>\n";
  }else{
     $fsc_update = ' '. __('(latest version)', 'si-contact-form');
  }
}
?>

<p>Good news! A major update is being worked on. The Fast Secure Contact Form 4.0 project began in late August 2012 and is making great progress. Read about the changes here:<br />
<a href="http://wordpress.org/support/topic/fast-secure-contact-form-40-project-reports" target="_blank">Fast Secure Contact Form 4.0 project reports</a></p>
<p><strong>How you can help with the new 4.0 verion:</strong> <a href="http://www.fastsecurecontactform.com/donate" target="_blank">Donate to the project</a>, and/or contribute your ideas in the <a href="http://wordpress.org/support/topic/working-on-a-40-version" target="_blank">Working on a 4.0 Version</a> post.
</p>

<p>
<?php echo __('Version:', 'si-contact-form'). ' '.$ctf_version.$fsc_update; ?> |
<a href="http://wordpress.org/extend/plugins/si-contact-form/changelog/" target="_blank"><?php _e('Changelog', 'si-contact-form'); ?></a> |
<a href="http://www.fastsecurecontactform.com/faq-wordpress-version" target="_blank"><?php _e('FAQ', 'si-contact-form'); ?></a> |
<a href="http://wordpress.org/support/view/plugin-reviews/si-contact-form" target="_blank"><?php _e('Rate This', 'si-contact-form'); ?></a> |
<a href="http://www.fastsecurecontactform.com/support" target="_blank"><?php _e('Support', 'si-contact-form'); ?></a> |
<a href="http://www.fastsecurecontactform.com/donate" target="_blank"><?php _e('Donate', 'si-contact-form'); ?></a> |
<a href="http://www.642weather.com/weather/scripts.php" target="_blank"><?php _e('Free PHP Scripts', 'si-contact-form'); ?></a> |
<a href="http://www.fastsecurecontactform.com/contact" target="_blank"><?php _e('Contact', 'si-contact-form'); ?> Mike Challis</a>
</p>

<?php
// action hook for database extension menu
do_action( 'fsctf_menu_links' );

if ($si_contact_gb['donated'] != 'true') {
  ?>

  <table style="border:none; width:850px;">
  <tr>
  <td>
  <div style="width:385px;height:200px; float:left;background-color:white;padding: 10px 10px 10px 10px; border: 1px solid #ddd; background-color:#FFFFE0;">
		<div>
          <h3><?php _e('Donate', 'si-contact-form'); ?></h3>

<?php
_e('Please donate to keep this plugin FREE', 'si-contact-form'); echo '<br />';
_e('If you find this plugin useful to you, please consider making a small donation to help contribute to my time invested and to further development. Thanks for your kind support!', 'si-contact-form'); echo ' '; ?>
- <a style="cursor:pointer;" title="<?php _e('More from Mike Challis', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_mike_challis_tip');"><?php _e('More from Mike Challis', 'si-contact-form'); ?></a>
 <br /> <br />
   </div>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
  <input type="hidden" name="cmd" value="_s-xclick" />
  <input type="hidden" name="hosted_button_id" value="5KKKX5BK5HHW2" />
  <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" style="border:none;" name="submit" alt="Paypal Donate" />
  <img alt="" style="border:none;" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
</form>
  </div>

  </td><td>

<?php
 $banner_alt_1 = '<div style="width:415px;height:220px; float:left;padding: 0; border: 1px solid #ddd;">
		<a href="http://www.vcita.com/landings/partner_fast_secure?supply_password=true&invite=wp-fscf&o=int.6" target="_blank">
			<img src="' . esc_url($this->vcita_banner_location()) .'" width="415px" height="220px" />
		</a>
	</div>
';

 $banner_alt_2 = '<div style="width:305px;height:300px; float:left;background-color:white;padding: 10px 10px 10px 20px; border: 1px solid #ddd;">
		<div>
			<h3>' . __('ThemeFuse Original WP Themes', 'si-contact-form') .'</h3>
		</div>
        <a href="http://themefuse.com/amember/aff/go?r=6664&amp;i=46" target="_blank"><img src="http://themefuse.com/amember/file/get/path/.banners.505787138b254/i/6664" border=0 alt="300x250" width="300" height="250"></a>
  </div>
 ';
 $banner_alt_num = rand (1,2);
 if ($si_contact_opt['vcita_enabled'] == 'true')
    $banner_alt_num = 2;
 switch ($banner_alt_num)
 {
 case 1:
 echo $banner_alt_1;
 break;
 case 2:
 echo $banner_alt_2;
 }
  ?>
  
  </td>
 </tr>
 </table>

<br />

<div style="text-align:left; display:none" id="si_contact_mike_challis_tip">
<img src="<?php echo plugins_url( 'si-contact-form/si-contact-form.jpg' ); ?>" width="250" height="185" alt="Mike Challis" /><br />
<?php _e('Mike Challis says: "Hello, I have spent hundreds of hours coding this plugin just for you. If you are satisfied with my programs and support please consider making a small donation. If you are not able to, that is OK.', 'si-contact-form'); ?>
<?php echo ' '; _e('Suggested donation: $25, $20, $15, $10, $5, $3. Donations can be made with your PayPal account, or securely using any of the major credit cards. Please also rate my plugin."', 'si-contact-form'); ?>
 <a href="http://wordpress.org/extend/plugins/si-contact-form/" target="_blank"><?php _e('Rate This', 'si-contact-form'); ?></a>.
<br />
<a style="cursor:pointer;" title="Close" onclick="toggleVisibility('si_contact_mike_challis_tip');"><?php _e('Close this message', 'si-contact-form'); ?></a>
</div>

<?php
}
?>
<form name="formoptions" action="<?php echo admin_url( "plugins.php?ctf_form_num=$form_num&amp;page=si-contact-form/si-contact-form.php" ); ?>" method="post">
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="form_type" value="upload_options" />
        <?php wp_nonce_field('si-contact-form-options_update', 'options_update'); ?>

    <input name="si_contact_donated" id="si_contact_donated" type="checkbox" <?php if( $si_contact_gb['donated'] == 'true' ) echo 'checked="checked"'; ?> />
    <label for="si_contact_donated"><?php _e('I have donated to help contribute for the development of this Contact Form.', 'si-contact-form'); ?></label>
    <br />

<h3><?php _e('Usage', 'si-contact-form'); ?></h3>


<p>
<?php _e('Add the shortcode in a Page, Post, or Text Widget', 'si-contact-form'); ?>. <a href="<?php echo plugins_url( 'si-contact-form/screenshot-4.gif' ); ?>" target="_new"><?php _e('help', 'si-contact-form'); ?></a>
<br />
<?php _e('Shortcode for this form:', 'si-contact-form'); echo " [si-contact-form form='$form_id']"; ?>
</p>

<?php
if( function_exists('get_sfc_like_button') || function_exists('get_sfc_share_button') ) {
  echo '<div id="message" class="error">';
  echo __('SFC Like and SFC Share plugins cause problems with Fast Secure Contact Form, please disable or uninstall SFC Like and SFC Share plugins.', 'si-contact-form');
  echo ' <a href="http://www.fastsecurecontactform.com/error-message-sfc-like">'. __('help', 'si-contact-form') . '</a>
  </div>'."\n";
}
?>


<h3><?php _e('Options', 'si-contact-form'); ?></h3>

<div class="form-tab"><?php echo __('Multi-forms:', 'si-contact-form').' '. sprintf(__('(form %d)', 'si-contact-form'),$form_id);?></div>
<div class="clear"></div>
<fieldset>

<h3><?php
  // multi-form selector
  for ($i = 1; $i <= $si_contact_gb['max_forms']; $i++) {
     if($i == 1) {
         if ($form_id == 1) {
             echo '<b>'.sprintf(__('Form: %d', 'si-contact-form'),1).'</b>';
             echo ' <small><a href="' . admin_url(  "plugins.php?show_form=1&amp;page=si-contact-form/si-contact-form.php" ) . '">('. __('view', 'si-contact-form'). ')</a></small>';
        } else {
             echo '<a href="' . admin_url(  'plugins.php?page=si-contact-form/si-contact-form.php' ) . '">'. sprintf(__('Form: %d', 'si-contact-form'),1). '</a>';
        }
     } else {
        if ($form_id == $i) {
             echo ' | <b>' . sprintf(__('Form: %d', 'si-contact-form'),$i).'</b>';
             echo ' <small><a href="' . admin_url(  'plugins.php?show_form='.$i.'&amp;ctf_form_num='.$i.'&amp;page=si-contact-form/si-contact-form.php' ) . '">('. __('view', 'si-contact-form'). ')</a></small>';
        } else {
             echo ' | <a href="' . admin_url(  'plugins.php?ctf_form_num='.$i.'&amp;page=si-contact-form/si-contact-form.php' ) . '">'. sprintf(__('Form: %d', 'si-contact-form'),$i). '</a>';
        }
     }
  }
  ?>
  </h3>
  <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_multi_tip');"><?php _e('Multi-forms help', 'si-contact-form'); ?></a>
  <div style="text-align:left; display:none" id="si_contact_multi_tip">
  <?php _e('This multi-form feature allows you to have many different forms on your site. Each form has unique settings and shortcode. Select the form you want to edit using the links above, then edit the settings below for the form you selected. Be sure to use the correct shortcode to call the form.', 'si-contact-form') ?>
  </div>

<br />
<label for="si_contact_max_forms"><?php _e('Number of available Multi-forms', 'si-contact-form'); ?>:</label>
<input name="si_contact_max_forms" id="si_contact_max_forms" type="text" onclick="return alert('<?php _e('Caution: Lowering this setting deletes forms.', 'si-contact-form'); ?>')" value="<?php echo absint($si_contact_gb['max_forms']);  ?>" size="3" />
<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_multi_num_tip');"><?php _e('help', 'si-contact-form'); ?></a>
<div style="text-align:left; display:none" id="si_contact_multi_num_tip">
<?php _e('Use this setting to increase or decrease the number of available forms. The most forms you can add is 99. Caution: lowering this number will delete forms of a higher number than the number you set.', 'si-contact-form') ?>
</div>

<br />

<label for="si_contact_form_name"><?php echo sprintf(__('Form %d label', 'si-contact-form'),$form_id) ?>:</label><input name="si_contact_form_name" id="si_contact_form_name" type="text" value="<?php echo esc_attr($si_contact_opt['form_name']);  ?>" size="55" />
<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_form_name_tip');"><?php _e('help', 'si-contact-form'); ?></a>
<div style="text-align:left; display:none" id="si_contact_form_name_tip">
<?php _e('Enter a label for your form. This is not used anywhere else, it just helps you keep track of what you are using it for.', 'si-contact-form'); ?>
</div>

</fieldset>

    <p class="submit">
      <input type="submit" name="submit" value="<?php echo esc_attr( __('Update Options', 'si-contact-form')); ?> &raquo;" />
    </p>


<div class="form-tab"><?php echo __('Form:', 'si-contact-form') .' '. sprintf(__('(form %d)', 'si-contact-form'),$form_id);?></div>
<div class="clear"></div>
<fieldset>

        <label for="si_contact_welcome"><?php _e('Welcome introduction', 'si-contact-form'); ?>:</label><br />
        <textarea rows="6" cols="70" name="si_contact_welcome" id="si_contact_welcome"><?php echo $this->ctf_output_string($si_contact_opt['welcome']); // can have html  ?></textarea>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_welcome_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_welcome_tip">
        <?php _e('This is printed before the contact form. HTML is allowed.', 'si-contact-form') ?>
        </div>

</fieldset>

    <p class="submit">
      <input type="submit" name="submit" value="<?php echo esc_attr( __('Update Options', 'si-contact-form')); ?> &raquo;" />
    </p>

<div class="form-tab"><?php echo __('E-mail:', 'si-contact-form') .' '. sprintf(__('(form %d)', 'si-contact-form'),$form_id);?></div>
<div class="clear"></div>
<fieldset>

<?php
// checks for properly configured E-mail To: addresses in options.
$ctf_contacts = array ();
$ctf_contacts_test = trim($si_contact_opt['email_to']);
$ctf_contacts_error = 0;
if(!preg_match("/,/", $ctf_contacts_test) ) {
    if($this->ctf_validate_email($ctf_contacts_test)) {
        // user1@example.com
       $ctf_contacts[] = array('CONTACT' => __('Webmaster', 'si-contact-form'),  'EMAIL' => $ctf_contacts_test );
    }
} else {
  $ctf_ct_arr = explode("\n",$ctf_contacts_test);
  if (is_array($ctf_ct_arr) ) {
    foreach($ctf_ct_arr as $line) {
        // echo '|'.$line.'|' ;
       list($key, $value) = preg_split('#(?<!\\\)\,#',$line); //string will be split by "," but "\," will be ignored
       $key   = trim(str_replace('\,',',',$key)); // "\," changes to ","
       $value = trim($value);
       if ($key != '' && $value != '') {
          if(!preg_match("/;/", $value)) {
               // just one email here
               // Webmaster,user1@example.com
               $value = str_replace('[cc]','',$value);
               $value = str_replace('[bcc]','',$value);
               if ($this->ctf_validate_email($value)) {
                  $ctf_contacts[] = array('CONTACT' => $key,  'EMAIL' => $value);
               } else {
                  $ctf_contacts_error = 1;
               }
          } else {
               // multiple emails here (additional ones will be Cc:)
               // Webmaster,user1@example.com;user2@example.com;user3@example.com;[cc]user4@example.com;[bcc]user5@example.com
               $multi_cc_arr = explode(";",$value);
               $multi_cc_string = '';
               foreach($multi_cc_arr as $multi_cc) {
               $multi_cc_t = str_replace('[cc]','',$multi_cc);
               $multi_cc_t = str_replace('[bcc]','',$multi_cc_t);
                  if ($this->ctf_validate_email($multi_cc_t)) {
                     $multi_cc_string .= "$multi_cc,";
                  } else {
                     $ctf_contacts_error = 1;
                  }
               }
               if ($multi_cc_string != '') {  // multi cc emails
                  $ctf_contacts[] = array('CONTACT' => $key,  'EMAIL' => rtrim($multi_cc_string, ','));
               }
         }
      }
   } // end foreach
  } // end if (is_array($ctf_ct_arr) ) {
} // end else

//print_r($ctf_contacts);

?>
        <label for="si_contact_email_to"><?php _e('E-mail To', 'si-contact-form'); ?>:</label>
<?php
if (empty($ctf_contacts) || $ctf_contacts_error ) {
       echo '<div id="message" class="error">';
       echo __('ERROR: Misconfigured "E-mail To" address.', 'si-contact-form');
       echo "</div>\n";
       echo '<div class="fsc-error">'. __('ERROR: Misconfigured "E-mail To" address.', 'si-contact-form').'</div>'."\n";
}

if ( !function_exists('mail') ) {
   echo '<div class="fsc-error">'. __('Warning: Your web host has the mail() function disabled. PHP cannot send email.', 'si-contact-form');
   echo ' '. __('Have them fix it. Or you can install the "WP Mail SMTP" plugin and configure it to use SMTP.', 'si-contact-form').'</div>'."\n";
}
?>
        <br />
        <textarea rows="6" cols="70" name="si_contact_email_to" id="si_contact_email_to"><?php echo esc_attr($si_contact_opt['email_to']);  ?></textarea>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_email_to_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_email_to_tip">
        <?php _e('E-mail address the messages are sent to (your email). Add as many contacts as you need, the drop down list on the contact form will be made automatically. Each contact has a name and an email address separated by a comma. Separate each contact by pressing enter. If you need to add more than one contact, follow this example:', 'si-contact-form'); ?><br />
        <?php _e('If you need to use a comma in the name, escape it with a back slash, like this: \,', 'si-contact-form'); ?><br />
        Webmaster,user1@example.com<br />
        Sales,user2@example.com<br /><br />

        <?php echo  __('You can have multiple emails per contact using [cc]Carbon Copy. Separate each email with a semicolon. Follow this example:', 'si-contact-form'); ?><br />
        Sales,user3@example.com;user4@example.com;user5@example.com<br /><br />

        <?php echo  __('You can specify [cc]Carbon Copy or [bcc]Blind Carbon Copy by using tags. Separate each email with a semicolon. Follow this example:', 'si-contact-form'); ?><br />
        Sales,user3@example.com;[cc]user1@example.com;[cc]user2@example.com;[bcc]user3@example.com;[bcc]user4@example.com
        </div>
        <br />
  <?php
   // Check for safe mode
    $safe_mode_is_on = ((boolean)@ini_get('safe_mode') === false) ? 0 : 1;
    if($safe_mode_is_on){
      echo '<br /><span style="color:red;">'. __('Warning: Your web host has PHP safe_mode turned on.', 'si-contact-form');
      echo '</span> ';
      echo __('PHP safe_mode can cause problems like sending mail failures and file permission errors.', 'si-contact-form')."<br />\n";
      echo __('Contact your web host for support.', 'si-contact-form')."<br /><br />\n";
    }

    // Check for older than PHP5
   if (phpversion() < 5) {
      echo '<br /><span style="color:red;">'. __('Warning: Your web host has not upgraded from PHP4 to PHP5.', 'si-contact-form');
      echo '</span> ';
      echo __('PHP4 was officially discontinued August 8, 2008 and is no longer considered safe.', 'si-contact-form')."<br />\n";
      echo __('Contact your web host for support.', 'si-contact-form')."<br /><br />\n";
    }

if ( $si_contact_opt['email_from'] != '' ) {
    $from_fail = 0;
    if(!preg_match("/,/", $si_contact_opt['email_from'])) {
        // just one email here
        // user1@example.com
        if (!$this->ctf_validate_email($si_contact_opt['email_from'])) {
           $from_fail = 1;
        }
    } else {
        // name and email here
        // webmaster,user1@example.com
        list($key, $value) = explode(",",$si_contact_opt['email_from']);
        $key   = trim($key);
        $value = trim($value);
        if (!$this->ctf_validate_email($value)) {
           $from_fail = 1;
        }
   }

   if ($from_fail)  {
       echo '<div id="message" class="error">';
       echo __('ERROR: Misconfigured "E-mail From" address.', 'si-contact-form');
       echo "</div>\n";
       echo '<div class="fsc-error">'. __('ERROR: Misconfigured "E-mail From" address.', 'si-contact-form').'</div>'."\n";
   } else {
       $uri = parse_url(get_option('home'));
       $blogdomain = preg_replace("/^www\./i",'',$uri['host']);
       list($email_from_user,$email_from_domain) = explode('@',$si_contact_opt['email_from']);
       if ( $blogdomain != $email_from_domain) {
       echo '<div id="message" class="updated">';
       echo sprintf(__('Warning: "E-mail From" is not set to an address from the same domain name as your web site (%s). This can sometimes cause mail not to send, or send but be delivered to a Spam folder. Be sure to test that your form is sending email and that you are receiving it, if not, fix this setting.', 'si-contact-form'), $blogdomain);
       echo "</div>\n";
       echo '<div class="fsc-notice">';
       echo sprintf(__('Warning: "E-mail From" is not set to an address from the same domain name as your web site (%s). This can sometimes cause mail not to send, or send but be delivered to a Spam folder. Be sure to test that your form is sending email and that you are receiving it, if not, fix this setting.', 'si-contact-form'), $blogdomain);
       echo "</div>\n";
       }
   }
}
?>
        <label for="si_contact_email_from"><?php _e('Custom E-mail From (optional)', 'si-contact-form'); ?>:</label>
        <input name="si_contact_email_from" id="si_contact_email_from" type="text" value="<?php echo esc_attr($si_contact_opt['email_from']);  ?>" size="50" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_email_from_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_email_from_tip">
        <?php _e('E-mail address the messages are sent from. Some web hosts do not allow PHP to send email unless the envelope sender email address is on the same web domain as your web site. And they require it to be a real address on that domain, or mail will NOT SEND! (They do this to help prevent spam.) If your contact form does not send any email, then set this to a real email address on the SAME domain as your web site, then test the form.', 'si-contact-form'); ?>
        <?php _e('If your form still does not send any email, also check the setting below: "Enable when web host requires "Mail From" strictly tied to domain email account". In some cases, this will resolve the problem. This setting is also recommended for gmail users to prevent email from going to spam folder.', 'si-contact-form'); ?>
        <br />
        <?php _e('Enter just an email: user1@example.com', 'si-contact-form'); ?><br />
        <?php _e('Or enter name and email: webmaster,user1@example.com ', 'si-contact-form'); ?>
        </div>
        <br />

        <?php
       if( $si_contact_opt['email_from_enforced'] == 'true' && $si_contact_opt['email_from'] == '') {
         echo '<div class="fsc-error">';
         echo __('Warning: Enabling this setting requires the "E-mail From" setting above to also be set.', 'si-contact-form');
         echo "</div>\n";
       }
       ?>
        <input name="si_contact_email_from_enforced" id="si_contact_email_from_enforced" type="checkbox" <?php if( $si_contact_opt['email_from_enforced'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_email_from_enforced"><?php _e('Enable when web host requires "Mail From" strictly tied to domain email account.', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_email_from_enforced_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_email_from_enforced_tip">
        <?php _e('If your form does not send any email, then set the "E-mail From" setting above to an address on the same web domain as your web site. If email still does not send, also check this setting. (ie: some users report this is required by yahoo small business web hosting)', 'si-contact-form') ?>
        </div>
        <br />

        <label for="si_contact_email_reply_to"><?php _e('Custom Reply To (optional)', 'si-contact-form'); ?>:</label>
        <input name="si_contact_email_reply_to" id="si_contact_email_reply_to" type="text" value="<?php echo esc_attr($si_contact_opt['email_reply_to']);  ?>" size="50" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_email_reply_to_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_email_reply_to_tip">
        <?php _e('Leave this setting blank for most forms because the "reply to" is set automatically. Only use this setting if you are using the form for a mailing list and you do NOT want the reply going to the form user.', 'si-contact-form'); ?>
        <?php _e('Defines the email address that is automatically inserted into the "To:" field when a user replies to an email message.', 'si-contact-form'); ?>
        <br />
        <?php _e('Enter just an email: user1@example.com', 'si-contact-form'); ?><br />
        </div>
        <br />

       <label for="si_contact_php_mailer_enable"><?php _e('Send E-mail function:', 'si-contact-form'); ?></label>
      <select id="si_contact_php_mailer_enable" name="si_contact_php_mailer_enable">
<?php

$selected = '';
foreach (array( 'wordpress' => __('WordPress', 'si-contact-form'),'php' => __('PHP', 'si-contact-form')) as $k => $v) {
 if ($si_contact_opt['php_mailer_enable'] == "$k")  $selected = ' selected="selected"';
 echo '<option value="'.esc_attr($k).'"'.$selected.'>'.esc_html($v).'</option>'."\n";
 $selected = '';
}
?>
</select>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_php_mailer_enable_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_php_mailer_enable_tip">
        <?php _e('Emails are normally sent by the wordpress mail function. Other functions are provided for diagnostic uses.', 'si-contact-form'); ?>
        <?php _e('If your form does not send any email, first try setting the "E-mail From" setting above because some web hosts do not allow PHP to send email unless the "From:" email address is on the same web domain.', 'si-contact-form'); ?>
        <?php _e('Note: attachments are only supported when using the "WordPress" mail function.', 'si-contact-form'); ?>
       </div>
       <br />

        <input name="si_contact_email_html" id="si_contact_email_html" type="checkbox" <?php if( $si_contact_opt['email_html'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_email_html"><?php _e('Enable to receive email as HTML instead of plain text.', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_email_html_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_email_html_tip">
        <?php _e('Enable if you want the email message sent as HTML format. HTML format is desired if you want to avoid a 70 character line wordwrap when you copy and paste the email message. Normally the email is sent in plain text wordwrapped 70 characters per line to comply with most email programs.', 'si-contact-form') ?>
        </div>
        <br />
<?php
if ( $si_contact_opt['email_bcc'] != ''){
    $bcc_fail = 0;
    if(!preg_match("/,/", $si_contact_opt['email_bcc'])) {
         // just one email here
         // user1@example.com
         if (!$this->ctf_validate_email($si_contact_opt['email_bcc'])) {
             $bcc_fail = 1;
         }
    } else {
         // multiple emails here
         // user1@example.com,user2@example.com
         $bcc_arr = explode(",",$si_contact_opt['email_bcc']);
         foreach($bcc_arr as $b_cc) {
             if (!$this->ctf_validate_email($b_cc)) {
                $bcc_fail = 1;
                break;
             }
         }
   }
   if ($bcc_fail)  {
      echo '<div id="message" class="error">';
      echo __('ERROR: Misconfigured "Bcc E-mail" address.', 'si-contact-form');
      echo "</div>\n";
      echo '<div class="fsc-error">'. __('ERROR: Misconfigured "Bcc E-mail" address.', 'si-contact-form').'</div>'."\n";
   }
}
?>

      <label for="si_contact_email_bcc"><?php _e('E-mail Bcc (optional)', 'si-contact-form'); ?>:</label>
        <input name="si_contact_email_bcc" id="si_contact_email_bcc" type="text" value="<?php echo esc_attr($si_contact_opt['email_bcc']);  ?>" size="50" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_email_bcc_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_email_bcc_tip">
        <?php _e('E-mail address(s) to receive Bcc (Blind Carbon Copy) messages. You can send to multiple or single, both methods are acceptable:', 'si-contact-form'); ?>
        <br />
        user1@example.com<br />
        user1@example.com,user2@example.com
        </div>
        <br />

        <label for="si_contact_email_subject"><?php _e('E-mail Subject Prefix', 'si-contact-form') ?>:</label><input name="si_contact_email_subject" id="si_contact_email_subject" type="text" value="<?php echo esc_attr($si_contact_opt['email_subject']);  ?>" size="55" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_email_subject_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_email_subject_tip">
        <?php _e('This will become a prefix of the subject for the e-mail you receive.', 'si-contact-form'); ?>
        <?php _e('Listed below is an optional list of field tags for fields you can add to the subject.', 'si-contact-form') ?><br />
        <?php _e('Example: to include the name of the form sender, include this tag in the e-mail Subject Prefix:', 'si-contact-form'); ?> [from_name]<br />
		<?php _e('Available field tags:', 'si-contact-form'); ?>
		<span style="margin: 2px 0" dir="ltr"><br />
        <?php
       // show available fields
       foreach ($av_fld_subj_arr as $i)
         echo "[$i]<br />";
        ?>
        </span>
        </div>
        <br />

        <label for="si_contact_email_subject_list"><?php _e('Optional E-mail Subject List', 'si-contact-form'); ?>:</label><br />
        <textarea rows="6" cols="70" name="si_contact_email_subject_list" id="si_contact_email_subject_list"><?php echo esc_attr($si_contact_opt['email_subject_list']);  ?></textarea>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_email_subject_list_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_email_subject_list_tip">
        <?php _e('Optional e-mail subject drop down list. Add as many subject options as you need, the drop down list on the contact form will be made automatically. Separate each subject option by pressing enter. Follow this example:', 'si-contact-form'); ?><br />
        Newsletter Signup<br />
        Question<br />
        Comment
        </div>
        <br />

        <input name="si_contact_double_email" id="si_contact_double_email" type="checkbox" <?php if( $si_contact_opt['double_email'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_double_email"><?php _e('Enable double e-mail entry required on contact form.', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_double_email_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_double_email_tip">
        <?php _e('Requires users to enter email address in two fields to help reduce mistakes.', 'si-contact-form') ?>
        </div>
        <br />

        <input name="si_contact_name_case_enable" id="si_contact_name_case_enable" type="checkbox" <?php if( $si_contact_opt['name_case_enable'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_name_case_enable"><?php _e('Enable upper case alphabet correction.', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_name_case_enable_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_name_case_enable_tip">
        <?php _e('Automatically corrects form input using a function knowing about alphabet case (example: correct caps on McDonald, or correct USING ALL CAPS).', 'si-contact-form'); ?>
        <?php _e('Enable on English language only because it can cause accent character problems if enabled on other languages.', 'si-contact-form'); ?>
        </div>
        <br />

        <input name="si_contact_sender_info_enable" id="si_contact_sender_info_enable" type="checkbox" <?php if( $si_contact_opt['sender_info_enable'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_sender_info_enable"><?php _e('Enable sender information in e-mail footer.', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_sender_info_enable_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_sender_info_enable_tip">
        <?php _e('Includes detailed information in the e-mail about the sender. Such as IP Address, date, time, and which web browser they used.', 'si-contact-form'); ?>
        <?php echo ' '; _e('Install the <a href="http://wordpress.org/extend/plugins/visitor-maps/">Visitor Maps plugin</a> to enable geolocation and then city, state, country will automatically be included.', 'si-contact-form'); ?>
        </div>
        <br />

        <input name="si_contact_domain_protect" id="si_contact_domain_protect" type="checkbox" <?php if( $si_contact_opt['domain_protect'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_domain_protect"><?php _e('Enable Form Post security by requiring domain name match for', 'si-contact-form'); ?>
        <?php
        $uri = parse_url(get_option('home'));
        $blogdomain = preg_replace("/^www\./i",'',$uri['host']);
        echo " $blogdomain ";
        ?><?php _e('(recommended).', 'si-contact-form'); ?>
        </label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_domain_protect_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_domain_protect_tip">
        <?php _e('Prevents automated spam bots posting from off-site forms. If you have multiple domains for your site, you have to disable this.', 'si-contact-form') ?>
        </div>
        <br />

        <input name="si_contact_email_check_dns" id="si_contact_email_check_dns" type="checkbox" <?php if( $si_contact_opt['email_check_dns'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_email_check_dns"><?php _e('Enable checking DNS records for the domain name when checking for a valid e-mail address.', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_email_check_dns_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_email_check_dns_tip">
        <?php _e('Improves email address validation by checking that the domain of the email address actually has a valid DNS record.', 'si-contact-form') ?>
        </div>

</fieldset>

    <p class="submit">
      <input type="submit" name="submit" value="<?php echo esc_attr( __('Update Options', 'si-contact-form')); ?> &raquo;" />
    </p>

<div class="form-tab"><?php echo __('Confirmation E-mail:', 'si-contact-form') .' '. sprintf(__('(form %d)', 'si-contact-form'),$form_id);?></div>
<div class="clear"></div>

<fieldset>

        <input name="si_contact_auto_respond_enable" id="si_contact_auto_respond_enable" type="checkbox" <?php if( $si_contact_opt['auto_respond_enable'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_auto_respond_enable"><?php _e('Enable confirmation e-mail message.', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_auto_respond_enable_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_auto_respond_enable_tip">
        <?php _e('Enable when you want the form to automatically answer with a confirmation e-mail message.', 'si-contact-form'); ?>
        </div>
<br />

      <?php
       if( $si_contact_opt['auto_respond_enable'] == 'true' && ($si_contact_opt['auto_respond_from_name'] == '' || $si_contact_opt['auto_respond_from_email'] == '' || $si_contact_opt['auto_respond_reply_to'] == '' || $si_contact_opt['auto_respond_subject'] == '' || $si_contact_opt['auto_respond_message'] == '') ) {
         echo '<div class="fsc-notice">';
         echo __('Warning: Enabling this setting requires all the confirmation fields below to also be set.', 'si-contact-form');
         echo "</div>\n";
       }
       if( !$autoresp_ok && $si_contact_opt['auto_respond_enable'] == 'true' && $si_contact_opt['auto_respond_from_name'] != '' && $si_contact_opt['auto_respond_from_email'] != '' && $si_contact_opt['auto_respond_reply_to'] != '' && $si_contact_opt['auto_respond_subject'] != '' && $si_contact_opt['auto_respond_message'] != '' ) {
         echo '<div class="fsc-error">';
         echo __('Warning: No email address field is set, you will not be able to reply to emails and the confirmation email will not work.', 'si-contact-form');
         echo "</div>\n";
       }
       if( !$autoresp_ok ) {
         echo '<div id="message" class="updated">';
         echo __('Warning: No email address field is set, you will not be able to reply to emails and the confirmation email will not work.', 'si-contact-form');
         echo "</div>\n";
       }
       ?>
        <label for="si_contact_auto_respond_from_name"><?php _e('Confirmation e-mail "From" name', 'si-contact-form'); ?>:</label><input name="si_contact_auto_respond_from_name" id="si_contact_auto_respond_from_name" type="text" value="<?php echo esc_attr($si_contact_opt['auto_respond_from_name']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_auto_respond_from_name_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_auto_respond_from_name_tip">
        <?php _e('This sets the name in the "from" field when the confirmation email is sent.', 'si-contact-form'); ?>
        </div>
<br />

        <label for="si_contact_auto_respond_from_email"><?php _e('Confirmation e-mail "From" address', 'si-contact-form'); ?>:</label><input name="si_contact_auto_respond_from_email" id="si_contact_auto_respond_from_email" type="text" value="<?php echo esc_attr($si_contact_opt['auto_respond_from_email']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_auto_respond_from_email_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_auto_respond_from_email_tip">
        <?php _e('This sets the "from" email address when the confirmation email is sent. If your email does not send any email, then set this setting to a real email address on the same web domain as your web site. (Same applies to the "Email-From" setting on this page)', 'si-contact-form'); ?>
        </div>
<br />

        <label for="si_contact_auto_respond_reply_to"><?php _e('Confirmation e-mail "Reply To" address', 'si-contact-form'); ?>:</label><input name="si_contact_auto_respond_reply_to" id="si_contact_auto_respond_reply_to" type="text" value="<?php echo esc_attr($si_contact_opt['auto_respond_reply_to']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_auto_respond_reply_to_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_auto_respond_reply_to_tip">
        <?php _e('This sets the "reply to" email address when the confirmation email is sent.', 'si-contact-form'); ?>
        </div>
<br />

        <label for="si_contact_auto_respond_subject"><?php _e('Confirmation e-mail subject', 'si-contact-form'); ?>:</label><input name="si_contact_auto_respond_subject" id="si_contact_auto_respond_subject" type="text" value="<?php echo esc_attr($si_contact_opt['auto_respond_subject']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_auto_respond_subject_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_auto_respond_subject_tip">
        <?php _e('Type your confirmation email subject here, then enable it with the setting above.', 'si-contact-form'); ?>
        <?php _e('Listed below is an optional list of field tags for fields you can add to the subject.', 'si-contact-form') ?><br />
        <?php _e('Example: to include the name of the form sender, include this tag in the confirmation email subject:', 'si-contact-form'); ?> [from_name]<br />
		<?php _e('Available field tags:', 'si-contact-form'); ?>
		<span style="margin: 2px 0" dir="ltr"><br />
        <?php
       // show available fields
       foreach ($av_fld_subj_arr as $i)
         echo "[$i]<br />";
        ?>
        </span>
        </div>
<br />

        <label for="si_contact_auto_respond_message"><?php _e('Confirmation e-mail message', 'si-contact-form'); ?>:</label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_auto_respond_message_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_auto_respond_message_tip">
        <?php _e('Type your confirmation email message here, then enable it with the setting above.', 'si-contact-form'); ?>
        <?php _e('Listed below is an optional list of field tags for fields you can add to the confirmation email message.', 'si-contact-form') ?><br />
        <?php _e('Example: to include the name of the form sender, include this tag in the confirmation email message:', 'si-contact-form'); ?> [from_name]<br />
		<?php _e('Available field tags:', 'si-contact-form'); ?>
		<span style="margin: 2px 0" dir="ltr"><br />
        <?php
       // show available fields
       foreach ($av_fld_arr as $i) {
         if( in_array($i,array('message','full_message','akismet')) )  // exclude these
            continue;
         echo "[$i]<br />";
       }
        ?>
        </span>
        <?php _e('Note: If you add any extra fields, they will show up in this list of available tags.', 'si-contact-form'); ?>
        <?php _e('Note: The message fields are intentionally disabled to help prevent spammers from using this form to relay spam.', 'si-contact-form'); ?>
        <?php _e('Try to limit this feature to just using the name field to personalize the message. Do not try to use it to send a copy of what was posted.', 'si-contact-form'); ?>

        </div><br />
        <textarea rows="3" cols="50" name="si_contact_auto_respond_message" id="si_contact_auto_respond_message"><?php echo $this->ctf_output_string($si_contact_opt['auto_respond_message']); // can have html ?></textarea>
<br />

        <input name="si_contact_auto_respond_html" id="si_contact_auto_respond_html" type="checkbox" <?php if( $si_contact_opt['auto_respond_html'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_auto_respond_html"><?php _e('Enable using HTML in confirmation email message.', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_auto_respond_html_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_auto_respond_html_tip">
        <?php _e('Enable when you want to use HTML in the confirmation email message.', 'si-contact-form'); echo ' ';?>
        <?php _e('Then you can use an HTML message. example:', 'si-contact-form'); ?><br />
&lt;html&gt;&lt;body&gt;<br />
&lt;h1&gt;<?php _e('Hello World!', 'si-contact-form'); ?>&lt;/h1&gt;<br />
&lt;/body&gt;&lt;/html&gt;
        </div>

</fieldset>

    <p class="submit">
      <input type="submit" name="submit" value="<?php echo esc_attr( __('Update Options', 'si-contact-form')); ?> &raquo;" />
    </p>

<div class="form-tab"><?php echo __('Akismet:', 'si-contact-form') .' '. sprintf(__('(form %d)', 'si-contact-form'),$form_id);?></div>
<div class="clear"></div>
<fieldset>

     <strong><?php _e('Akismet Spam Prevention:', 'si-contact-form'); ?></strong>

    <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_akismet_tip');"><?php _e('help', 'si-contact-form'); ?></a>
    <div style="text-align:left; display:none" id="si_contact_akismet_tip">
    <?php _e('Akismet is a WordPress spam prevention plugin. When Akismet is installed and active, this form will be checked with Akismet to help prevent spam.', 'si-contact-form') ?>
    </div>
    <br />

<?php
$akismet_installed = 0;
if( $si_contact_opt['akismet_disable'] == 'false' ) {
 if (function_exists('akismet_verify_key')) {
    if (!isset($_POST['si_contact_akismet_check'])){
       echo '<span style="background-color:#99CC99;">'. __('Akismet is installed.', 'si-contact-form'). '</span>';
       $akismet_installed = 1;
    }
    if (isset($_POST['si_contact_akismet_check'])){;
      $key_status = 'failed';
	  $key = get_option('wordpress_api_key');
		if ( empty( $key ) ) {
			$key_status = 'empty';
		} else {
			$key_status = akismet_verify_key( $key );
		}
		if ( $key_status == 'valid' ) {
		    $akismet_installed = 1;
            ?><div id="message" class="updated"><strong><?php echo __('Akismet is enabled and the key is valid. This form will be checked with Akismet to help prevent spam', 'si-contact-form'); ?></strong></div><?php
            echo '<div class="fsc-notice">' . __('Akismet is installed and the key is valid. This form will be checked with Akismet to help prevent spam.', 'si-contact-form'). '</strong></div>';
		} else if ( $key_status == 'invalid' ) {
			?><div id="message" class="error"><strong><?php echo __('Akismet plugin is enabled but key needs to be activated', 'si-contact-form'); ?></strong></div><?php
             echo '<div class="fsc-error">'. __('Akismet plugin is installed but key needs to be activated.', 'si-contact-form'). '</div>';
		} else if ( !empty($key) && $key_status == 'failed' ) {
			?><div id="message" class="error"><strong><?php echo __('Akismet plugin is enabled but key failed to verify', 'si-contact-form'); ?></strong></div><?php
             echo '<div class="fsc-error">'.__('Akismet plugin is installed but key failed to verify.', 'si-contact-form'). '</div>';
		} else {
            ?><div id="message" class="error"><strong><?php echo __('Akismet plugin is installed but key has not been entered.', 'si-contact-form'); ?></strong></div><?php
             echo '<div class="fsc-error">'.__('Akismet plugin is installed but key has not been entered.', 'si-contact-form'). '</div>';
        }
    }
?>
<br />
  <input name="si_contact_akismet_check" id="si_contact_akismet_check" type="checkbox" value="1" />
  <label for="si_contact_akismet_check"><?php _e('Check this and click "Update Options" to determine if Akismet key is active.', 'si-contact-form'); ?></label>
<br />
<?php echo '<a href="'.admin_url(  "plugins.php?page=akismet-key-config" ).'">' . __('Configure Akismet', 'si-contact-form').'</a>'; ?>
<?php
  }else{
     echo '<div class="fsc-notice">'.__('Akismet plugin is not installed or is deactivated.', 'si-contact-form'). '</div>';
  }
} else {
    echo '<div class="fsc-notice">'.__('Akismet is turned off for this form.', 'si-contact-form'). '</div>';
}
 if( $si_contact_opt['akismet_disable'] == 'false' ) {
?>
<br />
  <label for="si_contact_akismet_send_anyway"><?php _e('What should happen if Akismet determines the message is spam?', 'si-contact-form'); ?></label>
   <select id="si_contact_akismet_send_anyway" name="si_contact_akismet_send_anyway">
<?php
$akismet_send_anyway_array = array(
'false' => __('Block spam messages', 'si-contact-form'),
'true' => __('Tag as spam and send anyway', 'si-contact-form'),
);
$selected = '';
foreach ($akismet_send_anyway_array as $k => $v) {
 if ($si_contact_opt['akismet_send_anyway'] == "$k")  $selected = ' selected="selected"';
 echo '<option value="'.esc_attr($k).'"'.$selected.'>'.esc_html($v).'</option>'."\n";
 $selected = '';
}
?>
</select>
<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_akismet_send_anyway_tip');"><?php _e('help', 'si-contact-form'); ?></a>
    <div style="text-align:left; display:none" id="si_contact_akismet_send_anyway_tip">
    <?php _e('If you select "block spam messages". If Akismet determines the message is spam: An error will display "Invalid Input - Spam?" and the form will not send.', 'si-contact-form'); ?>
    <?php echo ' '; _e('If you select "tag as spam and send anyway". If Akismet determines the message is spam: The message will send and the subject wil begin with "Akismet: Spam". This way you can have Akismet on and be sure not to miss a message.', 'si-contact-form'); ?>
    </div>
<?php
} else {
    echo '<input name="si_contact_akismet_send_anyway" type="hidden" value="'. esc_attr($si_contact_opt['akismet_send_anyway']).'" />';
}
?>
<br />
  <input name="si_contact_akismet_disable" id="si_contact_akismet_disable" type="checkbox" <?php if( $si_contact_opt['akismet_disable'] == 'true' ) echo 'checked="checked"'; ?> />
  <label for="si_contact_akismet_disable"><?php _e('Turn off Akismet for this form.', 'si-contact-form'); ?></label>

</fieldset>

    <p class="submit">
      <input type="submit" name="submit" value="<?php echo esc_attr( __('Update Options', 'si-contact-form')); ?> &raquo;" />
    </p>

<div class="form-tab"><?php echo __('CAPTCHA:', 'si-contact-form') .' '. sprintf(__('(form %d)', 'si-contact-form'),$form_id);?></div>
<div class="clear"></div>
<fieldset>

        <input name="si_contact_captcha_enable" id="si_contact_captcha_enable" type="checkbox" <?php if ( $si_contact_opt['captcha_enable'] == 'true' ) echo ' checked="checked" '; ?> />
        <label for="si_contact_captcha_enable"><?php _e('Enable CAPTCHA (recommended).', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_captcha_enable_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_captcha_enable_tip">
        <?php _e('Prevents automated spam bots by requiring that the user pass a CAPTCHA test before posting. You can disable CAPTCHA if you prefer, because the form also uses Akismet to prevent spam when Akismet plugin is installed with the key activated.', 'si-contact-form') ?>
        </div>
        <br />

        <label for="si_contact_captcha_difficulty"><?php _e('CAPTCHA difficulty level:', 'si-contact-form'); ?></label>
      <select id="si_contact_captcha_difficulty" name="si_contact_captcha_difficulty">
<?php
$captcha_difficulty_array = array(
'low' => __('Low', 'si-contact-form'),
'medium' => __('Medium', 'si-contact-form'),
'high' => __('High', 'si-contact-form'),
);
$selected = '';
foreach ($captcha_difficulty_array as $k => $v) {
 if ($si_contact_opt['captcha_difficulty'] == "$k")  $selected = ' selected="selected"';
 echo '<option value="'.esc_attr($k).'"'.$selected.'>'.esc_html($v).'</option>'."\n";
 $selected = '';
}
?>
</select>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_captcha_difficulty_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_captcha_difficulty_tip">
        <?php _e('Changes level of distortion of the CAPTCHA image text.', 'si-contact-form') ?>
        </div>
        <br />

        <input name="si_contact_captcha_small" id="si_contact_captcha_small" type="checkbox" <?php if ( $si_contact_opt['captcha_small'] == 'true' ) echo ' checked="checked" '; ?> />
        <label for="si_contact_captcha_small"><?php _e('Enable smaller size CAPTCHA image.', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_captcha_small_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_captcha_small_tip">
        <?php _e('Makes the CAPTCHA image smaller.', 'si-contact-form') ?>
        </div>
        <br />

        <input name="si_contact_captcha_perm" id="si_contact_captcha_perm" type="checkbox" <?php if( $si_contact_opt['captcha_perm'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_captcha_perm"><?php _e('Hide CAPTCHA for', 'si-contact-form'); ?>
        <strong><?php _e('registered', 'si-contact-form'); ?></strong> <?php __('users who can', 'si-contact-form'); ?>:</label>
        <?php $this->si_contact_captcha_perm_dropdown('si_contact_captcha_perm_level', $si_contact_opt['captcha_perm_level']);  ?>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_captcha_perm_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_captcha_perm_tip">
        <?php _e('Registered users will not have to use the CAPTCHA feature. Do not enable this setting if you do not trust your registered users as some could be spammers.', 'si-contact-form') ?>
        </div>
        <br />

        <input name="si_contact_captcha_no_trans" id="si_contact_captcha_no_trans" type="checkbox" <?php if ( $si_contact_opt['captcha_no_trans'] == 'true' ) echo ' checked="checked" '; ?> />
        <label for="si_contact_captcha_no_trans"><?php _e('Disable CAPTCHA transparent text (only if captcha text is missing on the image, try this fix).', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_captcha_no_trans_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_captcha_no_trans_tip">
        <?php _e('Sometimes fixes missing text on the CAPTCHA image. If this does not fix missing text, your PHP server is not compatible with the CAPTCHA functions. You can disable CAPTCHA or have your web server fixed.', 'si-contact-form') ?>
        </div>
        <br />

        <input name="si_contact_honeypot_enable" id="si_contact_honeypot_enable" type="checkbox" <?php if ( $si_contact_opt['honeypot_enable'] == 'true' ) echo ' checked="checked" '; ?> />
        <label for="si_contact_honeypot_enable"><?php _e('Enable honeypot spambot trap.', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_honeypot_enable_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_honeypot_enable_tip">
        <?php _e('Enables empty field and time based honyepot traps for spam bots. For best results, do not enable unless you have a spam problem.', 'si-contact-form') ?>
        </div>


</fieldset>

    <p class="submit">
      <input type="submit" name="submit" value="<?php echo esc_attr( __('Update Options', 'si-contact-form')); ?> &raquo;" />
    </p>

<div class="form-tab"><?php echo __('Form:', 'si-contact-form') .' '. sprintf(__('(form %d)', 'si-contact-form'),$form_id);?></div>
<div class="clear"></div>
<fieldset>

<strong><?php echo __('Standard Fields:', 'si-contact-form'); ?></strong><br />
       <a style="cursor:pointer;" title="<?php echo __('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('stand_fields_tip');">
       <?php echo __('help', 'si-contact-form'); ?></a>
       <div style="text-align:left; display:none" id="stand_fields_tip">
       <?php echo __('The standard fields can be set to be required or not, or even be disabled.', 'si-contact-form'); ?>
      </div>
 <br />

       <input name="si_contact_auto_fill_enable" id="si_contact_auto_fill_enable" type="checkbox" <?php if( $si_contact_opt['auto_fill_enable'] == 'true' ) echo 'checked="checked"'; ?> />
       <label for="si_contact_auto_fill_enable"><?php _e('Enable auto form fill', 'si-contact-form'); ?>.</label>
       <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_auto_fill_enable_tip');"><?php _e('help', 'si-contact-form'); ?></a>
       <div style="text-align:left; display:none" id="si_contact_auto_fill_enable_tip">
       <?php _e('Auto form fill email address and name (username) on the contact form for logged in users who are not administrators.', 'si-contact-form'); ?>
       </div>
       <br />

      <label for="si_contact_name_type"><?php _e('Name field:', 'si-contact-form'); ?></label>
      <select id="si_contact_name_type" name="si_contact_name_type">
<?php
$name_type_array = array(
'not_available' => __('Not Available', 'si-contact-form'),
'not_required' => __('Not Required', 'si-contact-form'),
'required' => __('Required', 'si-contact-form'),
);
$selected = '';
foreach ($name_type_array as $k => $v) {
 if ($si_contact_opt['name_type'] == "$k")  $selected = ' selected="selected"';
 echo '<option value="'.esc_attr($k).'"'.$selected.'>'.esc_html($v).'</option>'."\n";
 $selected = '';
}
?>
</select>

      <label for="si_contact_name_format"><?php _e('Name field format:', 'si-contact-form'); ?></label>
      <select id="si_contact_name_format" name="si_contact_name_format">
<?php
$name_format_array = array(
'name' => __('Name', 'si-contact-form'),
'first_last' => __('First Name, Last Name', 'si-contact-form'),
'first_middle_i_last' => __('First Name, Middle Initial, Last Name', 'si-contact-form'),
'first_middle_last' => __('First Name, Middle Name, Last Name', 'si-contact-form'),
);
$selected = '';
foreach ($name_format_array as $k => $v) {
 if ($si_contact_opt['name_format'] == "$k")  $selected = ' selected="selected"';
 echo '<option value="'.esc_attr($k).'"'.$selected.'>'.esc_html($v).'</option>'."\n";
 $selected = '';
}
?>
</select>
       <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_name_format_tip');"><?php _e('help', 'si-contact-form'); ?></a>
       <div style="text-align:left; display:none" id="si_contact_name_format_tip">
       <?php _e('Select how the name field is formatted on the form.', 'si-contact-form'); ?>
       </div>
<br />

      <label for="si_contact_email_type"><?php _e('E-mail field:', 'si-contact-form'); ?></label>
      <select id="si_contact_email_type" name="si_contact_email_type">
<?php
$selected = '';
foreach ($name_type_array as $k => $v) {
 if ($si_contact_opt['email_type'] == "$k")  $selected = ' selected="selected"';
 echo '<option value="'.esc_attr($k).'"'.$selected.'>'.esc_html($v).'</option>'."\n";
 $selected = '';
}
?>
</select>
<br />

      <label for="si_contact_subject_type"><?php _e('Subject field:', 'si-contact-form'); ?></label>
      <select id="si_contact_subject_type" name="si_contact_subject_type">
<?php
$selected = '';
foreach ($name_type_array as $k => $v) {
 if ($si_contact_opt['subject_type'] == "$k")  $selected = ' selected="selected"';
 echo '<option value="'.esc_attr($k).'"'.$selected.'>'.esc_html($v).'</option>'."\n";
 $selected = '';
}
?>
</select>
<br />


      <label for="si_contact_message_type"><?php _e('Message field:', 'si-contact-form'); ?></label>
      <select id="si_contact_message_type" name="si_contact_message_type">
<?php
$selected = '';
foreach ($name_type_array as $k => $v) {
 if ($si_contact_opt['message_type'] == "$k")  $selected = ' selected="selected"';
 echo '<option value="'.esc_attr($k).'"'.$selected.'>'.esc_html($v).'</option>'."\n";
 $selected = '';
}
?>
</select>

      <input name="si_contact_preserve_space_enable" id="si_contact_preserve_space_enable" type="checkbox" <?php if( $si_contact_opt['preserve_space_enable'] == 'true' ) echo 'checked="checked"'; ?> />
      <label for="si_contact_preserve_space_enable"><?php _e('Preserve Message field spaces.', 'si-contact-form'); ?></label>
      <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_preserve_space_enable_tip');"><?php _e('help', 'si-contact-form'); ?></a>
      <div style="text-align:left; display:none" id="si_contact_preserve_space_enable_tip">
      <?php _e('Normally the Message field will have all extra white space removed. Enabling this setting will allow all the Message field white space to be preserved.', 'si-contact-form'); ?>
      </div>

<br />
<br />

<strong><?php _e('Extra Fields:', 'si-contact-form'); ?></strong>
       <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_extra_fields_tip');"><h3><?php _e('Click here to see instructions for extra fields.', 'si-contact-form'); ?></a></h3>
       <div style="text-align:left; display:none" id="si_contact_extra_fields_tip">
       <br />
<strong><?php _e('Instructions for how to use Extra Fields:', 'si-contact-form'); ?></strong>
       <blockquote>
      <?php _e('You can use extra contact form fields for phone number, company name, etc. To enable an extra field, just enter a label. Then check if you want the field to be required or not. To disable, empty the label.', 'si-contact-form'); ?>
<br /><strong><?php _e('Text and Textarea fields:', 'si-contact-form'); ?></strong><br />
       <?php _e('The text field is for single line text entry. The textarea field is for multiple line text entry.', 'si-contact-form'); ?>
<br /><strong><?php _e('Checkbox, Checkbox-multiple, Radio, Select, and Select-multiple extra fields:', 'si-contact-form'); ?></strong><br />
       <?php _e('To enable a checkbox field with a single option, just enter a label. Then check if you want the field to be required or not.', 'si-contact-form'); ?>
       <?php _e('To enable fields with multiple options like checkbox-multiple, radio, select, or select-multiple field types; first enter the label and a comma, then include the options separating each one with a semicolon like this example: Color:,Red;Green;Blue.', 'si-contact-form'); ?>
       <?php _e('If you need to use a comma besides the one needed to separate the label, escape it with a back slash, like this: \,', 'si-contact-form'); ?>
       <?php _e('You can also use fields that allow multiple options to be checked at once, such as checkbox-multiple and select-multiple like in this example: Pizza Toppings:,olives;mushrooms;cheese;ham;tomatoes. Now multiple options can be checked for the "Pizza Toppings" label.', 'si-contact-form'); ?>
       <?php _e('By default radio and checkboxes are displayed vertical. Here is how to make them display horizontal: add the tag {inline} before the label, like this: {inline}Pizza Toppings:,olives;mushrooms;cheese;ham;tomatoes.', 'si-contact-form'); ?>
<br /><strong><?php _e('Attachment:', 'si-contact-form'); ?></strong><br />
       <?php _e('The attachment is used to allow users to attach a file upload from the form. You can add multiple attachments. The attachment is sent with your email. Attachments are deleted from the server after the email is sent.', 'si-contact-form'); ?>
<br /><strong><?php _e('Date field:', 'si-contact-form'); ?></strong><br />
       <?php _e('The date is used to allow a date field with a calendar pop-up. The date field ensures that a date entry is in a standard format every time.', 'si-contact-form'); ?>
<br /><strong><?php _e('Time field:', 'si-contact-form'); ?></strong><br />
       <?php _e('The time is used to allow a time entry field with hours, minutes, and AM/PM. The time field ensures that a time entry is in a standard format.', 'si-contact-form'); ?>
<br /><strong><?php _e('Email field:', 'si-contact-form'); ?></strong><br />
       <?php _e('The email field is used to allow an email address entry field. The email field ensures that a email entry is in a valid email format.', 'si-contact-form'); ?>
<br /><strong><?php _e('URL field:', 'si-contact-form'); ?></strong><br />
       <?php _e('The URL field is used to allow a URL entry field. The URL field ensures that a URL entry is in a valid URL format.', 'si-contact-form'); ?>
<br /><strong><?php _e('Hidden field:', 'si-contact-form'); ?></strong><br />
       <?php _e('The hidden field is used if you need to pass a hidden value from the form to the email message. The hidden field does not show on the page. You must set the label and the value. First enter the label, a comma, then the value. Like in this example: Language,English', 'si-contact-form'); ?>
<br /><strong><?php _e('Password field:', 'si-contact-form'); ?></strong><br />
       <?php _e('The password field is used for a text field where what is entered shows up as dots on the screen. The email you receive will have the entered value fully visible.', 'si-contact-form'); ?>
<br /><strong><?php _e('Fieldset:', 'si-contact-form'); ?></strong><br />
       <?php _e('The fieldset(box-open) is used to draw a box around related form elements. The fieldset label is used for a (legend) title of the group.', 'si-contact-form'); ?>
       <br />
       <?php _e('The fieldset(box-close) is used to close a box around related form elements. A label is not required for this type. If you do not close a fieldset box, it will close automatically when you add another fieldset box.', 'si-contact-form'); ?>
<br /><br />
<strong><?php echo __('Optional modifiers:', 'si-contact-form'); ?></strong><br />

<br /><strong><?php echo __('Default text:', 'si-contact-form'); ?></strong><br />
       <?php echo __('Use to pre-fill a value for a text field. Can be used for text or textarea field types.', 'si-contact-form'); ?>
<br /><strong><?php echo __('Default option:', 'si-contact-form'); ?></strong><br />
       <?php echo __('To make "green" the default selection for a red, green, blue select field: set "Default option" 2. Can be used for checkbox, radio, or select field types.', 'si-contact-form'); ?>
<br /><strong><?php echo __('Max length:', 'si-contact-form'); ?></strong><br />
       <?php echo __('Use to limit the number of allowed characters for a text field. The limit will be checked when the form is posted. Can be used for text, textarea, and password field types.', 'si-contact-form'); ?>
<br /><strong><?php echo __('Required field:', 'si-contact-form'); ?></strong><br />
       <?php echo __('Check this setting if you want the field to be required when the form is posted. Can be used for any extra field type.', 'si-contact-form'); ?>
<br /><strong><?php echo __('Attributes:', 'si-contact-form'); ?></strong><br />
       <?php echo __('Use to insert input field attributes. Example: To make a text field readonly, set to: readonly="readonly" Can be used for any extra field type.', 'si-contact-form'); ?>
<br /><strong><?php echo __('Validation regex:', 'si-contact-form'); ?></strong><br />
       <?php echo __('Use to validate if form input is in a specific format. Example: If you want numbers in a text field type but do not allow text, use this regex: /^\d+$/ Can be used for text, textarea, date and password field types.', 'si-contact-form'); ?>
<br /><strong><?php echo __('Regex fail message:', 'si-contact-form'); ?></strong><br />
       <?php echo __('Use to customize a message to alert the user when the form fails to validate a regex after post. Example: Please only enter numbers. For use with validation regex only.', 'si-contact-form'); ?>
<br /><strong><?php echo __('Label CSS/Input CSS :', 'si-contact-form'); ?></strong><br />
       <?php echo __('Use to style individual form fields with CSS. CSS class names or style code are both acceptable. Note: If you do not need to style fields individually, you should use the CSS DIV settings instead.', 'si-contact-form'); ?>
<br /><strong><?php echo __('HTML before/after field:', 'si-contact-form'); ?></strong><br />
       <?php echo __('Use the HTML before/after field to print some HTML before or after an extra field on the form. This is for the form display only, not e-mail. HTML is allowed.', 'si-contact-form'); ?>


       </blockquote>
</div>



      <?php
$field_type_array = array(
'text' => __('text', 'si-contact-form'),
'textarea' => __('textarea', 'si-contact-form'),
'checkbox' => __('checkbox', 'si-contact-form'),
'checkbox-multiple' => __('checkbox-multiple', 'si-contact-form'),
'radio' => __('radio', 'si-contact-form'),
'select' => __('select', 'si-contact-form'),
'select-multiple' => __('select-multiple', 'si-contact-form'),
'attachment' => __('attachment', 'si-contact-form'),
'date' => __('date', 'si-contact-form'),
'time' => __('time', 'si-contact-form'),
'email' => __('email', 'si-contact-form'),
'url' => __('url', 'si-contact-form'),
'hidden' => __('hidden', 'si-contact-form'),
'password' => __('password', 'si-contact-form'),
'fieldset' => __('fieldset(box-open)', 'si-contact-form'),
'fieldset-close' => __('fieldset(box-close)', 'si-contact-form'),
);
      // optional extra fields
      for ($i = 1; $i <= $si_contact_opt['max_fields']; $i++) {
      ?>
      <fieldset style="padding:4px; margin:4px;">
        <legend style="padding:4px;"><b><?php echo sprintf( __('Extra field %d', 'si-contact-form'),$i);?></b></legend>

       <label for="<?php echo 'si_contact_ex_field'.$i.'_label' ?>"><?php echo __('Label:', 'si-contact-form'); ?></label>
       <input name="<?php echo 'si_contact_ex_field'.$i.'_label' ?>" id="<?php echo 'si_contact_ex_field'.$i.'_label' ?>" type="text" value="<?php echo esc_attr($si_contact_opt['ex_field'.$i.'_label']);  ?>" size="95" />

       <label for="<?php echo 'si_contact_ex_field'.$i.'_type' ?>"><?php echo __('Field type:', 'si-contact-form'); ?></label>
       <select id="<?php echo 'si_contact_ex_field'.$i.'_type' ?>" name="<?php echo 'si_contact_ex_field'.$i.'_type' ?>">
<?php
$selected = '';
foreach ($field_type_array as $k => $v) {
 if ($si_contact_opt['ex_field'.$i.'_type'] == "$k")  $selected = ' selected="selected"';
 echo '<option value="'.esc_attr($k).'"'.$selected.'>'.esc_html($v).'</option>'."\n";
 $selected = '';
}
?>
</select><br />

       <?php echo __('Optional modifiers', 'si-contact-form'); ?>:
       <label for="<?php echo 'si_contact_ex_field'.$i.'_default_text' ?>"><?php echo __('Default text', 'si-contact-form'); ?>:</label>
       <input name="<?php echo 'si_contact_ex_field'.$i.'_default_text' ?>" id="<?php echo 'si_contact_ex_field'.$i.'_default_text' ?>" type="text" value="<?php echo esc_attr($si_contact_opt['ex_field'.$i.'_default_text']);  ?>" size="45" />

       <label for="<?php echo 'si_contact_ex_field'.$i.'_default' ?>"><?php printf(__('Default option:', 'si-contact-form'),$i); ?></label>
       <input name="<?php echo 'si_contact_ex_field'.$i.'_default' ?>" id="<?php echo 'si_contact_ex_field'.$i.'_default' ?>" type="text" value="<?php echo absint(isset($si_contact_opt['ex_field'.$i.'_default']) ? $si_contact_opt['ex_field'.$i.'_default'] : 0);  ?>" size="2" />

       <label for="<?php echo 'si_contact_ex_field'.$i.'_max_len' ?>"><?php echo __('Max length', 'si-contact-form'); ?>:</label>
       <input name="<?php echo 'si_contact_ex_field'.$i.'_max_len' ?>" id="<?php echo 'si_contact_ex_field'.$i.'_max_len' ?>" type="text" value="<?php echo absint($si_contact_opt['ex_field'.$i.'_max_len']);  ?>" size="2" />

       <input name="<?php echo 'si_contact_ex_field'.$i.'_req' ?>" id="<?php echo 'si_contact_ex_field'.$i.'_req' ?>" type="checkbox" <?php if( $si_contact_opt['ex_field'.$i.'_req'] == 'true' ) echo 'checked="checked"'; ?> />
       <label for="<?php echo 'si_contact_ex_field'.$i.'_req' ?>"><?php _e('Required field', 'si-contact-form'); ?></label><br />

       <label for="<?php echo 'si_contact_ex_field'.$i.'_attributes' ?>"><?php echo __('Attributes', 'si-contact-form'); ?>:</label>
       <input name="<?php echo 'si_contact_ex_field'.$i.'_attributes' ?>" id="<?php echo 'si_contact_ex_field'.$i.'_attributes' ?>" type="text" value="<?php echo esc_attr($si_contact_opt['ex_field'.$i.'_attributes']);  ?>" size="20" />

       <label for="<?php echo 'si_contact_ex_field'.$i.'_regex' ?>"><?php echo __('Validation regex', 'si-contact-form'); ?>:</label>
       <input name="<?php echo 'si_contact_ex_field'.$i.'_regex' ?>" id="<?php echo 'si_contact_ex_field'.$i.'_regex' ?>" type="text" value="<?php echo esc_attr($si_contact_opt['ex_field'.$i.'_regex']);  ?>" size="20" />

       <label for="<?php echo 'si_contact_ex_field'.$i.'_regex_error' ?>"><?php echo __('Regex fail message', 'si-contact-form'); ?>:</label>
       <input name="<?php echo 'si_contact_ex_field'.$i.'_regex_error' ?>" id="<?php echo 'si_contact_ex_field'.$i.'_regex_error' ?>" type="text" value="<?php echo esc_attr($si_contact_opt['ex_field'.$i.'_regex_error']);  ?>" size="35" /><br />

       <label for="<?php echo 'si_contact_ex_field'.$i.'_label_css' ?>"><?php echo __('Label CSS', 'si-contact-form'); ?>:</label>
       <input name="<?php echo 'si_contact_ex_field'.$i.'_label_css' ?>" id="<?php echo 'si_contact_ex_field'.$i.'_label_css' ?>" type="text" value="<?php echo esc_attr($si_contact_opt['ex_field'.$i.'_label_css']);  ?>" size="53" />

       <label for="<?php echo 'si_contact_ex_field'.$i.'_input_css' ?>"><?php echo __('Input CSS', 'si-contact-form'); ?>:</label>
       <input name="<?php echo 'si_contact_ex_field'.$i.'_input_css' ?>" id="<?php echo 'si_contact_ex_field'.$i.'_input_css' ?>" type="text" value="<?php echo esc_attr($si_contact_opt['ex_field'.$i.'_input_css']);  ?>" size="53" /><br />

       <label for="<?php echo 'si_contact_ex_field'.$i.'_notes' ?>"><?php printf(__('HTML before form field %d:', 'si-contact-form'),$i); ?></label>
       <input name="<?php echo 'si_contact_ex_field'.$i.'_notes' ?>" id="<?php echo 'si_contact_ex_field'.$i.'_notes' ?>" type="text" value="<?php echo esc_attr($si_contact_opt['ex_field'.$i.'_notes']);  ?>" size="100" /><br />

       <label for="<?php echo 'si_contact_ex_field'.$i.'_notes_after' ?>"><?php printf(__('HTML after form field %d:', 'si-contact-form'),$i); ?></label>
       <input name="<?php echo 'si_contact_ex_field'.$i.'_notes_after' ?>" id="<?php echo 'si_contact_ex_field'.$i.'_notes_after' ?>" type="text" value="<?php echo esc_attr($si_contact_opt['ex_field'.$i.'_notes_after']);  ?>" size="100" />

</fieldset>
      <?php
      } // end foreach
      ?>

 <br />

 <label for="si_contact_max_fields"><?php _e('Number of available extra fields', 'si-contact-form'); ?>:</label>
 <input name="si_contact_max_fields" id="si_contact_max_fields" type="text" onclick="return alert('<?php _e('Caution: Increase the number of extra fields as needed, but make sure you do not change to a lower number than what is being used on this form.', 'si-contact-form'); ?>')" value="<?php echo absint($si_contact_opt['max_fields']);  ?>" size="3" />
 <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_max_fields_tip');"><?php _e('help', 'si-contact-form'); ?></a>
 <div style="text-align:left; display:none" id="si_contact_max_fields_tip">
   <?php _e('Caution: Increase the number of extra fields as needed, but make sure you do not change to a lower number than what is being used on this form.', 'si-contact-form'); ?>
 </div>

<br />
      <input name="si_contact_ex_fields_after_msg" id="si_contact_ex_fields_after_msg" type="checkbox" <?php if( $si_contact_opt['ex_fields_after_msg'] == 'true' ) echo 'checked="checked"'; ?> />
      <label for="si_contact_ex_fields_after_msg"><?php _e('Move extra fields to after the Message field.', 'si-contact-form'); ?></label>
      <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_ex_fields_after_msg_tip');"><?php _e('help', 'si-contact-form'); ?></a>
      <div style="text-align:left; display:none" id="si_contact_ex_fields_after_msg_tip">
      <?php _e('Normally the extra fields are inserted into the form between the e-mail address and the subject fields. Enabling this setting will move the extra fields to after the Message field.', 'si-contact-form'); ?>
      </div>
<br />

      <label for="si_contact_date_format"><?php _e('Date field - Date format:', 'si-contact-form'); ?></label>
      <select id="si_contact_date_format" name="si_contact_date_format">
<?php
$selected = '';
$cal_date_array = array(
'mm/dd/yyyy' => __('mm/dd/yyyy', 'si-contact-form'),
'dd/mm/yyyy' => __('dd/mm/yyyy', 'si-contact-form'),
'mm-dd-yyyy' => __('mm-dd-yyyy', 'si-contact-form'),
'dd-mm-yyyy' => __('dd-mm-yyyy', 'si-contact-form'),
'mm.dd.yyyy' => __('mm.dd.yyyy', 'si-contact-form'),
'dd.mm.yyyy' => __('dd.mm.yyyy', 'si-contact-form'),
'yyyy/mm/dd' => __('yyyy/mm/dd', 'si-contact-form'),
'yyyy-mm-dd' => __('yyyy-mm-dd', 'si-contact-form'),
'yyyy.mm.dd' => __('yyyy.mm.dd', 'si-contact-form'),
);
foreach ($cal_date_array as $k => $v) {
 if ($si_contact_opt['date_format'] == "$k")  $selected = ' selected="selected"';
 echo '<option value="'.esc_attr($k).'"'.$selected.'>'.esc_html($v).'</option>'."\n";
 $selected = '';
}
?>
</select>
       <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_date_format_tip');"><?php _e('help', 'si-contact-form'); ?></a>
       <div style="text-align:left; display:none" id="si_contact_date_format_tip">
       <?php _e('Use to set the date format for the date field.', 'si-contact-form'); ?>
       </div>
<br />

       <label for="si_contact_cal_start_day"><?php _e('Date field - Calendar Start Day of the Week', 'si-contact-form'); ?>:</label><input name="si_contact_cal_start_day" id="si_contact_cal_start_day" type="text" value="<?php echo absint($si_contact_opt['cal_start_day']);  ?>" size="3" />
       <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_cal_start_day_tip');"><?php _e('help', 'si-contact-form'); ?></a>
       <div style="text-align:left; display:none" id="si_contact_cal_start_day_tip">
       <?php _e('Use to set the day the week the date field calendar will start on: 0(Sun) to 6(Sat).', 'si-contact-form'); ?>
       </div>
<br />

      <label for="si_contact_time_format"><?php _e('Time field - Time format:', 'si-contact-form'); ?></label>
      <select id="si_contact_time_format" name="si_contact_time_format">
<?php
$selected = '';
$time_format_array = array(
'12' => __('12 Hour', 'si-contact-form'),
'24' => __('24 Hour', 'si-contact-form'),
);
foreach ($time_format_array as $k => $v) {
 if ($si_contact_opt['time_format'] == "$k")  $selected = ' selected="selected"';
 echo '<option value="'.esc_attr($k).'"'.$selected.'>'.esc_html($v).'</option>'."\n";
 $selected = '';
}
?>
</select>
       <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_time_format_tip');"><?php _e('help', 'si-contact-form'); ?></a>
       <div style="text-align:left; display:none" id="si_contact_time_format_tip">
       <?php _e('Use to set the time format for the time field.', 'si-contact-form'); ?>
       </div>
<br />


        <label for="si_contact_attach_types"><?php _e('Attached files acceptable types', 'si-contact-form'); ?>:</label><input name="si_contact_attach_types" id="si_contact_attach_types" type="text" value="<?php echo esc_attr($si_contact_opt['attach_types']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_attach_types_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_attach_types_tip">
        <?php _e('Set the acceptable file types for the file attachment feature. Any file type not on this list will be rejected.', 'si-contact-form'); ?>
        <?php _e('Separate each file type with a comma character. example:', 'si-contact-form'); ?>
        doc,pdf,txt,gif,jpg,jpeg,png
        </div>
<br />

        <label for="si_contact_attach_size"><?php _e('Attached files maximum size allowed', 'si-contact-form'); ?>:</label><input name="si_contact_attach_size" id="si_contact_attach_size" type="text" value="<?php echo esc_attr($si_contact_opt['attach_size']);  ?>" size="30" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_attach_size_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_attach_size_tip">
        <?php _e('Set the acceptable maximum file size for the file attachment feature.', 'si-contact-form'); ?><br />
        <?php _e('example: 1mb equals one Megabyte, 1kb equals one Kilobyte', 'si-contact-form');
        $max_upload = (int)(ini_get('upload_max_filesize'));
        $max_post = (int)(ini_get('post_max_size'));
        $memory_limit = (int)(ini_get('memory_limit'));
        $upload_mb = min($max_upload, $max_post, $memory_limit);
        ?><br />
        <?php _e('Note: Maximum size is limited to available server resources and various PHP settings. Very few servers will accept more than 2mb. Sizes under 1mb will usually have best results. examples:', 'si-contact-form'); ?>
        500kb, 800kb, 1mb, 1.5mb, 2mb
        <?php _e('Note: If you set the value higher than your server can handle, users will have problems uploading big files. The form can time out and may not even show an error.', 'si-contact-form'); ?>
        <b><?php _e('Your server will not allow uploading files larger than than:', 'si-contact-form');  echo " $upload_mb"; ?>mb</b>
        </div>
<br />

        <input name="si_contact_textarea_html_allow" id="si_contact_textarea_html_allow" type="checkbox" <?php if( $si_contact_opt['textarea_html_allow'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_textarea_html_allow"><?php _e('Enable users to send HTML code in the textarea extra field types.', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_textarea_html_allow_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_textarea_html_allow_tip">
        <?php _e('This setting is disabled by default for better security. Enable only if you want users to be able to send HTML code in the textarea extra field types. HTML code allowed will be filtered and limited to a few safe tags only.', 'si-contact-form'); ?>
        </div>
<br />

        <input name="si_contact_enable_reset" id="si_contact_enable_reset" type="checkbox" <?php if( $si_contact_opt['enable_reset'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_enable_reset"><?php _e('Enable a "Reset" button on the form.', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_enable_reset_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_enable_reset_tip">
        <?php _e('When a visitor clicks a reset button, the form entries are reset to the default values.', 'si-contact-form'); ?>
        </div>
<br />

        <input name="si_contact_enable_areyousure" id="si_contact_enable_reset" type="checkbox" <?php if( $si_contact_opt['enable_areyousure'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_enable_areyousure"><?php _e('Enable an "Are you sure?" popup for the submit button.', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_enable_areyousure_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_enable_areyousure_tip">
        <?php _e('When a visitor clicks the form submit button, a popup message will ask "Are you sure?". This message can be changed in the "change field labels" settings below.', 'si-contact-form'); ?>
        </div>
<br />

        <input name="si_contact_enable_credit_link" id="si_contact_enable_credit_link" type="checkbox" <?php if ( $si_contact_opt['enable_credit_link'] == 'true' ) echo ' checked="checked" '; ?> />
        <label for="si_contact_enable_credit_link"><?php _e('Enable plugin credit link:', 'si-contact-form') ?></label> <?php echo __('Powered by', 'si-contact-form'). ' <a href="http://wordpress.org/extend/plugins/si-contact-form/" target="_new">'.__('Fast Secure Contact Form', 'si-contact-form'); ?></a>

</fieldset>
<a id="vCitaSectionAnchor" data-user-changed="<?php echo (isset($vcita_user_changed) && $vcita_user_changed ? "true" : "false");?>" name="vCitaSettings"></a>
    <p class="submit">
      <input type="submit" name="submit" value="<?php echo esc_attr( __('Update Options', 'si-contact-form')); ?> &raquo;" />
    </p>

<?php 

	/* --- vCita Admin Display - Start --- */

 if ($si_contact_opt['vcita_enabled'] == 'true') {  // Mike challis added  02/01/2013
     // prevent setting vcita cookies in admin if vcita is disabled on the form

     	$confirmation_token = $this->vcita_get_confirmation_token($si_contact_opt);

	if (!empty($confirmation_token)) {
		?>
		<script type="text/javascript">
			VC_FSCF_set_cookie("<?php echo $si_contact_opt['vcita_uid']; ?>", "confirmation_token=<?php echo $confirmation_token; ?>");
		</script>
		<?php
	} else {
	    ?>
	    <script type="text/javascript">
	        VC_FSCF_set_cookie("generic-expert", "true");
	    </script>
	    <?php
	}
  }
?>

<div class="form-tab"><?php echo __('Meeting Scheduler - by vCita:', 'si-contact-form') .' '. sprintf(__('(form %d)', 'si-contact-form'),$form_id);?></div>
<div class="clear"></div>

<fieldset>
	<div style="max-width:600px;">
		<div>vCita extends your contact form and lets your users Schedule Meetings based on your availability.<br/>
		   You can meet users with web-based video, talk over phone conference, set a location for meetings  <br/>
		   and collect payments for your time and services.<br/>
		   <b>To learn more about vCita</b>, <a href="http://www.vcita.com/?autoplay=1&amp;no_redirect=true&amp;invite=wp-fscf" target="_blank">Take a Tour</a>
		</div>

		<div style="width:400px;float:left;">
			<?php $this->vcita_add_notification($si_contact_opt); ?>
			
			<input name="si_contact_vcita_confirm_tokens" type="hidden" value="<?php echo esc_attr($si_contact_opt['vcita_confirm_tokens']); ?>" />
			<input name="si_contact_vcita_initialized" type="hidden" value="<?php echo esc_attr($si_contact_opt['vcita_initialized']); ?>" />
			<input name="si_contact_vcita_uid" type="hidden" value="<?php echo esc_attr($si_contact_opt['vcita_uid']); ?>" />
			<input name="si_contact_vcita_approved" type="hidden" value="<?php echo esc_attr($si_contact_opt['vcita_approved']); ?>" />
			<input name="si_contact_vcita_auto_install" type="hidden" value="<?php echo esc_attr($si_contact_gb['vcita_auto_install']); ?>" />
			<input name="si_contact_vcita_dismiss" type="hidden" value="<?php echo esc_attr($si_contact_gb['vcita_dismiss']); ?>" />
			<input name="si_contact_ctf_version" type="hidden" value="<?php echo esc_attr($si_contact_gb['ctf_version']); ?>" />
			<input name="si_contact_vcita_email" type="hidden" value="<?php echo esc_attr($si_contact_opt['vcita_email']); ?>" />
			
			<?php if ( empty($si_contact_opt['vcita_uid'])) : ?>
			    
			    <label class="vcita-label" for="si_contact_vcita_email_new"><?php _e('Email Address:', 'si-contact-form') ?></label>
    			<input name="si_contact_vcita_email_new" id="si_contact_vcita_email_new" type="text" value="<?php echo esc_attr($si_contact_opt['vcita_email']); ?>"  />
    			<a style="cursor:pointer;" title="<?php _e('Privacy Policy', 'si-contact-form'); ?>" target="_blank" href="http://www.vcita.com/about/privacy_policy"><?php _e('Privacy Policy', 'si-contact-form'); ?></a>
    			<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_vcita_email_tip');"><?php _e('help', 'si-contact-form'); ?></a>
    			<div style="text-align:left; display:none" id="si_contact_vcita_email_tip">
    				<?php _e('Your email and name will only be used to send you meeting requests and additional communication from vCita, and will not be shared with your clients or third party.', 'si-contact-form'); ?>
    			</div>
    			
    			<br/>
    			<label class="vcita-label" for="si_contact_vcita_first_name"><?php _e('First Name:', 'si-contact-form') ?></label>
    			<input name="si_contact_vcita_first_name" id="si_contact_vcita_first_name" type="text" value="<?php echo esc_attr($si_contact_opt['vcita_first_name']); ?>"  />
    			<br/>
    			<label class="vcita-label" for="si_contact_vcita_last_name"><?php _e('Last Name:', 'si-contact-form') ?></label>
    			<input name="si_contact_vcita_last_name" id="si_contact_vcita_last_name" type="text" value="<?php echo esc_attr($si_contact_opt['vcita_last_name']); ?>"  />
    			<br/><br/>
    			
            <?php else : ?>
                <input name="si_contact_vcita_first_name" type="hidden" value="<?php echo esc_attr($si_contact_opt['vcita_first_name']); ?>" />
                <input name="si_contact_vcita_last_name" type="hidden" value="<?php echo esc_attr($si_contact_opt['vcita_last_name']); ?>" />
                
                <?php $this->vcita_add_config($si_contact_opt); ?>
            <?php endif ?>
            
            <br/>
            <input name="si_contact_vcita_enable_meeting_scheduler" id="si_contact_vcita_enable_meeting_scheduler" type="checkbox" <?php if ( $si_contact_opt['vcita_enabled'] != 'false' ) echo ' checked="checked" '; ?> />
			<label for="si_contact_vcita_enable_meeting_scheduler"><?php _e('Accept Meeting Requests via vCita', 'si-contact-form') ?></label>
			<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_vcita_enable_meeting_scheduler_tip');"><?php _e('help', 'si-contact-form'); ?></a>
			<div style="text-align:left; display:none" id="si_contact_vcita_enable_meeting_scheduler_tip">
				<?php _e('Check this option to add "Set a Meeting" button to your Contact Form, and let users send meeting requests', 'si-contact-form'); ?>
			</div>
		</div>
		<div style="float:left;max-width:155px;">
			<img src="<?php echo plugins_url( 'si-contact-form/vcita/vcita_icons.png' ); ?>" height="178px" width="151px" />
		</div>
	</div>
</fieldset>

 <p class="submit">
      <input type="submit" name="vcita_create" value="<?php echo esc_attr( __('Update Options', 'si-contact-form')); ?> &raquo;" />
    </p>

<?php /* --- vCita Admin Display - End --- */ ?>

<div class="form-tab"><?php echo __('Redirect:', 'si-contact-form') .' '. sprintf(__('(form %d)', 'si-contact-form'),$form_id);?></div>
<div class="clear"></div>
<fieldset>

        <input name="si_contact_redirect_enable" id="si_contact_redirect_enable" type="checkbox" <?php if( $si_contact_opt['redirect_enable'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_redirect_enable"><?php _e('Enable redirect after the message sends', 'si-contact-form'); ?>.</label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_redirect_enable_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_redirect_enable_tip">
        <?php _e('If enabled: After a user sends a message, the web browser will display "message sent" for x seconds, then redirect to the redirect URL. This can be used to redirect to the blog home page, or a custom "Thank You" page.', 'si-contact-form'); ?>
        </div>
        <br />

        <label for="si_contact_redirect_seconds"><?php _e('Redirect delay in seconds', 'si-contact-form'); ?>:</label>
        <input name="si_contact_redirect_seconds" id="si_contact_redirect_seconds" type="text" value="<?php echo absint($si_contact_opt['redirect_seconds']);  ?>" size="3" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_redirect_seconds_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_redirect_seconds_tip">
        <?php _e('How many seconds the web browser will display "message sent" before redirecting to the redirect URL. Values of 0-60 are allowed.', 'si-contact-form'); ?>
        </div>
        <br />

        <label for="si_contact_redirect_url"><?php _e('Redirect URL', 'si-contact-form'); ?>:</label><input name="si_contact_redirect_url" id="si_contact_redirect_url" type="text" value="<?php echo esc_attr($si_contact_opt['redirect_url']);  ?>" size="50" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_redirect_url_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_redirect_url_tip">
        <?php _e('The form will redirect to this URL after success. This can be used to redirect to the blog home page, or a custom "Thank You" page.', 'si-contact-form'); ?>
        <?php _e('Use FULL URL including http:// for best results.', 'si-contact-form'); ?>
        </div>
        <br />
      <?php
       if( $si_contact_opt['redirect_query'] == 'true' &&  $si_contact_opt['redirect_enable'] != 'true') {
         echo '<div class="fsc-error">';
         echo __('Warning: Enabling this setting requires the "Enable redirect" to also be set.', 'si-contact-form');
         echo "</div>\n";
       }
       ?>
        <input name="si_contact_redirect_query" id="si_contact_redirect_query" type="checkbox" <?php if( $si_contact_opt['redirect_query'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_redirect_query"><?php _e('Enable posted data to be sent as a query string on the redirect URL.', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_redirect_query_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_redirect_query_tip">
        <?php _e('If enabled: The posted data is sent to the redirect URL. This can be used to send the posted data via GET query string to a another form.', 'si-contact-form'); ?>
        </div>
        <br />
        <a href="http://www.fastsecurecontactform.com/sending-data-by-query-string" target="_new"><?php echo __('FAQ: Posted data can be sent as a query string on the redirect URL', 'si-contact-form'); ?></a>
        <br />
<table style="border:none;" cellspacing="20">
  <tr>
  <td valign="bottom">

        <label for="si_contact_redirect_ignore"><?php echo __('Query string fields to ignore', 'si-contact-form'); ?>:</label>
      <a style="cursor:pointer;" title="<?php echo __('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_redirect_ignore_tip');"><?php echo __('help', 'si-contact-form'); ?></a><br />
      <div style="text-align:left; display:none" id="si_contact_redirect_ignore_tip">
        <?php _e('Optional list of field names for fields you do not want included in the query string.', 'si-contact-form') ?><br />
        <?php _e('Start each entry on a new line.', 'si-contact-form'); ?><br />
		<?php _e('Available fields on this form:', 'si-contact-form'); ?>
		<span style="margin: 2px 0" dir="ltr"><br />
        <?php
       // show available fields
       foreach ($av_fld_arr as $i)
         echo "$i<br />";
        ?>
        </span>
      </div>
      <textarea rows="4" cols="25" name="si_contact_redirect_ignore" id="si_contact_redirect_ignore"><?php echo $si_contact_opt['redirect_ignore']; ?></textarea>
      <br />

 </td><td valign="bottom">

      <label for="si_contact_redirect_rename"><?php echo __('Query string fields to rename', 'si-contact-form'); ?>:</label>
      <a style="cursor:pointer;" title="<?php echo __('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_redirect_rename_tip');"><?php echo __('help', 'si-contact-form'); ?></a><br />
      <div style="text-align:left; display:none" id="si_contact_redirect_rename_tip">
        <?php _e('Optional list of field names for fields that need to be renamed for the query string.', 'si-contact-form') ?><br />
        <?php _e('Start each entry on a new line.', 'si-contact-form'); ?><br />
        <?php _e('Type the old field name separated by the equals character, then type the new name, like this: oldname=newname', 'si-contact-form'); ?><br />
		<?php _e('Examples:', 'si-contact-form'); ?>
		<span style="margin: 2px 0" dir="ltr"><br />
        from_name=name<br />
		from_email=email</span><br />
       	<?php _e('Available fields on this form:', 'si-contact-form'); ?>
		<span style="margin: 2px 0" dir="ltr"><br />
        <?php
       // show available fields
       foreach ($av_fld_arr as $i)
         echo "$i<br />";
        ?>
        </span>
      </div>
      <textarea rows="4" cols="25" name="si_contact_redirect_rename" id="si_contact_redirect_rename"><?php echo $si_contact_opt['redirect_rename']; ?></textarea>
      <br />

  </td><td valign="bottom">

      <label for="si_contact_redirect_add"><?php echo __('Query string key value pairs to add', 'si-contact-form'); ?>:</label>
      <a style="cursor:pointer;" title="<?php echo __('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_redirect_add_tip');"><?php echo __('help', 'si-contact-form'); ?></a><br />
      <div style="text-align:left; display:none" id="si_contact_redirect_add_tip">
        <?php _e('Optional list of key value pairs that need to be added.', 'si-contact-form') ?><br />
        <?php _e('Sometimes the outgoing connection will require fields that were not posted on your form.', 'si-contact-form') ?><br />
        <?php _e('Start each entry on a new line.', 'si-contact-form'); ?><br />
        <?php _e('Type the key separated by the equals character, then type the value, like this: key=value', 'si-contact-form'); ?><br />
		<?php _e('Examples:', 'si-contact-form'); ?>
		<span style="margin: 2px 0" dir="ltr"><br />
        account=3629675<br />
		newsletter=join<br />
		action=signup</span><br />
      </div>
      <textarea rows="4" cols="25" name="si_contact_redirect_add" id="si_contact_redirect_add"><?php echo $si_contact_opt['redirect_add']; ?></textarea>
      <br />

 </td>
 </tr>
 </table>

      <?php
       if( $si_contact_opt['redirect_email_off'] == 'true' && ($si_contact_opt['redirect_enable'] != 'true' || $si_contact_opt['redirect_query'] != 'true') ) {
         echo '<div class="fsc-error">';
         echo __('Warning: Enabling this setting requires the "Enable redirect" and "Enable posted data to be sent as a query string" to also be set.', 'si-contact-form');
         echo "</div>\n";
       }
       ?>

       <?php
       if( $si_contact_opt['redirect_email_off'] == 'true' && $si_contact_opt['redirect_enable'] == 'true' && $si_contact_opt['redirect_query'] == 'true' ) {
        ?><div id="message" class="updated"><strong><?php echo __('Warning: You have turned off email sending in the redirect settings below. This is just a reminder in case that was a mistake. If that is what you intended, then ignore this message.', 'si-contact-form'); ?></strong></div><?php
         echo '<div class="fsc-notice">';
         echo __('Warning: You have turned off email sending in the setting below. This is just a reminder in case that was a mistake. If that is what you intended, then ignore this message.', 'si-contact-form');
         echo "</div>\n";
       }
       ?>
        <input name="si_contact_redirect_email_off" id="si_contact_redirect_email_off" type="checkbox" <?php if( $si_contact_opt['redirect_email_off'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_redirect_email_off"><?php _e('Disable email sending (use only when required while you have enabled query string on the redirect URL).', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_redirect_email_off_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_redirect_email_off_tip">
        <?php _e('No email will be sent to you!! The posted data will ONLY be sent to the redirect URL. This can be used to send the posted data via GET query string to a another form. Note: the autoresponder will still send email if it is enabled.', 'si-contact-form'); ?>
        </div>
        <br />

</fieldset>

    <p class="submit">
      <input type="submit" name="submit" value="<?php echo esc_attr( __('Update Options', 'si-contact-form')); ?> &raquo;" />
    </p>

<div class="form-tab"><?php echo __('Silent Remote Sending:', 'si-contact-form') .' '. sprintf(__('(form %d)', 'si-contact-form'),$form_id);?></div>
<div class="clear"></div>
<fieldset>

   <?php echo __('Posted form data can be sent silently to a remote form using the method GET or POST.', 'si-contact-form'); ?>
   <br />
   <a href="http://www.fastsecurecontactform.com/send-form-data-elsewhere" target="_new"><?php echo __('FAQ: Send the posted form data to another site.', 'si-contact-form'); ?></a>
   <br />
   <br />

      <label for="si_contact_silent_send"><?php _e('Silent Remote Sending:', 'si-contact-form'); ?></label>
      <select id="si_contact_silent_send" name="si_contact_silent_send">
<?php
$silent_send_array = array(
'off' => __('Off', 'si-contact-form'),
'get' => __('Enabled: Method GET', 'si-contact-form'),
'post' => __('Enabled: Method POST', 'si-contact-form'),
);
$selected = '';
foreach ($silent_send_array as $k => $v) {
 if ($si_contact_opt['silent_send'] == "$k")  $selected = ' selected="selected"';
 echo '<option value="'.esc_attr($k).'"'.$selected.'>'.esc_html($v).'</option>'."\n";
 $selected = '';
}
?>
</select>

        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_silent_send_tip');">

        <?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_silent_send_tip">
        <?php _e('If enabled: After a user sends a message, the form can silently send the posted data to a third party remote URL. This can be used for a third party service such as a mailing list API.', 'si-contact-form'); ?>
        <?php echo ' '; _e('Select method GET or POST based on the remote API requirement.', 'si-contact-form'); ?>
        </div>
        <br />

       <?php
       if( $si_contact_opt['silent_send'] != 'off' &&  $si_contact_opt['silent_url'] == '') {
         echo '<div class="fsc-error">';
         echo __('Warning: Enabling this setting requires the "Silent Remote URL" to also be set.', 'si-contact-form');
         echo "</div>\n";
       }
       ?>

        <label for="si_contact_silent_url"><?php _e('Silent Remote URL', 'si-contact-form'); ?>:</label><input name="si_contact_silent_url" id="si_contact_silent_url" type="text" value="<?php echo $si_contact_opt['silent_url'];  ?>" size="50" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_silent_url_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_silent_url_tip">
        <?php _e('The form will silently send the form data to this URL after success. This can be used for a third party service such as a mailing list API.', 'si-contact-form'); ?>
        <?php _e('Use FULL URL including http:// for best results.', 'si-contact-form'); ?>
        </div>
        <br />

<table style="border:none;" cellspacing="20">
  <tr>
  <td valign="bottom">

        <label for="si_contact_silent_ignore"><?php echo __('Silent send fields to ignore', 'si-contact-form'); ?>:</label>
      <a style="cursor:pointer;" title="<?php echo __('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_silent_ignore_tip');"><?php echo __('help', 'si-contact-form'); ?></a><br />
      <div style="text-align:left; display:none" id="si_contact_silent_ignore_tip">
        <?php _e('Optional list of field names for fields you do not want included.', 'si-contact-form') ?><br />
        <?php _e('Start each entry on a new line.', 'si-contact-form'); ?><br />
		<?php _e('Available fields on this form:', 'si-contact-form'); ?>
		<span style="margin: 2px 0" dir="ltr"><br />
        <?php
       // show available fields
       foreach ($av_fld_arr as $i)
         echo "$i<br />";
        ?>
        </span>
      </div>
      <textarea rows="4" cols="25" name="si_contact_silent_ignore" id="si_contact_silent_ignore"><?php echo $si_contact_opt['silent_ignore']; ?></textarea>
      <br />

 </td><td valign="bottom">

      <label for="si_contact_silent_rename"><?php echo __('Silent send fields to rename', 'si-contact-form'); ?>:</label>
      <a style="cursor:pointer;" title="<?php echo __('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_silent_rename_tip');"><?php echo __('help', 'si-contact-form'); ?></a><br />
      <div style="text-align:left; display:none" id="si_contact_silent_rename_tip">
        <?php _e('Optional list of field names for fields that need to be renamed.', 'si-contact-form') ?><br />
        <?php _e('Start each entry on a new line.', 'si-contact-form'); ?><br />
        <?php _e('Type the old field name separated by the equals character, then type the new name, like this: oldname=newname', 'si-contact-form'); ?><br />
		<?php _e('Examples:', 'si-contact-form'); ?>
		<span style="margin: 2px 0" dir="ltr"><br />
        from_name=name<br />
		from_email=email</span><br />
        <?php _e('Available fields on this form:', 'si-contact-form'); ?>
		<span style="margin: 2px 0" dir="ltr"><br />
        <?php
       // show available fields
       foreach ($av_fld_arr as $i)
         echo "$i<br />";
        ?>
        </span>
      </div>
      <textarea rows="4" cols="25" name="si_contact_silent_rename" id="si_contact_silent_rename"><?php echo $si_contact_opt['silent_rename']; ?></textarea>
      <br />

  </td><td valign="bottom">

      <label for="si_contact_silent_add"><?php echo __('Silent send key value pairs to add', 'si-contact-form'); ?>:</label>
      <a style="cursor:pointer;" title="<?php echo __('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_silent_add_tip');"><?php echo __('help', 'si-contact-form'); ?></a><br />
      <div style="text-align:left; display:none" id="si_contact_silent_add_tip">
        <?php _e('Optional list of key value pairs that need to be added.', 'si-contact-form') ?><br />
        <?php _e('Sometimes the outgoing connection will require fields that were not posted on your form.', 'si-contact-form') ?><br />
        <?php _e('Start each entry on a new line.', 'si-contact-form'); ?><br />
        <?php _e('Type the key separated by the equals character, then type the value, like this: key=value', 'si-contact-form'); ?><br />
		<?php _e('Examples:', 'si-contact-form'); ?>
		<span style="margin: 2px 0" dir="ltr"><br />
        account=3629675<br />
		newsletter=join<br />
		action=signup</span><br />
      </div>
      <textarea rows="4" cols="25" name="si_contact_silent_add" id="si_contact_silent_add"><?php echo $si_contact_opt['silent_add']; ?></textarea>
      <br />

 </td>
 </tr>
 </table>

      <?php
       if( $si_contact_opt['silent_email_off'] == 'true' && ($si_contact_opt['silent_send'] == 'off' || $si_contact_opt['silent_url'] == '') ) {
         echo '<div class="fsc-error">';
         echo __('Warning: Enabling this setting requires the "Silent Remote Send" and "Silent Remote URL" to also be set.', 'si-contact-form');
         echo "</div>\n";
       }
       ?>

       <?php
       if( $si_contact_opt['silent_email_off'] == 'true' && $si_contact_opt['silent_send'] != 'off' ) {
        ?><div id="message" class="updated"><strong><?php echo __('Warning: You have turned off email sending in the Silent Remote Send settings below. This is just a reminder in case that was a mistake. If that is what you intended, then ignore this message.', 'si-contact-form'); ?></strong></div><?php
         echo '<div class="fsc-error">';
         echo __('Warning: You have turned off email sending in the setting below. This is just a reminder in case that was a mistake. If that is what you intended, then ignore this message.', 'si-contact-form');
         echo "</div>\n";
       }
       ?>
        <input name="si_contact_silent_email_off" id="si_contact_silent_email_off" type="checkbox" <?php if( $si_contact_opt['silent_email_off'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_silent_email_off"><?php _e('Disable email sending (use only when required while you have enabled silent remote sending).', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_silent_email_off_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_silent_email_off_tip">
        <?php _e('No email will be sent to you!! The posted data will ONLY be sent to the silent remote URL. This can be used for a third party service such as a mailing list API. Note: the autoresponder will still send email if it is enabled.', 'si-contact-form'); ?>
        </div>
        <br />

</fieldset>

    <p class="submit">
      <input type="submit" name="submit" value="<?php echo esc_attr( __('Update Options', 'si-contact-form')); ?> &raquo;" />
    </p>

<div class="form-tab"><?php echo __('Data Export:', 'si-contact-form') .' '. sprintf(__('(form %d)', 'si-contact-form'),$form_id);?></div>
<div class="clear"></div>
<fieldset>

         <?php echo sprintf( __('Posted fields data can be exported to another plugin such as the <a href="%s" target="_new">Contact Form 7 to DB Extension Plugin</a>.', 'si-contact-form'),'http://www.fastsecurecontactform.com/save-to-database'); ?>
         <br />
         <a href="http://www.fastsecurecontactform.com/save-to-database" target="_new"><?php echo __('FAQ: Save to a database or export to CSV file.', 'si-contact-form'); ?></a>
         <br />
         <br />
        <input name="si_contact_export_enable" id="si_contact_export_enable" type="checkbox" <?php if( $si_contact_opt['export_enable'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_export_enable"><?php _e('Enable data export after the message', 'si-contact-form'); ?>.</label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_export_enable_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_export_enable_tip">
        <?php echo sprintf( __('This settings requires a compatible data export plugin to be installed. If enabled: After a user sends a message, the posted fields data can be exported to another plugin such as the <a href="%s">Contact Form 7 to DB Extension Plugin</a>.', 'si-contact-form'),'http://www.fastsecurecontactform.com/save-to-database'); ?>
        <?php _e(' You can uncheck this setting to turn off data export for this form.', 'si-contact-form'); ?>
        </div>
        <br />

<table style="border:none;" cellspacing="20">
  <tr>
  <td valign="bottom">

        <label for="si_contact_export_ignore"><?php echo __('Data export fields to ignore', 'si-contact-form'); ?>:</label>
      <a style="cursor:pointer;" title="<?php echo __('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_export_ignore_tip');"><?php echo __('help', 'si-contact-form'); ?></a><br />
      <div style="text-align:left; display:none" id="si_contact_export_ignore_tip">
        <?php _e('Optional list of field names for fields you do not want included in the data export.', 'si-contact-form') ?><br />
        <?php _e('Start each entry on a new line.', 'si-contact-form'); ?><br />
		<?php _e('Available fields on this form:', 'si-contact-form'); ?>
		<span style="margin: 2px 0" dir="ltr"><br />
        <?php
       // show available fields
       foreach ($av_fld_arr as $i)
         echo "$i<br />";
        ?>
        </span>
      </div>

      <textarea rows="4" cols="25" name="si_contact_export_ignore" id="si_contact_export_ignore"><?php echo $si_contact_opt['export_ignore']; ?></textarea>
      <br />

 </td><td valign="bottom">

      <label for="si_contact_export_rename"><?php echo __('Data export fields to rename', 'si-contact-form'); ?>:</label>
      <a style="cursor:pointer;" title="<?php echo __('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_export_rename_tip');"><?php echo __('help', 'si-contact-form'); ?></a><br />
      <div style="text-align:left; display:none" id="si_contact_export_rename_tip">
        <?php _e('Optional list of field names for fields that need to be renamed before data export.', 'si-contact-form') ?><br />
        <?php _e('Start each entry on a new line.', 'si-contact-form'); ?><br />
        <?php _e('Type the old field name separated by the equals character, then type the new name, like this: oldname=newname', 'si-contact-form'); ?><br />
		<?php _e('Examples:', 'si-contact-form'); ?>
		<span style="margin: 2px 0" dir="ltr"><br />
        from_name=name<br />
		from_email=email</span><br />
        <?php _e('Available fields on this form:', 'si-contact-form'); ?>
		<span style="margin: 2px 0" dir="ltr"><br />
        <?php
       // show available fields
       foreach ($av_fld_arr as $i)
         echo "$i<br />";
        ?>
        </span>
      </div>
      <textarea rows="4" cols="25" name="si_contact_export_rename" id="si_contact_export_rename"><?php echo $si_contact_opt['export_rename']; ?></textarea>
      <br />

  </td><td valign="bottom">

      <label for="si_contact_export_add"><?php echo __('Data export key value pairs to add', 'si-contact-form'); ?>:</label>
      <a style="cursor:pointer;" title="<?php echo __('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_export_add_tip');"><?php echo __('help', 'si-contact-form'); ?></a><br />
      <div style="text-align:left; display:none" id="si_contact_export_add_tip">
        <?php _e('Optional list of key value pairs that need to be added.', 'si-contact-form') ?><br />
        <?php _e('Sometimes the outgoing connection will require fields that were not posted on your form.', 'si-contact-form') ?><br />
        <?php _e('Start each entry on a new line.', 'si-contact-form'); ?><br />
        <?php _e('Type the key separated by the equals character, then type the value, like this: key=value', 'si-contact-form'); ?><br />
		<?php _e('Examples:', 'si-contact-form'); ?>
		<span style="margin: 2px 0" dir="ltr"><br />
        account=3629675<br />
		newsletter=join<br />
		action=signup</span><br />
      </div>
      <textarea rows="4" cols="25" name="si_contact_export_add" id="si_contact_silent_add"><?php echo $si_contact_opt['export_add']; ?></textarea>
      <br />

 </td>
 </tr>
 </table>

      <?php
       if( $si_contact_opt['export_email_off'] == 'true' && ($si_contact_opt['export_enable'] != 'true' ) ) {
         echo '<div class="fsc-error">';
         echo __('Warning: Enabling this setting requires the "Enable data export" to also be set.', 'si-contact-form');
         echo "</div>\n";
       }
       ?>

       <?php
       if( $si_contact_opt['export_email_off'] == 'true' && $si_contact_opt['export_enable'] == 'true' ) {
        ?><div id="message" class="updated"><strong><?php echo __('Warning: You have turned off email sending in the data export settings below. This is just a reminder in case that was a mistake. If that is what you intended, then ignore this message.', 'si-contact-form'); ?></strong></div><?php
         echo '<div class="fsc-notice">';
         echo __('Warning: You have turned off email sending in the setting below. This is just a reminder in case that was a mistake. If that is what you intended, then ignore this message.', 'si-contact-form');
         echo "</div\n";
       }
       ?>
        <input name="si_contact_export_email_off" id="si_contact_export_email_off" type="checkbox" <?php if( $si_contact_opt['export_email_off'] == 'true' ) echo 'checked="checked"'; ?> />
        <label for="si_contact_export_email_off"><?php _e('Disable email sending (use only when required while you have enabled data export).', 'si-contact-form'); ?></label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_export_email_off_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_export_email_off_tip">
        <?php _e('No email will be sent to you!! The posted data will ONLY be sent to the data export. Note: the autoresponder will still send email if it is enabled.', 'si-contact-form'); ?>
        </div>

</fieldset>

    <p class="submit">
      <input type="submit" name="submit" value="<?php echo esc_attr( __('Update Options', 'si-contact-form')); ?> &raquo;" />
    </p>

<div class="form-tab"><?php echo __('Style:', 'si-contact-form') .' '. sprintf(__('(form %d)', 'si-contact-form'),$form_id);?></div>
<div class="clear"></div>
<fieldset>

        <strong><?php _e('Modifiable CSS Style Feature:', 'si-contact-form'); ?></strong>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_css_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_css_tip">
        <?php _e('Use to adjust the font colors or other styling of the contact form.', 'si-contact-form'); ?><br />
        <?php _e('You can use inline css, or add a class property to be used by your own stylsheet.', 'si-contact-form'); ?><br />
        <?php _e('Acceptable Examples:', 'si-contact-form'); ?><br />
        text-align:left; color:#000000; background-color:#CCCCCC;<br />
        style="text-align:left; color:#000000; background-color:#CCCCCC;"<br />
        class="input"
        </div>
<br />

        <input name="si_contact_reset_styles" id="si_contact_reset_styles" type="checkbox" />
        <label for="si_contact_reset_styles"><strong><?php _e('Reset the styles to labels on top (default).', 'si-contact-form') ?></strong></label><br />

        <input name="si_contact_reset_styles_left" id="si_contact_reset_styles_left" type="checkbox" />
        <label for="si_contact_reset_styles_left"><strong><?php _e('Reset the styles to labels on left.', 'si-contact-form') ?></strong></label><br />
 <br />

        <input name="si_contact_border_enable" id="si_contact_border_enable" type="checkbox" <?php if ( $si_contact_opt['border_enable'] == 'true' ) echo ' checked="checked" '; ?> />
        <label for="si_contact_border_enable"><?php _e('Enable border on contact form', 'si-contact-form') ?>.</label>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_border_enable_tip');"><?php _e('help', 'si-contact-form'); ?></a>
       <div style="text-align:left; display:none" id="si_contact_border_enable_tip">
       <?php _e('Enable to draw a fieldset box around all the form elements. You can add a border label or remove it with the setting below.', 'si-contact-form'); ?>
       </div>
<br />
         <label for="si_contact_title_border"><?php _e('Border label', 'si-contact-form'); ?>:</label><input name="si_contact_title_border" id="si_contact_title_border" type="text" value="<?php echo esc_attr($si_contact_opt['title_border']);  ?>" size="50" />

<br />

        <label for="si_contact_border_style"><?php _e('CSS style for border', 'si-contact-form'); ?>:</label><input name="si_contact_border_style" id="si_contact_border_style" type="text" value="<?php echo esc_attr($si_contact_opt['border_style']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_border_style_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_border_style_tip">
        <?php _e('Use to adjust the style of the contact form border (if border is enabled).', 'si-contact-form'); ?>
        </div>
<br />
<br />


        <label for="si_contact_form_style"><?php _e('CSS style for form DIV', 'si-contact-form'); ?>:</label><input name="si_contact_form_style" id="si_contact_form_style" type="text" value="<?php echo esc_attr($si_contact_opt['form_style']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_form_style_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_form_style_tip">
        <?php _e('Use to adjust the style in the form wrapping DIV.', 'si-contact-form'); ?>
        </div>
<br />


        <label for="si_contact_required_style"><?php _e('CSS style for required field text', 'si-contact-form'); ?>:</label><input name="si_contact_required_style" id="si_contact_required_style" type="text" value="<?php echo esc_attr($si_contact_opt['required_style']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_required_style_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_required_style_tip">
        <?php _e('Use to adjust the style of the message that says a field is required, and the required field indicator.', 'si-contact-form'); ?>
        </div>
<br />

        <label for="si_contact_notes_style"><?php _e('CSS style for extra field HTML', 'si-contact-form'); ?>:</label><input name="si_contact_notes_style" id="si_contact_notes_style" type="text" value="<?php echo esc_attr($si_contact_opt['notes_style']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_notes_style_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_notes_style_tip">
        <?php _e('Use to adjust the style in the HTML before and after DIVs for extra fields.', 'si-contact-form'); ?>
        </div>
<br />

        <label for="si_contact_title_style"><?php _e('CSS style for form input field labels', 'si-contact-form'); ?>:</label><input name="si_contact_title_style" id="si_contact_title_style" type="text" value="<?php echo esc_attr($si_contact_opt['title_style']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_title_style_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_title_style_tip">
        <?php _e('Use to adjust the style for the form field labels.', 'si-contact-form'); ?>
        </div>
<br />

        <label for="si_contact_field_style"><?php _e('CSS style inside form input fields', 'si-contact-form'); ?>:</label><input name="si_contact_field_style" id="si_contact_field_style" type="text" value="<?php echo esc_attr($si_contact_opt['field_style']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_field_style_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_field_style_tip">
        <?php _e('Use to adjust the style inside the form input fields such as text field types.', 'si-contact-form'); ?>
        </div>
<br />

        <label for="si_contact_field_div_style"><?php _e('CSS style for form input fields DIV', 'si-contact-form'); ?>:</label><input name="si_contact_field_div_style" id="si_contact_field_div_style" type="text" value="<?php echo esc_attr($si_contact_opt['field_div_style']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_div_style_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_div_style_tip">
        <?php _e('Use to adjust the style in the form input field alignment wrapping DIVs.', 'si-contact-form'); ?>
        </div>
<br />

        <label for="si_contact_error_style"><?php _e('CSS style for form input errors', 'si-contact-form'); ?>:</label><input name="si_contact_error_style" id="si_contact_error_style" type="text" value="<?php echo esc_attr($si_contact_opt['error_style']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_error_style_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_error_style_tip">
        <?php _e('Use to adjust the style of form input validation messages.', 'si-contact-form'); ?>
        </div>
<br />

        <label for="si_contact_select_style"><?php _e('CSS style for contact drop down select', 'si-contact-form'); ?>:</label><input name="si_contact_select_style" id="si_contact_select_style" type="text" value="<?php echo esc_attr($si_contact_opt['select_style']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_select_style_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_select_style_tip">
        <?php _e('Use to adjust the style of any form select field types including subject, if enabled.', 'si-contact-form'); ?>
        </div>
<br />

        <label for="si_contact_captcha_div_style_sm"><?php _e('CSS style for Small CAPTCHA DIV', 'si-contact-form'); ?>:</label><input name="si_contact_captcha_div_style_sm" id="si_contact_captcha_div_style_sm" type="text" value="<?php echo esc_attr($si_contact_opt['captcha_div_style_sm']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_captcha_div_style_sm_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_captcha_div_style_sm_tip">
        <?php _e('Use to adjust the style in the form Small CAPTCHA wrapping DIV, if enabled.', 'si-contact-form'); ?>
        </div>
<br />

        <label for="si_contact_captcha_div_style_m"><?php _e('CSS style for CAPTCHA DIV', 'si-contact-form'); ?>:</label><input name="si_contact_captcha_div_style_m" id="si_contact_captcha_div_style_m" type="text" value="<?php echo esc_attr($si_contact_opt['captcha_div_style_m']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_div_style_m_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_div_style_m_tip">
        <?php _e('Use to adjust the style in the form CAPTCHA wrapping DIV.', 'si-contact-form'); ?>
        </div>
<br />

        <label for="si_contact_captcha_input_style"><?php _e('CSS style for CAPTCHA input field', 'si-contact-form'); ?>:</label><input name="si_contact_captcha_input_style" id="si_contact_captcha_input_style" type="text" value="<?php echo esc_attr($si_contact_opt['captcha_input_style']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_captcha_input_style_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_captcha_input_style_tip">
        <?php _e('Use to adjust the style in the CAPTCHA code input text field.', 'si-contact-form'); ?>
        </div>
<br />

        <label for="si_contact_submit_div_style"><?php _e('CSS style for Submit DIV', 'si-contact-form'); ?>:</label><input name="si_contact_submit_div_style" id="si_contact_submit_div_style" type="text" value="<?php echo esc_attr($si_contact_opt['submit_div_style']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_submit_div_style_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_submit_div_style_tip">
        <?php _e('Use to adjust the style in the form submit button wrapping DIV.', 'si-contact-form'); ?>
        </div>
<br />

        <label for="si_contact_button_style"><?php _e('CSS style for Submit button', 'si-contact-form'); ?>:</label><input name="si_contact_button_style" id="si_contact_button_style" type="text" value="<?php echo esc_attr($si_contact_opt['button_style']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_button_style_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_button_style_tip">
        <?php _e('Use to adjust the style for the form submit button text.', 'si-contact-form'); ?>
        </div>
<br />

        <label for="si_contact_reset_style"><?php _e('CSS style for Reset button', 'si-contact-form'); ?>:</label><input name="si_contact_reset_style" id="si_contact_reset_style" type="text" value="<?php echo esc_attr($si_contact_opt['reset_style']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_reset_style_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_reset_style_tip">
        <?php _e('Use to adjust the style for the form reset button text, if enabled.', 'si-contact-form'); ?>
        </div>
<br />

        <label for="si_contact_powered_by_style"><?php _e('CSS style for "Powered by" message', 'si-contact-form'); ?>:</label><input name="si_contact_powered_by_style" id="si_contact_powered_by_style" type="text" value="<?php echo esc_attr($si_contact_opt['powered_by_style']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_powered_by_style_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_powered_by_style_tip">
        <?php _e('Use to adjust the style for the "powered by" message link.', 'si-contact-form'); ?>
        </div>
<br />

        <label for="si_contact_redirect_style"><?php _e('CSS style for redirecting message', 'si-contact-form'); ?>:</label><input name="si_contact_redirect_style" id="si_contact_redirect_style" type="text" value="<?php echo esc_attr($si_contact_opt['redirect_style']);  ?>" size="60" />
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_redirect_style_tip');"><?php _e('help', 'si-contact-form'); ?></a>
        <div style="text-align:left; display:none" id="si_contact_redirect_style_tip">
        <?php _e('Use to adjust the style for the "redirecting" message shown after the form is sent.', 'si-contact-form'); ?>
        </div>
<br />


       <label for="si_contact_field_size"><?php _e('Input Text Field Size', 'si-contact-form'); ?>:</label><input name="si_contact_field_size" id="si_contact_field_size" type="text" value="<?php echo absint($si_contact_opt['field_size']);  ?>" size="3" />
       <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_field_size_tip');"><?php _e('help', 'si-contact-form'); ?></a>
       <div style="text-align:left; display:none" id="si_contact_field_size_tip">
       <?php _e('Use to adjust the size of the contact form text input fields. Note: your theme CSS might override this setting.', 'si-contact-form'); ?>
       </div>
<br />

       <label for="si_contact_captcha_field_size"><?php _e('Input CAPTCHA Field Size', 'si-contact-form'); ?>:</label><input name="si_contact_captcha_field_size" id="si_contact_captcha_field_size" type="text" value="<?php echo absint($si_contact_opt['captcha_field_size']);  ?>" size="3" />
       <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_captcha_field_size_tip');"><?php _e('help', 'si-contact-form'); ?></a>
       <div style="text-align:left; display:none" id="si_contact_captcha_field_size_tip">
       <?php _e('Use to adjust the size of the contact form CAPTCHA input field. Note: your theme CSS might override this setting.', 'si-contact-form'); ?>
       </div>
<br />

       <label for="si_contact_text_cols"><?php _e('Input Textarea Field Cols', 'si-contact-form'); ?>:</label><input name="si_contact_text_cols" id="si_contact_text_cols" type="text" value="<?php echo absint($si_contact_opt['text_cols']);  ?>" size="3" />
       <label for="si_contact_text_rows"><?php _e('Rows', 'si-contact-form'); ?>:</label><input name="si_contact_text_rows" id="si_contact_text_rows" type="text" value="<?php echo absint($si_contact_opt['text_rows']);  ?>" size="3" />
       <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_text_rows_tip');"><?php _e('help', 'si-contact-form'); ?></a>
       <div style="text-align:left; display:none" id="si_contact_text_rows_tip">
       <?php _e('Use to adjust the size of the contact form message textarea. Note: your theme CSS might override this setting.', 'si-contact-form'); ?>
       </div>
<br />

       <input name="si_contact_aria_required" id="si_contact_aria_required" type="checkbox" <?php if( $si_contact_opt['aria_required'] == 'true' ) echo 'checked="checked"'; ?> />
       <label for="si_contact_aria_required"><?php _e('Enable aria-required tags for screen readers', 'si-contact-form'); ?>.</label>
       <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_aria_required_tip');"><?php _e('help', 'si-contact-form'); ?></a>
       <div style="text-align:left; display:none" id="si_contact_aria_required_tip">
       <?php _e('aria-required is a form input WAI ARIA tag. Screen readers use it to determine which fields are required. Enabling this is good for accessability, but will cause the HTML to fail the W3C Validation (there is no attribute "aria-required"). WAI ARIA attributes are soon to be accepted by the HTML validator, so you can safely ignore the validation error it will cause.', 'si-contact-form'); ?>
       </div>

</fieldset>

    <p class="submit">
      <input type="submit" name="submit" value="<?php echo esc_attr( __('Update Options', 'si-contact-form')); ?> &raquo;" />
    </p>

<div class="form-tab"><?php echo __('Fields:', 'si-contact-form') .' '. sprintf(__('(form %d)', 'si-contact-form'),$form_id);?></div>
<div class="clear"></div>
<fieldset>

<strong><?php _e('Change field labels:', 'si-contact-form'); ?></strong>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_text_fields_tip');"><?php _e('help', 'si-contact-form'); ?></a>
       <div style="text-align:left; display:none" id="si_contact_text_fields_tip">
       <?php _e('Some people wanted to change the labels for the contact form. These fields can be filled in to override the standard labels.', 'si-contact-form'); ?>
       </div>
<br />

        <input name="si_contact_req_field_label_enable" id="si_contact_req_field_label_enable" type="checkbox" <?php if ( $si_contact_opt['req_field_label_enable'] == 'true' ) echo ' checked="checked" '; ?> />
        <label for="si_contact_req_field_label_enable"><?php _e('Enable required field label on contact form:', 'si-contact-form') ?></label> <?php echo ($si_contact_opt['tooltip_required'] != '') ? $si_contact_opt['req_field_indicator'] .$si_contact_opt['tooltip_required'] : $si_contact_opt['req_field_indicator'] . __('(denotes required field)', 'si-contact-form'); ?><br />

        <label for="si_contact_tooltip_required"><?php _e('(denotes required field)', 'si-contact-form'); ?></label><input name="si_contact_tooltip_required" id="si_contact_tooltip_required" type="text" value="<?php echo esc_attr($si_contact_opt['tooltip_required']);  ?>" size="50" /><br />

        <input name="si_contact_req_field_indicator_enable" id="si_contact_req_field_indicator_enable" type="checkbox" <?php if ( $si_contact_opt['req_field_indicator_enable'] == 'true' ) echo ' checked="checked" '; ?> />
        <label for="si_contact_req_field_indicator_enable"><?php _e('Enable required field indicators on contact form', 'si-contact-form') ?>.</label><br />

        <label for="si_contact_req_field_indicator"><?php _e('Required field indicator:', 'si-contact-form'); ?></label><input name="si_contact_req_field_indicator" id="si_contact_req_field_indicator" type="text" value="<?php echo esc_attr($si_contact_opt['req_field_indicator']);  ?>" size="20" /><br />

         <label for="si_contact_title_dept"><?php _e('Department to Contact:', 'si-contact-form'); ?></label><input name="si_contact_title_dept" id="si_contact_title_dept" type="text" value="<?php echo esc_attr($si_contact_opt['title_dept']);  ?>" size="50" /><br />
         <label for="si_contact_title_select"><?php _e('Select', 'si-contact-form'); ?></label><input name="si_contact_title_select" id="si_contact_title_select" type="text" value="<?php echo esc_attr($si_contact_opt['title_select']);  ?>" size="50" /><br />
         <label for="si_contact_title_name"><?php _e('Name:', 'si-contact-form'); ?></label><input name="si_contact_title_name" id="si_contact_title_name" type="text" value="<?php echo esc_attr($si_contact_opt['title_name']);  ?>" size="50" /><br />
         <label for="si_contact_title_fname"><?php _e('First Name:', 'si-contact-form'); ?></label><input name="si_contact_title_fname" id="si_contact_title_fname" type="text" value="<?php echo esc_attr($si_contact_opt['title_fname']);  ?>" size="50" /><br />
         <label for="si_contact_title_lname"><?php _e('Last Name:', 'si-contact-form'); ?></label><input name="si_contact_title_lname" id="si_contact_title_lname" type="text" value="<?php echo esc_attr($si_contact_opt['title_lname']);  ?>" size="50" /><br />
         <label for="si_contact_title_mname"><?php _e('Middle Name:', 'si-contact-form'); ?></label><input name="si_contact_title_mname" id="si_contact_title_mname" type="text" value="<?php echo esc_attr($si_contact_opt['title_mname']);  ?>" size="50" /><br />
         <label for="si_contact_title_miname"><?php _e('Middle Initial:', 'si-contact-form'); ?></label><input name="si_contact_title_miname" id="si_contact_title_miname" type="text" value="<?php echo esc_attr($si_contact_opt['title_miname']);  ?>" size="50" /><br />
         <label for="si_contact_title_email"><?php _e('E-Mail Address:', 'si-contact-form'); ?></label><input name="si_contact_title_email" id="si_contact_title_email" type="text" value="<?php echo esc_attr($si_contact_opt['title_email']);  ?>" size="50" /><br />
         <label for="si_contact_title_email2"><?php _e('E-Mail Address again:', 'si-contact-form'); ?></label><input name="si_contact_title_email2" id="si_contact_title_email2" type="text" value="<?php echo esc_attr($si_contact_opt['title_email2']);  ?>" size="50" /><br />
         <label for="si_contact_title_email2"><?php _e('Please enter your e-mail Address a second time.', 'si-contact-form'); ?></label><input name="si_contact_title_email2_help" id="si_contact_title_email2_help" type="text" value="<?php echo esc_attr($si_contact_opt['title_email2_help']);  ?>" size="50" /><br />
         <label for="si_contact_title_subj"><?php _e('Subject:', 'si-contact-form'); ?></label><input name="si_contact_title_subj" id="si_contact_title_subj" type="text" value="<?php echo esc_attr($si_contact_opt['title_subj']);  ?>" size="50" /><br />
         <label for="si_contact_title_mess"><?php _e('Message:', 'si-contact-form'); ?></label><input name="si_contact_title_mess" id="si_contact_title_mess" type="text" value="<?php echo esc_attr($si_contact_opt['title_mess']);  ?>" size="50" /><br />
         <label for="si_contact_title_capt"><?php _e('CAPTCHA Code:', 'si-contact-form'); ?></label><input name="si_contact_title_capt" id="si_contact_title_capt" type="text" value="<?php echo esc_attr($si_contact_opt['title_capt']);  ?>" size="50" /><br />
         <label for="si_contact_title_submit"><?php _e('Submit', 'si-contact-form'); ?></label><input name="si_contact_title_submit" id="si_contact_title_submit" type="text" value="<?php echo esc_attr($si_contact_opt['title_submit']);  ?>" size="50" /><br />
         <label for="si_contact_title_reset"><?php _e('Reset', 'si-contact-form'); ?></label><input name="si_contact_title_reset" id="si_contact_title_reset" type="text" value="<?php echo esc_attr($si_contact_opt['title_reset']);  ?>" size="50" /><br />
         <label for="si_contact_title_areyousure"><?php _e('Are you sure?', 'si-contact-form'); ?></label><input name="si_contact_title_areyousure" id="si_contact_title_areyousure" type="text" value="<?php echo esc_attr($si_contact_opt['title_areyousure']);  ?>" size="50" /><br />
         <label for="si_contact_text_message_sent"><?php _e('Your message has been sent, thank you.', 'si-contact-form'); ?></label><input name="si_contact_text_message_sent" id="si_contact_text_message_sent" type="text" value="<?php echo esc_attr($si_contact_opt['text_message_sent']);  ?>" size="50" /><br />

</fieldset>

    <p class="submit">
      <input type="submit" name="submit" value="<?php echo esc_attr( __('Update Options', 'si-contact-form')); ?> &raquo;" />
    </p>

<div class="form-tab"><?php echo __('Tooltips:', 'si-contact-form') .' '. sprintf(__('(form %d)', 'si-contact-form'),$form_id);?></div>
<div class="clear"></div>
<fieldset>
<strong><?php _e('Change tooltips labels:', 'si-contact-form'); ?></strong>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_text_tools_tip');"><?php _e('help', 'si-contact-form'); ?></a>
       <div style="text-align:left; display:none" id="si_contact_text_tools_tip">
       <?php _e('Some people wanted to change the labels for the contact form. These fields can be filled in to override the standard labels.', 'si-contact-form'); ?>
       </div>
<br />

        <label for="si_contact_tooltip_captcha"><?php _e('CAPTCHA Image', 'si-contact-form'); ?></label><input name="si_contact_tooltip_captcha" id="si_contact_tooltip_captcha" type="text" value="<?php echo esc_attr($si_contact_opt['tooltip_captcha']);  ?>" size="50" /><br />
        <label for="si_contact_tooltip_audio"><?php _e('CAPTCHA Audio', 'si-contact-form'); ?></label><input name="si_contact_tooltip_audio" id="si_contact_tooltip_audio" type="text" value="<?php echo esc_attr($si_contact_opt['tooltip_audio']);  ?>" size="50" /><br />
        <label for="si_contact_tooltip_refresh"><?php _e('Refresh Image', 'si-contact-form'); ?></label><input name="si_contact_tooltip_refresh" id="si_contact_tooltip_refresh" type="text" value="<?php echo esc_attr($si_contact_opt['tooltip_refresh']);  ?>" size="50" /><br />
        <label for="si_contact_tooltip_filetypes"><?php _e('Acceptable file types:', 'si-contact-form'); ?></label><input name="si_contact_tooltip_filetypes" id="si_contact_tooltip_filetypes" type="text" value="<?php echo esc_attr($si_contact_opt['tooltip_filetypes']);  ?>" size="50" /><br />
        <label for="si_contact_tooltip_filesize"><?php _e('Maximum file size:', 'si-contact-form'); ?></label><input name="si_contact_tooltip_filesize" id="si_contact_tooltip_filesize" type="text" value="<?php echo esc_attr($si_contact_opt['tooltip_filesize']);  ?>" size="50" />

</fieldset>

    <p class="submit">
      <input type="submit" name="submit" value="<?php echo esc_attr( __('Update Options', 'si-contact-form')); ?> &raquo;" />
    </p>

<div class="form-tab"><?php echo __('Errors:', 'si-contact-form') .' '. sprintf(__('(form %d)', 'si-contact-form'),$form_id);?></div>
<div class="clear"></div>
<fieldset>
<strong><?php _e('Change error labels:', 'si-contact-form'); ?></strong>
        <a style="cursor:pointer;" title="<?php _e('Click for Help!', 'si-contact-form'); ?>" onclick="toggleVisibility('si_contact_error_fields_tip');"><?php _e('help', 'si-contact-form'); ?></a>
       <div style="text-align:left; display:none" id="si_contact_error_fields_tip">
       <?php _e('Some people wanted to change the error messages for the contact form. These fields can be filled in to override the standard included error messages.', 'si-contact-form'); ?>
       </div>
       <br />
         <label for="si_contact_error_contact_select"><?php _e('Selecting a contact is required.', 'si-contact-form'); ?></label><input name="si_contact_error_contact_select" id="si_contact_error_contact_select" type="text" value="<?php echo esc_attr($si_contact_opt['error_contact_select']);  ?>" size="50" /><br />
         <label for="si_contact_error_name"><?php _e('Your name is required.', 'si-contact-form'); ?></label><input name="si_contact_error_name" id="si_contact_error_name" type="text" value="<?php echo esc_attr($si_contact_opt['error_name']);  ?>" size="50" /><br />
         <label for="si_contact_error_email"><?php _e('A proper e-mail address is required.', 'si-contact-form'); ?></label><input name="si_contact_error_email" id="si_contact_error_email" type="text" value="<?php echo esc_attr($si_contact_opt['error_email']);  ?>" size="50" /><br />
         <label for="si_contact_error_email2"><?php _e('The two e-mail addresses did not match, please enter again.', 'si-contact-form'); ?></label><input name="si_contact_error_email2" id="si_contact_error_email2" type="text" value="<?php echo esc_attr($si_contact_opt['error_email2']);  ?>" size="50" /><br />
         <label for="si_contact_error_field"><?php _e('This field is required.', 'si-contact-form'); ?></label><input name="si_contact_error_field" id="si_contact_error_field" type="text" value="<?php echo esc_attr($si_contact_opt['error_field']);  ?>" size="50" /><br />
         <label for="si_contact_error_subject"><?php _e('Subject text is required.', 'si-contact-form'); ?></label><input name="si_contact_error_subject" id="si_contact_error_subject" type="text" value="<?php echo esc_attr($si_contact_opt['error_subject']);  ?>" size="50" /><br />
         <label for="si_contact_error_message"><?php _e('Message text is required.', 'si-contact-form'); ?></label><input name="si_contact_error_message" id="si_contact_error_message" type="text" value="<?php echo esc_attr($si_contact_opt['error_message']);  ?>" size="50" /><br />
         <label for="si_contact_error_input"><?php _e('Contact Form has Invalid Input', 'si-contact-form'); ?></label><input name="si_contact_error_input" id="si_contact_error_input" type="text" value="<?php echo esc_attr($si_contact_opt['error_input']);  ?>" size="50" /><br />
         <label for="si_contact_error_captcha_blank"><?php _e('Please complete the CAPTCHA.', 'si-contact-form'); ?></label><input name="si_contact_error_captcha_blank" id="si_contact_error_captcha_blank" type="text" value="<?php echo esc_attr($si_contact_opt['error_captcha_blank']);  ?>" size="50" /><br />
         <label for="si_contact_error_captcha_wrong"><?php _e('That CAPTCHA was incorrect.', 'si-contact-form'); ?></label><input name="si_contact_error_captcha_wrong" id="si_contact_error_captcha_wrong" type="text" value="<?php echo esc_attr($si_contact_opt['error_captcha_wrong']);  ?>" size="50" /><br />
         <label for="si_contact_error_spambot"><?php _e('Possible spam bot.', 'si-contact-form'); ?></label><input name="si_contact_error_spambot" id="si_contact_error_spambot" type="text" value="<?php echo esc_attr($si_contact_opt['error_spambot']);  ?>" size="50" /><br />
         <label for="si_contact_error_correct"><?php _e('Please make corrections below and try again.', 'si-contact-form'); ?></label><input name="si_contact_error_correct" id="si_contact_error_correct" type="text" value="<?php echo esc_attr($si_contact_opt['error_correct']);  ?>" size="50" />
</fieldset>

    <p class="submit">
      <input type="submit" name="submit" value="<?php echo esc_attr( __('Update Options', 'si-contact-form')); ?> &raquo;" />
    </p>
 <!-- end Click for Advanced was here -->

</form>

<form action="<?php echo admin_url( "plugins.php?ctf_form_num=$form_num&amp;page=si-contact-form/si-contact-form.php" ); ?>" method="post">
<?php wp_nonce_field('si-contact-form-email_test', 'email_test'); ?>
<fieldset class="options" style="border:1px solid black; padding:10px;">
<legend><?php _e('Send a Test E-mail', 'si-contact-form'); ?></legend>
<?php _e('If you are not receiving email from your form, try this test because it can display troubleshooting information.', 'si-contact-form'); ?><br />
<?php _e('There are settings you can use to try to fix email delivery problems, see this FAQ for help:', 'si-contact-form'); ?>
 <a href="http://www.fastsecurecontactform.com/email-does-not-send" target="_blank"><?php _e('FAQ', 'si-contact-form'); ?></a><br />
<?php _e('Type an email address here and then click Send Test to generate a test email.', 'si-contact-form'); ?>
<?php
if ( !function_exists('mail') ) {
   echo '<div class="fsc-error">'. __('Warning: Your web host has the mail() function disabled. PHP cannot send email.', 'si-contact-form');
   echo ' '. __('Have them fix it. Or you can install the "WP Mail SMTP" plugin and configure it to use SMTP.', 'si-contact-form').'</div>'."\n";
}
?>
<br />
<label for="si_contact_to"><?php _e('To:', 'si-contact-form'); ?></label>
<input type="text" name="si_contact_to" id="si_contact_to" value="" size="40" class="code" />
<p style="padding:0px;" class="submit">
<input type="submit" name="ctf_action" value="<?php _e('Send Test', 'si-contact-form'); ?>" />
</p>
</fieldset>
</form>

<br />


<form id="ctf_copy_settings" action="<?php echo admin_url( "plugins.php?ctf_form_num=$form_num&amp;page=si-contact-form/si-contact-form.php" ); ?>" method="post">
<?php wp_nonce_field('si-contact-form-copy_settings', 'copy_settings'); ?>
<fieldset class="options" style="border:1px solid black; padding:10px;">

<legend><?php _e('Copy Settings', 'si-contact-form'); ?></legend>
<?php _e('This tool can copy your contact form settings from this form number to any of your other forms.', 'si-contact-form'); ?><br />
<?php _e('Use to copy just the style settings, or all the settings from this form.', 'si-contact-form'); ?><br />
<?php _e('It is a good idea to backup all forms with the backup tool before you use this copy tool. Changes are permanent!', 'si-contact-form'); ?><br />

<label for="si_contact_copy_what"><?php echo __('What to copy:', 'si-contact-form'); ?></label>
<select id="si_contact_copy_what" name="si_contact_copy_what">
<?php
$copy_what_array = array(
'all' => sprintf(__('Form %d - all settings', 'si-contact-form'),$form_id),
'styles' => sprintf(__('Form %d - style settings', 'si-contact-form'),$form_id),
);

$selected = '';
foreach ($copy_what_array as $k => $v) {
 if (isset($_POST['si_contact_copy_what']) && $_POST['si_contact_copy_what'] == "$k")  $selected = ' selected="selected"';
 echo '<option value="'.esc_attr($k).'"'.$selected.'>'.esc_html($v).'</option>'."\n";
 $selected = '';
}
?>
</select>

<label for="si_contact_destination_form"><?php echo sprintf(__('Select a form to copy form %d settings to:', 'si-contact-form'),$form_id); ?></label>
<select id="si_contact_destination_form" name="si_contact_destination_form">
<?php
$backup_type_array = array(
'all' => __('All Forms', 'si-contact-form'),
);
$backup_type_array["1"] = sprintf(__('Form: %d', 'si-contact-form'),1);
// multi-forms > 1
for ($i = 2; $i <= $si_contact_gb['max_forms']; $i++) {
$backup_type_array[$i] = sprintf(__('Form: %d', 'si-contact-form'),$i);
}
$selected = '';
foreach ($backup_type_array as $k => $v) {
 if (isset($_POST['si_contact_destination_form']) && $_POST['si_contact_destination_form'] == "$k")  $selected = ' selected="selected"';
 echo '<option value="'.esc_attr($k).'"'.$selected.'>'.esc_html($v).'</option>'."\n";
 $selected = '';
}
?>
</select>


<input type="hidden" name="si_contact_this_form" id="si_contact_this_form" value="<?php echo $form_id ?>"  />
<p style="padding:0px;" class="submit">
<input type="submit" name="ctf_action" onclick="return confirm('<?php _e('Are you sure you want to permanently make this change?', 'si-contact-form'); ?>')" value="<?php _e('Copy Settings', 'si-contact-form'); ?>" />
</p>

</fieldset>
</form>

<br />


<form id="ctf_backup_settings" action="<?php echo admin_url( "plugins.php?ctf_form_num=$form_num&amp;page=si-contact-form/si-contact-form.php" ); ?>" method="post">
<?php wp_nonce_field('si-contact-form-backup_settings','backup_settings'); ?>
<fieldset class="options" style="border:1px solid black; padding:10px;">

<legend><?php _e('Backup Settings', 'si-contact-form'); ?></legend>
<?php _e('This tool can save a backup of your contact form settings.', 'si-contact-form'); ?><br />
<?php _e('Use to transfer one, or all, of your forms from one site to another. Or just make a backup to save.', 'si-contact-form'); ?><br />
<label for="si_contact_backup_type"><?php _e('Select a form to backup:', 'si-contact-form'); ?></label>

<select id="si_contact_backup_type" name="si_contact_backup_type">
<?php
$backup_type_array = array(
'all' => __('All Forms', 'si-contact-form'),
);
$backup_type_array["1"] = sprintf(__('Form: %d', 'si-contact-form'),1);
// multi-forms > 1
for ($i = 2; $i <= $si_contact_gb['max_forms']; $i++) {
$backup_type_array[$i] = sprintf(__('Form: %d', 'si-contact-form'),$i);
}
$selected = '';
foreach ($backup_type_array as $k => $v) {
 if (isset($_POST['si_contact_backup_type']) && $_POST['si_contact_backup_type'] == "$k")  $selected = ' selected="selected"';
 echo '<option value="'.esc_attr($k).'"'.$selected.'>'.esc_html($v).'</option>'."\n";
 $selected = '';
}
?>
</select>

<p style="padding:0px;" class="submit">
<input type="submit" name="ctf_action" value="<?php esc_attr(_e('Backup Settings', 'si-contact-form')); ?>" />
</p>

</fieldset>
</form>

<br />

<form enctype="multipart/form-data" id="ctf_restore_settings" action="<?php echo admin_url( "plugins.php?ctf_form_num=$form_num&amp;page=si-contact-form/si-contact-form.php" ); ?>" method="post">
<?php wp_nonce_field('si-contact-form-restore_settings','restore_settings'); ?>
<fieldset class="options" style="border:1px solid black; padding:10px;">

<legend><?php _e('Restore Settings', 'si-contact-form'); ?></legend>
<?php _e('This tool can restore a backup of your contact form settings. If you have previously made a backup, you can restore one or all your forms.', 'si-contact-form'); ?><br />
<?php _e('It is a good idea to backup all forms with the backup tool before you restore any. Changes are permanent!', 'si-contact-form'); ?><br />
<label for="si_contact_restore_backup_type"><?php _e('Select a form to restore:', 'si-contact-form'); ?></label>

<select id="si_contact_restore_backup_type" name="si_contact_backup_type">
<?php
$backup_type_array = array(
'all' => __('All Forms', 'si-contact-form'),
);
$backup_type_array["1"] = sprintf(__('Form: %d', 'si-contact-form'),1);
// multi-forms > 1
for ($i = 2; $i <= $si_contact_gb['max_forms']; $i++) {
$backup_type_array[$i] = sprintf(__('Form: %d', 'si-contact-form'),$i);
}
$selected = '';
foreach ($backup_type_array as $k => $v) {
 if (isset($_POST['si_contact_backup_type']) && $_POST['si_contact_backup_type'] == "$k")  $selected = ' selected="selected"';
 echo '<option value="'.esc_attr($k).'"'.$selected.'>'.esc_html($v).'</option>'."\n";
 $selected = '';
}
?>
</select>
<br />

<label for="si_contact_backup_file"><?php _e('Upload Backup File:', 'si-contact-form'); ?></label>
<input style="text-align:left; margin:0;" type="file" id="si_contact_backup_file" name="si_contact_backup_file" value=""  size="20" />

<p style="padding:0px;" class="submit">
<input type="submit" name="ctf_action" onclick="return confirm('<?php esc_js(_e('Are you sure you want to permanently make this change?', 'si-contact-form')); ?>')" value="<?php esc_html(_e('Restore Settings', 'si-contact-form')); ?>" />
</p>

</fieldset>
</form>

<?php
// Remotely fetch, cache, and display HTML ad for the Fast Secure Contact Form Newsletter plugin addon.
if (!function_exists('sicf_ctct_admin_form')) // skip if the plugin is already installed and activated
  $this->kws_get_remote_ad();
?>

<table style="border:none;" width="775">
  <tr>
  <td width="325">
<p><strong><?php _e('More WordPress plugins by Mike Challis:', 'si-contact-form') ?></strong></p>
<ul>
<li><a href="http://www.FastSecureContactForm.com/" target="_blank"><?php _e('Fast Secure Contact Form', 'si-contact-form'); ?></a></li>
<li><a href="http://wordpress.org/extend/plugins/si-captcha-for-wordpress/" target="_blank"><?php _e('SI CAPTCHA Anti-Spam', 'si-contact-form'); ?></a></li>
<li><a href="http://wordpress.org/extend/plugins/visitor-maps/" target="_blank"><?php _e('Visitor Maps and Who\'s Online', 'si-contact-form'); ?></a></li>

</ul>
<?php
if ($si_contact_gb['donated'] != 'true') { ?>
   </td><td width="350">
   <?php echo sprintf(__('"I recommend <a href="%s" target="_blank">HostGator Web Hosting</a>. All my sites are hosted there. The prices are great and they offer great features for WordPress users. If you click this link and start an account at HostGator, I get a small commission." - Mike Challis', 'si-contact-form'), 'http://secure.hostgator.com/~affiliat/cgi-bin/affiliates/clickthru.cgi?id=mchallis-fscwp&amp;page=http://www.hostgator.com/apps/wordpress-hosting.shtml'); ?>
   </td><td width="100">
    <a href="http://secure.hostgator.com/~affiliat/cgi-bin/affiliates/clickthru.cgi?id=mchallis-fscwp&amp;page=http://www.hostgator.com/apps/wordpress-hosting.shtml" target="_blank"><img title="<?php echo esc_attr(__('Web Site Hosting', 'si-contact-form')); ?>" alt="<?php echo esc_attr(__('Web Site Hosting', 'si-contact-form')); ?>" src="<?php echo plugins_url( 'si-contact-form/hostgator-blog.gif' ); ?>" width="100" height="100" /></a>

<?php
  }
 ?>
</td>
</tr>
</table>
</div>
</div><!-- end div main -->

<?php
 } // if show form
?>
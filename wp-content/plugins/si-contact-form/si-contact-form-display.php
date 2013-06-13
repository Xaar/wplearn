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

/*
All the code in this file is inside function si_contact_form_short_code
This function may be processed more than once via shortcode when there are multiple forms on a page,
or when a plugin modifies "the content".
The error display and the form post vars should only be processed for one form that was posted.
Only one form can be posted at a time
$this->si_contact_error is set if the form posted had errors in si_contact_form_check
$fsc_form_posted is set to the form # posted in si_contact_form_check_and_send, will be 0 of not posted
$display_only means that this iteration in the display code is not a form that was posted, so ignore post vars
*/


  // a couple language options need to be translated now.
  $this->si_contact_update_lang();

// Email address(s) to receive Bcc (Blind Carbon Copy) messages
$ctf_email_address_bcc = $si_contact_opt['email_bcc']; // optional

// optional subject list
$subjects = array ();
$subjects_test = explode("\n",trim($si_contact_opt['email_subject_list']));
if(!empty($subjects_test) ) {
  $ct = 1;
  foreach($subjects_test as $v) {
       $v = trim($v);
       if ($v != '') {
          $subjects["$ct"] = $v;
          $ct++;
       }
  }
}

// get the list of contacts for display
$contacts = $this->get_contacts();

// Site Name / Title
$ctf_sitename = get_option('blogname');

// Site Domain without the http://www like this: $domain = '642weather.com';
// Can be a single domain:      $ctf_domain = '642weather.com';
// Can be an array of domains:  $ctf_domain = array('642weather.com','someothersite.com');
        // get blog domain
        $uri = parse_url(get_option('home'));
        $blogdomain = preg_replace("/^www\./i",'',$uri['host']);

$this->ctf_domain = $blogdomain;

$form_action_url = $this->form_action_url();

// Double E-mail entry is optional
// enabling this requires user to enter their email two times on the contact form.
$ctf_enable_double_email = $si_contact_opt['double_email'];

// initialize vars
$string = '';
$mail_to    = '';
$to_contact = '';
$name       = $this->si_contact_get_var($form_id_num,'name');
$f_name     = $this->si_contact_get_var($form_id_num,'f_name');
$m_name     = $this->si_contact_get_var($form_id_num,'m_name');
$mi_name    = $this->si_contact_get_var($form_id_num,'mi_name');
$l_name     = $this->si_contact_get_var($form_id_num,'l_name');
$email      = $this->si_contact_get_var($form_id_num,'email');
$email2     = $this->si_contact_get_var($form_id_num,'email');
$subject    = $this->si_contact_get_var($form_id_num,'subject');
$message    = $this->si_contact_get_var($form_id_num,'message');
$captcha_code  = '';
$vcita_add_script = false;
if ($si_contact_opt['vcita_enabled'] == 'true')
  $vcita_add_script = true;

// optional extra fields
// capture query string vars
$have_attach = '';
for ($i = 1; $i <= $si_contact_opt['max_fields']; $i++) {
   if ($si_contact_opt['ex_field'.$i.'_label'] != '') {
      ${'ex_field'.$i} = '';
      if ($si_contact_opt['ex_field'.$i.'_type'] == 'time') {
         ${'ex_field'.$i.'h'} = $this->si_contact_get_var($form_id_num,'ex_field'.$i.'h');
         ${'ex_field'.$i.'m'} = $this->si_contact_get_var($form_id_num,'ex_field'.$i.'m');
         ${'ex_field'.$i.'ap'} = $this->si_contact_get_var($form_id_num,'ex_field'.$i.'ap');
      }
      if( in_array($si_contact_opt['ex_field'.$i.'_type'],array('hidden','text','email','url','textarea','date','password')) ) {
         ${'ex_field'.$i} = $this->si_contact_get_var($form_id_num,'ex_field'.$i);
      }
      if ($si_contact_opt['ex_field'.$i.'_type'] == 'radio' || $si_contact_opt['ex_field'.$i.'_type'] == 'select') {
         $exf_opts_array = $this->si_contact_get_exf_opts_array($si_contact_opt['ex_field'.$i.'_label']);
         $check_ex_field = $this->si_contact_get_var($form_id_num,'ex_field'.$i);
         if($check_ex_field != '' && is_numeric($check_ex_field) && $check_ex_field > 0 ) {
           if( isset($exf_opts_array[$check_ex_field-1]) )
               ${'ex_field'.$i} = $exf_opts_array[$check_ex_field-1];
         }
      }
      if ($si_contact_opt['ex_field'.$i.'_type'] == 'select-multiple') {
         $exf_opts_array = $this->si_contact_get_exf_opts_array($si_contact_opt['ex_field'.$i.'_label']);
         $ex_cnt = 1;
         foreach ($exf_opts_array as $k) {
             if( $this->si_contact_get_var($form_id_num,'ex_field'.$i.'_'.$ex_cnt) == 1 ){
                 ${'ex_field'.$i.'_'.$ex_cnt} = 'selected';
             }
             $ex_cnt++;
         }
      }
      if ($si_contact_opt['ex_field'.$i.'_type'] == 'checkbox' || $si_contact_opt['ex_field'.$i.'_type'] == 'checkbox-multiple') {
         $exf_array_test = trim($si_contact_opt['ex_field'.$i.'_label'] );
         if(preg_match('#(?<!\\\)\,#', $exf_array_test) ) {
            $exf_opts_array = $this->si_contact_get_exf_opts_array($si_contact_opt['ex_field'.$i.'_label']);
            $ex_cnt = 1;
            foreach ($exf_opts_array as $k) {
                if( $this->si_contact_get_var($form_id_num,'ex_field'.$i.'_'.$ex_cnt) == 1 ){
                     ${'ex_field'.$i.'_'.$ex_cnt} = 'selected';
                }
                $ex_cnt++;
            }
         }else{
              if($this->si_contact_get_var($form_id_num,'ex_field'.$i) == 1)
              ${'ex_field'.$i} = 'selected';
         }
      }
      if ($si_contact_opt['ex_field'.$i.'_type'] == 'attachment')
         $have_attach = 1; // for <form post

   }
}
$req_field_ind = ( $si_contact_opt['req_field_indicator_enable'] == 'true' ) ? '<span '.$this->si_contact_convert_css($si_contact_opt['required_style']).'>'.$si_contact_opt['req_field_indicator'].'</span>' : '';

// see if WP user
global $current_user, $user_ID;
get_currentuserinfo();

  // gather all input variables, and they have already been validated in si_contact_check_form

    // allow shortcode email_to
    // Webmaster,user1@example.com (must have name,email)
    // multiple emails allowed
    // Webmaster,user1@example.com;user2@example.com
   if ( $_SESSION["fsc_shortcode_email_to_$form_id_num"] != '') {
     if(preg_match("/,/", $_SESSION["fsc_shortcode_email_to_$form_id_num"]) ) {
       list($key, $value) = preg_split('#(?<!\\\)\,#',$_SESSION["fsc_shortcode_email_to_$form_id_num"]); //string will be split by "," but "\," will be ignored
       $key   = trim(str_replace('\,',',',$key)); // "\," changes to ","
       $value = trim(str_replace(';',',',$value)); // ";" changes to ","
       if ($key != '' && $value != '') {
             $mail_to    = $this->ctf_clean_input($value);
             $to_contact = $this->ctf_clean_input($key);
       }
     }
   }

    if ($si_contact_opt['name_type'] != 'not_available' && !$display_only) {
        switch ($si_contact_opt['name_format']) {
          case 'name':
             if (isset($_POST['si_contact_name']))
               $name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_name']));
          break;
          case 'first_last':
             if (isset($_POST['si_contact_f_name']))
               $f_name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_f_name']));
             if (isset($_POST['si_contact_l_name']))
               $l_name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_l_name']));
          break;
          case 'first_middle_i_last':
             if (isset($_POST['si_contact_f_name']))
               $f_name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_f_name']));
             if (isset($_POST['si_contact_mi_name']))
               $mi_name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_mi_name']));
             if (isset($_POST['si_contact_l_name']))
               $l_name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_l_name']));
          break;
          case 'first_middle_last':
             if (isset($_POST['si_contact_f_name']))
               $f_name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_f_name']));
             if (isset($_POST['si_contact_m_name']))
               $m_name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_m_name']));
             if (isset($_POST['si_contact_l_name']))
               $l_name = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_l_name']));
         break;
      }
    }
    if ($si_contact_opt['email_type'] != 'not_available' && !$display_only) {
       if (isset($_POST['si_contact_email']))
         $email = strtolower($this->ctf_clean_input($_POST['si_contact_email']));
       if ($ctf_enable_double_email == 'true') {
         if (isset($_POST['si_contact_email2']))
          $email2 = strtolower($this->ctf_clean_input($_POST['si_contact_email2']));
       }
    }

    if ($si_contact_opt['message_type'] != 'not_available' && !$display_only) {
       if (isset($_POST['si_contact_message'])) {
         if ($si_contact_opt['preserve_space_enable'] == 'true')
           $message = $this->ctf_clean_input($_POST['si_contact_message'],1);
         else
           $message = $this->ctf_clean_input($_POST['si_contact_message']);
       }
    }
    if ( $this->isCaptchaEnabled() === true)
         $captcha_code = $this->si_contact_post_var('si_contact_captcha_code',$display_only);

  // CAPS Decapitator
   if ($si_contact_opt['name_case_enable'] == 'true' && !preg_match("/[a-z]/", $message))
      $message = $this->ctf_name_case($message);

   if(!empty($f_name)) $name .= $f_name;
   if(!empty($mi_name))$name .= ' '.$mi_name;
   if(!empty($m_name)) $name .= ' '.$m_name;
   if(!empty($l_name)) $name .= ' '.$l_name;

   // optional extra fields form post validation
      for ($i = 1; $i <= $si_contact_opt['max_fields']; $i++) {
        if ($si_contact_opt['ex_field'.$i.'_label'] != '' && $si_contact_opt['ex_field'.$i.'_type'] != 'fieldset-close') {
          if ($si_contact_opt['ex_field'.$i.'_type'] == 'fieldset') {

          }else if ($si_contact_opt['ex_field'.$i.'_type'] == 'date') {

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
               // required validate
               //${'ex_field'.$i} = $this->si_contact_post_var("si_contact_ex_field$i");

          }else if ($si_contact_opt['ex_field'.$i.'_type'] == 'hidden') {
               ${'ex_field'.$i} = $this->si_contact_post_var("si_contact_ex_field$i",$display_only);
          }else if ($si_contact_opt['ex_field'.$i.'_type'] == 'time') {
              if ( isset($_POST["si_contact_ex_field".$i."h"]) )
                 ${'ex_field'.$i.'h'}  = $this->si_contact_post_var("si_contact_ex_field".$i."h",$display_only);
              if ( isset($_POST["si_contact_ex_field".$i."m"]) )
                 ${'ex_field'.$i.'m'}  = $this->si_contact_post_var("si_contact_ex_field".$i."m",$display_only);
              if ($si_contact_opt['time_format'] == '12') {
                 if ( isset($_POST["si_contact_ex_field".$i."ap"]) )
                  ${'ex_field'.$i.'ap'} = $this->si_contact_post_var("si_contact_ex_field".$i."ap",$display_only);
              }
          }else if ($si_contact_opt['ex_field'.$i.'_type'] == 'attachment') {
                   // file name that was uploaded.  PHP and browser security does not allow access to the local selected file path
                   ${'ex_field'.$i} = '';
          }else if ($si_contact_opt['ex_field'.$i.'_type'] == 'checkbox' || $si_contact_opt['ex_field'.$i.'_type'] == 'checkbox-multiple') {
             // see if checkbox children
             $exf_opts_array = array();
             $exf_opts_label = '';
             $exf_array_test = trim($si_contact_opt['ex_field'.$i.'_label'] );
             if(preg_match('#(?<!\\\)\,#', $exf_array_test) ) {
                  list($exf_opts_label, $value) = preg_split('#(?<!\\\)\,#',$exf_array_test); //string will be split by "," but "\," will be ignored
                  $exf_opts_label   = trim(str_replace('\,',',',$exf_opts_label)); // "\," changes to ","
                  $value = trim(str_replace('\,',',',$value)); // "\," changes to ","
                  if ($exf_opts_label != '' && $value != '') {
                     if(preg_match("/;/", $value)) {
                        // multiple options
                         $exf_opts_array = explode(";",$value);
                     }
                     // required check (only 1 has to be checked to meet required)
                    $ex_cnt = 1;
                    $ex_reqd = 0;
                    foreach ($exf_opts_array as $k) {
                      $k = trim($k);
                      if( isset($_POST["si_contact_ex_field$i".'_'.$ex_cnt]) && ! empty($_POST["si_contact_ex_field$i".'_'.$ex_cnt]) ){
                        ${'ex_field'.$i.'_'.$ex_cnt} = $this->si_contact_post_var("si_contact_ex_field$i".'_'.$ex_cnt,$display_only);
                        $ex_reqd++;
                      }
                      $ex_cnt++;
                    }
                }
             }else{
               if ( isset($_POST["si_contact_ex_field$i"]) )
                ${'ex_field'.$i} = $this->si_contact_post_var("si_contact_ex_field$i",$display_only);
             }
           }else if ($si_contact_opt['ex_field'.$i.'_type'] == 'select-multiple') {
             $exf_opts_array = array();
             $exf_opts_label = '';
             $exf_array_test = trim($si_contact_opt['ex_field'.$i.'_label'] );
             if(preg_match('#(?<!\\\)\,#', $exf_array_test) ) {
                  list($exf_opts_label, $value) = preg_split('#(?<!\\\)\,#',$exf_array_test); //string will be split by "," but "\," will be ignored
                  $exf_opts_label   = trim(str_replace('\,',',',$exf_opts_label)); // "\," changes to ","
                  $value = trim(str_replace('\,',',',$value)); // "\," changes to ","
                  if ($exf_opts_label != '' && $value != '') {
                     if(preg_match("/;/", $value)) {
                        // multiple options
                         $exf_opts_array = explode(";",$value);
                     }
                     // required check (only 1 has to be checked to meet required)
                     $ex_reqd = 0;
                     if ( isset($_POST["si_contact_ex_field$i"]) )
                      ${'ex_field'.$i} = $this->si_contact_post_var("si_contact_ex_field$i",$display_only);
                     if (is_array(${'ex_field'.$i}) && !empty(${'ex_field'.$i}) ) {
                       // loop
                       foreach ($exf_opts_array as $k) {  // checkbox multi
                          $k = trim($k);
                          if (in_array($k, ${'ex_field'.$i} ) ) {
                             $ex_reqd++;
                          }
                       }
                     }
                }
             }
           }else if ($si_contact_opt['ex_field'.$i.'_type'] == 'email') {
                 if ( isset($_POST["si_contact_ex_field$i"]) )
                  ${'ex_field'.$i} = strtolower($this->si_contact_post_var("si_contact_ex_field$i",$display_only));

           }else if ($si_contact_opt['ex_field'.$i.'_type'] == 'url') {
                 if ( isset($_POST["si_contact_ex_field$i"]) )
                  ${'ex_field'.$i} = $this->si_contact_post_var("si_contact_ex_field$i",$display_only);
           }else{
                // text, textarea, radio, select, password
                if ($si_contact_opt['ex_field'.$i.'_type'] == 'textarea' && $si_contact_opt['textarea_html_allow'] == 'true') {
                     if ( isset($_POST["si_contact_ex_field$i"]) )
                      ${'ex_field'.$i} = wp_kses_data(stripslashes($this->si_contact_post_var("si_contact_ex_field$i",$display_only))); // allow only some safe html
                }else{
                     if ( isset($_POST["si_contact_ex_field$i"]) )
                      ${'ex_field'.$i} = $this->si_contact_post_var("si_contact_ex_field$i",$display_only);
                }
           }
        }  // end if label != ''
      } // end foreach


     // The welcome is what gets printed just before the form.
     // It is not printed when there is an input error or after the form is completed
     $string .= '
'.$si_contact_opt['welcome'];

// the form is being displayed now
 $this->ctf_notes_style = $this->si_contact_convert_css($si_contact_opt['notes_style']);
 $this->ctf_form_style = $this->si_contact_convert_css($si_contact_opt['form_style']);
 $this->ctf_border_style = $this->si_contact_convert_css($si_contact_opt['border_style']);
 $this->ctf_select_style = $this->si_contact_convert_css($si_contact_opt['select_style']);
 $this->ctf_title_style = $this->si_contact_convert_css($si_contact_opt['title_style']);
 $this->ctf_field_style = $this->si_contact_convert_css($si_contact_opt['field_style']);
 $this->ctf_field_div_style = $this->si_contact_convert_css($si_contact_opt['field_div_style']);
 $this->ctf_error_style = $this->si_contact_convert_css($si_contact_opt['error_style']);
 $this->ctf_required_style = $this->si_contact_convert_css($si_contact_opt['required_style']);

 $ctf_field_size = absint($si_contact_opt['field_size']);

 $this->ctf_aria_required = ($si_contact_opt['aria_required'] == 'true') ? ' aria-required="true" ' : '';

if ($have_error)
  $this->ctf_form_style = str_replace('display: none;','',$this->ctf_form_style);

$string .= '
<!-- Fast Secure Contact Form plugin '.esc_html($this->ctf_version).' - begin - FastSecureContactForm.com -->
<div id="FSContact'.$form_id_num.'" '.$this->ctf_form_style.'>';

if ($si_contact_opt['vcita_enabled'] == 'true') {
  $string .= "
<div style='float:left;' class='fsc_data_container'>
";
}
if($have_attach) // there are attachment fields on this form
    $have_attach = 'enctype="multipart/form-data" '; // for <form post

if ($si_contact_opt['border_enable'] == 'true') {
  $string .= '
    <form '.$have_attach.'action="'.esc_url( $form_action_url ).'#FSContact'.$form_id_num.'" id="si_contact_form'.$form_id_num.'" method="post">
    <fieldset '.$this->ctf_border_style.'>

';
  if ($si_contact_opt['title_border'] != '')
        $string .= '      <legend>'.esc_html($si_contact_opt['title_border']).'</legend>';
} else {

 $string .= '
<form '.$have_attach.'action="'.esc_url( $form_action_url ).'#FSContact'.$form_id_num.'" id="si_contact_form'.$form_id_num.'" method="post">
';
}

// check attachment directory
$have_attach_error = 0;
if ($have_attach){
	$attach_dir = WP_PLUGIN_DIR . '/si-contact-form/attachments/';
    $this->si_contact_init_temp_dir($attach_dir);
    if ($si_contact_opt['php_mailer_enable'] == 'php'){
       $have_error = 1;
       $have_attach_error = 1;
	   $fsc_error_message['attach_dir_error'] = __('Attachments are only supported when the Send E-Mail function is set to WordPress. You can find this setting on the contact form edit page.', 'si-contact-form');
    }
	if ( !is_dir($attach_dir) ) {
        $have_error = 1;
        $have_attach_error = 1;
		$fsc_error_message['attach_dir_error'] = __('The temporary folder for the attachment field does not exist.', 'si-contact-form');
    } else if(!is_writable($attach_dir)) {
          $have_error = 1;
          $have_attach_error = 1;
		  $fsc_error_message['attach_dir_error'] = __('The temporary folder for the attachment field is not writable.', 'si-contact-form');
    }
}

// print any input errors
if ($have_error) {
    $string .= '<div '.$this->ctf_required_style.'>
    <div '.$this->ctf_error_style.'>
';
    $string .= esc_html(($si_contact_opt['error_correct'] != '') ? $si_contact_opt['error_correct'] : __('Please make corrections below and try again.', 'si-contact-form'));
    $string .= '
    </div>
</div>
';

// print attach error if there is one
if($have_attach && $have_attach_error ) {
      $string .= '<div '.$this->ctf_required_style.'>
      <div '.$this->ctf_error_style.'>
';
      $string .= esc_html($fsc_error_message['attach_dir_error']);
      $string .= '
      </div>
</div>
';
    }
     if ( !$this->isCaptchaEnabled() && $this->si_contact_error_var('captcha',$display_only) != '' ) {
      // honeypot without captcha
$string .= '<div '.$this->ctf_required_style.'>
      <div '.$this->ctf_error_style.'>
';
      $string .= esc_html($this->si_contact_error_var('captcha',$display_only));
      $string .= '
      </div>
</div>
';

     }

}
if (empty($contacts)) {
   $string .= '<div '.$this->ctf_required_style.'>
   <div '.$this->ctf_error_style.'>'.__('ERROR: Misconfigured E-mail address in options.', 'si-contact-form').'
   </div>
</div>
';
}

if ($si_contact_opt['req_field_label_enable'] == 'true' && $si_contact_opt['req_field_indicator_enable'] == 'true' ) {
   $string .=  '<div '.$this->ctf_required_style.'>
';

   $string .= ($si_contact_opt['tooltip_required'] != '') ? $si_contact_opt['req_field_indicator'].' ' . esc_html($si_contact_opt['tooltip_required']) : $si_contact_opt['req_field_indicator'].' '. esc_html(__('(denotes required field)', 'si-contact-form'));
   $string .= '
   </div>
';
}

// allow shortcode hidden fields
if ( $_SESSION["fsc_shortcode_hidden_$form_id_num"] != '') {
   $hidden_fields_test = explode(",",$_SESSION["fsc_shortcode_hidden_$form_id_num"]);
   if ( !empty($hidden_fields_test) ) {
      foreach($hidden_fields_test as $line) {
         if(preg_match("/=/", $line) ) {
            list($key, $value) = explode("=",$line);
            $key   = trim($key);
            $value = trim($value);
            if ($key != '' && $value != '') {
              $string .= '
         <div>
               <input type="hidden" name="'.esc_attr($key).'" value="'.esc_attr($value).'" />
        </div>
';
           }
       }
     }
   }
}

if (count($contacts) > 1 && $mail_to == '' ) { // $mail_to can come from shortcode, it overrides

     $string .= '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_CID'.$form_id_num.'">';
     $string .= esc_html(($si_contact_opt['title_dept'] != '') ? $si_contact_opt['title_dept'] : __('Department to Contact:', 'si-contact-form'));
     $string .= $req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>
                '.$this->ctf_echo_if_error($this->si_contact_error_var('contact',$display_only)).'
                <select '.$this->ctf_select_style.' id="si_contact_CID'.$form_id_num.'" name="si_contact_CID" '.$this->ctf_aria_required.'>
';
    $string .= '                <option value="">';
    $string .= esc_html(($si_contact_opt['title_select'] != '') ? $si_contact_opt['title_select'] : __('Select', 'si-contact-form'));
    $string .= '</option>
';

    $cid = $this->si_contact_post_var('si_contact_CID',$display_only);
    //echo "cid:$mail_to"; exit;
    if ( $cid == '' && isset($_GET[$form_id_num .'mailto_id']) ) {
        $cid = (int)$this->si_contact_get_var($form_id_num,'mailto_id');
    }else if ( $cid == '' && isset($_GET['si_contact_CID']) ){
        $cid = (int)$_GET['si_contact_CID']; // legacy code
    }

     $selected = '';

      foreach ($contacts as $k => $v)  {
          if (!empty($cid) && $cid == $k) {
                    $selected = ' selected="selected"';
          }
          $string .= '                <option value="' . esc_attr($k) . '"' . $selected . '>' . esc_html($v['CONTACT']) . '</option>
';
          $selected = '';
      }

      $string .= '                </select>
      </div>
';
}
else {

     $string .= '
         <div>
               <input type="hidden" name="si_contact_CID" value="1" />
        </div>
';

}

// find logged in user's WP email address (auto form fill feature):
// http://codex.wordpress.org/Function_Reference/get_currentuserinfo
if ($email == '') {
  if (
  $user_ID != '' &&
  $current_user->user_login != 'admin' &&
  !current_user_can('level_10') &&
  $si_contact_opt['auto_fill_enable'] == 'true'
  ) {
     //user logged in (and not admin rights) (and auto_fill_enable set in options)
     $email = $current_user->user_email;
     $email2 = $current_user->user_email;
     if ($name == '') {
        $name = $current_user->user_login;
     }
  }
}

if($si_contact_opt['name_type'] != 'not_available' ) {

     $f_name_string = '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_f_name'.$form_id_num.'">';
     $f_name_string .= esc_html(($si_contact_opt['title_fname'] != '') ? $si_contact_opt['title_fname'] : __('First Name:', 'si-contact-form'));
     if($si_contact_opt['name_type'] == 'required' )
           $f_name_string .= $req_field_ind;
     $f_name_string .= '</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error($this->si_contact_error_var('f_name',$display_only)).'
                <input '.$this->ctf_field_style.' type="text" id="si_contact_f_name'.$form_id_num.'" name="si_contact_f_name" value="' . esc_attr($f_name) .'" '.$this->ctf_aria_required.' size="'.esc_attr($ctf_field_size).'" />
        </div>';

     $l_name_string = '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_l_name'.$form_id_num.'">';
     $l_name_string .= esc_html(($si_contact_opt['title_lname'] != '') ? $si_contact_opt['title_lname'] : __('Last Name:', 'si-contact-form'));
     if($si_contact_opt['name_type'] == 'required' )
           $l_name_string .= $req_field_ind;
     $l_name_string .= '</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error($this->si_contact_error_var('l_name',$display_only)).'
                <input '.$this->ctf_field_style.' type="text" id="si_contact_l_name'.$form_id_num.'" name="si_contact_l_name" value="' . esc_attr($l_name) .'" '.$this->ctf_aria_required.' size="'.esc_attr($ctf_field_size).'" />
        </div>
';


    switch ($si_contact_opt['name_format']) {
       case 'name':

$string .= '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_name'.$form_id_num.'">';
     $string .= esc_html(($si_contact_opt['title_name'] != '') ? $si_contact_opt['title_name'] : __('Name:', 'si-contact-form'));
     if($si_contact_opt['name_type'] == 'required' )
           $string .= $req_field_ind;
     $string .= '</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error($this->si_contact_error_var('name',$display_only)).'
                <input '.$this->ctf_field_style.' type="text" id="si_contact_name'.$form_id_num.'" name="si_contact_name" value="' . esc_attr($name) .'" '.$this->ctf_aria_required.' size="'.esc_attr($ctf_field_size).'" />
        </div>
';

      break;
      case 'first_last':

     $string .= $f_name_string;
     $string .= $l_name_string;

      break;
      case 'first_middle_i_last':

     $string .= $f_name_string;

$string .= '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_mi_name'.$form_id_num.'">';
     $string .= esc_html(($si_contact_opt['title_miname'] != '') ? $si_contact_opt['title_miname'] : __('Middle Initial:', 'si-contact-form'));
     $string .= '</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error($this->si_contact_error_var('mi_name',$display_only)).'
                <input '.$this->ctf_field_style.' type="text" id="si_contact_mi_name'.$form_id_num.'" name="si_contact_mi_name" value="' . esc_attr($mi_name) .'" '.$this->ctf_aria_required.' size="2" />
        </div>';

     $string .= $l_name_string;

      break;
      case 'first_middle_last':

     $string .= $f_name_string;

$string .= '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_m_name'.$form_id_num.'">';
     $string .= esc_html(($si_contact_opt['title_mname'] != '') ? $si_contact_opt['title_mname'] : __('Middle Name:', 'si-contact-form'));
     $string .= '</label>
        </div>
        <div '.$this->ctf_field_div_style.'>
                <input '.$this->ctf_field_style.' type="text" id="si_contact_m_name'.$form_id_num.'" name="si_contact_m_name" value="' . esc_attr($m_name) .'" '.$this->ctf_aria_required.' size="'.esc_attr($ctf_field_size).'" />
        </div>';

     $string .= $l_name_string;

      break;
    }
}
if($si_contact_opt['email_type'] != 'not_available' ) {
 if ($ctf_enable_double_email == 'true') {
   $string .= '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_email'.$form_id_num.'">';
     $string .= esc_html(($si_contact_opt['title_email'] != '') ? $si_contact_opt['title_email'] : __('E-Mail Address:', 'si-contact-form'));
     if($si_contact_opt['email_type'] == 'required' )
           $string .= $req_field_ind;
     $string .= '</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error($this->si_contact_error_var('email',$display_only)).
        $this->ctf_echo_if_error($this->si_contact_error_var('double_email',$display_only)).'
                <input '.$this->ctf_field_style.' type="text" id="si_contact_email'.$form_id_num.'" name="si_contact_email" value="' . esc_attr($email) . '" '.$this->ctf_aria_required.' size="'.esc_attr($ctf_field_size).'" />
        </div>
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_email2_'.$form_id_num.'">';
     $string .= esc_html(($si_contact_opt['title_email2'] != '') ? $si_contact_opt['title_email2'] : __('E-Mail Address again:', 'si-contact-form'));
     $string .= $req_field_ind.'</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error($this->si_contact_error_var('email2',$display_only)).'
                <span style="font-size:x-small; font-weight:normal;">';
     $string .= esc_html(($si_contact_opt['title_email2_help'] != '') ? $si_contact_opt['title_email2_help'] : __('Please enter your E-mail Address a second time.', 'si-contact-form'));
     $string .= '</span><br />
                 <input '.$this->ctf_field_style.' type="text" id="si_contact_email2_'.$form_id_num.'" name="si_contact_email2" value="' . esc_attr($email2) . '" '.$this->ctf_aria_required.' size="'.esc_attr($ctf_field_size).'" />
        </div>
';

  } else {
    $string .= '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_email'.$form_id_num.'">';
     $string .= esc_html(($si_contact_opt['title_email'] != '') ? $si_contact_opt['title_email'] : __('E-Mail Address:', 'si-contact-form'));
     if($si_contact_opt['email_type'] == 'required' )
           $string .= $req_field_ind;
     $string .= '</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error($this->si_contact_error_var('email',$display_only)).'
                <input '.$this->ctf_field_style.' type="text" id="si_contact_email'.$form_id_num.'" name="si_contact_email" value="' . esc_attr($email) . '" '.$this->ctf_aria_required.' size="'.esc_attr($ctf_field_size).'" />
        </div>
';
  }
}

if ($si_contact_opt['ex_fields_after_msg'] != 'true') {
     // are there any optional extra fields/

     for ($i = 1; $i <= $si_contact_opt['max_fields']; $i++) {
        if ($si_contact_opt['ex_field'.$i.'_label'] != '') {
           // include the code to display extra fields
           include(WP_PLUGIN_DIR . '/si-contact-form/si-contact-form-ex-fields.php');
           break;
        }
      }
}

if($si_contact_opt['subject_type'] != 'not_available' ) {
   if (count($subjects) > 0) {

       $string .= '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_subject_ID'.$form_id_num.'">';
     $string .= esc_html(($si_contact_opt['title_subj'] != '') ? $si_contact_opt['title_subj'] : __('Subject:', 'si-contact-form'));
     if($si_contact_opt['subject_type'] == 'required' )
           $string .= $req_field_ind;
     $string .= '</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error($this->si_contact_error_var('subject',$display_only)).'

                <select '.$this->ctf_select_style.' id="si_contact_subject_ID'.$form_id_num.'" name="si_contact_subject_ID" '.$this->ctf_aria_required.'>
';

    $string .= '               <option value="">';
    $string .= esc_html(($si_contact_opt['title_select'] != '') ? $si_contact_opt['title_select'] : __('Select', 'si-contact-form'));
    $string .= '</option>
';
    $sid = '';
    $subject = '';
    if( isset($_POST['si_contact_subject_ID']) )
      $sid = (int)$this->si_contact_post_var('si_contact_subject_ID',$display_only);

    if ( $sid == '' && isset($_GET[$form_id_num .'subject_id']) ) {
        $sid = (int)$this->si_contact_get_var($form_id_num,'subject_id');
    } else if ( $sid == '' && isset($_GET['si_contact_SID']) ){
        $sid = (int)$_GET['si_contact_SID']; // legacy code
    }
    if ( $sid != '' && $sid > 0  )
      $subject = $this->ctf_clean_input($subjects[$sid]);

    $selected = '';

      foreach ($subjects as $k => $v)  {
          if (!empty($sid) && $sid == $k) {
                    $selected = ' selected="selected"';
          }
          $string .= '                        <option value="' . esc_attr($k) . '"' . $selected . '>' . esc_html($v) . '</option>
      ';
          $selected = '';
      }

      $string .= '               </select>';

       } else {
            // text entry subject
            if(isset($_POST['si_contact_subject']) && !$display_only)
                  $subject = $this->ctf_name_case($this->ctf_clean_input($_POST['si_contact_subject']));
              if ( $subject != '' ) {
                $subject = substr($subject,0,75); // shorten to 75 chars or less
              }
            $string .= '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_subject'.$form_id_num.'">';
     $string .= ($si_contact_opt['title_subj'] != '') ? $si_contact_opt['title_subj'] : __('Subject:', 'si-contact-form');
     if($si_contact_opt['subject_type'] == 'required' )
           $string .= $req_field_ind;
     $string .= '</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error($this->si_contact_error_var('subject',$display_only)).'
                <input '.$this->ctf_field_style.' type="text" id="si_contact_subject'.$form_id_num.'" name="si_contact_subject" value="' . $this->ctf_output_string($subject) . '" '.$this->ctf_aria_required.' size="'.$ctf_field_size.'" />';
       }

        $string .= '
        </div>
';
}

if($si_contact_opt['message_type'] != 'not_available' ) {
$string .= '
        <div '.$this->ctf_title_style.'>
                <label for="si_contact_message'.$form_id_num.'">';
     $string .= ($si_contact_opt['title_mess'] != '') ? $si_contact_opt['title_mess'] : __('Message:', 'si-contact-form');
     if($si_contact_opt['message_type'] == 'required' )
           $string .= $req_field_ind;
     $string .= '</label>
        </div>
        <div '.$this->ctf_field_div_style.'>'.$this->ctf_echo_if_error($this->si_contact_error_var('message',$display_only)).'
                <textarea '.$this->ctf_field_style.' id="si_contact_message'.$form_id_num.'" name="si_contact_message" '.$this->ctf_aria_required.' cols="'.absint($si_contact_opt['text_cols']).'" rows="'.absint($si_contact_opt['text_rows']).'">' . $this->ctf_output_string($message) . '</textarea>
        </div>
';
}

if ($si_contact_opt['ex_fields_after_msg'] == 'true') {
     // are there any optional extra fields/
     for ($i = 1; $i <= $si_contact_opt['max_fields']; $i++) {
        if ($si_contact_opt['ex_field'.$i.'_label'] != '') {
           // include the code to display extra fields
           include(WP_PLUGIN_DIR . '/si-contact-form/si-contact-form-ex-fields.php');
           break;
        }
      }
}

 $this->ctf_submit_div_style = $this->si_contact_convert_css($si_contact_opt['submit_div_style']);
 $this->ctf_submit_style = $this->si_contact_convert_css($si_contact_opt['button_style']);
 $this->ctf_reset_style = $this->si_contact_convert_css($si_contact_opt['reset_style']);
// captcha is optional but recommended to prevent spam bots from spamming your contact form

if ( $this->isCaptchaEnabled() ) {
  $string .= $this->si_contact_get_captcha_html($form_id_num,$display_only)."
";
}

   if($si_contact_opt['honeypot_enable'] == 'true' ) {
      // hidden empty honeypot field
      $string .= '        <div style="display:none;">
          <label for="email_'.$form_id_num.'"><small>'.__('Leave this field empty', 'si-contact-form').'</small></label>
          <input type="text" name="email_'.$form_id_num.'" id="email_'.$form_id_num.'" value="" />
        </div>
';
      // server-side timestamp forgery token.
      $string .= '    <input type="hidden" name="si_tok_'.$form_id_num.'" value="'. wp_hash( time() ).','.time() .'" />
';
   }
// server-side no back button mail again token.
      $string .= '    <input type="hidden" name="si_postonce_'.$form_id_num.'" value="'. wp_hash( time() ).','.time() .'" />
';

$string .= '
<div '.$this->ctf_submit_div_style.'>
  <input type="hidden" name="si_contact_action" value="send" />
  <input type="hidden" name="si_contact_form_id" value="'.$form_id_num.'" />
  <input type="submit" id="fsc-submit-'.$form_id_num.'" '.$this->ctf_submit_style.' value="';
     $string .= esc_attr(($si_contact_opt['title_submit'] != '') ? $si_contact_opt['title_submit'] :  __('Submit', 'si-contact-form'));
     $string .= '" ';
   if($si_contact_opt['enable_areyousure'] == 'true') {
     $string .= ' onclick="return confirm(\'';
     $string .= esc_js(($si_contact_opt['title_areyousure'] != '') ? $si_contact_opt['title_areyousure'] : __('Are you sure?', 'si-contact-form'));
     $string .= '\')" ';
    }
     $string .= '/> ';
   if($si_contact_opt['enable_reset'] == 'true') {
     $string .= '<input type="reset" id="fsc-reset-'.$form_id_num.'" '.$this->ctf_reset_style.' value="';
     $string .= esc_attr(($si_contact_opt['title_reset'] != '') ?  $si_contact_opt['title_reset'] : __('Reset', 'si-contact-form'));
     $string .= '" onclick="return confirm(\'';
     $string .= esc_js(__('Do you really want to reset the form?', 'si-contact-form'));
     $string .= '\')"  />
';
    }

$string .= '
</div>
';
if ($si_contact_opt['border_enable'] == 'true') {
  $string .= '
    </fieldset>
  ';
}
$string .= '
</form>
';
if ($si_contact_opt['enable_credit_link'] == 'true') {
  $this->ctf_powered_by_style = $this->si_contact_convert_css($si_contact_opt['powered_by_style']);
$string .= '
<p '.$this->ctf_powered_by_style.'>'.__('Powered by', 'si-contact-form'). ' <a href="http://wordpress.org/extend/plugins/si-contact-form/" target="_blank">'.__('Fast Secure Contact Form', 'si-contact-form'). '</a></p>
';
}

$string .= '</div>';

/* --- vCita Scheduler Display - Start --- */
if ($si_contact_opt['vcita_enabled'] == 'true') {
		$confirmation_token = $this->vcita_should_store_expert_confirmation_token($si_contact_opt);
		
		$string .= "
<div class='fscf_vcita_container' ";
		$string .= empty($confirmation_token) ? "" : "confirmation_token='".$confirmation_token;
		$string .= (empty($si_contact_opt['vcita_uid']) ? "preview='true" : " vcita_uid = '").$si_contact_opt['vcita_uid']."'>
</div>";
        $string .= "
<div style='clear:both;'></div>
"; // "Reset" the float properties
        $string .= '</div>';
       /* --- vCita Scheduler Display - End --- */
}
$string .= '
<!-- Fast Secure Contact Form plugin '.esc_attr($this->ctf_version).' - end - FastSecureContactForm.com -->';
?>
<?php
/*
Plugin Name: Fast Secure Contact Form
Plugin URI: http://www.FastSecureContactForm.com/
Description: Fast Secure Contact Form for WordPress. The contact form lets your visitors send you a quick E-mail message. Super customizable with a multi-form feature, optional extra fields, and an option to redirect visitors to any URL after the message is sent. Includes CAPTCHA and Akismet support to block all common spammer tactics. Spam is no longer a problem. <a href="plugins.php?page=si-contact-form/si-contact-form.php">Settings</a> | <a href="http://www.FastSecureContactForm.com/donate">Donate</a>
Version: 3.1.9.1
Author: Mike Challis
Author URI: http://www.642weather.com/weather/scripts.php
*/

$ctf_version = '3.1.9.1';

/*  Copyright (C) 2008-2013 Mike Challis  (http://www.fastsecurecontactform.com/contact)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// settings get deleted when plugin is deleted from admin plugins page
// this must be outside the class or it does not work
function si_contact_unset_options() {

  delete_option('si_contact_form');
  delete_option('si_contact_form_gb');

  // multi-forms (a unique configuration for each contact form)
  for ($i = 2; $i <= 100; $i++) {
    delete_option("si_contact_form$i");
  }
} // end function si_contact_unset_options

if (!class_exists('siContactForm')) {

 class siContactForm {

     var $si_contact_error;
     var $uploaded_files;
     var $ctf_notes_style;
     var $ctf_version;
     var $vcita_add_script;

function si_contact_add_tabs() {
    add_submenu_page('plugins.php', __('FS Contact Form Options', 'si-contact-form'), __('FS Contact Form Options', 'si-contact-form'), 'manage_options', __FILE__,array(&$this,'si_contact_options_page'));
	
}

function si_contact_update_lang() {
  global $si_contact_opt, $si_contact_option_defaults;

   // a few language options need to be re-translated now.
   // had to do this becuse the options were actually needed to be set before the language translator was initialized

  // update translation for these options (for when switched from English to another lang)
  if ($si_contact_opt['welcome'] == '<p>Comments or questions are welcome.</p>' ) {
     $si_contact_opt['welcome'] = __('<p>Comments or questions are welcome.</p>', 'si-contact-form');
     $si_contact_option_defaults['welcome'] = $si_contact_opt['welcome'];
  }

  if ($si_contact_opt['email_to'] == 'Webmaster,'.get_option('admin_email')) {
       $si_contact_opt['email_to'] = __('Webmaster', 'si-contact-form').','.get_option('admin_email');
       $si_contact_option_defaults['email_to'] = $si_contact_opt['email_to'];
  }

  if ($si_contact_opt['email_subject'] == get_option('blogname') . ' ' .'Contact:') {
      $si_contact_opt['email_subject'] =  get_option('blogname') . ' ' .__('Contact:', 'si-contact-form');
      $si_contact_option_defaults['email_subject'] = $si_contact_opt['email_subject'];
  }

} // end function si_contact_update_lang

function si_contact_options_page() {
  global $captcha_url_cf, $si_contact_opt, $si_contact_gb, $si_contact_gb_defaults, $si_contact_option_defaults, $ctf_version;

  require_once(WP_PLUGIN_DIR . '/si-contact-form/admin/si-contact-form-admin.php');

} // end function si_contact_options_page

/* --- vCita Admin Functions - Start ---  */

/**
 * Add the vcita Javascript to the admin section
 */
function vcita_add_admin_js() {

	if(isset($_GET['page']) && is_string($_GET['page']) && preg_match('/si-contact-form.php$/',$_GET['page']) ) {

          $form_num = $this->si_contact_form_num();
          $si_contact_opt = get_option("si_contact_form$form_num");
          if ($si_contact_opt['vcita_enabled'] == 'false') {  // Mike challis added  01/15/2013
            return;   // prevent setting vcita cookies in admin if vcita is disabled on the form
          }
		  wp_enqueue_script('jquery');
		  wp_register_script('vcita_fscf', plugins_url('vcita/vcita_fscf.js', __FILE__), array('jquery'), '1.1', true);
		  wp_register_script('vcita_fscf_admin', plugins_url('vcita/vcita_fscf_admin.js', __FILE__), array('jquery'), '1.1', true);
		  
		  wp_print_scripts('vcita_fscf');
          wp_print_scripts('vcita_fscf_admin');

	}
}

/**
 * Validate the user is initialized correctly by performing the following:
 * 1. Migration from old versions.
 * 2. New User - enable vCita if the auto install flag is set to true  
 * 3. Upgrade - enable vCita if wasn't previously disabled - Currently nothing is done
 */
function vcita_validate_initialized_user($form_num, $form_params, $general_params, $previous_version) {
    $auto_install = $general_params['vcita_auto_install'];
    $curr_version = $general_params['ctf_version'];
    $vcita_dismiss = $general_params['vcita_dismiss'];
    
    // Check if a initializtion is required
    if (!isset($form_params['vcita_initialized']) || $form_params['vcita_initialized'] == 'false') {
        // New Install - Only enable vCita 
        // This will cause the notification about misconfigured installation be shown.
         
        if ($auto_install == 'true' && $vcita_dismiss == "false") {
            $form_params['vcita_enabled'] = 'true';
        }
        
        // Currently nothing during upgrade.
    
        $form_params['vcita_initialized'] = 'true'; // Mark as initialized
        update_option("si_contact_form$form_num", $form_params);
    }
    
    $confirm_token = '';
    if (isset($form_params['vcita_confirm_token']))
        $confirm_token = $form_params['vcita_confirm_token'];
    
    // Migrate token to the new field
    if (!empty($confirm_token) && !empty($form_params['vcita_uid'])) {
        $form_params['vcita_confirm_tokens'] = '';
        $form_params = $this->vcita_set_confirmation_token($form_params, $confirm_token);

        $form_params['vcita_confirm_token'] = null;
        update_option("si_contact_form$form_num", $form_params);
    }
    
    // check if the approved flag should be turned on, happens when: 
    // When user available, enabled and approve is false (this can only happen if form is an old version)
    if (isset($form_params['vcita_enabled']) && $form_params['vcita_enabled'] == 'true' && 
        isset($form_params['vcita_uid']) && !empty($form_params['vcita_uid']) && 
        (!isset($form_params['vcita_approved']) || $form_params['vcita_approved'] == 'false')) {
        
        $form_params['vcita_approved'] = 'true';
        update_option("si_contact_form$form_num", $form_params);
    }
    
    return $form_params;
}

/**
 * Use the vCita API to get a user, either create a new one or get the id of an available user
 * In case the "default" email is used, no action takes place.
 * 
 * @return array of the user name, id and if he finished the registration or not
 */
function vcita_generate_or_validate_user($params) {

    $used_email = $params['vcita_email'];
    
	// Don't create / validate if this isn't the expert
	if (empty($_SESSION) || empty($_SESSION["vcita_expert"]) || !$_SESSION["vcita_expert"]) {
		return $params;
	}
	
	// Only generate a user if the mail isn't the default one.
	if ($used_email == 'mail@example.com') {
		$params['vcita_uid'] = '';
		
		return $params;
	} 
	
	extract($this->vcita_post_contents("http://www.vcita.com/api/experts?id=".$params['vcita_uid'].
	                                   "&email=".urlencode($used_email).
	                                   "&first_name=".urlencode($params['vcita_first_name'])."&last_name=".
	                                   urlencode($params['vcita_last_name'])."&ref=wp-fscf&o=int.1"));

	return $this->vcita_parse_user_info($params, $success, $raw_data);
}

/* 
 * Parse the result from the vCita API.
 * Update all the parameters with the given values / error.
 */
function vcita_parse_user_info($params, $success, $raw_data) {
    $previous_id = isset($params['vcita_uid']) ? $params['vcita_uid'] : '';
    $params['vcita_initialized'] = 'false';
	$params['vcita_uid'] = '';
	
	if (!$success) {
		$params['vcita_last_error'] = "Temporary problem, please try again later";
	} else {
		$data = json_decode($raw_data);
		
		if ($data->{'success'} == 1) {
			$params['vcita_confirmed'] = $data->{'confirmed'};
			$params['vcita_last_error'] = "";
			$params['vcita_uid'] = $data->{'id'};
			$params['vcita_initialized'] = 'true';
			$params['vcita_first_name'] = $data->{'first_name'};
			$params['vcita_last_name'] = $data->{'last_name'};
			
			if ($previous_id != $data->{'id'}) {
				$params = $this->vcita_set_confirmation_token($params, $data->{'confirmation_token'});
			}
			
			if (isset($data->{'email'}) && !empty($data->{'email'})) {
			    $params['vcita_email'] = $data->{'email'};
			}
			
		} else {
			$params['vcita_last_error'] = $data-> {'error'};
		}
	}
	
	return $params;
}

/**
 * Disconnect the user from vCita by removing his details.
 */
function vcita_disconnect_form($form_params) {
    global $si_contact_option_defaults;
    
     $form_params['vcita_approved']    = $si_contact_option_defaults['vcita_approved'];
     $form_params['vcita_uid']         = $si_contact_option_defaults['vcita_uid'];
     $form_params['vcita_email']       = $si_contact_option_defaults['vcita_email'];
     $form_params['vcita_first_name']  = $si_contact_option_defaults['vcita_first_name'];
     $form_params['vcita_last_name']   = $si_contact_option_defaults['vcita_last_name'];
     $form_params['vcita_initialized'] = 'true'; // Don't re-enable next time
     
     // On Purpose keeping the confirmation_tokens

     return $form_params;
}

/**
 * Perform an HTTP POST Call to retrieve the data for the required content.
 *
 * @param $url
 * @return array - raw_data and a success flag
 */
function vcita_post_contents($url) {
    $response  = wp_remote_post($url, array('header' => array('Accept' => 'application/json; charset=utf-8'),
                                          'timeout' => 10));

    return $this->vcita_parse_response($response);
}

/**
 * Perform an HTTP GET Call to retrieve the data for the required content.
 * 
 * @param $url
 * @return array - raw_data and a success flag
 */
function vcita_get_contents($url) {
    $response = wp_remote_get($url, array('header' => array('Accept' => 'application/json; charset=utf-8'),
                                          'timeout' => 10));

    return $this->vcita_parse_response($response);
}

/**
 * Parse the HTTP response and return the data and if was successful or not.
 */
function vcita_parse_response($response) {
    $success = false;
    $raw_data = "Unknown error";
    
    if (is_wp_error($response)) {
        $raw_data = $response->get_error_message();
    
    } elseif (!empty($response['response'])) {
        if ($response['response']['code'] != 200) {
            $raw_data = $response['response']['message'];
        } else {
            $success = true;
            $raw_data = $response['body'];
        }
    }
    
    return compact('raw_data', 'success');
}

/**
 * Add the dynamic notification area based on the current user status
 * 
 * This notification is for the Meeting scheduler section (Not for page header notifications)
 */
function vcita_add_notification($params) {
	$confirmation_token = $this->vcita_get_confirmation_token($params);
	
	if ($params['vcita_enabled'] == 'false') {
		$message = '<b>Meeting Scheduler is disabled</b>, please check the box below to allow users to request meetings via your contact form';
		$message_type = "fsc-notice";
		
	} elseif (!empty($params['vcita_last_error'])) {
        $message = $params['vcita_last_error'];
        $message_type = "fsc-error";
		
    } elseif (!empty($params['vcita_uid'])) {
	    $message_type = "fsc-notice";
		$message = "vCita Meeting Scheduler is <font style='color:green;font-weight:bold;'>active</font><br/>";
		
        if (!$params['vcita_confirmed'] && !empty($confirmation_token)) {
		    $message .= "<br/>Click below to set your meeting options and availability".
						"<div style='margin-top:10px;'><a href='http://www.vcita.com/users/confirmation?force=true&amp;non_avail=continue&amp;confirmation_token=".$this->vcita_get_confirmation_token($params)."&amp;o=int.2' target='_blank'><img src=".plugins_url( 'vcita/vcita_configure.png' , __FILE__ )." height='41px' width='242px' /></a></div>";
			$message_type = "fsc-error";
	    } elseif (!empty($params['vcita_last_name'])) {
	        $message .= "<b>Active account: </b>".$params['vcita_first_name']." ".$params['vcita_last_name'];
	    }
    } elseif ($this->vcita_get_email($params) == 'mail@example.com') {
		$message = "You are currently using the default mail: <b>mail@example.com</b>, To activate - please enter you email below.";
		$message_type = "fsc-notice";
		
	} elseif ($params['vcita_enabled'] == 'true') {
	    $message = "Please configure your vCita Meeting Scheduler below.";
		$message_type = "fsc-notice";
	} 
	
    echo "<br/><div class=".$message_type.">".$message."</div>";
	
	echo "<div style='clear:both;display:block'></div>";
}

/**
 * Location for the vcita banner
 */
function vcita_banner_location() {
	return plugins_url( 'vcita/vcita_banner.jpg' , __FILE__ );
}

/**
 * Add the vCita advanced configuraion links to user admin.
 * Show the settings only if the user is available
 */
function vcita_add_config($params) {
	// Only show the Edit link in case the user is available
	if (!empty($params["vcita_uid"]) && $params['vcita_enabled'] == 'true') {
		$confirmation_token = $this->vcita_get_confirmation_token($params);
		
		$vcita_curr_notifcation = "<div style='clear:both;float:left;text-align:left;display:block;padding:5px 0 10px 0;width:100%;'>";
		
		
		if ($params['vcita_confirmed']) {
		    $vcita_curr_notifcation .= "
                 <div style='margin-right:10px;float:left;'><a href='http://www.vcita.com/settings?section=profile' target='_blank'>Edit Profile</a></div>
                 <div style='margin-right:10px;float:left;'><a href='http://www.vcita.com/settings?section=configuration' target='_blank'>Edit Meeting Preferences</a></div>
                 <div style='margin-right:10px;float:left;'>
                     <input style='display:none;' id='vcita_disconnect_button' type='submit' name='vcita_disconnect'/>
                    <a id='vcita_fscf_disconnected_button' href='#' onclick='document.formoptions.vcita_disconnect_button.click();return false;' target='_blank'>Change Account</a>
                 </div>";
				 
		} elseif (empty($confirmation_token)) {
		    $vcita_curr_notifcation .= "
			    <div style='margin-right:10px;float:left;'>
			     <a href='http://www.vcita.com/users/send_password_instructions?activation=true&email=".$this->vcita_get_email($params)."' target='_blank'>Configure your account</a></div>
			     <div style='margin-right:5px;float:left;'>
                    <input style='display:none;' id='vcita_disconnect_button' type='submit' name='vcita_disconnect'/>
                   <a id='vcita_fscf_disconnected_button' href='#' onclick='document.formoptions.vcita_disconnect_button.click();return false;' target='_blank'>Change Account</a>
                </div>";
		} else {
		    $vcita_curr_notifcation .= "
		        <div style='margin-right:5px;float:left;'>
        		    <input style='display:none;' id='vcita_disconnect_button' type='submit' name='vcita_disconnect''/>
		            <a id='vcita_fscf_disconnected_button' href='#' onclick='document.formoptions.vcita_disconnect_button.click();return false;' target='_blank'>Change Account</a>
		        </div>";
		}
		
		$vcita_curr_notifcation .= "</div>";
		
		echo $vcita_curr_notifcation;
	}
}

/**
 * Print the notification for the admin page for the main plugins page or the fast secure page
 * 
 */
function vcita_print_admin_page_notification($si_contact_global_tmp = null, $form_params = null, $form_num = "", $internal_page = false) {
    $form_used = isset($form_params["vcita_enabled"]) && $form_params["vcita_enabled"] == "true";
     
    // Don't do anything if dismissed
    if (isset($si_contact_global_tmp["vcita_dismiss"]) && $si_contact_global_tmp["vcita_dismiss"] == "true" && !$form_used) {
        return false;
    }
    
    $notification_created = false;
    $prefix = ($internal_page) ? "" : "<p><b>Fast Secure Contact Form - </b>";
    $suffix = ($internal_page) ? "" : "</p>"; 
    $class = ($internal_page) ? "fsc-error" : "error"; 
    $origin = ($internal_page) ? "&amp;o=int.3" : "&amp;o=int.5";
    $notification_created = true;
    
    $vcita_section_url = admin_url( "plugins.php?ctf_form_num=$form_num&amp;page=si-contact-form/si-contact-form.php#vCitaSettings");
    $vcita_dismiss_url = admin_url( "plugins.php?vcita_dismiss=true&amp;ctf_form_num=$form_num&amp;page=si-contact-form/si-contact-form.php");
    
    // Show if empty, missing details, or internal page, vcita not used and upgrade 
    if (empty($form_params) || 
        $this->vcita_should_notify_missing_details($form_params) || 
        ($internal_page && !$this->vcita_is_being_used() && $this->vcita_should_show_when_not_used($si_contact_global_tmp))) {

        echo "<div id='si-fscf-vcita-warning' class='".$class."'>".$prefix."vCita Meeting Scheduler is active but some settings are missing. <a href='".esc_url($vcita_section_url)."'>Click here to SETUP the use of vCita</a>, or <a href='".esc_url($vcita_dismiss_url)."'>Disable vCita.</a>".$suffix."</div>";

    } elseif ($internal_page && $this->vcita_should_complete_registration($form_params)) {
        $vcita_complete_url = "http://www.vcita.com/users/confirmation?force=true&non_avail=continue&confirmation_token=".$this->vcita_get_confirmation_token($form_params).$origin."' target='_blank";
        
        if (!$internal_page) { // direct outside pages to vCita section (This currently won't happen but keeping for future use.) 
            $vcita_complete_url = $vcita_section_url;
        }

        echo "<div id='si-fscf-vcita-warning' class='".$class."'>".$prefix."vCita Meeting Scheduler is active but some settings are missing. <a href='".esc_url($vcita_complete_url)."'>Click here to SETUP the use of vCita</a>, or <a href='".esc_url($vcita_section_url)."'>Disable vCita</a>".$suffix."</div>";
        
    } elseif (!empty($params["vcita_last_error"])) {
        echo "<div class='".$class."'>".$prefix."<strong>"._e('Meeting Scheduler - '.$si_contact_opt["vcita_last_error"], 'si-contact-form')."</strong>".$suffix."</div>";
        
    } else {
        $notification_created = false;
    }
    
    return $notification_created;
}

/**
 * Check if registration for the given form wasn't completed yet.
 */
function vcita_should_complete_registration($form_params) {
	$vcita_confirmation_token = $this->vcita_get_confirmation_token($form_params);
	return isset($form_params['vcita_uid']) && !empty($form_params['vcita_uid']) && $form_params['vcita_enabled'] == 'true' && !$form_params['vcita_confirmed'] && !empty($vcita_confirmation_token);
}

/**
 * Check if a notification for the current form should be displayed to the user
 */
function vcita_should_notify_missing_details($form_params) {
    return isset($form_params['vcita_uid']) && empty($form_params['vcita_uid']) && $form_params['vcita_enabled'] == 'true';
}


/** 
 * Check if should display a warning in the admin section
 * Warning will be shown in all admin pages (as being done by many other plugins)
 * Won't shown for the actual fast contact page - it is being called directly from the page 
 */
function si_contact_vcita_admin_warning() {
    
   if (!isset($_GET['page']) || !preg_match('/si-contact-form.php$/',$_GET['page'])) {
       $si_contact_global_tmp = get_option("si_contact_form_gb");
       
       if (class_exists("siContactForm") && !isset($si_contact_form) ) {
         $si_contact_form = new siContactForm();
    
         if (empty($si_contact_global_tmp)) {
             $this->vcita_print_admin_page_notification();
              
         } else {
             $vcita_never_used = true;
             
             for ($i = 1; $i <= $si_contact_global_tmp['max_forms']; $i++) {
                 $form_num = ($i == 1) ? "" : $i;
                 $si_form_params = get_option("si_contact_form$form_num");
                  
                 if ($this->vcita_print_admin_page_notification($si_contact_global_tmp, $si_form_params, $form_num)) {
                     $vcita_never_used = false;
                     return;
                     
                 } else if ($this->vcita_is_form_used($si_form_params)) {
                     $vcita_never_used = false;
                 } 
             }
             
             if ($vcita_never_used && $this->vcita_should_show_when_not_used($si_contact_global_tmp)) {
                 $this->vcita_print_admin_page_notification($si_contact_global_tmp, null); // Put the general
             }
         }
      }
   }
}

/**
 * Get the email which should be used for vcita meeting scheduling
 */
function vcita_get_email($params) {
	if (!empty($params["vcita_email"])) {
		return $params["vcita_email"];
	} else {
		return $this->si_contact_extract_email($params["email_to"]);
	}
}

/* 
 * Check if the user is already available in vCita
 */
function vcita_check_user($params) {
	extract($this->vcita_get_contents("http://www.vcita.com/api/experts/".$params['vcita_uid']));
	
	return $this->vcita_parse_user_info($params, $success, $raw_data);
}

/**
 * Get the confirmation token matches the current user
 */
function vcita_get_confirmation_token($params) {

	$token = "";
	
	if (!empty($params["vcita_confirm_tokens"])) {
		$token = "";
		$tokens = explode("|", $params["vcita_confirm_tokens"]);
		if (count($tokens) > 0) {
			foreach ($tokens as $raw_token) {
				$token_values = explode("-", $raw_token);
				
				if (!empty($raw_token) && $token_values[0] == $params["vcita_uid"]) {
					$token = $token_values[1];
					
					if (!empty($_SESSION) && $_SESSION['vcita_expert']) {
						$_SESSION['vcita_owner-of-'.$params['vcita_uid']] = true;
					}
					
					break;
				}
			}
		}
	}
	return $token;
}

/** 
 * Set the confirmation for the current user
 */ 
function vcita_set_confirmation_token($params, $confirmation_token) {
	if (!empty($confirmation_token)) {
		$tokens = explode("|", $params["vcita_confirm_tokens"]);
		array_push($tokens, $params["vcita_uid"]."-".$confirmation_token);
	
		$params["vcita_confirm_tokens"] = implode("|", $tokens);
	}
	
	return $params;
}

/**
 * Check if the vcita confirmation token should be saved.
 * Currently this means it will be also saved in the client side in a dedicated cookie.
 */
function vcita_should_store_expert_confirmation_token($params) {

	$confirmation_token = $this->vcita_get_confirmation_token($params);
	
	if (!empty($confirmation_token) && !empty($_SESSION) && $_SESSION['vcita_owner-of-'.$params['vcita_uid']]) {
		return $confirmation_token;
	} else {
		return "";
	}
}

/**
 * Flip the dismiss flag to true and make all the neccessary adjustments.
 */
function vcita_dismiss_pending_notification($global_params, $current_form_num) {
    global $si_contact_opt;
    
    // Go over all the forms and disable the pending ones
    for ($i = 1; $i <= $global_params['max_forms']; $i++) {
        $form_num = ($i == 1) ? "" : $i;
        
        if ($current_form_num == $form_num) {
            $si_form_params = $si_contact_opt;
        } else {
            $si_form_params = get_option("si_contact_form$form_num");
        }

        if ($this->vcita_should_complete_registration($si_form_params) || 
            $this->vcita_should_notify_missing_details($si_form_params)) {
            
            $si_form_params['vcita_enabled'] = 'false';
            $si_form_params['vcita_last_error'] = '';
            $si_form_params['vcita_uid'] = '';
            $si_form_params['vcita_first_name'] = '';
            $si_form_params['vcita_last_name'] = '';
            $si_form_params['vcita_email'] = '';
            update_option("si_contact_form$form_num", $si_form_params);

            // Also update the global variable
            if ($current_form_num == $form_num) {
                $si_contact_opt = $si_form_params;
            }
        }
    }

    // Put the dismiss flag
    $global_params["vcita_dismiss"] = "true";
    update_option("si_contact_form_gb", $global_params);
    
    return $global_params;
}


/**
 * True / False if notification should be displayed if user didn't use vCita
 * 
 * True only if upgrade user (never had auto install vCita) 
 */
function vcita_should_show_when_not_used($global_params) {
    return isset($global_params['vcita_auto_install']) && $global_params['vcita_auto_install'] == "false";
}

/**
 * vCita form is used if one of the following:
 *  
 * - form enabled
 * - has a vcita_uid 
 * - has a confirmation_token -> in the past had a user
 */
function vcita_is_form_used($form_param) {
    return ((isset($form_param["vcita_enabled"]) && $form_param["vcita_enabled"] == "true") ||
            (isset($form_param["vcita_uid"]) && !empty($form_param["vcita_uid"])) || 
            (isset($form_param["vcita_confirm_tokens"]) && !empty($form_param["vcita_confirm_tokens"])));
}

/**
 * Check if vcita is used in any form
 */
function vcita_is_being_used() {
    $si_contact_global_tmp = get_option("si_contact_form_gb");

    for ($i = 1; $i <= $si_contact_global_tmp['max_forms']; $i++) {
        $form_num = ($i == 1) ? "" : $i;
        $si_form_params = get_option("si_contact_form$form_num");
    
        if ($this->vcita_is_form_used($si_form_params)) {
            return true;
        }
    }
    
    return false;
}

/* --- vCita Admin Functions - End --- */

/* --- vCita Contact Functions - Start --- */

/** 
 * Add the vcita script to the pages of the fast secure
 */
function vcita_si_contact_add_script(){
    global $si_contact_opt, $vcita_add_script;

    if (!$vcita_add_script)
      return;
    wp_enqueue_script('jquery');
    wp_register_script('vcita_fscf', plugins_url('vcita/vcita_fscf.js', __FILE__), array('jquery'), '1.1', true);
    wp_print_scripts('vcita_fscf');
      ?>
    <script type="text/javascript">
//<![CDATA[
var vicita_fscf_style = "<!-- begin Fast Secure Contact Form - vCita scheduler page header -->" +
"<style type='text/css'>" + 
".vcita-widget-right { float: left !important; } " +
".vcita-widget-bottom { float: none !important; clear:both;}" +
"</style>" + 
"<!-- end Fast Secure Contact Form - vCita scheduler page header -->";
jQuery(document).ready(function($) {
$('head').append(vicita_fscf_style);
});
//]]>
</script>
	<?php

}
/* --- vCita Contact Functions - End --- */

/**
 * Extract the mail contained and the received argument.
 * Handles the following usecases:
 * 1. Name and email concatenation - Webmaster,mail@example.com
 * 2. Only email
 *
 * Returns the email address
 */
function si_contact_extract_email($ctf_extracted_email) {
	$ctf_trimmed_email = trim($ctf_extracted_email);
	  
	if(!preg_match("/,/", $ctf_trimmed_email) ) { // single email without,name
		$name = '';        // name,email
		$email = $ctf_trimmed_email;
	} else{
		list($name, $email) = preg_split('#(?<!\\\)\,#',array_shift(preg_split('/[;]/',$ctf_trimmed_email)));
	}

	return $email;
}


function si_contact_captcha_perm_dropdown($select_name, $checked_value='') {
        // choices: Display text => permission_level
        $choices = array (
                 __('All registered users', 'si-contact-form') => 'read',
                 __('Edit posts', 'si-contact-form') => 'edit_posts',
                 __('Publish Posts', 'si-contact-form') => 'publish_posts',
                 __('Moderate Comments', 'si-contact-form') => 'moderate_comments',
                 __('Administer site', 'si-contact-form') => 'level_10'
                 );
        // print the <select> and loop through <options>
        echo '<select name="' . esc_attr($select_name) . '" id="' . esc_attr($select_name) . '">
';
        foreach ($choices as $text => $capability) :
                if ($capability == $checked_value) $checked = ' selected="selected" ';
                echo '    <option value="' . esc_attr($capability) . '"' . $checked . '>'.esc_html($text).'</option>
';
                $checked = '';
        endforeach;
        echo '    </select>
';
} // end function si_contact_captcha_perm_dropdown


// Returns a array of contacts for display
// E-mail Contacts
// the drop down list array will be made automatically by this code
// checks for properly configured E-mail To: addresses in options.
function get_contacts() {
      global $si_contact_opt;

$contacts = array ();
$contacts[] = '';	// dummy entry to take up key 0
$contacts_test = trim($si_contact_opt['email_to']);
if(!preg_match("/,/", $contacts_test) ) {
    if($this->ctf_validate_email($contacts_test)) {
        // user1@example.com
       $contacts[] = array('CONTACT' => __('Webmaster', 'si-contact-form'),  'EMAIL' => $contacts_test );
    }
} else {
  $ctf_ct_arr = explode("\n",$contacts_test);
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
                  $contacts[] = array('CONTACT' => $key,  'EMAIL' => $value);
               }
          } else {
               // multiple emails here
               // Webmaster,user1@example.com;user2@example.com;user3@example.com;[cc]user4@example.com;[bcc]user5@example.com
               $multi_cc_arr = explode(";",$value);
               $multi_cc_string = '';
               foreach($multi_cc_arr as $multi_cc) {
                  $multi_cc_t = str_replace('[cc]','',$multi_cc);
                  $multi_cc_t = str_replace('[bcc]','',$multi_cc_t);
                  if ($this->ctf_validate_email($multi_cc_t)) {
                     $multi_cc_string .= "$multi_cc,";
                   }
               }
               if ($multi_cc_string != '') { // multi cc emails
                  $contacts[] = array('CONTACT' => $key,  'EMAIL' => rtrim($multi_cc_string, ','));
               }
         }
      }

   } // end foreach
  } // end if (is_array($ctf_ct_arr) ) {
} // end else
unset($contacts[0]);	// remove dummy entry.. the array keys now start with 1
//print_r($ctf_contacts);
return $contacts;

} // end function get_contacts

// this function builds and displays the contact form HTML content
// [si_contact_form form='2']
// This function may be processed more than once via shortcode when there are multiple forms on a page,
// or when a plugin modifies "the content".

function si_contact_form_short_code($atts) {
  global $fsc_form_posted, $captcha_path_cf, $si_contact_opt, $si_contact_gb, $ctf_version, $vcita_add_script, $fsc_error_message;

  $this->ctf_version = $ctf_version;
  // get options
  $si_contact_gb_mf = get_option("si_contact_form_gb");

   extract(shortcode_atts(array(
   'form' => '',
   'redirect' => '',
   'hidden' => '',
   'email_to' => '',
   ), $atts));

    $form_num = '';
    $form_id_num = 1;
    if ( isset($form) && is_numeric($form) && $form <= $si_contact_gb_mf['max_forms'] ) {
       $form_num = (int)$form;
       $form_id_num = (int)$form;
       if ($form_num == 1)
         $form_num = '';
    }


  // http://www.fastsecurecontactform.com/shortcode-options
  $_SESSION["fsc_shortcode_redirect_$form_id_num"] = $redirect;
  $_SESSION["fsc_shortcode_hidden_$form_id_num"] = $hidden;
  $_SESSION["fsc_shortcode_email_to_$form_id_num"] = $email_to;

  // get options
  $si_contact_gb = $this->si_contact_get_options($form_num);

 /*
This next few lines of code controls logic for when two different forms are on one page.
The error display and the form post vars should only be processed for one form that was posted.
Only one form can be posted at a time
$this->si_contact_error is set if the form posted had errors in si_contact_form_check
$fsc_form_posted is set to the form # posted in si_contact_form_check_and_send, will be 0 of not posted
$display_only means that this iteration in the display code is not a form that was posted, so ignore post vars
*/
$display_only = 0;
$have_error = 0;
if ($form_id_num != $fsc_form_posted) {
    $display_only = 1;
} else {
     if ($this->si_contact_error)
          $have_error = 1;
}

  // did we already get a valid and action completed form result?
  if(!$display_only && ( isset($_POST['si_contact_action']) && $_POST['si_contact_action'] == 'send')
      && (isset($_POST['si_contact_form_id']) && is_numeric($_POST['si_contact_form_id'])) ){
     $form_id_posted = (int)$_POST['si_contact_form_id'];
     // return the form HTML now
     if( isset( $_SESSION['fsc_form_display_html'] ) ) {
         //already processed, this variable is used to print the form results HTML to shortcode now, even more than once if other plugins cause
         return $_SESSION['fsc_form_display_html'];
     }
  }

  // have to continue on and build the form results HTML now.

     // include the code to display the form
     include(WP_PLUGIN_DIR . '/si-contact-form/si-contact-form-display.php');


 return $string;
} // end function si_contact_form_short_code


// returns the URL for the WP page the form was on
function form_action_url( ) {

  if(function_exists('qtrans_convertURL'))
      // compatible with qtranslate plugin
      // In case of multi-lingual pages, the /de/ /en/ language url is used.
      $form_action_url = qtrans_convertURL(strip_tags($_SERVER['REQUEST_URI']));
  else
      $form_action_url = 'http://'.strip_tags($_SERVER['HTTP_HOST']).strip_tags($_SERVER['REQUEST_URI']);

  // set the type of request (SSL or not)
  if ( is_ssl() )
      $form_action_url = preg_replace('|http://|', 'https://', $form_action_url);

  return $form_action_url;

} // end function form_action_url

// This function decides if the form # was posted and needs to be processed, called only once during init
// even when there are multiple forms on a page, only one can be posted to and processed
// Also has logic that prevents clicking the back button to mail again, if you try, it will redirect to blank form.
function si_contact_check_and_send(  ) {
   global $fsc_form_posted, $si_contact_opt, $fsc_error_message;
   $fsc_form_posted = 0;

  // do we process one of our forms now?
  if(   ( isset($_POST['si_contact_action']) && $_POST['si_contact_action'] == 'send')
      && (isset($_POST['si_contact_form_id']) && is_numeric($_POST['si_contact_form_id'])) ){
     $form_id_num = (int)$_POST['si_contact_form_id'];

         // begin logic that prevents clicking the back button to mail again.
         if (!isset($_POST["si_postonce_$form_id_num"]) || empty($_POST["si_postonce_$form_id_num"]) || strpos($_POST["si_postonce_$form_id_num"] , ',') === false ) {
                // redirect, get out
                wp_redirect( $this->form_action_url() ); // token was no good, missing or in incorrect format
		        exit;
         }
         $vars = explode(',', $_POST["si_postonce_$form_id_num"]);
         if ( empty($vars[0]) || empty($vars[1]) || ! preg_match("/^[0-9]+$/",$vars[1]) ) {
                // redirect, get out
                wp_redirect( $this->form_action_url() ); // token was no good, parts missing or in incorrect format
		        exit;
         }
         if ( wp_hash( $vars[1] ) == $vars[0] ) {
         // 04/01/13 disabled because caching plugins will cause problems with this
            // if ( isset($_SESSION["fsc_form_lastpost_$form_id_num"]) && ($_SESSION["fsc_form_lastpost_$form_id_num"] == $vars[0])){
            //   wp_redirect( $this->form_action_url() ); // the form was already posted, no clicking the back button to mail again, show blank form
		    //   exit;
            // }
            // $_SESSION["fsc_form_lastpost_$form_id_num"] = $vars[0];
         } else {
                // redirect, get out
                wp_redirect( $this->form_action_url() ); // token was no good, it was forged
		        exit;
         }
         // end logic that prevents clicking the back button to mail again.
            // prevent double action
      		if( !isset( $_SESSION["fsc_sent_mail"] )) { // form not already emailed out
			    // si_contact_check_form will check all posted input data and send the email if everything passes validation
                // only called once for the form that was posted, not called if this form was not posted.
                $fsc_form_posted = $form_id_num; // this is needed to prevent post vars and error display on wrong form when 2 diff forms on 1 page
			   	$this->si_contact_check_form($form_id_num);
            }

  }
} // function si_contact_check_and_send

// This function will check all posted input data and send the email if everything passes validation
// only called once for the form that was posted, not called if this form was not posted.
// if the form validates, it will send mail and process the optional silent posts,
// then return the results HTML in a session var
function si_contact_check_form($form_id_num) {
     global $this_form_posted, $captcha_path_cf, $si_contact_opt, $si_contact_gb, $ctf_version, $vcita_add_script, $fsc_error_message;

     // include the code to process the form
     include(WP_PLUGIN_DIR . '/si-contact-form/si-contact-form-process.php');

} // function si_contact_check_form

function si_contact_export_convert($posted_data,$rename,$ignore,$add,$return = 'array') {
    $query_string = '';
    $posted_data_export = array();
    //rename field names array
    $rename_fields = array();
    $rename_fields_test = explode("\n",$rename);
    if ( !empty($rename_fields_test) ) {
      foreach($rename_fields_test as $line) {
         if(preg_match("/=/", $line) ) {
            list($key, $value) = explode("=",$line);
            $key   = trim($key);
            $value = trim($value);
            if ($key != '' && $value != '')
              $rename_fields[$key] = $value;
         }
      }
    }
    // add fields
    $add_fields_test = explode("\n",$add);
    if ( !empty($add_fields_test) ) {
      foreach($add_fields_test as $line) {
         if(preg_match("/=/", $line) ) {
            list($key, $value) = explode("=",$line);
            $key   = trim($key);
            $value = trim($value);
            if ($key != '' && $value != '') {
              if($return == 'array')
		        $posted_data_export[$key] = $value;
              else
                $query_string .= $key . '=' . urlencode( stripslashes($value) ) . '&';
            }
         }
      }
    }
    //ignore field names array
    $ignore_fields = array();
    $ignore_fields = array_map('trim', explode("\n", $ignore));
    // $posted_data is an array of the form name value pairs
    foreach ($posted_data as $key => $value) {
	  if( is_string($value) ) {
         if(in_array($key, $ignore_fields))
            continue;
         $key = ( isset($rename_fields[$key]) ) ? $rename_fields[$key] : $key;
         if($return == 'array')
		    $posted_data_export[$key] = $value;
         else
            $query_string .= $key . '=' . urlencode( stripslashes($value) ) . '&';
      }
    }
    if($return == 'array')
      return $posted_data_export;
    else
      return $query_string;
} // end function si_contact_export_convert

// initializes and sets the GET input vars for the form display HTML
// the var is initialized from the matching GET var of this form # if it is set
// if the GET var is not set, then the var is initialized empty
function si_contact_get_var($form_id_num,$name) {
   $value = (isset( $_GET["$form_id_num$name"])) ? $this->ctf_clean_input($_GET["$form_id_num$name"]) : '';
   return $value;
}

// initializes and sets the POST input vars for the form display HTML
// if the form is display only, then the var is initialized empty
// if the form is being processed, then the var is initialized from the post if it is set
function si_contact_post_var($index,$display_only) {
   $value = (isset( $_POST["$index"]) && !$display_only) ? $this->ctf_clean_input($_POST["$index"]) : '';
   return $value;
}

// initializes and sets the input validation error messages
// $fsc_error_message array can only have values for the form that was posted.
// if the form is display only, then the error message is initialized empty
// if the form is being processed, then the error message is initialized from the $fsc_error_message array
// $fsc_error_message array messages are set in si_contact_check_form when the form that was posted is processed.
function si_contact_error_var($index,$display_only) {
  global $fsc_error_message;
   $value = (isset( $fsc_error_message["$index"]) && !$display_only) ? $fsc_error_message["$index"] : '';
   return $value;
}

// returns an array of extra field options
function si_contact_get_exf_opts_array($label) {
  $exf_opts_array = array();
  $exf_opts_label = '';
  $exf_array_test = trim($label);
  if(!preg_match('#(?<!\\\)\,#', $exf_array_test) ) {
                // Error: A radio field is not configured properly in settings
  } else {
      list($exf_opts_label, $value) = preg_split('#(?<!\\\)\,#',$exf_array_test); //string will be split by "," but "\," will be ignored
      $exf_opts_label   = trim(str_replace('\,',',',$exf_opts_label)); // "\," changes to ","
      $value = trim(str_replace('\,',',',$value)); // "\," changes to ","
      if ($exf_opts_label != '' && $value != '') {
          if(!preg_match("/;/", $value)) {
             //Error: A radio field is not configured properly in settings.
          } else {
             // multiple options
             $exf_opts_array = explode(";",$value);
          }
      }
  } // end else
  return $exf_opts_array;
} //end function si_contact_get_exf_opts_array

// needed for making temp directories for attachments
function si_contact_init_temp_dir($dir) {
    $dir = trailingslashit( $dir );
    // make the temp directory
	wp_mkdir_p( $dir );
	//@chmod( $dir, 0733 );
	$htaccess_file = $dir . '.htaccess';
	if ( !file_exists( $htaccess_file ) ) {
	   if ( $handle = @fopen( $htaccess_file, 'w' ) ) {
		   fwrite( $handle, "Deny from all\n" );
		   fclose( $handle );
	   }
    }
    $php_file = $dir . 'index.php';
	if ( !file_exists( $php_file ) ) {
       	if ( $handle = @fopen( $php_file, 'w' ) ) {
		   fwrite( $handle, '<?php //do not delete ?>' );
		   fclose( $handle );
     	}
	}
} // end function si_contact_init_temp_dir

// needed for emptying temp directories for attachments
// garbage collection
// called in si_contact_check_form
function si_contact_clean_temp_dir($dir, $minutes = 30) {
    // deletes all files over xx minutes old in a temp directory
  	if ( ! is_dir( $dir ) || ! is_readable( $dir ) || ! is_writable( $dir ) )
		return false;

	$count = 0;
    $list = array();
	if ( $handle = @opendir( $dir ) ) {
		while ( false !== ( $file = readdir( $handle ) ) ) {
			if ( $file == '.' || $file == '..' || $file == '.htaccess' || $file == 'index.php')
				continue;

			$stat = @stat( $dir . $file );
			if ( ( $stat['mtime'] + $minutes * 60 ) < time() ) {
			    @unlink( $dir . $file );
				$count += 1;
			} else {
               $list[$stat['mtime']] = $file;
            }
		}
		closedir( $handle );
        // purge xx amount of files based on age to limit a DOS flood attempt. Oldest ones first, limit 500
        if( isset($list) && count($list) > 499) {
          ksort($list);
          $ct = 1;
          foreach ($list as $k => $v) {
            if ($ct > 499) @unlink( $dir . $v );
            $ct += 1;
          }
       }
	}
	return $count;
} // end function si_contact_clean_temp_dir


// validates and saves uploaded file attchments for file attach field types.
// also sets errors if the file did not upload or was not accepted.
// called in si_contact_check_form
function si_contact_validate_attach( $file, $ex_field  ) {
    global $si_contact_opt;

    $result['valid'] = true;

    if ($si_contact_opt['php_mailer_enable'] == 'php') {
        $result['valid'] = false;
		$result['error'] = __('Attachments not supported.', 'si-contact-form');
		return $result;
    }

	if ( ($file['error'] && UPLOAD_ERR_NO_FILE != $file['error']) || !is_uploaded_file( $file['tmp_name'] ) ) {
		$result['valid'] = false;
		$result['error'] = __('Attachment upload failed.', 'si-contact-form');
		return $result;
	}

	if ( empty( $file['tmp_name'] ) ) {
		$result['valid'] = false;
		$result['error'] = __('This field is required.', 'si-contact-form');
		return $result;
	}

    // check file types
    $file_type_pattern = $si_contact_opt['attach_types'];
	if ( $file_type_pattern == '' )
		$file_type_pattern = 'doc,pdf,txt,gif,jpg,jpeg,png';
    $file_type_pattern = str_replace(',','|',$si_contact_opt['attach_types']);
    $file_type_pattern = str_replace(' ','',$file_type_pattern);
	$file_type_pattern = trim( $file_type_pattern, '|' );
	$file_type_pattern = '(' . $file_type_pattern . ')';
	$file_type_pattern = '/\.' . $file_type_pattern . '$/i';

	if ( ! preg_match( $file_type_pattern, $file['name'] ) ) {
		$result['valid'] = false;
		$result['error'] = __('Attachment file type not allowed.', 'si-contact-form');
		return $result;
	}

    // check size
    $allowed_size = 1048576; // 1mb default
	if ( preg_match( '/^([[0-9.]+)([kKmM]?[bB])?$/', $si_contact_opt['attach_size'], $matches ) ) {
	     $allowed_size = (int) $matches[1];
		 $kbmb = strtolower( $matches[2] );
		 if ( 'kb' == $kbmb ) {
		     $allowed_size *= 1024;
		 } elseif ( 'mb' == $kbmb ) {
		     $allowed_size *= 1024 * 1024;
		 }
	}
	if ( $file['size'] > $allowed_size ) {
		$result['valid'] = false;
		$result['error'] = __('Attachment file size is too large.', 'si-contact-form');
		return $result;
	}

	$filename = $file['name'];

	// safer file names for scripts.
	if ( preg_match( '/\.(php|pl|py|rb|js|cgi)\d?$/', $filename ) )
		$filename .= '.txt';

 	$attach_dir = WP_PLUGIN_DIR . '/si-contact-form/attachments/';

	$filename = wp_unique_filename( $attach_dir, $filename );

	$new_file = trailingslashit( $attach_dir ) . $filename;

	if ( false === @move_uploaded_file( $file['tmp_name'], $new_file ) ) {
		$result['valid'] = false;
		$result['error'] = __('Attachment upload failed while moving file.', 'si-contact-form');
		return $result;
	}

	// uploaded only readable for the owner process
	@chmod( $new_file, 0400 );

	$this->uploaded_files[$ex_field] = $new_file;

    $result['file_name'] = $filename; // needed for email message

	return $result;
} // end function si_contact_validate_attach

// makes bold html email labels for the email message
// called in si_contact_check_form
function make_bold($label) {
   global $si_contact_opt;

   if ($si_contact_opt['email_html'] == 'true')
        return '<b>'.$label.'</b>';
   else
        return $label;

}

// checks if captcha is enabled based on the current captcha permission settings set in the plugin options
function isCaptchaEnabled() {
   global $si_contact_opt;

   if ($si_contact_opt['captcha_enable'] !== 'true') {
        return false; // captcha setting is disabled for si contact
   }
   // skip the captcha if user is loggged in and the settings allow
   if (is_user_logged_in() && $si_contact_opt['captcha_perm'] == 'true') {
       // skip the CAPTCHA display if the minimum capability is met
       if ( current_user_can( $si_contact_opt['captcha_perm_level'] ) ) {
               // skip capthca
               return false;
        }
   }
   return true;
} // end function isCaptchaEnabled

function captchaCheckRequires() {
  global $captcha_path_cf;

  $ok = 'ok';
  // Test for some required things, print error message if not OK.
  if ( !extension_loaded('gd') || !function_exists('gd_info') ) {
      $this->captchaRequiresError .= '<p '.$this->ctf_error_style.'>'.__('ERROR: si-contact-form.php plugin says GD image support not detected in PHP!', 'si-contact-form').'</p>';
      $this->captchaRequiresError .= '<p>'.__('Contact your web host and ask them why GD image support is not enabled for PHP.', 'si-contact-form').'</p>';
      $ok = 'no';
  }
  if ( !function_exists('imagepng') ) {
      $this->captchaRequiresError .= '<p '.$this->ctf_error_style.'>'.__('ERROR: si-contact-form.php plugin says imagepng function not detected in PHP!', 'si-contact-form').'</p>';
      $this->captchaRequiresError .= '<p>'.__('Contact your web host and ask them why imagepng function is not enabled for PHP.', 'si-contact-form').'</p>';
      $ok = 'no';
  }
  if ( !@strtolower(ini_get('safe_mode')) == 'on' && !file_exists("$captcha_path_cf/securimage.php") ) {
       $this->captchaRequiresError .= '<p '.$this->ctf_error_style.'>'.__('ERROR: si-contact-form.php plugin says captcha_library not found.', 'si-contact-form').'</p>';
       $ok = 'no';
  }
  if ($ok == 'no')  return false;
  return true;
}

// check the honeypot trap for spam bots
// this is very basic, just checks if a hidden empty field was filled in
function si_contact_check_honeypot($form_id) {
    global $si_contact_opt;

    if ($si_contact_opt['honeypot_enable'] == 'false')
         return 'ok';

    // hidden honeypot field
    if( isset($_POST["email_$form_id"]) && trim($_POST["email_$form_id"]) != '')
         return 'failed honeypot';

      return 'ok';

}  //  end function si_contact_validate_honeypot

// this function adds the captcha to the contact form
function si_contact_get_captcha_html($form_id_num,$display_only) {
   global $captcha_path_cf, $captcha_url_cf, $si_contact_gb, $si_contact_opt, $fsc_error_message;
   $req_field_ind = ( $si_contact_opt['req_field_indicator_enable'] == 'true' ) ? '<span '.$this->si_contact_convert_css($si_contact_opt['required_style']).'>'.$si_contact_opt['req_field_indicator'].'</span>' : '';

  $string = '';

// Test for some required things, print error message right here if not OK.
if ($this->captchaCheckRequires()) {

  $si_contact_opt['captcha_image_style'] = 'border-style:none; margin:0; padding:0px; padding-right:5px; float:left;';
  $si_contact_opt['reload_image_style'] = 'border-style:none; margin:0; padding:0px; vertical-align:bottom;';

// the captch html

 $string = '
<div '.$this->ctf_title_style.'> </div>
 <div ';
$this->ctf_captcha_div_style_sm = $this->si_contact_convert_css($si_contact_opt['captcha_div_style_sm']);
$this->ctf_captcha_div_style_m = $this->si_contact_convert_css($si_contact_opt['captcha_div_style_m']);

// url for captcha image
$securimage_show_url = $captcha_url_cf .'/securimage_show.php?';

$securimage_size = 'width="175" height="60"';
if($si_contact_opt['captcha_small'] == 'true') {
  $securimage_show_url .= 'ctf_sm_captcha=1&';
  $securimage_size = 'width="132" height="45"';
}

$parseUrl = parse_url($captcha_url_cf);
$securimage_url = $parseUrl['path'];


$securimage_show_rf_url = $securimage_show_url . 'ctf_form_num=' .$form_id_num;
$securimage_show_url .= 'ctf_form_num=' .$form_id_num;


$string .= ($si_contact_opt['captcha_small'] == 'true') ? $this->ctf_captcha_div_style_sm : $this->ctf_captcha_div_style_m;
$string .= '>
    <img class="ctf-captcha" id="si_image_ctf'.$form_id_num.'" ';
    $string .= ($si_contact_opt['captcha_image_style'] != '') ? 'style="' . esc_attr( $si_contact_opt['captcha_image_style'] ).'"' : '';
    $string .= ' src="'.esc_url($securimage_show_url).'" '.$securimage_size.' alt="';
    $string .= esc_attr(($si_contact_opt['tooltip_captcha'] != '') ? $si_contact_opt['tooltip_captcha'] : __('CAPTCHA Image', 'si-contact-form'));
    $string .='" title="';
    $string .= esc_attr(($si_contact_opt['tooltip_captcha'] != '') ? $si_contact_opt['tooltip_captcha'] : __('CAPTCHA Image', 'si-contact-form'));
    $string .= '" />
';

         $string .= '    <div id="si_refresh_ctf'.$form_id_num.'">
';
         $string .= '      <a href="#" rel="nofollow" title="';
         $string .= esc_attr(($si_contact_opt['tooltip_refresh'] != '') ? $si_contact_opt['tooltip_refresh'] : __('Refresh Image', 'si-contact-form'));

         $string .= '" onclick="document.getElementById(\'si_image_ctf'.$form_id_num.'\').src = \''.esc_url($securimage_show_url).'&amp;sid=\''.' + Math.random(); return false;">
';

         $string .= '      <img src="'.$captcha_url_cf.'/images/refresh.png" width="22" height="20" alt="';
         $string .= esc_attr(($si_contact_opt['tooltip_refresh'] != '') ? $si_contact_opt['tooltip_refresh'] : __('Refresh Image', 'si-contact-form'));
         $string .=  '" ';
         $string .= ($si_contact_opt['reload_image_style'] != '') ? 'style="' . esc_attr( $si_contact_opt['reload_image_style'] ).'"' : '';
         $string .=  ' onclick="this.blur();" /></a>
   </div>
   </div>

      <div '.$this->ctf_title_style.'>
                <label for="si_contact_captcha_code'.$form_id_num.'">';
     $string .= esc_html(($si_contact_opt['title_capt'] != '') ? $si_contact_opt['title_capt'] : __('CAPTCHA Code:', 'si-contact-form'));
     $string .= $req_field_ind.'</label>
        </div>
        <div '.$this->si_contact_convert_css($si_contact_opt['field_div_style']).'>'.$this->ctf_echo_if_error($this->si_contact_error_var('captcha',$display_only)).'
                <input '.$this->si_contact_convert_css($si_contact_opt['captcha_input_style']).' type="text" value="" id="si_contact_captcha_code'.$form_id_num.'" name="si_contact_captcha_code" '.$this->ctf_aria_required.' size="'.absint($si_contact_opt['captcha_field_size']).'" />
       </div>
';
} else {
      $string .= $this->captchaRequiresError;
}
  return $string;
} // end function si_contact_get_captcha_html

// shows form validation error messages
function ctf_echo_if_error($this_error){
  //if ($this->si_contact_error) {
    if (!empty($this_error)) {
         return '
         <div '.$this->ctf_error_style.'>'. esc_html($this_error) . '</div>
';
    }
 // }
} // end function ctf_echo_if_error

// functions for protecting and validating form input vars
function ctf_clean_input($string, $preserve_space = 0) {
    if (is_string($string)) {
       if($preserve_space)
          return $this->ctf_sanitize_string(strip_tags($this->ctf_stripslashes($string)),$preserve_space);
       return trim($this->ctf_sanitize_string(strip_tags($this->ctf_stripslashes($string))));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = $this->ctf_clean_input($value,$preserve_space);
      }
      return $string;
    } else {
      return $string;
    }
} // end function ctf_clean_input

// functions for protecting and validating form vars
function ctf_sanitize_string($string, $preserve_space = 0) {
    if(!$preserve_space)
      $string = preg_replace("/ +/", ' ', trim($string));

    return preg_replace("/[<>]/", '_', $string);
} // end function ctf_sanitize_string

// functions for protecting and validating form vars
function ctf_stripslashes($string) {
        //if (get_magic_quotes_gpc()) {
          // wordpress always has magic_quotes On regardless of PHP settings!!
                return stripslashes($string);
       // } else {
        //       return $string;
       // }
} // end function ctf_stripslashes

// functions for protecting output against XSS. encode  < > & " ' (less than, greater than, ampersand, double quote, single quote).
function ctf_output_string($string) {
    $string = str_replace('&', '&amp;', $string);
    $string = str_replace('"', '&quot;', $string);
    $string = str_replace("'", '&#39;', $string);
    $string = str_replace('<', '&lt;', $string);
    $string = str_replace('>', '&gt;', $string);
    return $string;
} // end function ctf_output_string

// A function knowing about name case (i.e. caps on McDonald etc)
// $name = name_case($name);
function ctf_name_case($name) {
   global $si_contact_opt;

   if ($si_contact_opt['name_case_enable'] !== 'true') {
        return $name; // name_case setting is disabled for si contact
   }
   if ($name == '') return '';
   $break = 0;
   $newname = strtoupper($name[0]);
   for ($i=1; $i < strlen($name); $i++) {
       $subed = substr($name, $i, 1);
       if (((ord($subed) > 64) && (ord($subed) < 123)) ||
           ((ord($subed) > 48) && (ord($subed) < 58))) {
           $word_check = substr($name, $i - 2, 2);
           if (!strcasecmp($word_check, 'Mc') || !strcasecmp($word_check, "O'")) {
               $newname .= strtoupper($subed);
           }else if ($break){
               $newname .= strtoupper($subed);
           }else{
               $newname .= strtolower($subed);
           }
             $break = 0;
       }else{
             // not a letter - a boundary
             $newname .= $subed;
             $break = 1;
       }
   }
   return $newname;
} // end function ctf_name_case

// checks proper url syntax (not perfect, none of these are, but this is the best I can find)
//   tutorialchip.com/php/preg_match-examples-7-useful-code-snippets/
function ctf_validate_url($url) {

    $regex = "((https?|ftp)\:\/\/)?"; // Scheme
	$regex .= "([a-zA-Z0-9+!*(),;?&=\$_.-]+(\:[a-zA-Z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
    $regex .= "([a-zA-Z0-9-.]*)\.([a-zA-Z]{2,6})"; // Host or IP
    $regex .= "(\:[0-9]{2,5})?"; // Port
    $regex .= "(\/#\!)?"; // Path hash bang  (twitter) (mike challis added)
    $regex .= "(\/([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
    $regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
    $regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor

	return preg_match("/^$regex$/", $url);

} // end function ctf_validate_url

// checks proper email syntax (not perfect, none of these are, but this is the best I can find)
function ctf_validate_email($email) {
   global $si_contact_opt;

   //check for all the non-printable codes in the standard ASCII set,
   //including null bytes and newlines, and return false immediately if any are found.
   if (preg_match("/[\\000-\\037]/",$email)) {
      return false;
   }
   // regular expression used to perform the email syntax check
   // http://fightingforalostcause.net/misc/2006/compare-email-regex.php
   $pattern = "/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?$/iD";
   if(!preg_match($pattern, $email)){
      return false;
   }
   // Make sure the domain exists with a DNS check (if enabled in options)
   // MX records are not mandatory for email delivery, this is why this function also checks A and CNAME records.
   // if the checkdnsrr function does not exist (skip this extra check, the syntax check will have to do)
   // checkdnsrr available in Linux: PHP 4.3.0 and higher & Windows: PHP 5.3.0 and higher
   if ($si_contact_opt['email_check_dns'] == 'true') {
      if( function_exists('checkdnsrr') ) {
         list($user,$domain) = explode('@',$email);
         if(!checkdnsrr($domain.'.', 'MX') &&
            !checkdnsrr($domain.'.', 'A') &&
            !checkdnsrr($domain.'.', 'CNAME')) {
            // domain not found in DNS
            return false;
         }
      }
   }
   return true;
} // end function ctf_validate_email

// helps spam protect email input
// finds new lines injection attempts
function ctf_forbidifnewlines($input) {

 // check posted input for email injection attempts
 // Check for these common exploits
 // if you edit any of these do not break the syntax of the regex
 $input_expl = "/(<CR>|<LF>|\r|\n|%0a|%0d|content-type|mime-version|content-transfer-encoding|to:|bcc:|cc:|document.cookie|document.write|onmouse|onkey|onclick|onload)/i";
 // Loop through each POST'ed value and test if it contains one of the exploits fromn $input_expl:
   if (is_string($input)){
     $v = strtolower($input);
     $v = str_replace('donkey','',$v); // fixes invalid input with "donkey" in string
     $v = str_replace('monkey','',$v); // fixes invalid input with "monkey" in string
     if( preg_match($input_expl, $v) ){
                wp_die(__('Illegal characters in POST. Possible email injection attempt', 'si-contact-form'));
     }
   }


} // end function ctf_forbidifnewlines

// helps spam protect email input
// blocks contact form posted from other domains
function ctf_spamcheckpost() {

 if(!isset($_SERVER['HTTP_USER_AGENT'])){
     return __('Invalid User Agent', 'si-contact-form');
 }

 // Make sure the form was indeed POST'ed:
 //  (requires your html form to use: si_contact_action="post")
 if(!$_SERVER['REQUEST_METHOD'] == "POST"){
    return __('Invalid POST', 'si-contact-form');
 }

  // Make sure the form was posted from an approved host name.
 if ($this->ctf_domain_protect == 'true') {
     $print_authHosts = '';
   // Host names from where the form is authorized to be posted from:
   if (is_array($this->ctf_domain)) {
      $this->ctf_domain = array_map(strtolower, $this->ctf_domain);
      $authHosts = $this->ctf_domain;
      foreach ($this->ctf_domain as $each_domain) {
         $print_authHosts .= ' '.$each_domain;
      }
   } else {
      $this->ctf_domain =  strtolower($this->ctf_domain);
      $authHosts = array("$this->ctf_domain");
      $print_authHosts = $this->ctf_domain;
   }

   // Where have we been posted from?
   if( isset($_SERVER['HTTP_REFERER']) and trim($_SERVER['HTTP_REFERER']) != '' ) {
      $fromArray = parse_url(strtolower($_SERVER['HTTP_REFERER']));
      // Test to see if the $fromArray used www to get here.
      $wwwUsed = preg_match("/^www\./i",$fromArray['host']);
      if(!in_array((!$wwwUsed ? $fromArray['host'] : preg_replace("/^www\./i",'',$fromArray['host'])), $authHosts ) ){
         return sprintf( __('Invalid HTTP_REFERER domain. See FAQ. The domain name posted from does not match the allowed domain names of this form: %s', 'si-contact-form'), esc_html($print_authHosts) );
      }
   }
 } // end if domain protect

 // check posted input for email injection attempts
 // Check for these common exploits
 // if you edit any of these do not break the syntax of the regex
 $input_expl = "/(%0a|%0d)/i";
 // Loop through each POST'ed value and test if it contains one of the exploits fromn $input_expl:
 foreach($_POST as $k => $v){
   if (is_string($v)){
     $v = strtolower($v);
     $v = str_replace('donkey','',$v); // fixes invalid input with "donkey" in string
     $v = str_replace('monkey','',$v); // fixes invalid input with "monkey" in string
     if( preg_match($input_expl, $v) ){
       return __('Illegal characters in POST. Possible email injection attempt', 'si-contact-form');
     }
   }
 }

 return 0;
} // end function ctf_spamcheckpost

function si_contact_plugin_action_links( $links, $file ) {
    //Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
	if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

	if ( $file == $this_plugin ){
        $settings_link = '<a href="plugins.php?page=si-contact-form/si-contact-form.php">' .esc_html( __('Settings', 'si-contact-form')) . '</a>';
	    array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
} // end function si_contact_plugin_action_links

function si_contact_form_num() {
     // get options
    $si_contact_gb_mf = get_option("si_contact_form_gb");

    $form_num = '';
    if ( isset($_GET['ctf_form_num']) && is_numeric($_GET['ctf_form_num']) && $_GET['ctf_form_num'] > 1 && $_GET['ctf_form_num'] <= $si_contact_gb_mf['max_forms'] ) {
       $form_num = (int)$_GET['ctf_form_num'];
    }
    return $form_num;
} // end function si_contact_form_num

// load things during init
function si_contact_init() {

   if (function_exists('load_plugin_textdomain')) {
      load_plugin_textdomain('si-contact-form', false, dirname(plugin_basename(__FILE__)).'/languages' );
   }

} // end function si_contact_init

function si_contact_get_options($form_num) {
   global $si_contact_opt, $si_contact_gb, $si_contact_gb_defaults, $si_contact_option_defaults, $ctf_version;

      $si_contact_gb_defaults = array(
         'donated' => 'false',
         'max_forms' => '4',
         'max_fields' => '4',
		 'vcita_auto_install' => 'true', /* --- vCita Global Settings --- */
         'vcita_dismiss' => 'false',
		 'ctf_version' => $ctf_version
      );

     $si_contact_option_defaults = array(
         'form_name' => '',
         'welcome' => __('<p>Comments or questions are welcome.</p>', 'si-contact-form'),
         'email_to' => __('Webmaster', 'si-contact-form').','.get_option('admin_email'),
         'php_mailer_enable' => 'wordpress',
         'email_from' => '',
         'email_from_enforced' => 'false',
         'email_reply_to' => '',
         'email_bcc' => '',
         'email_subject' => get_option('blogname') . ' ' .__('Contact:', 'si-contact-form'),
         'email_subject_list' => '',
         'name_format' => 'name',
         'name_type' => 'required',
         'email_type' => 'required',
         'subject_type' => 'required',
         'message_type' => 'required',
         'preserve_space_enable' => 'false',
         'max_fields' => $si_contact_gb_defaults['max_fields'],
         'double_email' => 'false',
         'name_case_enable' => 'false',
         'sender_info_enable' => 'true',
         'domain_protect' => 'true',
         'email_check_dns' => 'false',
         'email_html' => 'false',
         'akismet_disable' => 'false',
         'akismet_send_anyway' => 'true',
         'captcha_enable' => 'true',
         'captcha_small' => 'false',
         'captcha_perm' => 'false',
         'captcha_perm_level' => 'read',
         'honeypot_enable' => 'false',
         'redirect_enable' => 'true',
         'redirect_seconds' => '3',
         'redirect_url' => get_option('home'),
         'redirect_query' => 'false',
         'redirect_ignore' => '',
         'redirect_rename' => '',
         'redirect_add' => '',
         'redirect_email_off' => 'false',
         'silent_send' => 'off',
         'silent_url' => '',
         'silent_ignore' => '',
         'silent_rename' => '',
         'silent_add' => '',
         'silent_email_off' => 'false',
         'export_enable' => 'true',
         'export_ignore' => '',
         'export_rename' => '',
         'export_add' => '',
         'export_email_off' => 'false',
         'ex_fields_after_msg' => 'false',
         'date_format' => 'mm/dd/yyyy',
         'cal_start_day' => '0',
         'time_format' => '12',
         'attach_types' =>  'doc,pdf,txt,gif,jpg,jpeg,png',
         'attach_size' =>   '1mb',
         'textarea_html_allow' => 'false',
         'enable_areyousure' => 'false',
         'auto_respond_enable' => 'false',
         'auto_respond_html' => 'false',
         'auto_respond_from_name' => 'WordPress',
         'auto_respond_from_email' => get_option('admin_email'),
         'auto_respond_reply_to' => get_option('admin_email'),
         'auto_respond_subject' => '',
         'auto_respond_message' => '',
         'req_field_indicator_enable' => 'true',
         'req_field_label_enable' => 'true',
         'req_field_indicator' => ' *',
         'border_enable' => 'false',
         'form_style' => 'width:375px;',
         'border_style' => 'border:1px solid black; padding:10px;',
         'required_style' => 'text-align:left;',
         //'notes_style' => 'text-align:left;',
         'title_style' => 'text-align:left; padding-top:5px;',
         'option_label_style' => 'display:inline;',
         'field_style' => 'text-align:left; margin:0;',
         'field_div_style' => 'text-align:left;',
         'error_style' => 'text-align:left; color:red;',
         'select_style' => 'text-align:left;',
         'captcha_div_style_sm' => 'width:175px; height:50px; padding-top:2px;',
         'captcha_div_style_m' => 'width:250px; height:65px; padding-top:2px;',
         'captcha_input_style' => 'text-align:left; margin:0; width:50px;',
         'submit_div_style' => 'text-align:left; padding-top:2px;',
         'button_style' => 'cursor:pointer; margin:0;',
         'reset_style' => 'cursor:pointer; margin:0;',
         'powered_by_style' => 'font-size:x-small; font-weight:normal; padding-top:5px;',
         'redirect_style' => 'text-align:left;',
         'field_size' => '40',
         'captcha_field_size' => '6',
         'text_cols' => '30',
         'text_rows' => '10',
         'aria_required' => 'false',
         'auto_fill_enable' => 'true',
         'form_attributes' => '',
         'submit_attributes' => '',
         'title_border' => __('Contact Form:', 'si-contact-form'),
         'title_dept' => '',
         'title_select' => '',
         'title_name' => '',
         'title_fname' => '',
         'title_mname' => '',
         'title_miname' => '',
         'title_lname' => '',
         'title_email' => '',
         'title_email2' => '',
         'title_email2_help' => '',
         'title_subj' => '',
         'title_mess' => '',
         'title_capt' => '',
         'title_submit' => '',
         'title_reset' => '',
         'title_areyousure' => '',
         'text_message_sent' => '',
         'tooltip_required' => '',
         'tooltip_captcha' => '',
         'tooltip_refresh' => '',
         'tooltip_filetypes' => '',
         'tooltip_filesize' => '',
         'enable_reset' => 'false',
         'enable_credit_link' => 'false',
         'error_contact_select' => '',
         'error_name'           => '',
         'error_email'          => '',
         'error_email2'         => '',
         'error_field'          => '',
         'error_subject'        => '',
         'error_message'        => '',
         'error_input'          => '',
         'error_captcha_blank'  => '',
         'error_captcha_wrong'  => '',
         'error_correct'        => '',
         'error_spambot'        => '',
         'vcita_enabled'        => 'false', /* --- vCita Settings --- */
         'vcita_approved'       => 'false', /* --- vCita Settings --- */
         'vcita_uid'            => '',
         'vcita_email'          => '',
         'vcita_confirm_tokens'	=> '',
         'vcita_initialized'	=> 'false',
         'vcita_first_name'	    => '',
         'vcita_last_name'	    => '',
  );

  // optional extra fields
  $si_contact_max_fields = $si_contact_gb_defaults['max_fields'];
  if ($si_contact_opt = get_option("si_contact_form$form_num")) { // when not in admin
     if (isset($si_contact_opt['max_fields'])) // use previous setting if it is set
     $si_contact_max_fields = $si_contact_opt['max_fields'];
  }

  for ($i = 1; $i <= $si_contact_max_fields; $i++) { // initialize new
        $si_contact_option_defaults['ex_field'.$i.'_default'] = '0';
        $si_contact_option_defaults['ex_field'.$i.'_default_text'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_req'] = 'false';
        $si_contact_option_defaults['ex_field'.$i.'_label'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_type'] = 'text';
        $si_contact_option_defaults['ex_field'.$i.'_max_len'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_label_css'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_input_css'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_attributes'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_regex'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_regex_error'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_notes'] = '';
        $si_contact_option_defaults['ex_field'.$i.'_notes_after'] = '';
  }

  // upgrade path from old version 3.1.8.1 or older
  if (!get_option('si_contact_form_version') ) {
      // just now updating from version 3.1.8.1 or older, run any related functions you need here

      update_option('si_contact_form_version', $ctf_version);
  } elseif (get_option('si_contact_form_version') != $ctf_version) {
       // just now updating from newer version than 3.1.8.1, run any related functions you need here

      update_option('si_contact_form_version', $ctf_version);
  }

  // upgrade path from old version
  if (!get_option('si_contact_form') && get_option('si_contact_email_to')) {
    // just now updating, migrate settings
    $si_contact_option_defaults = $this->si_contact_migrate($si_contact_option_defaults);
  }

  // upgrade path from old version  2.0.1 or older
  if (!get_option('si_contact_form_gb') && get_option('si_contact_form')) {
    // just now updating, migrate settings
    $si_contact_gb_defaults = $this->si_contact_migrate2($si_contact_gb_defaults);
  }

  // install the global option defaults
  add_option('si_contact_form_gb',  $si_contact_gb_defaults, '', 'yes');

  // install the option defaults
  add_option('si_contact_form',  $si_contact_option_defaults, '', 'yes');

  // multi-form
  $si_contact_max_forms = ( isset($_POST['si_contact_max_forms']) && is_numeric($_POST['si_contact_max_forms']) ) ? $_POST['si_contact_max_forms'] : $si_contact_gb_defaults['max_forms'];
  for ($i = 2; $i <= $si_contact_max_forms; $i++) {
     add_option("si_contact_form$i", $si_contact_option_defaults, '', 'yes');
  }

  // get the options from the database
  $si_contact_gb = get_option("si_contact_form_gb");
  
  /* --- vCita Migrate - Start --- */
  
  // Upgrade ! - Save state and check if the user already in vCita, happens only once.
  if (!isset($si_contact_gb['vcita_auto_install'])) {
    $si_contact_gb['vcita_auto_install'] = 'false';
  }

  // Upgrade ! - Set initial value for dismiss flag
  if (!isset($si_contact_gb['vcita_dismiss'])) {
    $si_contact_gb['vcita_dismiss'] = 'false';
  }

  /* --- vCita Migrate - End --- */

  // Save the previous version 
  if (isset($si_contact_gb['ctf_version'])) {
	$ctf_previous_version = $si_contact_gb['ctf_version'];
  } else {
	$ctf_previous_version = 'new';
  }
 
  // array merge incase this version has added new options
  $si_contact_gb = array_merge($si_contact_gb_defaults, $si_contact_gb);
  
  $si_contact_gb['ctf_version'] = $ctf_version;

  update_option("si_contact_form_gb", $si_contact_gb);

  // get the options from the database
  $si_contact_gb = get_option("si_contact_form_gb");

  // get the options from the database
  $si_contact_opt = get_option("si_contact_form$form_num");

  if (!isset($si_contact_opt['max_fields'])) {  // updated from version < 3.0.3
          $si_contact_opt['max_fields'] = $si_contact_gb['max_fields'];
          update_option("si_contact_form$form_num", $si_contact_opt);
  }
  
  // array merge incase this version has added new options
  $si_contact_opt = array_merge($si_contact_option_defaults, $si_contact_opt);

  // strip slashes on get options array
  foreach($si_contact_opt as $key => $val) {
           $si_contact_opt[$key] = $this->ctf_stripslashes($val);
  }
  if ($si_contact_opt['title_style'] == '' && $si_contact_opt['field_style'] == '') {
     // if styles seem to be blank, reset styles
     $si_contact_opt = $this->si_contact_copy_styles($si_contact_option_defaults,$si_contact_opt);
  }

  // new field type defaults on version 2.6.3
  if ( !isset($si_contact_gb['2.6.3']) ) {
          // optional extra fields
    for ($i = 1; $i <= $si_contact_opt['max_fields']; $i++) {
        if ($si_contact_opt['ex_field'.$i.'_label'] != '' && $si_contact_opt['ex_field'.$i.'_type'] != 'radio' && $si_contact_opt['ex_field'.$i.'_type'] != 'select' ) {
                $si_contact_opt['ex_field'.$i.'_default'] = '0';
        }
        if ($si_contact_opt['ex_field'.$i.'_label'] == '') {
          $si_contact_opt['ex_field'.$i.'_default'] = '0';
          $si_contact_opt['ex_field'.$i.'_default_text'] = '';
          $si_contact_opt['ex_field'.$i.'_req'] = 'false';
          $si_contact_opt['ex_field'.$i.'_label'] = '';
          $si_contact_opt['ex_field'.$i.'_type'] = 'text';
          $si_contact_opt['ex_field'.$i.'_max_len'] = '';
          $si_contact_opt['ex_field'.$i.'_label_css'] = '';
          $si_contact_opt['ex_field'.$i.'_input_css'] = '';
          $si_contact_opt['ex_field'.$i.'_attributes'] = '';
          $si_contact_opt['ex_field'.$i.'_regex'] = '';
          $si_contact_opt['ex_field'.$i.'_regex_error'] = '';
          $si_contact_opt['ex_field'.$i.'_notes'] = '';
          $si_contact_opt['ex_field'.$i.'_notes_after'] = '';
        }
    }
    update_option("si_contact_form", $si_contact_opt);
    for ($i = 2; $i <= $si_contact_gb['max_forms']; $i++) {
       // get the options from the database
       $si_contact_opt{$i} = get_option("si_contact_form$i");
       for ($f = 1; $f <= $si_contact_opt['max_fields']; $f++) {
         if ($si_contact_opt{$i}['ex_field'.$f.'_label'] != '' && $si_contact_opt{$i}['ex_field'.$f.'_type'] != 'radio' && $si_contact_opt{$i}['ex_field'.$f.'_type'] != 'select' ) {
                $si_contact_opt{$i}['ex_field'.$f.'_default'] = '0';
         }
         if ($si_contact_opt{$i}['ex_field'.$f.'_label'] == '') {
          $si_contact_opt{$i}['ex_field'.$f.'_default'] = '0';
         }
       }
       update_option("si_contact_form$i", $si_contact_opt{$i});
       unset($si_contact_opt{$i});
    }
    $si_contact_opt = get_option("si_contact_form$form_num");
    $si_contact_opt = array_merge($si_contact_option_defaults, $si_contact_opt);
    foreach($si_contact_opt as $key => $val) {
           $si_contact_opt[$key] = $this->ctf_stripslashes($val);
    }
    $si_contact_gb['2.6.3'] = 1;
    update_option("si_contact_form_gb", $si_contact_gb);
    $si_contact_gb = get_option("si_contact_form_gb");
    $si_contact_gb = array_merge($si_contact_gb_defaults, $si_contact_gb);
  }
  
  /* --- vCita User Initialization - Start --- */
  
  $si_contact_opt = $this->vcita_validate_initialized_user($form_num,
                                                           $si_contact_opt,
                                                           $si_contact_gb,
                                                           $ctf_previous_version);

  /* --- vCita User Initialization - End --- */
          //print_r($si_contact_opt);
  return $si_contact_gb;

} // end function si_contact_get_options

// used when resetting or copying style settings
function si_contact_copy_styles($this_form_arr,$destination_form_arr) {

     $style_copy_arr = array(
     'border_enable','form_style','border_style','required_style',
     'title_style','option_label_style','field_style','field_div_style','error_style','select_style',
     'captcha_div_style_sm','captcha_div_style_m','captcha_input_style','submit_div_style','button_style', 'reset_style',
     'powered_by_style','redirect_style','field_size','captcha_field_size','text_cols','text_rows');
     foreach($style_copy_arr as $style_copy) {
           $destination_form_arr[$style_copy] = $this_form_arr[$style_copy];
     }
     return $destination_form_arr;
}

function si_contact_start_session() {
  // start the PHP session - used by CAPTCHA, the form action logic, and also used by vCita
  // this has to be set before any header output

  // start cookie session
  if( !isset( $_SESSION ) ) { // play nice with other plugins
    //set the $_SESSION cookie into HTTPOnly mode for better security
    if (version_compare(PHP_VERSION, '5.2.0') >= 0)  // supported on PHP version 5.2.0  and higher
      @ini_set("session.cookie_httponly", 1);
    session_cache_limiter ('private, must-revalidate');
    session_start();
  }

  if(isset($_SESSION['fsc_form_display_html']))
       unset($_SESSION['fsc_form_display_html']); // clear for next page
  if(isset($_SESSION['fsc_sent_mail']))
       unset($_SESSION['fsc_sent_mail']);  // clear for next page
  
  if (is_admin()) {
    $_SESSION["vcita_expert"] = true;
  }
	
} // end function si_contact_start_session

function si_contact_migrate($si_contact_option_defaults) {
  // read the options from the prior version
   $new_options = array ();
   foreach($si_contact_option_defaults as $key => $val) {
      $new_options[$key] = $this->ctf_stripslashes( get_option( "si_contact_$key" ));
      // now delete the options from the prior version
      delete_option("si_contact_$key");
   }
   // delete settings no longer used
   delete_option('si_contact_email_language');
   delete_option('si_contact_email_charset');
   delete_option('si_contact_email_encoding');
   // by returning this the old settings will carry over to the new version
   return $new_options;
} //  end function si_contact_migrate

function si_contact_migrate2($si_contact_gb_defaults) {
  // read the options from the prior version

   $new_options = array ();
   $migrate_opt = get_option("si_contact_form");
   $new_options['donated'] = $migrate_opt['donated'];
   $new_options['max_forms'] = $si_contact_gb_defaults['max_forms'];
   $new_options['max_fields'] = $si_contact_gb_defaults['max_fields'];
   if(defined('SI_CONTACT_FORM_MAX_FORMS') && SI_CONTACT_FORM_MAX_FORMS > $si_contact_gb_defaults['max_forms']) {
    $new_options['max_forms'] = SI_CONTACT_FORM_MAX_FORMS;
   }
   if(defined('SI_CONTACT_FORM_MAX_FIELDS') && SI_CONTACT_FORM_MAX_FIELDS > $si_contact_gb_defaults['max_fields']) {
    $new_options['max_fields'] = SI_CONTACT_FORM_MAX_FIELDS;
   }
   unset($migrate_opt);

   // by returning this the old settings will carry over to the new version
   //print_r($new_options); exit;
   return $new_options;
} //  end function si_contact_migrate2

// restores settings from a contact form settings backup file
function si_contact_form_backup_restore($bk_form_num) {
  global $si_contact_opt, $si_contact_gb, $si_contact_gb_defaults, $si_contact_option_defaults;

   require_once WP_PLUGIN_DIR . '/si-contact-form/admin/si-contact-form-restore.php';

} // end function si_contact_form_backup_restore

// outputs a contact form settings backup file
function si_contact_backup_download() {
  global $si_contact_opt, $si_contact_gb, $si_contact_gb_defaults, $si_contact_option_defaults, $ctf_version;

  require_once WP_PLUGIN_DIR . '/si-contact-form/admin/si-contact-form-backup.php';

} // end function si_contact_backup_download


function get_captcha_url_cf() {

  // The captcha URL cannot be on a different domain as the site rewrites to or the cookie won't work
  // also the path has to be correct or the image won't load.
  // WP_PLUGIN_URL was not getting the job done! this code should fix it.

  //http://media.example.com/wordpress   WordPress address get_option( 'siteurl' )
  //http://tada.example.com              Blog address      get_option( 'home' )

  //http://example.com/wordpress  WordPress address get_option( 'siteurl' )
  //http://example.com/           Blog address      get_option( 'home' )
  // even works on multisite, network activated
  $site_uri = parse_url(get_option('home'));
  $home_uri = parse_url(get_option('siteurl'));

  $captcha_url_cf  = plugins_url( 'captcha' , __FILE__ );

  if ($site_uri['host'] == $home_uri['host']) {
      // use $captcha_url_cf above
  } else {
      $captcha_url_cf  = get_option( 'home' ) . '/'.PLUGINDIR.'/si-contact-form/captcha';
  }
  // set the type of request (SSL or not)
  if ( is_ssl() ) {
		$captcha_url_cf = preg_replace('|http://|', 'https://', $captcha_url_cf);
  }

  return $captcha_url_cf;
}

function si_contact_admin_head() {
 // only load this header stuff on the admin settings page
if(isset($_GET['page']) && is_string($_GET['page']) && preg_match('/si-contact-form.php$/',$_GET['page']) ) {
?>
<!-- begin Fast Secure Contact Form - admin settings page header code -->
<style type="text/css">
div.fsc-star-holder { position: relative; height:19px; width:100px; font-size:19px;}
div.fsc-star {height: 100%; position:absolute; top:0px; left:0px; background-color: transparent; letter-spacing:1ex; border:none;}
.fsc-star1 {width:20%;} .fsc-star2 {width:40%;} .fsc-star3 {width:60%;} .fsc-star4 {width:80%;} .fsc-star5 {width:100%;}
.fsc-star.fsc-star-rating {background-color: #fc0;}
.fsc-star img{display:block; position:absolute; right:0px; border:none; text-decoration:none;}
div.fsc-star img {width:19px; height:19px; border-left:1px solid #fff; border-right:1px solid #fff;}
#main fieldset {border: 1px solid #B8B8B8; padding:19px; margin: 0 0 20px 0;background: #F1F1F1; font:13px Arial, Helvetica, sans-serif;}
.form-tab {background:#F1F1F1; display:block; font-weight:bold; padding:7px 20px; float:left; font-size:13px; margin-bottom:-1px; border:1px solid #B8B8B8; border-bottom:none;}
.submit {padding:7px; margin-bottom:15px;}
.fsc-error{background-color:#ffebe8;border-color:red;border-width:1px;border-style:solid;padding:5px;margin:5px 5px 20px;-moz-border-radius:3px;-khtml-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;}
.fsc-error a{color:#c00;}
.fsc-notice{background-color:#ffffe0;border-color:#e6db55;border-width:1px;border-style:solid;padding:5px;margin:5px 5px 20px;-moz-border-radius:3px;-khtml-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;}
.fsc-success{background-color:#E6EFC2;border-color:#C6D880;border-width:1px;border-style:solid;padding:5px;margin:5px 5px 20px;-moz-border-radius:3px;-khtml-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;}
.vcita-label{width: 93px; display: block; float: left; margin-top: 4px;}
</style>
<!-- end Fast Secure Contact Form - admin settings page header code -->
<?php

  } // end if(isset($_GET['page'])

}

// message sent meta refresh when redirect is on
function si_contact_form_meta_refresh() {
 echo $this->meta_string;
}

function si_contact_form_from_email() {
 return $this->si_contact_from_email;
}

function si_contact_form_from_name() {
 return $this->si_contact_from_name;
}

function si_contact_form_mail_sender($phpmailer) {
 // add Sender for Return-path to wp_mail
 $phpmailer->Sender = $this->si_contact_mail_sender;
}

function ctf_notes($notes) {
    return "\n    <div style=\"clear:both;\"></div>\n$notes\n";
}

function si_contact_convert_css($string) {
    // sanitize admin Modifiable CSS Style Feature
    if( preg_match("/^style=\"(.*)\"$/i", $string, $matches) ){
      return 'style="'.esc_attr($matches[1]).'"';
    }
    if( preg_match("/^class=\"(.*)\"$/i", $string, $matches) ){
      return 'class="'.esc_attr($matches[1]).'"';
    }
    return 'style="'.esc_attr($string).'"';
} // end function si_contact_convert_css

function validate_date( $input ) {
      global $si_contact_opt;
    // Matches the date format and also validates month and number of days in a month.
    // All leap year dates allowed.

     $date_format = $si_contact_opt['date_format'];
    // find the delimiter of the date_format setting: slash, dash or dot
    if (strpos($date_format,'/')) {
      $delim = '/'; $regexdelim = '\/';
    } else if (strpos($date_format,'-')) {
       $delim = '-'; $regexdelim = '-';
    } else if (strpos($date_format,'.')) {
      $delim = '.';  $regexdelim = '\.';
    }

    if ( $date_format == "mm${delim}dd${delim}yyyy" )
        $regex = "/^(((0[13578]|(10|12))${regexdelim}(0[1-9]|[1-2][0-9]|3[0-1]))|(02${regexdelim}(0[1-9]|[1-2][0-9]))|((0[469]|11)${regexdelim}(0[1-9]|[1-2][0-9]|30)))${regexdelim}[0-9]{4}$/";

	if ( $date_format == "dd${delim}mm${delim}yyyy" )
        $regex = "/^(((0[1-9]|[1-2][0-9]|3[0-1])${regexdelim}(0[13578]|(10|12)))|((0[1-9]|[1-2][0-9])${regexdelim}02)|((0[1-9]|[1-2][0-9]|30)${regexdelim}(0[469]|11)))${regexdelim}[0-9]{4}$/";

	if ( $date_format == "yyyy${delim}mm${delim}dd" )
        $regex = "/^[0-9]{4}${regexdelim}(((0[13578]|(10|12))${regexdelim}(0[1-9]|[1-2][0-9]|3[0-1]))|(02${regexdelim}(0[1-9]|[1-2][0-9]))|((0[469]|11)${regexdelim}(0[1-9]|[1-2][0-9]|30)))$/";

    if ( ! preg_match($regex, $input)  )
	    return false;
    else
        return true;

    } // end function validate_date()

/**
 * Remotely fetch, cache, and display HTML ad for the Fast Secure Contact Form Newsletter plugin addon.
 * To use, either add kws_get_remote_ad() to the plugin, or
 * add `do_action('example_do_action');` where the ad should be, then
 * `add_action('example_do_action', 'kws_get_remote_ad');` elsewhere in the plugin.
 */
function kws_get_remote_ad() {

    // The ad is stored locally for 30 days as a transient. See if it exists.
    $cache = function_exists('get_site_transient') ? get_site_transient('fscf_kws_ad') : get_transient('fscf_kws_ad');

    // If it exists, use that (so we save some request time), unless ?cache is set.
    if(!empty($cache) && !isset($_REQUEST['cache'])) { echo $cache; return; }

    // Grab the FSCF settings for version info
    $si_contact_gb = get_option("si_contact_form_gb");

    // Get the advertisement remotely. An encrypted site identifier, the language of the site, and the version of the FSCF plugin will be sent to katz.co
    $response = wp_remote_post('http://katz.co/ads/', array('timeout' => 45,'body' => array('siteid' => sha1(site_url()), 'language' => get_bloginfo('language'), 'version' => (isset($si_contact_gb) && isset($si_contact_gb['ctf_version'])) ? $si_contact_gb['ctf_version'] : null )));

    // If it was a successful request, process it.
    if(!is_wp_error($response)) {

        // Basically, remove <script>, <iframe> and <object> tags for security reasons
        $body = strip_tags(trim(rtrim($response['body'])), '<b><strong><em><i><span><u><ul><li><ol><div><attr><cite><a><style><blockquote><q><p><form><br><meta><option><textarea><input><select><pre><code><s><del><small><table><tbody><tr><th><td><tfoot><thead><u><dl><dd><dt><col><colgroup><fieldset><address><button><aside><article><legend><label><source><kbd><tbody><hr><noscript><link><h1><h2><h3><h4><h5><h6><img>');

        // If the result is empty, cache it for 8 hours. Otherwise, cache it for 30 days.
        $cache_time = empty($response['body']) ? floatval(60*60*8) : floatval(60*60*30);

        if(function_exists('set_site_transient')) {
            set_site_transient('fscf_kws_ad', $body, $cache_time);
        } else {
            set_transient('fscf_kws_ad', $body, $cache_time);
        }

        // Print the results.
        echo  $body;
    }
}

function fscf_enqueue_scripts() {
 // used when clicking the link to install the Fast Secure Contact Form Newsletter plugin addon.
  if(isset($_GET['page']) && is_string($_GET['page']) && preg_match('/si-contact-form.php$/',$_GET['page']) ) {
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
  }
}

} // end of class
} // end of if class


if (class_exists("siContactForm")) {
 $si_contact_form = new siContactForm();
}

if (isset($si_contact_form)) {

  $captcha_url_cf  = $si_contact_form->get_captcha_url_cf();
  $captcha_path_cf = WP_PLUGIN_DIR . '/si-contact-form/captcha';

  // si_contact initialize options
  add_action('init', array(&$si_contact_form, 'si_contact_init'),1);

  // start the PHP session - used by CAPTCHA, the form action logic, and also used by vCita
  add_action('init', array(&$si_contact_form,'si_contact_start_session'),1);

  // process the form POST logic
  add_action('init', array(&$si_contact_form,'si_contact_check_and_send'),10);

  // si contact form admin options
  add_action('admin_menu', array(&$si_contact_form,'si_contact_add_tabs'),1);
  add_action('admin_head', array(&$si_contact_form,'si_contact_admin_head'),1);

  add_action('wp_footer', array(&$si_contact_form,'vcita_si_contact_add_script'),1);

  // this is for downloading settings backup txt file.
  add_action('admin_init', array(&$si_contact_form,'si_contact_backup_download'),1);

  add_action('admin_init', array(&$si_contact_form,'fscf_enqueue_scripts'),2);

  add_action('admin_enqueue_scripts', array(&$si_contact_form,'vcita_add_admin_js'),1);
  
  add_action('admin_notices', array(&$si_contact_form, 'si_contact_vcita_admin_warning'));

  // adds "Settings" link to the plugin action page
  add_filter( 'plugin_action_links', array(&$si_contact_form,'si_contact_plugin_action_links'),10,2);

  // use shortcode to print the contact form or process contact form logic
  // can use dashes or underscores: [si-contact-form] or [si_contact_form]
  add_shortcode('si_contact_form', array(&$si_contact_form,'si_contact_form_short_code'),1);
  add_shortcode('si-contact-form', array(&$si_contact_form,'si_contact_form_short_code'),1);

  // If you want to use shortcodes in your widgets or footer
  add_filter('widget_text', 'do_shortcode');
  add_filter('wp_footer', 'do_shortcode');

    // options deleted when this plugin is deleted in WP 2.7+
  if ( function_exists('register_uninstall_hook') )
     register_uninstall_hook(__FILE__, 'si_contact_unset_options');

}

?>
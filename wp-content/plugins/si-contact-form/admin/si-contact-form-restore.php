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

    // form file upload
     if(isset($_FILES['si_contact_backup_file']) && !empty( $_FILES['si_contact_backup_file'] ))
       $file = $_FILES['si_contact_backup_file'];
     else
       return '<div id="message" class="updated fade"><p>'.__('Restore failed: Backup file is required.', 'si-contact-form').'</p></div>';

	 if ( ($file['error'] && UPLOAD_ERR_NO_FILE != $file['error']) || !is_uploaded_file( $file['tmp_name'] ) )
        return '<div id="message" class="updated fade"><p>'.__('Restore failed: Backup file upload failed.', 'si-contact-form').'</p></div>';

	 if ( empty( $file['tmp_name'] ) )
        return '<div id="message" class="updated fade"><p>'.__('Restore failed: Backup file is required.', 'si-contact-form').'</p></div>';

    // check file type
	$file_type_pattern = '/\.txt$/i';
	if ( ! preg_match( $file_type_pattern, $file['name'] ) )
        return '<div id="message" class="updated fade"><p>'.__('Restore failed: Backup file type not allowed.', 'si-contact-form').'</p></div>';

    // check size
    $allowed_size = 1048576; // 1mb default
	if ( $file['size'] > $allowed_size )
        return '<div id="message" class="updated fade"><p>'.__('Restore failed: Backup file size is too large.', 'si-contact-form').'</p></div>';

    // get the uploaded file that contains all the data
    $ctf_backup_data = file_get_contents($file['tmp_name']);
    $ctf_backup_data_split = explode("@@@@SPLIT@@@@\r\n", $ctf_backup_data);
    $ctf_backup_array = unserialize($ctf_backup_data_split[1]);

    if ( !isset($ctf_backup_array) || !is_array($ctf_backup_array) || !isset($ctf_backup_array[0]['backup_type']) )
         return '<div id="message" class="updated fade"><p>'.__('Restore failed: Backup file contains invalid data.', 'si-contact-form').'</p></div>';

   //print_r($ctf_backup_array);
   //exit;

         $ctf_backup_type = $ctf_backup_array[0]['backup_type'];
         unset($ctf_backup_array[0]['backup_type']);

         // is the uploaded file of the "all" type?
         if ( $ctf_backup_type != 'all' && $bk_form_num == 'all'  )
              return '<div id="message" class="updated fade"><p>'.__('Restore failed: Selected All to restore, but backup file is a single form.', 'si-contact-form').'</p></div>';

         // restore all ?
         if($ctf_backup_type == 'all' && $bk_form_num == 'all' ) {
            // all

            // is the uploaded file of the "all" type?
            if ( !isset($ctf_backup_array[2]) || !is_array($ctf_backup_array[2])  )
              return '<div id="message" class="updated fade"><p>'.__('Restore failed: Selected All to restore, but backup file is a single form.', 'si-contact-form').'</p></div>';

            $my_max_forms = $si_contact_gb['max_forms'];
            // if current max_forms or max_fields are more, go with higher value
            if($si_contact_gb['max_forms'] > $ctf_backup_array[0]['max_forms']) {
                $my_max_forms = $ctf_backup_array[0]['max_forms'];
                $ctf_backup_array[0]['max_forms'] = $si_contact_gb['max_forms'];
            } else {
                $my_max_forms = $ctf_backup_array[0]['max_forms'];
            }
            if($si_contact_gb['max_fields'] > $ctf_backup_array[0]['max_fields'])
                $ctf_backup_array[0]['max_fields'] = $si_contact_gb['max_fields'];
            update_option("si_contact_form_gb", $ctf_backup_array[0]);

               // extra field labels might have \, (make sure it does not get removed)
            foreach($ctf_backup_array[1] as $key => $val) {
                $ctf_backup_array[1][$key] = str_replace('\,','\\\,',$val);
            }
            update_option("si_contact_form", $ctf_backup_array[1]);
            // multi-forms > 1
            for ($i = 2; $i <= $my_max_forms; $i++) {
               // extra field labels might have \, (make sure it does not get removed)
              foreach($ctf_backup_array[$i] as $key => $val) {
                  $ctf_backup_array[$i][$key] = str_replace('\,','\\\,',$val);
              }
              if(!get_option("si_contact_form$i")) {
                    add_option("si_contact_form$i", $ctf_backup_array[$i], '', 'yes');
              }else{
                   update_option("si_contact_form$i", $ctf_backup_array[$i]);
              }
            }
           //error_reporting(0); // suppress errors because a different version backup may have uninitialized vars
           // success
           return '<div id="message" class="updated fade"><p>'.__('All form settings have been restored from the backup file.', 'si-contact-form').'</p></div>';

         } // end restoring all

         // restore single?
         if(is_numeric($bk_form_num)){
            // single
            if( ($bk_form_num == 1 && !get_option("si_contact_form")) || ($bk_form_num > 1 && !get_option("si_contact_form$bk_form_num")))
               return '<div id="message" class="updated fade"><p>'.__('Restore failed: Form to restore to does not exist.', 'si-contact-form').'</p></div>';

            // update the globals
            if($si_contact_gb['max_fields'] < $ctf_backup_array[0]['max_fields']) {
                $si_contact_gb['max_fields'] = $ctf_backup_array[0]['max_fields'];
                update_option("si_contact_form_gb", $si_contact_gb);
            }

            // is the uploaded file of the "single" type?
            if ( !isset($ctf_backup_array[2]) || !is_array($ctf_backup_array[2])  ) {
               //single

               // extra field labels might have \, (make sure it does not get removed)
               foreach($ctf_backup_array[1] as $key => $val) {
                  $ctf_backup_array[1][$key] = str_replace('\,','\\\,',$val);
               }
               if ($bk_form_num == 1)
                  update_option("si_contact_form", $ctf_backup_array[1]);

               if ($bk_form_num > 1)
                   update_option("si_contact_form$bk_form_num", $ctf_backup_array[1]);

               // is the uploaded file of the "all" type?
            } else {
               // "all" backup file, but wants to restore only one form, match the form #
               // extra field labels might have \, (make sure it does not get removed)
               foreach($ctf_backup_array[$bk_form_num] as $key => $val) {
                   $ctf_backup_array[$bk_form_num][$key] = str_replace('\,','\\\,',$val);
               }
               if ($bk_form_num == 1)
                  update_option("si_contact_form", $ctf_backup_array[1]);

               if ($bk_form_num > 1)
                  update_option("si_contact_form$bk_form_num", $ctf_backup_array[$bk_form_num]);
             }

              // success
              return '<div id="message" class="updated fade"><p>'.sprintf(__('Form %d settings have been restored from the backup file.', 'si-contact-form'),$bk_form_num).'</p></div>';

         } // end restoring single

?>
// add the bar
cookie_opt_in._current_bmt = 0;

cookie_opt_in.show_after = function (is_new) {
  is_new = is_new || false;
  cookie_opt_in._current_bmt = jQuery('body').css('margin-top');
  if (is_new) {
    var h = cookie_opt_in._set_bmt||62;
    jQuery('body').css('margin-top', h);
    jQuery('#cookie_opt_in_top_bar_container').show();
  }
  else {
    jQuery('#cookie_opt_in_top_bar_container').show();
    jQuery('#cookie_opt_in_top_bar_container form').show();
  }
};

cookie_opt_in.hide_after = function () {
  var h = cookie_opt_in._current_bmt;
  jQuery('body').css('margin-top', h);
  jQuery('#cookie_opt_in_top_bar_container').hide();
};

cookie_opt_in.init_after = function () {
  jQuery("#cookie_opt_in_container").prepend(
    jQuery('<span />').attr('id', 'coia-bar-message').append(
      jQuery('<h1 />').html(cookie_opt_in_settings.form_title).append(
        jQuery('<a />').addClass('coia-toggle-details').addClass('coia-show-details').html(cookie_opt_in_settings.view_details||"").attr('href', 'javascript:void(0);').before('<span>&nbsp;</span>')
      )
    ).append(
      jQuery('<span />').addClass('coia-bar-buttons').append(
        jQuery('<button class="coia-accept-all button" />').html(cookie_opt_in_settings.label_allow)
      )
    )
  );
  jQuery("#cookie_opt_in_container form").prepend(
    jQuery('<span />').addClass('coia-bar-buttons').append(
      jQuery('<button class="coia-ok button" />').html(cookie_opt_in_settings.label_ok)
    )
  );

  jQuery('#coia-accept-all,#coia-deny-all,#coia-ok').hide();

  jQuery("#cookie_opt_in_container").wrap(jQuery('<div />').addClass('lang-' + cookie_opt_in_settings.lang||"").attr('id', 'cookie_opt_in_top_bar_container').hide());
};

cookie_opt_in.activate_after = function () {
  if (jQuery.browser.msie) jQuery("button.button").hover(function(){ jQuery(this).addClass('hover'); },function(){ jQuery(this).removeClass('hover'); });

  jQuery('.coia-show-details').bind('click', function() {
    jQuery("#cookie_opt_in_container form").slideDown();
  });

  jQuery('.coia-hide-details').bind('click', function() {
    jQuery("#cookie_opt_in_container form").slideUp();
  });
};

cookie_opt_in.action_before = function(el) {
  if (jQuery(el).is('.coia-accept-all')) {
    var newCookie= cookie_opt_in_settings.default_cookie;
    newCookie = { value: cookie_opt_in.expand(newCookie.replace(/0/g, 1)) };
    cookie_opt_in.set(newCookie);
    jQuery("#cookie_opt_in_container form input[type=checkbox]").not('[disabled]').attr('checked', true);
  }
  if (jQuery(el).is('.coia-ok')) {
    setTimeout('cookie_opt_in.update_cookie()', 100);
  }
};
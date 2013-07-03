var cookie_opt_in = {
  /* cookie destroyer does not work on google cookies. so I doubt it will ever
  destroy_unwelcome_cookies: function() {
    if (typeof cookie_opt_in_settings.destroy === 'undefined') return;
    var d = cookie_opt_in_settings.destroy;
    var cookie=false;
    if (d.length > 0) {
      for (var i = 0; i < d.length; i++) {
        cookie = cookie_opt_in.get_cookie(d[i]);
        if (cookie !== false && cookie !== null) {
          document.cookie = cookie[0]+"=; expires=Thu, 01-Jan-70 00:00:01 GMT; path=/";
        }
      }
    }
  }, */
  get_cookie: function(name) {
    var nameEQ = new RegExp('^(' + name + ")=(.*)$");
    var ca = document.cookie.split(';');
    var c=null, r=false, p=0;
    for(var i=0;i < ca.length;i++) {
      c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1,c.length);
      }
      r = c.match(nameEQ);
      if (r !== null && r !== false) {
        p = r[0].indexOf('=');
        r = [ r[0].substring(0,p), r[0].substring(p+1) ];
        return r;
      }
    }
    return [];
  },
  get: function() {
    // read the cookie with name set in the plugin
    // if it exists, check the contents.
    // if the contents is valid, then return it.
    // otherwise, return default values.
    var cookie = cookie_opt_in.get_cookie(cookie_opt_in_settings.preference_cookie_name);
    var cookie_value = { value: cookie[1], is_new: false };
    if (!cookie_value.value) {
      cookie_value.value = cookie_opt_in_settings.default_cookie;
      cookie_value.is_new = true;
    }
    cookie_value.value = cookie_opt_in.expand(cookie_value.value);

    return cookie_value;
  },
  set: function (cookie_object) {
    if (typeof cookie_object.value == 'undefined') return;
    var value = cookie_opt_in.compress(cookie_object.value);
    if (value === '') return;
    var expires = "; expires="+cookie_opt_in_settings.preference_cookie_expires;
    var date;
    document.cookie = cookie_opt_in_settings.preference_cookie_name+"="+value+expires+"; path=/";
  },
  expand: function(compressed) {
    var expanded = {};
    for (var i = 0; i < compressed.length; i = i + 2) {
      expanded[ compressed.charAt(i) ] = compressed.charAt(i+1);
    }
    return expanded;
  },
  compress: function(expanded) {
    var compressed = '';
    for (var i in expanded) {
      compressed = compressed + i + expanded[i];
    }
    return compressed;
  },
  checkboxes: function() {
    var types = cookie_opt_in_settings.cookie_types;
    var checked = false;
    var i = types.length;
    var html = '';
    var type = null;
    var preferences = cookie_opt_in.get();
    var label_tag = '';
    var brief_tag = '';
    var cookie_tag = '';
    var disabled;
    var cookie_label;
    var cookie_brief;
    var more_info_url;
		var more_info_same_window;
    for (var j = 0; j < i; j ++) {
      disabled = false;
      type = types[j];
      label_tag = type.replace('site_has_', 'label_');
      brief_tag = type.replace('site_has_', 'brief_info_on_');
      more_tag = type.replace('site_has_', 'more_info_');
      cookie_tag = type.charAt(9);
      cookie_value = preferences.value[cookie_tag];
      cookie_label = cookie_opt_in_settings[label_tag];
      cookie_brief = cookie_opt_in_settings[brief_tag];
      more_info_url = cookie_opt_in_settings[more_tag];
			more_info_same_window = cookie_opt_in_settings['more_info_same_window'] == "yes" ? true : false;
//			console.log(more_info_same_window);

      if (typeof more_info_url !== 'undefined' && more_info_url !== '') {
        cookie_brief = cookie_brief + '<a href="'+ more_info_url + '" ';
				if (!more_info_same_window)
				{
					cookie_brief += 'target="_blank" ';
				}
				cookie_brief += 'class="more">'+ cookie_opt_in_settings.more_info_text +'</a>';
      }

      if (cookie_opt_in_settings.always_on.hasOwnProperty(type)) {
        disabled = true;
        if (typeof cookie_opt_in_settings.always_on_remark !== 'undefined')
          cookie_brief = cookie_brief + '<span class="always-on">' + cookie_opt_in_settings.always_on_remark + '</span>';
      }

      if (cookie_opt_in_settings.all_or_nothing == 1) {
        html = html + cookie_opt_in.nocheckbox(cookie_tag, cookie_value, cookie_label, cookie_brief, disabled);
      }
      else {
        html = html + cookie_opt_in.checkbox(cookie_tag, cookie_value, cookie_label, cookie_brief, disabled);
      }
    }

    if (typeof cookie_opt_in_settings.more_info_url !== 'undefined' && cookie_opt_in_settings.more_info_url !== '') {
      html = html + '<a href="'+ cookie_opt_in_settings.more_info_url + '" ';
			if (!more_info_same_window)
			{
				html += 'target="_blank" ';
			}
			html += 'class="more generic-more">'+ cookie_opt_in_settings.more_info_text +'</a>';
    }

    return html;
  },
  checkbox: function(name, current_state, label, info, disabled) {
    current_state = current_state == '0' ? '' : ' checked="checked" ';
    disabled = disabled ? ' disabled="disabled" ' : '';
    return '<span class="checkbox"><input type="checkbox" value="1" '+ current_state + disabled + ' id="cookie_opt_in_'+ name +'" name="'+ name +'"><label for="cookie_opt_in_'+ name +'">' + label + '<span class="qmark">?</span></label><span class="info">'+info+'</span></span>';
  },
  nocheckbox: function(name, current_state, label, info, disabled) {
    current_state = current_state == '0' ? '' : ' checked="checked" ';
    disabled = disabled ? ' disabled="disabled" ' : '';
    return '<span class="checkbox"><label>' + label + '<span class="qmark">?</span></label><span class="info">'+info+'</span></span>';
  },
  init: function() {
    /* cookie destroyer does not work on google cookies. so I doubt it will ever */
    // cookie_opt_in.destroy_unwelcome_cookies();
    if (typeof cookie_opt_in.init_before == 'function') cookie_opt_in.init_before();
    var cookie = cookie_opt_in.get();
    var html = '<div id="cookie_opt_in_container" class="lang-' + cookie_opt_in_settings.lang + '" style="display: none;"><form id="cookie_opt_in"><h1>' + cookie_opt_in_settings.form_title +'</h1>'+ cookie_opt_in.checkboxes();
      if (cookie_opt_in_settings.all_or_nothing == 1) {
        html = html + '<input class="button" type="button" id="coia-accept-all" value="'+ cookie_opt_in_settings.label_allow +'" />';
        html = html + '<input class="button" type="button" id="coia-deny-all" value="'+ cookie_opt_in_settings.label_deny +'" />';
      }
      else {
        html = html + '<input class="button" type="button" id="coia-ok" value="'+ cookie_opt_in_settings.label_ok +'" />';
      }
        html = html + '</form></div>';
        html = html + '<div id="cookie_opt_in_anchor"><a id="cookie_opt_in_toggle" href="javascript:void(0);">' + cookie_opt_in_settings.anchor_title + '</a></div>';
    jQuery('body').append(html);
    if (typeof cookie_opt_in.init_after == 'function') cookie_opt_in.init_after();
    cookie_opt_in.activate();
    if (cookie.is_new) {
      cookie_opt_in.show(true);
    }
  },
  update_cookie: function() {
    var cookie_value = { value: {} };
    jQuery('form#cookie_opt_in input[type=checkbox]').each( function () {
      var the_name = jQuery(this).attr('name');
      if (the_name.length == 1) {
        cookie_value.value[ the_name ] = jQuery(this).is(':checked') ? '1' : '0';
      }
    });
    cookie_opt_in.set(cookie_value);
  },
  activate: function() {
    if (typeof cookie_opt_in.activate_before == 'function') cookie_opt_in.activate_before();
    jQuery('form#cookie_opt_in input').bind('click', function() { setTimeout('cookie_opt_in.update_cookie()', 100); });
    jQuery('#cookie_opt_in_toggle').bind('click', function() {
      if (cookie_opt_in.active_state) {
        cookie_opt_in.hide();
      }
      else {
        cookie_opt_in.show();
      }
    });
    jQuery('#cookie_opt_in_container .button').live('click', function(e) {
      if (typeof cookie_opt_in.action_before == 'function') cookie_opt_in.action_before(this);
      var newCookie= cookie_opt_in_settings.default_cookie;
      if (jQuery(this).attr('id') == 'coia-accept-all') {
        newCookie = { value: cookie_opt_in.expand(newCookie.replace(/0/g, 1)) };
        cookie_opt_in.set(newCookie);
      }
      else if (jQuery(this).attr('id') == 'coia-deny-all') {
        newCookie = { value: cookie_opt_in.expand(newCookie.replace(/1/g, 0).replace('f0', 'f1')) };
        cookie_opt_in.set(newCookie);
      }
      if (typeof cookie_opt_in.action_after == 'function') cookie_opt_in.action_after(this);
      cookie_opt_in.hide();
      return false;
    });
    if (typeof cookie_opt_in.activate_after == 'function') cookie_opt_in.activate_after();
  },
  active_state: false,
  show: function (is_new) {
    is_new = is_new || false;
    if (typeof cookie_opt_in.show_before == 'function') cookie_opt_in.show_before(is_new);
    cookie_opt_in.active_state = true;
    jQuery('#cookie_opt_in_container').show();
    if (typeof cookie_opt_in.show_after == 'function') cookie_opt_in.show_after(is_new);
  },
  hide: function () {
    if (typeof cookie_opt_in.hide_before == 'function') cookie_opt_in.hide_before();
    cookie_opt_in.active_state = false;
    jQuery('#cookie_opt_in_container').hide();
    if (typeof cookie_opt_in.hide_after == 'function') cookie_opt_in.hide_after();
  }
};

jQuery(document).ready( function () {
  if (cookie_opt_in_settings) cookie_opt_in.init();
});

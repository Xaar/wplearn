cookie_opt_in._interface_element_open = false;
cookie_opt_in.jQueryversion = (jQuery().jquery).split('.');
cookie_opt_in.version_ok = ( (cookie_opt_in.jQueryversion[0] == '1' && cookie_opt_in.jQueryversion[1] >= 3) || cookie_opt_in.jQueryversion[0] > 1 );

cookie_opt_in.init_after = function () {
  jQuery('#cookie_opt_in .checkbox label .qmark').bind('click', function (e) {
    e.preventDefault();
    if (cookie_opt_in._interface_element_open !== false) {
      if (cookie_opt_in.version_ok) cookie_opt_in._interface_element_open.stop();
      cookie_opt_in._interface_element_open.slideUp();
    }
    cookie_opt_in._interface_element_open = jQuery(this).parent().siblings('.info');
    if (cookie_opt_in.version_ok) cookie_opt_in._interface_element_open.stop();
    cookie_opt_in._interface_element_open.slideDown();
  });
};

cookie_opt_in.hide_after = function () {
  if (cookie_opt_in._interface_element_open !== false) {
    if (cookie_opt_in.version_ok) cookie_opt_in._interface_element_open.stop();
    cookie_opt_in._interface_element_open.hide();
    cookie_opt_in._interface_element_open = false;
  }
};

/**
* easyModal.js v1.1.0
* A minimal jQuery modal that works with your CSS.
* Author: Flavius Matis - http://flaviusmatis.github.com/
* URL: https://github.com/flaviusmatis/easyModal.js
*/

(function(jQuery){

	var methods = {
		init : function(options) {

			var defaults = {
				top: 'auto',
				autoOpen: false,
				overlayOpacity: 0.5,
				overlayColor: '#000',
				overlayClose: true,
				overlayParent: 'body',
				closeOnEscape: true,
				closeButtonClass: '.close',
				onOpen: false,
				onClose: false
			};

			options = jQuery.extend(defaults, options);

			return this.each(function() {

				var o = options;

				var $overlay = jQuery('<div class="lean-overlay"></div>');

				$overlay.css({
					'display': 'none',
					'position': 'fixed',
					'z-index': 2000,
					'top': 0,
					'left': 0,
					'height': 100 + '%',
					'width': 100+ '%',
					'background': o.overlayColor,
					'opacity': o.overlayOpacity
				}).appendTo(o.overlayParent);

				var $modal = $(this);

				$modal.css({
					'display': 'none',
					'position' : 'absolute',
					'z-index': 2001,
					'left' : 50 + '%',
//					'top' : parseInt(o.top) > -1 ? o.top + 'px' : 50 + '%',
					'top' : 60,
					'margin-left' : -($modal.outerWidth()/2) + 'px'
//					'margin-top' : (parseInt(o.top) > -1 ? 0 : -($modal.outerHeight()/2)) + 'px'
				});

				$modal.bind('openModal', function(){
					jQuery(this).css('display', 'block');
					$overlay.fadeIn(200, function(){
						if (o.onOpen && typeof (o.onOpen) === 'function') {
							// onOpen callback receives as argument the modal window
							o.onOpen($modal[0]);
						}
					});
				});

				$modal.bind('closeModal', function(){
					jQuery(this).css('display', 'none');
					$overlay.fadeOut(200, function(){
						if (o.onClose && typeof(o.onClose) === 'function') {
							// onClose callback receives as argument the modal window
							o.onClose($modal[0]);
						}
					});
					$f("*").each(function() {
						$f(this.id()).pause();
					});
				});

				// Resize Window
                                jQuery(window).resize(function(e) {
	                               $modal.css({
                        //        	        'left' : 50 + '%',
                                        	'margin-left' : -($modal.outerWidth()/2) + 'px'
					});
                                });

				// Close on overlay click
				$overlay.on('click', function(e) {
					$modal.trigger('closeModal');
				});

				jQuery(document).keydown(function(e) {
					// ESCAPE key pressed
					if (o.closeOnEscape && e.keyCode == 27) {
						$modal.trigger('closeModal');
					}
				});

				// Close when button pressed
				$modal.find(o.closeButtonClass).click(function(e) {
					$modal.trigger('closeModal');
					e.preventDefault();
				});

				// Automatically open modal if option set
				if (o.autoOpen)
					$modal.trigger('openModal');

			});

		}
	};

	jQuery.fn.easyModal = function(method) {

		// Method calling logic
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || ! method) {
			return methods.init.apply(this, arguments);
		} else {
			jQuery.error('Method ' + method + ' does not exist on jQuery.easyModal');
		}

	};

})(jQuery);


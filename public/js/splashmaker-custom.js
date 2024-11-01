(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	
			'use strict';
		
			$.fn.firstVisitPopup = function (settings) {
		
				var $body = $('body');
				var $dialog = $(this);
				var $blackout;
				var setCookie = function (name, value) {
					var date = new Date(),
						expires = 'expires=';
					date.setTime(date.getTime() + 1);
					expires += date.toGMTString();
					document.cookie = name + '=' + value + '; ' + expires + '; path=/';
				}
				var getCookie = function (name) {
					var allCookies = document.cookie.split(';'),
						cookieCounter = 0,
						currentCookie = '';
					for (cookieCounter = 0; cookieCounter < allCookies.length; cookieCounter++) {
						currentCookie = allCookies[cookieCounter];
						while (currentCookie.charAt(0) === ' ') {
							currentCookie = currentCookie.substring(1, currentCookie.length);
						}
						if (currentCookie.indexOf(name + '=') === 0) {
							return currentCookie.substring(name.length + 1, currentCookie.length);
						}
					}
					return false;
				}
				var showMessage = function () {
					$blackout.show();
					$dialog.show();
				}
				var hideMessage = function () {
					$blackout.hide();
					$dialog.hide();
					setCookie('fvpp' + settings.cookieName, 'true');
				}
		
				$body.append('<div id="fvpp-blackout"></div>');
				$dialog.append('<a id="fvpp-close">X</a>');
				$blackout = $('#fvpp-blackout');
		
				if (getCookie('fvpp' + settings.cookieName)) {
					hideMessage();
				} else {
					showMessage();
				}
		
				$(settings.showAgainSelector).on('click', showMessage);
				$body.on('click', '#fvpp-blackout, #fvpp-close', hideMessage);
		
			};
			// for json data
			$.fn.serializeFormJSON = function () {
				var o = {};
				var a = this.serializeArray();
				$.each(a, function () {
					if (o[this.name]) {
						if (!o[this.name].push) {
							o[this.name] = [o[this.name]];
						}
						o[this.name].push(this.value || '');
					} else {
						o[this.name] = this.value || '';
					}
				});
				return o;
			};
})( jQuery );

 function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === null ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};



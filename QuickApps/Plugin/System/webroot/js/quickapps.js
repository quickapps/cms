var QuickApps = QuickApps || {'settings': {}, 'behaviors': {}, 'locale': {'strings': {}}};

(function ($) {
/**
 * Translate strings to the page language or a given language.
 * See the documentation of the server-side __t() function for further details.
 *
 * @param str
 *   A string containing the English string to translate.
 * @return
 *   The translated string.
 */
	QuickApps.__t = function (str) {
		var args = Array.prototype.slice.apply(arguments, [1]);
		// Fetch the localized version of the string.
		if (QuickApps.locale.strings && QuickApps.locale.strings[str]) {
			str = QuickApps.locale.strings[str];
		}

		if (args) {
			call = "QuickApps.sprintf(str";
			for (var key in args) {
				call += ", args[" + key + "]";
			}
			call += ");";
			str = eval(call);
		}

		return str;
	};

/**
 * Encode special characters in a plain-text string for display as HTML.
 *
 */
	QuickApps.checkPlain = function (str) {
	  var character, regex,
		  replace = { '&': '&amp;', '"': '&quot;', '<': '&lt;', '>': '&gt;' };
	  str =  (str);
	  for (character in replace) {
		if (replace.hasOwnProperty(character)) {
		  regex = new RegExp(character, 'g');
		  str = str.replace(regex, replace[character]);
		}
	  }
	  return str;
	};

/**
 * PHP sprintf() equivalent
 *
 */
	QuickApps.sprintf = function () {
		var i = 0,
		f = arguments[i++],
		o = [],
		m,
		a,
		p;
		while (f) {
			if (m = /^[^\x25]+/.exec(f)) o.push(m[0]);
			else if (m = /^\x25{2}/.exec(f)) o.push('%');
			else if (m = /^\x25(\+)?(0|'[^$])?(-)?(\d+)?(\.\d+)?([b-fosuxX])/.exec(f)) {
				if (! (a = arguments[i++])) throw ("Too few arguments.");
				if (/[^s]/.test(m[6]) && (typeof(a) != 'number')) throw ("Expecting number but found " + typeof(a));
				switch (m[6]) {
				case 'b':
					a = a.toString(2);
					break;
				case 'c':
					a = String.fromCharCode(a);
					break;
				case 'd':
					a = parseInt(a);
					break;
				case 'e':
					a = m[5] ? a.toExponential(m[5].charAt(1)) : a.toExponential();
					break;
				case 'f':
					a = m[5] ? parseFloat(a).toFixed(m[5].charAt(1)) : parseFloat(a);
					break;
				case 'o':
					a = a.toString(8);
					break;
				case 's':
					a = ((a = String(a)) && m[5] ? a.substring(0, m[5].charAt(1)) : a);
					break;
				case 'u':
					a = Math.abs(a);
					break;
				case 'x':
					a = a.toString(16);
					break;
				case 'X':
					a = a.toString(16).toUpperCase();
					break;
				}
				a = (/[def]/.test(m[6]) && m[1] && a > 0 ? '+' + a: a);
				p = m[4] ? str_repeat(m[2] ? m[2] == '0' ? '0': m[2].charAt(1) : ' ', m[5] ? m[4] - String(a).length: m[4]) : '';
				o.push(m[3] ? a + p: p + a);
			} else throw ("Huh ?");
			f = f.substring(m[0].length);
		}
		return (o.join(''));
	};

/**
 * Creates a cookie.
 *
 */
	QuickApps.setCookie = function (name, value, expires, path) {
		var today = new Date();
		today.setTime(today.getTime());
		if (expires) {
			expires = expires * 1000 * 60 * 60 * 24;
		} else {
			expires = 0;
		}
		var expires_date = new Date(today.getTime() + expires);
		document.cookie = name + "=" + escape(value) + "; expires=" + expires_date.toGMTString() + ((path) ? "; path=" + path: "; path=/;");
	};

/**
 * Check all checkbox in page.
 * 
 */
	QuickApps.checkAll = function (el) {
		QuickApps.checkAllByClassName(el, '');
	};	

/**
 * Check all checkbox in page matching the given className.
 * 
 */
	QuickApps.checkAllByClassName = function (el, className) {
		var className = className.length > 0 ? '.' + className : '' ;
		if (el.checked == true){ c = true; } else { c = false; }
		$('input[type="checkbox"]').attr('checked', c);
	};

/**
 * Silently discard extra submits for the given form element.
 *
 */
	QuickApps.preventDoubleSubmit = function (el) {
		el.submitted = false;

		jQuery(el).submit(function () {
		if (el.submitted) {
			  return false;
			} else {
			  el.submitted = true;
			}
		});
	};

/**
 * Class indicating that JS is enabled; used for styling purpose.
 *
 */
	$('html').addClass('js');

/**
 * 'js enabled' cookie.
 *
 */
	QuickApps.setCookie('has_js', 1);

/**
 * Additions to jQuery.support.
 *
 */
	$(function () {
		// Boolean indicating whether or not position:fixed is supported.
		if (jQuery.support.positionFixed === undefined) {
			var el = $('<div style="position:fixed; top:10px" />').appendTo(document.body);
			jQuery.support.positionFixed = el[0].offsetTop === 10;
			el.remove();
		}
	});
})(jQuery);

$(document).ready(function() {
	$.ajaxSetup({cache: false});

	// auto-toggleable fieldsets
	$("span.fieldset-toggle").each(function () {
		$(this).css('cursor', 'pointer');
		$(this).click(function () {
			$(this).parent("legend").next(".fieldset-toggle-container").toggle("fast", "linear");
		});
	});

	// prevent double submit on every form in page
	$('form').each(function() {
		QuickApps.preventDoubleSubmit($(this));
	});

	// FF fix
	if ($.browser.mozilla) {
		window.addEventListener('pageshow', function(e) {
			$('form').each(function() {
				$(this).unbind('submit');
				QuickApps.preventDoubleSubmit($(this));
			});
		}, false);
	}
});
var QuickApps = QuickApps || { 'settings': {}, 'behaviors': {}, 'locale': { 'strings': {} } };

(function ($) {
/**
 * Translate strings to the page language or a given language.
 *
 * See the documentation of the server-side __() function for further details.
 *
 * @param str
 *   A string containing the English string to translate.
 * @param args
 *   An object of replacements pairs to make after translation. Incidences
 *   of any key in this array are replaced with the corresponding value.
 *   Based on the first character of the key, the value is escaped and/or themed:
 *    - !variable: inserted as is
 *    - @variable: escape plain text to HTML (QuickApps.checkPlain)
 *    - %variable: escape text and theme as a placeholder for user-submitted
 *      content (checkPlain + QuickApps.theme('placeholder'))
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
            // Transform arguments before inserting them.
            for (var key in args) {
                switch (key.charAt(0)) {
                    // Escaped only.
                    case '@':
                        args[key] = QuickApps.checkPlain(args[key]);
                    break;
                    
                    // Pass-through.
                    case '!':
                    break;
                    
                    // Escaped and placeholder.
                    case '%':
                        default:
                      args[key] = QuickApps.theme('placeholder', args[key]);
                    break;
                }
                str = str.replace(key, args[key]);
            }
        }
        return str;
    };
    
/**
 * Generate the themed representation of a QuickApps object.
 *
 * All requests for themed output must go through this function. It examines
 * the request and routes it to the appropriate theme function. If the current
 * theme does not provide an override function, the generic theme function is
 * called.
 *
 * For example, to retrieve the HTML for text that should be emphasized and
 * displayed as a placeholder inside a sentence, call
 * QuickApps.theme('placeholder', text).
 *
 * @param func
 *   The name of the theme function to call.
 * @param ...
 *   Additional arguments to pass along to the theme function.
 * @return
 *   Any data the theme function returns. This could be a plain HTML string,
 *   but also a complex object.
 */    
    QuickApps.theme = function (func) {
        var args = Array.prototype.slice.apply(arguments, [1]);
        return (QuickApps.theme[func] || QuickApps.theme.prototype[func]).apply(this, args);
    };
    
/**
 * Encode special characters in a plain-text string for display as HTML.
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
 * PHP sprintf equivalent
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
    
/* 
 * Creates a cookie
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
    
/* 
 * Check all checkbox in page
 * 
 */
    QuickApps.checkAll = function (el) {
        QuickApps.checkAllByClassName(el, '');
    };    
    
/* 
 * Check all checkbox in page with className
 * 
 */
    QuickApps.checkAllByClassName = function (el, className) {
        var className = className.length > 0 ? '.' + className : '' ;
        if (el.checked == true){ c = true; } else { c = false; }
        $('input[type="checkbox"]').attr('checked', c);
    };    
    
// Class indicating that JS is enabled; used for styling purpose.
    $('html').addClass('js');

// 'js enabled' cookie.
    QuickApps.setCookie('has_js', 1);

/**
 * Additions to jQuery.support.
 */
    $(function () {
      /**
       * Boolean indicating whether or not position:fixed is supported.
       * 
       */
      if (jQuery.support.positionFixed === undefined) {
        var el = $('<div style="position:fixed; top:10px" />').appendTo(document.body);
        jQuery.support.positionFixed = el[0].offsetTop === 10;
        el.remove();
      }
    });
    
/**
 * The default themes.
 */
    QuickApps.theme.prototype = {
        /**
        * Formats text for emphasized display in a placeholder inside a sentence.
        *
        * @param str
        *   The text to format (plain-text).
        * @return
        *   The formatted text (html).
        */
      placeholder: function (str) {
        return '<em class="placeholder">' + QuickApps.checkPlain(str) + '</em>';
      }
    };
    
})(jQuery);
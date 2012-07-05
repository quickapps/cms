<?php
/**
 * Js Helper
 *
 * PHP version 5
 *
 * @package	 QuickApps.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class QaJsHelper extends AppHelper {
/**
 * Other helpers used by QaFormHelper
 *
 * @var array
 * @access public
 */
	public $helpers = array('CoreJs' => array('className' => 'Js'));

/**
 * call__ Allows for dispatching of methods to the Engine Helper.
 * methods in the Engines bufferedMethods list will be automatically buffered.
 * You can control buffering with the buffer param as well. By setting the last parameter to
 * any engine method to a boolean you can force or disable buffering.
 *
 * e.g. `$js->get('#foo')->effect('fadeIn', array('speed' => 'slow'), true);`
 *
 * Will force buffering for the effect method. If the method takes an options array you may also add
 * a 'buffer' param to the options array and control buffering there as well.
 *
 * e.g. `$js->get('#foo')->event('click', $functionContents, array('buffer' => true));`
 *
 * The buffer parameter will not be passed onto the EngineHelper.
 *
 * @param string $method Method to be called
 * @param array $params Parameters for the method being called.
 * @return mixed Depends on the return of the dispatched method, or it could be an instance of the EngineHelper
 */
	public function __call($method, $params) {
	   return $this->CoreJs->__call($method, $params);
	}

/**
 * Overwrite inherited Helper::value()
 * See JsBaseEngineHelper::value() for more information on this method.
 *
 * @param mixed $val A PHP variable to be converted to JSON
 * @param boolean $quoteString If false, leaves string values unquoted
 * @return string a JavaScript-safe/JSON representation of $val
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/js.html#JsHelper::value
 **/
	public function value($val = array(), $quoteString = null, $key = 'value') {
		$data = compact('var', 'quoteString', 'key');

		$this->hook('js_value_alter', $data);
		extract($data);

		return $this->CoreJs->value($val, $quoteString, $key);
	}

/**
 * Writes all Javascript generated so far to a code block or
 * caches them to a file and returns a linked script.  If no scripts have been
 * buffered this method will return null.  If the request is an XHR(ajax) request
 * onDomReady will be set to false. As the dom is already 'ready'.
 *
 * ### Options
 *
 * - `inline` - Set to true to have scripts output as a script block inline
 *   if `cache` is also true, a script link tag will be generated. (default true)
 * - `cache` - Set to true to have scripts cached to a file and linked in (default false)
 * - `clear` - Set to false to prevent script cache from being cleared (default true)
 * - `onDomReady` - wrap cached scripts in domready event (default true)
 * - `safe` - if an inline block is generated should it be wrapped in <![CDATA[ ... ]]> (default true)
 *
 * @param array $options options for the code block
 * @return mixed Completed javascript tag if there are scripts, if there are no buffered
 *   scripts null will be returned.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/js.html#JsHelper::writeBuffer
 */
	public function writeBuffer($options = array()) {
		$this->hook('js_write_buffer_alter', $options);

		$this->CoreJs->writeBuffer($options);
	}

/**
 * Write a script to the buffered scripts.
 *
 * @param string $script Script string to add to the buffer.
 * @param boolean $top If true the script will be added to the top of the
 *   buffered scripts array.  If false the bottom.
 * @return void
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/js.html#JsHelper::buffer
 */
	public function buffer($script, $top = false) {
		$data = compact('script', 'top');

		$this->hook('js_buffer_alter', $data);
		extract($data);

		return $this->CoreJs->buffer($script, $top);
	}

/**
 * Get all the buffered scripts
 *
 * @param boolean $clear Whether or not to clear the script caches (default true)
 * @return array Array of scripts added to the request.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/js.html#JsHelper::getBuffer
 */
	public function getBuffer($clear = true) {
		$this->hook('js_get_buffer_alter', $clear);

		return $this->CoreJs->getBuffer($clear);
	}

/**
 * Generate an 'Ajax' link.  Uses the selected JS engine to create a link
 * element that is enhanced with Javascript.  Options can include
 * both those for HtmlHelper::link() and JsBaseEngine::request(), JsBaseEngine::event();
 *
 * ### Options
 *
 * - `confirm` - Generate a confirm() dialog before sending the event.
 * - `id` - use a custom id.
 * - `htmlAttributes` - additional non-standard htmlAttributes.  Standard attributes are class, id,
 *	rel, title, escape, onblur and onfocus.
 * - `buffer` - Disable the buffering and return a script tag in addition to the link.
 *
 * @param string $title Title for the link.
 * @param mixed $url Mixed either a string URL or an cake url array.
 * @param array $options Options for both the HTML element and Js::request()
 * @return string Completed link. If buffering is disabled a script tag will be returned as well.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/js.html#JsHelper::link
 */
	public function link($title, $url = null, $options = array()) {
		$data = compact('title', 'url', 'options');

		$this->hook('js_link_alter', $data);
		extract($data);

		return $this->CoreJs->link($title, $url, $options);
	}

/**
 * Pass variables into Javascript.  Allows you to set variables that will be
 * output when the buffer is fetched with `JsHelper::getBuffer()` or `JsHelper::writeBuffer()`
 * The Javascript variable used to output set variables can be controlled with `JsHelper::$setVariable`
 *
 * @param mixed $one Either an array of variables to set, or the name of the variable to set.
 * @param mixed $two If $one is a string, $two is the value for that key.
 * @return void
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/js.html#JsHelper::set
 */
	public function set($one, $two = null) {
		$data = compact('one', 'two');

		$this->hook('js_set_alter', $data);
		extract($data);

		return $this->CoreJs->set($one, $two);
	}

/**
 * Uses the selected JS engine to create a submit input
 * element that is enhanced with Javascript.  Options can include
 * both those for FormHelper::submit() and JsBaseEngine::request(), JsBaseEngine::event();
 *
 * Forms submitting with this method, cannot send files. Files do not transfer over XmlHttpRequest
 * and require an iframe or flash.
 *
 * ### Options
 *
 * - `url` The url you wish the XHR request to submit to.
 * - `confirm` A string to use for a confirm() message prior to submitting the request.
 * - `method` The method you wish the form to send by, defaults to POST
 * - `buffer` Whether or not you wish the script code to be buffered, defaults to true.
 * - Also see options for JsHelper::request() and JsHelper::event()
 *
 * @param string $caption The display text of the submit button.
 * @param array $options Array of options to use. See the options for the above mentioned methods.
 * @return string Completed submit button.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/js.html#JsHelper::submit
 */
	public function submit($caption = null, $options = array()) {
		$data = compact('caption', 'options');

		$this->hook('js_submit_alter', $data);
		extract($data);

		return $this->CoreJs->set($caption, $options);
	}
}
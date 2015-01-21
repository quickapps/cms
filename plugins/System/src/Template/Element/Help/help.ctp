<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<h2>About</h2>
<p>
	The System plugin is integral to the site, and provides basic but extensible
	functionality for use by other plugins and themes. Some integral elements of
	QuickApps are contained in and managed by the System plugin, including caching,
	enabling and disabling plugins and themes and configuring fundamental site
	settings.
</p>

<h2>Uses</h2>
<dl>
	<dt>Hooktags</dt>
	<dd>
		<p>
			A Hooktag is a QuickApps-specific code that lets you do nifty things
			with very little effort. Hooktags can for example print current language
			code (i.e. "en") or call especifics plugins/themes functions. Plugins and
			themes are able to define their own hooktags. The <em>System</em>
			plugin provides a series of buil-in	hooktags as described below:
		</p>

		<hr />

		<p>
			<code>[locale OPTION/]</code>
			<p>
				Possible values of OPTION are: <em>code, name, native or direction.</em>
				<ul>
					<li><strong>code</strong>: Returns language's <a href="http://www.sil.org/iso639-3/codes.asp" target="_blank">ISO 639-2 code</a> (en, es, fr, etc)</li>
					<li><strong>name</strong>: Returns language's English name (English, Spanish, German, French, etc)</li>
					<li><strong>direction</strong>: Returns direction that text is presented. <em>lft</em> (Left To Right) or <em>rtl</em> (Right to Left)</li>
				</ul>
			</p>
		</p>

		<p>
			<code>[locale /]</code>
			<p>Shortcut for [language code] which return current language code (en, es, fr, etc).</p>
		</p>

		<p>
			<code>[t domain=DOMAIN]text to translate by domain[/t]</code>
			<p>Search for translation in specified domain, e.g: [t domain=System]Help[/t] will try to find translation for <em>Help</em> in <em>System</em> plugin translation table.</p>
		</p>

		<p>
			<code>[t]text to translate using default domain[/t]</code>
			<p>Search for translation in default translation domain.</p>
		</p>

		<p>
			<code>[url]/some_path/image.jpg[/url]</code>
			<p>Return well formatted url. URL can be an relative url (/type-of-content/my-post.html) or external (http://www.example.com/my-url).</p>
		</p>

		<p>
			<code>[date format=FORMAT]TIME_STAMP_OR_ENGLISH_DATE[/date]</code>
			<p>
				Returns php result of <em>date(FORMAT, TIME_STAMP_OR_ENGLISH_DATE)</em>. <a href="http://www.php.net/manual/function.date.php" target="_blank">More info about date()</a><br/>
				It accepts both: numeric time stamp or english formatted date (Year-month-day Hours:Mins:Secs) as second parameter.
			</p>
		</p>

		<p>
			<code>[date format=FORMAT /]</code>
			<p>Returns php result of <em>date(FORMAT)</em>. <a href="http://www.php.net/manual/function.date.php" target="_blank">More info about date()</a></p>
		</p>

		<p>
			<code>[random]values,by,comma[/random]</code>
			<p>
				Returns a random value from the specified group. e.g. [random]one,two,three[/random].<br />
				If only two numeric values are given as group, then PHP function <a href="http://www.php.net/manual/function.rand.php" target="_blank">rand(min, max)</a> is returned. e.g.: [random]3,10[/random]
			</p>
		</p>
	</dd>

	<dt>Managing plugins</dt>
	<dd>
		The System plugin allows users with the appropriate permissions to enable and disable plugins on the
		<?php echo $this->Html->link('Plugins administration page', ['plugin' => 'System', 'controller' => 'plugins', 'prefix' => 'admin']); ?>.
		QuickAppsCMS comes with a number of core plugins, and each plugin provides a discrete set of features and may be enabled or disabled depending on the needs of the site.
	</dd>

	<dt>Managing themes</dt>
	<dd>
		The System plugin allows users with the appropriate permissions to enable and disable themes on the <?php echo $this->Html->link('Appearance administration page', ['plugin' => 'System', 'controller' => 'themes', 'prefix' => 'admin']); ?>.
		Themes determine the design and presentation of your site.
		QuickAppsCMS comes packaged with two core themes (FrontendTheme and BackendTheme)
	</dd>

	<dt>Configuring basic site settings</dt>
	<dd>
		The System plugin also handles basic configuration options for your site,
		including Date and time settings, Site name and other information</a>.
	</dd>
</dl>
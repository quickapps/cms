<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Field\Utility;

use QuickApps\Core\Plugin;

/**
 * FileToolbox class for handling files related tasks.
 *
 * A set of utility methods related to files, such as `bytesToSize()`,
 * `fileIcon()`, etc.
 */
class FileToolbox {

/**
 * Gets a translated string representation of the size.
 *
 * @param integer $bytes Size to convert given in bytes units
 * @param integer $precision Decimal precision
 * @return string
 */
	public static function bytesToSize($bytes, $precision = 2) {
		$kilobyte = 1024;
		$megabyte = $kilobyte * 1024;
		$gigabyte = $megabyte * 1024;
		$terabyte = $gigabyte * 1024;

		if (($bytes >= 0) && ($bytes < $kilobyte)) {
			return $bytes . ' B';
		} elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
			return round($bytes / $kilobyte, $precision) . ' KB';
		} elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
			return round($bytes / $megabyte, $precision) . ' MB';
		} elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
			return round($bytes / $gigabyte, $precision) . ' GB';
		} elseif ($bytes >= $terabyte) {
			return round($bytes / $terabyte, $precision) . ' TB';
		} else {
			return $bytes . ' B';
		}
	}

/**
 * Renders the given custom field.
 * 
 * @param \Cake\View\View $View Instance of view class
 * @param \Field\Model\Entity\Field $field The field to be rendered
 * @return string HTML code
 */
	public static function formatter($View, $field) {
		switch ($field->view_mode_settings['formatter']) {
			case 'link':
				return $View->element('Field.FileField/display_link', compact('field'));
			break;

			case 'table':
				return $View->element('Field.FileField/display_table', compact('field'));
			break;

			case 'url':
				default:
					return $View->element('Field.FileField/display_url', compact('field'));
			break;
		}
	}

/**
 * Creates a path to the icon for a file mime.
 *
 * @param string $mime file mime type
 * @param mixed $iconsDirectory A path to a directory of icons to be used for
 * files. Defaults to built-in icons directory (Field/webroot/img/file-icons/)
 * @return string A string to the icon as a local path, or false if an appropriate
 * icon could not be found
 */
	public static function fileIcon($mime, $iconsDirectory = false) {
		$iconsDirectory = !$iconsDirectory ? Plugin::path('Field') . 'webroot/img/file-icons/' : $iconsDirectory;

		// If there's an icon matching the exact mimetype, go for it.
		$dashedMime = strtr($mime, array('/' => '-'));
		$iconPath = "{$iconsDirectory}{$dashedMime}.png";

		if (file_exists($iconPath)) {
			return "{$dashedMime}.png";
		}

		// For a few mimetypes, we can "manually" map to a generic icon.
		$genericMime = (string)static::fileIconMap($mime);
		$iconPath = "{$iconsDirectory}{$genericMime}.png";

		if ($genericMime && file_exists($iconPath)) {
			return "{$genericMime}.png";
		}

		// Use generic icons for each category that provides such icons.
		foreach (array('audio', 'image', 'text', 'video') as $category) {
			if (strpos($mime, $category . '/') === 0) {
				$iconPath = "{$iconsDirectory}{$category}-x-generic.png";
				if (file_exists($iconPath)) {
					return "{$category}-x-generic.png";
				}
			}
		}

		// Try application-octet-stream as last fallback.
		$iconPath = "{$iconsDirectory}/application-octet-stream.png";

		if (file_exists($iconPath)) {
			return 'application-octet-stream.png';
		}

		// No icon can be found.
		return false;
	}

/**
 * Determine the generic icon MIME package based on a file's MIME type.
 *
 * @param string $mime File mime type
 * @return string The generic icon MIME package expected for this file
 */
	public static function fileIconMap($mime) {
		switch ($mime) {
			// Word document types.
			case 'application/msword':
			case 'application/vnd.ms-word.document.macroEnabled.12':
			case 'application/vnd.oasis.opendocument.text':
			case 'application/vnd.oasis.opendocument.text-template':
			case 'application/vnd.oasis.opendocument.text-master':
			case 'application/vnd.oasis.opendocument.text-web':
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			case 'application/vnd.stardivision.writer':
			case 'application/vnd.sun.xml.writer':
			case 'application/vnd.sun.xml.writer.template':
			case 'application/vnd.sun.xml.writer.global':
			case 'application/vnd.wordperfect':
			case 'application/x-abiword':
			case 'application/x-applix-word':
			case 'application/x-kword':
			case 'application/x-kword-crypt':
				return 'x-office-document';

			// Spreadsheet document types.
			case 'application/vnd.ms-excel':
			case 'application/vnd.ms-excel.sheet.macroEnabled.12':
			case 'application/vnd.oasis.opendocument.spreadsheet':
			case 'application/vnd.oasis.opendocument.spreadsheet-template':
			case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
			case 'application/vnd.stardivision.calc':
			case 'application/vnd.sun.xml.calc':
			case 'application/vnd.sun.xml.calc.template':
			case 'application/vnd.lotus-1-2-3':
			case 'application/x-applix-spreadsheet':
			case 'application/x-gnumeric':
			case 'application/x-kspread':
			case 'application/x-kspread-crypt':
				return 'x-office-spreadsheet';

			// Presentation document types.
			case 'application/vnd.ms-powerpoint':
			case 'application/vnd.ms-powerpoint.presentation.macroEnabled.12':
			case 'application/vnd.oasis.opendocument.presentation':
			case 'application/vnd.oasis.opendocument.presentation-template':
			case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
			case 'application/vnd.stardivision.impress':
			case 'application/vnd.sun.xml.impress':
			case 'application/vnd.sun.xml.impress.template':
			case 'application/x-kpresenter':
				return 'x-office-presentation';

			// Compressed archive types.
			case 'application/zip':
			case 'application/x-zip':
			case 'application/stuffit':
			case 'application/x-stuffit':
			case 'application/x-7z-compressed':
			case 'application/x-ace':
			case 'application/x-arj':
			case 'application/x-bzip':
			case 'application/x-bzip-compressed-tar':
			case 'application/x-compress':
			case 'application/x-compressed-tar':
			case 'application/x-cpio-compressed':
			case 'application/x-deb':
			case 'application/x-gzip':
			case 'application/x-java-archive':
			case 'application/x-lha':
			case 'application/x-lhz':
			case 'application/x-lzop':
			case 'application/x-rar':
			case 'application/x-rpm':
			case 'application/x-tzo':
			case 'application/x-tar':
			case 'application/x-tarz':
			case 'application/x-tgz':
				return 'package-x-generic';

			// Script file types.
			case 'application/ecmascript':
			case 'application/javascript':
			case 'application/mathematica':
			case 'application/vnd.mozilla.xul+xml':
			case 'application/x-asp':
			case 'application/x-awk':
			case 'application/x-cgi':
			case 'application/x-csh':
			case 'application/x-m4':
			case 'application/x-perl':
			case 'application/x-php':
			case 'application/x-ruby':
			case 'application/x-shellscript':
			case 'text/vnd.wap.wmlscript':
			case 'text/x-emacs-lisp':
			case 'text/x-haskell':
			case 'text/x-literate-haskell':
			case 'text/x-lua':
			case 'text/x-makefile':
			case 'text/x-matlab':
			case 'text/x-python':
			case 'text/x-sql':
			case 'text/x-tcl':
				return 'text-x-script';

			// HTML aliases.
			case 'application/xhtml+xml':
				return 'text-html';

			// Executable types.
			case 'application/x-macbinary':
			case 'application/x-ms-dos-executable':
			case 'application/x-pef-executable':
				return 'application-x-executable';

			default:
				return false;
		}
	}

/**
 * Get file extension.
 *
 * @param string $file Name of the file. e.g.: `my-file.docx`
 * @return string
 */
	public static function ext($file) {
		return strtolower(
			substr(
				$file,
				strrpos($file, '.') + 1,
				strlen($file)
			)
		);
	}

}

<?php
/**
 * Utility functions for File field handler
 *
 * @author Christopher Castro <chris@quickapps.es>
 * @link http://www.quickappscms.org
 */
class FieldFile {
/**
 * Defines a new preview configuration, or overwrite if exists.
 *
 * @param string $id unique ID. e.g.: `new_preview_mode`
 * @param string $label Human-readable name. e.g.: `New preview mode`
 * @param integer $width Width for images that would use this preview mode
 * @param integer $height Height for images that would use this preview mode
 * @return void
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
 * Given a file mime, create a path to a matching icon.
 *
 * @param string $mime file mime type
 * @return A string to the icon as a local path, or FALSE if an appropriate icon could not be found
 */
	public static function fileIcon($mime) {
		// Use the default set of icons if none specified.
		if (!isset($icon_directory)) {
			$icon_directory = CakePlugin::path('FieldFile') . 'webroot' . DS . 'img' . DS . 'icons' . DS;
		}

		// If there's an icon matching the exact mimetype, go for it.
		$dashed_mime = strtr($mime, array('/' => '-'));
		$icon_path = $icon_directory . $dashed_mime . '.png';

		if (file_exists($icon_path)) {
			return "{$dashed_mime}.png";
		}

		// For a few mimetypes, we can "manually" map to a generic icon.
		$generic_mime = (string) FieldFile::fileIconMap($mime);
		$icon_path = $icon_directory . $generic_mime . '.png';

		if ($generic_mime && file_exists($icon_path)) {
			return "{$generic_mime}.png";
		}

		// Use generic icons for each category that provides such icons.
		foreach (array('audio', 'image', 'text', 'video') as $category) {
			if (strpos($mime, $category . '/') === 0) {
				$icon_path = $icon_directory  . $category . '-x-generic.png';

				if (file_exists($icon_path)) {
					return "{$category}-x-generic.png";
				}
			}
		}

		// Try application-octet-stream as last fallback.
		$icon_path = $icon_directory . '/application-octet-stream.png';

		if (file_exists($icon_path)) {
			return "application-octet-stream.png";
		}

		// No icon can be found.
		return false;
	}

/**
 * Determine the generic icon MIME package based on a file's MIME type.
 *
 * @param string $mime File mime type
 * @return The generic icon MIME package expected for this file
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
 * @param string $filename Name of the file. e.g.: `my-file.docx`
 * @return string
 */
	public static function findExts($file) {
		$pos = strrpos($file, '.');

		return strtolower(substr($file, $pos+1, strlen($file)));
	}
}
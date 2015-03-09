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
namespace Field\Utility;

use QuickApps\Core\Plugin;

/**
 * FileToolbox class for handling files related tasks.
 *
 * A set of utility methods related to files, such as `bytesToSize()`,
 * `fileIcon()`, etc.
 */
class FileToolbox
{

    /**
     * Renders the given custom field.
     *
     * @param \Cake\View\View $view Instance of view class
     * @param \Field\Model\Entity\Field $field The field to be rendered
     * @return string HTML code
     */
    public static function formatter($view, $field)
    {
        switch ($field->viewModeSettings['formatter']) {
            case 'link':
                $out = $view->element('Field.FileField/display_link', compact('field'));
                break;
            case 'table':
                $out = $view->element('Field.FileField/display_table', compact('field'));
                break;
            case 'url':
            default:
                $out = $view->element('Field.FileField/display_url', compact('field'));
                break;
        }
        return $out;
    }

    /**
     * Gets a translated string representation of the size.
     *
     * @param int $bytes Size to convert given in bytes units
     * @param int $precision Decimal precision
     * @return string Human-readable size, e.g. `1 KB`, `36.8 MB`, etc
     */
    public static function bytesToSize($bytes, $precision = 2)
    {
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
     * Creates a path to the icon for a file mime.
     *
     * @param string $mime File mime type
     * @param mixed $iconsDirectory A path to a directory of icons to be used for
     *  files. Defaults to built-in icons directory (Field/webroot/img/file-icons/)
     * @return mixed A string for the icon file name, or false if an appropriate
     *  icon could not be found
     */
    public static function fileIcon($mime, $iconsDirectory = false)
    {
        if (!$iconsDirectory) {
            $iconsDirectory = Plugin::path('Field') . 'webroot/img/file-icons/';
        }

        // If there's an icon matching the exact mimetype, go for it.
        $dashedMime = strtr($mime, ['/' => '-']);
        if (file_exists("{$iconsDirectory}{$dashedMime}.png")) {
            return "{$dashedMime}.png";
        }

        // For a few mimetypes, we can "manually" map to a generic icon.
        $genericMime = (string)static::fileIconMap($mime);
        if ($genericMime && file_exists("{$iconsDirectory}{$genericMime}.png")) {
            return "{$genericMime}.png";
        }

        // Use generic icons for each category that provides such icons.
        if (preg_match('/^(audio|image|text|video)\//', $mime, $matches)) {
            if (file_exists("{$iconsDirectory}{$matches[1]}-x-generic.png")) {
                return "{$matches[1]}-x-generic.png";
            }
        }

        if (file_exists("{$iconsDirectory}/application-octet-stream.png")) {
            return 'application-octet-stream.png';
        }

        return false;
    }

    /**
     * Determine the generic icon MIME package based on a file's MIME type.
     *
     * @param string $mime File mime type
     * @return string The generic icon MIME package expected for this file
     */
    public static function fileIconMap($mime)
    {
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

            // not found
            default:
                return false;
        }
    }

    /**
     * Get file extension.
     *
     * @param string $fileName File name, including its extension. e.g.: `my-file.docx`
     * @return string File extension without the ending DOT and lowercased,
     *  e.g. `pdf`, `jpg`, `png`, etc. If no extension is found an empty string will
     *  be returned
     */
    public static function ext($fileName)
    {
        if (strpos($fileName, '.') === false) {
            return '';
        }
        return strtolower(
            substr(
                $fileName,
                strrpos($fileName, '.') + 1,
                strlen($fileName)
            )
        );
    }

    /**
     * Remove file extension.
     *
     * @param string $fileName File name, including its extension.
     *  e.g. `my-file.docx`, `myFile.DoCX`, etc
     * @return string File name without extension, e.g. `my-file`, `myFile`, etc
     */
    public static function removeExt($fileName)
    {
        $ext = static::ext($fileName);
        if ($ext) {
            return preg_replace("/\.{$ext}$/i", '', $fileName);
        }
        return $fileName;
    }
}

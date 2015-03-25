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

use Field\Utility\FileIconMap;
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
        if (is_readable("{$iconsDirectory}{$dashedMime}.png")) {
            return "{$dashedMime}.png";
        }

        // For a few mimetypes, we can "manually" map to a generic icon.
        $genericMime = (string)static::fileIconMap($mime);
        if ($genericMime && is_readable("{$iconsDirectory}{$genericMime}.png")) {
            return "{$genericMime}.png";
        }

        // Use generic icons for each category that provides such icons.
        if (preg_match('/^(audio|image|text|video)\//', $mime, $matches)) {
            if (is_readable("{$iconsDirectory}{$matches[1]}-x-generic.png")) {
                return "{$matches[1]}-x-generic.png";
            }
        }

        if (is_readable("{$iconsDirectory}/application-octet-stream.png")) {
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
        foreach (FileIconMap::$map as $icon => $mimeList) {
            if (in_array($mime, $mimeList)) {
                return $icon;
            }
        }
        return false;
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

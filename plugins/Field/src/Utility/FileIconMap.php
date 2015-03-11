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

/**
 * Holds information for mapping a mime-type to its corresponding icon.
 *
 */
class FileIconMap
{

    /**
     * Map array, indexed by icon file.
     *
     * @var array
     */
    public static $map = [
        // Word document types.
        'x-office-document' => [
            'application/msword',
            'application/vnd.ms-word.document.macroEnabled.12',
            'application/vnd.oasis.opendocument.text',
            'application/vnd.oasis.opendocument.text-template',
            'application/vnd.oasis.opendocument.text-master',
            'application/vnd.oasis.opendocument.text-web',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.stardivision.writer',
            'application/vnd.sun.xml.writer',
            'application/vnd.sun.xml.writer.template',
            'application/vnd.sun.xml.writer.global',
            'application/vnd.wordperfect',
            'application/x-abiword',
            'application/x-applix-word',
            'application/x-kword',
            'application/x-kword-crypt',
        ],
        // Spreadsheet document types.
        'x-office-spreadsheet' => [
            'application/vnd.ms-excel',
            'application/vnd.ms-excel.sheet.macroEnabled.12',
            'application/vnd.oasis.opendocument.spreadsheet',
            'application/vnd.oasis.opendocument.spreadsheet-template',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.stardivision.calc',
            'application/vnd.sun.xml.calc',
            'application/vnd.sun.xml.calc.template',
            'application/vnd.lotus-1-2-3',
            'application/x-applix-spreadsheet',
            'application/x-gnumeric',
            'application/x-kspread',
            'application/x-kspread-crypt',
        ],
        // Presentation document types.
        'x-office-presentation' => [
            'application/vnd.ms-powerpoint',
            'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
            'application/vnd.oasis.opendocument.presentation',
            'application/vnd.oasis.opendocument.presentation-template',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.stardivision.impress',
            'application/vnd.sun.xml.impress',
            'application/vnd.sun.xml.impress.template',
            'application/x-kpresenter',
        ],
        // Compressed archive types.
        'package-x-generic' => [
            'application/zip',
            'application/x-zip',
            'application/stuffit',
            'application/x-stuffit',
            'application/x-7z-compressed',
            'application/x-ace',
            'application/x-arj',
            'application/x-bzip',
            'application/x-bzip-compressed-tar',
            'application/x-compress',
            'application/x-compressed-tar',
            'application/x-cpio-compressed',
            'application/x-deb',
            'application/x-gzip',
            'application/x-java-archive',
            'application/x-lha',
            'application/x-lhz',
            'application/x-lzop',
            'application/x-rar',
            'application/x-rpm',
            'application/x-tzo',
            'application/x-tar',
            'application/x-tarz',
            'application/x-tgz',
        ],
        // Script file types.
        'text-x-script' => [
            'application/ecmascript',
            'application/javascript',
            'application/mathematica',
            'application/vnd.mozilla.xul+xml',
            'application/x-asp',
            'application/x-awk',
            'application/x-cgi',
            'application/x-csh',
            'application/x-m4',
            'application/x-perl',
            'application/x-php',
            'application/x-ruby',
            'application/x-shellscript',
            'text/vnd.wap.wmlscript',
            'text/x-emacs-lisp',
            'text/x-haskell',
            'text/x-literate-haskell',
            'text/x-lua',
            'text/x-makefile',
            'text/x-matlab',
            'text/x-python',
            'text/x-sql',
            'text/x-tcl',
        ],
        // HTML aliases.
        'text-html' => ['application/xhtml+xml'],
        // Executable types.
        'application-x-executable' => [
              'application/x-macbinary',
              'application/x-ms-dos-executable',
              'application/x-pef-executable',
        ],
    ];
}

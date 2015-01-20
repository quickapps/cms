<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Field\Test\TestCase\Utility;

use Cake\TestSuite\TestCase;
use Field\Utility\FileToolbox;

/**
 * FileToolboxTest class.
 */
class FileToolboxTest extends TestCase
{

/**
 * test bytesToSize() method.
 *
 * @return void
 */
    public function testBytesToSize()
    {
        $this->assertEquals('6 B', FileToolbox::bytesToSize(6));
        $this->assertEquals('1 KB', FileToolbox::bytesToSize(1024));
        $this->assertEquals('3.6 MB', FileToolbox::bytesToSize(1024 * 1024 * 3.6));
        $this->assertEquals('1.66 GB', FileToolbox::bytesToSize(1024 * 1024 * 1024 * 1.66));
        $this->assertEquals('1.3 TB', FileToolbox::bytesToSize(1024 * 1024 * 1024 * 1024 * 1.3));
    }

/**
 * test fileIcon() method.
 *
 * @return void
 */
    public function testFileIcon()
    {
        $this->assertEquals('x-office-document.png', FileToolbox::fileIcon('application/msword'));
        $this->assertEquals('x-office-spreadsheet.png', FileToolbox::fileIcon('application/x-applix-spreadsheet'));
        $this->assertEquals('audio-x-generic.png', FileToolbox::fileIcon('audio/mp3'));
    }

/**
 * test ext() method.
 *
 * @return void
 */
    public function testExt()
    {
        $this->assertEquals('exe', FileToolbox::ext('some-file-name.exe'));
        $this->assertEquals('gif', FileToolbox::ext('some-file-name.png.gif'));
        $this->assertEquals('jpg', FileToolbox::ext('some-file-name.not-this.and-not-this.JPG'));
    }

/**
 * test that ext() returns empty when filename has no extension.
 *
 * @return void
 */
    public function testExtNotExtension()
    {
        $this->assertEquals('', FileToolbox::ext('file-with-no-extension'));
        $this->assertEquals('', FileToolbox::ext('file-with-no-extension.'));
        $this->assertEquals('', FileToolbox::ext('file-with-no-extension....'));
    }

/**
 * test removeExt() method.
 *
 * @return void
 */
    public function testRemoveExt()
    {
        $this->assertEquals('some-file-name', FileToolbox::removeExt('some-file-name.exe'));
        $this->assertEquals('some-file-name.png', FileToolbox::removeExt('some-file-name.png.gif'));
        $this->assertEquals('some-file-name.not-this.and-not-this', FileToolbox::removeExt('some-file-name.not-this.and-not-this.JPG'));
        $this->assertEquals('file-with-no-extension', FileToolbox::removeExt('file-with-no-extension'));
    }
}

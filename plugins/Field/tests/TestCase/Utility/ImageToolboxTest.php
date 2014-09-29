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

use Cake\Filesystem\Folder;
use Cake\TestSuite\TestCase;
use Field\Utility\ImageToolbox;

/**
 * ImageToolboxTest class.
 */
class ImageToolboxTest extends TestCase {

/**
 * Path to image test file.
 * 
 * @var string
 */
	public $img1;

/**
 * Path to image test file.
 * 
 * @var string
 */
	public $img2;

/**
 * setUp().
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->img1 = WWW_ROOT . 'files/test.png';
		$this->img2 = WWW_ROOT . 'files/test2.png';
	}

/**
 * tearDown().
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		$folder = new Folder(WWW_ROOT . 'files/.tmb/');
		$folder->delete();
	}

/**
 * test thumbnail() method.
 *
 * @return void
 */
	public function testThumbnail() {
		$this->assertNotEmpty(ImageToolbox::thumbnail($this->img1, 'thumbnail'));
		$this->assertNotEmpty(ImageToolbox::thumbnail($this->img2, 'thumbnail'));
	}

/**
 * test previewsOptions() method.
 *
 * @return void
 */
	public function testPreviewsOptions() {
		$this->assertNotEmpty(ImageToolbox::previewsOptions());
	}

/**
 * test getPreviews() method.
 *
 * @return void
 */
	public function testGetPreviews() {
		$this->assertNotEmpty(ImageToolbox::getPreviews());
		$this->assertNotEmpty(ImageToolbox::getPreviews('thumbnail'));
	}

/**
 * test addPreview() method.
 *
 * @return void
 */
	public function testAddPreview() {
		ImageToolbox::addPreview('box20', '20x20 Box', 20, 20);
		$this->assertNotEmpty(ImageToolbox::getPreviews('box20'));
	}

/**
 * test delete() method.
 *
 * @return void
 */
	public function testDelete() {
		copy($this->img2, WWW_ROOT . 'files/dummy-test.png');
		ImageToolbox::delete(WWW_ROOT . 'files/dummy-test.png');
		$this->assertFalse(file_exists(WWW_ROOT . 'files/dummy-test.png'));
	}

}

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
namespace CMS\Test\TestCase\Shortcode;

use Cake\Event\EventManager;
use Cake\TestSuite\TestCase;
use CMS\Event\EventDispatcher;
use CMS\Shortcode\ShortcodeParser;

/**
 * ShortcodeParserTest class.
 */
class ShortcodeParserTest extends TestCase
{

/**
 * setUp().
 *
 * @return void
 */
    public function setUp()
    {
        parent::setUp();
        $manager = EventDispatcher::instance('Shortcode')->eventManager();
        if (!$manager->listeners('dummy')) {
            $manager->on('dummy', function ($event, $atts, $content, $code) {
                return '@@DUMMY@@';
            });

            $manager->on('dummy_atts', function ($event, $atts, $content, $code) {
                return $atts['at'];
            });

            $manager->on('enclosed', function ($event, $atts, $content, $code) {
                return $content;
            });
        }
    }

/**
 * test parser() method.
 *
 * @return void
 */
    public function testParse()
    {
        $this->assertEquals('some text @@DUMMY@@', ShortcodeParser::parse('some text {dummy /}'));
        $this->assertEquals('hello world', ShortcodeParser::parse('hello {dummy_atts at=world/}'));
        $this->assertEquals('hello world!', ShortcodeParser::parse('hello {enclosed}world!{/enclosed}'));
    }

/**
 * test strip() method.
 *
 * @return void
 */
    public function testStrip()
    {
        $this->assertEquals('some text ', ShortcodeParser::strip('some text {dummy /}'));
        $this->assertEquals('hello ', ShortcodeParser::strip('hello {dummy_atts at=world/}'));
    }

/**
 * test scape() method.
 *
 * @return void
 */
    public function testEscape()
    {
        $this->assertEquals('some text {{dummy /}}', ShortcodeParser::escape('some text {dummy /}'));
        $this->assertEquals('hello {{dummy_atts at=world/}}', ShortcodeParser::escape('hello {dummy_atts at=world/}'));
    }
}

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
namespace CMS\Test\TestCase\config;

use Cake\I18n\I18n;
use Cake\TestSuite\TestCase;

/**
 * BasicTest class.
 */
class BasicTest extends TestCase
{

   /**
     * test stripLanguagePrefix() function.
     *
     * @return void
     */
    public function testStripLanguagePrefix()
    {
        $tests = [
            'http://www.example.com/es_ES/article/demo.html' => 'http://www.example.com/article/demo.html',
            'http://www.example.com/en_US/article/demo.html' => 'http://www.example.com/article/demo.html',
            '/es_ES/article/demo.html' => '/article/demo.html',
            '/en_US/article/demo.html' => '/article/demo.html',
            'es_ES/article/demo.html' => '/article/demo.html',
            'en_US/article/demo.html' => '/article/demo.html',
        ];

        foreach ($tests as $input => $expected) {
            $actual = stripLanguagePrefix($input);
            $this->assertEquals($expected, $actual);
        }
    }
}

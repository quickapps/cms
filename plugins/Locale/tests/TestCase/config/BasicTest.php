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
namespace Locale\Test\TestCase\config;

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
            'http://www.example.com/es_ES/article/demo1.html' => 'http://www.example.com/article/demo1.html',
            'http://www.example.com/en_US/article/demo2.html' => 'http://www.example.com/article/demo2.html',
            '/es_ES/article/demo3.html' => '/article/demo3.html',
            '/en_US/article/demo4.html' => '/article/demo4.html',
            'es_ES/article/demo5.html' => '/article/demo5.html',
            'en_US/article/demo6.html' => '/article/demo6.html',
            'http://www.example.es_ES/en_US/article/demo7.html' => 'http://www.example.es_ES/article/demo7.html',
        ];

        foreach ($tests as $input => $expected) {
            $actual = stripLanguagePrefix($input);
            $this->assertEquals($expected, $actual);
        }
    }
}

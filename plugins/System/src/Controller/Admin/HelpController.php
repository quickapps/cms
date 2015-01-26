<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    1.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace System\Controller\Admin;

use Cake\Core\App;
use Cake\I18n\I18n;
use Cake\Network\Exception\NotFoundException;
use QuickApps\Core\Plugin;
use System\Controller\AppController;

/**
 * Help Controller.
 *
 * For handling plugin's help documents.
 */
class HelpController extends AppController
{

    /**
     * Main action.
     *
     * Here is where we render all available documents. Plugins are able to define
     * their own `help document` just by creating an view-element named `help.ctp`.
     *
     * Example:
     *
     * Album plugin may create its own `help document` by creating this file:
     *
     *     /plugins/Album/src/Template/Element/help.ctp
     *
     * Optionally, plugins are able to define translated versions of
     * help documents. To do this, you must simply define a view element as
     * `help_[code].ctp`, where `[code]` is a two-character language code.
     * For example:
     *
     *     help_en-us.ctp
     *     help_es.ctp
     *     help_fr.ctp
     *
     * @return void
     */
    public function index()
    {
        $plugins = [];

        foreach (Plugin::collection() as $plugin) {
            if ($plugin['status'] && $plugin['hasHelp']) {
                $plugins[] = $plugin['human_name'];
            }
        }

        $this->set('plugins', $plugins);
        $this->Breadcrumb->push('/admin/system/help');
    }

    /**
     * Renders the help document of the given plugin.
     *
     * @param string $pluginName The plugin name
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When no help document was found
     */
    public function about($pluginName)
    {
        $about = false;

        if (Plugin::loaded($pluginName)) {
            $locale = I18n::defaultLocale();
            $templatePath = App::path('Template', $pluginName)[0] . 'Element/Help/';
            $about = false;
            $lookFor = ["help_{$locale}", 'help'];

            foreach ($lookFor as $name) {
                if (file_exists($templatePath . "{$name}.ctp")) {
                    $about = "{$pluginName}.Help/{$name}";
                    break;
                }
            }
        }

        if ($about) {
            $this->set('about', $about);
        } else {
            throw new NotFoundException(__d('system', 'No help was found.'));
        }

        $this->Breadcrumb
            ->push('/admin/system/help')
            ->push(__d('system', 'About {0}', $pluginName), '#');
    }
}

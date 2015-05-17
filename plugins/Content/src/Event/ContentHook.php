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
namespace Content\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use CMS\View\ViewModeRegistry;
use Content\Model\Entity\Content;

/**
 * Main Hook Listener for Content plugin.
 *
 */
class ContentHook implements EventListenerInterface
{

    /**
     * Returns a list of hooks this Hook Listener is implementing. When the class is
     * registered in an event manager, each individual method will be associated with
     * the respective event.
     *
     * @return void
     */
    public function implementedEvents()
    {
        return [
            'Render.Content\Model\Entity\Content' => 'renderContent',
        ];
    }

    /**
     * Renders a single Content Entity.
     *
     * You can define `specialized-renders` according to your needs as follow.
     * This method looks for specialized renders in the order described below,
     * if one is not found we look the next one, etc.
     *
     * ### Render content based on content-type & view-mode
     *
     *      render_content_[content-type]_[view-mode].ctp
     *
     * Renders the given content based on `content-type` + `view-mode` combination,
     * for example:
     *
     * - render_content_article_full.ctp: Render for "article" contents in "full"
     *   view-mode.
     *
     * - render_content_article_search-result.ctp: Render for "article" contents in
     *   "search-result" view-mode.
     *
     * - render_content_basic-page_search-result.ctp: Render for "basic-page"
     *   contents in "search-result" view-mode.
     *
     * ### Render content based on content-type
     *
     *     render_content_[content-type].ctp
     *
     * Similar as before, but just based on `content-type` (and any view-mode), for
     * example:
     *
     * - render_content_article.ctp: Render for "article" contents.
     *
     * - render_content_basic-page.ctp: Render for "basic-page" contents
     *
     * ### Render content based on view-mode
     *
     *     render_content_[view-mode].ctp
     *
     * Similar as before, but just based on `view-mode` (and any content-type), for
     * example:
     *
     * - render_content_rss.ctp: Render any content (article, page, etc) in "rss"
     *   view-mode.
     *
     * - render_content_full.ctp: Render any content (article, page, etc) in "full"
     *   view-mode.
     *
     * NOTE: To avoid collisions between `view-mode` names and `content-type` names,
     * you should alway use unique and descriptive names as possible when defining
     * new content types. By default, Content plugin defines the following view-
     * modes: `default`, `teaser`, `search-result`, `rss`, `full`.
     *
     * ### Default
     *
     *     render_content.ctp
     *
     * This is the global render, if none of the above renders is found we try to
     * use this last. Themes can overwrite this view element by creating a new one
     * at `ExampleTheme/Template/Element/render_content.ctp`.
     *
     * ---
     *
     * NOTE: Please note the difference between "_" and "-"
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Content\Model\Entity\Content $content The content to render
     * @param array $options Additional options as an array
     * @return string HTML
     */
    public function renderContent(Event $event, Content $content, $options = [])
    {
        $View = $event->subject();
        $viewMode = $View->viewMode();
        $html = '';
        $try = [
            "Content.render_content_{$content->content_type_slug}_{$viewMode}",
            "Content.render_content_{$content->content_type_slug}",
            "Content.render_content_{$viewMode}",
            'Content.render_content'
        ];

        foreach ($try as $element) {
            if ($View->elementExists($element)) {
                $html = $View->element($element, compact('content', 'options'));
                break;
            }
        }

        return $html;
    }
}

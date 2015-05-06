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

$this->set('channelData', [
    'title' => $this->fetch('title'),
    'link' => $this->Url->build('/', true),
    'description' => $this->fetch('description'),
    'language' => language('locale')
]);

foreach ($contents as $content) {
    $bodyText = h(strip_tags($this->render($content)));
    $bodyText = $this->Text->truncate($bodyText, 400, [
        'ending' => '...',
        'exact' => true,
        'html' => true,
    ]);

    echo $this->Rss->item([], [
        'title' => $content->title,
        'link' => $content->url,
        'guid' => ['url' => $content->url, 'isPermaLink' => 'true'],
        'description' => $bodyText,
        'pubDate' => $content->created,
    ]);
}
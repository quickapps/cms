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

foreach ($nodes as $node) {
    $bodyText = h(strip_tags($this->render($node)));
    $bodyText = $this->Text->truncate($bodyText, 400, [
        'ending' => '...',
        'exact' => true,
        'html' => true,
    ]);

    echo $this->Rss->item([], [
        'title' => $node->title,
        'link' => $node->url,
        'guid' => ['url' => $node->url, 'isPermaLink' => 'true'],
        'description' => $bodyText,
        'pubDate' => $node->created,
    ]);
}
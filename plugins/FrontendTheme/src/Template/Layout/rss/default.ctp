<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */

if (!isset($documentData)) {
	$documentData = [];
}

if (!isset($channelData)) {
	$channelData = [];
}

if (!isset($channelData['title'])) {
	$channelData['title'] = $this->fetch('title');
}

$channel = $this->Rss->channel([], $channelData, $this->fetch('content'));
echo $this->Rss->document($documentData, $channel);
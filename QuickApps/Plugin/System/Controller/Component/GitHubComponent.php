<?php
App::uses('HttpSocket', 'Network/Http');

/**
 * Simple GitHub API
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.System.Controller.Component
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class GitHubComponent extends Component {
	public $Socket;
	public $settings = array(
		'githubServer' => 'https://api.github.com/',
		'origin' => 'QACMS-Modules',
		'cacheDuration' => '+1 hours'
	);

	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->Socket = new HttpSocket();
		$this->settings = array_merge($this->settings, $settings);

		Cache::config('github_repo',
			array(
				'engine' => 'File',
				'duration' => $this->settings['cacheDuration'],
				'path' => CACHE
			)
		);
	}

	public function setOrigin($origin) {
		$this->settings['origin'] = $origin;
	}

	public function getRepo($repo) {
		$r = Cache::read("github_repo_{$repo}", 'github_repo');

		if (!$r) {
			$r = $this->Socket->get("{$this->settings['githubServer']}repos/{$this->settings['origin']}/{$repo}");
			$r = json_decode($r);

			if (isset($r->message)) {
				return false;
			}

			$r->yaml = $this->getYaml($repo);
			
			if (!isset($r->yaml->content)) {
				return false;
			} else {
				$r->yaml = Spyc::YAMLLoadString(base64_decode($r->yaml->content));
			}

			$r->thumbnail = $this->getThumbnail($repo);
			$r->branches = $this->getBranches($repo);
			$r->tags = $this->getTags($repo);
			$r->readme = $this->getReadme($repo);

			if (isset($r->readme->content)) {
				$markdown = base64_decode($r->readme->content);
				$post = $this->Socket->post("{$this->settings['githubServer']}markdown/raw",
					$markdown,
					array('header' => array('Content-Type' => 'text/x-markdown'))
				);

				if (isset($post->body)) {
					$r->readme = $post->body;
				} else {
					$r->readme = '';
				}
			} else {
				$r->readme = '';
			}

			Cache::write("github_repo_{$repo}", $r, 'github_repo');
		}

		return $r;
	}

	public function getThumbnail($repo) {
		$t = $this->Socket->get("{$this->settings['githubServer']}repos/{$this->settings['origin']}/{$repo}/contents/thumbnail.png");
		$t = json_decode($t);

		if (isset($t->content) && !empty($t->content)) {
			$link = $t->_links->html;
			$link = str_replace('://github.com', '://raw.github.com', $link);
			$link = str_replace('/blob/', '/', $link);

			return $link;
		}

		return false;
	}

	public function getYaml($repo) {
		$appName = preg_replace('/^QACMS-/', '', $repo);
		$y = $this->Socket->get("{$this->settings['githubServer']}repos/{$this->settings['origin']}/{$repo}/contents/{$appName}.yaml");

		return json_decode($y);
	}

	public function getReadme($repo) {
		$r = $this->Socket->get("{$this->settings['githubServer']}repos/{$this->settings['origin']}/{$repo}/readme");

		return json_decode($r);
	}

	public function getBranches($repo) {
		$b = $this->Socket->get("{$this->settings['githubServer']}repos/{$this->settings['origin']}/{$repo}/branches");

		return json_decode($b);
	}

	public function getTags($repo) {
		$t = $this->Socket->get("{$this->settings['githubServer']}repos/{$this->settings['origin']}/{$repo}/tags");

		return json_decode($t);
	}

	public function searchRepos($keywords, $params = array()) {
		$keywords = "QACMS- {$keywords}";
		$options = $this->__buildParams($params);
		$results = $this->Socket->get("{$this->settings['githubServer']}legacy/repos/search/{$keywords}{$options}");
		$results = json_decode($results);

		if (isset($results->repositories)) {
			foreach ($results->repositories as $i => $r) {
				if ($r->owner != $this->settings['origin']) {
					unset($results->repositories[$i]);
				}
			}
		}

		return $results;
	}

	public function listRepos($params = array()) {
		$options = $this->__buildParams($params);
		$key = 'github_repos_list_' . md5(serialize($options) . $this->settings['origin']);
		$list = Cache::read($key, 'github_repo');

		if (!$list) {
			$list = $this->Socket->get("{$this->settings['githubServer']}users/{$this->settings['origin']}/repos{$options}");
			$list = json_decode($list);

			foreach ($list as &$l) {
				$l->thumbnail = $this->getThumbnail($l->name);
			}

			Cache::write($key, $list, 'github_repo');
		}

		return $list;
	}

	private function __buildParams($params = array()) {
		$options = '';

		if ($params) {
			$parts = array();

			foreach ($params as $key => $value) {
				$parts[] = "{$key}={$value}";
			}

			if (!empty($parts)) {
				$options = '?' . implode('&', $parts);
			}
		}

		return $options;
	}
}
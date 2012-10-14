<?php
/**
 * Repos Directory Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.System.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class DirectoryController extends SystemAppController {
	public $name = 'Directory';
	public $uses = array('System.Module');
	public $components = array('System.GitHub');

	public function beforeFilter() {
		$this->title(__t('Apps Directory'));

		parent::beforeFilter();
	}

	public function admin_themes($listing = null) {
		$this->GitHub->setOrigin('QACMS-Themes');

		$results = array();

		if (isset($this->data['Search']['keywords'])) {
			$results = $this->GitHub->searchRepos($this->data['Search']['keywords']);

			if (isset($results->repositories)) {
				$results = $results->repositories;
			} else {
				$results = array();
			}

			if (empty($results)) {
				$this->flashMsg(__t('No themes match your request.'), 'error');
			}
		} elseif (in_array($listing, array('created', 'updated'))) {
			$results = $this->GitHub->listRepos(array(
				'sort' => $listing,
				'direction' => 'desc'
			));
		}

		$this->setCrumb(
			'/admin/system/themes',
			array(__t('Themes Directory'))
		);

		$this->set(compact('results'));
		$this->set(compact('listing'));
	}

	public function admin_modules($listing = null) {
		$results = array();

		if (isset($this->data['Search']['keywords'])) {
			$results = $this->GitHub->searchRepos($this->data['Search']['keywords']);

			if (isset($results->repositories)) {
				$results = $results->repositories;
			} else {
				$results = array();
			}
			
			if (empty($results)) {
				$this->flashMsg(__t('No modules match your request.'), 'error');
			}
		} elseif (in_array($listing, array('created', 'updated'))) {
			$results = $this->GitHub->listRepos(array(
				'sort' => $listing,
				'direction' => 'desc'
			));
		}

		$this->setCrumb(
			'/admin/system/modules',
			array(__t('Modules Directory'))
		);

		$this->set(compact('results'));
		$this->set(compact('listing'));
	}

	public function admin_theme_details($repo) {
		$this->GitHub->setOrigin('QACMS-Themes');

		$repo = $this->GitHub->getRepo($repo) or $this->redirect('/admin/system/directory/themes');

		$this->setCrumb(
			'/admin/system/themes',
			array(__t('Themes Directory'), '/admin/system/directory/themes'),
			array(__t('Theme Details: %s', $repo->yaml['info']['name']))
		);
		$this->set(compact('repo'));
	}

	public function admin_module_details($repo) {
		$repo = $this->GitHub->getRepo($repo) or $this->redirect('/admin/system/directory/modules');

		$this->setCrumb(
			'/admin/system/modules',
			array(__t('Modules Directory'), '/admin/system/directory/modules'),
			array(__t('Module Details: %s', $repo->yaml['name']))
		);
		$this->set(compact('repo'));
	}
}
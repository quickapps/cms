<?php
/**
 * Theme Helper
 *
 * For core theme `Admin`
 *
 * PHP version 5
 *
 * @package  QuickApps.Themes.Admin.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class ThemeAdminHookHelper extends AppHelper {
	public $helpers = array('ThemeAdmin.BootstrapPaginator');

	public function beforeRender($viewFile) {
		if ($this->_View->request->params['plugin'] == 'user' &&
			$this->_View->request->params['controller'] == 'user' &&
			in_array($this->_View->request->params['action'], array('login', 'admin_login'))
		) {
			$this->_View->Layout->script('login.js');
		}

		$this->_View->viewVars['Layout']['meta']['viewport'] = array(
			'name' => 'viewport',
			'content' => 'width=device-width; initial-scale=1.0'
		);
	}

	public function pagination() {
		return $this->BootstrapPaginator->pagination();
	}

	public function html_table_alter(&$info) {
		$info['options']['tableOptions'] = array('class' => 'table table-bordered');

		// styles for nodes table list
		if ($this->request->controller == 'contents' && $this->action == 'admin_index' && strtolower($this->plugin) == 'node') {
			$statusClass = '{php} return ({Node.status} == 0 ? "btn-danger" : "btn-primary"); {/php}';
			$actions = "
				<li><a href='{url}/admin/node/contents/edit/{Node.slug}{/url}'>" . __t('edit') . "</a></li>
				{php} return (!'{Node.translation_of}' && '{Node.language}') ? \"<li><a href='{url}/admin/node/contents/translate/{Node.slug}{/url}'>" . __t('translate') . "</a></li>\" : ''; {/php}
				<li><a href='{url}/admin/node/contents/delete/{Node.slug}{/url}' onclick=\"return confirm('" . __t('Delete selected content ?') . "');\">" . __t('delete') . "</a></li>
			";
			$label = '
				<span class="visible-phone visible-tablet">[{NodeType.name}]</span>
				<span class="hidden-phone">{truncate length=50} {Node.title} {/truncate}</span>
				<span class="visible-phone">{truncate length=25} {Node.title} {/truncate}</span>
				{php} return ({Node.sticky}) ? \'<i class="icon-star" title="' . __t("Sticky at top") . '"></i>\' : ""; {/php}
				{php} return ({Node.promote}) ? \'<i class="icon-home" title="' . __t("Promoted in front page") . '"></i>\' : ""; {/php}
				{php} return (trim("{Node.cache}") != "") ? \'<i class="icon-hdd" title="' . __t("Cache activated") . ': ' . '{Node.cache}"></i>\' : ""; {/php}
				{php} return (trim("{Node.translation_of}") != "") ? \'<i class="icon-flag" title="' . __t('This node is a translation of other') . '"></i>\' : ""; {/php}
				{php} return (trim("{Node.modified}") != trim("{Node.created}")) ? \'<i class="icon-refresh visible-phone" title="' . __t('updated') . '"></i>\' : ""; {/php}
			';
			$info['options']['columns'][__t('Title')]['value'] = "
				<div class=\"btn-group\">
					<a href=\"{url}/admin/node/contents/edit/{Node.slug}{/url}\" class=\"btn {$statusClass}\">
						{$label}
					</a>
					<button class=\"btn dropdown-toggle {$statusClass}\" data-toggle=\"dropdown\">
						<span class=\"caret\"></span>
					</button>
					<ul class=\"dropdown-menu\">
						{$actions}
					</ul>
				</div>
			";

			unset($info['options']['columns'][__t('Actions')], $info['options']['columns'][__t('Status')]);

			// hide columns to prevent overflow
			$info['options']['columns']['<input type="checkbox" onclick="QuickApps.checkAll(this);">']['thOptions']['width'] = '';
			$info['options']['columns']['<input type="checkbox" onclick="QuickApps.checkAll(this);">']['tdOptions']['width'] = '';
			$info['options']['columns'][__t('Title')]['thOptions']['width'] = '';
			$info['options']['columns'][__t('Title')]['tdOptions']['width'] = '';
			$info['options']['columns'][__t('Type')]['thOptions']['class'] = 'hidden-tablet hidden-phone';
			$info['options']['columns'][__t('Type')]['tdOptions']['class'] = 'hidden-tablet hidden-phone';
			$info['options']['columns'][__t('Author')]['thOptions']['class'] = 'hidden-phone hidden-tablet';
			$info['options']['columns'][__t('Author')]['tdOptions']['class'] = 'hidden-phone hidden-tablet';
			$info['options']['columns'][__t('Updated')]['thOptions']['class'] = 'hidden-phone';
			$info['options']['columns'][__t('Updated')]['tdOptions']['class'] = 'hidden-phone';
			$info['options']['columns'][__t('Language')]['thOptions']['class'] = 'hidden-phone';
			$info['options']['columns'][__t('Language')]['tdOptions']['class'] = 'hidden-phone';

			//insert icons meaning at bottom for mobile devices
			$info['options']['append'] = '
				<ul class="visible-phone visible-tablet">
					<li><i class="icon-home"></i> ' . __t('Promoted in front page') . '</li>
					<li><i class="icon-star"></i> ' . __t('Sticky at top') . '</li>
					<li><i class="icon-hdd"></i> ' . __t('Cache activated') . '</li>
					<li><i class="icon-flag"></i> ' . __t('This node is a translation of other') . '</li>
					<li><i class="icon-refresh"></i> ' . __t('Updated') . '</li>
				</ul>
			';
		}

		// styles for users table list
		if ($this->request->controller == 'list' && $this->action == 'admin_index' && strtolower($this->plugin) == 'user') {
			$statusClass = '{php} return {User.status} == 1 ? "btn-primary" : "btn-warning"; {/php}';
			$label = '{User.username} ({User.email})';
			$actions = "<li><a href='{url}/admin/user/list/edit/{User.id}{/url}'>" . __t('edit') . "</a></li>";
			$info['options']['columns'][__t('User Name')]['value'] = "
				<div class=\"btn-group\">
					<a href=\"{url}/admin/user/list/edit/{User.id}{/url}\" class=\"btn {$statusClass}\">
						{$label}
					</a>
					<button class=\"btn dropdown-toggle {$statusClass}\" data-toggle=\"dropdown\">
						<span class=\"caret\"></span>
					</button>
					<ul class=\"dropdown-menu\">
						{$actions}
					</ul>
				</div>
			";
			unset($info['options']['columns'][__t('Actions')], $info['options']['columns'][__t('Email')]);

			// hide columns to prevent overflow
			$info['options']['columns'][__t('Roles')]['thOptions']['class'] = 'hidden-phone';
			$info['options']['columns'][__t('Roles')]['tdOptions']['class'] = 'hidden-phone';
		}

		// styles for languages table list
		if ($this->request->controller == 'languages' && $this->action == 'admin_index' && strtolower($this->plugin) == 'locale') {
			$statusClass = '{php} return {Language.status} == 1 ? "btn-primary" : "btn-danger"; {/php}';
			$actions = '
				<li><a href="{url}/admin/locale/languages/move/{Language.id}/up{/url}">' . __t('move up') . '</a></li>
				<li><a href="{url}/admin/locale/languages/move/{Language.id}/down{/url}">' . __t('move down') . '</a></li>
				{php} return "{Language.code}" != "' . Configure::read('Variable.default_language') . '" ? \'<li><a href="{url}/admin/locale/languages/set_default/{Language.id}{/url}">' . __t('set as default') . '</a></li>\' : \'\'; {/php}
				<li><a href="{url}/admin/locale/languages/edit/{Language.id}{/url}">' . __t('edit') . '</a></li>
				<li><a href="{url}/admin/locale/languages/delete/{Language.id}{/url}" onclick=\'return confirm("' . __t('Delete this language ?') . '");\'>' . __t('delete') . '</a></li>
			';
			$label = '
				{php}
					$icon = strpos("{Language.icon}", "://") !== false ? "{Language.icon}" : "/locale/img/flags/{Language.icon}";
					return ("{Language.icon}" != "" ? $this->_View->Html->image($icon, array("width" => 16, "class" => "flag-icon")) : "");
				{/php}
				<span class="visible-phone">[{Language.code}]</span>
				<span class="visible-phone">{truncate length=15} {Language.name} {/truncate}</span>
				<span class="hidden-phone">{Language.name}</span>
				<span class="visible-tablet"> ~ {Language.native}</span>
				{php} return ("{Language.code}" == "' . Configure::read('Variable.default_language') . '" ? \'<i class="icon-star" title="' . __t('Default language') . '"></i>\' : ""); {/php}
				{php} return ("{Language.direction}" == "ltr" ? \'<i class="icon-arrow-right visible-phone" title="' . __t('Left to right') . '"></i>\' : \'<i class="icon-arrow-left visible-phone" title="' . __t('Right to left') . '"></i>\'); {/php}
			';

			$info['options']['columns'][__t('English name')]['value'] = "
				<div class=\"btn-group\">
					<a href=\"{url}/admin/locale/languages/edit/{Language.id}{/url}\" class=\"btn {$statusClass}\">
						{$label}
					</a>
					<button class=\"btn dropdown-toggle {$statusClass}\" data-toggle=\"dropdown\">
						<span class=\"caret\"></span>
					</button>
					<ul class=\"dropdown-menu\">
						{$actions}
					</ul>
				</div>
			";

			unset($info['options']['columns'][__t('Actions')], $info['options']['columns'][__t('Status')]);

			// hide columns to prevent overflow
			$info['options']['columns'][__t('Native name')]['thOptions']['class'] = 'hidden-phone';
			$info['options']['columns'][__t('Native name')]['tdOptions']['class'] = 'hidden-phone';
			$info['options']['columns'][__t('Code')]['thOptions']['class'] = 'hidden-phone';
			$info['options']['columns'][__t('Code')]['tdOptions']['class'] = 'hidden-phone';
			$info['options']['columns'][__t('Direction')]['thOptions']['class'] = 'hidden-phone';
			$info['options']['columns'][__t('Direction')]['tdOptions']['class'] = 'hidden-phone';

			//insert icons meaning at bottom for mobile devices
			$info['options']['append'] = '
				<ul class="visible-phone visible-tablet">
					<li><i class="icon-star"></i> ' . __t('Default language') . '</li>
					<li><i class="icon-arrow-left"></i> ' . __t('Right to left') . '</li>
					<li><i class="icon-arrow-right"></i> ' . __t('Left to right') . '</li>
				</ul>
			';
		}

		// styles for translatable entries table list
		if ($this->request->controller == 'translations' && $this->action == 'admin_list' && strtolower($this->plugin) == 'locale') {
			$label = '{truncate length=80} {php} return htmlentities("{Translation.original}", ENT_QUOTES, "UTF-8"); {/php} {/truncate}';
			$actions = "
				<li><a href='{url}/admin/locale/translations/edit/{Translation.id}{/url}'>" . __t('edit') . "</a></li>
				<li><a href='{url}/admin/locale/translations/regenerate/{Translation.id}{/url}' title='" . __t('Regenerate translation cache') . "'>" . __t('regenerate') . "</a></li>
				<li><a href='{url}/admin/locale/translations/delete/{Translation.id}{/url}' onclick='return confirm(\"" . __t('Delete this entry ?') . "\");'>" . __t('delete') . "</a></li>
			";

			$info['options']['columns'][__t('Text')]['value'] = "
				<div class=\"btn-group\">
					<a href=\"{url}/admin/locale/translations/edit/{Translation.id}{/url}\" class=\"btn btn-primary\">
						{$label}
					</a>
					<button class=\"btn dropdown-toggle btn-primary\" data-toggle=\"dropdown\">
						<span class=\"caret\"></span>
					</button>
					<ul class=\"dropdown-menu\">
						{$actions}
					</ul>
				</div>
			";

			unset($info['options']['columns'][__t('Actions')]);
		}

		// styles for menu table list
		if ($this->request->controller == 'manage' && $this->action == 'admin_index' && strtolower($this->plugin) == 'menu') {
			$label = '{truncate length=80} {Menu.title} {/truncate}';
			$actions = "
				<li><a href='{url}/admin/menu/manage/edit/{Menu.id}{/url}'>" . __t('edit') . "</a></li>
				<li><a href='{url}/admin/menu/manage/links/{Menu.id}{/url}'>" . __t('links') . "</a></li>
				<li><a href='{url}/admin/menu/manage/add_link/{Menu.id}{/url}'>" . __t('add link') . "</a></li>
				{php}
					return (in_array('{Menu.id}', array('main-menu', 'management', 'navigation', 'user-menu'))) ?
						'' :
						\"<li><a href='{url}/admin/menu/manage/delete/{Menu.id}{/url}' onclick='return confirm(\\\" " . __t('Delete selected menu ?') . " \\\");'>\" . __t('delete') . \"</a></li>\";
				{/php}
			";
			$info['options']['columns'][__t('Title')]['value'] = "
				<div class=\"btn-group\">
					<a href=\"{url}/admin/menu/manage/edit/{Menu.id}{/url}\" class=\"btn btn-primary\">
						{$label}
					</a>
					<button class=\"btn dropdown-toggle btn-primary\" data-toggle=\"dropdown\">
						<span class=\"caret\"></span>
					</button>
					<ul class=\"dropdown-menu\">
						{$actions}
					</ul>
				</div>";

			$info['options']['columns'][__t('Description')]['value'] = '
				<span class="visible-desktop">{php} return __t("{Menu.description}"); {/php}</span>
				<span class="visible-tablet">{truncate length=80} {php} return __t("{Menu.description}"); {/php} {/truncate}</span>
			';
			$info['options']['columns'][__t('Description')]['thOptions'] = array('class' => 'hidden-phone');
			$info['options']['columns'][__t('Description')]['tdOptions'] = array('class' => 'hidden-phone');

			unset($info['options']['columns'][__t('Actions')]);
		}

		// styles for vocabularies table list
		if ($this->request->controller == 'vocabularies' && $this->action == 'admin_index' && strtolower($this->plugin) == 'taxonomy') {
			$label = '{truncate length=80} {Vocabulary.title} {/truncate}';
			$actions = "
				<li><a href='{url}/admin/taxonomy/vocabularies/edit/{Vocabulary.slug}{/url}'>" . __t('edit') . "</a></li>
				<li><a href='{url}/admin/taxonomy/vocabularies/terms/{Vocabulary.slug}{/url}'>" . __t('terms') . "</a></li>
				<li><a href='{url}/admin/taxonomy/vocabularies/delete/{Vocabulary.id}{/url}' onclick=\"return confirm('" . __t('Delete selected vocabulary and all its terms ?') . "'); \">" . __t('delete') . "</a></li>
				<li><a href='{url}/admin/taxonomy/vocabularies/move/{Vocabulary.id}/up{/url}'>" . __t('move up') . "</a></li>
				<li><a href='{url}/admin/taxonomy/vocabularies/move/{Vocabulary.id}/down{/url}'>" . __t('move down') . "</a></li>
			";
			$info['options']['columns'][__t('Vocabulary name')]['value'] = "
				<div class=\"btn-group\">
					<a href=\"{url}/admin/menu/manage/edit/{Menu.id}{/url}\" class=\"btn btn-primary\">
						{$label}
					</a>
					<button class=\"btn dropdown-toggle btn-primary\" data-toggle=\"dropdown\">
						<span class=\"caret\"></span>
					</button>
					<ul class=\"dropdown-menu\">
						{$actions}
					</ul>
				</div>";

			$info['options']['columns'][__t('Description')]['value'] = '
				<span class="visible-desktop">{php} return __t("{Vocabulary.description}"); {/php}</span>
				<span class="visible-tablet">{truncate length=80} {php} return __t("{Vocabulary.description}"); {/php} {/truncate}</span>
			';
			$info['options']['columns'][__t('Description')]['thOptions'] = array('class' => 'hidden-phone');
			$info['options']['columns'][__t('Description')]['tdOptions'] = array('class' => 'hidden-phone');

			unset($info['options']['columns'][__t('Actions')]);
		}

		// styles for content-types table list
		if ($this->request->controller == 'types' && $this->action == 'admin_index' && strtolower($this->plugin) == 'node') {
			$label = '{truncate length=80} {NodeType.name} [{NodeType.id}] {/truncate}';
			$actions = "
				<li><a href='{url}/admin/node/types/display/{NodeType.id}{/url}'>" . __t('display') . "</a></li>
				<li><a href='{url}/admin/node/types/edit/{NodeType.id}{/url}'>" . __t('edit') . "</a></li>
				<li><a href='{url}/admin/node/types/fields/{NodeType.id}{/url}'>" . __t('fields') . "</a></li>
				{php} return ('{NodeType.module}' == 'Node') ? \"<li><a href='{url}/admin/node/types/delete/{NodeType.id}{/url}' onClick=\\\"return confirm('" . __t("Are you sure that you want to delete this type of content. ? This action cannot be undone.") . "'); \\\">" . __t('delete') . "</a></li>\" : '';{/php}
			";
			$info['options']['columns'][__t('Name')]['value'] = "
				<div class=\"btn-group\">
					<a href=\"{url}/admin/node/types/edit/{NodeType.id}{/url}\" class=\"btn btn-primary\">
						{$label}
					</a>
					<button class=\"btn dropdown-toggle btn-primary\" data-toggle=\"dropdown\">
						<span class=\"caret\"></span>
					</button>
					<ul class=\"dropdown-menu\">
						{$actions}
					</ul>
				</div>";
			$info['options']['headerPosition'] = 'top';

			unset($info['options']['columns'][__t('Actions')]);
		}

		// styles for node-type-display table list
		if ($this->request->controller == 'types' && $this->action == 'admin_display' && strtolower($this->plugin) == 'node') {
			$label = '{truncate length=80} {Field.label} {/truncate}';
			$actions = "
				<li><a href='{url}/admin/node/types/field_formatter/{Field.id}/display:" . $this->_View->viewVars['display'] . "{/url}'>" . __t('edit format') . "</a></li>
				<li><a href='{url}/admin/field/handler/move/{Field.id}/up/" . $this->_View->viewVars['display'] . "{/url}'>" . __t('move up') . "</a></li>
				<li><a href='{url}/admin/field/handler/move/{Field.id}/down/" . $this->_View->viewVars['display'] . "{/url}'>" . __t('move down') . "</a></li>
			";
			$info['options']['columns'][__t('Name')]['value'] = "
				<div class=\"btn-group\">
					<a href=\"{url}/admin/node/types/field_formatter/{Field.id}/display:" . $this->_View->viewVars['display'] . "{/url}\" class=\"btn btn-primary\">
						{$label}
					</a>
					<button class=\"btn dropdown-toggle btn-primary\" data-toggle=\"dropdown\">
						<span class=\"caret\"></span>
					</button>
					<ul class=\"dropdown-menu\">
						{$actions}
					</ul>
				</div>";

			$info['options']['columns'][__t('Label')]['thOptions'] = array('class' => 'hidden-phone');
			$info['options']['columns'][__t('Label')]['tdOptions'] = array('class' => 'hidden-phone');
			$info['options']['columns'][__t('Format')]['thOptions'] = array('class' => 'hidden-phone');
			$info['options']['columns'][__t('Format')]['tdOptions'] = array('class' => 'hidden-phone');

			unset($info['options']['columns'][__t('Actions')]);		
		}
	}

	public function form_input_alter(&$info) {
		if (isset($info['options']['type']) && $info['options']['type'] == 'submit') {
			$this->__button($info);
		}

		$type = isset($info['options']['type']) ? $info['options']['type'] : '';
		$info['options']['div'] = "input {$type} control-group";
	}

	public function form_help_block_alter(&$info) {
		$info['options']['tag'] = 'span';
		$info['options']['class'] = 'help-block';
	}

	public function menu_toolbar_alter(&$info) {
		$info['options']['class'] = 'nav nav-pills';
	}

	public function form_submit_alter(&$info) {
		$this->__button($info);
	}

	public function form_error_alter(&$info) {
		$info['options']['class'] = 'help-inline';
	}

	public function form_button_alter(&$info) {
		$this->__button($info);
	}

	private function __button(&$info) {
		$info['options']['label'] = false;
		$info['options']['div'] = false;
		$info['options']['class'] = 'btn btn-primary';
	}
}
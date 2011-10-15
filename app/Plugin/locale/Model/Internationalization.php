<?php
/**
 * Locale i18n Model
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Locale.Model
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class Internationalization extends LocaleAppModel {
    public $name = 'Internationalization';
    public $useTable = "i18n";
    public $primaryKey = 'id';
}
<?php
/**
 * Field Model Hooks
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Field.Model.Behavior
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class FieldHookBehavior extends ModelBehavior {

/**
 * Return an associative array with field(s) information.
 *
 * @param mixed $field Optional string will return only information for the specified field.
 *                     FALSE will return all fields information.
 * @return array Associative array.
 */
    public function field_info($field = false) {
        $field = is_string($field) ? Inflector::camelize($field) : '';
        $field_modules = array();
        $plugins = App::objects('plugins');

        if (!empty($field) && in_array($field, $plugins)) {
            $plugins = array();
            $plugins[] = $field;
        }

        foreach ($plugins as $plugin) {
            $ppath = App::pluginPath($plugin);

            if (strpos($ppath, DS . 'Fields' . DS . $plugin . DS) !== false) {
                $yaml = Spyc::YAMLLoad($ppath . "{$plugin}.yaml");
                $yaml['path'] = $ppath;
                $field_modules[$plugin] = $yaml;
            }
        }

        return $field_modules;
    }
}
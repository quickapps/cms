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
namespace Field\Field;

use Cake\Filesystem\File;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use Field\Handler;
use Field\Model\Entity\Field;
use Field\Model\Entity\FieldInstance;
use QuickApps\View\View;

/**
 * File Field Handler.
 *
 * This field allows attach files to entities.
 */
class FileField extends Handler
{

    /**
     * {@inheritDoc}
     */
    public function info()
    {
        return [
            'type' => 'text',
            'name' => __d('field', 'Attachment'),
            'description' => __d('field', 'Allows to upload and attach files to contents.'),
            'hidden' => false,
            'maxInstances' => 0,
            'searchable' => false,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function render(Field $field, View $view)
    {
        if ($field->metadata->settings['multi'] === 'custom') {
            $settings = $field->metadata->settings;
            $settings['multi'] = $field->metadata->settings['multi_custom'];
            $field->metadata->set('settings', $settings);
        }
        return $view->element('Field.FileField/display', compact('field'));
    }

    /**
     * {@inheritDoc}
     */
    public function edit(Field $field, View $view)
    {
        return $view->element('Field.FileField/edit', compact('field'));
    }

    /**
     * {@inheritDoc}
     */
    public function fieldAttached(Field $field)
    {
        $extra = (array)$field->extra;
        if (!empty($extra)) {
            $newExtra = [];
            foreach ($extra as $file) {
                $newExtra[] = array_merge([
                    'mime_icon' => '',
                    'file_name' => '',
                    'file_size' => '',
                    'description' => '',
                ], (array)$file);
            }
            $field->set('extra', $newExtra);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function validate(Field $field, Validator $validator)
    {
        if ($field->metadata->required) {
            $validator
                ->add($field->name, 'isRequired', [
                    'rule' => function ($value, $context) use ($field) {
                        if (isset($context['data'][$field->name])) {
                            $count = 0;
                            foreach ($context['data'][$field->name] as $k => $file) {
                                if (is_integer($k)) {
                                    $count++;
                                }
                            }
                            return $count > 0;
                        }
                        return false;
                    },
                    'message' => __d('field', 'You must upload one file at least.')
                ]);
        }

        if ($field->metadata->settings['multi'] !== 'custom') {
            $maxFiles = intval($field->metadata->settings['multi']);
        } else {
            $maxFiles = intval($field->metadata->settings['multi_custom']);
        }

        $validator
            ->add($field->name, 'numberOfFiles', [
                'rule' => function ($value, $context) use ($field, $maxFiles) {
                    if (isset($context['data'][$field->name])) {
                        $count = 0;
                        foreach ($context['data'][$field->name] as $k => $file) {
                            if (is_integer($k)) {
                                $count++;
                            }
                        }

                        return $count <= $maxFiles;
                    }
                    return false;
                },
                'message' => __d('field', 'You can upload {0} files as maximum.', $maxFiles)
            ]);

        if (!empty($field->metadata->settings['extensions'])) {
            $extensions = $field->metadata->settings['extensions'];
            $extensions = array_map('strtolower', array_map('trim', explode(',', $extensions)));
            $validator
                ->add($field->name, 'extensions', [
                    'rule' => function ($value, $context) use ($field, $extensions) {
                        if (isset($context['data'][$field->name])) {
                            foreach ($context['data'][$field->name] as $k => $file) {
                                if (is_integer($k)) {
                                    $ext = strtolower(str_replace('.', '', strrchr($file['file_name'], '.')));
                                    if (!in_array($ext, $extensions)) {
                                        return false;
                                    }
                                }
                            }
                            return true;
                        }
                        return false;
                    },
                    'message' => __d('field', 'Invalid file extension. Allowed extension are: {0}', $field->metadata->settings['extensions'])
                ]);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * - extra: Holds a list (array) of files and their in formation (mime-icon,
     *   file name, etc).
     *
     * - value: Holds a text containing all file names separated by space.
     */
    public function beforeSave(Field $field, $post)
    {
        // FIX Removes the "dummy" input from extra if exists, the "dummy" input is
        // used to force Field Handler to work when empty POST information is sent
        $extra = [];
        foreach ((array)$field->extra as $k => $v) {
            if (is_integer($k)) {
                $extra[] = $v;
            }
        }
        $field->set('extra', $extra);

        $files = (array)$post;
        if (!empty($files)) {
            $value = [];
            foreach ($files as $k => $file) {
                if (!is_integer($k)) {
                    unset($files[$k]);
                    continue;
                } else {
                    $file = array_merge([
                        'mime_icon' => '',
                        'file_name' => '',
                        'file_size' => '',
                        'description' => '',
                    ], (array)$file);
                }
                $value[] = trim("{$file['file_name']} {$file['description']}");
            }
            $field->set('value', implode(' ', $value));
            $field->set('extra', $files);
        }

        if ($field->metadata->value_id) {
            $newFileNames = Hash::extract($files, '{n}.file_name');

            try {
                $prevFiles = (array)TableRegistry::get('Eav.EavValues')
                    ->get($field->metadata->value_id)
                    ->extra;
            } catch (\Exception $ex) {
                $prevFiles = [];
            }

            foreach ($prevFiles as $f) {
                if (!in_array($f['file_name'], $newFileNames)) {
                    $file = normalizePath(WWW_ROOT . "/files/{$field->metadata->settings['upload_folder']}/{$f['file_name']}", DS);
                    $file = new File($file);
                    $file->delete();
                }
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function settings(FieldInstance $instance, View $view)
    {
        return $view->element('Field.FileField/settings_form', compact('instance'));
    }

    /**
     * {@inheritDoc}
     */
    public function defaultSettings(FieldInstance $instance)
    {
        return [
            'extensions' => '',
            'multi' => 1,
            'multi_custom' => 1,
            'upload_folder' => '',
            'description' => '',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function viewModeSettings(FieldInstance $instance, View $view, $viewMode)
    {
        return $view->element('Field.FileField/view_mode_form', compact('instance', 'viewMode'));
    }

    /**
     * {@inheritDoc}
     */
    public function defaultViewModeSettings(FieldInstance $instance, $viewMode)
    {
        return [
            'label_visibility' => 'above',
            'shortcodes' => true,
            'hidden' => false,
            'formatter' => 'link',
        ];
    }
}

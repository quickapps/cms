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
namespace Field\Event;

use Cake\Event\Event;
use Cake\Filesystem\File;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Field\Event\Base\FieldHandler;
use Field\Model\Entity\Field;
use Field\Utility\TextToolbox;

/**
 * File Field Handler.
 *
 * This field allows attach files to entities.
 */
class FileField extends FieldHandler
{

/**
 * {@inheritDoc}
 */
    public function entityDisplay(Event $event, Field $field, $options = [])
    {
        $View = $event->subject;
        if ($field->metadata->settings['multi'] === 'custom') {
            $settings = $field->metadata->settings;
            $settings['multi'] = $field->metadata->settings['multi_custom'];
            $field->metadata->set('settings', $settings);
        }
        return $View->element('Field.FileField/display', compact('field', 'options'));
    }

/**
 * {@inheritDoc}
 */
    public function entityEdit(Event $event, Field $field, $options = [])
    {
        $View = $event->subject;
        return $View->element('Field.FileField/edit', compact('field', 'options'));
    }

/**
 * {@inheritDoc}
 */
    public function entityFieldAttached(Event $event, Field $field)
    {
        $raw = (array)$field->raw;
        if (!empty($raw)) {
            $newRaw = [];
            foreach ($raw as $file) {
                $newRaw[] = array_merge([
                    'mime_icon' => '',
                    'file_name' => '',
                    'file_size' => '',
                    'description' => '',
                ], (array)$file);
            }
            $field->set('raw', $newRaw);
        }
    }

/**
 * {@inheritDoc}
 */
    public function entityBeforeFind(Event $event, Field $field, $options, $primary)
    {
    }

/**
 * {@inheritDoc}
 */
    public function entityBeforeSave(Event $event, Field $field, $options)
    {
        $files = (array)$options['_post'];

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
            $field->set('raw', $files);
        }

        if ($field->metadata->field_value_id) {
            $newFileNames = Hash::extract($files, '{n}.file_name');
            $prevFiles = (array)TableRegistry::get('Field.FieldValues')
                ->get($field->metadata->field_value_id)
                ->raw;

            foreach ($prevFiles as $f) {
                if (!in_array($f['file_name'], $newFileNames)) {
                    $file = normalizePath(WWW_ROOT . "/files/{$field->settings['upload_folder']}/{$f['file_name']}", DS);
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
    public function entityAfterSave(Event $event, Field $field, $options)
    {
    }

/**
 * {@inheritDoc}
 */
    public function entityBeforeValidate(Event $event, Field $field, $options, $validator)
    {
        if ($field->metadata->required) {
            $validator
                ->add(":{$field->name}", 'isRequired', [
                    'rule' => function ($value, $context) use ($field) {
                        if (isset($context['data'][":{$field->name}"])) {
                            $count = 0;
                            foreach ($context['data'][":{$field->name}"] as $k => $file) {
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

        $maxFiles = 0;
        if ($field->metadata->settings['multi'] !== 'custom') {
            $maxFiles = intval($field->metadata->settings['multi']);
        } else {
            $maxFiles = intval($field->metadata->settings['multi_custom']);
        }

        $validator
            ->add(":{$field->name}", 'numberOfFiles', [
                'rule' => function ($value, $context) use ($field, $maxFiles) {
                    if (isset($context['data'][":{$field->name}"])) {
                        $count = 0;
                        foreach ($context['data'][":{$field->name}"] as $k => $file) {
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
                ->add(":{$field->name}", 'extensions', [
                    'rule' => function ($value, $context) use ($field, $extensions) {
                        if (isset($context['data'][":{$field->name}"])) {
                            foreach ($context['data'][":{$field->name}"] as $k => $file) {
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
 */
    public function entityAfterValidate(Event $event, Field $field, $options, $validator)
    {
        // removes the "dummy" input from raw if exists
        $raw = [];
        foreach ((array)$field->raw as $k => $v) {
            if (is_integer($k)) {
                $raw[] = $v;
            }
        }
        $field->set('raw', $raw);
        return true;
    }

/**
 * {@inheritDoc}
 */
    public function entityBeforeDelete(Event $event, Field $field, $options)
    {
        return true;
    }

/**
 * {@inheritDoc}
 */
    public function entityAfterDelete(Event $event, Field $field, $options)
    {
    }

/**
 * {@inheritDoc}
 *
 * @param \Cake\Event\Event $event
 * @return array
 */
    public function instanceInfo(Event $event)
    {
        return [
            'name' => __d('field', 'Attachment'),
            'description' => __d('field', 'Allows to upload and attach files to contents.'),
            'hidden' => false
        ];
    }

/**
 * {@inheritDoc}
 */
    public function instanceSettingsForm(Event $event, $instance, $options = [])
    {
        $View = $event->subject;
        return $View->element('Field.FileField/settings_form', compact('instance', 'options'));
    }

/**
 * {@inheritDoc}
 */
    public function instanceSettingsDefaults(Event $event, $instance, $options = [])
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
    public function instanceSettingsValidate(Event $event, Entity $settings, $validator)
    {
    }

/**
 * {@inheritDoc}
 */
    public function instanceViewModeForm(Event $event, $instance, $options = [])
    {
        $View = $event->subject;
        return $View->element('Field.FileField/view_mode_form', compact('instance', 'options'));
    }

/**
 * {@inheritDoc}
 */
    public function instanceViewModeDefaults(Event $event, $instance, $options = [])
    {
        switch ($options['viewMode']) {
            default:
                return [
                    'label_visibility' => 'above',
                    'hooktags' => true,
                    'hidden' => false,
                    'formatter' => 'link',
                ];
        }
    }

/**
 * {@inheritDoc}
 */
    public function instanceViewModeValidate(Event $event, Entity $viewMode, $validator)
    {
    }

/**
 * {@inheritDoc}
 */
    public function instanceBeforeAttach(Event $event, $instance, $options = [])
    {
        return true;
    }

/**
 * {@inheritDoc}
 */
    public function instanceAfterAttach(Event $event, $instance, $options = [])
    {
    }

/**
 * {@inheritDoc}
 */
    public function instanceBeforeDetach(Event $event, $instance, $options = [])
    {
        return true;
    }

/**
 * {@inheritDoc}
 */
    public function instanceAfterDetach(Event $event, $instance, $options = [])
    {
    }
}

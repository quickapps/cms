<?php
    $field['FieldData'] = !isset($field['FieldData']) ? array() : $field['FieldData'];
    $field['FieldData'] = array_merge(array('id' => null, 'field_id' => null, 'foreignKey' => null, 'belongsTo' => null, 'data' => ''), $field['FieldData']);
    $options = array();

    $field['settings'] = array_merge(
        array(
            'vocabulary' => 0,
            'type' => 'checkbox',
            'max_values' => 0
        ),
        $field['settings']
    );

    if ($field['settings']['vocabulary'] > 0) {
        $options = ClassRegistry::init('Taxonomy.Term')->generateTreeList(
            array(
                'Term.vocabulary_id' => $field['settings']['vocabulary']
            ), null, null, '&nbsp;&nbsp;&nbsp;|-&nbsp;'
        );
    } else {
        echo "<label>{$field['label']}</label>";
       echo __t('You must <a href="%s">select a vocabulary</a> for this field!', Router::url("/admin/node/types/field_settings/{$field['id']}"));

       return;
    }

    if (isset($this->data['FieldData']['TaxonomyTerms'][$field['id']]['data'])) {
        $selected = $this->data['FieldData']['TaxonomyTerms'][$field['id']]['data'];
    } else {
        $selected = explode(',', (string)$field['FieldData']['data']);
    }

    // max_values > 1
    $Options = array(
        'escape' => false,
        'type' => 'select',
        'label' => $field['label'],
        'multiple' => ($field['settings']['type'] === 'checkbox' ? 'checkbox' : true),
        'options' => $options,
        'value' => $selected
    );

    if (in_array($field['settings']['type'], array('select', 'checkbox'))) {
        if ($field['settings']['type'] == 'select' && $field['settings']['max_values'] == 1) {
            $Options['multiple'] = false;
        } elseif ($field['settings']['type'] == 'checkbox' && $field['settings']['max_values'] == 1) {
            $Options['type'] = 'radio';
            $Options['separator'] = '<br />';
            $Options['legend'] = $field['label'];
            $Options['value'] = @$selected[0];

            unset($Options['multiple']);
        }
    } else {
        $Options['type'] = 'text';
        $Options['class'] = 'tags';

        unset($Options['multiple'], $Options['options']);
        echo $this->Layout->hook('taxonomy_terms_render_autocomplete', $field);
    }

    echo $this->Form->input("FieldData.TaxonomyTerms.{$field['id']}.data", $Options);
    echo $this->Form->hidden("FieldData.TaxonomyTerms.{$field['id']}.id", array('value' => $field['FieldData']['id']));
?>

<?php if (!empty($field['description'])): ?>
    <em><?php echo $field['description']; ?></em>
<?php endif; ?>
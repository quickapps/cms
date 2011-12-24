<?php
    $data['FieldData'] = !isset($data['FieldData']) ? array() : $data['FieldData'];
    $data['FieldData'] = array_merge(array('id' => null, 'field_id' => null, 'foreignKey' => null, 'belongsTo' => null, 'data' => ''), $data['FieldData']);
    $options = array();

    $data['settings'] = array_merge(
        array(
            'vocabulary' => 0,
            'type' => 'checkbox',
            'max_values' => 0
        ),
        $data['settings']
    );

    if ($data['settings']['vocabulary'] > 0) {
        $options = ClassRegistry::init('Taxonomy.Term')->generateTreeList(
            array(
                'Term.vocabulary_id' => $data['settings']['vocabulary']
            ), null, null, '&nbsp;&nbsp;&nbsp;|-&nbsp;'
        );
    }

    if (isset($this->data['FieldData']['FieldTerms'][$data['id']]['data'])) {
        $selected = $this->data['FieldData']['FieldTerms'][$data['id']]['data'];
    } else {
        $selected = explode('|', (string)$data['FieldData']['data']);
    }

    // max_values > 1
    $Options = array(
        'escape' => false,
        'type' => 'select',
        'label' => $data['label'],
        'multiple' => ($data['settings']['type'] === 'checkbox' ? 'checkbox' : true),
        'options' => $options,
        'value' => $selected
    );

    if ($data['settings']['type'] == 'select' && $data['settings']['max_values'] == 1) {
        $Options['multiple'] = false;
    } elseif ($data['settings']['type'] == 'checkbox' && $data['settings']['max_values'] == 1) {
        $Options['type'] = 'radio';
        $Options['separator'] = '<br />';
        $Options['legend'] = $data['label'];
        $Options['value'] = @$selected[0];
        unset($Options['multiple']);
    }

    echo $this->Form->input("FieldData.FieldTerms.{$data['id']}.data", $Options);
    echo $this->Form->hidden("FieldData.FieldTerms.{$data['id']}.id", array('value' => $data['FieldData']['id']));
?>

<?php if (!empty($data['description'])): ?>
    <em><?php echo $data['description']; ?></em>
<?php endif; ?>
<?php
class FieldFileHookHelper extends AppHelper {
    private $__instancesCount = 0;

    public function field_file_libs() {
        $out = '';

        if (!$this->__instancesCount) {
            $out .= $this->_View->Html->css('/field_file/js/uploadify/uploadify.css');
            $out .= $this->_View->Html->css('/field_file/css/field_file.css');
            $out .= $this->_View->Html->script('/system/js/ui/jquery-ui.js');
            $out .= $this->_View->Html->script('/system/js/json.js');
            $out .= $this->_View->Html->script('/field_file/js/field_file.js');
            $out .= $this->_View->Html->script('/field_file/js/locale.' . Configure::read('Variable.language.code') . '.js');
            $out .= $this->_View->Html->script('/field_file/js/uploadify/swfobject.js');
            $out .= $this->_View->Html->script('/field_file/js/uploadify/jquery.uploadify.v2.1.4.min.js');
            $out .=  "
            <script type=\"text/javascript\">
                QuickApps.field_file.uploader = '" . Router::url('/field_file/js/uploadify/uploadify.swf') . "';
                QuickApps.field_file.session_id = '" . CakeSession::id() . "';
                QuickApps.field_file.cancelImg = '" . Router::url('/field_file/js/uploadify/cancel.png') . "';
            </script>";
        }

        return $out;
    }

    public function field_file_formatter($data) {
        $__default = array(
            'content' => array(
                'files' => array()
            ),
            'settings' => array(),
            'format' => array()
        );
        $data = Set::merge($__default, $data);
        $content = '';
        $base_url = '/files/' . $data['settings']['upload_folder'];

        foreach ($data['content']['files'] as $file) {
            $title = '';
            $title .= isset($file['mime_icon']) && !empty($file['mime_icon']) ? $this->_View->Html->image("/field_file/img/icons/{$file['mime_icon']}", array('border' => 0)) : '';
            $title .= ' ' . $file['file_name'];
            $description = $data['settings']['description'] && isset($file['description']) ? "<div class=\"description\"><em>{$file['description']}</em></div>": '';

            switch($data['format']['type']) {
                case 'link':
                    default:
                        $content .= '<p>';
                            $content .= $this->_View->Html->link($title, $base_url . $file['file_name'], array('escape' => false, 'target' => '_blank'));
                            $content .= $description;
                        $content .= '</p>';
                break;

                case 'table':
                    $content .= '<tr>
                        <td align="left">' . $this->_View->Html->link($title, $base_url . $file['file_name'], array('escape' => false, 'target' => '_blank')) . $description . '</td>
                        <td align="center">' . $file['file_size'] . '</td>
                    </tr>';
                break;

                case 'url':
                    $content .= "<p>{$base_url}{$file['file_name']}</p>";
                break;
            }
        }

        if ($data['format']['type'] == 'table' && count($data['content']['files'])) {
            $content = "
            <table width=\"100%\">
                <thead>
                    <tr>
                        <th align=\"left\" width=\"75%\">" . __d('field_file', 'Attachment') . "</th>
                        <th align=\"center\">" . __d('field_file', 'Size') . "</th>
                    </tr>
                </thead>

                <tbody>
                    {$content}
                </tbody>
            </table>";
        }

        return $content;
    }
}
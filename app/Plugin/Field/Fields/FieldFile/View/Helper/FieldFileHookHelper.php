<?php
class FieldFileHookHelper extends AppHelper {
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
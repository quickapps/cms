<?php
/**
 * Table Helper
 *
 * PHP version 5
 *
 * @package  QuickApps.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */

/**
 * ##Expected data's structure
 * $data MUST be a numeric array. (Any list result of `Model::find()` or paginated result)
 *     {{{
 *         $data = array(
 *             0 => array(
 *                 'Model' => array('field1' => 'data', ...),
 *                 'Model2' => ...
 *             ),
 *             ....
 *         );
 *     }}}
 *
 * ##Options:
 * columns (array): Information about each of the columns of your table.
 *    {{{
 *         ...
 *         'Column Title' => array(
 *             'value' => ,        # (string) Values to display when filling this column.
 *                                     You can specify array paths to find in the $data array. e.g.: `{Model.field}`
 *                                     Also:
 *                                         {php}{/php}: Will print out the result returned by the PHP code. e.g.: {php} return 'hello world!'; {/php}
 *                                         {url}{/url}: Will print out the specified internal/external URL. e.g.: {url}/this/is_an/internal_url{/url}
 *             'thOptions' => ,    # (array) <th> tag options for this column. This will affect table header only.
 *             'tdOptions' => ,    # (array) <td> tag options for this column. This will affect table body (result rows) only.
 *             'sort' =>           # Optional (string) `Model.field`
 *         )
 *         ...
 *     }}}
 *
 * headerPosition (mixed): render header at `top`, `bottom`, `top&bottom`, false (no render).
 * headerRowOptions (array): header <tr> tag attributes.
 * noItemsMessage (string): message when there are 0 records.
 * tableOptions (array): table tag <table> attributes.
 * paginate (array): set to false for no pagination.
 */
class TableHelper extends AppHelper {
    public $helpers = array('Html', 'Paginator');
    private $_defaults = array(
        'columns' => array(),
        'headerPosition' => 'top',
        'headerRowOptions' => array(),
        'noItemsMessage' => 'There are no items to display',
        'tableOptions' => array('cellpadding' => 0, 'cellspacing' => 0, 'border' => 0),
        'paginate' => array(
            'options' => array(),
            'prev' => array(
                'title' => '« Previous ',
                'options' => array(),
                'disabledTitle' => null,
                'disabledOptions' => array('class' => 'disabled')
            ),
            'numbers' => array(
                'options' => array(
                    'before' => ' &nbsp; ',
                    'after' => ' &nbsp; ',
                    'modulus' => 10,
                    'separator' => ' &nbsp; ',
                    'tag' => 'span',
                    'first' => 'first',
                    'last' => 'last',
                    'ellipsis' => '...'
                )
            ),
            'next' => array(
                'title' => ' Next »',
                'options' => array(),
                'disabledTitle' => null,
                'disabledOptions' => array('class' => 'disabled')
            ),

            'position' => 'bottom',                                # String: row position, 'top', 'top&bottom', 'bottom'
            'trOptions' => array('class' => 'paginator'),        # Array: <tr> tag attributes
            'tdOptions' => array('align' => 'center')            # Array: <td> tag attributes
        )
    );

    private $_columnDefaults = array(
        'value' => '',                                # String: cell content,
        'thOptions' => array('align' => 'left'),    # Array: th attributes, header cells (text align left by default)
        'tdOptions' => array('align' => 'left'),    # Array: td attributes, body cells (text align left by default)
        'sort' => false                                # Mix: sortable field name:String, false (no sort this col), paginate must be on (see paginate option)
    );

    private $_colsCount = 0;

    public function create($data, $options) {
        $this->_defaults['paginate']['prev']['title'] = __t('« Previous ');
        $this->_defaults['paginate']['next']['title'] = __t(' Next »');

        if (isset($options['paginate']) && $options['paginate'] === true) {
            unset($options['paginate']); # default settings
        } else {
            $this->_defaults['paginate'] = !isset($options['paginate']) ? false : $this->_defaults['paginate'];
        }

        $options = Set::merge($this->_defaults, $options);
        $this->_colsCount = count($options['columns']);
        $out = sprintf('<table%s>', $this->Html->_parseAttributes($options['tableOptions'])) . "\n";

        if (count($data) > 0) {

            $print_header_top = ($options['headerPosition'] !== false && in_array($options['headerPosition'], array('top', 'top&bottom')));
            $print_paginator_top = ($options['paginate'] !== false && in_array($options['paginate']['position'], array('top', 'top&bottom')));

            if ($print_header_top ||  $print_paginator_top) {
                $out .= "\t<thead>\n";
                $out .= $print_header_top ? $this->_renderHeader($options) : '';
                $out .= $print_paginator_top ? $this->_renderPaginator($options) : '';
                $out .= "\n\t</thead>\n";
            }

            $out .= "\t<tbody>\n";
            $count = 1;

            foreach ($data as $i => $r_data) {
                $td = '';

                foreach ($options['columns'] as $name => $c_data) {
                    $c_data = array_merge($this->_columnDefaults, $c_data);

                    $td .= "\n\t";
                    $td .= $this->Html->useTag('tablecell', $this->Html->_parseAttributes($c_data['tdOptions']),$this->_renderCell($c_data['value'], $data[$i]));
                    $td .= "\t";
                }

                $tr_class = $count%2 ? 'even' : 'odd';
                $out .= $this->Html->useTag('tablerow', $this->Html->_parseAttributes(array('class' => $tr_class)), $td);
                $count++;
            }

            $out .= "\t</tbody>\n";
            $print_header_bottom = ($options['headerPosition'] !== false && in_array($options['headerPosition'], array('bottom', 'top&bottom')));
            $print_paginator_bottom = ($options['paginate'] != false && in_array($options['paginate']['position'], array('bottom', 'top&bottom')));

            if ($print_header_bottom || $print_paginator_bottom) {
                $out .= "\t<tfoot>\n";
                $out .= $print_header_bottom ? $this->_renderHeader($options) : '';
                $out .= $print_paginator_bottom ? $this->_renderPaginator($options) : '';
                $out .= "\n\t</tfoot>\n";
            }
        } else {
            $td   = $this->Html->useTag('tablecell', $this->Html->_parseAttributes(array('colspan' => $this->_colsCount)), __t($options['noItemsMessage']));
            $out .= $this->Html->useTag('tablerow', $this->Html->_parseAttributes(array('class' => 'even')), $td);
        }

        $out .= "</table>\n";

        return $out;
    }

    protected function _renderCell($value, $row_data) {
        # look for urls
        preg_match_all('/\{url\}(.+)\{\/url\}/iUs', $value, $url);
        if (isset($url[1]) && !empty($url[1])) {
            foreach ($url[0] as $i => $m) {
                $value = str_replace($m, $this->Html->url(trim($url[1][$i]), true), $value);
            }
        }

        # look for array paths
        preg_match_all('/\{([\{\}0-9a-zA-Z_\.]+)\}/iUs', $value, $path);
        if (isset($path[1]) && !empty($path[1])) {
            foreach ($path[0] as $i => $m) {
                if (in_array($m, array('{php}', '{/php}'))) {
                    continue;
                }

                $value = str_replace($m, Set::extract(trim($path[1][$i]), $row_data), $value);
            }
        }

        # look for images
        preg_match_all('/\{img(.*)\}(.+)\{\/img\}/iUs', $value, $img);
        if (isset($img[1]) && !empty($img[1])) {
            foreach ($img[0] as $i => $m) {
                $opts = isset($img[1][$i]) ? $this->__parseAtts(trim($img[1][$i])) : array();
                $value = str_replace($m, $this->Html->image(trim($img[2][$i]), $opts), $value);
            }
        }

        # look for links
        preg_match_all('/\{link(.*)\}(.+)\{\/link\}/iUs', $value, $link);
        if (isset($link[1]) && !empty($link[1])) {
            foreach ($link[0] as $i => $m) {
                $opts = isset($link[1][$i]) ? $this->__parseAtts(trim($link[1][$i])) : array();
                $value = str_replace($m, $this->Html->link(trim($link[2][$i]), $opts), $value);
            }
        }

        # look for php code
        preg_match_all('/\{php\}(.+)\{\/php\}/iUs', $value, $php);
        if (isset($php[1]) && !empty($php[1])) {
            foreach ($php[0] as $i => $m) {
                $value = str_replace($m, $this->__php_eval("<?php {$php[1][$i]}", $row_data), $value);
            }
        }

        return $value;
    }

/**
 * Parse hooktags attributes
 *
 * @param string $text Tag string to parse
 * @return array Array of attributes
 */
    private function __parseAtts($text) {
        $atts = array();
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);

        if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
            foreach ($match as $m) {
                if (!empty($m[1])) {
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                } elseif (!empty($m[3])) {
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                } elseif (!empty($m[5])) {
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                } elseif (isset($m[7]) and strlen($m[7])) {
                    $atts[] = stripcslashes($m[7]);
                } elseif (isset($m[8])) {
                    $atts[] = stripcslashes($m[8]);
                }
            }
        } else {
            $atts = ltrim($text);
        }

        return $atts;
    }

    private function __php_eval($code, $row_data = array()) {
        ob_start();
        print eval('?>' . $code);

        $output = ob_get_contents();

        ob_end_clean();

        return $output;
    }

    protected function _renderHeader($options, $footer = false) {
        $th = $out ='';

        if ($footer && $options['paginate'] !== false && in_array($options['paginate']['position'], array('top', 'top&bottom'))) {
            @$out .= $this->_renderPaginator($options);
        }

        foreach ($options['columns'] as $name => $data) {
            $data = array_merge($this->_columnDefaults, $data);
            if ($options['paginate'] !== false && is_string($data['sort'])) {
                @$name = $this->Paginator->sort($data['sort'], $name);
            }

            $th .= "\t\t". $this->Html->useTag('tableheader', $this->Html->_parseAttributes($data['thOptions']), $name) . "\n";
        }

        $out .= $this->Html->useTag('tablerow', null, $th);

        return $out;
    }

    protected function _renderPaginator($array) {
        $out = $paginator = '';
        $array = $array['paginate'];
        $paginator .= $this->Paginator->options($array['options']);
        $paginator .= $this->Paginator->prev($array['prev']['title'], $array['prev']['options'], $array['prev']['disabledTitle'], $array['prev']['disabledOptions']);
        $paginator .= $this->Paginator->numbers($array['numbers']['options']);
        $paginator .= $this->Paginator->next($array['next']['title'], $array['next']['options'], $array['next']['disabledTitle'], $array['next']['disabledOptions']);
        $td = $this->Html->useTag('tablecell', $this->Html->_parseAttributes(array_merge(array('colspan' => $this->_colsCount), $array['tdOptions'])), $paginator);
        $out .= $this->Html->useTag('tablerow', $this->Html->_parseAttributes($array['trOptions']), $td);

        return $out;
    }
}
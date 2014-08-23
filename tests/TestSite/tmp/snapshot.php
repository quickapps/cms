<?php
$config = array (
  'QuickApps' => 
  array (
    'version' => '2.0-dev0',
    'node_types' => 
    array (
    ),
    'plugins' => 
    array (
      'BackendTheme' => 
      array (
        'name' => 'BackendTheme',
        'human_name' => 'Backend',
        'package' => 'quickapps-plugins',
        'isTheme' => true,
        'isCore' => true,
        'hasHelp' => false,
        'hasSettings' => false,
        'eventListeners' => 
        array (
        ),
        'status' => true,
        'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\BackendTheme',
      ),
      'Block' => 
      array (
        'name' => 'Block',
        'human_name' => 'Block',
        'package' => 'quickapps-plugins',
        'isTheme' => false,
        'isCore' => true,
        'hasHelp' => true,
        'hasSettings' => false,
        'eventListeners' => 
        array (
          'Block\\Event\\BlockHook' => 
          array (
            'namespace' => 'Block\\Event\\',
            'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Block\\src\\Event',
          ),
        ),
        'status' => true,
        'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Block',
      ),
      'Comment' => 
      array (
        'name' => 'Comment',
        'human_name' => 'Comment',
        'package' => 'quickapps-plugins',
        'isTheme' => false,
        'isCore' => true,
        'hasHelp' => true,
        'hasSettings' => true,
        'eventListeners' => 
        array (
          'Comment\\Event\\CommentHook' => 
          array (
            'namespace' => 'Comment\\Event\\',
            'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Comment\\src\\Event',
          ),
        ),
        'status' => true,
        'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Comment',
      ),
      'Field' => 
      array (
        'name' => 'Field',
        'human_name' => 'Field',
        'package' => 'quickapps-plugins',
        'isTheme' => false,
        'isCore' => true,
        'hasHelp' => true,
        'hasSettings' => false,
        'eventListeners' => 
        array (
          'Field\\Event\\FieldHook' => 
          array (
            'namespace' => 'Field\\Event\\',
            'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Field\\src\\Event',
          ),
          'Field\\Event\\ListField' => 
          array (
            'namespace' => 'Field\\Event\\',
            'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Field\\src\\Event',
          ),
          'Field\\Event\\TextField' => 
          array (
            'namespace' => 'Field\\Event\\',
            'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Field\\src\\Event',
          ),
        ),
        'status' => true,
        'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Field',
      ),
      'FrontendTheme' => 
      array (
        'name' => 'FrontendTheme',
        'human_name' => 'Frontend',
        'package' => 'quickapps-plugins',
        'isTheme' => true,
        'isCore' => true,
        'hasHelp' => false,
        'hasSettings' => false,
        'eventListeners' => 
        array (
        ),
        'status' => true,
        'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\FrontendTheme',
      ),
      'Installer' => 
      array (
        'name' => 'Installer',
        'human_name' => 'Installer',
        'package' => 'quickapps-plugins',
        'isTheme' => false,
        'isCore' => true,
        'hasHelp' => false,
        'hasSettings' => false,
        'eventListeners' => 
        array (
        ),
        'status' => true,
        'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Installer',
      ),
      'Locale' => 
      array (
        'name' => 'Locale',
        'human_name' => 'Locale',
        'package' => 'quickapps-plugins',
        'isTheme' => false,
        'isCore' => true,
        'hasHelp' => true,
        'hasSettings' => false,
        'eventListeners' => 
        array (
        ),
        'status' => true,
        'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Locale',
      ),
      'Menu' => 
      array (
        'name' => 'Menu',
        'human_name' => 'Menu',
        'package' => 'quickapps-plugins',
        'isTheme' => false,
        'isCore' => true,
        'hasHelp' => true,
        'hasSettings' => false,
        'eventListeners' => 
        array (
          'Menu\\Event\\MenuHook' => 
          array (
            'namespace' => 'Menu\\Event\\',
            'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Menu\\src\\Event',
          ),
        ),
        'status' => true,
        'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Menu',
      ),
      'Node' => 
      array (
        'name' => 'Node',
        'human_name' => 'Node',
        'package' => 'quickapps-plugins',
        'isTheme' => false,
        'isCore' => true,
        'hasHelp' => true,
        'hasSettings' => false,
        'eventListeners' => 
        array (
          'Node\\Event\\NodeHook' => 
          array (
            'namespace' => 'Node\\Event\\',
            'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Node\\src\\Event',
          ),
        ),
        'status' => true,
        'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Node',
      ),
      'Search' => 
      array (
        'name' => 'Search',
        'human_name' => 'Search',
        'package' => 'quickapps-plugins',
        'isTheme' => false,
        'isCore' => true,
        'hasHelp' => false,
        'hasSettings' => false,
        'eventListeners' => 
        array (
        ),
        'status' => true,
        'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Search',
      ),
      'System' => 
      array (
        'name' => 'System',
        'human_name' => 'System',
        'package' => 'quickapps-plugins',
        'isTheme' => false,
        'isCore' => true,
        'hasHelp' => true,
        'hasSettings' => false,
        'eventListeners' => 
        array (
          'System\\Event\\SystemHook' => 
          array (
            'namespace' => 'System\\Event\\',
            'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\System\\src\\Event',
          ),
          'System\\Event\\SystemHooktag' => 
          array (
            'namespace' => 'System\\Event\\',
            'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\System\\src\\Event',
          ),
          'System\\Event\\TwitterBootstrapHook' => 
          array (
            'namespace' => 'System\\Event\\',
            'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\System\\src\\Event',
          ),
        ),
        'status' => true,
        'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\System',
      ),
      'Taxonomy' => 
      array (
        'name' => 'Taxonomy',
        'human_name' => 'Taxonomy',
        'package' => 'quickapps-plugins',
        'isTheme' => false,
        'isCore' => true,
        'hasHelp' => true,
        'hasSettings' => false,
        'eventListeners' => 
        array (
          'Taxonomy\\Event\\TaxonomyField' => 
          array (
            'namespace' => 'Taxonomy\\Event\\',
            'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Taxonomy\\src\\Event',
          ),
        ),
        'status' => true,
        'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Taxonomy',
      ),
      'User' => 
      array (
        'name' => 'User',
        'human_name' => 'User',
        'package' => 'quickapps-plugins',
        'isTheme' => false,
        'isCore' => true,
        'hasHelp' => true,
        'hasSettings' => false,
        'eventListeners' => 
        array (
          'User\\Event\\UserHook' => 
          array (
            'namespace' => 'User\\Event\\',
            'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\User\\src\\Event',
          ),
        ),
        'status' => true,
        'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\User',
      ),
      'Wysiwyg' => 
      array (
        'name' => 'Wysiwyg',
        'human_name' => 'Wysiwyg',
        'package' => 'quickapps-plugins',
        'isTheme' => false,
        'isCore' => true,
        'hasHelp' => false,
        'hasSettings' => false,
        'eventListeners' => 
        array (
          'Wysiwyg\\Event\\WysiwygHook' => 
          array (
            'namespace' => 'Wysiwyg\\Event\\',
            'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Wysiwyg\\src\\Event',
          ),
        ),
        'status' => true,
        'path' => 'C:\\xampp\\htdocs\\quickapps2\\vendor\\quickapps\\cms\\plugins\\Wysiwyg',
      ),
    ),
    'options' => 
    array (
    ),
    'languages' => 
    array (
    ),
  ),
);
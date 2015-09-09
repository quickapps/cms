<?php
/**
 * Split each core plugin and theme into separate repos and push the to GitHub. For
 * example, the "CMS" plugin is mapped to "git@github.com:quickapps-plugins/cms.git"
 *
 * ### Usage:
 *
 * ```
 * php splitter.php --main-branch="2.0" --plugins="Block,Bootstrap"
 * ```
 *
 * - If no main branch name is provided "2.0" will be used by default.
 * - If no plugin names are given, all of them will be splitted.
 */

/**
 * Main branch name.
 *
 * @var string
 */
$options = getopt('', ['main-branch::', 'plugins::']);
if (empty($options['main-branch'])) {
    die("No main branch name given, you must provide one using the '--main-branch' option. For example: --main-branch=\"2.0\".\n");
}
$mainBranch = $options['main-branch'];

/**
 * List of core plugins located in the "plugins" directory.
 *
 * @var array
 */
$plugins = [
    'Block',
    'Bootstrap',
    'Captcha',
    'CMS',
    'Comment',
    'Content',
    'Eav',
    'Field',
    'Installer',
    'Jquery',
    'Locale',
    'MediaManager',
    'Menu',
    'Search',
    'System',
    'Taxonomy',
    'User',
    'Wysiwyg',
    'BackendTheme',
    'FrontendTheme',
];

if (!empty($options['plugins'])) {
    $plugins = array_intersect($plugins, explode(',', $options['plugins']));
}

/**
 * Null device, based on OS.
 *
 * @var string
 */
$null = DIRECTORY_SEPARATOR === '/' ? '/dev/null' : 'NUL';

/**
 * Creates a new branch for every plugin and theme, and push to corresponding GitHub
 * repository. Such branches are removed after pushed.
 */
foreach ($plugins as $plugin) {
    $pluginDirectory = dirname(__FILE__) . "/plugins/{$plugin}";
    $jsonPath = "{$pluginDirectory}/composer.json";

    if (!is_readable($jsonPath)) {
        echo sprintf('Missing file: %s', $jsonPath);
        continue;
    }

    $composer = json_decode(file_get_contents($jsonPath), true);

    if (!isset($composer['name']) || strpos($composer['name'], '/') === false) {
        echo 'Invalid composer.json, missing or invalid "name" key';
        continue;
    }

    list($org, $plg) = explode('/', $composer['name']);

    echo "Processing: {$plugin}\n";
    echo str_repeat('-', strlen("Processing: {$plugin}")) . "\n\n";

    exec("git checkout {$mainBranch} > {$null}");
    exec("git remote add {$plg} git@github.com:{$org}/{$plg}.git -f 2> {$null}");
    exec("git branch -D {$plg} 2> {$null}");
    exec("git checkout -b {$plg}");
    exec("git filter-branch --prune-empty --subdirectory-filter plugins/{$plugin} -f {$plg}");
    exec("git push {$plg} {$plg}:master --force");
    exec("git checkout {$mainBranch} > {$null}");
    exec("git branch -D {$plg}");
    exec("git remote rm {$plg}");

    echo "\n\n";
}

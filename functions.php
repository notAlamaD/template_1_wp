<?php
if (!defined('FIN_ECONOMY_VERSION')) {
    define('FIN_ECONOMY_VERSION', '1.0.0');
}

require_once get_template_directory() . '/schema.php';

$fin_economy_includes = [
    'inc/helpers.php',
    'inc/setup.php',
    'inc/assets.php',
    'inc/customizer.php',
    'inc/demo-content.php',
];

foreach ($fin_economy_includes as $file) {
    $path = get_template_directory() . '/' . $file;

    if (file_exists($path)) {
        require_once $path;
    }
}

<?php

// use Core\Application;

use Core\Application;

require('autoload.php');
require('config.php');

// Constants
define('ROOT', str_replace('\\', '/', __DIR__));
define('VIEW_PATH', ROOT . '/src/views');
define('SPACER', ' ');


// App
try {
    $app = new Application();
    $app->process();
} catch (\Throwable $e) {
    echo '<div class="alert">' . $e->getMessage() . '</div>';
}

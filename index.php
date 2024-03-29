<?php
session_start();
// header("Content-Type: text/plain");
// use Core\Application;

use Core\Application;

require('autoload.php');
require('config.php');

// Constants
define('ROOT', str_replace('\\', '/', __DIR__));
define('VIEW_PATH', ROOT . '/src/views');
define('SPACER', ' ');


// App
$app = new Application();
$app->process();
// try {
// } catch (\Throwable $e) {
//     echo 'App says:';
//     echo '<div class="alert">' . $e->getMessage() . '</div>';
// }

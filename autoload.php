<?php

function my_autoloader($className)
{
    $filePath = str_replace('App\\', 'src\\', $className);
    $filePath = str_replace('\\', '/', $filePath);
    require($filePath . '.php');
}

spl_autoload_register('my_autoloader');

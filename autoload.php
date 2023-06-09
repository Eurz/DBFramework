<?php

function my_autoloader($className)
{
    $filePath = str_replace('App\\', 'src\\', $className);
    $filePath = str_replace('\\', '/', $filePath);
    $fileName = $filePath . '.php';

    // if (!file_exists($fileName)) {
    //     throw new LogicException("Désolé, ce fichier n'existe pas: $filePath");
    // }


    require($fileName);
}

spl_autoload_register('my_autoloader');

<?php

namespace Core;

class Http
{
    /**
     * @param string $controller - Controller name
     * @param string $task - Method from controller
     * @param array $options - Additionnal url params
     */
    public static function redirect(string $url, string $task = 'index',  array $options = [])
    {

        $locationRoot = 'attributes';

        if ($options) {
            foreach ($options as $key => $value) {
                $locationRoot .= '&' . $key . '=' . $value;
            }
        }

        header('Location: ' . $url);
    }

    public static function notFound()
    {
        header("HTTP/1.1 404 Not Found");
        // self::redirect(Config::getInstance()->get('homepage'));
        die('Page introuvable');
    }
}

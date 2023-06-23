<?php

namespace Core;

class Http
{

    private $header;

    /**
     * @param string $controller - Controller name
     * @param string $task - Method from controller
     * @param array $options - Additionnal url params
     */
    public static function redirect(string $url)
    {
        // header($_SERVER["SERVER_PROTOCOL"] . SPACER . "200 OK");
        // $url = trim($url, '/');
        // var_dump($url);
        header('Location: /' . $url);
        exit();
    }

    public static function notFound()
    {
        header("HTTP/1.1 404 Not Found");
        // self::redirect(Config::getInstance()->get('homepage'));
        // header('Location: /notFound');
        self::redirect('notFound');
    }
}

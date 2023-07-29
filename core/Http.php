<?php

namespace Core;

class Http
{

    private $header;

    /**
     * @param string $url - Controller name
     */
    public static function redirect(string $url)
    {
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

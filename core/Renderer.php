<?php

namespace Core;

class Renderer
{

    public static function render(string $path, array $variables = [])
    {
        extract($variables);
        ob_start();
        require(VIEW_PATH .  '/' . $path . '.html.php');
        $pageContent = ob_get_clean();

        require_once(VIEW_PATH . '/layout.html.php');
    }
}

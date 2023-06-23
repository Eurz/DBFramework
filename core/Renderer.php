<?php

namespace Core;

class Renderer
{
    /**
     * Render a view
     * @param string $path - View path to render
     * @param array $variables - Additionnals parameters to add to an url
     * @return void
     */
    public static function render(string $path, array $variables = []): void
    {
        extract($variables);
        ob_start();
        require(VIEW_PATH .  '/' . $path . '.html.php');
        $pageContent = ob_get_clean();

        $messageManager = new Messages();
        $messages = $messageManager->read();


        require_once(VIEW_PATH . '/layout.html.php');
    }
}

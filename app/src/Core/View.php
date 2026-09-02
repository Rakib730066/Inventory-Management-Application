<?php

namespace App\Core;

class View
{
    public static function render(string $view, array $data = []): void
    {
        // Create variables from data array without using unsafe extract()
        foreach ($data as $__key => $__value) {
            ${$__key} = $__value;
        }
        unset($__key, $__value);

        $basePath = dirname(__DIR__) . '/Views/';
        $viewFile = $basePath . $view . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo 'View not found: ' . htmlspecialchars($view, ENT_QUOTES, 'UTF-8');
            exit;
        }

        $headerFile = $basePath . 'layout/header.php';
        $navFile = $basePath . 'layout/nav.php';
        $footerFile = $basePath . 'layout/footer.php';

        if (file_exists($headerFile)) {
            require $headerFile;
        }

        if (file_exists($navFile)) {
            require $navFile;
        }

        require $viewFile;

        if (file_exists($footerFile)) {
            require $footerFile;
        }
    }

    public static function partial(string $partial, array $data = []): void
    {
        // Create variables from data array without using unsafe extract()
        foreach ($data as $__key => $__value) {
            ${$__key} = $__value;
        }
        unset($__key, $__value);

        $partialFile = dirname(__DIR__) . '/Views/' . $partial . '.php';

        if (file_exists($partialFile)) {
            require $partialFile;
        }
    }
}
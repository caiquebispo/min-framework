<?php
if (!function_exists('dd')) {
    function dd(): void
    {
        echo '<pre>';
        array_map(function ($x) {
            var_dump($x);
        }, func_get_args());
        die;
    }
}
if (!function_exists('view')) {

    function view(string $view, array $data = [])
    {
        extract($data);

        $resourcePath = "../resources/views/{$view}.php";

        if (file_exists($resourcePath)) {

            ob_start();

            return require $resourcePath;

            ob_get_clean();
        } else {

            echo "View '{$view}' não encontrada!";
        }
    }
}

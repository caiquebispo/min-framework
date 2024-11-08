<?php
if(!function_exists('dd')){
    function dd(): void
    {
        echo '<pre>';
        array_map(function($x) {var_dump($x);}, func_get_args());
        die;
    }
}
if(!function_exists('view')){

    function view(string $path, array $params)
    {
        extract($params);
        
        include './resources/views/'.$path.'.php';

    }
}
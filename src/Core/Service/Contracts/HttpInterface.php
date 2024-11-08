<?php

namespace Caiquebispo\Project\Core\Service\Contracts;

interface HttpInterface
{
    public static function get(string $url);
    public static function post(string $url);
    public static function delete(string $url);
    public static function put(string $url);
}

<?php

namespace Caiquebispo\Project\Controllers;

use Caiquebispo\Project\Core\Service\Http;
use Exception;
use PDO;

class HomeController
{
    public function index()
    {

        $payload = Http::put('https://fakestoreapi.com/carts/7')
            ->withData([
                'userId' => 10,
                'date' => '2019-12-10',
                'products' => ['productId' => 1, 'quantity' => 50]

            ])
            ->toJson();

        dd($payload);
        exit();
    }
}

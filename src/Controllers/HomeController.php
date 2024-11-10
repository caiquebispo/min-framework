<?php

namespace Caiquebispo\Project\Controllers;

use Caiquebispo\Project\Core\Service\DB;
use Caiquebispo\Project\Core\Service\Http;

class HomeController
{
    public function index()
    {

        DB::table('GMP0017')
            ->insert([
                ['name' => 'User 1', 'age' => 30],
                ['name' => 'User 2', 'age' => 45],
                ['name' => 'User 3', 'age' => 70],
                ['name' => 'User 4', 'age' => 95],
                ['name' => 'User 5', 'age' => 10],
                ['name' => 'User 6', 'age' => 185],
            ]);

        $userId = DB::table('GMP0017')
            ->insert(['name' => 'Pablo Marçal', 'age' => 150])
            ->id();

        dd('user_id', $userId);
    }
}

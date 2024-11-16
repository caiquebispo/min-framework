<?php

namespace Caiquebispo\Project\Controllers;

use Caiquebispo\Project\Core\Service\DB;

class HomeController
{
    public function index()
    {

        return view('welcome', ['version' => '0.0.1']);
    }
    public function debug(int $id)
    {

        dd(
            DB::table('GMP0017')

                ->get()

        );
    }
}

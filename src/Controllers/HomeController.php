<?php

namespace Caiquebispo\Project\Controllers;

use Caiquebispo\Project\Core\Service\DB;

class HomeController
{
    public function index()
    {


        // $userId = DB::table('GMP0017')
        //     ->insert(['name' => 'Caique Bispo', 'age' => 27])
        //     ->id();

        $result = DB::table('GMP0017')
            ->where('GMP0017_Id', '=', 15)
            ->delete();

        dd($result);
    }
}

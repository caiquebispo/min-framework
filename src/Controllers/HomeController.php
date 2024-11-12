<?php

namespace Caiquebispo\Project\Controllers;

use Caiquebispo\Project\Core\Service\DB;

class HomeController
{
    public function index()
    {


        // $userId = DB::table('GMP0017')
        //     ->insert([
        //         ['name' => 'Caique Bispo',  'age' => 27],
        //         ['name' => 'Tarcio Bispo',  'age' => 27],
        //         ['name' => 'Tiago Bispo',   'age' => 27],
        //         ['name' => 'Rafael Bispo',  'age' => 27],
        //         ['name' => 'Ricardo Bispo', 'age' => 27],
        //         ['name' => 'Edvaldo Bispo', 'age' => 27],
        //         ['name' => 'Sonia Bispo',   'age' => 27],
        //     ]);

        $result = DB::table('GMP0017')
            ->whereNotIn('GMP0017_Id', [23, 24, 25])
            ->get();

        dd($result);
    }
}

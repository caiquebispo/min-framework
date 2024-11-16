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
            DB::table('profiles')
                ->where('profiles.id', '=', 1)
                ->join('modules', 'modules.profile_id', '=', 'profile_id')
                ->get()

        );
    }
}

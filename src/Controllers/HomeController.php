<?php

namespace Caiquebispo\Project\Controllers;

use Caiquebispo\Project\Core\Service\Cache;
use Caiquebispo\Project\Core\Service\DB;
use Caiquebispo\Project\Core\Service\Http;

class HomeController
{
    public function index()
    {
        $cache = new Cache();
        $aux = 'caique';
        
        $user = $cache->remember('user_data', function () {

            return Http::get('https://jsonplaceholder.typicode.com/todos')->toJson();
        }, 1);
        dd($user);
        return view('welcome', ['version' => '0.0.1']);
    }
    public function debug(int $id)
    {

        dd(
            DB::table('profiles')
                ->join('modules', 'modules.profile_id', '=', 'profile_id')
                ->where('profiles.id', '=', 1)
                ->where('profiles.id', '=', 2)
                ->where('profiles.id', '=',)
                ->get()

        );
    }
}

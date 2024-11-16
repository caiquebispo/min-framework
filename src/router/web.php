<?php

use Caiquebispo\Project\Controllers\HomeController;
use Caiquebispo\Project\Core\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/debug/{id}', [HomeController::class, 'debug']);

Route::get('/closure/{test}', function ($data) {

    dd($data);
});

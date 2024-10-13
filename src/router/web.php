<?php

use Caiquebispo\Project\Controllers\HomeController;
use Caiquebispo\Project\Core\Route;

Route::get('/', [HomeController::class, 'index']);


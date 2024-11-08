<?php

//loading autoload
use Caiquebispo\Project\Core\Route;

require '../vendor/autoload.php';

//load helps function
//require '../src/Helpers/functions.php';

//load routers
require  '../src/router/web.php';

Route::dispatch();

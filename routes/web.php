<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MApplicantController;


Route::get('/{any}', function () {
    return view('welcome');
})->where('any' ,'^(?!api).*$'); // Excludes /api/* routes from Angular handling


// Route::get('/', [MApplicantController::class, 'index']);





// Route::get('/', function () {
//     return view('welcome');
// });

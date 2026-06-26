<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('api', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Connecté avec succès au Backend Laravel !',
    ]);
});

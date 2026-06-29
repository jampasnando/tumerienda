<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});
// Route::redirect('/', '/admin');

Route::get('/politica-privacidad', function () {
    return view('politica_privacidad');
});

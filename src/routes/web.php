<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});
// Route::redirect('/', '/admin');

Route::get('/politica-privacidad', function () {
    return view('politica_privacidad');
});
Route::get('/mail-test', function () {

    try {

        Mail::raw('segundo correo en teoria desde brevo.', function ($message) {
            $message->to('jampasnando@gmail.com')
                    ->subject('el subject es correo de tumerienda');
        });

        return response()->json([
            'ok' => true,
            'mensaje' => 'Correo enviado correctamente.'
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'ok' => false,
            'error' => $e->getMessage()
        ],500);

    }

});

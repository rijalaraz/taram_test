<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Mail;

// Route::get('/test-mail', function () {
//     Mail::raw('MailHog fonctionne !', function ($message) {
//         $message->to('toto@gmail.com')
//                 ->subject('Test');
//     });

//     return 'Email envoyé';
// });

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';

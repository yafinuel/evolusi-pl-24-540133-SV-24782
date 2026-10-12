<?php

use Illuminate\Support\Facades\Route;

// Root URL redirects to API tasks or provides API status
Route::get('/', function () {
    return redirect()->route('api.tasks.index');
});


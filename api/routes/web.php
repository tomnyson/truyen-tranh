<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $currentDate = date('Y-m-d H:i:s');
    return response()->json(['message' => 'Server running by tomnyson', 'date' => $currentDate], 200);
});

Route::get('login', function () {
    return response()->json(['message' => 'Server is up'], 200);
});

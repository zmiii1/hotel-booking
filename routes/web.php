// Edit routes/web.php - REPLACE EVERYTHING:
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return '<h1>HOTEL WEBSITE WORKING!</h1><p>Laravel deployed successfully on Railway!</p>';
});

Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API working',
        'database' => 'connected'
    ]);
});
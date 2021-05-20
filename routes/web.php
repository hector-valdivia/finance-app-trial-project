<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['auth']], function(){
    Route::get('/dashboard', [DashboardController::class, 'index']);

    #Entry
    Route::get('/balance', [EntryController::class, 'totalBalance']);
    Route::get('/entry', [EntryController::class, 'find']);
    Route::post('/entry', [EntryController::class, 'store']);
    Route::put('/entry/{entry}', [EntryController::class, 'update']);
    Route::delete('/entry/{entry}', [EntryController::class, 'destroy']);

    Route::post('/entry_csv', [EntryController::class, 'processCSV']);
});

Route::get('/dashboard_example', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

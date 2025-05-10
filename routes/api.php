<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);


Route::post('/register', [AuthController::class, 'register']);

use App\Models\User;

// Route::middleware('auth:api')->get('/etudiants', function (Request $request) {
//     return User::where('role', 'etudiant')->get();
// });
// Route::middleware('auth:api')->get('/etudiants', function (Request $request) {
//     return response()->json(User::where('role', 'etudiant')->get());
// });
Route::middleware('auth:sanctum')->get('/etudiants', function (Request $request) {
    return User::where('role', 'etudiant')->get();
});

Route::middleware('auth:sanctum')->get('/enseignants', function (Request $request) {
    return User::where('role', 'enseignant')->get();
});

use App\Http\Controllers\EtudiantController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/etudiants', [EtudiantController::class, 'index']);
    Route::post('/etudiants', [EtudiantController::class, 'store']);
    Route::put('/etudiants/{id}', [EtudiantController::class, 'update']);
    Route::delete('/etudiants/{id}', [EtudiantController::class, 'destroy']);

});
use App\Http\Controllers\EnseignantController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/enseignants', [EnseignantController::class, 'index']);
    Route::delete('/enseignants/{id}', [EnseignantController::class, 'destroy']);
    Route::put('/enseignants/{id}', [EnseignantController::class, 'update']);
    Route::put('/enseignants/{id}/status', [EnseignantController::class, 'updateStatus']);

});

use App\Http\Controllers\MatiereController;

Route::apiResource('matieres', MatiereController::class);

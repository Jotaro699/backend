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
Route::get('/notes/matiere/{matiere_id}', [NoteController::class, 'getNotesByMatiere']);

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
Route::get('/etudiants/by-user/{userId}', [EtudiantController::class, 'getEtudiantByUser']);

use App\Http\Controllers\EnseignantController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/enseignants', [EnseignantController::class, 'index']);
    Route::delete('/enseignants/{id}', [EnseignantController::class, 'destroy']);
    Route::put('/enseignants/{id}', [EnseignantController::class, 'update']);
    Route::put('/enseignants/{id}/status', [EnseignantController::class, 'updateStatus']);

});

use App\Http\Controllers\MatiereController;
Route::apiResource('matieres', MatiereController::class);

use App\Http\Controllers\CoursController;
Route::apiResource('cours', CoursController::class);

use App\Http\Controllers\NoteController;

// 📌 Récupérer les étudiants liés à une matière (par leur classe)
Route::get('/enseignant/matiere/{matiere_id}/etudiants', [NoteController::class, 'getEtudiants']);

// 📌 Enregistrer une nouvelle note
Route::post('/notes', [NoteController::class, 'store']);

// 📌 (Optionnel) Voir les notes d’un étudiant pour une matière
Route::get('/notes/{etudiant_id}/{matiere_id}', [NoteController::class, 'getNotesByEtudiant']);



// partie notes 
Route::get('/enseignant/matieres/{id}', [EnseignantController::class, 'mesMatieres']);

// ✅ Route qui retourne l'enseignant lié à l'utilisateur connecté
// Route::get('/enseignants/by-user/{userId}', function ($userId) {
//     return \App\Models\Enseignant::where('user_id', $userId)->first();
// });
Route::get('/enseignants/by-user/{userId}', function ($userId) {
    return \App\Models\Enseignant::with('user')->where('user_id', $userId)->first();
});

Route::post('/notes/store-or-update', [NoteController::class, 'storeOrUpdate']);
Route::get('/notes/matiere/{matiere_id}', [NoteController::class, 'getNotesByMatiere']);

// enseignat 
Route::get('/etudiants/by-classe/{classe}', [EtudiantController::class, 'getByClasse']);

//etudiant 
Route::get('/etudiant/notes/{etudiantId}', [NoteController::class, 'getNotesByEtudiantSimple']);


//parent 
Route::middleware('auth:api')->get('/parent/enfants', [AuthController::class, 'getEnfants']);


// profile 
use App\Models\ParentModel;

Route::get('/parents/by-user/{userId}', function ($userId) {
    return ParentModel::with('user')->where('user_id', $userId)->first();
});

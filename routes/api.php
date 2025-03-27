<?php

use App\Http\Controllers\EditionController;
use App\Http\Controllers\JuryController;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\TeamController;
use App\Http\Middleware\JwtMiddleware;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('register', [JWTAuthController::class, 'register']);
Route::post('login', [JWTAuthController::class, 'login']);
// Route::post('jury/login', [AuthController::class, 'juryLogin']); //a faire

Route::get('editions', [EditionController::class, 'index']);
Route::get('editions/{id}', [EditionController::class, 'show']);
Route::get('editions/current', [EditionController::class, 'getCurrentEdition']);
Route::get('statistiques/global', [StatistiqueController::class, 'getGlobalStats']);


Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('user', [JWTAuthController::class, 'getUser']);
    Route::post('logout', [JWTAuthController::class, 'logout']);

    // edition
    Route::post('editions', [EditionController::class, 'store']);
    Route::put('editions/{id}', [EditionController::class, 'update']);
    Route::delete('editions/{id}', [EditionController::class, 'destroy']);
    Route::get('editions/{id}/teams', [EditionController::class, 'getEditionTeams']);


    // equipe
    Route::apiResource('equipes', TeamController::class);
    Route::get('equipes/{id}/participants', [TeamController::class, 'getTeamParticipants']);
    Route::get('equipes/{id}/projet', [TeamController::class, 'getTeamProject']);

    // Jurys
    Route::apiResource('jurys', JuryController::class);
    Route::post('jurys/assign-project', [JuryController::class, 'assignProject']);
    Route::get('jurys/{id}/projects', [JuryController::class, 'getJuryProjects']);
    
});

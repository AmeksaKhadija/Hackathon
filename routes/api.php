<?php

use App\Http\Controllers\EditionController;
use App\Http\Controllers\JuryController;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\MemberJuryController;
use App\Http\Controllers\OrganisateurController;
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

// Public Routes
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
    
    // Member Jurys
    Route::apiResource('member-jurys', MemberJuryController::class);
    Route::get('member-jurys/jury/{juryId}', [MemberJuryController::class, 'getMembersByJury']);
    
    // Organisateurs
    Route::apiResource('organisateurs', OrganisateurController::class);
    Route::get('organisateurs/edition/{editionId}', [OrganisateurController::class, 'getOrganisateursByEdition']);
    
});


// // Protected Routes
// Route::middleware('jwt.auth')->group(function () {
//     // Auth
//     Route::post('logout', [UserController::class, 'logout']);
//     // Users
//     Route::get('users', [UserController::class, 'index']);
//     Route::get('users/{id}', [UserController::class, 'show']);
//     Route::put('users/{id}', [UserController::class, 'updateUser']);
//     Route::delete('users/{id}', [UserController::class, 'destroy']);
//     Route::get('users/role/{roleName}', [UserController::class, 'getUsersByRole']);
    
//     // Roles
//     Route::apiResource('roles', RoleController::class);
//     Route::post('roles/assign', [RoleController::class, 'assignRoleToUser']);
//     Route::post('roles/remove', [RoleController::class, 'removeRoleFromUser']);
//     Route::get('roles/{id}/users', [RoleController::class, 'getUsersByRole']);
    
//     // Editions
//     Route::post('editions', [EditionController::class, 'store']);
//     Route::put('editions/{id}', [EditionController::class, 'update']);
//     Route::delete('editions/{id}', [EditionController::class, 'destroy']);
//     Route::get('editions/{id}/teams', [EditionController::class, 'getEditionTeams']);
    
//     // Equipes
//     Route::apiResource('equipes', EquipeController::class);
//     Route::get('equipes/{id}/participants', [EquipeController::class, 'getTeamParticipants']);
//     Route::get('equipes/{id}/projet', [EquipeController::class, 'getTeamProject']);
    
//     // Participants
//     Route::apiResource('participants', ParticipantController::class);
//     Route::get('participants/user/{userId}', [ParticipantController::class, 'getParticipantByUser']);
//     Route::put('participants/{id}/make-leader', [ParticipantController::class, 'makeLeader']);
    
//     // Projets
//     Route::apiResource('projets', ProjetController::class);
//     Route::put('projets/{id}/rate', [ProjetController::class, 'rateProject']);
//     Route::get('projets/edition/{editionId}', [ProjetController::class, 'getProjectsByEdition']);
//     Route::get('projets/top/{limit?}', [ProjetController::class, 'getTopProjects']);
    


//     // Messages
//     Route::apiResource('messages', MessageController::class);
//     Route::get('messages/equipe/{equipeId}', [MessageController::class, 'getMessagesByEquipe']);
    
//     // Notifications
//     Route::apiResource('notifications', NotificationController::class);
//     Route::put('notifications/{id}/mark-read', [NotificationController::class, 'markAsRead']);
//     Route::get('notifications/participant/{participantId}', [NotificationController::class, 'getNotificationsByParticipant']);
//     Route::get('notifications/participant/{participantId}/unread', [NotificationController::class, 'getUnreadNotificationsByParticipant']);
    
//     // Organisateurs
//     Route::apiResource('organisateurs', OrganisateurController::class);
//     Route::get('organisateurs/edition/{editionId}', [OrganisateurController::class, 'getOrganisateursByEdition']);
//     Route::get('organisateurs/user/{userId}', [OrganisateurController::class, 'getEditionsByUser']);
    
//     // Statistiques
//     Route::apiResource('statistiques', StatistiqueController::class);
//     Route::get('statistiques/edition/{editionId}', [StatistiqueController::class, 'getStatsByEdition']);
//     Route::put('statistiques/edition/{editionId}/recalculate', [StatistiqueController::class, 'recalculateStats']);
// });


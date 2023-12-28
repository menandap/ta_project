<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/latest_build_info/{jobs_id}/{project_id}',  [UserAuthController::class, 'get_latest_jobs']);
Route::get('/get_build_jobs/{jobs_id}/{project_id}/{build_id}',  [UserAuthController::class, 'get_build_jobs']);
Route::get('/get_build_console/{jobs_id}/{project_id}',  [UserAuthController::class, 'get_build_console']);
Route::post('/build/jobs_jenkins/{jobs_id}/{project_id}', [UserAuthController::class, 'jobs_jenkins']);
Route::post('/build/pipeline/{project_id}/{job_1}/{job_2}/{job_3}', [UserAuthController::class, 'pipeline']);

// Route::post('/build/pullrepo', [UserAuthController::class, 'build_pull_repo']);
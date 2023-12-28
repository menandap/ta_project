<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserDashboardController;

// AUTH
Route::view('/login', 'auth.login')->name('login');
Route::post('/actLogin', [UserAuthController::class, 'actLogin'])->name('actLogin');
Route::view('/register', 'auth.register')->name('register');
Route::post('/actRegister', [UserAuthController::class, 'actRegister']);
Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');

Route::get('/project/{id}/status-check', [UserAuthController::class, 'checkStatus'])
    ->name('checkStatus')
    ->withoutMiddleware(['auth']);

// DASHBOARD
Route::group(['middleware' => 'auth'], function () {
    Route::get('/mydashboard', [UserDashboardController::class, 'mydashboard'])->name('mydashboard');

    Route::get('/check_job_status/{{jobss_id}}',  [UserDashboardController::class, 'checkStatus']);

    Route::get('/latest_build_info/{jobs_id}/{project_id}',  [UserDashboardController::class, 'get_latest_jobs']);
    Route::get('/get_build_jobs/{jobs_id}/{project_id}/{build_id}',  [UserDashboardController::class, 'get_build_jobs']);
    Route::get('/get_build_console/{jobs_id}/{project_id}',  [UserDashboardController::class, 'get_build_console']);
    Route::post('/build/jobs_jenkins/{jobs_id}/{project_id}', [UserDashboardController::class, 'jobs_jenkins']);

    Route::post('/build/make_project_template/{id}', [UserDashboardController::class, 'make_project_template']);
    Route::post('/build/pull_project_template/{id}', [UserDashboardController::class, 'pull_project_template']);
    Route::post('/build/build_image_docker/{id}', [UserDashboardController::class, 'build_image_docker']);
    Route::post('/build/deploy_image_docker/{id}', [UserDashboardController::class, 'deploy_image_docker']);
    Route::post('/build/deploy_image_kubernetes/{id}', [UserDashboardController::class, 'deploy_image_kubernetes']);
    
    Route::get('/docker', [UserDashboardController::class, 'docker']);
    Route::get('/docker/{id}/show', [UserDashboardController::class, 'docker_show'])->name('dockerdetail');
    Route::get('/docker/create', [UserDashboardController::class, 'docker_create']);
    Route::post('/docker/store', [UserDashboardController::class, 'docker_store']);
    Route::get('/docker/{id}/edit', [UserDashboardController::class, 'docker_edit']);
    Route::post('/docker/{id}/update', [UserDashboardController::class, 'docker_update']);
    Route::get('/docker/{id}/delete', [UserDashboardController::class, 'docker_delete']);

    Route::get('/jenkins', [UserDashboardController::class, 'jenkins']);
    Route::get('/jenkins/{id}/show', [UserDashboardController::class, 'jenkins_show'])->name('jenkinsdetail');
    Route::get('/jenkins/create', [UserDashboardController::class, 'jenkins_create']);
    Route::post('/jenkins/store', [UserDashboardController::class, 'jenkins_store']);
    Route::get('/jenkins/{id}/edit', [UserDashboardController::class, 'jenkins_edit']);
    Route::post('/jenkins/{id}/update', [UserDashboardController::class, 'jenkins_update']);
    Route::get('/jenkins/{id}/delete', [UserDashboardController::class, 'jenkins_delete']);

    Route::get('/server', [UserDashboardController::class, 'server']);
    Route::get('/server/{id}/show', [UserDashboardController::class, 'server_show'])->name('serverdetail');
    Route::get('/server/create', [UserDashboardController::class, 'server_create']);
    Route::post('/server/store', [UserDashboardController::class, 'server_store']);
    Route::get('/server/{id}/edit', [UserDashboardController::class, 'server_edit']);
    Route::post('/server/{id}/update', [UserDashboardController::class, 'server_update']);
    Route::get('/server/{id}/delete', [UserDashboardController::class, 'server_delete']);

    Route::get('/template', [UserDashboardController::class, 'template']);
    Route::get('/template/{id}/show', [UserDashboardController::class, 'template_show'])->name('templatedetail');
    Route::get('/template/create', [UserDashboardController::class, 'template_create']);
    Route::post('/template/store', [UserDashboardController::class, 'template_store']);
    Route::get('/template/{id}/edit', [UserDashboardController::class, 'template_edit']);
    Route::post('/template/{id}/update', [UserDashboardController::class, 'template_update']);
    Route::get('/template/{id}/delete', [UserDashboardController::class, 'template_delete']);

    Route::get('/masterjobs', [UserDashboardController::class, 'masterjobs']);
    Route::get('/masterjobs/{id}/show', [UserDashboardController::class, 'masterjobs_show'])->name('masterjobsdetail');
    Route::get('/masterjobs/create', [UserDashboardController::class, 'masterjobs_create']);
    Route::post('/masterjobs/store', [UserDashboardController::class, 'masterjobs_store']);
    Route::get('/masterjobs/{id}/edit', [UserDashboardController::class, 'masterjobs_edit']);
    Route::post('/masterjobs/{id}/update', [UserDashboardController::class, 'masterjobs_update']);
    Route::get('/masterjobs/{id}/delete', [UserDashboardController::class, 'masterjobs_delete']);

    Route::get('/project', [UserDashboardController::class, 'project']);
    Route::get('/project/{id}/show', [UserDashboardController::class, 'project_show'])->name('projectdetail');
    Route::get('/project/create/{type}', [UserDashboardController::class, 'project_create']);
    Route::post('/project/store', [UserDashboardController::class, 'project_store']);
    Route::get('/project/{id}/edit', [UserDashboardController::class, 'project_edit']);
    Route::post('/project/{id}/update', [UserDashboardController::class, 'project_update']);
    Route::get('/project/{id}/delete', [UserDashboardController::class, 'project_delete']);

    Route::get('/jobs', [UserDashboardController::class, 'jobs']);
    Route::get('/jobs/{id}/show', [UserDashboardController::class, 'jobs_show'])->name('jobsdetail');
    Route::get('/jobs/create', [UserDashboardController::class, 'jobs_create']);
    Route::post('/jobs/store', [UserDashboardController::class, 'jobs_store']);
    Route::get('/jobs/{id}/edit', [UserDashboardController::class, 'jobs_edit']);
    Route::post('/jobs/{id}/update', [UserDashboardController::class, 'jobs_update']);
    Route::get('/jobs/{id}/delete', [UserDashboardController::class, 'jobs_delete']);
});

// Route::get('/', function () {
//     return view('welcome');
// });
Route::view('/', 'auth.login');


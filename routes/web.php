<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Games routes
|--------------------------------------------------------------------------
*/

Route::get('/', function (Request $request) {
    return redirect('check-auth');
});

Route::get('/check-auth', function (Request $request) {
    return view('check-auth');
});

Route::get('/login', function (Request $request) {
    return view('login');
});

Route::get('/logout', function (Request $request) {
    return view('logout');
});

Route::get('/registration', function (Request $request) {
    return view('registration');
});


/**
 *
 */
Route::get('/my/{vue_capture?}', function (Request $request) {
    return view('contractor.profile');
})->where('vue_capture', '[\/\w\.-]*');


/**
 *
 */
Route::get('/my-company', function (Request $request) {
    return view('my-company');
});
Route::get('/my-company-profile', function (Request $request) {
    return view('my-company-profile');
});
Route::get('/my-company-payouts', function (Request $request) {
    return view('my-company-payouts');
});
Route::get('/employees', function (Request $request) {
    return view('company.employees');
});
Route::get('/employees/{employee_id}', function (Request $request) {
    return view('company.profile-employee', ['employee_id' => $request->employee_id]);
});

Route::get('/my-company-permissions', function (Request $request) {
    return view('my-company-permissions');
});


Route::get('/contractor/npd-attach', function () {
    return view('npd-attach');
});

Route::get('/contractor/tasks/new', function () {
    return view('contractor-tasks-new');
});

Route::get('/contractor/task/{task_id}', function ($task_id) {
    return view('contractor-tasks-new', ['task_id' => $task_id]);
});

Route::get('/contractor/tasks/my', function () {
    return view('contractor-tasks-my');
});
Route::get('/contractor/tasks/my/{task_id}', function ($task_id) {
    return view('contractor-tasks-my', ['task_id' => $task_id]);
});




/**
 * Уведомления
 */
Route::get('/notifications', function (Request $request) {
    return view('notifications');
});



/**
 * Страница проектов
 * (редирект на нужную страницу в зависимости от типа пользователя)
 */
Route::get('/projects', function (Request $request) {
    return view('projects');
});


/**
 * Проекты клиента
 */
Route::get('/client/projects', function (Request $request) {
    return view('client-projects');
});


/**
 * Все исполнители
 */
Route::get('/client/contractors/all', function (Request $request) {
    return view('contractors');
});


Route::get('/contractor/payouts', function () {
    return view('contractor-payouts');
});

Route::get('/contractor/payouts', function (Request $request) {
    $payout_id = $request->payout_id;
    return view('contractor-payouts', ['payout_id' => $payout_id]);
});

/**
 * Профиль исполнителя
 */
Route::get('/contractor/{user_id}', function ($user_id) {
    return view('contractor', ['user_id' => $user_id]);
});





/**
 * Проекты исполнителя
 */
//Route::get('/contractor/projects', function(Request $request){
//	return view('contractor-projects');
//});



Route::get('/project/{project_id}/contractors', function ($project_id) {
    return view('contractors', ['project_id' => $project_id]);
});

Route::get('/project/{project_id}/tasks', function ($project_id) {
    return view('tasks', ['project_id' => $project_id]);
});
Route::get('/project/{project_id}/tasks/{task_id}', function ($project_id, $task_id) {
    return view('tasks', ['project_id' => $project_id, 'task_id' => $task_id]);
});

Route::get('/documents', function () {
    return view('contractor-documents');
});
Route::get('/project/{project_id}/documents', function ($project_id) {
    return view('documents', ['project_id' => $project_id]);
});
Route::get('/company/documents', function () {
    return view('documents');
});





/**
 * Профиль компании
 */
Route::get('/company/{company_id}', function ($company_id) {
    return view('company', ['company_id' => $company_id]);
});


Route::get('/selfemployed/profile', function (Request $request) {
    return view('profile-selfemployed');
});


Route::get('/project/{project_id}/payouts', function ($project_id) {
    return view('payouts', ['project_id' => $project_id]);
});

Route::get('/client/payouts', function () {
    return view('company.all-payouts');
});


Route::get('/knowledge-base', function () {
    return view('knowledge-base');
});

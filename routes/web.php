<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    return view('auth.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/tes', function () {
        return view('layouts.main');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    Route::resource('projects', ProjectController::class);
    Route::get('/showProjects', [ProjectController::class, 'perLeader'])->name('showProjects');
    Route::post('/project/{projectId}/remove-member', [ProjectController::class, 'removeMember']);
    Route::get('/showProjectsCommon', [ProjectController::class, 'other'])->name('showProjectsCommon');

    Route::resource('tasks', TaskController::class);
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    // Route::post('/tasks/{task}/comments', [TaskController::class, 'addComment'])->name('tasks.comments.add');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::get('/comments/{id}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::post('/tasks/{task}/mark-completed', [TaskController::class, 'markCompleted'])->name('tasks.markCompleted');
    Route::post('/tasks/{task}/add-time', [TaskController::class, 'addTime'])->name('tasks.addTime');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.delete');
    Route::post('/tasks/{task}/mark-working', [TaskController::class, 'markWorking'])->name('tasks.markWorking');




    // Route::get('/projects/{id}', [ProjectController::class, 'projectDetails'])->name('projects.details');
    // Route::get('/tasks/{id}', [TaskController::class, 'taskDetails'])->name('tasks.details');
});



Route::middleware([AdminMiddleware::class])->group(function () {


    // manage user
    Route::get('/showuser', [AdminController::class, 'showUser'])->name('showuser');
    Route::get('/addnewuser', [AdminController::class, 'showAddnewUserForm'])->name('addnewuser');
    Route::post('/addnewuser', [AdminController::class, 'addNewUser'])->name('addnewuser.post');
    Route::delete('/deleteuser/{id}', [AdminController::class, 'destroyUser'])->name('admin.delete');
    Route::put('/updateuser/{id}', [AdminController::class, 'updateUser'])->name('updateuser');

    // manage Project
    // Route::resource('projects', ProjectController::class)->except(['create','show','edit']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/data', [ProjectController::class, 'index']);

<?php

use App\Http\Controllers\AboutUs;
use App\Http\Controllers\Auth\Socials\GitHubAuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Teams\TeamsController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Employees\Admin\ManageEmployeesController;

Auth::routes();
Route::permanentRedirect('/', 'login');

Route::get('login/github', [GitHubAuthController::class, 'redirectToProvider'])->name('login.github');
Route::get('auth/github', [GitHubAuthController::class, 'handleProviderCallback']);

Route::group(['middleware' => 'auth'], function (){
    Route::get('teams/dashboard', [TeamsController::class,'index'])->withoutMiddleware('auth')->name('teams.index');
    Route::get('teams/{team}',[TeamsController::class,'show'])->withoutMiddleware('auth')->name('teams.show');
    Route::resource('teams', TeamsController::class)->except('index','show');
});

Route::get('about',AboutUs::class)->name('about_us');

Route::get('email/verify', '\App\Http\Controllers\Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', '\App\Http\Controllers\Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', '\App\Http\Controllers\Auth\VerificationController@resend')->name('verification.resend');

Route::group(['middleware' => ['auth', 'termsAccepted', 'role:admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::resource('users', UserController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class);

    Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::put('/{notification}', [NotificationController::class, 'update'])->name('update');
        Route::delete('/destroy', [NotificationController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'media', 'as' => 'media.'], function () {
        Route::post('{model}/{id}/upload', [MediaController::class, 'store'])->name('upload');
        Route::get('{mediaItem}/download', [MediaController::class, 'download'])->name('download');
        Route::delete('{model}/{id}/{mediaItem}/delete', [MediaController::class, 'destroy'])->name('delete');
    });

    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');

    Route::get('employees/{employee}/interactions', [InteractionController::class,'index'])->name('interactions.index');
    Route::post('interactions', [InteractionController::class,'store'])->name('interactions.store');

    Route::resource('employees', ManageEmployeesController::class);
    Route::get('get-employees-list', [ManageEmployeesController::class,'getData'])->name('employees.getData');

    Route::get('token', function () {
        return auth()->user()->createToken('crm')->plainTextToken;
    });


});

Route::get('userSearch',[UserController::class, 'search'])->name('user.search');

Route::get('terms', [TermsController::class, 'index'])->middleware('auth')->name('terms.index');
Route::post('terms', [TermsController::class, 'store'])->middleware('auth')->name('terms.store');

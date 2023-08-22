<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkedInOAuthController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\RedditOAuthController;
use App\Http\Controllers\TumblrOAuthController;
use App\Http\Controllers\CrudHorarioController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/linkedin/redirect', [LinkedInOAuthController::class, 'redirectToLinkedIn'])->name('redirectlinkedin');
Route::get('/auth/linkedin', [LinkedInOAuthController::class, 'redirectToLinkedIn'])->name('linkedin.redirect');
Route::get('/auth/linkedin/callback', [LinkedInOAuthController::class, 'handleLinkedInCallback'])->name('linkedin.callback');
Route::get('/queue', [QueueController::class, 'index'])->name('queue');
Route::post('/queue/add', [QueueController::class, 'addToQueue'])->name('addToQueue');
Route::post('linkedin/post', [LinkedInOAuthController::class, 'sendLinkedInMessage'])->name('linkedin.post');
Route::get('linkedin/post', [LinkedInOAuthController::class, 'sendLinkedInMessage'])->name('posteo');
Route::get('publicacionesLinkedin', [LinkedInOAuthController::class, 'publicacionesLinkedin'])->name('publicacionesLinkedin');

Route::get('crudHorario', [CrudHorarioController::class, 'index'])->name('index'); 
Route::post('store/horarios', [CrudHorarioController::class, 'store'])->name('storage-horario'); 
Route::delete('/horarios/{id}', [CrudHorarioController::class, 'destroy'])->name('horarios.destroy');
Route::get('/horarios/{horario}/editar', [CrudHorarioController::class,'edit'])->name('horarios.edit');
Route::put('/horarios/{horario}', [CrudHorarioController::class,'update'])->name('horarios.update');



Route::middleware(['2fa'])->group(function () {
   
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/2fa', function () {
        return redirect(route('home'));
    })->name('2fa');
});
  
Route::get('/complete-registration', [RegisterController::class, 'completeRegistration'])->name('complete.registration');

Route::get('/reddit/redirect', [RedditOAuthController::class, 'redirectToReddit'])->name('reddit.redirect');
Route::get('/auth/reddit', [RedditOAuthController::class, 'redirectToReddit'])->name('reddit.auth');
Route::get('/auth/reddit/callback', [RedditOAuthController::class, 'handleRedditCallback'])->name('reddit.callback');
Route::post('/reddit/post', [RedditOAuthController::class, 'sendRedditMessage'])->name('reddit.post');
Route::get('/publicacionesReddit', [RedditOAuthController::class, 'publicacionesReddit'])->name('publicacionesReddit');
//Route::get('/reddit/post', [RedditOAuthController::class, 'show'])->name('reddit.post');


Route::get('/tumblr/redirect', [TumblrOAuthController::class, 'redirectToTumblr'])->name('tumblr.redirect');
Route::get('/auth/tumblr', [TumblrOAuthController::class, 'redirectToTumblr'])->name('tumblr.auth');
Route::get('/auth/tumblr/callback', [TumblrOAuthController::class, 'handleTumblrCallback'])->name('tumblr.callback');
Route::post('/tumblr/post', [TumblrOAuthController::class, 'sendTumblrMessage'])->name('tumblr.post');
Route::get('/publicacionesTumblr', [TumblrOAuthController::class, 'publicacionesTumblr'])->name('publicacionesTumblr');
//Route::get('/tumblr/post', [TumblrOAuthController::class, 'show'])->name('tumblr.post');



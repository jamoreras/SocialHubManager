<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CrudController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkedInOAuthController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\CrudHorarioController;
use App\Models\CrudHorario;

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
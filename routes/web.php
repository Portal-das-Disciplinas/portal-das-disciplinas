<?php

use App\Models\Classification;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\ProfessorUserController;
use App\Http\Controllers\Chart\PassRateController;
use App\Http\Controllers\ClassificationController;
use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\InformationController;
use App\Models\Collaborator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Routes auth
Auth::routes([
    'register' => false,
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/', [DisciplineController::class, 'index'])->name('index');
Route::post('/search', [DisciplineController::class, 'search'])->name('search');

Route::get('/discipline/filter', [DisciplineController::class, 'disciplineFilter']);

//--Desativada por enquanto
// route::get('/minhasdisciplinas', [DisciplineController::class, 'mydisciplines'])->name('mydisciplines');

/*Route::get('sobre', function () {

    return view('information'); 
})->name('information'); */
Route::get('sobre', [InformationController::class, 'index'])->name('information');
Route::put('information/{information}',[InformationController::class,'update'])->name('information.update');
Route::post('information/supdate',[InformationController::class,'storeOrUpdate'])->name('information.supdate');

Route::get('colaborar', function () {
    return view('collaborate');
})->name('collaborate');

Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [UsersController::class, 'index'])->name('profile');
    Route::post('/perfil', [UsersController::class, 'update'])->name('updateUser');

    Route::resource('disciplinas', DisciplineController::class)
        ->except(['index', 'show',]);

    Route::resource('professores', ProfessorUserController::class);

    Route::resource('disciplinas.faqs', FaqController::class)
        ->except(['index']);

    Route::resource('classificacoes', ClassificationController::class)
        ->except(['show']);
});

Route::resource('collaborators',CollaboratorController::class);
Route::put('collaborators/update/photo/{photo}',[CollaboratorController::class,'updatePhoto'])->name('collaborators.update.photo');
Route::delete('collaborators/delete/photo/{photo}',[CollaboratorController::class,'deletePhoto'])->name('collaborators.delete.photo');


Route::get('/disciplinas/{id}', [DisciplineController::class, 'show'])
    ->name('disciplinas.show');

<?php

use App\Http\Controllers\ApiSistemasController;
use App\Models\Classification;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\ProfessorUserController;
use App\Http\Controllers\Chart\PassRateController;
use App\Http\Controllers\ClassificationController;
use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\CollaboratorProductionController;
use App\Http\Controllers\DisciplineParticipantController;
use App\Http\Controllers\DisciplinePerformanceDataController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\LinksController;
use App\Http\Controllers\ParticipantLinkController;
use App\Http\Controllers\SchedulingDisciplinePerformanceUpdateController;
use App\Http\Controllers\SemesterPerformanceDataController;
use App\Models\Collaborator;
use App\Models\DisciplinePerformanceData;
use App\Models\Link;
use App\Models\SemesterPerformanceData;
use App\Services\DisciplinePerformanceDataService;
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
Route::get('/discipline/code/name',[DisciplineController::class,'getCodesAndNames'])->name('disciplines.codes_and_names');

//--Desativada por enquanto
// route::get('/minhasdisciplinas', [DisciplineController::class, 'mydisciplines'])->name('mydisciplines');

/*Route::get('sobre', function () {

    return view('information'); 
})->name('information'); */
Route::get('sobre', [InformationController::class, 'index'])->name('information');
Route::put('information/{information}',[InformationController::class,'update'])->name('information.update');
Route::post('information/supdate',[InformationController::class,'storeOrUpdate'])->name('information.supdate');
Route::delete('information/delete/{name}',[InformationController::class,'deleteByName'])->name('information.deleteByName');

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

    Route::put('classificacoes/update/ordem', [ClassificationController::class, 'updateClassificationOrder'])->name('updateOrder');

    Route::resource('configuracoes', ThemeController::class);
});

Route::resource('collaborators',CollaboratorController::class);



Route::get('/disciplinas/{id}', [DisciplineController::class, 'show'])
    ->name('disciplinas.show');

Route::post('participantes_disciplina/store', [DisciplineParticipantController::class,'store'])->name('participants_discipline.store');
Route::post('produtores/videoportal/supdate',[DisciplineParticipantController::class,'storeOrUpdatePortalVideoProducers'])->name('content_producers.store_update');
Route::put('/participantes_disciplina', [DisciplineParticipantController::class,'update'])->name('participants_discipline.update');
Route::delete('participantes_disciplina/{id}', [DisciplineParticipantController::class,'destroy'])->name('participants_discipline.destroy');
Route::resource('links', LinksController::class);
Route::put('/links/update/toggleactive',[LinksController::class, 'toggleActive'])->name('links.active.toggle');
Route::put('/links/supdate/opinion_form_link', [LinksController::class,'updateOpinionFormLink'])->name('links.supdate.opinion_form_link');

Route::post('/collaboratorProductions/store/listjson',[CollaboratorProductionController::class,"storeListJson"])->name('colalborators_productions.store_list_json');
Route::get('/collaborator/productions/show/{idCollaborator}',[CollaboratorProductionController::class,"show"])->name('collaborator_productions.show');
Route::put('/collaborator/productions/update',[CollaboratorProductionController::class,"update"])->name('collaborator_production.update');
Route::delete('collaborator/productions/delete',[CollaboratorProductionController::class,'delete'])->name('collaborator_production.delete');

Route::get('/disciplinas/dados/{codigo}/{ano}/{periodo}',[DisciplineController::class,"getDisciplineData"])->name('disciplinas.dados');
Route::get('/disciplinas/dados/{codigo}/{idTurma}/{ano}/{periodo}',[DisciplineController::class,"getDisciplineData"])->name('disciplinas.dados');
Route::get('/apisistemas/turmas',[ApiSistemasController::class,'getTurmasPorComponente'])->name("apisistemas.turmas");

Route::get('/agendamento_busca_dados',[SchedulingDisciplinePerformanceUpdateController::class,'index'])->name('scheduling.index');
Route::post('/agendamentos_busca_dados/store',[SchedulingDisciplinePerformanceUpdateController::class,'store'])->name('scheduling.store');
Route::delete('/agendamentos_busca_dados/delete',[SchedulingDisciplinePerformanceUpdateController::class,'delete'])->name('scheduling.delete');
Route::get('/agendamentos_busca_dados/executar/{idSchedule}',[SchedulingDisciplinePerformanceUpdateController::class,'runSchedule'])->name('scheduling.execute');

Route::get('api/performance/{disciplineCode}/{year}/{period}', [DisciplinePerformanceDataController::class,'getDisciplinePerformanceData'])->name('performance.get');
Route::get('/performance',[DisciplinePerformanceDataController::class,'index'])->name('performance.index');
Route::get('/performance/list',[DisciplinePerformanceDataController::class,'listData'])->name('performance.list');
Route::get('api/performance/data/interval',[DisciplinePerformanceDataController::class,'getDisciplinePerformanceDataByInterval'])->name('performance.data_interval_json');

Route::delete('/performance/data/delete',[DisciplinePerformanceDataController::class, 'deletePerformanceData'])->name('performance.delete');
Route::delete('/performance/data/code/year/period',[DisciplinePerformanceDataController::class, 'deletePerformanceDataByCodeYearPeriod'])->name('performance.delete_by_code_year_period');

Route::get('/semester/performance/data', [SemesterPerformanceDataController::class,'index'])->name('semester_performance_data');
Route::delete('/semester/performance/data/delete/{id}',[SemesterPerformanceDataController::class,'destroy'])->name('semester_performance_data.destroy');


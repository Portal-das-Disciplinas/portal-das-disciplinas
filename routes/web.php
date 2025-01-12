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
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseLevelController;
use App\Http\Controllers\DisciplineParticipantController;
use App\Http\Controllers\DisciplinePerformanceDataController;
use App\Http\Controllers\EducationLevelController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\InstitutionalUnitController;
use App\Http\Controllers\LinksController;
use App\Http\Controllers\MethodologyController;
use App\Http\Controllers\ParticipantLinkController;
use App\Http\Controllers\PortalAccessInfoController;
use App\Http\Controllers\ProfessorMethodologyController;
use App\Http\Controllers\SchedulingDisciplinePerformanceUpdateController;
use App\Http\Controllers\SemesterPerformanceDataController;
use App\Http\Controllers\SubjectConceptController;
use App\Http\Controllers\SubjectReferenceController;
use App\Http\Controllers\SubjectTopicController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UnitAdminController;
use App\Models\Collaborator;
use App\Models\Discipline;
use App\Models\DisciplinePerformanceData;
use App\Models\InstitutionalUnit;
use App\Models\Link;
use App\Models\ProfessorMethodology;
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
        ->except(['index', 'show']);

    Route::resource('professores', ProfessorUserController::class);

    Route::resource('disciplinas.faqs', FaqController::class)
        ->except(['index']);

    Route::resource('classificacoes', ClassificationController::class)
        ->except(['show']);

    Route::put('classificacoes/update/ordem', [ClassificationController::class, 'updateClassificationOrder'])->name('updateOrder');

    Route::resource('configuracoes', ThemeController::class);

    Route::get('/painel-metodologias',[MethodologyController::class,'painel'])->name('methodology.painel');
});

Route::resource('collaborators',CollaboratorController::class);



Route::get('/disciplinas/{id}', [DisciplineController::class, 'show'])->name('disciplinas.show');


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
Route::get('/disciplinas/dados/turmas/{codigo}', [DisciplineController::class, 'getDisciplineTurmas'])->name('disciplinas.turmas');
Route::get('/disciplinas/turmas/{codigo}/docente', [DisciplineController::class, 'getDisciplineClassTeacher'])->name('disciplinas.turma-docente');
Route::get('/apisistemas/turmas',[ApiSistemasController::class,'getTurmasPorComponente'])->name("apisistemas.turmas");
Route::get('/autocomplete_disciplinas', [DisciplineController::class, 'autocomplete'])->name('autocomplete_disciplinas');
Route::get('/disciplinas/{codigo}/componentes-curriculares', [DisciplineController::class, 'getComponentesCurriculares'])->name('disciplinas.componentes-curriculares');
Route::get('/disciplinas/{codigo}/bibliografia', [DisciplineController::class, 'getReferenciasBibliograficas'])->name('disciplinas.bibliografia');

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
Route::get('/metodologias',[MethodologyController::class,'index'])->name('methodology.index');
Route::get('/metodologias/professor/{professor_id}/{discipline_id}',[ProfessorMethodologyController::class,'listProfessorMethodologies'])->name('discipline_professor_methodologies.get');
Route::put('metodologias/update/{idMethodology}',[MethodologyController::class,'update'])->name('methodology.update');
Route::put('/metodologias/professor/update/{idProfessorMethodology}',[ProfessorMethodologyController::class,'update'])->name('professor_methodology.update');
Route::post('metodologias/store',[MethodologyController::class,'store'])->name('methodology.store');
Route::post('/disciplinas/metodologias/adicionar/',[DisciplineController::class, 'addMethodologiesToDiscipline'])->name('discipline_add_methodologies.store_mult');
Route::delete('/metodologias/delete/{id_methodology}',[MethodologyController::class,'destroy'])->name('methodology.destroyd');
Route::delete('/metodologias/professor/delete/{id_professor_methodology}',[ProfessorMethodologyController::class,'destroy'])->name('professor_methodology.destroy');
Route::delete('/disciplinas/metodologias/remove/{discipline_id}/{professor_methodology_id}',[DisciplineController::class,'removeMethodologyFromDiscipline'])->name('discipline.remove_methodology');
Route::post('/conteudos/temas/salvar',[SubjectTopicController::class,'store'])->name('subject_topic.store');
Route::post('/conteudos/conceitos/salvar',[SubjectConceptController::class,'store'])->name('subject_concept.store');
Route::post('/conteudos/referencias/salvar',[SubjectReferenceController::class,'store'])->name('subject_reference.store');
Route::delete('conteudos/temas/delete/{id}',[SubjectTopicController::class,'destroy'])->name('subject_topic.destroy');
Route::delete('conteudos/conceitos/delete/{id}',[SubjectConceptController::class,'destroy'])->name('subject_concept.destroy');
Route::delete('conteudos/referencias/delete/{id}',[SubjectReferenceController::class,'destroy'])->name('subject_reference.destroy');

Route::get('/discipline/{discipline_id}/topic/{topic_id}/subtopics', [TopicController::class, 'getSubtopicsList']);
Route::post('/topic/store', [TopicController::class, 'store'])->name('topic.store');
Route::put('/topic/{topic_id}/update', [TopicController::class, 'update']);
Route::delete('/discipline/{discipline_id}/topic/{topic_id}/delete', [TopicController::class, 'destroy']);

Route::get('/acessos',[PortalAccessInfoController::class,'index'])->name('portal_access_info.index');

Route::get('/units',[InstitutionalUnitController::class,'index'])->name('institutional_unit.index');
Route::post('/units/store',[InstitutionalUnitController::class, 'store'])->name('institutional_unit.store');
Route::put('/units/{id}',[InstitutionalUnitController::class, 'update'])->name('institutional_units.update');
Route::delete('/units/delete/{id}',[InstitutionalUnitController::class,'destroy'])->name('institutional_unit.destroy');

Route::get('/courses',[CourseController::class,'index'])->name('course.index');
Route::post('courses/store',[CourseController::class,'store'])->name('course.store');

Route::get('/ensino/niveis',[EducationLevelController::class,'index'])->name('education_level.index');
Route::post('ensino/niveis/store',[EducationLevelController::class,'store'])->name('education_level.store');
Route::delete('/ensino/niveis/delete/{id}',[EducationLevelController::class,'destroy'])->name('education_level.destroy');

Route::get('users/unit/admin',[UnitAdminController::class,'index'])->name('unit_admin.index');
Route::post('users/unit/admin/store',[UnitAdminController::class,'store'])->name('unit_admin.store');
Route::delete('users/unit/admin/delete/{id}',[UnitAdminController::class,'destroy'])->name('unit_admin.destroy');

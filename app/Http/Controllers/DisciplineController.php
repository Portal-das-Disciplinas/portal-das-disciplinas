<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use App\Enums\ClassificationID;
use App\Enums\MediaType;
use App\Http\Requests\Discipline\CreateRequest;
use App\Http\Requests\Discipline\StoreRequest;
use App\Http\Requests\Discipline\UpdateRequest;
use App\Models\Classification;
use App\Models\DisciplinePerformanceData;
use App\Models\ClassificationDiscipline;
use App\Services\Urls\GoogleDriveService;
use App\Services\Urls\YoutubeService;
use Illuminate\Http\Request;
use \App\Models\Discipline;
use App\Models\DisciplineParticipant;
use \App\Models\Media;
use \App\Models\Emphasis;
use App\Models\Professor;
use App\Models\Link;
use App\Models\Faq;
use App\Models\ParticipantLink;
use App\Services\APISigaa\APISigaaService;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

/**
 * Controlador responsável por realizar as tarefas relacionadas com as disciplinas.
 */
class DisciplineController extends Controller
{
    const VIEW_PATH = 'disciplines.';

    protected $theme;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        
        $name_discipline = $request->name_discipline ?? null;

        $emphasis = Emphasis::all();
        $classifications = Classification::all();
        $studentsData = DisciplinePerformanceData::all();

        $disciplinesPeriods = collect([]);

        //foreach para juntar todos os anos e periodos numa collection
        foreach($studentsData as $student) {
            $disciplinesPeriods->push("$student->year.$student->period");
        }

        //Collection com todos os anos.periodo disponíveis
        $periodsColection = $disciplinesPeriods->unique();

        // $disciplines = Discipline::query()
        //     ->with([
        //         'professor',
        //         'medias',
        //     ])
        //     ->orderBy('name', 'ASC') 
        //     ->when(isset($name_discipline), function($query) use($name_discipline) {
        //         $query->where("name", "like", $name_discipline."%");
        //     })
        //     ->when(isset($emphasis), function($query) use($emphasis) {
        //         $query->where("name", "like", $emphasis."%");
        //     })
        //     ->get();
        $emphasis = Emphasis::all();
        $disciplines = Discipline::query()->orderBy('name','ASC')->get();
        $opinionLinkForm = Link::where('name','opinionForm')->first();
       
        return view('disciplines.index')
            // ->with('name_discipline', $name_discipline)
            ->with('disciplines', $disciplines->paginate(12))
            ->with('emphasis', $emphasis)
            ->with('theme', $this->theme)
            ->with('showOpinionForm', true)
            ->with('opinionLinkForm',$opinionLinkForm)
            ->with('classifications', $classifications)
            ->with('studentsData', $studentsData)
            ->with('periodsColection', $periodsColection);
    }

    public function disciplineFilter(Request $request)
    {
        // dd($request);
        $emphasis_all = Emphasis::all();
        $disciplines_all = Discipline::all();
        $classifications_all = Classification::all();
        $studentsData = DisciplinePerformanceData::all();

        $disciplinesPeriods = collect([]);

        //foreach para juntar todos os anos e periodos numa collection
        foreach($studentsData as $student) {
            $disciplinesPeriods->push("$student->year.$student->period");
        }

        //Collection com todos os anos.periodo disponíveis
        $periodsColection = $disciplinesPeriods->unique();

        $emphasis_id = $request->input('emphasis');
        $discipline_name = $request->input('name_discipline');

        $arrayClassifications = array($request->input());
        
        $input;
        $arrayValues;
        $arrayValuesRanges;
        $disciplines = collect([]);
        
        // isRangeChosen: variável que checa se os ranges foram enviados
        // 0 = sim / 1 = não 
        $isRangeChosen = 1;

        // pega todos os parametros vindos do 
        // request e salva numa variável
        foreach($arrayClassifications as $arr) {
            $arrayValues = $arr;
            $arrayValuesRanges = $arr;
        }

        // dd($arrayValues);

        // remove todos os parametros que tenham haver com
        // os ranges, que seja a emphase ou o nome da disciplina
        foreach($arrayValues as $key => $value) {
            if (str_contains($key, "range") == true) {
                unset($arrayValues[$key]);
                // array_push($arrayValuesRanges, $key => $value);

                // If para checar se os ranges estão sendo enviados
                // com qualquer valor diferente de menos 1, significando 
                // que os ranges foram enviados
                if ($value != -1) {
                    $isRangeChosen = 0;
                } else {
                    $isRangeChosen = 1;
                }
            } else if (str_contains($key, "page") == true) {
                unset($arrayValues[$key]);
            } else if ($key == "name_discipline") {
                unset($arrayValues[$key]);
            } else if ($key == "emphasis") {
                unset($arrayValues[$key]);
            } else if ($key == "_token") {
                unset($arrayValues[$key]);
            }
        }

        // foreach pra tirar qualquer parâmetro que não tenha range
        // de dentro do arrayValuesRanges
        foreach($arrayValuesRanges as $key => $value) {
            if (str_contains($key, "range") == false) {
                unset($arrayValuesRanges[$key]);
            }
        }

        // Foreach pra remover valores nulos do $arrayValuesRanges 
        foreach ($arrayValuesRanges as $key => $value) {
            if ($value < 1) {
                unset($arrayValuesRanges[$key]);
            }
        }

        // Mecanismo pra saber se mais parâmetros fora o nome da disciplina e a ênfase foram enviados
        if (count($arrayValues) > 0 && $request->input('filtro') === "classificacao") {
            // Mais parâmetros fora o nome, ênfase e os ranges foram enviados
            if ($discipline_name != null && $emphasis_id != null) {
                $disciplines = Discipline::join("classifications_disciplines", "id", "=", "discipline_id")
                ->where("name", "like", "%" . $discipline_name . "%")
                ->where("emphasis_id", $emphasis_id)
                ->get();
                
                $classifications = Classification::all();
                $disciplinesResult = collect([]);
                $finalCollection = collect([]);
                $arrayClassificationValues = array();

                // Fazer um foreach pra pegar o $arrayValues (["Metodologias" => "mais"]) 
                // e trocar o "Metodologias" pelo respectivo id
                foreach ($classifications as $key => $value) {
                    foreach ($arrayValues as $arrKey => $arrValue) {
                        if (str_contains($arrKey, "_") == true) {
                            $charToBeRemoved;

                            for ($i = 0; $i < mb_strlen($arrKey); $i++) {
                                // Capturar qual a posição da string que tem o undeline
                                // que vai ser removido
                                if ($arrKey[$i] === '_') {
                                    $charToBeRemoved = $i;
                                }
                            }

                            $noUnderlineString = str_replace("_", " ", $arrKey);

                            if ($value->name == $noUnderlineString) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        } else {
                            if ($value->name == $arrKey) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        }
                    }
                }

                $fieldsToCheck = count($arrayClassificationValues);
                
                foreach ($disciplines as $disciplineKey => $disciplineValue) {
                    $cont = 0;
                    foreach ($arrayClassificationValues as $key => $value) {
                        $disciplineGroup = ClassificationDiscipline::where("discipline_id", $disciplineValue->discipline_id)
                        ->get();
                        foreach ($disciplineGroup as $keyGroup => $valueGroup) {
                            if ($valueGroup->classification_id == $key) {
                                if ($value == "mais") {
                                    $currentDiscipline = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                    ->where("classification_id", $key)
                                    ->where("value", "<=", 50)
                                    ->get();

                                    if (count($currentDiscipline) > 0) {
                                        $cont++;
                                    }
                                } else {
                                    $currentDiscipline = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                    ->where("classification_id", $key)
                                    ->where("value", ">=", 51)
                                    ->get();

                                    if (count($currentDiscipline) > 0) {
                                        $cont++;
                                    }
                                }
                            }
                        }
                    }

                    if ($fieldsToCheck == $cont) {
                        $filteredDiscipline = Discipline::where("id", $disciplineValue->discipline_id)->get();
                        $disciplinesResult->push($filteredDiscipline);
                    }
                }

                $disciplinesMixed = $disciplinesResult->collapse()->unique()->paginate(12);

                return view('disciplines.index')
                ->with("disciplines", $disciplinesMixed)
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            } else if ($discipline_name == null && $emphasis_id == null) {
                // pesquisa apenas por classificações
                $disciplines = ClassificationDiscipline::all();
                $classifications = Classification::all();
                $arrayClassificationValues = array();
                $disciplinesResult = collect([]);

                // Fazer um foreach pra pegar o $arrayValues (["Metodologias" => "mais"]) 
                // e trocar o "Metodologias" pelo respectivo id
                foreach ($classifications as $key => $value) {
                    foreach ($arrayValues as $arrKey => $arrValue) {
                        if (str_contains($arrKey, "_") == true) {
                            $charToBeRemoved;

                            for ($i = 0; $i < mb_strlen($arrKey); $i++) {
                                // Capturar qual a posição da string que tem o undeline
                                // que vai ser removido
                                if ($arrKey[$i] === '_') {
                                    $charToBeRemoved = $i;
                                }
                            }

                            $noUnderlineString = str_replace("_", " ", $arrKey);

                            if ($value->name == $noUnderlineString) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        } else {
                            if ($value->name == $arrKey) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        }
                    }
                }

                $fieldsToCheck = count($arrayClassificationValues);

                foreach ($disciplines as $disciplineKey => $disciplineValue) {
                    $cont = 0;
                    foreach ($arrayClassificationValues as $key => $value) {
                        $disciplineGroup = ClassificationDiscipline::where("discipline_id", $disciplineValue->discipline_id)
                        ->get();

                        foreach ($disciplineGroup as $keyGroup => $valueGroup) {
                            if ($valueGroup->classification_id == $key) {
                                if ($value == "mais") {
                                    $currentDiscipline = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                    ->where("classification_id", $key)
                                    ->where("value", "<=", 50)
                                    ->get();

                                    if (count($currentDiscipline) > 0) {
                                        $cont++;
                                    }
                                } else {
                                    $currentDiscipline = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                    ->where("classification_id", $key)
                                    ->where("value", ">=", 51)
                                    ->get();

                                    if (count($currentDiscipline) > 0) {
                                        $cont++;
                                    }
                                }
                            }
                        }
                    }

                    if ($fieldsToCheck == $cont) {
                        $filteredDiscipline = Discipline::where("id", $disciplineValue->discipline_id)->get();
                        $disciplinesResult->push($filteredDiscipline);
                    }
                }

                $finalCollection = $disciplinesResult->collapse()->unique()->paginate(12);

                return view('disciplines.index')
                ->with('disciplines', $finalCollection)
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            } else if ($emphasis_id != null) {
                $disciplines = Discipline::join("classifications_disciplines", "id", "=", "discipline_id")
                ->where('emphasis_id', $emphasis_id)
                ->get();
                
                $classifications = Classification::all();
                $disciplinesResult = collect([]);
                $finalCollection = collect([]);
                $arrayClassificationValues = array();

                // Fazer um foreach pra pegar o $arrayValues (["Metodologias" => "mais"]) 
                // e trocar o "Metodologias" pelo respectivo id
                foreach ($classifications as $key => $value) {
                    foreach ($arrayValues as $arrKey => $arrValue) {
                        if (str_contains($arrKey, "_") == true) {
                            $charToBeRemoved;

                            for ($i = 0; $i < mb_strlen($arrKey); $i++) {
                                // Capturar qual a posição da string que tem o undeline
                                // que vai ser removido
                                if ($arrKey[$i] === '_') {
                                    $charToBeRemoved = $i;
                                }
                            }

                            $noUnderlineString = str_replace("_", " ", $arrKey);

                            if ($value->name == $noUnderlineString) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        } else {
                            if ($value->name == $arrKey) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        }
                    }
                }

                $fieldsToCheck = count($arrayClassificationValues);
                
                foreach ($disciplines as $disciplineKey => $disciplineValue) {
                    $cont = 0;
                    foreach ($arrayClassificationValues as $key => $value) {
                        $disciplineGroup = ClassificationDiscipline::where("discipline_id", $disciplineValue->discipline_id)
                        ->get();
                        foreach ($disciplineGroup as $keyGroup => $valueGroup) {
                            if ($valueGroup->classification_id == $key) {
                                if ($value == "mais") {
                                    $currentDiscipline = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                    ->where("classification_id", $key)
                                    ->where("value", "<=", 50)
                                    ->get();

                                    if (count($currentDiscipline) > 0) {
                                        $cont++;
                                    }
                                } else {
                                    $currentDiscipline = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                    ->where("classification_id", $key)
                                    ->where("value", ">=", 51)
                                    ->get();

                                    if (count($currentDiscipline) > 0) {
                                        $cont++;
                                    }
                                }
                            }
                        }
                    }

                    if ($fieldsToCheck == $cont) {
                        $filteredDiscipline = Discipline::where("id", $disciplineValue->discipline_id)->get();
                        $disciplinesResult->push($filteredDiscipline);
                    }
                }

                $finalCollection = $disciplinesResult->collapse()->unique()->paginate(12);

                return view('disciplines.index')
                ->with('disciplines', $finalCollection)
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            } else if ($discipline_name != null) {
                $disciplines = Discipline::join("classifications_disciplines", "id", "=", "discipline_id")
                ->where("name", "like", "%" . $discipline_name . "%")
                ->get();

                $classifications = Classification::all();
                $disciplinesResult = collect([]);
                $finalCollection = collect([]);
                $arrayClassificationValues = array();

                // Fazer um foreach pra pegar o $arrayValues (["Metodologias" => "mais"]) 
                // e trocar o "Metodologias" pelo respectivo id
                foreach ($classifications as $key => $value) {
                    foreach ($arrayValues as $arrKey => $arrValue) {
                        if (str_contains($arrKey, "_") == true) {
                            $charToBeRemoved;

                            for ($i = 0; $i < mb_strlen($arrKey); $i++) {
                                // Capturar qual a posição da string que tem o undeline
                                // que vai ser removido
                                if ($arrKey[$i] === '_') {
                                    $charToBeRemoved = $i;
                                }
                            }

                            $noUnderlineString = str_replace("_", " ", $arrKey);

                            if ($value->name == $noUnderlineString) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        } else {
                            if ($value->name == $arrKey) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        }
                    }
                }

                $fieldsToCheck = count($arrayClassificationValues);
                
                foreach ($disciplines as $disciplineKey => $disciplineValue) {
                    $cont = 0;
                    foreach ($arrayClassificationValues as $key => $value) {
                        $disciplineGroup = ClassificationDiscipline::where("discipline_id", $disciplineValue->discipline_id)
                        ->get();
                        foreach ($disciplineGroup as $keyGroup => $valueGroup) {
                            if ($valueGroup->classification_id == $key) {
                                if ($value == "mais") {
                                    $currentDiscipline = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                    ->where("classification_id", $key)
                                    ->where("value", "<=", 50)
                                    ->get();

                                    if (count($currentDiscipline) > 0) {
                                        $cont++;
                                    }
                                } else {
                                    $currentDiscipline = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                    ->where("classification_id", $key)
                                    ->where("value", ">=", 51)
                                    ->get();

                                    if (count($currentDiscipline) > 0) {
                                        $cont++;
                                    }
                                }
                            }
                        }
                    }

                    if ($fieldsToCheck == $cont) {
                        $filteredDiscipline = Discipline::where("id", $disciplineValue->discipline_id)->get();
                        $disciplinesResult->push($filteredDiscipline);
                    }
                }

                $finalCollection = $disciplinesResult->collapse()->unique()->paginate(12);
                
                return view('disciplines.index')
                ->with('disciplines', $finalCollection)
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            } else {
                return redirect('/')
                ->with('disciplines', $disciplines_all->paginate(12))
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            }
        } else if (count($arrayValues) > 0 && $request->input('filtro') === "aprovacao") {
            if ($discipline_name != null && $emphasis_id != null) {
                $disciplines = Discipline::join("classifications_disciplines", "id", "=", "discipline_id")
                ->where("name", "like", "%" . $discipline_name . "%")
                ->where("emphasis_id", $emphasis_id)
                ->get();
                
                $collection = collect([]);
                $filteredCollection = collect([]);

                // dd($request);
                if ($request->input('porcentagem') !== null && $request->input('periodo') !== null) {
                    // pesquisa por porcentagem e por período
                    if ($request->input('maiorMenor') === "maior") {
                        foreach ($studentsData as $student) {
                            $dis = $student->where("year", substr($request->input('periodo'), 0,4))
                            ->where("period", substr($request->input('periodo'), 5));
                            
                            // if ($student->calculatePercentage() > $request->input('porcentagem')) {
                                $filteredCollection->push($dis);
                            // }
                        }
                    } else {
                        foreach ($studentsData as $student) {
                            $dis = $student->where("year", substr($request->input('periodo'), 0,4))
                            ->where("period", substr($request->input('periodo'), 5));
                            
                            if ($student->calculatePercentage() < $request->input('porcentagem')) {
                                $filteredCollection->push($dis);
                            }
                        }
                    }
                }

                dd($dis->get());
                return view('disciplines.index')
                ->with("disciplines", $disciplinesMixed)
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            } else if ($discipline_name == null && $emphasis_id == null) {
                // pesquisa apenas por classificações
                $disciplines = ClassificationDiscipline::all();
                $classifications = Classification::all();
                $arrayClassificationValues = array();
                $disciplinesResult = collect([]);

                // Fazer um foreach pra pegar o $arrayValues (["Metodologias" => "mais"]) 
                // e trocar o "Metodologias" pelo respectivo id
                foreach ($classifications as $key => $value) {
                    foreach ($arrayValues as $arrKey => $arrValue) {
                        if (str_contains($arrKey, "_") == true) {
                            $charToBeRemoved;

                            for ($i = 0; $i < mb_strlen($arrKey); $i++) {
                                // Capturar qual a posição da string que tem o undeline
                                // que vai ser removido
                                if ($arrKey[$i] === '_') {
                                    $charToBeRemoved = $i;
                                }
                            }

                            $noUnderlineString = str_replace("_", " ", $arrKey);

                            if ($value->name == $noUnderlineString) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        } else {
                            if ($value->name == $arrKey) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        }
                    }
                }

                $fieldsToCheck = count($arrayClassificationValues);

                foreach ($disciplines as $disciplineKey => $disciplineValue) {
                    $cont = 0;
                    foreach ($arrayClassificationValues as $key => $value) {
                        $disciplineGroup = ClassificationDiscipline::where("discipline_id", $disciplineValue->discipline_id)
                        ->get();

                        foreach ($disciplineGroup as $keyGroup => $valueGroup) {
                            if ($valueGroup->classification_id == $key) {
                                if ($value == "mais") {
                                    $currentDiscipline = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                    ->where("classification_id", $key)
                                    ->where("value", "<=", 50)
                                    ->get();

                                    if (count($currentDiscipline) > 0) {
                                        $cont++;
                                    }
                                } else {
                                    $currentDiscipline = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                    ->where("classification_id", $key)
                                    ->where("value", ">=", 51)
                                    ->get();

                                    if (count($currentDiscipline) > 0) {
                                        $cont++;
                                    }
                                }
                            }
                        }
                    }

                    if ($fieldsToCheck == $cont) {
                        $filteredDiscipline = Discipline::where("id", $disciplineValue->discipline_id)->get();
                        $disciplinesResult->push($filteredDiscipline);
                    }
                }

                $finalCollection = $disciplinesResult->collapse()->unique()->paginate(12);

                return view('disciplines.index')
                ->with('disciplines', $finalCollection)
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            } else if ($emphasis_id != null) {
                $disciplines = Discipline::join("classifications_disciplines", "id", "=", "discipline_id")
                ->where('emphasis_id', $emphasis_id)
                ->get();
                
                $classifications = Classification::all();
                $disciplinesResult = collect([]);
                $finalCollection = collect([]);
                $arrayClassificationValues = array();

                // Fazer um foreach pra pegar o $arrayValues (["Metodologias" => "mais"]) 
                // e trocar o "Metodologias" pelo respectivo id
                foreach ($classifications as $key => $value) {
                    foreach ($arrayValues as $arrKey => $arrValue) {
                        if (str_contains($arrKey, "_") == true) {
                            $charToBeRemoved;

                            for ($i = 0; $i < mb_strlen($arrKey); $i++) {
                                // Capturar qual a posição da string que tem o undeline
                                // que vai ser removido
                                if ($arrKey[$i] === '_') {
                                    $charToBeRemoved = $i;
                                }
                            }

                            $noUnderlineString = str_replace("_", " ", $arrKey);

                            if ($value->name == $noUnderlineString) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        } else {
                            if ($value->name == $arrKey) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        }
                    }
                }

                $fieldsToCheck = count($arrayClassificationValues);
                
                foreach ($disciplines as $disciplineKey => $disciplineValue) {
                    $cont = 0;
                    foreach ($arrayClassificationValues as $key => $value) {
                        $disciplineGroup = ClassificationDiscipline::where("discipline_id", $disciplineValue->discipline_id)
                        ->get();
                        foreach ($disciplineGroup as $keyGroup => $valueGroup) {
                            if ($valueGroup->classification_id == $key) {
                                if ($value == "mais") {
                                    $currentDiscipline = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                    ->where("classification_id", $key)
                                    ->where("value", "<=", 50)
                                    ->get();

                                    if (count($currentDiscipline) > 0) {
                                        $cont++;
                                    }
                                } else {
                                    $currentDiscipline = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                    ->where("classification_id", $key)
                                    ->where("value", ">=", 51)
                                    ->get();

                                    if (count($currentDiscipline) > 0) {
                                        $cont++;
                                    }
                                }
                            }
                        }
                    }

                    if ($fieldsToCheck == $cont) {
                        $filteredDiscipline = Discipline::where("id", $disciplineValue->discipline_id)->get();
                        $disciplinesResult->push($filteredDiscipline);
                    }
                }

                $finalCollection = $disciplinesResult->collapse()->unique()->paginate(12);

                return view('disciplines.index')
                ->with('disciplines', $finalCollection)
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            } else if ($discipline_name != null) {
                $disciplines = Discipline::join("classifications_disciplines", "id", "=", "discipline_id")
                ->where("name", "like", "%" . $discipline_name . "%")
                ->get();

                $classifications = Classification::all();
                $disciplinesResult = collect([]);
                $finalCollection = collect([]);
                $arrayClassificationValues = array();

                // Fazer um foreach pra pegar o $arrayValues (["Metodologias" => "mais"]) 
                // e trocar o "Metodologias" pelo respectivo id
                foreach ($classifications as $key => $value) {
                    foreach ($arrayValues as $arrKey => $arrValue) {
                        if (str_contains($arrKey, "_") == true) {
                            $charToBeRemoved;

                            for ($i = 0; $i < mb_strlen($arrKey); $i++) {
                                // Capturar qual a posição da string que tem o undeline
                                // que vai ser removido
                                if ($arrKey[$i] === '_') {
                                    $charToBeRemoved = $i;
                                }
                            }

                            $noUnderlineString = str_replace("_", " ", $arrKey);

                            if ($value->name == $noUnderlineString) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        } else {
                            if ($value->name == $arrKey) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        }
                    }
                }

                $fieldsToCheck = count($arrayClassificationValues);
                
                foreach ($disciplines as $disciplineKey => $disciplineValue) {
                    $cont = 0;
                    foreach ($arrayClassificationValues as $key => $value) {
                        $disciplineGroup = ClassificationDiscipline::where("discipline_id", $disciplineValue->discipline_id)
                        ->get();
                        foreach ($disciplineGroup as $keyGroup => $valueGroup) {
                            if ($valueGroup->classification_id == $key) {
                                if ($value == "mais") {
                                    $currentDiscipline = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                    ->where("classification_id", $key)
                                    ->where("value", "<=", 50)
                                    ->get();

                                    if (count($currentDiscipline) > 0) {
                                        $cont++;
                                    }
                                } else {
                                    $currentDiscipline = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                    ->where("classification_id", $key)
                                    ->where("value", ">=", 51)
                                    ->get();

                                    if (count($currentDiscipline) > 0) {
                                        $cont++;
                                    }
                                }
                            }
                        }
                    }

                    if ($fieldsToCheck == $cont) {
                        $filteredDiscipline = Discipline::where("id", $disciplineValue->discipline_id)->get();
                        $disciplinesResult->push($filteredDiscipline);
                    }
                }

                $finalCollection = $disciplinesResult->collapse()->unique()->paginate(12);
                
                return view('disciplines.index')
                ->with('disciplines', $finalCollection)
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            } else {
                return redirect('/')
                ->with('disciplines', $disciplines_all->paginate(12))
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            }
        } else if ($isRangeChosen == 0) {
            // Ranges foram enviados
            if ($discipline_name != null && $emphasis_id != null) {
                $disciplines = Discipline::join("classifications_disciplines", "id", "=", "discipline_id")
                ->where("name", "like", "%" . $discipline_name . "%")
                ->where("emphasis_id", $emphasis_id)
                ->get(); 
                
                $classifications = Classification::all();
                $disciplinesResult = collect([]);
                $finalCollection = collect([]);
                $arrayClassificationValues = array();
                $arrayCollectionDisciplines = collect([]);

                // Fazer um foreach pra pegar o $arrayValues (["Metodologias" => "mais"]) 
                // e trocar o "Metodologias" pelo respectivo id
                foreach ($classifications as $key => $value) {
                    foreach ($arrayValuesRanges as $arrKey => $arrValue) {
                        if (str_contains($arrKey, "_") == true) {
                            $charToBeRemoved;

                            for ($i = 0; $i < mb_strlen($arrKey); $i++) {
                                // Capturar qual a posição da string que tem o undeline
                                // que vai ser removido
                                if ($arrKey[$i] === '_') {
                                    $charToBeRemoved = $i;
                                }
                            }

                            $noUnderlineString = str_replace("_", " ", $arrKey);

                            if ($value->name == substr($noUnderlineString, 5)) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        } else {
                            if ($value->name == substr($arrKey, 5)) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        }
                    }
                }

                $fieldsToCheck = count($arrayClassificationValues);
                
                foreach ($disciplines as $disciplineKey => $disciplineValue) {
                    $cont = 0;
                    foreach ($arrayClassificationValues as $key => $value) {
                        $disciplineGroup = ClassificationDiscipline::where("discipline_id", $disciplineValue->discipline_id)
                        ->get();
                        
                        foreach ($disciplineGroup as $keyGroup => $valueGroup) {
                            if ($valueGroup->classification_id == $key) {
                                $disciplineFiltered = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                ->where("classification_id", $valueGroup->classification_id)
                                ->where("value", ">=", $value)
                                ->get();

                                if (count($disciplineFiltered) == 1) {
                                    $arrayCollectionDisciplines->push($disciplineFiltered);
                                    $cont++;
                                }
                            }
                        }
                    }

                    
                    if ($fieldsToCheck == $cont) {
                        $result = Discipline::where("id", $disciplineValue->discipline_id)->get();
                        $disciplinesResult->push($result);
                    }
                }

                $disciplinesMixed = $disciplinesResult->collapse()->unique()->paginate(12);

                return view('disciplines.index')
                ->with('disciplines', $disciplinesMixed)
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            } else if ($discipline_name == null && $emphasis_id == null) {
                $disciplines = ClassificationDiscipline::all(); 
                $classifications = Classification::all();
                $disciplinesResult = collect([]);
                $finalCollection = collect([]);
                $arrayClassificationValues = array();
                $arrayCollectionDisciplines = collect([]);

                // Fazer um foreach pra pegar o $arrayValues (["Metodologias" => "mais"]) 
                // e trocar o "Metodologias" pelo respectivo id
                foreach ($classifications as $key => $value) {
                    foreach ($arrayValuesRanges as $arrKey => $arrValue) {
                        if (str_contains($arrKey, "_") == true) {
                            $charToBeRemoved;

                            for ($i = 0; $i < mb_strlen($arrKey); $i++) {
                                // Capturar qual a posição da string que tem o undeline
                                // que vai ser removido
                                if ($arrKey[$i] === '_') {
                                    $charToBeRemoved = $i;
                                }
                            }

                            $noUnderlineString = str_replace("_", " ", $arrKey);

                            if ($value->name == substr($noUnderlineString, 5)) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        } else {
                            if ($value->name == substr($arrKey, 5)) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        }
                    }
                }

                $fieldsToCheck = count($arrayClassificationValues);
                
                foreach ($disciplines as $disciplineKey => $disciplineValue) {
                    $cont = 0;
                    foreach ($arrayClassificationValues as $key => $value) {
                        $disciplineGroup = ClassificationDiscipline::where("discipline_id", $disciplineValue->discipline_id)
                        ->get();
                        
                        foreach ($disciplineGroup as $keyGroup => $valueGroup) {
                            if ($valueGroup->classification_id == $key) {
                                $disciplineFiltered = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                ->where("classification_id", $valueGroup->classification_id)
                                ->where("value", ">=", $value)
                                ->get();

                                if (count($disciplineFiltered) == 1) {
                                    $arrayCollectionDisciplines->push($disciplineFiltered);
                                    $cont++;
                                }
                            }
                        }
                    }

                    
                    if ($fieldsToCheck == $cont) {
                        $result = Discipline::where("id", $disciplineValue->discipline_id)->get();
                        $disciplinesResult->push($result);
                    }
                }
                
                $disciplinesMixed = $disciplinesResult->collapse()->unique()->paginate(12);

                return view('disciplines.index')
                ->with('disciplines', $disciplinesMixed)
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            } else if ($emphasis_id != null) {
                $disciplines = Discipline::join("classifications_disciplines", "id", "=", "discipline_id")
                ->where('emphasis_id', $emphasis_id)
                ->get();

                $classifications = Classification::all();
                $disciplinesResult = collect([]);
                $finalCollection = collect([]);
                $arrayClassificationValues = array();
                $arrayCollectionDisciplines = collect([]);

                // Fazer um foreach pra pegar o $arrayValues (["Metodologias" => "mais"]) 
                // e trocar o "Metodologias" pelo respectivo id
                foreach ($classifications as $key => $value) {
                    foreach ($arrayValuesRanges as $arrKey => $arrValue) {
                        if (str_contains($arrKey, "_") == true) {
                            $charToBeRemoved;

                            for ($i = 0; $i < mb_strlen($arrKey); $i++) {
                                // Capturar qual a posição da string que tem o undeline
                                // que vai ser removido
                                if ($arrKey[$i] === '_') {
                                    $charToBeRemoved = $i;
                                }
                            }

                            $noUnderlineString = str_replace("_", " ", $arrKey);

                            if ($value->name == substr($noUnderlineString, 5)) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        } else {
                            if ($value->name == substr($arrKey, 5)) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        }
                    }
                }

                $fieldsToCheck = count($arrayClassificationValues);
                
                foreach ($disciplines as $disciplineKey => $disciplineValue) {
                    $cont = 0;
                    foreach ($arrayClassificationValues as $key => $value) {
                        $disciplineGroup = ClassificationDiscipline::where("discipline_id", $disciplineValue->discipline_id)
                        ->get();
                        
                        foreach ($disciplineGroup as $keyGroup => $valueGroup) {
                            if ($valueGroup->classification_id == $key) {
                                $disciplineFiltered = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                ->where("classification_id", $valueGroup->classification_id)
                                ->where("value", ">=", $value)
                                ->get();

                                if (count($disciplineFiltered) == 1) {
                                    $arrayCollectionDisciplines->push($disciplineFiltered);
                                    $cont++;
                                }
                            }
                        }
                    }

                    
                    if ($fieldsToCheck == $cont) {
                        $result = Discipline::where("id", $disciplineValue->discipline_id)->get();
                        $disciplinesResult->push($result);
                    }
                }

                $disciplinesMixed = $disciplinesResult->collapse()->unique()->paginate(12);

                return view('disciplines.index')
                ->with('disciplines', $disciplinesMixed)
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            } else if ($discipline_name != null) {
                $disciplines = Discipline::join("classifications_disciplines", "id", "=", "discipline_id")
                ->where("name", "like", "%" . $discipline_name . "%")
                ->get();

                $classifications = Classification::all();
                $disciplinesResult = collect([]);
                $finalCollection = collect([]);
                $arrayClassificationValues = array();
                $arrayCollectionDisciplines = collect([]);

                // Fazer um foreach pra pegar o $arrayValues (["Metodologias" => "mais"]) 
                // e trocar o "Metodologias" pelo respectivo id
                foreach ($classifications as $key => $value) {
                    foreach ($arrayValuesRanges as $arrKey => $arrValue) {
                        if (str_contains($arrKey, "_") == true) {
                            $charToBeRemoved;

                            for ($i = 0; $i < mb_strlen($arrKey); $i++) {
                                // Capturar qual a posição da string que tem o undeline
                                // que vai ser removido
                                if ($arrKey[$i] === '_') {
                                    $charToBeRemoved = $i;
                                }
                            }

                            $noUnderlineString = str_replace("_", " ", $arrKey);

                            if ($value->name == substr($noUnderlineString, 5)) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        } else {
                            if ($value->name == substr($arrKey, 5)) {
                                $arrayClassificationValues += array($value->id => $arrValue);
                            }
                        }
                    }
                }

                $fieldsToCheck = count($arrayClassificationValues);
                
                foreach ($disciplines as $disciplineKey => $disciplineValue) {
                    $cont = 0;
                    foreach ($arrayClassificationValues as $key => $value) {
                        $disciplineGroup = ClassificationDiscipline::where("discipline_id", $disciplineValue->discipline_id)
                        ->get();
                        
                        foreach ($disciplineGroup as $keyGroup => $valueGroup) {
                            if ($valueGroup->classification_id == $key) {
                                $disciplineFiltered = ClassificationDiscipline::where("discipline_id", $valueGroup->discipline_id)
                                ->where("classification_id", $valueGroup->classification_id)
                                ->where("value", ">=", $value)
                                ->get();

                                if (count($disciplineFiltered) == 1) {
                                    $arrayCollectionDisciplines->push($disciplineFiltered);
                                    $cont++;
                                }
                            }
                        }
                    }
                    
                    if ($fieldsToCheck == $cont) {
                        $result = Discipline::where("id", $disciplineValue->discipline_id)->get();
                        $disciplinesResult->push($result);
                    }
                }

                $disciplinesMixed = $disciplinesResult->collapse()->unique()->paginate(12);

                return view('disciplines.index')
                ->with('disciplines', $disciplinesMixed)
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            } else {
                return redirect('/')
                ->with('disciplines', $disciplines_all->paginate(12))
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            }
        } else {
            // Apenas o nome ou a ênfase foram enviados
            if ($discipline_name != null && $emphasis_id != null) {
                $disciplines = Discipline::where("name", "like", "%" . $discipline_name . "%")
                ->where("emphasis_id",$emphasis_id)
                ->paginate(12);

                return view('disciplines.index', compact('disciplines'))
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            } else if ($emphasis_id != null) {
                $disciplines = Discipline::where('emphasis_id', $emphasis_id)->paginate(12);

                return view('disciplines.index', compact('disciplines'))
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            } else if ($discipline_name != null) {
                $disciplines = Discipline::where("name", "like", "%" . $discipline_name . "%")->paginate(12);

                return view('disciplines.index', compact('disciplines'))
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            } else if ($emphasis_id == null) {
                $disciplines = Discipline::where("name", "like", "%" . $discipline_name . "%")->paginate(12);

                return view('disciplines.index', compact('disciplines'))
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            } else {
                return redirect('/')
                ->with('disciplines', $disciplines_all->paginate(12))
                ->with('emphasis', $emphasis_all)
                ->with('theme', $this->theme)
                ->with('classifications', $classifications_all)
                ->with('studentsData', $studentsData)
                ->with('periodsColection', $periodsColection);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(CreateRequest $request)
    {
        $professors = new Professor();
        $classifications = Classification::all();
        $emphasis = Emphasis::all();

        if (Auth::user()->isAdmin) {
            $professors = Professor::query()->orderBy('name', 'ASC')->get();
        }
        $opinioLinkForm = Link::where('name','opinionForm')->first();
        return view(self::VIEW_PATH . 'create', compact('professors'))
            ->with('classifications', $classifications)
            ->with('emphasis', $emphasis)
            ->with('theme', $this->theme)
            ->with('opinionLinkForm', $opinioLinkForm)
            ->with('showOpinionForm',true);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $professor = new Professor();
            if ($user->isAdmin) {
                $professor = Professor::query()->find($request->input('professor'));
            }

            $discipline = Discipline::create([
                'name' => $request->input('name'),
                'code' => $request->input('code'),
                'description' => $request->input('synopsis'),
                'emphasis_id' => $request->input('emphasis'),
                'difficulties' => $request->input('difficulties'),
                'acquirements' => $request->input('acquirements'),
                'professor_id' => $user->isAdmin ? $professor->id : $user->professor->id
            ]);

            if ($request->filled('media-trailer') && YoutubeService::match($request->input('media-trailer'))) {

                $url = $request->input('media-trailer');
                $mediaId = YoutubeService::getIdFromUrl($url);
                Media::create([
                    'title' => 'Trailer de ' . $discipline->name,
                    'type' => MediaType::VIDEO,
                    'is_trailer' => true,
                    'view_url' => 'https://www.youtube.com/embed/' . $mediaId,
                    'url' => $url,
                    'discipline_id' => $discipline->id,
                ]);
            }

            if ($request->filled('media-podcast') && GoogleDriveService::match($request->input('media-podcast'))) {
                $url = $request->input('media-podcast');
                $mediaId = GoogleDriveService::getIdFromUrl($url);
                Media::create([
                    'title' => "Podcast de " . $discipline->name,
                    'type' => MediaType::PODCAST,
                    'view_url' => "https://drive.google.com/uc?export=open&id=" . $mediaId,
                    'url' => $url,
                    'is_trailer' => false,
                    'discipline_id' => $discipline->id
                ]);
            }

            if ($request->filled('media-video') && YoutubeService::match($request->input('media-video'))) {
                $url = $request->input('media-video');
                $mediaId = YoutubeService::getIdFromUrl($url);
                Media::create([
                    'title' => "Video de " . $discipline->name,
                    'type' => MediaType::VIDEO,
                    'is_trailer' => false,
                    'view_url' => "https://www.youtube.com/embed/" . $mediaId,
                    'url' => $url,
                    'discipline_id' => $discipline->id
                ]);
            }

            if ($request->filled('media-material') && GoogleDriveService::match($request->input('media-material'))) {
                $url = $request->input('media-material');
                $mediaId = GoogleDriveService::getIdFromUrl($url);
                Media::create([
                    'title' => "Materiais de " . $discipline->name,
                    'type' => MediaType::MATERIAIS,
                    'is_trailer' => false,
                    'view_url' => "https://drive.google.com/uc?export=download&id=" . $mediaId,
                    'url' => $url,
                    'discipline_id' => $discipline->id
                ]);
            }

            // Apagar
            // $classificationsMap = [
            //     ClassificationID::METODOLOGIAS => "classificacao-metodologias",
            //     ClassificationID::DISCUSSAO => "classificacao-discussao",
            //     ClassificationID::ABORDAGEM => "classificacao-abordagem",
            //     ClassificationID::AVALIACAO => "classificacao-avaliacao",
            // ];

            $classificationsMap = Classification::all()->pluck('id')->toArray();

            foreach ($classificationsMap as $classificationId) {
                ClassificationDiscipline::create([
                    'classification_id' => $classificationId,
                    'discipline_id' => $discipline->id,
                    'value' => $request->input('classification-' . $classificationId) == null ? 0 : $request->input('classification-' . $classificationId)
                ]);
            }

            $titles = $request->input('title');
            $contents = $request->input('content');

            if ($titles != null) {
                foreach ($titles as $key => $title) {
                    Faq::create([
                        'discipline_id' => $discipline->id,
                        'title' => $title,
                        'content' => $contents[$key],
                    ]);
                }
            }

            $participants = json_decode($request->participantsList);
            if ($participants) {
                foreach ($participants as $participant) {
                    $participantModel = DisciplineParticipant::create([
                        'name' => $participant->name,
                        'role' => $participant->role,
                        'email' => $participant->email,
                        'discipline_id' => $discipline->id
                    ]);
                    if ($participant->links) {
                        foreach ($participant->links as $link) {
                            ParticipantLink::create([
                                'name' => $link->name,
                                'url' => $link->url,
                                'discipline_participant_id' => $participantModel->id
                            ]);
                        }
                    }
                }
            }


            DB::commit();
            return redirect()->route("disciplinas.show", $discipline->id);
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->route("disciplinas.create")
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $service = new APISigaaService();
        $discipline = Discipline::query()
            ->with([
                'professor',
                'medias',
                'faqs',
                'classificationsDisciplines.classification',
            ])
            ->findOrFail($id);
        $user = Auth::user();

        $classifications = Classification::all()->sortBy('order');
                
        $opinioLinkForm = Link::where('name','opinionForm')->first();
        if (!is_null($user)) {
            $can = $user->canDiscipline($discipline);
            return view(self::VIEW_PATH . 'show', compact('discipline', 'can'))
                ->with('classifications', $classifications)
                ->with('theme', $this->theme)
                ->with('opinionLinkForm',$opinioLinkForm)
                ->with('showOpinionForm',true);
        }
        
        return view(self::VIEW_PATH . 'show', compact('discipline'))
            ->with('classifications', $classifications)
            ->with('theme', $this->theme)
            ->with('opinionLinkForm', $opinioLinkForm)
            ->with('showOpinionForm',true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $emphasis = Emphasis::all();
        $professors = new Professor();
        if (Auth::user()->isAdmin) {
            $professors = Professor::query()->orderBy('name', 'ASC')->get();
        }
        $discipline = Discipline::query()
            ->with([
                'professor',
                'medias',
                'faqs',
                'disciplineParticipants',
            ])
            ->findOrFail($id);
        $classifications = Classification::query()->orderBy('order','ASC')->get();
        $participants = array();
        for ($i = 0; $i < count($discipline->disciplineParticipants()->get()); $i++) {
            array_push($participants, json_decode($discipline->disciplineParticipants()->get()[$i]));
            $participants[$i]->links = json_decode($discipline->disciplineParticipants()->get()[$i]->links);
        }

        $opinioLinkForm = Link::where('name','opinionForm')->first();
        return view(self::VIEW_PATH . 'edit', compact('discipline'), compact('professors'))
            ->with('classifications', $classifications)
            ->with('emphasis', $emphasis)
            ->with('theme', $this->theme)
            ->with('participants', $participants)
            ->with('opinionLinkForm', $opinioLinkForm)
            ->with('showOpinionForm',true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $professor = new Professor();

            if ($user->isAdmin) {
                $professor = Professor::query()->find($request->input('professor'));
            }




            $discipline = Discipline::query()->find($id);
            $discipline->update([
                'name' => $request->input('name'),
                'code' => $request->input('code'),
                'description' => $request->input('description'),
                'emphasis_id' => $request->input('emphasis'),
                'difficulties' => $request->input('difficulties'),
                'acquirements' => $request->input('acquirements'),
                'professor_id' => $user->isAdmin ? $professor->id : $user->professor->id,
            ]);

            $participantsFromJson = (json_decode($request->participantList));
            /* Deleta os participantes que não estão na lista da requisição */
            $databaseDisciplineParticipants = $discipline->disciplineParticipants()->get();
            foreach ($databaseDisciplineParticipants as $participant) {
                $hasParticipant = false;
                for ($i = 0; $i < count($participantsFromJson); $i++) {
                    if (isset($participantsFromJson[$i]->id) && ($participantsFromJson[$i]->id == $participant->id)) {
                        $hasParticipant = true;
                    }
                }
                if (!$hasParticipant) {
                    $discipline->disciplineParticipants()->find($participant->id)->delete();
                }
            }
            /*Inclui ou atualiza os participantes da disciplina */
            foreach ($participantsFromJson as $json) {
                $participant = new DisciplineParticipant();
                if (isset($json->id)) {
                    $participant->id = $json->id;
                }

                $participant->name = $json->name;
                $participant->role = $json->role;
                $participant->email = $json->email;
                $participant->discipline()->associate($discipline);
                if (isset($json->id)) {
                    $discipline->disciplineParticipants()->updateOrCreate(['id' => $json->id], $participant->toArray());
                } else {
                    $participant->save();
                    //$discipline->disciplineParticipants()->save($participant->toArray());
                }
                ParticipantLink::where('discipline_participant_id', $participant->id)->delete();
                foreach ($json->links as $linkJson) {
                    $link = new ParticipantLink();
                    $link->name = $linkJson->name;
                    $link->url = $linkJson->url;
                    $participant->links()->save($link);
                }
            }

            $url = $request->input('media-trailer') ?? '';
            $mediaId = YoutubeService::getIdFromUrl($url);

            if (!$discipline->has_trailer_media) {
                Media::create([
                    'title' => 'Trailer de ' . $discipline->name,
                    'type' => MediaType::VIDEO,
                    'is_trailer' => true,
                    'view_url' => 'https://www.youtube.com/embed/' . $mediaId,
                    'url' => $url,
                    'discipline_id' => $discipline->id,
                ]);
            } else {
                $view_url = 'https://www.youtube.com/embed/' . $mediaId;
                if ($mediaId == '') {
                    $view_url = '';
                }
                Media::query()->find($discipline->trailer->id)->update([
                    'view_url' => $view_url,
                    'url' => $url,
                ]);
            }


            $url = $request->input('media-podcast') ?? '';
            $mediaId = GoogleDriveService::getIdFromUrl($url);
            if (!$discipline->hasMediaOfType(\App\Enums\MediaType::PODCAST)) {
                Media::create([
                    'title' => 'Podcast de ' . $discipline->name,
                    'type' => MediaType::PODCAST,
                    'is_trailer' => false,
                    'view_url' => "https://drive.google.com/uc?export=open&id=" . $mediaId,
                    'url' => $url,
                    'discipline_id' => $discipline->id,
                ]);
            } else {
                $view_url = 'https://drive.google.com/uc?export=open&id=' . $mediaId;
                if ($mediaId == '') {
                    $view_url = '';
                }
                Media::query()->find($discipline->getMediaByType(\App\Enums\MediaType::PODCAST)->id)->update([
                    'view_url' => $view_url,
                    'url' => $url,
                ]);
            }

            $url = $request->input('media-video') ?? '';
            $mediaId = YoutubeService::getIdFromUrl($url);
            if (!$discipline->hasMediaOfType(\App\Enums\MediaType::VIDEO)) {
                Media::create([
                    'title' => 'Video de ' . $discipline->name,
                    'type' => MediaType::VIDEO,
                    'is_trailer' => false,
                    'view_url' => 'https://www.youtube.com/embed/' . $mediaId,
                    'url' => $url,
                    'discipline_id' => $discipline->id,
                ]);
            } else {
                $view_url = 'https://www.youtube.com/embed/' . $mediaId;
                if ($mediaId == '') {
                    $view_url = '';
                }
                Media::query()->find($discipline->getMediaByType(\App\Enums\MediaType::VIDEO)->id)->update([
                    'view_url' => $view_url,
                    'url' => $url,
                ]);
            }

            $url = $request->input('media-material') ?? '';
            $mediaId = GoogleDriveService::getIdFromUrl($url);
            if (!$discipline->hasMediaOfType(\App\Enums\MediaType::MATERIAIS)) {
                Media::create([
                    'title' => 'Material de ' . $discipline->name,
                    'type' => MediaType::MATERIAIS,
                    'is_trailer' => false,
                    'view_url' => 'https://drive.google.com/uc?export=download&id=' . $mediaId,
                    'url' => $url,
                    'discipline_id' => $discipline->id,
                ]);
            } else {
                $view_url = 'https://drive.google.com/uc?export=download&id=' . $mediaId;
                if ($mediaId == '') {
                    $view_url = '';
                }
                Media::query()->find($discipline->getMediaByType(\App\Enums\MediaType::MATERIAIS)->id)->update([
                    'view_url' => $view_url,
                    'url' => $url,
                ]);
            }

            // atualizar as classificações de uma disciplina pelo id
            $classification_collection = collect([]);
            $classification_collection = ClassificationDiscipline::where('discipline_id', $id)->get();
            $classificationsMap = Classification::all()->pluck('id')->toArray();
            // foreach ($classificationsMap as $classificationId) {
            //     ClassificationDiscipline::updateOrCreate(
            //         [
            //             'discipline_id' => $discipline->id,
            //             'classification_id' => $classificationId
            //         ],
            //         ['value' => $request->input('classification-' . $classificationId)]
            //     );
            // }
        
            foreach($classification_collection as $col) {
                foreach($classificationsMap as $class){
                    ClassificationDiscipline::where('discipline_id',$id)
                    ->where('classification_id', $class)
                    ->update(['value' => $request->input('classification-'.$class)]);
                }
            }

            $faqValidator = Validator::make($request->all(), [
                'faqTitle.*' => 'required',
                'faqContent.*' => 'required'
            ]);

            if ($faqValidator->fails()) {

                return redirect()->back()->withInput()->withErrors(['faq' => 'faq']);
            }

            $databaseFaqsIds = Faq::where('discipline_id', $discipline->id)->pluck('id')->toArray();
            $faqIds = $request->faqId;
            $faqTitles = $request->faqTitle;
            $faqContents = $request->faqContent;

            if (isset($faqIds)) {

                foreach ($faqIds as $i => $ids) {
                    if (!isset($ids) ||  $ids != 'undefined') {
                        Faq::where('id', $faqIds[$i])->update(['title' => $faqTitles[$i], 'content' => $faqContents[$i]]);
                    } else {
                        Faq::create([
                            'title' => $faqTitles[$i],
                            'content' => $faqContents[$i],
                            'discipline_id' => $discipline->id
                        ]);
                    }
                }

                if (count($databaseFaqsIds) > 0) {
                    foreach ($databaseFaqsIds as $i => $dIds) {
                        if (!in_array($dIds, $faqIds)) {
                            Faq::find($dIds)->delete();
                        }
                    }
                }
            }else{
                Faq::where('discipline_id',$discipline->id)->delete();
            }

            /* foreach ($faqsMap as $faqId) {
                Faq::updateOrCreate(
                    ['title' => $discipline->id,'title' => $faqId],
                    ['content' => $request->input('content-' . $faqId)]
                );
            }     */

            $discipline->save();
            DB::commit();
            return redirect()->route("disciplinas.show", $discipline->id);
        } catch (\Exception $exception) {
            dd($exception);
            DB::rollBack();
            return redirect()->route("disciplinas.edit", $discipline->id)
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Discipline::query()
            ->where('id', $id)
            ->delete();

        return redirect()->route('index');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $disciplines = DB::table('disciplines')
            ->select(
                'disciplines.*',
                (DB::raw("(SELECT medias.url FROM medias WHERE medias.discipline_id = disciplines.id and medias.type = 'video' and medias.is_trailer = '1' ) AS urlMedia"))
            )
            ->where('disciplines.name', 'like', "%$search%")
            ->get();

        return view('disciplines-search')
            ->with('disciplines', $disciplines)
            ->with('search', $search)
            ->with('theme', $this->theme);
    }

    public function mydisciplines()
    {


        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $id = Auth::id();
        $disciplines = Discipline::where('user_id', '=', $id)
            ->join('users', 'users.id', '=', 'disciplines.user_id')
            ->leftJoin('medias', 'disciplines.id', '=', 'medias.discipline_id')
            ->select('disciplines.*', 'users.name as nameUser')
            ->orderBy('disciplines.name', 'asc')
            ->orderBy('nameUser', 'asc')
            ->get();

        return view('my-disciplines')
            ->with('disciplines', $disciplines)
            ->with('theme', $this->theme);
    }

    /**
     * Obtém os dados de turmas consolidadas como quantidade de aprovações, reprovações e média geral.
     * @param \Illuminate\Http\Request $request Objeto contendo as informações de requição http.
     * @param string $disciplineCode Código da disciplina.
     * @param int year $ano em que a a turma foi aberta.
     * @param int período em que a turma foi aberta.
     * @return @return \Illuminate\Http\JsonResponse
     */
    function getDisciplineData(Request $request){
        $apiService = new APISigaaService();

        $data = $apiService->getDisciplineData($request['codigo'], $request['idTurma'], $request['ano'], $request['periodo']);
        
        return response()->json($data,200);
    }

    function getCodesAndNames(Request $request){
        if($request->ajax()){
            $disciplineCodesAndNames = [];
            $disciplines = Discipline::where("name","like",'%' . $request->disciplineName . '%')->get();
            foreach($disciplines as $discipline){
                $disciplineCodeAndName= new stdClass();
                $disciplineCodeAndName->code = $discipline->code;
                $disciplineCodeAndName->name = $discipline->name;
                if(in_array($disciplineCodeAndName, $disciplineCodesAndNames) == false){
                    array_push($disciplineCodesAndNames, $disciplineCodeAndName);
                }
                
            }
            return response()->json($disciplineCodesAndNames);
        }
    }


}
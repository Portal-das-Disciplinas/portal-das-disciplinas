<?php

namespace App\Http\Controllers\Chart;

use App\Enums\CacheKey;
use App\Http\Requests\Chart\PassRate\GetTableDataRequest;
use App\Http\Requests\Chart\PassRate\IndexRequest;
use App\Http\Requests\Chart\PassRate\SelectDisciplinesRequest;
use App\Http\Requests\Chart\PassRate\SelectProfessorsRequest;
use App\Services\FileReader\CsvReader;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PassRateController extends ChartController
{
    /**
     * @param IndexRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(IndexRequest $request)
    {
        return view(self::VIEW_PATH . 'pass_rate');
    }

    /**
     * @return Collection
     */
    private function getData(): Collection
    {
        $data = Cache::get(CacheKey::CHART_DATA);

        if (is_null($data)) {
            $filePath = storage_path(self::STORAGE_PATH . 'pass_rate.csv');
            $csvReader = new CsvReader($filePath, CsvReader::SEPARATOR_COMMA, true);
            $data = $csvReader->getAsCollection();

            $date = Carbon::now()
                ->addDay()
                ->setTime(0, 0, 0);

            Cache::put(CacheKey::CHART_DATA, $data, $date);
        }

        return $data;
    }

    /**
     * @param SelectProfessorsRequest $request
     * @return mixed
     */
    public function selectProfessors(SelectProfessorsRequest $request)
    {
        $professors = Cache::get(CacheKey::CHART_PROFESSORS);

        if (is_null($professors)) {
            $passRateCollect = $this->getData();
            $professors = $passRateCollect->pluck('nome_docente', 'siape')
                ->unique()
                ->sort();

            $date = Carbon::now()
                ->addDay()
                ->setTime(0, 0, 0);

            Cache::put(CacheKey::CHART_PROFESSORS, $professors, $date);
        }

        if ($request->has('q')) {
            $search = $request->get('q');
            $data = $professors->filter(function ($discipline) use ($search) {
                return false !== stripos($discipline, $search);
            })->paginate(30);
        } else {
            $data = $professors->paginate(30);
        }

        return $data;
    }

    /**
     * @param SelectDisciplinesRequest $request
     * @return mixed
     */
    public function selectDisciplines(SelectDisciplinesRequest $request)
    {
        $disciplines = Cache::get(CacheKey::CHART_DISCIPLINES);

        if (is_null($disciplines)) {
            $passRateCollect = $this->getData();
            $disciplines = $passRateCollect->pluck('nome_componente', 'id_componente_curricular')
                ->unique()
                ->sort();

            $date = Carbon::now()
                ->addDay()
                ->setTime(0, 0, 0);

            Cache::put(CacheKey::CHART_DISCIPLINES, $disciplines, $date);
        }

        if ($request->has('q')) {
            $search = $request->get('q');
            $data = $disciplines->filter(function ($discipline) use ($search) {
                return false !== stripos($discipline, $search);
            })->paginate(30);
        } else {
            $data = $disciplines->paginate(30);
        }

        return $data;
    }

    /**
     * @param GetTableDataRequest $request
     * @return array
     */
    public function getTableData(GetTableDataRequest $request)
    {
        $passRateCollect = $this->getData();
        $type = $request->get('type');
        $id = $request->get('id');
        $data = collect();

        if ($type == 'professor') {
            $data = $passRateCollect->filter(function ($row) use ($id) {
                return $row['siape'] == $id;
            })->sortByDesc('taxa_aprovacao')
                ->values();
        } elseif ($type == 'discipline') {
            $data = $passRateCollect->filter(function ($row) use ($id) {
                return $row['id_componente_curricular'] == $id;
            })->sortByDesc('taxa_aprovacao')
                ->values();
        }

        return $data->toArray();
    }
}

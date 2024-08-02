<?php

namespace App\Http\Controllers;

use App\Services\PortalAccessInfoService;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PortalAccessInfoController extends Controller
{
    protected $theme;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
        $this->middleware('admin');
    }

    public function index(Request $request){

        $service = new PortalAccessInfoService();
        $totalAccess = $service->getNumAccess();
        $totalDistinctAccess = $service->getNumDistinctAccess();
        $pathMoreAccessed = $service->getPathMoreAccessed();

        $initialDate = $request->{'initial-date'};
        $finalDate = $request->{'final-date'};
        $oldInitialDate = null;
        $oldFinalDate = null;

        $totalAccessPeriod = null;
        $totalDistinctAccessPeriod = null;
        $pathMoreAccessedPeriod = null;
        if(isset($initialDate) || isset($finalDate)){

            $IDate = date_create($initialDate);
            
            $FDate = date_create($finalDate);

            $oldInitialDate = $initialDate;
            $oldFinalDate = $finalDate;

            $totalAccessPeriod = $service->getNumAccess($IDate,$FDate);
            $totalDistinctAccessPeriod = $service->getNumDistinctAccess($IDate, $FDate);
            $pathMoreAccessedPeriod = $service->getPathMoreAccessed($IDate, $FDate);

        }
        return view('portal_access_info.index') ->with([
            "theme" => $this->theme,
            "totalAccess" => $totalAccess,
            "totalDistinctAccess" => $totalDistinctAccess,
            "pathMoreAccessed" => $pathMoreAccessed,
            "initialDate" => $initialDate,
            "finalDate" => $finalDate,
            "totalAccessPeriod" => $totalAccessPeriod,
            "totalDistinctAccessPeriod" => $totalDistinctAccessPeriod,
            "pathMoreAccessedPeriod" => $pathMoreAccessedPeriod,
            "oldInitialDate" => $oldInitialDate,
            "oldFinalDate" => $oldFinalDate
        ]);
    }
}

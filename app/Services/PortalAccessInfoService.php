<?php

namespace App\Services;

use App\Exceptions\InvalidInputException;
use App\Exceptions\InvalidIntervalException;
use App\Models\PortalAccessInfo;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Log;

class PortalAccessInfoService
{

    public function registerAccess($ip, $path, $accessedOn)
    {
        try {
            PortalAccessInfo::create([
                "ip" => $ip,
                "path" => $path,
                "accessed_on" => $accessedOn
            ]);
        } catch (Exception $e) {
            Log::error("Erro ao registrar o acesso: " . $ip . " " . $path . " ");
            Log::error($e);
        }
    }

    public function getNumAccess($initialDate = null, $finalDate = null)
    {
        if ($initialDate == null && $finalDate == null) {
            return PortalAccessInfo::All()->count();
        } else {
            if ($initialDate == null || $finalDate == null) {
                throw new InvalidInputException("A data não pode ser nula");
            }
            if ($finalDate < $initialDate) {
                throw new InvalidIntervalException("Intervalo inválido");
            }

            return PortalAccessInfo::whereDate('accessed_on', '>=', $initialDate)
                ->whereDate('accessed_on', '<=', $finalDate)->count();
        }
    }

    public function getNumDistinctAccess($initialDate = null, $finalDate = null)
    {
        if ($initialDate == null && $finalDate == null) {
            return PortalAccessInfo::distinct("ip")->select("path")->count();
        } else {
            if ($initialDate == null || $finalDate == null) {
                throw new InvalidInputException("A data não pode ser nula");
            }
            if ($finalDate < $initialDate) {
                throw new InvalidIntervalException("Intervalo inválido");
            }
            return PortalAccessInfo::distinct("ip")->select("path")->whereDate('accessed_on', '>=', $initialDate)
                ->whereDate('accessed_on', '<=', $finalDate)->count();
        }
    }

    public function getPathMoreAccessed($initialDate = null, $finalDate = null)
    {
        $distinctPaths = null;
        $moreAccessedPath = "";
        $maxAccess = 0;
        $pathAccessCount = [];
        if ($initialDate == null && $finalDate == null) {
            $distinctPaths = PortalAccessInfo::distinct("path")->select('portal_access_infos.path')->get();
            foreach($distinctPaths as $path){
                $pathAccessCount[$path->path] = 0;
            }
        } else {
            if ($initialDate == null || $finalDate == null) {
                throw new InvalidInputException("A data não pode ser nula");
            }
            if ($finalDate < $initialDate) {
                throw new InvalidIntervalException("Intervalo inválido");
            }
            $distinctPaths = PortalAccessInfo::distinct("path")->select('portal_access_infos.path')
                ->whereDate('accessed_on', '>=', $initialDate)->whereDate('accessed_on', '<=', $finalDate)->get();
                foreach($distinctPaths as $path){
                    $pathAccessCount[$path->path] = 0;
                }
        }
        foreach ($distinctPaths as $path) {
            $count = 0;
            if ($initialDate == null && $finalDate == null) {
                $count = PortalAccessInfo::where('path', '=', $path->path)->count();
                $pathAccessCount[$path->path] = $count;
            } else {
                $count = PortalAccessInfo::whereDate('accessed_on', '>=', $initialDate)
                    ->whereDate('accessed_on', '<=', $finalDate)
                    ->where('path', '=', $path->path)->count();
                $pathAccessCount[$path->path] = $count;
            }


            if ($count > $maxAccess) {
                $moreAccessedPath = $path->path;
                $maxAccess = $count;
            }
        }
        $pathsWithBiggestAccess = [];
        foreach($pathAccessCount as $path=>$numAccess){
            if($numAccess == $maxAccess){
                array_push($pathsWithBiggestAccess,$path);

            }
        }
        //Log::info($pathAccessCount);
        //Log::info($pathsWithBiggestAccess);
        //Log::info("max Access: " . $maxAccess);
        return $pathsWithBiggestAccess;
    }
}

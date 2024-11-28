<?php

namespace App\Services;

use App\Exceptions\InvalidInputException;
use App\Exceptions\InvalidIntervalException;
use App\Models\PortalAccessInfo;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PortalAccessInfoService
{

    public function registerAccess($ip, $path, $accessedOn)
    {
        if(Auth::user() && Auth::user()->isAdmin){
            return;
        }
        
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
            return PortalAccessInfo::count();
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
        if ($initialDate == null && $finalDate == null) {
           $data = PortalAccessInfo::select('path')
                ->groupBy('path')->orderByRaw('count(*) desc')
                ->limit(3)
                ->get();
            return $data;

        } else {
            if ($initialDate == null || $finalDate == null) {
                throw new InvalidInputException("A data não pode ser nula");
            }
            if ($finalDate < $initialDate) {
                throw new InvalidIntervalException("Intervalo inválido");
            }
            $data = PortalAccessInfo::select('path')
                ->whereDate('accessed_on', '>=', $initialDate)->whereDate('accessed_on', '<=', $finalDate)
                ->groupBy('path')->orderByRaw('count(*) desc')
                ->limit(3)
                ->get();
            return $data;
        }
        
    }
}
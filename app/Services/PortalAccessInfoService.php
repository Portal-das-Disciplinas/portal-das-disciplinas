<?php

namespace App\Services;

use App\Models\PortalAccessInfo;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Log;

class PortalAccessInfoService{

    public function registerAccess($ip, $path, $accessedOn){
        try{
            PortalAccessInfo::create([
                "ip"=> $ip,
                "path" => $path,
                "accessed_on" => $accessedOn
            ]);
        }catch(Exception $e){
            Log::info("Erro ao registrar o acesso: " . $ip . " " . $path . " ");
            Log::error($e);
        }
        
    }
}
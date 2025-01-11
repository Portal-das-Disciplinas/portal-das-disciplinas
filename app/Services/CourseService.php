<?php

use App\Models\Course;

namespace App\Services;

use App\Exceptions\NotAuthorizedException;
use App\Models\Course;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CourseService{

    public function list($unitId=null, $courseLevelId=null){
        $query = Course::query();
        if(isset($unitId)){
            $query->where('institutional_unit_id','=',$unitId);
        }
        if(isset($courseLevelId)){
            $query->where('education_level_id','=',$courseLevelId);
        }

        return $query->orderBy('name','asc')->get();
    }

    public function save($name, $unitId, $levelId){

        if($this->checkIsAdmin()){
            return Course::create([
                'name' => $name,
                'institutional_unit_id' => $unitId,
                'education_level_id' => $levelId
            ]);

        }elseif($this->checkIsUnitAdmin()){
            $unitIdOfUser = Auth::user()->unitAdmin->institutionalUnit->id;

            if($unitIdOfUser == $unitId){
                return Course::create([
                    'name' => $name,
                    'institutional_unit_id' => $unitId,
                    'education_level_id' => $levelId
                ]);

            }else{
                throw new NotAuthorizedException('Você não está alocado nesta unidade.');
            }
            
        }else{
            throw new NotAuthorizedException("Você não tem permissão para realizar esta operação.");
        }
        
    }

    private function checkIsAdmin(){
        return (Auth::user() && Auth::user()->is_admin);
    }

    private function checkIsUnitAdmin(){
        return (Auth::user() && Auth::user()->is_unit_admin);
    }
}
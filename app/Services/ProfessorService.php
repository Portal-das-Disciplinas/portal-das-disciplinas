<?php
namespace App\Services;

use App\Models\Professor;

class ProfessorService{
    
    public function getProfessorsByInstitutionalUnit($idUnit){

        return Professor::query()->where('institutional_unit_id','=',$idUnit)->orderBy('name','asc')->get();
    }
}
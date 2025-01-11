<?php

namespace App\Services;

use App\Exceptions\ExistingDataException;
use App\Exceptions\IntegrityConstraintViolationException;
use App\Exceptions\InvalidInputException;
use App\Models\InstitutionalUnit;
use App\Models\Professor;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class InstitutionalUnitService
{
    public function listAll()
    {
        return InstitutionalUnit::query()->orderBy('name', 'asc')->get();
    }

    public function getByUnitAdmin($adminUnitId){
        return InstitutionalUnit::where('unit_admin_id','=',$adminUnitId)->first();
    }

    public function save($unitAcronym, $unitName)
    {
        if (isset($unitAcronym) && strlen($unitAcronym) > 10) {
            throw new InvalidInputException("A sigla está muito longa!");

        } elseif (!isset($unitName) || $unitName == "") {
            throw new InvalidInputException("O nome da unidade não pode ser nulo!");

        } elseif (InstitutionalUnit::query()->where('acronym', '=', $unitAcronym)->where('name', '=', $unitName)->exists()) {
            throw new ExistingDataException("Está unidade já foi cadastrada!");

        } else {
            try {
                return InstitutionalUnit::create([
                    'acronym' => $unitAcronym,
                    'name' => $unitName
                ]);
            } catch (Exception $e) {
                throw $e;
            }
        }
    }

    public function delete($id)
    {
        $numberOfProfessorsInUnit = Professor::where('institutional_unit_id','=',$id)->count();
        if($numberOfProfessorsInUnit >0){
            throw new IntegrityConstraintViolationException('Não foi possível deletar. Há ' 
                . $numberOfProfessorsInUnit . " professor(es) na unidade.");
        }

        DB::beginTransaction();
        try {
            InstitutionalUnit::destroy($id);
            DB::commit();

        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
}

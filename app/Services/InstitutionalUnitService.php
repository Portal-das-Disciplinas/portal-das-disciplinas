<?php

namespace App\Services;

use App\Exceptions\ExistingDataException;
use App\Exceptions\InvalidInputException;
use App\Models\InstitutionalUnit;
use Exception;
use Illuminate\Support\Facades\DB;

class InstitutionalUnitService
{
    public function listAll()
    {
        return InstitutionalUnit::query()->orderBy('name', 'asc')->get();
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
        DB::beginTransaction();
        try {
            InstitutionalUnit::destroy($id);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Não foi possível deletar a unidade");
        }
    }
}

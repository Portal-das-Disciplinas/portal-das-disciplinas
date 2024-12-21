<?php

namespace App\Services;

use App\Models\InstitutionalUnit;
use Exception;
use Illuminate\Support\Facades\DB;

class InstitutionalUnitService
{
    public function listAll()
    {
        return InstitutionalUnit::query()->orderBy('name', 'asc')->get();
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
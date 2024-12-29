<?php

namespace App\Services;

use App\Exceptions\ExistingDataException;
use App\Exceptions\NotAuthorizedException;
use App\Models\InstitutionalUnit;
use App\Models\Role;
use App\Models\UnitAdmin;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UnitAdminService
{

    public function list()
    {

        return UnitAdmin::leftJoin('users', 'unit_admins.user_id', '=', 'users.id')
            ->orderBy('users.name')->select('unit_admins.*')->get();
    }

    public function save($name, $email, $password, $idUnit)
    {
        $userService = new UserService();
        $existingUnitWithAdmin = UnitAdmin::where('institutional_unit_id', '=', $idUnit)->exists();
        if ($existingUnitWithAdmin) {
            throw new ExistingDataException("Esta Unidade já possui um administrador");
        }

        DB::beginTransaction();
        try {
            $idRole = Role::where('name', '=', 'unit_admin')->first()->id;
            $user = $userService->save($name, $email, $password, $idRole);
            $unitAdmin = UnitAdmin::create([
                'user_id' => $user->id,
                'institutional_unit_id' => $idUnit
            ]);

            $institutionalUnit = InstitutionalUnit::find($idUnit);
            $institutionalUnit->unit_admin_id = $unitAdmin->id;
            $institutionalUnit->save();
            DB::commit();
            return $unitAdmin;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        if ($this->isAdmin()) {
            DB::beginTransaction();
            try {
                $userAdmin = UnitAdmin::findOrFail($id);
                User::destroy($userAdmin->user->id);
                UnitAdmin::destroy($id);
                DB::commit();

            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } else {
            throw new NotAuthorizedException("Você não tem permissão para executar esta operação");
        }
    }

    private function isAdmin()
    {
        return Auth::user() && Auth::user()->isAdmin;
    }

    private function isUnitAdmin()
    {
        return Auth::user() && Auth::user()->is_unit_admin;
    }
}

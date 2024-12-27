<?php

namespace App\Services;

use App\Exceptions\ExistingDataException;
use App\Models\Role;
use App\Models\UnitAdmin;
use Exception;
use Illuminate\Support\Facades\DB;

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
        $existingAdmin = UnitAdmin::where('institutional_unit_id', '=', $idUnit)->exists();
        if ($existingAdmin) {
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

            DB::commit();
            return $unitAdmin;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

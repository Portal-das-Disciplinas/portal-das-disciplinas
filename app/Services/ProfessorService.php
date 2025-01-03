<?php

namespace App\Services;

use App\Enums\RoleName;
use App\Exceptions\MissingDataException;
use App\Models\Professor;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfessorService
{

    public function listAll(){
        return Professor::query()->orderBy('name','asc')->get();
    }

    public function ListByInstitutionalUnitId($id){
        return Professor::where('institutional_unit_id','=',$id)->orderBy('id','asc')->get();
    }

    public function save(
        $name,
        $loginEmail,
        $password,
        $profilePictureLink = null,
        $publicEmail,
        $socialNetwork1,
        $socialNetwork1Link,
        $socialNetwork2,
        $socialNetwork2Link,
        $socialNetwork3,
        $socialNetwork3Link,
        $socialNetwork4,
        $socialNetwork4Link,
        $idUnit=null
    ) {
        
        DB::beginTransaction();
        try {
            $roleId = Role::where('name', '=', RoleName::PROFESSOR)->first()->id;
            if(Auth::user() && Auth::user()->is_unit_admin && $idUnit == null){
                throw new MissingDataException("O professor tem que estar na mesma unidade do administrador de unidade");
            }
            $user = User::create([
                'name' => $name,
                'email' => $loginEmail,
                'password' => bcrypt($password),
                'role_id' => $roleId
            ]);

            $professor = Professor::create([
                'name' => $user->name,
                'profile_pic_link' => $profilePictureLink,
                'public_email' => $publicEmail,
                'rede_social1' => $socialNetwork1,
                'link_rsocial1' => $socialNetwork1Link,
                'rede_social2' => $socialNetwork2,
                'link_rsocial2' => $socialNetwork2Link,
                'rede_social3' => $socialNetwork3,
                'link_rsocial3' => $socialNetwork3Link,
                'rede_social4' => $socialNetwork4,
                'link_rsocial4' => $socialNetwork4Link,
                'user_id' => $user->id,
                'institutional_unit_id' => $idUnit
            ]);
            DB::commit();
            return $professor;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    

    public function getProfessorsByInstitutionalUnit($idUnit)
    {
        return Professor::query()->where('institutional_unit_id', '=', $idUnit)->orderBy('name', 'asc')->get();
    }
}

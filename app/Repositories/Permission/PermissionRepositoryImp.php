<?php

namespace App\Repositories\Permission;

use App\Exceptions\Authorization\Permission\PermissionCreateException;
use App\Exceptions\Authorization\Permission\PermissionDeleteException;
use App\Exceptions\Authorization\Permission\PermissionRetrieveException;
use App\Exceptions\Authorization\Permission\PermissionUpdateException;
use \Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Authorization\Permission;
use App\Models\Authorization\RolePermission;

class PermissionRepositoryImp implements PermissionRepository
{

    public function createPermisionRoot(
        string $name,
        string $slug,
        string $ability,
        string $description
    ): int {
        try {

            $permission = Permission::create([
                'PERMISSION_NAME' => $name,
                'PERMISSION_SLUG' => $slug,
                'PERMISSION_ABILITY' => $ability,
                'PERMISSION_ACTIVE' => Permission::ACTIVE_Y,
                'PERMISSION_DESCRIPTION' => $description,
            ]);

            return $permission->PERMISSION_CODE;
        } catch (\Throwable $th) {
            throw PermissionCreateException::create();
        }
    }

    public function createPermision(
        string $name,
        string $slug,
        string $ability,
        string $description,
        int $permissionCode
    ): int {
        try {

            $permission = Permission::create([
                'PERMISSION_NAME' => $name,
                'PERMISSION_SLUG' => $slug,
                'PERMISSION_ABILITY' => $ability,
                'PERMISSION_ACTIVE' => Permission::ACTIVE_Y,
                'PERMISSION_DESCRIPTION' => $description,
                'PERMISSION_PERMISSION_CODE' => $permissionCode
            ]);

            return $permission->PERMISSION_CODE;
        } catch (\Throwable $th) {
            throw PermissionCreateException::create();
        }
    }

    public function delete(
        int $code
    ): void {
        try {
            Permission::destroy($code);
        } catch (\Throwable $th) {
            throw PermissionDeleteException::delete();
        }
    }

    public function update(
        int $code, 
        string $name, 
        string $description, 
        string $active
    ): void {
        try {

            DB::update("UPDATE permissions SET PERMISSION_NAME = ?, PERMISSION_DESCRIPTION = ?, 
                PERMISSION_ACTIVE = ? WHERE PERMISSION_CODE = ?", [
                $name,
                $description,
                $active,
                $code,
            ]);
        } catch (\Throwable $th) {
            throw PermissionUpdateException::update();
        }
    }

    public function permissionsRootByActive( 
        $active 
    ): Collection {
        $query = Permission::whereNull('PERMISSION_PERMISSION_CODE')
            ->where('PERMISSION_ACTIVE', '=', $active);

        return $query->get();
    }

    public function findPermissionsByPermissionCode( 
        $permissionCode 
    ): Collection {
        try {
            return Permission::where('PERMISSION_PERMISSION_CODE', '=', $permissionCode)->get();
        } catch (\Throwable $th) {
            throw PermissionRetrieveException::list();
        }
    }

    public function findByCode(
        int $code
    ): ?Permission {
        try {

            $permission = Permission::find($code);

            return $permission;
        } catch (\Throwable $th) {
            throw PermissionRetrieveException::select();
        }
    }

    public function findByNameInPermissionRoot(
        string $name
    ): ?Permission {
        try {
            $query = Permission::where('PERMISSION_NAME', '=', $name)
                ->whereNull('PERMISSION_PERMISSION_CODE');

            return $query->first();
        } catch (\Throwable $th) {
            throw PermissionRetrieveException::select();
        }
    }

    public function findByNameNotByThisCodeInPermissionRoot(
        int $code, 
        string $name
    ): ?Permission {
        $query = Permission::where('PERMISSION_NAME', '=', $name)
            ->whereNotIn('PERMISSION_CODE', [$code])
            ->whereNull('PERMISSION_PERMISSION_CODE');
        return $query->first();
    }

    public function findByNameNotByThisCode(
        int $code, 
        string $name, 
        int $permissionCode
    ): ?Permission {
        $query = Permission::where('PERMISSION_NAME', '=', $name)
            ->whereNotIn('PERMISSION_CODE', [$code])
            ->where('PERMISSION_PERMISSION_CODE', '=', $permissionCode);
        return $query->first();
    }

    public function findByName(
        string $name, 
        int $permissionCode
    ): ?Permission {
        $query =  Permission::where('PERMISSION_NAME', '=', $name)
            ->where('PERMISSION_PERMISSION_CODE', '=', $permissionCode);

        return $query->first();
    }

    public function findBySlugInPermissionRoot(
        string $name
    ): ?Permission {
        $query = Permission::where('PERMISSION_SLUG', '=', $name)
            ->whereNull('PERMISSION_PERMISSION_CODE');

        return $query->first();
    }

    public function findBySlug(
        string $name, 
        int $permissionCode
    ): ?Permission {
        $query = Permission::where('PERMISSION_SLUG', '=', $name)
            ->where('PERMISSION_PERMISSION_CODE', '=', $permissionCode);

        return $query->first();
    }

    public function findByAbility(
        string $ability
    ): ?Permission {
        $query = Permission::where('PERMISSION_ABILITY', '=', $ability);

        return $query->first();
    }

    public function permissionsRoot(): Collection
    {
        try {
            return Permission::whereNull('PERMISSION_PERMISSION_CODE')->get();
        } catch (\Throwable $th) {
            throw PermissionRetrieveException::list();
        }
    }

    public function searchLikeAbility(
        string $ability
    ): Collection {
        try {
            return Permission::where('PERMISSION_ABILITY', 'LIKE', $ability . '%')->get();
        } catch (\Throwable $th) {
            throw PermissionRetrieveException::list();
        }
    }
}

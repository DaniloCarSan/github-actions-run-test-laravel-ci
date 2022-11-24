<?php

namespace App\Repositories\Permission;

use App\Models\Authorization\Permission;
use \Illuminate\Database\Eloquent\Collection;

interface PermissionRepository {

    public function permissionsRoot(): Collection;
    
    public function createPermisionRoot(
        string $name, 
        string $slug, 
        string $ability, 
        string $description
    ): int;

    public function createPermision(
        string $name, 
        string $slug, 
        string $ability, 
        string $description, 
        int $permissionCode
    ): int;

    public function delete( 
        int $code
    ): void;

    public function update(
        int $code, 
        string $name, 
        string $description, 
        string $active
    ): void;

    public function permissionsRootByActive(
        string $active
    ): Collection;

    public function findPermissionsByPermissionCode(
        $permissionCode
    ): Collection;

    public function findByCode(
        int $code
    ): ?Permission;

    public function findByNameInPermissionRoot(
        string $name
    ): ?Permission;

    public function findByName(
        string $name, int $permissionCode
    ): ?Permission;

    public function findBySlugInPermissionRoot(
        string $name
    ): ?Permission;

    public function findBySlug(
        string $name, 
        int $permissionCode
    ): ?Permission;
    
    public function findByAbility(
        string $ability
    ): ?Permission;

    public function findByNameNotByThisCode(
        int $code, 
        string $name, 
        int $permissionCode
    ): ?Permission;

    public function findByNameNotByThisCodeInPermissionRoot(
        int $code, 
        string $name
    ): ?Permission;

    public function searchLikeAbility(
        string $ability
    ): Collection;
}
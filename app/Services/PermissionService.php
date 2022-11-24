<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Exceptions\Authorization\Permission\PermissionAlreadyExistsException;
use App\Exceptions\Authorization\Permission\PermissionDeleteException;
use App\Exceptions\Authorization\Permission\PermissionNotExistsException;
use App\Exceptions\Authorization\Permission\PermissionRetrieveException;
use App\Models\Authorization\Permission;
use App\Repositories\Permission\PermissionRepository;
use App\Utils\ValidatorAdapter;
use \Illuminate\Database\Eloquent\Collection;

class PermissionService
{

    const PERMISSION_ABILITY_SEPARATOR = "->";

    public function __construct(
        private PermissionRepository $repository,
    ) {
  
    }

    public function create(
        ?string $name,
        ?string $slug,
        ?string $description,
        ?int $permissionCode
    ): int {

        $this->createDataValidate(
            $name,
            $slug,
            $description,
            $permissionCode
        );

        $isPermissionRoot = is_null($permissionCode);

        if($isPermissionRoot) {
            $code = $this->createPermissionRoot(
                $name,
                $slug,
                $description
            );
        } else {
            $code = $this->createPermission(
                $name,
                $slug,
                $description,
                $permissionCode
            );
        }

        return $code;
    }

    private function createDataValidate(
        ?string $name,
        ?string $slug,
        ?string $description,
        ?int $permissionCode
    ) {
        $data = [
            'PERMISSION_NAME' => $name,
            'PERMISSION_SLUG' => $slug,
            'PERMISSION_DESCRIPTION' => $description,
            'PERMISSION_PERMISSION_CODE' => $permissionCode
        ];

        $rules = [
            'PERMISSION_NAME'=>'required|string|min:1|max:60',
            'PERMISSION_SLUG'=>'nullable|string|min:1|max:300|slug',
            'PERMISSION_DESCRIPTION'=>'required|string|min:1|max:300',
            'PERMITION_PERMISSION_CODE'=>'nullable|numeric|exists:permissions,PERMISSION_CODE',
        ];

        ValidatorAdapter::fields($data,$rules);
    }

    private function createPermissionRoot(
        string $name,
        ?string $slug,
        string $description
    ): int {

        if ($this->repository->findByNameInPermissionRoot($name)) {
            throw PermissionAlreadyExistsException::name($name);
        }

        $slug = $this->generateSlug($name, $slug);

        if ($this->repository->findBySlugInPermissionRoot($slug)) {
            throw PermissionAlreadyExistsException::slug($slug);
        }

        $ability = $slug;

        if ($this->repository->findByAbility($ability)) {
            throw PermissionAlreadyExistsException::ability($ability);
        }

        $code = $this->repository->createPermisionRoot(
            $name,
            $slug,
            $ability,
            $description
        );
       
        return $code;
    }

    private function createPermission(
        string $name,
        ?string $slug,
        string $description,
        int $permissionCode
    ): int {

        if ($this->repository->findByName($name, $permissionCode)) {
            throw PermissionAlreadyExistsException::name($name);
        }

        $slug = $this->generateSlug($name, $slug);

        if ($this->repository->findBySlug($slug, $permissionCode)) {
            throw PermissionAlreadyExistsException::slug($slug);
        }

        $ability = $this->generateAbility($slug, $permissionCode);

        if ($this->repository->findByAbility($ability)) {
            throw PermissionAlreadyExistsException::ability($ability);
        }

        $code = $this->repository->createPermision(
            $name,
            $slug,
            $ability,
            $description,
            $permissionCode
        );
        
        return $code;
    }

    public function delete(
        int $code
    ): void {
        try {
            $permission = $this->findByCode($code);
        } catch(PermissionNotExistsException $exeception) {
            throw PermissionDeleteException::notFound();
        } catch(PermissionRetrieveException $exeception) {
            throw PermissionDeleteException::delete();
        }
        
        if($permission->isLinkedInTables()) {
            throw PermissionDeleteException::linkedInTable();
        }

        $this->repository->delete($code);
    }

    private function generateSlug(
        string $name, 
        ?string $slug
    ): string {
        if (empty($slug)) {
            return Str::slug($name);
        }

        return $slug;
    }

    public function findPermissionsByPermissionCode(
        int $permissionCode
    ): Collection {
        return $this->repository->findPermissionsByPermissionCode($permissionCode);
    }

    public function findByCode(
        int $code
    ): Permission {
        $permission = $this->repository->findByCode($code);

        if( ! $permission) {
            throw PermissionNotExistsException::code($code);
        }

        return $permission;
    }

    private function generateAbility(
        string $slug, 
        int $permissionCode
    ): string
    {
        $permission = $this->findByCode($permissionCode);

        $ability = $permission->PERMISSION_ABILITY . static::PERMISSION_ABILITY_SEPARATOR . $slug;

        return $ability;
    }

    public function permissionsRoot(): Collection 
    {
        return $this->repository->permissionsRoot();
    }

    public function searchLikeAbility(
        string $ability
    ): Collection {
        return $this->repository->searchLikeAbility($ability);
    }
    
    public function update(
        ?int $code,
        ?string $name,
        ?string $description,
        ?string $active
    ): void {

        $this->updateDataValidate(
            $code,
            $name,
            $description,
            $active
        );

        $permission = $this->findByCode($code);
         
        if ($permission->isPermissionRoot()) {
            if (
                $this->repository->findByNameNotByThisCodeInPermissionRoot(
                    $code, 
                    $name
                )
            ) {
                throw PermissionAlreadyExistsException::name($name);
            }
        } else {
            if (
                $this->repository->findByNameNotByThisCode(
                    $code, 
                    $name, 
                    $permission->PERMISSION_PERMISSION_CODE
                )
            ) {
                throw PermissionAlreadyExistsException::name($name);
            }
        }

        $this->repository->update(
            $code,
            $name,
            $description,
            $active,
        );
    }

    private function updateDataValidate(
        ?int $code,
        ?string $name,
        ?string $description,
        ?string $active
    ): void {

        $data = [
            'PERMISSION_CODE' => $code,
            'PERMISSION_NAME' => $name,
            'PERMISSION_DESCRIPTION'=> $description,
            'PERMISSION_ACTIVE' => $active,
        ];

        $rules = [
            'PERMISSION_CODE' => 'required|numeric',
            'PERMISSION_NAME' => 'required|string|min:1|max:60',
            'PERMISSION_DESCRIPTION' => 'required|string|min:1|max:300',
            'PERMISSION_ACTIVE'=> 'required|ends_with:'.\implode(',',Permission::ACTIVE_OPTIONS),
        ];

        ValidatorAdapter::fields($data,$rules);
    }
}

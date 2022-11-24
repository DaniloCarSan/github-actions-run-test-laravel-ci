<?php

namespace Tests\Feature\App\Services;

use App\Repositories\Permission\PermissionRepositoryImp;
use App\Services\PermissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class PermissionServiceTest extends TestCase
{

    use RefreshDatabase;
    
    public function test_create_permission_root_valid()
    {
        $repository = new PermissionRepositoryImp();
        $service = new PermissionService($repository);

        $name = "v1";
        $slug = null;
        $description = "First version of permissions";
        $permissionParent = null;

        $actualPermissionCode = $service->create(
            $name,
            $slug,
            $description,
            $permissionParent
        );

        $expectedPermissionCode = 1;

        $this->assertEquals(
            $expectedPermissionCode,
            $actualPermissionCode
        );
    }

    public function test_create_permission_root_already_exists()
    {
        $repository = new PermissionRepositoryImp();
        $service = new PermissionService($repository);

        $name = "v1";
        $slug = null;
        $description = "First version of permissions";
        $permissionParent = null;

        $service->create(
            $name,
            $slug,
            $description,
            $permissionParent
        );
        
        $this->expectException(\App\Exceptions\Authorization\Permission\PermissionAlreadyExistsException::class);

        $service->create(
            $name,
            $slug,
            $description,
            $permissionParent
        );
        
    }

}

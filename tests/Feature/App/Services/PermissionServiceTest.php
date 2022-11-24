<?php

namespace Tests\Feature;

use App\Repositories\Permission\PermissionRepositoryImp;
use App\Services\PermissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class PermissionServiceTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
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


}

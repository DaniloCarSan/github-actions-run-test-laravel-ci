<?php

namespace App\Models\Authorization;

use App\Traits\VerifyLinkedInTables;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory, VerifyLinkedInTables;

    protected $primaryKey = 'PERMISSION_CODE';

    public $timestamps = false;

    const ACTIVE_Y ="Y";
    const ACTIVE_N ="N";

    const ACTIVE_LABELS = [
        Permission::ACTIVE_Y => "Sim",
        Permission::ACTIVE_N => "Não",
    ];

    const ACTIVE_OPTIONS = [
        Permission::ACTIVE_Y,
        Permission::ACTIVE_N
    ];

    private function getTablePrimaryKeyLinked () {
        return $this->PERMISSION_CODE;
    }

    public $tablesLinked = [
        ['permissions','PERMISSION_PERMISSION_CODE'],
        ['roles','ROLE_PERMISSION_CODE'],
        ['role_permissions','ROLPER_PERMISSION_CODE'],
    ];

    public function getLabelActive()
    {
        return Permission::ACTIVE_LABELS[$this->PERMISSION_ACTIVE];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'PERMISSION_NAME',
        'PERMISSION_SLUG',
        'PERMISSION_ABILITY',
        'PERMISSION_ACTIVE',
        'PERMISSION_DESCRIPTION',
        'PERMISSION_PERMISSION_CODE',
    ];

    public function isPermissionRoot():bool {
        return is_null($this->PERMISSION_PERMISSION_CODE);
    }

}

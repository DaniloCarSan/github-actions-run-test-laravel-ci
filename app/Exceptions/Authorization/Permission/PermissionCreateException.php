<?php

namespace App\Exceptions\Authorization\Permission;

use App\Exceptions\CustomException;
use Illuminate\Http\Response;

class PermissionCreateException extends CustomException
{
    public static function create()
    {
        return new static("Erro ao criar permissão",Response::HTTP_BAD_REQUEST);
    }
}

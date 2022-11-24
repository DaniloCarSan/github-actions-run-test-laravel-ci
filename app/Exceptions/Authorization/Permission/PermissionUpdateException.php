<?php

namespace App\Exceptions\Authorization\Permission;

use App\Exceptions\CustomException;
use Illuminate\Http\Response;

class PermissionUpdateException extends CustomException
{
    public static function update()
    {
        return new static("Erro ao salvar alterações", Response::HTTP_BAD_REQUEST);
    }
}
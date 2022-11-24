<?php

namespace App\Exceptions\Authorization\Permission;

use App\Exceptions\CustomException;
use Illuminate\Http\Response;

class PermissionNotExistsException extends CustomException
{
    public static function code(string $code)
    {
        return new static("Permissão com o código: '".$code."' não existe",Response::HTTP_NOT_FOUND);
    }
}
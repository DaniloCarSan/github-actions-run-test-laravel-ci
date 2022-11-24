<?php

namespace App\Exceptions\Authorization\Permission;

use App\Exceptions\CustomException;
use Illuminate\Http\Response;

class PermissionAlreadyExistsException extends CustomException
{
    public static function name(string $name)
    {
        return new static("Permissão com o nome: '".$name."' já existe",Response::HTTP_CONFLICT);
    }

    public static function slug(string $slug)
    {
        return new static("Permissão com o slug: '".$slug."' já existe",Response::HTTP_CONFLICT);
    }

    public static function ability(string $ability)
    {
        return new static("Permissão com o abilidade: '".$ability."' já existe",Response::HTTP_CONFLICT);
    }
}
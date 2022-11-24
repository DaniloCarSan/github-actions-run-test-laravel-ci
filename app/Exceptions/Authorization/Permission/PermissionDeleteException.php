<?php

namespace App\Exceptions\Authorization\Permission;

use App\Exceptions\CustomException;
use Illuminate\Http\Response;

class PermissionDeleteException extends CustomException
{
    public static function delete()
    {
        return new static("Erro ao excluir permissão",Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public static function notFound()
    {
        return new static("Erro ao excluir permissão pois a mesma não foi encontrada",Response::HTTP_NOT_FOUND);
    }

    public static function linkedInTable()
    {
        return new static("Erro ao excluir permissão pois a mesma se encontra em uso",Response::HTTP_BAD_REQUEST);
    }
}

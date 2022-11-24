<?php

namespace App\Exceptions\Authorization\Permission;

use App\Exceptions\CustomException;
use Illuminate\Http\Response;

class PermissionRetrieveException extends CustomException
{

    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        return false;
    }

    public static function list()
    {
        return new static("Erro ao recuperar listade de permissões",Response::HTTP_BAD_REQUEST);
    }
    
    public static function select()
    {
        return new static("Erro ao recuperar permissão",Response::HTTP_INTERNAL_SERVER_ERROR);
    }

}
<?php

namespace App\Utils;

use App\Exceptions\ValidatorAdapterException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ValidatorAdapter {

    public static function field( 
        string $fieldName, 
        $fieldValue,
         string $rules, 
         array $messages = []
    ): void {
        $data = [$fieldName => $fieldValue];
        $rules = [ $fieldName => $rules];
        $validator = Validator::make($data, $rules, $messages);

        if($validator->fails()) {
            throw new ValidatorAdapterException(
                $validator->errors()->first($fieldName),
                Response::HTTP_CONFLICT
            );
        }
    }

    public static function fields( 
        array $data, 
        array $rules, 
        array $messages = []
    ): void {
        $validator = Validator::make($data, $rules, $messages);
        
        if($validator->fails()) {
            throw new ValidatorAdapterException(
                "Preencha os campos corretamente",
                Response::HTTP_CONFLICT,
                $validator->errors()->toArray()
            );
        }
    }

}
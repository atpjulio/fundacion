<?php

namespace App\Services;

use Illuminate\Validation\Validator;

class CustomValidationRules extends Validator
{
/*
    public function validateCheckExcelExtension($attribute, $value, $parameters,$messages) {
        //dd($attribute, $value->getClientOriginalExtension(), $parameters,$messages);
        $extension = strtolower($value->getClientOriginalExtension());

        if($extension === "xls" or $extension === "xlsx"){
            return true;
        }
        return false;
    }

    public function validateCustomerPhone($attribute, $value, $parameters,$messages)
    {
        return !Customer::checkPhoneExists($value);
    }

    public function validateCustomerInBlackList($attribute, $value, $parameters,$messages)
    {
        return !BlackList::checkIfExists($value);
    }

    public function validateCustomerEmail($attribute, $value, $parameters,$messages)
    {
        return !Customer::checkEmailExists($value);
    }

    public function validateYoutubeUrl($attribute, $value, $parameters,$messages)
    {
        return !Video::isValid($value);
    }
*/
}

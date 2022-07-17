<?php

namespace ZohoConnect\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AuthorizationRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id'          => "required",
            'secret'      => "required",
            'data_center' => "required",
            'scopes'      => ["required", "array"],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return view("zoho.connection.error");
    }
}

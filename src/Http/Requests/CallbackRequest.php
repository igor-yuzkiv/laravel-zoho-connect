<?php

namespace ZohoConnect\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class CallbackRequest extends FormRequest
{

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function rules()
    {
        return [
            'code'     => "required",
            'location' => ["string", "nullable"],
        ];
    }

    /**
     * @param Validator $validator
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    protected function failedValidation(Validator $validator)
    {
        return view("zoho.auth-driver.error");
    }
}

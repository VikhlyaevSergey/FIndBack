<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fullName' => 'string',
            'phones'   => 'array',
            'phones.*' => 'min:10|regex:/\d+/',

            'emails'   => 'array',
            'emails.*' => 'string|unique:emails,email',
            // 'image' => 'string',

            'places'             => 'array',
            'places.*.name'      => 'required|string',
            'places.*.address'   => 'nullable|string',
            'places.*.latitude'  => 'required|numeric',
            'places.*.longitude' => 'required|numeric',
        ];
    }
}

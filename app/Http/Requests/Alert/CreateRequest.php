<?php

namespace App\Http\Requests\Alert;

use App\Http\Requests\ApiBaseRequest;

class CreateRequest extends ApiBaseRequest
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
            'app_name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'enabled' => 'required|boolean',
            'metric' => 'required',
            'condition' => 'required|max:2',
            'threshold' => 'required|integer',
        ];
    }
}

<?php

namespace App\Http\Requests\Alert;

use App\Http\Requests\ApiBaseRequest;

class UpdateRequest extends ApiBaseRequest
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
            'app_name' => 'sometimes|required',
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'enabled' => 'sometimes|required|boolean',
            'metric' => 'sometimes|required',
            'condition' => 'sometimes|required|max:2',
            'threshold' => 'sometimes|required|integer',
        ];
    }
}

<?php

namespace App\Http\Requests\Metric;

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
            'metricName' => 'sometimes|required',
            'appName' => 'sometimes|required',
            'value' => 'sometimes|required|integer',
        ];
    }
}

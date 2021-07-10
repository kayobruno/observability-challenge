<?php

namespace App\Http\Requests\Alert;

use App\Http\Requests\ApiBaseRequest;
use App\Models\Alert;

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
        $conditions = Alert::getConstantsValuesByPrefix('CONDITION');
        $conditions = implode(',', $conditions);

        return [
            'app_name' => 'sometimes|required',
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'enabled' => 'sometimes|required|boolean',
            'metric' => 'sometimes|required',
            'condition' => "sometimes|required|max:2|in:{$conditions}",
            'threshold' => 'sometimes|required|integer',
        ];
    }
}

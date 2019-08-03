<?php

namespace App\Http\Requests\Marker;

use Illuminate\Foundation\Http\FormRequest;

class CreateMarker extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'address' => 'required',
            'district' => 'required',
            'marker_type_id' => 'required|exists:marker_types,id',
            'lat' => 'required',
            'long' => 'required'
        ];
    }
}

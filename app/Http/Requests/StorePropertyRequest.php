<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
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
            'title' => 'required',
            'country_id' => 'required:min:1',
            'category_id' => 'required|numeric|min:1',
//            'photo_url.*'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048000',
//            'picture'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048000'
//            'photo_url.*'=>'image|mimes:jpeg,png,jpg,gif,svg|max:512',
//            'picture'=>'image|mimes:jpeg,png,jpg,gif,svg|max:512'
        ];
    }
    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'category_id' => 'Debe seleccionar una categoría,'
        ];
    }
}

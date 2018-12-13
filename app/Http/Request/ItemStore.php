<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class ItemStore extends FormRequest
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
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'manufacture_time' => 'required|time',
            'image' => 'nullable|string',
            'category_id' => 'required'
        ];
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() 
    {    
        return [
            'name.required' => 'Item name is required',
            'description.required' => 'Item description is required',
            'price.required' => 'Item price is required',
            'manufacture_time.required' => 'Item manufacture time is required'
        ];
    }
}

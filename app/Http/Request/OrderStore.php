<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class OrderStore extends FormRequest
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
            'status' => 'required|boolean',
            'execution_time' => 'required|time',
            'order' => 'required|integer',
            'user_id' => 'required',
            'vendor_id' => 'required'
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
            'status.required' => 'Order status is required',
            'execution_time.required' => 'Order execution_time is required',
            'order.required' => 'Order order is required'
        ];
    }
}

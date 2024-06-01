<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,phone',
            'service_id' => 'required|exists:services,id',
            'service_fee' => 'required',
            'transport_fee' => 'required',
            'total' => 'required',
            'payment_method' => 'required',
            'status' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ];
    }
}

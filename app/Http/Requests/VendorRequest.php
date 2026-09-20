<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'role' => 'required|in:Admin,Vendor,Customer',
        ];

        // Add vendor-specific validation if role is vendor
        if ($this->role === 'Vendor') {
            $rules['pharmacy_name'] = 'required|string|max:255';
            $rules['license_number'] = 'required|string|unique:vendors,license_number';
            $rules['location'] = 'required|string|max:500';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'pharmacy_name.required' => 'Pharmacy name is required for vendors.',
            'license_number.required' => 'License number is required for vendors.',
            'license_number.unique' => 'This license number is already registered.',
            'location.required' => 'Location is required for vendors.',
        ];
    }
}

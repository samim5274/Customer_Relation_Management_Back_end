<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Personal Info
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:users,email',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'blood_group' => 'nullable|in:O+,O-,A+,A-,B+,B-,AB+,AB-',
            'visaCategory' => 'nullable|exists:visa_categories,id',
            'country' => 'nullable|exists:countries,id',
            'national_id' => 'nullable|string|max:50',
            'religion' => 'nullable|string|max:50',

            // Other Info
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',

            'passport_no' => 'nullable|string|max:50',
            'passport_expiry_date' => 'nullable|date|after:today',

            // Files
            'passport_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nid_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Spouse
            'spouse_name' => 'nullable|string|max:255',
            'spouse_nid' => 'nullable|string|max:50',
            'spouse_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'spouse_nid_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Address
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',

            // Status
            'is_submited' => 'boolean',

            // Profile Photo
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}

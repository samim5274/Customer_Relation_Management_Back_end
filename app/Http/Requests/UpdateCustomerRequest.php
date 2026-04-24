<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
        $customerId = $this->route('id');

        return [
            // Personal Info
            'name'                 => 'required|string|max:191',
            'phone'                => 'required|string|max:20|unique:customers,phone,' . $customerId,
            'email'                => 'nullable|email|max:191|unique:customers,email,' . $customerId,
            'dob'                  => 'nullable|date',
            'gender'               => 'nullable|in:male,female,other',
            'blood_group'          => 'nullable|string|max:10',
            'religion'             => 'nullable|string|max:50',

            // Dropdowns
            'country'              => 'required|exists:countries,id',
            'visaCategory'         => 'required|exists:visa_categories,id',

            // Other Info
            'father_name'          => 'nullable|string|max:191',
            'mother_name'          => 'nullable|string|max:191',
            'occupation'           => 'nullable|string|max:191',
            'national_id'          => 'nullable|string|max:50|unique:customers,national_id,' . $customerId,
            'passport_no'          => 'nullable|string|max:50|unique:customers,passport_no,' . $customerId,
            'passport_expiry_date' => 'nullable|date',

            // Spouse Details
            'spouse_name'          => 'nullable|string|max:191',
            'spouse_nid'           => 'nullable|string|max:50',

            // Address & Status
            'present_address'      => 'nullable|string',
            'permanent_address'    => 'nullable|string',
            'is_submitted'         => 'nullable|in:0,1',

            // Images Validation
            'user_photo'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'passport_photo'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nid_photo'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'spouse_photo'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'spouse_nid_photo'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages(): array
{
    return [
        // Personal Info
        'name.required'         => 'The customer name is required.',
        'phone.required'        => 'The phone number is required.',
        'phone.unique'          => 'This phone number is already in use by another customer.',
        'email.unique'          => 'This email address is already registered.',
        'email.email'           => 'Please enter a valid email address.',

        // Selection Fields
        'country.required'      => 'Please select a country.',
        'country.exists'        => 'The selected country is invalid.',
        'visaCategory.required' => 'Please select a visa category.',
        'visaCategory.exists'   => 'The selected visa category is invalid.',

        // Documents
        'national_id.unique'    => 'This National ID number is already registered.',
        'passport_no.unique'    => 'This passport number is already in the system.',
        'passport_expiry_date.date' => 'Please enter a valid expiry date.',

        // Image Validations
        'user_photo.image'      => 'The photo must be an image file.',
        'user_photo.mimes'      => 'Allowed formats: jpeg, png, jpg.',
        'user_photo.max'        => 'Photo size must not exceed 2MB.',

        'passport_photo.image'  => 'The passport photo must be an image file.',
        'passport_photo.max'    => 'Passport photo size must not exceed 2MB.',

        'nid_photo.image'       => 'The NID photo must be an image file.',
        'nid_photo.max'         => 'NID photo size must not exceed 2MB.',

        'spouse_photo.image'    => 'The spouse photo must be an image file.',
        'spouse_nid_photo.image'=> 'The spouse NID photo must be an image file.',
    ];
}
}

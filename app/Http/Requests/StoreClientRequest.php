<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
            'first_name' => 'required|alpha:ascii|max:255',
            'last_name' => 'required|alpha:ascii|max:255',
            'email' => 'required|string|email:rfc,dns|max:255',
            'phone' => 'required|string', //todo: implement phone input field validation
            // 'country_code' => 'required', //TODO: may be implemented later
            'date_of_birth' => 'required|date|before:today',
            'country' => 'required|not_in:null',
            'country_of_residence' => 'required|not_in:null',
            'consultation_package' => 'required|array|min:1',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'The First Name field is required.',
            'first_name.alpha' => 'The First Name field must contain only letters.',
            'first_name.max' => 'The First Name field cannot exceed 255 characters.',
            'last_name.required' => 'The Last Name field is required.',
            'last_name.alpha' => 'The Last Name field must contain only letters.',
            'last_name.max' => 'The Last Name field is required.',
            'email.required' => 'The Email Address field is required.',
            'email.email' => 'The Email Address field is invalid.',
            // 'email.unique' => 'The email address is already registered.',
            'phone.required' => 'The Phone Number field is required.',
            'phone.phone' => 'Invalid phone number or country mismatch.',
            // 'phone.unique' => 'The phone number is already registered.',
            'date_of_birth.required' => 'The Date of Birth field is required.',
            'date_of_birth.before' => 'The Date of Birth field cannot be in the future',
            'country.required' => 'The Country of Citizenship field is required.',
            'country_of_residence.required' => 'The Country of Residence field is required.',
            'country_code.required' => 'Code required.',
            'consultation_package.required' => 'You must select at least one Consultation Package.',
            'consultation_package.array' => 'Invalid data format.',
            'consultation_package.min' => 'You must select at least one Consultation Package.',

        ];
    }
}

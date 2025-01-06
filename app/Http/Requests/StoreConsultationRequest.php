<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsultationRequest extends FormRequest
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
            'email' => 'required|string|email:rfc,dns|max:255|unique:consultations,email',
            'phone' => 'required|string|unique:consultations,phone', //todo: implement phone input field validation
            'country_code' => 'required',
            'date_of_birth' => 'required|date',
            'country' => 'required|not_in:null',
            'country_of_residence' => 'required|not_in:null',
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
            'first_name.required' => 'The First Name field is required',
            'first_name.alpha' => 'The First Name field must contain only letters',
            'first_name.max' => 'The First Name field cannot exceed 255 characters',
            'last_name.required' => 'The Last Name field is required',
            'last_name.alpha' => 'The Last Name field must contain only letters',
            'last_name.max' => 'The Last Name field is required',
            'email.required' => 'The Email Address field is required',
            'email.email' => 'The Email Address field is invalid',
            'email.unique' => 'The email address is already registered',
            'phone.required' => 'The Phone Number field is required',
            'phone.phone' => 'Invalid phone number or country mismatch',
            'phone.unique' => 'The phone number is already registered',
            'country_code.required' => 'Code required',

        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRegisterRequest extends FormRequest
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:companies,email',
            'password' => 'required|string|min:6|confirmed',
            'industry' => 'nullable|string|max:255',
            'website'  => 'nullable|url',
            'location' => 'nullable|string|max:255',
            'logo'     => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}

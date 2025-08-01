<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s.,!?()\'"-]+$/',
            'description' => 'nullable|string|regex:/^[a-zA-Z0-9\s.,!?()\'"-]+$/',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The group name is required.',
            'name.string' => 'The group name must be a string.',
            'name.max' => 'The group name may not be greater than 255 characters.',
            'description.string' => 'The description must be a string.',
            'users.array' => 'The users field must be an array.',
            'users.*.exists' => 'The selected user does not exist.',
        ];
    }
}

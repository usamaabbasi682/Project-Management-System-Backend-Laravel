<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone' => 'nullable|numeric',
            'email' => 'required|email|unique:users,email,' . $this->user,
            'password' => 'nullable|string|min:8',
            'password_confirmation'=> 'nullable|same:password',
            'projects' => 'nullable|array',
            'role' => 'required|string',
            'status' => 'required|in:1,0',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg',
            'salary' => 'nullable|numeric',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors(),
            'data' => []
        ]));
    }
}

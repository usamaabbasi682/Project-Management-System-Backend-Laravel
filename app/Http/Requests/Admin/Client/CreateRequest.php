<?php

namespace App\Http\Requests\Admin\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateRequest extends FormRequest
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
            'department' => 'required|integer|exists:departments,id',
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'website' => 'nullable|url|max:100',
            'client_panel' => 'required|boolean',
            'password' => 'required_if:client_panel,1',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg',
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

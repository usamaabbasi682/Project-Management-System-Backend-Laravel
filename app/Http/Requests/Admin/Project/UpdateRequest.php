<?php

namespace App\Http\Requests\Admin\Project;

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
            'client_id' => 'required|exists:users,id',
            'name' => 'required|string|max:100',
            'prefix' => 'required|string|max:100',
            'color' => 'required|string',
            'budget' => 'required|string',
            'budget_type' => 'required|in:fixed,hourly',
            'currency' => 'required|in:USD,EUR,GBP,INR,AUD,CAD',
            'description' => 'nullable|string',
            'status' => 'required|in:archived,finished,ongoing,onhold',
            'users' => 'required|array',
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

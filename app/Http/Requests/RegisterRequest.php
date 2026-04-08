<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'birthdate' => 'required|date',
            'gender' => 'required|string|in:male,female,other',
            'weight' => 'required|numeric|between:1,500',
            'height' => 'required|integer|between:1,300',
            'body_fat_pct' => 'required|integer|between:1,100',
            'constraints' => 'required|array',
            'physical_activity_level' => 'required|string',
            'daily_caloric_intake' => 'required|integer',
            'goal' => 'required|string|max:500',
        ];
    }
}

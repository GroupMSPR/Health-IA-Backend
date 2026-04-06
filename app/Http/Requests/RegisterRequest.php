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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'age' => 'required|integer|between:1,130',
            'gender' => 'required|string|in:male,female,other',
            'weight' => 'required|numeric|between:1,500',
            'height' => 'required|integer|between:1,300',
            'body_fat_pct' => 'required|integer|between:1,100',
            'disease_type' => 'required|string',
            'severity' => 'required|string',
            'physical_activity_level' => 'required|string',
            'daily_caloric_intake' => 'required|integer',
            'goal' => 'required|string|max:500',
        ];
    }
}

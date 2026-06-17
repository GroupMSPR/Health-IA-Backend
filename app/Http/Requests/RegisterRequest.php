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
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'birthdate' => 'required|date',
            'gender' => 'required|string|in:Homme,Femme,Autres',
            'weight' => 'required|numeric|between:1,500',
            'height' => 'required|integer|between:1,300',
            'body_fat_pct' => 'required|integer|between:1,100',
            'physical_activity_level' => 'required|string|in:sedentary,moderate,active',
            'daily_caloric_intake' => 'required|integer|min:1000',
            'favorite_exercise_category' => 'required|string|in:Musculation,Cardio,Poids du corps',
        ];
    }
}

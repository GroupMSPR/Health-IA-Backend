<?php

namespace App\Http\Requests;

use App\Enums\ActivityLevel;
use App\Enums\ExerciseCategory;
use App\Enums\Gender;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'gender' => ['required', Rule::enum(Gender::class)],
            'weight' => 'required|numeric|between:1,500',
            'height' => 'required|integer|between:1,300',
            'body_fat_pct' => 'required|integer|between:1,100',
            'physical_activity_level' => ['required', Rule::enum(ActivityLevel::class)],
            'daily_caloric_intake' => 'required|integer|min:1000',
            'favorite_exercise_category' => ['required', Rule::enum(ExerciseCategory::class)],
        ];
    }
}

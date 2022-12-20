<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\UserGender;
use App\Enums\UserType;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'unique:users', 'min:3', 'max:30'],
            'email' => ['required', 'email'],
            // 'type' => ['required', new Enum(UserType::class)],
            'gender' => ['required', new Enum(UserGender::class)],
            'birthDate' => ['required', 'date'],
            'bio' => ['nullable', 'string'],
            'password' => ['required', 'string', 'confirmed', 'min:6'],
        ];
    }
}

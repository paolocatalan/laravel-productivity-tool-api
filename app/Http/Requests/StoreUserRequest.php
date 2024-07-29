<?php

namespace App\Http\Requests;

use App\Enums\UserRolesEnums;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:users', 'email:strict,rfc,dns'],
            'role' => ['required', Rule::in(
                UserRolesEnums::ADMIN->value,
                UserRolesEnums::MANAGER->value,
                UserRolesEnums::USER->value,
            )],
            'password' => ['required', 'confirmed', Password::defaults()]
        ];
    }
}

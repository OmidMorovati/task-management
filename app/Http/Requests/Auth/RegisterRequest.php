<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * @property string name
 * @property string email
 * @property string password
 */
class RegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name'     => ['required', 'string'],
            'email'    => ['required', Rule::unique(User::class, 'email')],
            'password' => ['required', 'string', Password::min(8)],
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Helpers\ValidationHelper;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
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
        $rules = User::getRules();

        $teacher = $this->route('teacher');
        $id = $teacher->getKey();

        // Make each field optional by modifying the rules
        $rules = ValidationHelper::makeRulesOptional($rules);
        $rules['email'] = [
            'string',
            'email',
            'max:' . User::$MAX_EMAIL_LENGTH,
            Rule::unique('users')->ignore($id),
        ];
        return $rules;

    }

    public function validated($key = null, $default = null)
    {
        if ($key === 'password') {
            return null;
        }
        return parent::validated($key, $default);
    }
}

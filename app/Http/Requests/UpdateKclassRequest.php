<?php

namespace App\Http\Requests;

use App\Helpers\ValidationHelper;
use App\Models\Major;
use Illuminate\Foundation\Http\FormRequest;

class UpdateKclassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = Major::getRules();

        $rules = ValidationHelper::makeRulesOptional($rules);

        return $rules;
    }
}

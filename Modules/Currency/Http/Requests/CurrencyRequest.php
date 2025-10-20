<?php

namespace Modules\Currency\Http\Requests;

use Modules\Currency\Enums\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CurrencyRequest extends FormRequest
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
        $rules = [
            'symbol' => 'required|string|max:50',
        ];

        $languages = Language::cases();

        foreach ($languages as $language) {
            $rules[$language->name] = "required|array";
            $rules[$language->name . '.name'] = "required|string|max:100";
        }

        if ($this->get('currency_id'))
            $rules['code'] = ['required', 'string', Rule::unique('currencies', 'code')->ignore($this->get('currency_id')), 'size:3'];
        else
            $rules['code'] = 'required|string|unique:currencies,code|size:3';


        return $rules;
    }
}

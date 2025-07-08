<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LabelRequest extends FormRequest
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
        $label = $this->route('label');
        $labelId = $label && is_object($label) && array_key_exists('id', $label->getAttributes()) ? ',' . $label->id : '';

        return [
            'name' => 'required|max:255|unique:labels,name' . $labelId,
            'description' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('app.validation.required', ['field' => __('app.fields.name')]),
            'name.unique' => __('app.validation.unique', ['field' => __('app.fields.name')]),
        ];
    }
}

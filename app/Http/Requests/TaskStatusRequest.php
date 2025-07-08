<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStatusRequest extends FormRequest
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
        $status = $this->route('task_statuses');
        $statusId = $status && is_object($status) && property_exists($status, 'id') ? ',' . $status->id : '';

        return [
            'name' => 'required|max:255|unique:task_statuses,name' . $statusId,
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

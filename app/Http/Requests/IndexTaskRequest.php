<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexTaskRequest extends FormRequest
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
            'filter.status_id' => [
                'nullable',
                'integer',
                'exists:task_statuses,id'
            ],
            'filter.created_by_id' => [
                'nullable',
                'integer',
                'exists:users,id'
            ],
            'filter.assigned_to_id' => [
                'nullable',
                'integer',
                'exists:users,id'
            ],
            'sort' => [
                'nullable',
                'string',
                'in:id,name,status_id,created_by_id,assigned_to_id'
            ],
        ];
    }
}

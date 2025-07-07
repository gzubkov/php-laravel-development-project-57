<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskStatusRequest extends FormRequest
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
        
        return [
            'name' => 'required|max:255|unique:task_statuses,name,{$this->id}',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название статуса обязательно',
            'name.unique' => 'Статус с таким названием уже существует',
        ];
    }
}

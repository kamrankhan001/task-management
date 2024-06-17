<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:200|unique:tasks,title,' . $this->route('task'),
            'description' => 'nullable|string',
            'due_date' => 'required|date|after_or_equal:today',
            'priority' => 'in:high,medium,low',
            'status' => 'in:pending,review,complete',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'nullable|exists:users,id',
            'group_id' => 'nullable|exists:groups,id',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Please enter a title for your task.',
            'title.string' => 'The title must be text characters only.',
            'title.max' => 'The title cannot be more than 200 characters long.',
            'title.unique' => 'This task title already exists. Please choose a unique title.',
            'description.nullable' => 'The description is optional.',
            'description.string' => 'The description must be text characters only (if provided).',
            'due_date.required' => 'A due date is required for the task.',
            'due_date.date' => 'The due date must be a valid date format (e.g., YYYY-MM-DD).',
            'due_date.after' => 'The due date must be a future date. Please select a date on or after today.',
            'priority.in' => 'The priority must be one of: high, medium, or low.',
            'status.in' => 'The status must be one of: pending, review, or complete.',
            'category_id.required' => 'Please select a category for the task.',
            'category_id.exists' => 'The selected category does not exist. Please choose a valid category.',
            'user_id.nullable' => 'The user field is optional.',
            'user_id.exists' => 'The selected user does not exist. Please choose a valid user (if provided).',
            'group_id.nullable' => 'The group field is optional.',
            'group_id.exists' => 'The selected group does not exist. Please choose a valid group (if provided).',
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Enums\TaskPriorityEnums;
use App\Models\Subtask;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreSubtaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        if (request()->isMethod('post')) {
            return Gate::allows('createSubtask', Subtask::class);
        } else {
            return Gate::allows('updateSubtask', $this->subtask);
        }

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'assignee' => [$this->isPostRequest(), Rule::exists('users', 'name')->where('role', 'User')],
            'name' => [$this->isPostRequest(), 'max:255'],
            'description' => [$this->isPostRequest()],
            'priority' => [$this->isPostRequest(), Rule::in(array_column(TaskPriorityEnums::cases(), 'value'))]
        ];
    }

    public function messages(): array
    {
        return [
            'assignee' => 'Please ensure the name matches a current team member.',
            'priority' => 'Please select an option from the dropdown list.'
        ];
    }

    public function validated($key = null, $default = null)
    {
        if (request()->isMethod('post')) {
            return array_merge(parent::validated(), ['task_id' => $this->task->id, 'user_id' => $this->getUserId($this->assignee)]);
        }

        return parent::validated();
    }

    private function getUserId($name): int
    {
        $id = User::where('name', $name)->first()->id;

        return $id;
    }

    private function isPostRequest()
    {
        return request()->isMethod('post') ? 'required' : 'sometimes';
    }
}

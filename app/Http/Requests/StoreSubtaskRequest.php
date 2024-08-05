<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class StoreSubtaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    // look into passedValidation with merge method
    protected function prepareForValidation(): void
    {
        $userId = (request()->isMethod('post')) ? $this->getUserId($this->assignee) : $this->task->user->id;
        $this->merge(['task_id' => $this->task->id, 'user_id' => $userId]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'task_id' => ['required'],
            'user_id' => ['required'],
            'assignee' => [
                $this->isPostRequest(),
                Rule::exists('users', 'name')->where('role', 'User'),
            ],
            'name' => [$this->isPostRequest(), 'max:255'],
            'description' => [$this->isPostRequest()],
            'priority' => [$this->isPostRequest()]
        ];
    }

    public function messages(): array
    {
        return [
            'assignee' => 'Please ensure the name matches a current team member.'
        ];
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

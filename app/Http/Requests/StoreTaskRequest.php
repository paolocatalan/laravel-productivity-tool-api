<?php

namespace App\Http\Requests;

use App\Enums\TaskStagesEnums;
use App\Models\v1\Project;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTaskRequest extends FormRequest
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
        $projecId = (request()->isMethod('post')) ? $this->getProjectId($this->project) : $this->task->project->id;
        $this->merge(['user_id' => Auth::id(), 'project_id' => $projecId]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required'],
            'project_id' => ['required'],
            'project' => [$this->isPostRequest(), Rule::exists('projects', 'title')],
            'name' => [$this->isPostRequest(), 'max:255'],
            'description' => [$this->isPostRequest()],
            'due_date' =>  [$this->isPostRequest(), 'date', 'date_format:Y-m-d H:s:i', 'after:now'],
            'priority' => [$this->isPostRequest()],
            'stage' => [$this->isPostRequest(), Rule::in(array_column(TaskStagesEnums::cases(), 'value'))]
        ];
    }

    public function messages(): array
    {
        return [
            'due_date' => 'Please ensure the date is beyond today\'s date to proceed.',
            'stage' => 'Please select an option from the dropdown list.'
        ];
    }

    private function getProjectId($title): int
    {
        $id = Project::where('title', $title)->first()->id;
        
        return $id;
    }

    private function isPostRequest()
    {
        return request()->isMethod('post') ? 'required' : 'sometimes';
    }
}

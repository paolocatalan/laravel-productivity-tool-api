<?php

namespace App\Http\Requests;

use App\Enums\TaskStagesEnums;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'name' => [$this->isPostRequest(), 'max:255'],
            'description' => [$this->isPostRequest()],
            'due_date' =>  [$this->isPostRequest(), 'date', 'date_format:Y-m-d H:s:i', 'after:now'],
            'priority' => [$this->isPostRequest()],
            'stage' => [$this->isPostRequest(), Rule::in(
                    TaskStagesEnums::NOT_STARTED->value,
                    TaskStagesEnums::IN_PROGRESS->value,
                    TaskStagesEnums::COMPLETED->value
                )]
        ];
    }

    public function messages(): array
    {
        return [
            'due_date' => 'Please ensure the date is beyond today\'s date to proceed.',
            'stage' => 'Please select an option from the dropdown list.'
        ];
    }

    private function isPostRequest()
    {
        return request()->isMethod('post') ? 'required' : 'sometimes';
    }
}

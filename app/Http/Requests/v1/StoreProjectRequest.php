<?php

namespace App\Http\Requests\v1;

use App\Models\v1\Project;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return Gate::allows('manageProject', Project::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [$this->isPostRequest(), 'max:255'],
            'description' => [$this->isPostRequest()],
            'objective' => [$this->isPostRequest()],
            'start_date' => [$this->isPostRequest(), 'date', 'date_format:Y-m-d H:s:i', 'after:now'],
            'end_date' => [$this->isPostRequest(), 'date', 'date_format:Y-m-d H:s:i', 'after:now']
        ];
    }

    public function validated($key = null, $default = null)
    {
        return array_merge(parent::validated(), ['user_id' => Auth::id()]);
    }

    private function isPostRequest()
    {
        return request()->isMethod('post') ? 'required' : 'sometimes';
    }
}

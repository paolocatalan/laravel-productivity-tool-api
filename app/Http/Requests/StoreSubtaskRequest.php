<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
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
            'priority' => [$this->isPostRequest()]
        ];
    }

    private function isPostRequest()
    {
        return request()->isMethod('post') ? 'required' : 'sometimes';
    }
}

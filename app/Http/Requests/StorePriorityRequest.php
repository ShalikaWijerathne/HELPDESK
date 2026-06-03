<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePriorityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $priorityId = $this->route('priority')?->id;

        return [
            'name' => ['required', 'string', 'max:50',
                       Rule::unique('priorities', 'name')->ignore($priorityId)],
            'rank' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        // Ignore the current category's own name when updating (avoids "duplicate" false positive)
        $categoryId = $this->route('category')?->id;

        return [
            'name'      => ['required', 'string', 'max:100',
                            Rule::unique('categories', 'name')->ignore($categoryId)],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}

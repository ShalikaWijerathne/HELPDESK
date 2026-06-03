<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKbArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isStaffOrAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'body'        => ['required', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'is_published'=> ['nullable', 'boolean'],
        ];
    }
}

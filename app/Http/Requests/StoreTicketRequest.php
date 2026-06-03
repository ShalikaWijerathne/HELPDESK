<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'subject'      => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string', 'max:10000'],
            'category_id'  => ['required', 'exists:categories,id'],
            'priority_id'  => ['required', 'exists:priorities,id'],
            'requester_id' => ['nullable', 'exists:users,id'],  // staff-only field
            // Attachment is optional but must be a safe file type under 10 MB
            'attachment'   => [
                'nullable',
                'file',
                'max:10240',  // 10 MB
                'mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt,zip',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'attachment.mimes' => 'Allowed file types: jpg, png, gif, pdf, doc, docx, xls, xlsx, txt, zip.',
            'attachment.max'   => 'The attachment must not exceed 10 MB.',
        ];
    }
}

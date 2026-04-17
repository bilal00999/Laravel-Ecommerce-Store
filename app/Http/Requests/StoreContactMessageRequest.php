<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only authenticated users can submit contact form
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'subject' => [
                'required',
                'string',
                'min:5',
                'max:255',
            ],
            'message' => [
                'required',
                'string',
                'min:20',
                'max:5000',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^[+]?[(]?[0-9]{3}[)]?[-\s.]?[0-9]{3}[-\s.]?[0-9]{4,6}$/i',
                'max:20',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'subject.required' => 'Please provide a subject for your message.',
            'subject.min' => 'Subject must be at least 5 characters long.',
            'subject.max' => 'Subject cannot exceed 255 characters.',
            
            'message.required' => 'Please provide a message.',
            'message.min' => 'Message must be at least 20 characters long.',
            'message.max' => 'Message cannot exceed 5000 characters.',
            
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            
            'phone.regex' => 'Please provide a valid phone number.',
        ];
    }
}

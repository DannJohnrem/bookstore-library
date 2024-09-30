<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'book_name' => 'required|string|max:255|unique:books,book_name',
            'author' => 'required|string|max:255|unique:books,book_name',
            'book_cover' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}

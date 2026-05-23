<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 3-6 で Policy に置き換え
    }

    public function rules(): array
    {
        return [
            'name'         => ['required','string','max:100'],
            'price'        => ['required','integer','min:0','max:10000000'],
            'description'  => ['nullable','string'],
            'published_at' => ['nullable','date','before_or_equal:today'],
        ];
    }
}
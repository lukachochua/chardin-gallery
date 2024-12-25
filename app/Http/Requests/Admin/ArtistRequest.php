<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ArtistRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'biography' => 'nullable',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'website' => 'nullable|url',
            'profile_image' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ];
    }
}
<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ArtworkRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'artist_id' => 'required|exists:artists,id',
            'categories' => 'array',
            'tags' => 'array',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'dimensions' => 'nullable|string',
            'medium' => 'nullable|string',
            'year_created' => 'nullable|integer',
            'is_available' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id'
        ];
    }
}

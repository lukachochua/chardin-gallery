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
            'title' => 'required|max:255',
            'artist_id' => 'required|exists:artists,id',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'dimensions' => 'nullable|string',
            'medium' => 'nullable|string',
            'year_created' => 'nullable|integer|min:1800|max:' . date('Y'),
            'is_available' => 'required|boolean', 
            'is_featured' => 'required|boolean',  
            'stock' => 'required|integer|min:0',
            'image' => 'sometimes|image|max:5120',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id'
        ];
    }
}

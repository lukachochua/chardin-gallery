<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    protected $category;

    public function __construct($category = null)
    {
        $this->category = $category;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name,' . ($this->category ? $this->category->id : ''),
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ];
    }

    public function authorize()
    {
        return true; 
    }
}

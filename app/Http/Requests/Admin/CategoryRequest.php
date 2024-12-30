<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(function ($query) {
                    $query->where('parent_id', $this->input('parent_id'));
                })->ignore($this->category ? $this->category->id : null),
            ],
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ];
    }

    public function authorize()
    {
        return true;
    }
}

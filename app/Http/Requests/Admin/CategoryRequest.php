<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function rules()
    {
        // Get the current category from the route
        $category = $this->route('category');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')
                    ->where(function ($query) {
                        return $query->where('parent_id', $this->input('parent_id'));
                    })
                    ->ignore($category), 
            ],
            'description' => 'nullable|string',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) use ($category) {
                    if ($value && $category && $value == $category->id) {
                        $fail('A category cannot be its own parent.');
                    }
                },
            ],
        ];
    }

    public function authorize()
    {
        return true;
    }
}

<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * Create a new category.
     */
    public function create(array $data)
    {
        $data['slug'] = Str::slug($data['name']);
        return Category::create($data);
    }

    /**
     * Update an existing category.
     */
    public function update(Category $category, array $data)
    {
        $data['slug'] = Str::slug($data['name']);
        $category->update($data);
        return $category;
    }

    /**
     * Delete a category.
     */
    public function delete(Category $category)
    {
        $category->delete();
    }
}

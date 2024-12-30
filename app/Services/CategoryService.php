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
        // Ensure a category cannot be its own parent
        if (isset($data['parent_id']) && $data['parent_id'] == $category->id) {
            throw new \InvalidArgumentException("A category cannot be its own parent.");
        }

        // Check if the name is unique within the new parent category
        if (isset($data['name'])) {
            $existingCategory = Category::where('name', $data['name'])
                ->where('parent_id', $data['parent_id'] ?? $category->parent_id)
                ->where('id', '!=', $category->id)
                ->first();

            if ($existingCategory) {
                throw new \InvalidArgumentException("The name is already taken within the selected parent category.");
            }
        }

        // Update the category
        $data['slug'] = Str::slug($data['name']);
        $category->update($data);

        // If the parent_id is changed, move the category to the new parent
        if (array_key_exists('parent_id', $data)) {
            if ($data['parent_id']) {
                $parent = Category::find($data['parent_id']);
                if ($parent) {
                    $category->appendToNode($parent)->save();
                }
            } else {
                // If parent_id is null, make the category a root category
                $category->makeRoot()->save();
            }
        }

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

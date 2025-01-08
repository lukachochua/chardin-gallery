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
        $category = new Category([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
        ]);

        if (!empty($data['parent_id'])) {
            $parent = Category::findOrFail($data['parent_id']);
            $category->parent_id = $parent->id;
            $category->save();
            $category->appendToNode($parent)->save();
        } else {
            // Explicitly save as root
            $category->saveAsRoot();
        }

        return $category;
    }

    /**
     * Update an existing category.
     */
    public function update(Category $category, array $data)
    {
        $category->fill([
            'name' => $data['name'],
            'description' => $data['description'] ?? $category->description,
            'slug' => Str::slug($data['name'])
        ]);

        if (array_key_exists('parent_id', $data)) {
            if ($data['parent_id'] === null) {
                if (!$category->isRoot()) {
                    $category->saveAsRoot();
                }
            } else {
                $newParent = Category::findOrFail($data['parent_id']);
                if ($category->parent_id != $data['parent_id']) {
                    $category->parent_id = $newParent->id;
                    $category->save();
                    $category->appendToNode($newParent)->save();
                }
            }
        }

        $category->save();
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

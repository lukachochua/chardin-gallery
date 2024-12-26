<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Support\Str;

class TagService
{
    /**
     * Create a new tag.
     */
    public function create(array $data)
    {
        $data['slug'] = Str::slug($data['name']);
        return Tag::create($data);
    }

    /**
     * Update an existing tag.
     */
    public function update(Tag $tag, array $data)
    {
        $data['slug'] = Str::slug($data['name']);
        $tag->update($data);
        return $tag;
    }

    /**
     * Delete a tag.
     */
    public function delete(Tag $tag)
    {
        $tag->delete();
    }
}

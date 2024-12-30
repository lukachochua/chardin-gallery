<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test admin user
        $admin = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // Make sure to use a hashed password
            'is_admin' => true,
        ]);

        // Create some artists
        $artist1 = Artist::create([
            'name' => 'Artist One',
            'slug' => 'artist-one',
            'biography' => 'Biography of Artist One',
            'email' => 'artist1@example.com',
            'is_active' => true
        ]);

        $artist2 = Artist::create([
            'name' => 'Artist Two',
            'slug' => 'artist-two',
            'biography' => 'Biography of Artist Two',
            'email' => 'artist2@example.com',
            'is_active' => true
        ]);

        // Create parent category
        $category1 = Category::create(['name' => 'Painting', 'slug' => 'painting']);

        // Create child categories under 'Painting'
        $abstractCategory = Category::create([
            'name' => 'Abstract',
            'slug' => 'abstract',
            'parent_id' => $category1->id, // Explicitly set parent_id
        ]);

        $realismCategory = Category::create([
            'name' => 'Realism',
            'slug' => 'realism',
            'parent_id' => $category1->id, // Explicitly set parent_id
        ]);


        // Create another parent category
        $category2 = Category::create(['name' => 'Sculpture', 'slug' => 'sculpture']);

        // Create tags
        $tag1 = Tag::create(['name' => 'Modern', 'slug' => 'modern']);
        $tag2 = Tag::create(['name' => 'Classic', 'slug' => 'classic']);
        $tag3 = Tag::create(['name' => 'Abstract', 'slug' => 'abstract']);

        // Create artworks and associate them with artists, categories, and tags
        $artwork1 = Artwork::create([
            'artist_id' => $artist1->id,
            'title' => 'Artwork One',
            'slug' => 'artwork-one',
            'description' => 'Description of Artwork One',
            'price' => 1500.00,
            'dimensions' => '30x40 cm',
            'medium' => 'Oil on Canvas',
            'year_created' => 2020,
            'is_available' => true,
            'is_featured' => true,
            'stock' => 1,
        ]);

        // Attach tags and categories to the first artwork
        $artwork1->tags()->attach([$tag1->id, $tag2->id]); // Tagging with 'Modern' and 'Classic'
        $artwork1->categories()->attach([$category1->id, $abstractCategory->id]); // Categorizing under 'Painting' and 'Abstract'

        // Create more artworks for different artists
        $artwork2 = Artwork::create([
            'artist_id' => $artist2->id,
            'title' => 'Artwork Two',
            'slug' => 'artwork-two',
            'description' => 'Description of Artwork Two',
            'price' => 2000.00,
            'dimensions' => '40x60 cm',
            'medium' => 'Sculpture',
            'year_created' => 2018,
            'is_available' => true,
            'is_featured' => false,
            'stock' => 2,
        ]);

        // Attach tags and categories to the second artwork
        $artwork2->tags()->attach([$tag2->id]); // Tagging with 'Classic'
        $artwork2->categories()->attach([$category2->id]); // Categorizing under 'Sculpture'
    }
}

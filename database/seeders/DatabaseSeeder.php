<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Exhibition;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure storage directories exist and are created recursively
        Storage::deleteDirectory('public/artworks');
        Storage::deleteDirectory('public/artists');
        Storage::deleteDirectory('public/exhibitions');  // Add directory for exhibitions
        Storage::makeDirectory('public/artworks', 0755, true);
        Storage::makeDirectory('public/artists', 0755, true);
        Storage::makeDirectory('public/exhibitions', 0755, true);  // Create directory for exhibitions

        // Create physical directories just to be sure
        if (!is_dir(storage_path('app/public/artworks'))) {
            mkdir(storage_path('app/public/artworks'), 0755, true);
        }
        if (!is_dir(storage_path('app/public/artists'))) {
            mkdir(storage_path('app/public/artists'), 0755, true);
        }
        if (!is_dir(storage_path('app/public/exhibitions'))) {  // Check for exhibitions directory
            mkdir(storage_path('app/public/exhibitions'), 0755, true);
        }

        $admin = User::create([
            'name' => 'luka',
            'is_admin' => true,
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $users = User::factory()->count(5)->create();

        // Seed Categories with Nested Structure
        $this->seedCategories();

        // Seed Tags
        $tags = $this->seedTags();

        // Seed Artists
        $artists = $this->seedArtists();

        // Seed Artworks
        $this->seedArtworks($artists, $tags);

        // Seed Exhibitions after Artists and Artworks
        $this->seedExhibitions($artists);

        // Seed Carts and Orders
        $this->seedCartsAndOrders($users, $admin);
    }


    private function seedCategories(): void
    {
        DB::table('categories')->delete();

        $categories = [
            ['name' => 'Painting', 'slug' => 'painting', 'description' => 'All kinds of paintings'],
            ['name' => 'Sculpture', 'slug' => 'sculpture', 'description' => 'All kinds of sculptures'],
            ['name' => 'Digital Art', 'slug' => 'digital-art', 'description' => 'Digital artworks'],
        ];

        foreach ($categories as $category) {
            $parent = Category::create($category);

            // Add subcategories
            $subcategories = [
                ['name' => "{$category['name']} - Sub 1", 'slug' => Str::slug("{$category['name']} - Sub 1"), 'description' => "{$category['name']} Subcategory 1"],
                ['name' => "{$category['name']} - Sub 2", 'slug' => Str::slug("{$category['name']} - Sub 2"), 'description' => "{$category['name']} Subcategory 2"],
            ];

            foreach ($subcategories as $subcategory) {
                $parent->children()->create($subcategory);
            }
        }
    }

    private function seedTags(): array
    {
        DB::table('tags')->delete();

        $tags = [
            ['name' => 'Abstract', 'slug' => 'abstract'],
            ['name' => 'Modern', 'slug' => 'modern'],
            ['name' => 'Classic', 'slug' => 'classic'],
        ];

        return Tag::insert($tags) ? Tag::all()->toArray() : [];
    }

    private function generatePlaceholderImage(string $text, string $path): string
    {
        $manager = new ImageManager(new Driver());

        // Create a blank image
        $width = 800;
        $height = 600;
        $image = $manager->create($width, $height);

        // Fill background with a random color
        $backgroundColor = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        $image->fill($backgroundColor);

        // Add text
        $image->text($text, $width / 2, $height / 2, function ($font) {
            $font->size(32);
            $font->color('#FFFFFF');
            $font->align('center');
            $font->valign('middle');
        });

        // Create full path
        $fullPath = storage_path('app/public/' . $path);

        // Ensure directory exists
        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Save the image
        $image->save($fullPath);

        return $path;
    }

    private function seedArtists(): array
    {
        DB::table('artists')->delete();

        $artists = [];
        $artistsData = [
            ['name' => 'John Doe', 'slug' => 'john-doe', 'biography' => 'An abstract artist.', 'email' => 'john.doe@example.com'],
            ['name' => 'Jane Smith', 'slug' => 'jane-smith', 'biography' => 'A modern art sculptor.', 'email' => 'jane.smith@example.com'],
        ];

        foreach ($artistsData as $artistData) {
            $profileImage = $this->generatePlaceholderImage(
                $artistData['name'],
                'artists/' . $artistData['slug'] . '.jpg'
            );

            $artists[] = array_merge($artistData, [
                'is_active' => true,
                'profile_image' => $profileImage
            ]);
        }

        return Artist::insert($artists) ? Artist::all()->toArray() : [];
    }

    private function seedArtworks(array $artists, array $tags): void
    {
        DB::table('artworks')->delete();
        DB::table('artwork_tag')->delete();
        DB::table('artwork_category')->delete();

        $faker = \Faker\Factory::create();

        foreach ($artists as $artist) {
            foreach (range(1, 5) as $index) {
                $title = $faker->sentence(3);
                $imagePath = 'artworks/' . Str::slug($title) . '-' . $index . '.jpg';

                // Generate actual image
                $this->generatePlaceholderImage($title, $imagePath);

                $artwork = [
                    'artist_id' => $artist['id'],
                    'title' => $title,
                    'slug' => Str::slug($title . '-' . $artist['id']),
                    'description' => $faker->paragraph,
                    'price' => $faker->randomFloat(2, 100, 5000),
                    'dimensions' => $faker->randomElement(['100x120 cm', '80x100 cm', '50x70 cm']),
                    'medium' => $faker->randomElement(['Oil on Canvas', 'Acrylic on Canvas', 'Watercolor']),
                    'year_created' => $faker->numberBetween(1900, 2025),
                    'is_available' => $faker->boolean(80),
                    'is_featured' => $faker->boolean(20),
                    'stock' => $faker->numberBetween(0, 10),
                    'image' => $imagePath,
                ];

                $artworkModel = Artwork::create($artwork);

                // Attach random categories
                $categories = Category::inRandomOrder()->limit(2)->get();
                $artworkModel->categories()->attach($categories);

                // Attach random tags
                $randomTags = array_slice($tags, 0, 2);
                $artworkModel->tags()->attach(array_column($randomTags, 'id'));
            }
        }
    }

    private function seedExhibitions(array $artists): void
    {
        DB::table('exhibitions')->delete();
        DB::table('artist_exhibition')->delete();
        DB::table('artwork_exhibition')->delete();

        $faker = \Faker\Factory::create();

        foreach ($artists as $artist) {
            foreach (range(1, 2) as $index) {
                $title = $faker->sentence(3);
                $imagePath = 'exhibitions/' . Str::slug($title) . '-' . $index . '.jpg';

                // Generate actual image
                $this->generatePlaceholderImage($title, $imagePath);

                $exhibition = [
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'description' => $faker->paragraph,
                    'start_date' => $faker->dateTimeThisYear(),
                    'end_date' => $faker->dateTimeThisYear(),
                    'location' => $faker->city,
                    'image' => $imagePath,
                ];

                // Create the exhibition
                $exhibitionModel = Exhibition::create($exhibition);

                // Attach the artist to the exhibition
                $exhibitionModel->artists()->attach($artist['id']);

                // Attach random artworks to the exhibition
                $artworks = Artwork::where('artist_id', $artist['id'])
                    ->inRandomOrder()
                    ->limit(3)
                    ->get();

                $exhibitionModel->artworks()->attach($artworks->pluck('id'));
            }
        }
    }

    private function seedCartsAndOrders($users, $admin): void
    {
        $artworks = Artwork::all();
        $faker = \Faker\Factory::create();

        // Seed carts for all users
        foreach ($users as $user) {
            // Create cart
            $cart = Cart::create(['user_id' => $user->id]);

            // Add 1-3 random artworks to cart
            $cartItems = $artworks->random(rand(1, 3))->map(function ($artwork) use ($faker) {
                return new CartItem([
                    'artwork_id' => $artwork->id,
                    'quantity' => $faker->numberBetween(1, 3)
                ]);
            });

            $cart->items()->saveMany($cartItems);

            // Create 1-2 orders per user
            foreach (range(1, rand(1, 2)) as $index) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'status' => $faker->randomElement(['pending', 'completed', 'cancelled']),
                    'total' => 0,
                    'shipping_address' => $faker->address,
                    'payment_method' => 'credit_card',
                ]);

                // Create order items from random artworks
                $orderItems = $artworks->random(rand(1, 3))->map(function ($artwork) use ($faker) {
                    return new OrderItem([
                        'artwork_id' => $artwork->id,
                        'quantity' => $faker->numberBetween(1, 3),
                        'price' => $artwork->price
                    ]);
                });

                $order->items()->saveMany($orderItems);

                // Update order total
                $order->update([
                    'total' => $order->items->sum(fn($item) => $item->price * $item->quantity)
                ]);
            }
        }

        // Also create some orders for admin
        foreach (range(1, 3) as $index) {
            $order = Order::create([
                'user_id' => $admin->id,
                'status' => 'completed',
                'total' => 0,
                'shipping_address' => $faker->address,
                'payment_method' => 'credit_card',
            ]);

            $orderItems = $artworks->random(3)->map(function ($artwork) {
                return new OrderItem([
                    'artwork_id' => $artwork->id,
                    'quantity' => 1,
                    'price' => $artwork->price
                ]);
            });

            $order->items()->saveMany($orderItems);
            $order->update(['total' => $order->items->sum(fn($item) => $item->price * $item->quantity)]);
        }
    }
}

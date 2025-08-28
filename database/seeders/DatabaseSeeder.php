<?php

namespace Database\Seeders;

use App\Enums\CartStatus;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Nicoló',
            'email' => 'nico@example.com',
            'isAdmin' => true,
            'isTeam' => true,
            'role' => 'Mastro Artigiano',
        ]);
        User::factory()->create([
            'name' => 'Xavier',
            'email' => 'savio@example.com',
            'isAdmin' => true,
            'isTeam' => true,
            'role' => 'Mastro Diversamente Occupato',
        ]);
        User::factory(5);

        Tag::factory()->create(['tag' => '150cm']);
        Tag::factory()->create(['tag' => '160cm']);
        Tag::factory()->create(['tag' => '170cm']);
        Tag::factory()->create(['tag' => 'morbida']);
        Tag::factory()->create(['tag' => 'rigida']);
        Tag::factory()->create(['tag' => 'acrobatica']);
        Tag::factory()->create(['tag' => 'principianti']);

        $tags = Tag::all();
        // $tags = [...$tags,...$tags,...$tags,...$tags,...$tags,...$tags];
        Product::factory()
            ->count(5)
            ->recycle($tags)
            ->hasAttached($tags->random(5))
            ->has(Comment::factory(1))
            ->has(ProductGallery::factory(3))
            ->create();

        Post::factory(2)
            ->recycle($tags)
            ->create();

        $user = User::all();

        $carts = Cart::factory(30)
            ->recycle($user)
            ->has(
                CartItem::factory(3)
            )
            ->create(['status' => CartStatus::ordered]);

        Order::factory()
            ->count(30)
            ->recycle($user)
            ->recycle($carts)
            ->create();
    }
}

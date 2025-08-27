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
        $user = User::factory()->create([
            'name' => 'Xavier',
            'email' => 'savio@example.com',
            'isAdmin' => true,
            'isTeam' => true,
            'role' => 'Mastro Diversamente Occupato',
        ]);

        Product::factory(5)
            ->has(Tag::factory())
            ->has(Comment::factory(1))
            ->has(ProductGallery::factory(3))
            ->create();

        $tags = Tag::all();
        Post::factory(2)
            ->recycle($tags)
            ->create();

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

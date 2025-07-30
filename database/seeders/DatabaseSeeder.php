<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Product;
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
            'role' => 'Supporto Informatico',
        ]);

        User::factory(10)->create();

        Product::factory(5)
            ->has(Tag::factory())
            ->has(Comment::factory(5))
            ->create();

        $tags = Tag::all();
        Post::factory(8)
            ->recycle($tags)
            ->create();
    }
}

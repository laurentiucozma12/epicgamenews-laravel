<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Route;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key constraints for users and enable it again.
        Schema::disableForeignKeyConstraints();

        \App\Models\User::truncate();
        \App\Models\Role::truncate();
        \App\Models\VideoGame::truncate();
        \App\Models\Category::truncate();
        \App\Models\Platform::truncate();
        \App\Models\Other::truncate();
        \App\Models\Post::truncate();
        \App\Models\Tag::truncate();
        \App\Models\Image::truncate();

        Schema::enableForeignKeyConstraints();

        // Create Roles
        $userRole = \App\Models\Role::factory()->create(['name' => 'user']);
        $adminRole = \App\Models\Role::factory()->create(['name' => 'admin']);

        // Assign the 'admin' role to a specific user
        $adminUser = \App\Models\User::factory()->create([
            'name' => 'Lau',
            'email' => 'laurentiucozma12@gmail.com',
        ]);

        $adminUser->roles()->attach($adminRole->id);

        // Create Users
        $users = \App\Models\User::factory(10)->create();

        // Attach roles to users
        $users->each(function ($user) use ($userRole) {
            $user->roles()->attach($userRole->id);
        });

        \App\Models\VideoGame::factory(1)->create(['name' => 'uncategorized', 'slug' => 'uncategorized']);
        $video_games = \App\Models\VideoGame::factory(10)->create();

        foreach ($video_games as $video_game)
        {
            $video_game->image()->save( \App\Models\Image::factory()->make() );
        }

        \App\Models\Category::factory(1)->create(['name' => 'uncategorized', 'slug' => 'uncategorized']);
        $categories = \App\Models\Category::factory(10)->create();

        foreach ($categories as $category)
        {
            $category->image()->save( \App\Models\Image::factory()->make() );
        }
        
        $platformsData = [
            (object)['name' => 'uncategorized', 'slug' => 'uncategorized'],
            (object)['name' => 'PC', 'slug' => 'pc'],
            (object)['name' => 'PlayStation', 'slug' => 'playstation'],
            (object)['name' => 'Xbox', 'slug' => 'xbox'],
            (object)['name' => 'Mobile', 'slug' => 'mobile'],
            (object)['name' => 'Nintendo', 'slug' => 'nintendo'],
        ];
        
        foreach ($platformsData as $platform) {
            $platform = \App\Models\Platform::factory()->create([
                'name' => $platform->name,
                'slug' => $platform->slug,
            ]);

            $platform->image()->save( \App\Models\Image::factory()->make() );
        }
        
        $othersData = [
            (object)['name' => 'uncategorized', 'slug' => 'uncategorized'],
            (object)['name' => 'Game Trailers', 'slug' => 'game-trailers'],
            (object)['name' => 'Anime', 'slug' => 'anime'],
            (object)['name' => 'Cartoons', 'slug' => 'cartoons'],
            (object)['name' => 'Movies', 'slug' => 'movies'],
            (object)['name' => 'Series', 'slug' => 'series'],
            (object)['name' => 'Lists', 'slug' => 'lists'],
        ];
        
        foreach ($othersData as $other) {
            $other = \App\Models\Other::factory()->create([
                'name' => $other->name,
                'slug' => $other->slug,
            ]);

            $other->image()->save( \App\Models\Image::factory()->make() );
        }

        $posts = \App\Models\Post::factory(10)->create(['approved' => true]);;

        \App\Models\Tag::factory(1)->create();

        foreach($posts as $post) 
        {
            $categories_ids = [];
            $categories_ids[] = \App\Models\Platform::all()->random()->id;
            $categories_ids[] = \App\Models\Platform::all()->random()->id;
            $categories_ids[] = \App\Models\Platform::all()->random()->id;
            $post->categories()->sync( $categories_ids );

            $platforms_ids = [];
            $platforms_ids[] = \App\Models\Platform::all()->random()->id;
            $platforms_ids[] = \App\Models\Platform::all()->random()->id;
            $platforms_ids[] = \App\Models\Platform::all()->random()->id;
            $post->platforms()->sync( $platforms_ids );

            $platforms_ids = [];
            $tags_ids[] = \App\Models\Tag::all()->random()->id;
            $tags_ids[] = \App\Models\Tag::all()->random()->id;
            $tags_ids[] = \App\Models\Tag::all()->random()->id;
            $post->tags()->sync( $tags_ids );

            $post->image()->save( \App\Models\Image::factory()->make() );
        }

        \App\Models\About::factory(1)->create();
    }
}

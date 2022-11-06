<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{


    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        if(App::environment() === 'production') { exit(); }
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $tables = DB::select('SHOW TABLES');
        $attributeName = "Tables_in_".env('DB_DATABASE');
        foreach ($tables as $table) {
            if($table->$attributeName !== 'migrations') {
                DB::table($table->$attributeName)->truncate();
            }
        }

        // 2. Seed the tables with new data
        $numOfUsers = 500;
        $numOfCategories = 30;
        $numOfProducts = 1000;
        $numOfTransactions = 1200;

        User::factory()->count($numOfUsers)->create();
        Category::factory()->count($numOfCategories)->create();
        Product::factory()
            ->count($numOfProducts)
            ->create()
            ->each(function($product) {
                $category_ids = Category::all()->random(random_int(1, 5))->pluck('id');
                $product->categories()->attach($category_ids);
            });
        Transaction::factory()->count($numOfTransactions)->create();
    }

    }
}

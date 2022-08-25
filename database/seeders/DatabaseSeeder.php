<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        // \App\Models\User::factory(10)->create();

        // Seed the levels table
        // DB::table('levels')->insert([
        //     'name' => 'Jss1 A',
        //     'description' => '',
            
        // ]);
        // DB::table('levels')->insert([
        //     'name' => 'Jss1 B',
        //     'description' => '',
            
        // ]);
        // DB::table('levels')->insert([
        //     'name' => 'Jss2 A',
        //     'description' => '',
            
        // ]);
        // DB::table('levels')->insert([
        //     'name' => 'Jss3 A',
        //     'description' => '',
            
        // ]);
        DB::table('levels')->insert([
            'name' => 'Jss3 B',
            'description' => '',
            
        ]);

      
    }
}

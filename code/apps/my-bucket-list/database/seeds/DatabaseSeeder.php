<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->call(StatesTableSeeder::class);
    }
}

class StatesTableSeeder extends Seeder {
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        DB::table('states')->insert([
            'short_name' => 'new', 'display_name' => 'New'            
        ]);
        DB::table('states')->insert([
            'short_name' => 'expired', 'display_name' => 'Expired'                        
        ]);
        DB::table('states')->insert([
            'short_name' => 'completed', 'display_name' => 'Completed'                        
        ]);
    }
}
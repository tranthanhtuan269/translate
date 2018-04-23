<?php

use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time_created = date('Y-m-d H:i:s');
    	for($i = 0; $i < 60; $i++){
    		\DB::table('languages')->insert([
        		'name' => 'language_' . $i,
        		'created_by' => 1,
        		'updated_by' => 1,
        		'created_at' => $time_created,
        		'updated_at' => $time_created
        	]);
    	}
    }
}

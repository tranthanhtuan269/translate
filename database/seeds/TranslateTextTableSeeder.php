<?php

use Illuminate\Database\Seeder;

class TranslateTextTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time_created = date('Y-m-d H:i:s');
        
		\DB::table('translate_text')->insert([
    		'source_text' 		=> 'hello',
    		'trans_text' 		=> 'xin chào',
    		'slug' 				=> 'hello',
    		'category_id' 		=> 1,
    		'language_id' 		=> 2,
    		'translate_type' 	=> 0,
    		'created_by' 		=> 1,
    		'updated_by' 		=> 1,
    		'created_at' 		=> $time_created,
    		'updated_at' 		=> $time_created
    	]);
        
		\DB::table('translate_text')->insert([
    		'source_text' 		=> 'hi',
    		'trans_text' 		=> 'xin chào',
    		'slug' 				=> 'hi',
    		'category_id' 		=> 1,
    		'language_id' 		=> 2,
    		'translate_type' 	=> 0,
    		'created_by' 		=> 1,
    		'updated_by' 		=> 1,
    		'created_at' 		=> $time_created,
    		'updated_at' 		=> $time_created
    	]);

		\DB::table('translate_text')->insert([
    		'source_text' 		=> 'Good Morning',
    		'trans_text' 		=> 'xin chào',
    		'slug' 				=> 'good_morning',
    		'category_id' 		=> 1,
    		'language_id' 		=> 2,
    		'translate_type' 	=> 0,
    		'created_by' 		=> 1,
    		'updated_by' 		=> 1,
    		'created_at' 		=> $time_created,
    		'updated_at' 		=> $time_created
    	]);

		\DB::table('translate_text')->insert([
    		'source_text' 		=> 'Good Afternoon',
    		'trans_text' 		=> 'xin chào',
    		'slug' 				=> 'good_afternoon',
    		'category_id' 		=> 1,
    		'language_id' 		=> 2,
    		'translate_type' 	=> 0,
    		'created_by' 		=> 1,
    		'updated_by' 		=> 1,
    		'created_at' 		=> $time_created,
    		'updated_at' 		=> $time_created
    	]);

		\DB::table('translate_text')->insert([
    		'source_text' 		=> 'Good Evening',
    		'trans_text' 		=> 'xin chào',
    		'slug' 				=> 'good_evening',
    		'category_id' 		=> 1,
    		'language_id' 		=> 2,
    		'translate_type' 	=> 0,
    		'created_by' 		=> 1,
    		'updated_by' 		=> 1,
    		'created_at' 		=> $time_created,
    		'updated_at' 		=> $time_created
    	]);

		\DB::table('translate_text')->insert([
    		'source_text' 		=> 'Good Bye',
    		'trans_text' 		=> 'Tạm biệt',
    		'slug' 				=> 'good_bye',
    		'category_id' 		=> 1,
    		'language_id' 		=> 2,
    		'translate_type' 	=> 0,
    		'created_by' 		=> 1,
    		'updated_by' 		=> 1,
    		'created_at' 		=> $time_created,
    		'updated_at' 		=> $time_created
    	]);
    }
}

<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time_created = date('Y-m-d H:i:s');
        \DB::table('users')->insert([
                'name' => 'admin',
                'avatar' => '',
                'role_id' => '1',
        		'status' => '1',
                'career' => 'admin',
        		'email' => 'admin@tohsoft.com',
        		'password' => bcrypt('tohsoft'),
                'created_at' => $time_created,
                'updated_at' => $time_created
        	]);

        \DB::table('roles')->insert([
                'name' => 'admin',
                'permission' => '2,3,4,5,1,6,7,8,9,10,14,15,13,12,11,16,17,18,19,20,21,22,23,24,25,26',
                'created_at' => $time_created,
                'updated_at' => $time_created
            ]);
    }
}

<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$time_created = date('Y-m-d H:i:s');
        
        \DB::table('permissions')->insert(['name' => 'List User','route' => 'user','group' => '1', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Create User','route' => 'user.create','group' => '1', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Edit User','route' => 'user.edit','group' => '1', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Delete User','route' => 'user.detele','group' => '1', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Delete All User','route' => 'user.delMulti','group' => '1', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        
        \DB::table('permissions')->insert(['name' => 'List Category','route' => 'category','group' => '2', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Create Category','route' => 'category.create','group' => '2', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Edit Category','route' => 'category.edit','group' => '2', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Delete Category','route' => 'category.detele','group' => '2', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Delete All Category','route' => 'category.delMulti','group' => '2', 'created_at' => $time_created,'updated_at' => $time_created
            ]);

        \DB::table('permissions')->insert(['name' => 'List Language','route' => 'language','group' => '3', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Create Language','route' => 'language.create','group' => '3', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Edit Language','route' => 'language.edit','group' => '3', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Delete Language','route' => 'language.detele','group' => '3', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Delete All Language','route' => 'language.delMulti','group' => '3', 'created_at' => $time_created,'updated_at' => $time_created
            ]);

        \DB::table('permissions')->insert(['name' => 'List Language Group','route' => 'groups','group' => '4', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Create Language Group','route' => 'groups.create','group' => '4', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Edit Language Group','route' => 'groups.edit','group' => '4', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Delete Language Group','route' => 'groups.detele','group' => '4', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Delete All Language Group','route' => 'groups.delMulti', 'group' => '4', 'created_at' => $time_created,'updated_at' => $time_created
            ]);

        \DB::table('permissions')->insert(['name' => 'List Translator','route' => 'translates','group' => '5', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'List Review Translator','route' => 'translates.review', 'group' => '5', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Create Translator','route' => 'translates.create','group' => '5', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Edit Translator','route' => 'translates.edit','group' => '5', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Delete Translator','route' => 'translates.detele','group' => '5', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
        \DB::table('permissions')->insert(['name' => 'Delete All Translator','route' => 'translates.delMulti','group' => '5', 'created_at' => $time_created,'updated_at' => $time_created
            ]);
    }
}

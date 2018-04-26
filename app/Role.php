<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	protected $fillable = ['name', 'permission', 'created_by', 'updated_by'];
	
    public static function getDataForDatatable(){
        $query = \DB::table('roles')
                ->select(
                        'id',
                        'name'
                        );
        return collect($query->get());
    }

    public static function deleteMulti($id_list){
        $list = explode(",",$id_list);
        $checkRole = Role::whereIn('id', $list);
        return ($checkRole->delete() > 0);
    }
}

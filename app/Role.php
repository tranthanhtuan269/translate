<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public static function getDataForDatatable(){
        $query = \DB::table('roles')
                ->select(
                        'id',
                        'name'
                        );
        return collect($query->get());
    }
}

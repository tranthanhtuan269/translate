<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'created_by', 'updated_by'];

    public function commonTexts()
    {
        return $this->hasMany('App\CommonText');
    }

    public function translateTexts()
    {
        return $this->hasMany('App\TranslateText');
    }

	public function creator()
	{
	    return $this->belongsTo('App\User', 'created_by');
	}

	public function updater()
	{
	    return $this->belongsTo('App\User', 'updated_by');
	}

    public static function getDataForDatatable(){
        $query = \DB::table('categories')
                ->join('users', 'users.id', 'categories.updated_by')
                ->select(
                        'categories.id as id',
                        'categories.name as name',
                        'users.name as updater'
                        );
        return collect($query->get());
    }

    public static function deleteMulti($id_list){
        $list = explode(",",$id_list);
        $checkCategory = Category::where("created_by", \Auth::user()->id);
        $checkCategory = $checkCategory->whereIn('id', $list);
        return ($checkCategory->delete() > 0);
    }
}

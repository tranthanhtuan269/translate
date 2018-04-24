<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
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
        $query = \DB::table('languages')
                ->join('users', 'users.id', 'languages.updated_by')
                ->select(
                        'languages.id as id',
                        'languages.name as name',
                        'users.name as updater'
                        )
                ->orderBy('languages.updated_at', 'desc');
        return collect($query->get());
    }

    public static function deleteMulti($id_list){
        $list = explode(",",$id_list);
        $checkLanguage = Language::where("created_by", \Auth::user()->id);
        $checkLanguage = $checkLanguage->whereIn('id', $list);
        return ($checkLanguage->delete() > 0);
    }
}

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
}

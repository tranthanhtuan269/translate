<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommonText extends Model
{
    protected $fillable = [
    						'source', 'trans', 'language_id', 
    						'translate_type', 'status', 
    						'slug', 'created_by', 'updated_by'
    					];
    
    public function language()
    {
        return $this->belongsTo('App\Language');
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

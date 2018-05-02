<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TranslateText extends Model
{
    protected $table = 'translate_text';
    protected $fillable = [
    						'source_text', 'trans_text', 'category_id', 
    						'language_id', 'translate_type', 'slug', 'created_by', 'updated_by'
    					];
    
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    
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

    public static function updateContribute($category_id, $language_id, $slug, $trans_text){
        return TranslateText::where('category_id', $category_id)
                    ->where('language_id', $language_id)
                    ->where('slug', $slug)
                    ->update(['trans_text' => $trans_text, 'translate_type' => 1]);

    }
}

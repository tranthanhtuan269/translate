<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

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

    public static function updateContribute(
                                    $category_id, $category_before, $language_id, 
                                    $language_before, $slug, $source_text, $trans_text, $translate_type){

        return TranslateText::where('category_id', $category_before)
                    ->where('language_id', $language_before)
                    ->where('slug', $slug)
                    ->update([
                        'slug' => str_slug($source_text, '_'), 
                        'source_text' => $source_text, 
                        'trans_text' => $trans_text, 
                        'category_id' => $category_id, 
                        'language_id' => $language_id, 
                        'translate_type' => $translate_type
                        ]);
    }

    public static function findBySlug($slug, $category, $language, $source_text){
        $return = TranslateText::where('category_id', $category)
                    ->where('language_id', $language)
                    ->where('slug', $slug)->first();

        if(!isset($return)){
            $return = TranslateText::where('category_id', [5])
                    ->where('language_id', $language)
                    ->where('slug', $slug)->first();
            if(!isset($return)){
                $return = TranslateText::whereNotIn('category_id', [$category, 5])
                    ->where('language_id', $language)
                    ->where('slug', $slug)->first();
                if(!isset($return)){
                    $time_created = date('Y-m-d H:i:s');
                    // call API google translate

                    // save to datable with 
                    $translate_text = new TranslateText;
                    $translate_text->category_id = $category;
                    $translate_text->language_id = $language;
                    $translate_text->slug = $slug;
                    $translate_text->source_text = $source_text;
                    $translate_text->trans_text = "";
                    $translate_text->translate_type = 0;
                    $translate_text->created_by = Auth::user()->id;
                    $translate_text->updated_by = Auth::user()->id;
                    $translate_text->created_at = $time_created;
                    $translate_text->updated_at = $time_created;
                    $translate_text->save();

                    return $translate_text;
                }
            }
        }

        return $return;
    }

    public static function getDataForDatatable(){
        $query = \DB::table('translate_text')
                ->join('languages', 'languages.id', 'translate_text.language_id')
                ->join('categories', 'categories.id', 'translate_text.category_id')
                ->select(
                            'translate_text.source_text as source_text',
                            'translate_text.trans_text as trans_text',
                            'translate_text.translate_type as translate_type',
                            'translate_text.slug as slug',
                            'languages.id as language_id',
                            'languages.name as language_name',
                            'categories.id as category_id',
                            'categories.name as category_name'
                        )
                ->orderBy('translate_text.updated_at', 'desc');
        return collect($query->get());
    }

    public static function deleteObject($slug, $category, $language){
        $return = TranslateText::where('category_id', $category)
                    ->where('language_id', $language)
                    ->where('slug', $slug)->delete();
        return $return;
    }

    public static function deleteMulti($obj_list){
        $return = true;
        foreach($obj_list as $obj){
            $return &= TranslateText::deleteObject($obj->slug, $obj->category, $obj->language);
        }
        return $return;
    }

    public static function getDataForExport($search, $category, $language, $status){
        $query = \DB::table('translate_text')
                ->join('languages', 'languages.id', 'translate_text.language_id')
                ->join('categories', 'categories.id', 'translate_text.category_id')
                ->select(
                            'translate_text.source_text as source_text',
                            'translate_text.trans_text as trans_text',
                            'translate_text.translate_type as translate_type',
                            'translate_text.slug as slug',
                            'languages.id as language_id',
                            'languages.name as language_name',
                            'categories.id as category_id',
                            'categories.name as category_name'
                        );

        if(isset($category)){
            $query->where('categories.id', $category);
        }

        if(isset($language)){
            $query->where('languages.id', $language);
        }

        if(isset($status)){
            $query->where('translate_type', $status);
        }

        if(isset($search)){
            $query->orWhere('translate_text.source_text', 'like', '%'.$search.'%');   
            $query->orWhere('translate_text.trans_text', 'like', '%'.$search.'%');   
            $query->orWhere('languages.name', 'like', '%'.$search.'%');   
            $query->orWhere('categories.name', 'like', '%'.$search.'%');   
        }

        $query->orderBy('translate_text.updated_at', 'desc');
        return collect($query->get());
    }
}

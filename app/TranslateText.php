<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Common\AutoTranslate;
use App\Language;
use Auth;

class TranslateText extends Model
{
    protected $table = 'translate_texts';
    protected $fillable = [
    						'keyword' ,'source_text', 'trans_text', 'category_id', 
    						'language_id', 'translate_type', 'created_by', 'updated_by'
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
                                    $language_before, $keyword, $source_text, $trans_text, $translate_type){

        return TranslateText::where('category_id', $category_before)
                    ->where('language_id', $language_before)
                    ->where('keyword', $keyword)
                    ->update([
                        'source_text' => $source_text, 
                        'trans_text' => $trans_text, 
                        'category_id' => $category_id, 
                        'language_id' => $language_id, 
                        'translate_type' => $translate_type
                        ]);
    }

    public static function findByKeyword($keyword, $category, $language, $source_text){
        $return = TranslateText::where('category_id', $category)
                    ->where('language_id', $language)
                    ->where('keyword', $keyword)->first();

        if(!isset($return)){
            $return = TranslateText::where('category_id', [5])
                    ->where('language_id', $language)
                    ->where('keyword', $keyword)->first();
            if(!isset($return)){
                $return = TranslateText::whereNotIn('category_id', [$category, 5])
                    ->where('language_id', $language)
                    ->where('keyword', $keyword)->first();
                if(!isset($return)){
                    // get language code
                    $language_code = Language::getLanguageCode($language);
                    if($language_code != ''){
                        // call API google translate
                        $autoTranslate = TranslateText::autoTranslateToCreate(
                                        $keyword,
                                        $source_text,
                                        $category,
                                        $language,
                                        $language_code
                                    );
                        return $autoTranslate;
                    }
                }
            }
        }

        return $return;
    }

    public static function autoTranslateToCreate(
                                $keyword, $source, $category, $language, $language_code){
        // call api google translate
        $autoTranslate = new AutoTranslate($source, 'en', $language_code);
        $time_created = date('Y-m-d H:i:s');
        $obj = $autoTranslate->callApi();
        if($obj != null)
        {
            if(isset($obj['error']))
            {
                echo "Error is : ".$obj['error']['message'];
            }
            else
            {
                $translated_text = $obj['data']['translations'][0]['translatedText'];
                // save to datable with 
                $translate_text                 = new TranslateText;
                $translate_text->keyword        = $keyword;
                $translate_text->category_id    = $category;
                $translate_text->language_id    = $language;
                $translate_text->source_text    = $source;
                $translate_text->trans_text     = $translated_text;
                $translate_text->translate_type = 0;
                $translate_text->created_by     = Auth::user()->id;
                $translate_text->updated_by     = Auth::user()->id;
                $translate_text->created_at     = $time_created;
                $translate_text->updated_at     = $time_created;
                $translate_text->save();

                return $translate_text;
            }
        }
        return null;
    }

    public static function autoTranslateToUpdate(
                                $keyword, $source, $category, $language, $language_code){
        // call api google translate
        $autoTranslate = new AutoTranslate($source, 'en', $language_code);
        $obj = $autoTranslate->callApi();
        if($obj != null)
        {
            if(isset($obj['error']))
            {
                $update = TranslateText::updateTranslate($keyword, $category, $language, "Find not found!", 0);
            }
            else
            {
                $update = TranslateText::updateTranslate($keyword, $category, $language, $obj['data']['translations'][0]['translatedText'], 0);
                return $update;
            }
        }
        return null;
    }

    public static function checkExist($keyword, $category, $language){
        $return = TranslateText::where('language_id', $language)
                    ->where('category_id', $category)
                    ->where('keyword', $keyword)->first();

        return $return;
    }

    public static function updateTranslate($keyword, $category, $language, $trans_text, $translate_type = 2){
        $return = TranslateText::where('category_id', $category)
                    ->where('language_id', $language)
                    ->where('keyword', $keyword)->update([
                            'trans_text' => $trans_text,
                            'translate_type' => $translate_type
                        ]);

        return $return;
    }

    public static function getDataForDatatable(){
        $query = \DB::table('translate_texts')
                ->join('languages', 'languages.id', 'translate_texts.language_id')
                ->join('categories', 'categories.id', 'translate_texts.category_id')
                ->select(
                            'translate_texts.source_text as source_text',
                            'translate_texts.trans_text as trans_text',
                            'translate_texts.translate_type as translate_type',
                            'translate_texts.keyword as keyword',
                            'languages.id as language_id',
                            'languages.name as language_name',
                            'categories.id as category_id',
                            'categories.name as category_name'
                        )
                ->orderBy('translate_texts.updated_at', 'desc');
        return collect($query->get());
    }

    public static function getDataReviewForDatatable(){
        $query = \DB::table('translate_texts')
                ->join('languages', 'languages.id', 'translate_texts.language_id')
                ->join('categories', 'categories.id', 'translate_texts.category_id')
                ->select(
                            'translate_texts.source_text as source_text',
                            'translate_texts.trans_text as trans_text',
                            'translate_texts.translate_type as translate_type',
                            'translate_texts.keyword as keyword',
                            'languages.id as language_id',
                            'languages.name as language_name',
                            'categories.id as category_id',
                            'categories.name as category_name'
                        )
                ->where('translate_texts.translate_type', 1)
                ->orderBy('translate_texts.updated_at', 'desc');
        return collect($query->get());
    }

    public static function getTextMiss(){
        $query = \DB::table('translate_texts')
                ->join('languages', 'languages.id', 'translate_texts.language_id')
                ->select(
                            'translate_texts.source_text as source_text',
                            'translate_texts.trans_text as trans_text',
                            'translate_texts.translate_type as translate_type',
                            'translate_texts.keyword as keyword',
                            'translate_texts.category_id as category_id',
                            'languages.id as language_id',
                            'languages.name as language_name',
                            'languages.code as language_code'
                        )
                ->where('translate_texts.trans_text', null)
                ->orWhere('translate_texts.trans_text', '')
                ->orderBy('translate_texts.updated_at', 'desc');
        return collect($query->get());   
    }

    public static function deleteObject($keyword, $category, $language){
        $return = TranslateText::where('category_id', $category)
                    ->where('language_id', $language)
                    ->where('keyword', $keyword)->delete();
        return $return;
    }

    public static function confirmObject($keyword, $category, $language){
        $return = TranslateText::where('category_id', $category)
                    ->where('language_id', $language)
                    ->where('keyword', $keyword)
                    ->update([
                        'translate_type' => 2
                        ]);
        return $return;
    }

    public static function deleteMulti($obj_list){
        $return = true;
        foreach($obj_list as $obj){
            $return &= TranslateText::deleteObject($obj->keyword, $obj->category, $obj->language);
        }
        return $return;
    }

    public static function getDataForExport($search, $category, $language, $status){
        $query = \DB::table('translate_texts')
                ->join('languages', 'languages.id', 'translate_texts.language_id')
                ->join('categories', 'categories.id', 'translate_texts.category_id')
                ->select(
                    \DB::raw('
                        translate_texts.source_text as source_text,
                        translate_texts.trans_text as trans_text,
                        languages.name as language_name,
                        categories.name as category_name,
                        case translate_texts.translate_type
                            when 0 then "Auto"
                            when 1 then "Contributor"
                            when 2 then "Confirmed"
                        end as translate_type 
                    ')
                            
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
            $query->orWhere('translate_texts.source_text', 'like', '%'.$search.'%');   
            $query->orWhere('translate_texts.trans_text', 'like', '%'.$search.'%');   
            $query->orWhere('languages.name', 'like', '%'.$search.'%');   
            $query->orWhere('categories.name', 'like', '%'.$search.'%');   
        }

        $query->orderBy('translate_texts.updated_at', 'desc');
        return collect($query->get());
    }
}

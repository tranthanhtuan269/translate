<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category, App\Language, App\TranslateText;
use App\Http\Requests\UpdateContributeRequest;

class ContributorController extends Controller
{
    public function index(){
        $categories = Category::pluck('name', 'id');
        $languages  = Language::pluck('name', 'id');
    	return view('site.contribute', ['categories' => $categories, 'languages' => $languages]);
    }

    public function getData(Request $request){
    	if(isset($request->language) && isset($request->category)){
    		$translateTexts = TranslateText::select('slug', 'source_text', 'trans_text')
    							->where('category_id', $request->category)
    							->where('language_id', $request->language)
    							->where('translate_type', 0)->get();
    		$res=array('status'=>200,"Message"=>isset($messages['category.delete_multi_success']) ? $messages['category.delete_multi_success'] : "", "translateTexts" => $translateTexts);
    		echo json_encode($res);
    	}
    }

    public function update(UpdateContributeRequest $request){
        $translate = TranslateText::updateContribute(
                                                    $request->category, 
                                                    $request->category, 
                                                    $request->language, 
                                                    $request->language, 
                                                    $request->slug, 
                                                    $request->source_text, 
                                                    $request->trans_text, 
                                                    1
                                                );

        $res=array('status'=>200,"Message"=>isset($messages['category.delete_multi_success']) ? $messages['category.delete_multi_success'] : "Translate text has been updated! Thank you for your contribution!");
    	echo json_encode($res);
    }
}

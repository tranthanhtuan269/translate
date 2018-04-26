<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category, App\Language;
use App\TranslateText;

class ContributorController extends Controller
{
    public function index(){
        $categories = Category::pluck('name', 'id');
        $languages  = Language::pluck('name', 'id');
    	return view('site.contribute', ['categories' => $categories, 'languages' => $languages]);
    }

    public function getDataByAjax(Request $request){
    	if(isset($request->language) && isset($request->category)){
    		$translateTexts = TranslateText::select('slug', 'source_text', 'trans_text')->where('translate_type', 0)->get();
    		dd($translateTexts);
    	}
    }

    public function store(){
    }
}

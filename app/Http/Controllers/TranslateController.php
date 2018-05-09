<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Helper, App\Common\TranslateExport;
use App\TranslateText, App\Language, App\Category;
use App\Http\Requests\StoreTranslateRequest;
use App\Http\Requests\UpdateTranslateRequest;

class TranslateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::pluck('name', 'id');
        $languages = Language::pluck('name', 'id');
        $translates = TranslateText::where('category_id', 1)->where('language_id', 1)->get();
        return view('translate_text.index', [
                        'translates' => $translates, 
                        'categories' => $categories, 
                        'languages' => $languages
                    ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::pluck('name', 'id');
        $languages = Language::pluck('name', 'id');
        return view('translate_text.create', ['categories' => $categories, 'languages' => $languages]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTranslateRequest $request)
    {
        $time_created = date('Y-m-d H:i:s');
        $translate = new TranslateText;
        $translate->source_text     = $request->source_text;
        $translate->trans_text      = $request->translated_text;
        $translate->category_id     = $request->category;
        $translate->language_id     = $request->language;
        $translate->translate_type  = $request->status;
        $translate->slug            = str_slug($request->source_text, '_');
        $translate->created_by      = \Auth::user()->id;
        $translate->updated_by      = \Auth::user()->id;
        $translate->created_at      = $time_created;
        $translate->updated_at      = $time_created;

        if($translate->save()){
            $res=array('status'=>201,"Message"=>isset($messages['translate.created_success']) ? $messages['translate.created_success'] : "Translate text has been created!");
        }else{
            $res=array('status'=>"400","Message"=>isset($messages['translate.created_unsuccess']) ? $messages['category.created_unsuccess'] : "Translate text hasn't been created!");
        }
        echo json_encode($res);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete(Request $request){
        $delete = TranslateText::deleteObject($request->slug, $request->category, $request->language);

        if($delete){
            $res=array('status'=>200,"Message"=>isset($messages['translate.delete_success']) ? $messages['translate.delete_success'] : "Translate text has been deleted!");
        }else{
            $res=array('status'=>"204","Message"=>isset($messages['translate.delete_unsuccess']) ? $messages['category.delete_unsuccess'] : "Translate text hasn't been deleted!");
        }
        echo json_encode($res);
    }

    public function translate(Request $request){
        if(isset($request->files) && isset($request->category) && isset($request->translateGroup)){
            
            $category   = $request->category;
            $group      = $request->translateGroup;
            $return     = null;

            foreach($request->files as $file){
                switch($file->getClientOriginalExtension())
                {
                    case "xml": $return = Helper::readFileXML($file, $category, $group);
                    break;

                    case "json": $return = Helper::readFileJSON($file, $category, $group);
                    break;

                    default:
                    break;
                }
            }

            $zip_file = Helper::zip($return);

            $res=array('status'=>"200","list_files"=> $zip_file);

            echo json_encode($res);
        }
    }

    public function delMulti(Request $request){
        if(isset($request) && $request->input('obj_list')){
            $obj_list = json_decode($request->input('obj_list'));

            if(TranslateText::deleteMulti($obj_list)){
                $res=array('status'=>200,"Message"=>isset($messages['translate.delete_multi_success']) ? $messages['translate.delete_multi_success'] : "Translates have been successfully deleted!");
            }else{
                $res=array('status'=>"204","Message"=>isset($messages['translate.delete_multi_error']) ? $messages['translate.delete_multi_error'] : "Translates haven't been successfully deleted!");
            }
            echo json_encode($res);
        }
    }

    public function adminUpdate(UpdateTranslateRequest $request){
        $translate = TranslateText::updateContribute($request->category, $request->category_before, $request->language, $request->language_before, $request->slug, $request->sourceText, $request->translatedText, 2);

        $res=array('status'=>200,"Message"=>isset($messages['translate.update_success']) ? $messages['translate.update_success'] : "Translate text has been updated!");
        echo json_encode($res);
    }

    public function getDataAjax()
    {
        $translates = TranslateText::getDataForDatatable();

        return datatables()->of($translates)
                ->addColumn('action', function ($translate) {
                    return 1;
                })
                ->addColumn('all', function ($translate) {
                    return 1;
                })->make(true);
    }

    public function getDataAjaxReview()
    {
        $translates = TranslateText::getDataReviewForDatatable();

        return datatables()->of($translates)
                ->addColumn('action', function ($translate) {
                    return 1;
                })
                /*->addColumn('all', function ($translate) {
                    return 1;
                })*/->make(true);
    }

    public function createFileExport(Request $request){
        return (
            new TranslateExport(
                    $request->search, 
                    $request->category, 
                    $request->language, 
                    $request->status
                )
            )->download('translate.xlsx');
    }

    public function reviewContribute(){
        $categories = Category::pluck('name', 'id');
        $languages = Language::pluck('name', 'id');
        return view('translate_text.review', [
                        'categories' => $categories, 
                        'languages' => $languages
                    ]);
    }

    public function confirm(Request $request){
        $confirm = TranslateText::confirmObject($request->slug, $request->category, $request->language);

        if($confirm){
            $res=array('status'=>200,"Message"=>isset($messages['translate.confirm_success']) ? $messages['translate.confirm_success'] : "Translate text has been confirmed!");
        }else{
            $res=array('status'=>"204","Message"=>isset($messages['translate.confirm_unsuccess']) ? $messages['category.confirm_unsuccess'] : "Translate text hasn't been confirmed!");
        }
        echo json_encode($res);
    }
}
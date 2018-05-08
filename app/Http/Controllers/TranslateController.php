<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Helper;
use App\TranslateText, App\Language, App\Category;
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    public function createFileExport(Request $request){
        $translates = TranslateText::getDataForExport(
                                        $request->search, $request->category, $request->language, $request->status);
        
        $linkFile = Helper::exportList($translates);
    }
}
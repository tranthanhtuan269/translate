<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Language;
use Validator,Cache;

class LanguageController extends Controller
{
	private $messages;

    public function __construct()
    {
        $this->messages = Cache::remember('messages', 1440, function() {
            return \DB::table('messages')->where('category', 1)->pluck('message', 'name');
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('language.index');
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
        if ($request->ajax()) {
            try{
                $rules = [
                    'name'=>'required'
                ];

                $messages = [
                    'name.required'=> isset($this->messages['language.name.required']) ? $this->messages['language.name.required'] : 'The name field is required.',
                ];


                $validator = Validator::make(Input::all(), $rules, $messages);
                if ($validator->fails()) {
                    $errors = [];
                    foreach ($validator->errors()->toArray() as $key => $value) {
                        foreach ($value as $k => $v) {
                            $errors[] = $v;
                        }
                    }
                    $res=array('status'=>"400","Message"=>$errors );
                }else{
                    $language = Language::find($id);
                    if($language){
                        $language->name         = $request->name;
                        $language->updated_by   = auth()->user()->id;
                        $language->updated_at   = date('Y-m-d H:i:s');

                        if($language->save()){
                            $res=array('status'=>"200","Message"=>isset($messages['language.update_success']) ? $messages['language.update_success'] : "The language has been successfully updated!");    
                        }else{
                            $res=array('status'=>"401","Message"=>isset($this->messages['language.update_error']) ? $this->messages['language.update_error'] : 'The language hasn\' been successfully updated.' );    
                        }
                    }
                }
                echo json_encode($res);
            } catch (\Illuminate\Database\QueryException $ex){
                return $ex->getMessage(); 
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(isset($id)){
            $language = Language::find($id);
            if(isset($language) && $language->delete()){
                $res=array('status'=>"200","Message"=>isset($messages['language.delete_success']) ? $messages['language.delete_success'] : "The language has been successfully deleted!");
            }else{
                $res=array('status'=>"204","Message"=>isset($messages['language.delete_error']) ? $messages['language.delete_error'] : "The language hasn't been successfully deleted!");
            }
            echo json_encode($res);
        }
    }

    public function delMulti(Request $request){
        if(isset($request) && $request->input('id_list')){
            $id_list = $request->input('id_list');
            $id_list = rtrim($id_list, ',');

            if(Language::deleteMulti($id_list)){
                $res=array('status'=>200,"Message"=>isset($messages['language.delete_multi_success']) ? $messages['language.delete_multi_success'] : "Languages have been successfully deleted!");
            }else{
                $res=array('status'=>"204","Message"=>isset($messages['language.delete_multi_error']) ? $messages['language.delete_multi_error'] : "Languages haven't been successfully deleted!");
            }
            echo json_encode($res);
        }
    }

    public function getDataAjax()
    {
        $categories = Language::getDataForDatatable();

        return datatables()->of($categories)
                ->addColumn('action', function ($language) {
                    return $language->id;
                })
                ->addColumn('all', function ($language) {
                    return $language->id;
                })
                ->removeColumn('id')->make(true);
    }
}

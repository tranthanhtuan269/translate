<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TranslateGroup;
use App\Language;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;

class TranslateGroupController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages      = Language::select('id', 'name')->get();
        $translateGroup = TranslateGroup::getAllTranslateGroup();
        return view('translate_group.index', ['translateGroup' => $translateGroup, 'languages' => $languages]);
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
    public function store(StoreGroupRequest $request)
    {
        $input = $request->all();        
        $group = TranslateGroup::create($input);
        // return redirect('groups');
        $res=array('status'=>"200","Message"=>isset($messages['group.update_success']) ? $messages['group.update_success'] : "The group has been successfully updated!", "group" => $group);

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
    public function update(UpdateGroupRequest $request, $id)
    {
        $group = translateGroup::find($id);
        if($group){
            $group->name         = $request->name;
            $group->description  = $request->description;

            if($group->save()){
                $res=array('status'=>"200","Message"=>isset($messages['group.update_success']) ? $messages['group.update_success'] : "The group has been successfully updated!", "group" => $group);
            }else{
                $res=array('status'=>"404","Message"=>isset($this->messages['group.update_error']) ? $this->messages['group.update_error'] : 'The group hasn\' been successfully updated.' );
            }
        }else{
            $res=array('status'=>"404","Message"=>isset($this->messages['group.update_error']) ? $this->messages['group.update_error'] : 'The group hasn\' been successfully updated.' );
        }
        echo json_encode($res);
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
            $translateGroup = TranslateGroup::find($id);
            if($translateGroup->delete()){
                $res=array('status'=>"200","Message"=>isset($messages['group.delete_success']) ? $messages['group.delete_success'] : "The group has been successfully deleted!");
            }else{
                $res=array('status'=>"204","Message"=>isset($messages['group.delete_error']) ? $messages['group.delete_error'] : "The group hasn't been successfully deleted!");
            }
            echo json_encode($res);
        }
    }

    public function delMulti(Request $request){
        if(isset($request) && $request->input('id_list')){
            $id_list = $request->input('id_list');
            $id_list = rtrim($id_list, ',');

            if(TranslateGroup::deleteMulti($id_list)){
                $res=array('status'=>200,"Message"=>isset($messages['group.delete_multi_success']) ? $messages['group.delete_multi_success'] : "Groups have been successfully deleted!");
            }else{
                $res=array('status'=>"204","Message"=>isset($messages['group.delete_multi_error']) ? $messages['group.delete_multi_error'] : "Groups haven't been successfully deleted!");
            }
            echo json_encode($res);
        }
    }

    public function getDataAjax()
    {
        $groups = TranslateGroup::getDataForDatatable();

        return datatables()->of($groups)
                ->addColumn('action', function ($group) {
                    return $group['id'];
                })
                ->addColumn('all', function ($group) {
                    return $group['id'];
                })
                ->removeColumn('id')->make(true);
    }

    public function getLanguages($group)
    {
        $languages = TranslateGroup::getLanguages($group);
        $res=array('status'=>200, "languages" => $languages);
        echo json_encode($res);
    }

    public function addLanguages(Request $request, $group)
    {
        if(isset($group) && isset($request->langList)){
            // remove all 
            \DB::table('translate_group_language')->where('translate_group_id', $group)->delete();
            // add to
            foreach($request->langList as $lang)
                \DB::table('translate_group_language')->insert([
                    'translate_group_id' => $group, 'language_id' => $lang
            ]);
            $res=array('status'=>200, "languages" => $request->langList);
            echo json_encode($res);
        }else{
            $res=array('status'=>"400","Message"=>isset($this->messages['group.add_language_error']) ? $this->messages['group.add_language_error'] : 'The group hasn\' been successfully updated.' );
            echo json_encode($res);
        }

    }
}

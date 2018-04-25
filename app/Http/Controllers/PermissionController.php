<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Permission;
use Validator,Cache;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('permission.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permission.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $time_created = date('Y-m-d H:i:s');
        $this->validate($request, [
            'name' => 'required',
            'route' => 'required'
        ]);
        $input = $request->all();
        $input['route'] = strtolower($input['route']);
        $input['created_at'] = $time_created;
        $input['updated_at'] = $time_created;
        unset($input['_token']);
        
        Permission::create($input);
        return redirect('permission');
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
                    'name'    => 'required',
                    'route'   => 'required',
                ];

                $messages = [

                ];

                $validator = Validator::make(Input::all(), $rules, $messages);
                if ($validator->fails()) {
                    $errors = [];
                    foreach ($validator->errors()->toArray() as $key => $value) {
                        foreach ($value as $k => $v) {
                            $errors[$key] = $v;
                        }
                    }
                    $res=array('status'=>"400","Message"=>$errors );
                }else{
                    $permission = Permission::find($id);
                    if($permission){
                        $permission->name         = $request->name;
                        $permission->route        = strtolower($request->route);
                        $permission->updated_at   = date('Y-m-d H:i:s');

                        if($permission->save()){
                            $res=array('status'=>"200","Message"=>isset($messages['permission.update_success']) ? $messages['permission.update_success'] : "The permission has been successfully updated!");    
                        }else{
                            $res=array('status'=>"401","Message"=>isset($this->messages['permission.update_error']) ? $this->messages['permission.update_error'] : 'The permission hasn\' been successfully updated.' );    
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
        //
    }

    public function getDataAjax()
    {
        $permissions = Permission::getDataForDatatable();

        return datatables()->of($permissions)
                ->addColumn('action', function ($permission) {
                    return $permission->id;
                })
                ->addColumn('all', function ($permission) {
                    return $permission->id;
                })
                ->removeColumn('id')->make(true);
    }
}

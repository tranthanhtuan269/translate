<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Role;
use App\Permission;
use Validator,Cache;

class RoleController extends Controller
{
    private $messages;

    public function __construct()
    {
        $this->messages = Cache::remember('messages', 1440, function() {
            return \DB::table('messages')->where('role', 1)->pluck('message', 'name');
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('role.create', ['permissions' => $permissions]);
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
        $role = Role::find($id);
        if($role){
            return view('role.show');
        }

        return view('error.404');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        if($role){
            return view('role.edit', ['role' => $role]);
        }

        return view('error.404');
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
        if(isset($id)){
            $role = Role::find($id);
            if(isset($role) && $role->delete()){
                $res=array('status'=>"200","Message"=>isset($messages['role.delete_success']) ? $messages['role.delete_success'] : "The role has been successfully deleted!");
            }else{
                $res=array('status'=>"204","Message"=>isset($messages['role.delete_error']) ? $messages['role.delete_error'] : "The role hasn't been successfully deleted!");
            }
            echo json_encode($res);
        }
    }

    public function delMulti(Request $request){
        if(isset($request) && $request->input('id_list')){
            $id_list = $request->input('id_list');
            $id_list = rtrim($id_list, ',');

            if(Role::deleteMulti($id_list)){
                $res=array('status'=>200,"Message"=>isset($messages['role.delete_multi_success']) ? $messages['role.delete_multi_success'] : "Categories have been successfully deleted!");
            }else{
                $res=array('status'=>"204","Message"=>isset($messages['role.delete_multi_error']) ? $messages['role.delete_multi_error'] : "Categories haven't been successfully deleted!");
            }
            echo json_encode($res);
        }
    }

    public function getDataAjax()
    {
        $roles = Role::getDataForDatatable();

        return datatables()->of($roles)
                ->addColumn('action', function ($role) {
                    return $role->id;
                })
                ->addColumn('all', function ($role) {
                    return $role->id;
                })
                ->removeColumn('id')->make(true);
    }
}

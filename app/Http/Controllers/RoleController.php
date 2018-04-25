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
        return view('role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('role.create');
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
            'name' => 'required'
        ]);
        $input = $request->all();
        $input['permission'] = $input['permission-checked'];
        $input['created_at'] = $time_created;
        $input['updated_at'] = $time_created;
        unset($input['_token']);
        unset($input['permission-checked']);
        
        Role::create($input);
        return redirect('role');
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
        if ($request->ajax()) {
            try{
                $rules = [
                    'name'=>'required'
                ];

                $messages = [
                    'name.required'=> isset($this->messages['role.name.required']) ? $this->messages['role.name.required'] : 'The name field is required.',
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
                    $role = Role::find($id);
                    if($role){
                        $role->name         = $request->name;
                        $role->permission   = $request->permission;
                        $role->updated_at   = date('Y-m-d H:i:s');

                        if($role->save()){
                            $res=array('status'=>"200","Message"=>isset($messages['role.update_success']) ? $messages['role.update_success'] : "The role has been successfully updated!");    
                        }else{
                            $res=array('status'=>"401","Message"=>isset($this->messages['role.update_error']) ? $this->messages['role.update_error'] : 'The role hasn\' been successfully updated.' );    
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

    public function getInfoByID($id){
        if($id){
            $role = Role::find($id);
            if($role){
                $permission = rtrim($role->permission, ',');
                $permission_list = explode(",",$permission);

                $permissions = Permission::select('id', 'name', 'group')->orderby('group', 'asc')->get();
                $group = 1;
                $html = '<optgroup label="User Group">';
                foreach ($permissions as $p) {
                    if(in_array($p->id, $permission_list)){
                        if($p->group != $group){
                            $html .= '</optgroup>';
                            if($p->group == 2)
                            $html .= '<optgroup label="Category Group">';
                            if($p->group == 3)
                            $html .= '<optgroup label="Language Group">';
                            if($p->group == 4)
                            $html .= '<optgroup label="Translate Group">';
                            $group = $p->group;
                        }
                        $html .= '<option value="'.$p->id.'" selected="selected">'.$p->name.'</option>';
                        
                    }else{
                        $html .= '<option value="'.$p->id.'">'.$p->name.'</option>';
                    }
                }
                $html .= '</optgroup>';

                return $html;
            }
        }
        return '';
    }
}

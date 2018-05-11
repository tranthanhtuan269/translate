<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Role;
use Validator,Cache;
use App\Http\Requests\UpdateInfoRequest;
use Auth;

class UserController extends Controller
{
    private $messages;

    public function __construct()
    {
        $this->middleware('auth');
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
        $roles = Role::pluck('name', 'id');
        return view('user.index', ['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'id');
        return view('user.create', ['roles' => $roles]);
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
            'name'      => 'required|min:3|max:100',
            'email'     => 'required|unique:users,email|regex_email:"/^[_a-zA-Z0-9-]{2,}+(\.[_a-zA-Z0-9-]{2,}+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/"',
            'password'  => 'required|min:8|max:100',
            'confirmpassword'=>'required|same:password',
            'role_id'   => 'required',
        ], [
            'email.regex_email' => "The email must be a valid email address."
        ]);
        
        $user           = new User;
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = bcrypt(trim($request->password));
        $user->role_id  = $request->role_id;
        $user->status   = 1; // active
        if($user->save()){
            return redirect('user');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if($user){
            return view('user.show', ['user' => $user]);
        }else{
            return view('error.404');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        if($user){
            return view('user.edit', ['user' => $user]);
        }else{
            return view('error.404');
        }
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
                    'name'              => 'required|min:3|max:100',
                    'email'             => 'required|regex_email:"/^[_a-zA-Z0-9-]{2,}+(\.[_a-zA-Z0-9-]{2,}+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/"|unique:users,email,'.$id,
                    'password'          => 'required|min:8|max:100',
                    'confirmpassword'   => 'required|same:password',
                    'role_id'           => 'required',
                ];

                $messages = [
                    'email.regex_email' => "The email must be a valid email address."
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
                    $user = User::find($id);
                    if($user){
                        $user->name         = $request->name;
                        $user->email        = $request->email;
                        $user->role_id      = $request->role_id;
                        
                        if($request->password != "not_change"){
                            $user->password = bcrypt(trim($request->password));
                        }

                        $user->updated_at   = date('Y-m-d H:i:s');

                        if($user->save()){
                            $res=array('status'=>"200","Message"=>isset($messages['user.update_success']) ? $messages['user.update_success'] : "The user has been successfully updated!");    
                        }else{
                            $res=array('status'=>"401","Message"=>isset($this->messages['user.update_error']) ? $this->messages['user.update_error'] : 'The user hasn\' been successfully updated.' );    
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
            $user = User::find($id);
            if(isset($user) && $user->delete()){
                $res=array('status'=>"200","Message"=>isset($messages['user.delete_success']) ? $messages['user.delete_success'] : "The user has been successfully deleted!");
            }else{
                $res=array('status'=>"204","Message"=>isset($messages['user.delete_error']) ? $messages['user.delete_error'] : "The user hasn't been successfully deleted!");
            }
            echo json_encode($res);
        }
    }

    public function delMulti(Request $request){
        if(isset($request) && $request->input('id_list')){
            $id_list = $request->input('id_list');
            $id_list = rtrim($id_list, ',');

            if(User::deleteMulti($id_list)){
                $res=array('status'=>200,"Message"=>isset($messages['user.delete_multi_success']) ? $messages['user.delete_multi_success'] : "User have been successfully deleted!");
            }else{
                $res=array('status'=>"204","Message"=>isset($messages['user.delete_multi_error']) ? $messages['user.delete_multi_error'] : "User haven't been successfully deleted!");
            }
            echo json_encode($res);
        }
    }

    public function getDataAjax()
    {
        $users = User::getDataForDatatable();

        return datatables()->of($users)
                ->addColumn('action', function ($user) {
                    return $user['id'];
                })
                ->addColumn('all', function ($user) {
                    return $user['id'];
                })
                ->removeColumn('id')->make(true);
    }

    public function getInfoByID($id)
    {
        $user = User::find($id);

        if($user){
            $res=array('status'=>"200","Message"=>isset($messages['user.find_success']) ? $messages['user.find_success'] : "The user is exist!", "user" => $user);    
        }else{
            $res=array('status'=>"401","Message"=>isset($this->messages['user.find_unsuccess']) ? $this->messages['category.find_unsuccess'] : 'The user is not exist.', "user" => null);    
        }
        echo json_encode($res);
    }

    public function updateSefl(UpdateInfoRequest $request)
    {
        if(strlen($request->password) > 0 || strlen($request->repassword) > 0) {
                $this->validate($request, [
                    'password' => 'min:6|max:100|same:repassword',
                    'repassword' => 'min:6|max:100'
                ]);
            }
        
        $user           = Auth::user();
        $user->name     = $request->name;
        if(strlen($request->avatar) > 0){
            $user->avatar   = $request->avatar;
        }
        if(strlen($request->password) > 0){
            $user->password = Hash::make($request->password);
        }

        if($user->save()){
            $res=array('status'=>"200","Message"=>isset($messages['user.update_success']) ? $messages['user.update_success'] : "The update has been success!", "user" => $user);    
        }else{
            $res=array('status'=>"401","Message"=>isset($this->messages['user.update_unsuccess']) ? $this->messages['user.update_unsuccess'] : 'The user is not exist.', "user" => null);    
        }
        echo json_encode($res);
    }
}

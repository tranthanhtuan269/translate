<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,File,Auth;
use Illuminate\Support\Facades\Input;
use App\Category, App\Language;
use Cache;

class SiteController extends Controller
{
	private $messages;

    public function __construct()
    {
        $this->messages = Cache::remember('messages', 1440, function() {
            return \DB::table('messages')->where('category', 2)->pluck('message', 'name');
        });
    }

    public function welcome(){
    	$categories = Category::select('id', 'name')->get();
    	$languages 	= Language::select('id', 'name')->get();
    	return view('welcome', ['categories' => $categories, 'languages' => $languages]);
    }

    public function loginAjax(Request $request){
    	if ($request->ajax()) {
	        try{
		    	$rules = [
		    		'email'=>'required|email',
		    		'password'=>'required'
		    	];

                $messages = [
                    'email.required'=> isset($this->messages['user.email.required']) ? $this->messages['user.email.required'] : 'The email field is required.',
                    'email.email'=> isset($this->messages['user.email.email']) ? $this->messages['user.email.email'] : 'The email must be a valid email address.',
                    'password.required'=> isset($this->messages['user.password.required']) ? $this->messages['user.password.required'] : 'The password field is required.',
                ];


		    	$validator = Validator::make(Input::all(), $rules, $messages);
		        if ($validator->fails()) {
	                $errors = [];
	                foreach ($validator->errors()->toArray() as $key => $value) {
	                	foreach ($value as $k => $v) {
	                		$errors[] = $v;
	                	}
	                }
	                $res=array('Response'=>"Error","Message"=>$errors );
		        }else{
		        	$email = $request->email;
		        	$password = $request->password;
		        	$remember = ( $request->remember == "true" ) ? true : false ;
		        	if(Auth::attempt(['email'=>$email,'password'=>$password, 'status' => 1], $remember)){
				        $res=array('status'=>"200","Message"=>isset($messages['user.login_success']) ? $messages['user.login_success'] : "You are logined");
		        	}else{
				        $res=array('status'=>"401","Message"=>isset($this->messages['user.login_error']) ? $this->messages['user.login_error'] : 'Email or Password incorrect.' );
		       		}
		        }
	            echo json_encode($res);
	        } catch (\Illuminate\Database\QueryException $ex){
	            return $ex->getMessage(); 
	        }
        }
    }

    public function uploadAjaxFile(Request $request){
        $return_data = [];
        if ($request->ajax()) {
            if(isset($request->files)){
                foreach($request->files as $file){
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $destinationPath = base_path('/public/uploads');
                    $textFile = date('His') . $filename;
                    $file->move($destinationPath, $textFile);
                    $return_data[] = ['filename' => $filename, 'new_name' => $textFile ];
                }
                $res=array('status'=>"200","Message"=>isset($messages['translate.upload_success']) ? $messages['translate.upload_success'] : "Upload OK!", "fileUploaded" => $return_data);
            }else{
                $res=array('status'=>"400","Message"=>isset($messages['translate.upload_unsuccess']) ? $messages['translate.upload_unsuccess'] : "An error occurred during save process, please try again");
            }
            echo json_encode($res);
        }else{
            $res=array('status'=>"400","Message"=>isset($messages['translate.upload_unsuccess']) ? $messages['translate.upload_unsuccess'] : "An error occurred during save process, please try again");
            echo json_encode($res);
        }
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}

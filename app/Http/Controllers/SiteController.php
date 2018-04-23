<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,File,Auth;
use Illuminate\Support\Facades\Input;
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

    public function contribute(){
    	return view('site.contribute');
    }

    public function storeContribute(Request $request){
    	
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

    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}

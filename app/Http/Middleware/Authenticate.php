<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Role;
use App\Permission;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard()->check()) {
            $user   = Auth::user();
            if($user->id == 1){
                return $next($request);
            }
            $user_id = $user->id;
            $role_id = $user->role_id;
            $data =  Role::where('id', $role_id)->first();
            $arr = explode(',', $data->permission);  
            $data2 = Permission::find($arr);
            $route_arr = array();

            $segment2 = '';
            foreach ($data2 as $key => $value) {
               $route_arr[] = trim($value->route);
            }
            $route_name = '';
            if ($request->segment(1) != '') {
                $route_name .= $request->segment(1);
            }
            if ($request->segment(2) != '') {
                $segment2 = $request->segment(2);
                $route_name .= '.' . $request->segment(2);
            }
            if ($request->segment(3) != '') {
                $route_name .= '.'.$request->segment(3);
            }

            if($request->ajax() && $request->method() != "delete"){
                return $next($request);
            }

            if (in_array($route_name, $route_arr) || $request->path() == 'home'){
                view()->share('arr_route', $route_arr);
                return $next($request);
            }else{
                return response()->view('error.404');
            }
        }
        return response()->view('error.404');
    }
}

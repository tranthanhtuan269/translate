<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category, App\TranslateGroup;
use App\Common\Helper;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories         = Category::pluck('name', 'id');
        $translateGroup     = TranslateGroup::pluck('name', 'id');
        return view('home', ['categories' => $categories, 'translateGroup' => $translateGroup]);
    }

    public function profile(){
        return view('site.profile');
    }

    public function uploadImage(Request $request){
        $img_file = '';
        if (isset($request->base64)) {
            $data = $request->base64;

            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $filename = time() . '.png';
            file_put_contents(base_path('public/images/avatar/') . $filename, $data);

            return \Response::json(array('code' => '200', 'message' => 'success', 'image_url' => $filename));
        }
        return \Response::json(array('code' => '404', 'message' => 'unsuccess', 'image_url' => ""));
    }

    public function test(){
        $fileSource = public_path() . '/zipfile/zip.zip';
        $fileDest   = public_path() . '/uploads/' . \Auth::id() .'/zip/zip.zip';
        copy($fileSource, $fileDest);
        chmod($fileDest, 0777); 
        die(1);
        dd(file_exists(public_path() . '/zipfile/zip.zip'));
        dd(1);
        $exists = \Storage::disk('zip')->has('zip.zip');
        dd($exists);
        $file = public_path() . '/zipfile/zip.zip';
        $urlZipFolder   = public_path() . '/uploads/' . \Auth::id() .'/zip/zip.zip';

        mkdir(dirname($urlZipFolder), 0777, true);
        copy($file, $urlZipFolder);
    }
}

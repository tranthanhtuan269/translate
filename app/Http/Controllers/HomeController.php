<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category, App\Language;

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
        $categories = Category::pluck('name', 'id');
        $languages  = Language::pluck('name', 'id');
        return view('home', ['categories' => $categories, 'languages' => $languages]);
    }
}

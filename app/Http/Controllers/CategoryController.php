<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Category;
use Validator,Cache;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
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
        return view('category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $time_created = date('Y-m-d H:i:s');
        $input = $request->all();
        $input['created_by'] = auth()->user()->id;
        $input['updated_by'] = auth()->user()->id;
        $input['created_at'] = $time_created;
        $input['updated_at'] = $time_created;
        
        Category::create($input);
        return redirect('category');
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
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::find($id);
        if($category){
            $category->name         = $request->name;
            $category->updated_by   = auth()->user()->id;
            $category->updated_at   = date('Y-m-d H:i:s');

            if($category->save()){
                $res=array('status'=>"200","Message"=>isset($messages['category.update_success']) ? $messages['category.update_success'] : "The category has been successfully updated!");    
            }else{
                $res=array('status'=>"401","Message"=>isset($this->messages['category.update_error']) ? $this->messages['category.update_error'] : 'The category hasn\' been successfully updated.' );    
            }
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
            $category = Category::find($id);
            if(isset($category) && $category->delete()){
                $res=array('status'=>"200","Message"=>isset($messages['category.delete_success']) ? $messages['category.delete_success'] : "The category has been successfully deleted!");
            }else{
                $res=array('status'=>"204","Message"=>isset($messages['category.delete_error']) ? $messages['category.delete_error'] : "The category hasn't been successfully deleted!");
            }
            echo json_encode($res);
        }
    }

    public function delMulti(Request $request){
        if(isset($request) && $request->input('id_list')){
            $id_list = $request->input('id_list');
            $id_list = rtrim($id_list, ',');

            if(Category::deleteMulti($id_list)){
                $res=array('status'=>200,"Message"=>isset($messages['category.delete_multi_success']) ? $messages['category.delete_multi_success'] : "Categories have been successfully deleted!");
            }else{
                $res=array('status'=>"204","Message"=>isset($messages['category.delete_multi_error']) ? $messages['category.delete_multi_error'] : "Categories haven't been successfully deleted!");
            }
            echo json_encode($res);
        }
    }

    public function getDataAjax()
    {
        $categories = Category::getDataForDatatable();

        return datatables()->of($categories)
                ->addColumn('action', function ($category) {
                    return $category->id;
                })
                ->addColumn('all', function ($category) {
                    return $category->id;
                })
                ->removeColumn('id')->make(true);
    }
}

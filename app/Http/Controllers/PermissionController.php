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

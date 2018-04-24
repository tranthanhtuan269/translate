@extends('layouts.app')

@section('content')

<div class="row">
    <div class="offset-sm-1 col-sm-10">
        <div class="row">
            <div class="col-sm-12 card mb-4 box-shadow pr-0 pl-0">
                <div class="card-header" id="permission-header">
                    <h4 class="my-0 font-weight-normal"><i class="fas fa-plus-circle"></i> Create a Permission</h4>
                </div>
                <div class="card-body pb-0 text-left" id="permission-body">
                    {!! Form::open(['url' => 'permission']) !!}
                        <div class="form-group row">
                            <label for="permissionName" class="col-sm-2 col-form-label">Permission Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="permissionName" name="name" placeholder="Eg: Create User">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="permissionName" class="col-sm-2 col-form-label">Route Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="routeName" name="route" placeholder="Eg: user.create">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="permissionName" class="col-sm-2 col-form-label">Group</label>
                            <div class="col-sm-10">
                            {{ Form::select('group', [
                                '1' => 'User', 
                                '2' => 'Category', 
                                '3' => 'Language', 
                                '4' => 'Translate',
                                '5' => 'Permission',
                                '6' => 'Role'
                                ], '1', ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="offset-sm-5 col-sm-2">
                            <button type="submit" class="btn btn-primary mb-2">Submit</button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
</script>
@endsection
@extends('layouts.app')

@section('content')

<div class="row">
    <div class="offset-sm-1 col-sm-10">
        <div class="row">
            <div class="col-sm-12 card mb-4 box-shadow pr-0 pl-0">
                <div class="card-header" id="role-header">
                    <h4 class="my-0 font-weight-normal"><i class="fas fa-plus-circle"></i> Create a Role</h4>
                </div>
                <div class="card-body pb-0 text-left" id="role-body">
                    {!! Form::open(['url' => 'role']) !!}
                        <div class="form-group row">
                            <label for="roleName" class="col-sm-2 col-form-label">Role Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="roleName" name="name" placeholder="Role Name">
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
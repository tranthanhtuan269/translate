@extends('layouts.app')

@section('content')

<div class="row">
    <div class="offset-sm-1 col-sm-10">
        <div class="row">
            <div class="col-sm-12 card mb-4 box-shadow pr-0 pl-0">
                <div class="card-header" id="user-header">
                    <h4 class="my-0 font-weight-normal"><i class="fas fa-plus-circle"></i> Create a User</h4>
                </div>
                <div class="card-body pb-0 text-left" id="user-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {!! Form::open(['url' => 'user']) !!}
                        <div class="form-group row">
                            <label for="userName" class="col-sm-2 col-form-label">User Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="userName" name="name" placeholder="User Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="userEmail" class="col-sm-2 col-form-label">User Email</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="userEmail" name="email" placeholder="User Email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="userPassword" class="col-sm-2 col-form-label">User Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="userPassword" name="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="passConfirm" class="col-sm-2 col-form-label">Password Comfirm</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="passConfirm" name="confirmpassword" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="userRole" class="col-sm-2 col-form-label">User Role</label>
                            <div class="col-sm-10">
                                {{ Form::select('role_id', $roles, null, ['class' => 'form-control', 'id' => 'role_id']) }}
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
@extends('layouts.app')

@section('content')
<style type="text/css">
    .pointer{
        cursor: pointer;
    }
</style>

<div class="row">
    <div class="offset-sm-1 col-sm-10">
        <div class="row">
            <div class="col-sm-12 card mb-4 box-shadow pr-0 pl-0">
                <div class="card-header" id="role-header">
                    <h4 class="my-0 font-weight-normal"><i class="fas fa-plus-circle"></i> Create a Role</h4>
                </div>
                <div class="card-body pb-0 text-left" id="role-body">
                    {!! Form::open(['url' => 'role', 'id' => 'create-role-form']) !!}
                        <div class="form-group row">
                            <label for="roleName" class="col-sm-2 col-form-label">Role Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="roleName" name="name" placeholder="Role Name">
                            </div>
                        </div>
                        <div class="form-group border rounded p-3">
                            <input type="hidden" name="permission-checked" value="">
                            <?php 
                                $UPermissions = App\Permission::where('group', 1)->get();
                            ?>
                            <div class="row">
                            <div class="col-sm-12 font-weight-bold font-italic my-3"><u>User Group</u></div>
                            @foreach($UPermissions as $permission)
                            <div class="col-sm-4">
                                <label class="pointer">
                                    {{ Form::checkbox('permission', $permission->id, false, ['class' => 'permission-check', 'data-id' => $permission->id]) }}
                                    {{ $permission->name }}
                                </label>
                            </div>
                            @endforeach
                            </div>
                            <?php 
                                $CPermissions = App\Permission::where('group', 2)->get();
                            ?>
                            <div class="row">
                            <div class="col-sm-12 font-weight-bold font-italic my-3"><u>Category Group</u></div>
                            @foreach($CPermissions as $permission)
                            <div class="col-sm-4">
                                <label class="pointer">
                                    {{ Form::checkbox('permission', $permission->id, false, ['class' => 'permission-check', 'data-id' => $permission->id]) }}
                                    {{ $permission->name }}
                                </label>
                            </div>
                            @endforeach
                            </div>
                            <?php 
                                $LPermissions = App\Permission::where('group', 3)->get();
                            ?>
                            <div class="row">
                            <div class="col-sm-12 font-weight-bold font-italic my-3"><u>Language Group</u></div>
                            @foreach($LPermissions as $permission)
                            <div class="col-sm-4">
                                <label class="pointer">
                                    {{ Form::checkbox('permission', $permission->id, false, ['class' => 'permission-check', 'data-id' => $permission->id]) }}
                                    {{ $permission->name }}
                                </label>
                            </div>
                            @endforeach
                            </div>
                            <?php 
                                $LPermissions = App\Permission::where('group', 4)->get();
                            ?>
                            <div class="row">
                            <div class="col-sm-12 font-weight-bold font-italic my-3"><u>Group Language Group</u></div>
                            @foreach($LPermissions as $permission)
                            <div class="col-sm-4">
                                <label class="pointer">
                                    {{ Form::checkbox('permission', $permission->id, false, ['class' => 'permission-check', 'data-id' => $permission->id]) }}
                                    {{ $permission->name }}
                                </label>
                            </div>
                            @endforeach
                            </div>
                            <?php 
                                $LPermissions = App\Permission::where('group', 5)->get();
                            ?>
                            <div class="row">
                            <div class="col-sm-12 font-weight-bold font-italic my-3"><u>Translate Group</u></div>
                            @foreach($LPermissions as $permission)
                            <div class="col-sm-4">
                                <label class="pointer">
                                    {{ Form::checkbox('permission', $permission->id, false, ['class' => 'permission-check', 'data-id' => $permission->id]) }}
                                    {{ $permission->name }}
                                </label>
                            </div>
                            @endforeach
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="offset-sm-5 col-sm-2">
                                <div id="create-role-btn" class="btn btn-primary mb-2">Submit</div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var permission_checked = '';
        $('#create-role-btn').click(function(){
            $.each($('.permission-check'), function( index, value ) {
                if($(this).prop('checked')){
                    permission_checked += $(this).attr('data-id') + ',';
                }
            });
            $('input[name=permission-checked]').val(permission_checked);
            $('#create-role-form').submit();
        });
    });
</script>
@endsection
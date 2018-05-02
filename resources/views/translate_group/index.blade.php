@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="header-text border-bottom mb-4 mt-2">Translate Group Config</h1>

            <div class="form-group row">
                <div class="col-sm-8 offset-1">
                    <input type="text" name="search_txt" class="form-control">
                </div>
                <div class="col-sm-1"><div class="btn btn-primary">Search</div></div>
                <div class="col-sm-2"><div class="btn btn-warning">New Group</div></div>
            </div>

            <div class="form-group row">
                <div class="col-sm-11 offset-1">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">Group name</th>
                          <th scope="col">Description</th>
                          <th scope="col">Include Languages</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($translateGroup as $group)
                        <tr>
                          <td>{{ $group->name }}</td>
                          <td>{{ $group->description }}</td>
                          <td>{{ $group->languages }}</td>
                          <td><span class="mr-2 edit-group" data-id="{{ $group->id }}" data-name="{{ $group->name }}"><i class="fas fa-edit"></i></span><span class="delete-group" data-id="{{ $group->id }}"><i class="fas fa-trash"></i></span></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    setTimeout(function(){
       window.location.reload(1);
    }, 1000);

    $(document).ready(function(){
        
    });
</script>
@endsection

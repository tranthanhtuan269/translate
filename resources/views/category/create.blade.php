@extends('layouts.app')

@section('content')

<div class="row">
    <div class="offset-sm-1 col-sm-10">
        <div class="row">
            <div class="col-sm-12 card mb-4 box-shadow pr-0 pl-0">
                <div class="card-header" id="category-header">
                    <h4 class="my-0 font-weight-normal"><i class="fas fa-plus-circle"></i> Create a Category</h4>
                </div>
                <div class="card-body pb-0 text-left" id="category-body">
                    {!! Form::open(['url' => 'category']) !!}
                        <div class="form-group row">
                            <label for="categoryName" class="col-sm-2 col-form-label">Category Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="categoryName" name="name" placeholder="Category Name">
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
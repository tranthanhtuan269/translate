@extends('layouts.app')

@section('content')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h3 class="float-left">List of categories. </h3><span class="float-right" data-toggle="modal" data-target="#create_category_modal"><i class="fas fa-plus-circle icon-header"></i></span></div>

                <div class="card-body">
                    <table id="category-table" class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th width="5%">#</th>
                                <th width="60%">Name</th>
                                <th width="25%">Created_by</th>
                                <th width="10%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <th>{{ $category->id }}</th>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->updater->name }}</td>
                                <td class="text-center">
                                    <i class="fas fa-edit icon-small" data-toggle="modal" data-target="#edit_category_modal" data-id="{{ $category->id }}" data-name="{{ $category->name }}"></i>
                                    <i class="fas fa-trash icon-small" data-id="{{ $category->id }}"></i>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-center"> 
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="create_category_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
            <label for="staticEmail" class="col-sm-4 col-form-label">Category Name</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="categoryName_crt" placeholder="Name">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Create</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit_category_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
            <label for="staticEmail" class="col-sm-4 col-form-label">Category Name</label>
            <div class="col-sm-8">
                <input type="hidden" id="categoryID_upd" value="0">
                <input type="text" class="form-control" id="categoryName_upd" placeholder="Name">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="updateCategory()">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready( function () {
        $('#category-table').DataTable();
    });
</script>
@endsection
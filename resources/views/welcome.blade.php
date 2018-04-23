@extends('layouts.app')

@section('content')
@if(Auth::check())
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.16/api/fnReloadAjax.js"></script>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <input type="file" name="file_upload" type="file" accept=".xml, .json"  multiple>
                        </div>
                        <div class="col">
                            <select class="form-control" id="category-select">
                                <option value="0">Select a Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-control" id="language-select">
                                <option value="0">Select a language</option>
                                @foreach($languages as $language)
                                <option value="{{ $language->id }}">{{ $language->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <button id="translate-btn">Translate</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <table class="table" id="translate-table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col"><input type="checkbox" id="select-all-btn" data-check="false"></th>
                                <th scope="col">Text</th>
                                <th scope="col">Translated</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    <div class="row my-2">
                        <span style="line-height: 30px; margin-left: 30px;">Action on selected rows:</span>
                        <span class="btn btn-sm btn-outline-primary ml-2" id="save-all-btn">Save</span>
                        <span class="btn btn-sm btn-outline-primary ml-2" id="delete-all-btn">Delete</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    input[type=checkbox]{
        cursor: pointer;
    }
    .action-field>span{
        cursor: pointer;
    }
</style>

<script type="text/javascript">
    $(document).ready(function(){
        var files;

        var dataTable = $('#translate-table').DataTable( {
                        stateSave: true,
                        pageLength: 25,
                        aoColumnDefs: [
                            {
                                bSortable: false,
                                aTargets: [ 0,-1, -2 ]
                            }
                        ],
                        fnDrawCallback: function( oSettings ) {
                        }
                    });

        $('input[name="file_upload"]').change(function(event) {
            var _self = $(this);
            files = event.target.files;
            var fileExtension = ['json', 'xml'];
            $.each(files, function( index, value ) {
                if ($.inArray($(this)[0].name.split('.').pop().toLowerCase(), fileExtension) == -1) {
                    $().toastmessage('showErrorToast', "The File is not formatted correctly.");
                    _self.val('');
                }
            });
        });

        $('#translate-btn').click(function(event){
            var category = $('#category-select').val();
            var language = $('#language-select').val();

            if(category <= 0){
                $().toastmessage('showErrorToast', "Please select a category.");
                return;
            }

            if(language <= 0){
                $().toastmessage('showErrorToast', "Please select a language.");
                return;
            }
            
            var formData = new FormData();
            formData.append('category', category);
            formData.append('language', language);
            $.each(files, function(key, value)
            {
                formData.append(key, value);
            });

            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "{{ url('/') }}/uploadAjaxFile",
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                complete: function(data) {
                    if(data.status == 200){
                        $.each(data.responseJSON.translate, function( index, value ) {

                            var html_checkbox   = '<input type="checkbox" name="selectCol" class="check-category" value="'+ this.slug +'">';
                            var html_translate  = '<input type="text" value="'+this.translate+'" id="'+this.slug+'" class="form-control">';
                            var html_action     = '<span class="mr-2 edit-translate" data-id="'+this.slug+'"><i class="fas fa-edit"></i></span><span class="delete-translate" data-id="'+this.slug+'"><i class="fas fa-trash"></i></span>';

                            dataTable.row.add( [
                                html_checkbox,
                                this.text,
                                html_translate,
                                html_action
                            ] ).draw( false );
                        });
                    }
                }
            });
        });
    });
</script>
@else
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

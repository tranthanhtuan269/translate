@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.16/api/fnReloadAjax.js"></script>

<div class="row">
    <div class="offset-sm-1 col-sm-10">
        <div class="row">
            <div class="col-sm-12 card mb-4 box-shadow pr-0 pl-0">
                <div class="card-header" id="permission-header">
                    <h4 class="my-0 font-weight-normal"><i class="far fa-list-alt"></i> Permission <a href="{{ url('/') }}/permission/create" class="float-right"><i class="fas fa-plus-circle"></i></a></h4>
                </div>
                <div class="card-body pb-0 text-left" id="permission-body">
                    <table class="table" id="permission-table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col"><input type="checkbox" id="select-all-btn" data-check="false"></th>
                                <th scope="col">Name</th>
                                <th scope="col">Route</th>
                                <th scope="col">Group</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    <div class="row my-2">
                        <span style="line-height: 30px; margin-left: 30px;">Action on selected rows:</span>
                        <span class="btn btn-sm btn-outline-primary ml-2" id="apply-all-btn">Delete</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="edit_permission_modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Permission</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
            <label for="permissionName_upd" class="col-sm-4 col-form-label">Permission Name</label>
            <div class="col-sm-8">
                <input type="hidden" id="permissionID_upd" value="">
                <input type="text" class="form-control" id="permissionName_upd" placeholder="Permission Name">
            </div>
        </div>
        <div class="form-group row">
            <label for="permissionRoute_upd" class="col-sm-4 col-form-label">Permission Route</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="permissionRoute_upd" placeholder="Permission Route">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="savePermission">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    var dataTable = null;
    var permissionCheckList = [];
    $(document).ready(function(){
        var dataObject = [
            { 
                data: "all",
                class: "all-permission",
                render: function(data, type, row){
                    return '<input type="checkbox" name="selectCol" id="permission-'+ data +'" class="check-permission" value="'+ data +'" data-column="'+ data +'">';
                },
                orderable: false
            },
            { 
                data: "name",
                class: "name-field"
            },
            { 
                data: "route",
                class: "route-field"
            },
            { 
                data: "group",
                class: "route-field",
                render: function(data, type, row){
                    if(data == 1){
                        return 'User';
                    }else if(data == 2){
                        return 'Category';
                    }else if(data == 3){
                        return 'Language';
                    }else if(data == 4){
                        return 'Translate';
                    }else if(data == 5){
                        return 'Permission';
                    }else if(data == 5){
                        return 'Role';
                    }
                    return '';
                },
            },
            { 
                data: "action", 
                class: "action-field",
                render: function(data, type, row){
                    return '<span class="mr-2 edit-permission" data-id="'+data+'" data-name="'+row.name+'" data-route="'+row.route+'" data-group="'+row.group+'"><i class="fas fa-edit"></i></span><span class="delete-permission" data-id="'+data+'"><i class="fas fa-trash"></i></span>';
                },
                orderable: false
            },
        ];

        dataTable = $('#permission-table').DataTable( {
                        serverSide: true,
                        aaSorting: [],
                        stateSave: true,
                        ajax: "{{ url('/') }}/permission/getDataAjax",
                        columns: dataObject,
                        pageLength: 25,
                        colReorder: {
                            fixedColumnsRight: 1,
                            fixedColumnsLeft: 1
                        },
                        fnServerParams: function ( aoData ) {
                            console.log('call event fnServerParams');
                        },
                        fnDrawCallback: function( oSettings ) {
                            addEventListener();
                            checkCheckboxChecked();
                        }
                    });

        //select all checkboxes
        $("#select-all-btn").change(function(){  
            $('#permission-body tbody input[type="checkbox"]').prop('checked', $(this).prop("checked"));
            // save localstore
            setCheckboxChecked();
        });

        $('body').on('click', '#permission-body tbody input[type="checkbox"]', function() {
            if(false == $(this).prop("checked")){
                $("#select-all-btn").prop('checked', false); 
            }
            if ($('#permission-body tbody input[type="checkbox"]:checked').length == $('#permission-body tbody input[type="checkbox"]').length ){
                $("#select-all-btn").prop('checked', true);
            }

            // save localstore
            setCheckboxChecked();
        });

        function setCheckboxChecked(){
            permissionCheckList = [];
            $.each($('.check-permission'), function( index, value ) {
                if($(this).prop('checked')){
                    permissionCheckList.push($(this).attr("id"));
                }
            });
        }

        function checkCheckboxChecked(){
            var count_row = 0;
            var listBarcode = $('.check-permission');
            if(listBarcode.length > 0){
                $.each(listBarcode, function( index, value ) {
                    if(containsObject($(this).attr("id"), permissionCheckList)){
                        $(this).prop('checked', 'true');
                        count_row++;
                    }
                });

                if(count_row == listBarcode.length){
                    $('#select-all-btn').prop('checked', true);
                }else{
                    $('#select-all-btn').prop('checked', false);
                }
            }else{
                $('#select-all-btn').prop('checked', false);
            }
        }

        function containsObject(obj, list) {
            var i;
            for (i = 0; i < list.length; i++) {
                if (list[i] === obj) {
                    return true;
                }
            }

            return false;
        }

        function addEventListener(){
            $('.edit-permission').off('click');
            $('.edit-permission').click(function(){
                var id      = $(this).attr('data-id');
                var name    = $(this).attr('data-name');
                var route   = $(this).attr('data-route');

                $('#edit_permission_modal').modal('show');

                $('#permissionID_upd').val(id);
                $('#permissionName_upd').val(name);
                $('#permissionRoute_upd').val(route);
            });

            $('.delete-permission').off('click');
            $('.delete-permission').click(function(){
                var _self   = $(this);
                var id      = $(this).attr('data-id');
                var data    = {
                    _method             : "DELETE"
                };
                $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN'    : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: baseURL+"/permission/" + id,
                    data: data,
                    method: "POST",
                    dataType:'json',
                    success: function (response) {
                        var html_data = '';
                        if(response.status == 200){
                          _self.parent().parent().hide('slow');
                        }else{
                          $().toastmessage('showErrorToast', response.Message);
                        }
                    },
                    error: function (data) {
                      $().toastmessage('showErrorToast', "Login failed. Please check your internet connection and try again.");
                    }
                });
            });
        }

        $('#savePermission').click(function(){
            var data    = {
                name                : $('#permissionName_upd').val(),
                route               : $('#permissionRoute_upd').val(),
                _method             : "PUT"
            };
            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN'    : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: baseURL+"/permission/" + $('#permissionID_upd').val(),
                data: data,
                method: "POST",
                dataType:'json',
                success: function (response) {
                    var html_data = '';
                    if(response.status == 200){
                      location.reload();
                    }else{
                      $().toastmessage('showErrorToast', response.Message);
                    }
                },
                error: function (data) {
                  $().toastmessage('showErrorToast', "Login failed. Please check your internet connection and try again.");
                }
            });
        });

        $('#apply-all-btn').click(function (){
            var $id_list = '';
            $.each($('.check-permission'), function (key, value){
                if($(this).prop('checked') == true) {
                    $id_list += $(this).attr("data-column") + ',';
                }
            });

            if ($id_list.length > 0) {
                var $id_list = '';
                $.each($('.check-permission'), function (key, value){
                    if($(this).prop('checked') == true) {
                        $id_list += $(this).attr("data-column") + ',';
                    }
                });

                if($id_list.length > 0){
                    var data = {
                        id_list:$id_list,
                        _method:'delete'
                    };
                    $.ajaxSetup(
                    {
                        headers:
                        {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "{{ url('/') }}/permission/delMulti",
                        data: data,
                        success: function (response) {
                            var obj = $.parseJSON(response);
                            if(obj.status == 200){
                                $.each($('.check-permission'), function (key, value){
                                    if($(this).prop('checked') == true) {
                                        $(this).parent().parent().hide("slow");
                                    }
                                });
                                dataTable.ajax.reload(); 
                            }
                        },
                        error: function (data) {
                        }
                    });
                }
            } else {

            }
        });
    });
</script>

<style type="text/css">
    input[type=checkbox]{
        cursor: pointer;
    }
    .action-field>span,
    .fa-plus-circle{
        cursor: pointer;
    }
</style>
@endsection
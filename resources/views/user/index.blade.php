@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.16/api/fnReloadAjax.js"></script>

<div class="row">
    <div class="offset-sm-1 col-sm-10">
        <div class="row">
            <div class="col-sm-12 card mb-4 box-shadow pr-0 pl-0">
                <div class="card-header" id="user-header">
                    <h4 class="my-0 font-weight-normal"><i class="far fa-list-alt"></i> User</h4>
                </div>
                <div class="card-body pb-0 text-left" id="user-body">
                    <table class="table" id="user-table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col"><input type="checkbox" id="select-all-btn" data-check="false"></th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
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

<div id="edit_user_modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
            <label for="userName_upd" class="col-sm-4 col-form-label">User Name</label>
            <div class="col-sm-8">
                <input type="hidden" id="userID_upd" value="">
                <input type="text" class="form-control" id="userName_upd" placeholder="User Name">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveUser">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    var dataTable = null;
    var userCheckList = [];
    $(document).ready(function(){

        $('#edit_user_modal').on('shown.bs.modal', function () {
            console.log($('#userID_upd').val());
            var id      = $('#userID_upd').val();
            $.ajax({
                url: baseURL+"/user/getInfoByID/" + id,
                method: "GET",
                dataType:'json',
                success: function (response) {
                    var html_data = '';
                    if(response.status == 200){
                        console.log(response);
                      // _self.parent().parent().hide('slow');
                    }else{
                      $().toastmessage('showErrorToast', response.Message);
                    }
                },
                error: function (data) {
                  $().toastmessage('showErrorToast', "Login failed. Please check your internet connection and try again.");
                }
            });
        })

        var dataObject = [
            { 
                data: "all",
                class: "all-user",
                render: function(data, type, row){
                    return '<input type="checkbox" name="selectCol" id="user-'+ data +'" class="check-user" value="'+ data +'" data-column="'+ data +'">';
                },
                orderable: false
            },
            { 
                data: "name",
                class: "name-field"
            },
            { 
                data: "email",
                class: "email-field"
            },
            { 
                data: "action", 
                class: "action-field",
                render: function(data, type, row){
                    return '<span class="mr-2 edit-user" data-id="'+data+'" data-name="'+row.name+'"><i class="fas fa-edit"></i></span><span class="delete-user" data-id="'+data+'"><i class="fas fa-trash"></i></span>';
                },
                orderable: false
            },
        ];

        dataTable = $('#user-table').DataTable( {
                        serverSide: true,
                        aaSorting: [],
                        stateSave: true,
                        ajax: "{{ url('/') }}/user/getDataAjax",
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
            $('#user-body tbody input[type="checkbox"]').prop('checked', $(this).prop("checked"));
            // save localstore
            setCheckboxChecked();
        });

        $('body').on('click', '#user-body tbody input[type="checkbox"]', function() {
            if(false == $(this).prop("checked")){
                $("#select-all-btn").prop('checked', false); 
            }
            if ($('#user-body tbody input[type="checkbox"]:checked').length == $('#user-body tbody input[type="checkbox"]').length ){
                $("#select-all-btn").prop('checked', true);
            }

            // save localstore
            setCheckboxChecked();
        });

        function setCheckboxChecked(){
            userCheckList = [];
            $.each($('.check-user'), function( index, value ) {
                if($(this).prop('checked')){
                    userCheckList.push($(this).attr("id"));
                }
            });
        }

        function checkCheckboxChecked(){
            var count_row = 0;
            var listBarcode = $('.check-user');
            if(listBarcode.length > 0){
                $.each(listBarcode, function( index, value ) {
                    if(containsObject($(this).attr("id"), userCheckList)){
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
            $('.edit-user').off('click');
            $('.edit-user').click(function(){
                var id      = $(this).attr('data-id');
                var name    = $(this).attr('data-name');

                $('#edit_user_modal').modal('show');

                $('#userID_upd').val(id);
                $('#userName_upd').val(name);
            });

            $('.delete-user').off('click');
            $('.delete-user').click(function(){
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
                    url: baseURL+"/user/" + id,
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

        $('#saveUser').click(function(){
            var data    = {
                name                : $('#userName_upd').val(),
                _method             : "PUT"
            };
            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN'    : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: baseURL+"/user/" + $('#userID_upd').val(),
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
            $.each($('.check-user'), function (key, value){
                if($(this).prop('checked') == true) {
                    $id_list += $(this).attr("data-column") + ',';
                }
            });

            if ($id_list.length > 0) {
                var $id_list = '';
                $.each($('.check-user'), function (key, value){
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
                        url: "{{ url('/') }}/user/delMulti",
                        data: data,
                        success: function (response) {
                            var obj = $.parseJSON(response);
                            if(obj.status == 200){
                                $.each($('.check-user'), function (key, value){
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
    .action-field>span{
        cursor: pointer;
    }
</style>
@endsection
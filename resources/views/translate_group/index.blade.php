@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.16/api/fnReloadAjax.js"></script>
<!-- Include the plugin's CSS and JS: -->
<script type="text/javascript" src="{{ url('/') }}/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="{{ url('/') }}/css/bootstrap-multiselect.css" type="text/css"/>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="header-text mb-4 mt-2">
              <span class="border-bottom">Translate Group Config</span>
            </h1>

            <div class="form-group row">
                <div class="col-sm-8 offset-1">
                    <input type="text" name="search_txt" class="form-control" placeholder="Search text...">
                </div>
                <div class="col-sm-1"><div class="btn btn-primary btn-search">Search</div></div>
                <div class="col-sm-2"><div class="btn btn-warning" data-toggle="modal" data-target="#add-group-modal">New Group</div></div>
            </div>

            <div class="form-group row">
                <div class="col-sm-11 offset-1 pl-0">
                    <table class="table" id="group-table">
                      <thead class="thead-dark">
                          <tr>
                              <th scope="col"><input type="checkbox" id="select-all-btn" data-check="false"></th>
                              <th scope="col">Name</th>
                              <th scope="col">Description</th>
                              <th scope="col">Languages</th>
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

<!-- Modal -->
<div class="modal fade" id="edit-group-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
            <label for="groupName_upd" class="col-sm-4 col-form-label">Group Name</label>
            <div class="col-sm-8">
                <input type="hidden" id="groupID_upd" value="">
                <input type="text" class="form-control" id="groupName_upd" placeholder="Group Name">
                <div class="alert alert-danger mt-2 px-3 py-1" role="alert" id="error-name">
                  This is a danger alert—check it out!
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="groupName_upd" class="col-sm-4 col-form-label">Group Description</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="groupDescription_upd" placeholder="Group Description">
                <div class="alert alert-danger mt-2 px-3 py-1" role="alert" id="error-description">
                  This is a danger alert—check it out!
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-group-edit">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add-group-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
            <label for="groupName_upd" class="col-sm-4 col-form-label">Group Name</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="groupName_add" placeholder="Group Name">
                <div class="alert alert-danger mt-2 px-3 py-1" role="alert" id="error-name">
                  This is a danger alert—check it out!
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="groupName_upd" class="col-sm-4 col-form-label">Group Description</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="groupDescription_add" placeholder="Group Description">
                <div class="alert alert-danger mt-2 px-3 py-1" role="alert" id="error-description">
                  This is a danger alert—check it out!
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-group-add">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add-language-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Languages to Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
          <input type="hidden" id="groupID_langAdd" value="">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">Language</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody id="languages-table">
              @foreach($languages as $language)
                <tr>
                  <td>{{ $language->name }}</td>
                  <td><input type="checkbox" id="language-{{ $language->id }}" class="check-language" data-id="{{ $language->id }}"></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-language-add">Save</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    var dataTable = null;
    var groupCheckList = [];
    var dataObject = [
        { 
            data: "all",
            class: "all-group",
            render: function(data, type, row){
                return '<input type="checkbox" name="selectCol" id="group-'+ data +'" class="check-group" value="'+ data +'" data-column="'+ data +'">';
            },
            orderable: false
        },
        { 
            data: "name",
            class: "name-field"
        },
        { 
            data: "description",
            class: "description-field"
        },
        { 
            data: "languages",
            class: "languages-field",
            render: function(data, type, row){
              var html = '<i class="fas fa-plus-circle group-language-plus" data-toggle="modal" data-target="#add-language-modal" data-id="'+row.action+'"></i>';
              if(data == null) return '<div></div>' + html;
              return '<div class="language-name"> ' + data + '</div>' + html;
            },
        },
        { 
            data: "action", 
            class: "action-field",
            render: function(data, type, row){
                return '<span class="mr-2 edit-group" data-id="'+data+'" data-name="'+row.name+'" data-description="'+row.description+'"><i class="fas fa-edit"></i></span><span class="delete-group" data-id="'+data+'"><i class="fas fa-trash"></i></span>';
            },
            orderable: false
        },
    ];

    $('#language-list').multiselect();

    dataTable = $('#group-table').DataTable( {
                    serverSide: true,
                    aaSorting: [],
                    stateSave: true,
                    // searching: false,
                    bLengthChange: false,
                    ajax: "{{ url('/') }}/groups/getDataAjax",
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
                    },
                    fnCreatedRow: function (nRow, aData, iDataIndex) {
                        $(nRow).attr('id', 'group-' + aData.action);
                    },
                });
    dataTable.search($('input[name=search_txt]').val()).draw();

    $('.btn-search').on('click', function() {
      dataTable.search($('input[name=search_txt]').val()).draw();
    });

    //select all checkboxes
    $("#select-all-btn").change(function(){  
        $('#group-table tbody input[type="checkbox"]').prop('checked', $(this).prop("checked"));
        // save localstore
        setCheckboxChecked();
    });

    $('body').on('click', '#group-table tbody input[type="checkbox"]', function() {
        if(false == $(this).prop("checked")){
            $("#select-all-btn").prop('checked', false); 
        }
        if ($('#group-table tbody input[type="checkbox"]:checked').length == $('#group-table tbody input[type="checkbox"]').length ){
            $("#select-all-btn").prop('checked', true);
        }

        // save localstore
        setCheckboxChecked();
    });

    function setCheckboxChecked(){
        groupCheckList = [];
        $.each($('.check-group'), function( index, value ) {
            if($(this).prop('checked')){
                groupCheckList.push($(this).attr("id"));
            }
        });
    }

    function checkCheckboxChecked(){
      var count_row = 0;
      var listGroup = $('.check-group');
      if(listGroup.length > 0){
        $.each(listGroup, function( index, value ) {
          if(containsObject($(this).attr("id"), groupCheckList)){
            $(this).prop('checked', 'true');
            count_row++;
          }
        });

        if(count_row == listGroup.length){
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
        $('.edit-group').off('click');
        $('.edit-group').click(function(){
            var id              = $(this).attr('data-id');
            var name            = $(this).attr('data-name');
            var description     = $(this).attr('data-description');

            $('.alert-danger').hide();
            $('#edit-group-modal').modal('show');

            $('#groupID_upd').val(id);
            $('#groupName_upd').val(name);
            $('#groupDescription_upd').val(description);
        });

        $('.delete-group').off('click');
        $('.delete-group').click(function(){
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
                url: baseURL+"/groups/" + id,
                data: data,
                method: "POST",
                dataType:'json',
                success: function (response) {
                    var html_data = '';
                    if(response.status == 200){
                      // _self.parent().parent().hide('slow');
                      dataTable.ajax.reload();
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

    $('#apply-all-btn').click(function (){
        var $id_list = '';
        $.each($('.check-group'), function (key, value){
            if($(this).prop('checked') == true) {
                $id_list += $(this).attr("data-column") + ',';
            }
        });

        if ($id_list.length > 0) {
            var $id_list = '';
            $.each($('.check-group'), function (key, value){
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
                    url: "{{ url('/') }}/groups/delMulti",
                    data: data,
                    success: function (response) {
                        var obj = $.parseJSON(response);
                        if(obj.status == 200){
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

    $('#save-group-edit').click(function(){
      $('.alert-danger').hide();

      var $id           = $('#groupID_upd').val();
      var $name         = $('#groupName_upd').val();
      var $description  = $('#groupDescription_upd').val();
      var data          = {
              id          : $id,
              name        : $name,
              description : $description,
              _method     : "PUT"
      }
      $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          type      : "POST",
          url       : $base_url + "/groups/" + $id,
          data      : data,
          dataType  : 'json',
          complete  : function(data) {
            if(data.status == 200){
              // $('#group-' + $id).find('.name-field').html(data.responseJSON.group.name);
              // $('#group-' + $id).find('.description-field').html(data.responseJSON.group.description);
              // $('#group-' + $id).find('.edit-group').attr("data-name", data.responseJSON.group.name);
              // $('#group-' + $id).find('.edit-group').attr("data-description", data.responseJSON.group.description);
              $('#edit-group-modal').modal('toggle');
              dataTable.ajax.reload(); 
            }
          }, 
          error: function(data){
            var data_out = data.responseJSON;
            $.each( data_out.errors , function( index, value ) {
              $('#error-' + index).html(value);
              $('#error-' + index).show();
            });
          }
        });
    });

    $('#save-group-add').click(function(){
      $('.alert-danger').hide();

      var $name         = $('#groupName_add').val();
      var $description  = $('#groupDescription_add').val();
      var data          = {
              name        : $name,
              description : $description
      }
      $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          type      : "POST",
          url       : $base_url + "/groups",
          data      : data,
          dataType  : 'json',
          complete  : function(data) {
            if(data.status == 200){
              $('#add-group-modal').modal('toggle');
              // dataTable.draw();
              dataTable.ajax.reload(); 
            }
          }, 
          error: function(data){
            var data_out = data.responseJSON;
            $.each( data_out.errors , function( index, value ) {
              $('#error-' + index).html(value);
              $('#error-' + index).show();
            });
          }
        });
    });

    $('#save-language-add').click(function(){
      var $id       = $('#groupID_langAdd').val();
      var $langList = [];
      $('.check-language').each(function( index ) {
        if($(this).prop('checked')){
          $langList.push($(this).attr('data-id'));
        }
      });

      var data          = {
              langList  : $langList
      }

      $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          type      : "POST",
          url       : $base_url + "/groups/" + $id + "/addLanguages",
          data      : data,
          dataType  : 'json',
          complete  : function(data) {
            if(data.status == 200){
              $('#add-language-modal').modal('toggle');
              // dataTable.draw();
              dataTable.ajax.reload(); 
            }
          }
        });
    });

    $('#add-group-modal').on('show.bs.modal', function (event) {
      $('.alert-danger').hide();

      var modal       = $(this);
      modal.find('#groupID_add').val('');
      modal.find('#groupName_add').val('');
      modal.find('#groupDescription_add').val('');
    });

    $('#add-language-modal').on('show.bs.modal', function (event) {
      var $id = $(event.relatedTarget).attr('data-id');
      $('#groupID_langAdd').val($id);
      $('.check-language').prop('checked', false);
      $.ajax({
        type      : "GET",
        url       : $base_url + "/groups/" + $id + "/getLanguages",
        dataType  : 'json',
        complete  : function(data) {
          if(data.status == 200){
            $.each(data.responseJSON.languages, function( index, value ) {
              $('#language-' + value.id).prop('checked', true);
            });
          }
        }
      });
    });
  });
</script>
@endsection

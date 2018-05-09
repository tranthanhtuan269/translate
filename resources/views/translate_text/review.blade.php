@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.16/api/fnReloadAjax.js"></script>
<!-- Include the plugin's CSS and JS: -->
<script type="text/javascript" src="{{ url('/') }}/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="{{ url('/') }}/css/bootstrap-multiselect.css" type="text/css"/>

<div class="container-fluid" id="review-page">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="header-text mb-4 mt-2">
              <span class="border-bottom">Review contributor</span>
            </h1>

            <div class="form-group row">
                <div class="col-sm-11 offset-1 pl-0">
                    <table class="table" id="translate-table">
                      <thead class="thead-dark">
                          <tr>
                              <!-- <th scope="col"><input type="checkbox" id="select-all-btn" data-check="false"></th> -->
                              <th scope="col">Source text</th>
                              <th scope="col">Translated text</th>
                              <th scope="col">In Language</th>
                              <th scope="col">Category</th>
                              <th scope="col">Action</th>
                          </tr>
                      </thead>
                      <tbody>
                          
                      </tbody>
                    </table>
                    <!-- <div class="row my-2">
                        <span style="line-height: 30px; margin-left: 30px;">Action on selected rows:</span>
                        <span class="btn btn-sm btn-outline-primary ml-2" id="apply-all-btn">Delete</span>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="edit-translate-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Translate</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
            <label for="sourceText_upd" class="col-sm-4 col-form-label">Source Text</label>
            <div class="col-sm-8">
                <input type="hidden" id="translateSlug_upd" value="">
                <input type="text" class="form-control" id="sourceText_upd" placeholder="Source Text">
                <div class="alert alert-danger" role="alert" id="error-sourceText">
                  This is a danger alert—check it out!
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="translatedText_upd" class="col-sm-4 col-form-label">Translated Text</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="translatedText_upd" placeholder="Translated Text">
                <div class="alert alert-danger" role="alert" id="error-translatedText">
                  This is a danger alert—check it out!
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="language_upd" class="col-sm-4 col-form-label">Language</label>
            <div class="col-sm-8">
            	<input type="hidden" id="language_before" value="">
            	{{ Form::select('language', $languages, null, ['placeholder' => 'Pick a language', 'id' => 'language_upd', 'class' => 'form-control']) }}
                <div class="alert alert-danger" role="alert" id="error-language">
                  This is a danger alert—check it out!
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="category_upd" class="col-sm-4 col-form-label">Category</label>
            <div class="col-sm-8">
            	<input type="hidden" id="category_before" value="">
            	{{ Form::select('category', $categories, null, ['placeholder' => 'Pick a category', 'id' => 'category_upd', 'class' => 'form-control']) }}
                <div class="alert alert-danger" role="alert" id="error-category">
                  This is a danger alert—check it out!
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-translate-edit">Confirm</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    var dataTable = null;
    var groupCheckList = [];
    $(".alert-danger").hide();
    var dataObject = [
        /*{ 
            data: "all",
            class: "all-translate",
            render: function(data, type, row){
                return '<input type="checkbox" name="selectCol" class="check-translate" data-slug="'+row.slug+'" data-category="'+row.category_id+'" data-language="'+row.language_id+'">';
            },
            orderable: false
        },*/
        { 
            data: "source_text",
            class: "source_text-field"
        },
        { 
            data: "trans_text",
            class: "trans_text-field"
        },
        { 
            data: "language_id",
            class: "language_name-field",
            render: function(data, type, row){
            	return row.language_name;
            },
        },
        { 
            data: "category_id",
            class: "category_name-field",
            render: function(data, type, row){
            	return row.category_name;
            },
        },
        { 
            data: "action", 
            class: "action-field",
            render: function(data, type, row){
            	return '<span class="mr-2 edit-translate" data-slug="'+row.slug+'" data-category="'+row.category_id+'" data-language="'+row.language_id+'">Edit</span><span class="delete-translate"  data-slug="'+row.slug+'" data-category="'+row.category_id+'" data-language="'+row.language_id+'">Confirm</span>';
            },
            orderable: false
        },
    ];

    dataTable = $('#translate-table').DataTable( {
                    serverSide: true,
                    aaSorting: [],
                    stateSave: true,
                    // searching: false,
                    bLengthChange: false,
                    ajax: "{{ url('/') }}/translates/getDataAjaxReview",
                    columns: dataObject,
                    pageLength: 25,
                    colReorder: {
                        fixedColumnsRight: 1,
                        fixedColumnsLeft: 1
                    },
                    fnServerParams: function ( aoData ) {

                    },
                    fnDrawCallback: function( oSettings ) {
                        addEventListener();
                        // checkCheckboxChecked();
                    },
                    fnCreatedRow: function (nRow, aData, iDataIndex) {
                        $(nRow).attr('id', 'group-' + aData.action);
                    },
                });

    //select all checkboxes
    /*$("#select-all-btn").change(function(){  
        $('#translate-table tbody input[type="checkbox"]').prop('checked', $(this).prop("checked"));
        // save localstore
        setCheckboxChecked();
    });

    $('body').on('click', '#translate-table tbody input[type="checkbox"]', function() {
        if(false == $(this).prop("checked")){
            $("#select-all-btn").prop('checked', false); 
        }
        if ($('#translate-table tbody input[type="checkbox"]:checked').length == $('#translate-table tbody input[type="checkbox"]').length ){
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
    }*/

    function addEventListener(){
        $('.edit-translate').off('click');
        $('.edit-translate').click(function(){
        	var row = $(this).parent().parent();

            var source_text = row.find('.source_text-field').html();
            var trans_text  = row.find('.trans_text-field').html();
            var category    = $(this).attr('data-category');
            var language    = $(this).attr('data-language');
            var slug      	= $(this).attr('data-slug');

            $('.alert-danger').hide();
            $('#edit-translate-modal').modal('show');

            $('#translateSlug_upd').val(slug);
            $('#sourceText_upd').val(source_text);
            $('#translatedText_upd').val(trans_text);
            $('#language_before').val(language);
            $('#language_upd').val(language);
            $('#category_before').val(category);
            $('#category_upd').val(category);
        });

        $('.delete-translate').off('click');
        $('.delete-translate').click(function(){
            var _self   = $(this);
            
            var $category    		= $(this).attr('data-category');
            var $language    		= $(this).attr('data-language');
            var $slug      			= $(this).attr('data-slug');

            var data 		= {
				slug          		: $slug,
				language          	: $language,
				category          	: $category,
				_method     		: "PUT"
	      	}

            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN'    : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: baseURL+"/translates/confirm",
                data: data,
                method: "POST",
                dataType:'json',
                success: function (response) {
                    var html_data = '';
                    if(response.status == 200){
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
/*
    $('#apply-all-btn').click(function (){
        var $obj_list = [];

        $.each($('.check-translate'), function (key, value){
            if($(this).prop('checked') == true) {

            	var slug 				= $(this).attr('data-slug');
            	var category 			= $(this).attr('data-category');
            	var language 			= $(this).attr('data-language');

            	$obj = {
            		slug 				: slug,
            		category 			: category,
            		language 			: language
            	}

            	$obj_list.push($obj);
            }
        });


        if($obj_list.length > 0){
            var data = {
                obj_list 	: JSON.stringify($obj_list),
                _method 	: 'delete'
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
                url: "{{ url('/') }}/translates/delMulti",
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
    });*/

    $('#save-translate-edit').click(function(){
      $('.alert-danger').hide();

      var $slug           	= $('#translateSlug_upd').val();
      var $sourceText       = $('#sourceText_upd').val();
      var $translatedText   = $('#translatedText_upd').val();
      var $language         = $('#language_upd').val();
      var $language_before  = $('#language_before').val();
      var $category  		= $('#category_upd').val();
      var $category_before  = $('#category_before').val();

      var data 		= {
			slug          		: $slug,
			sourceText        	: $sourceText,
			translatedText    	: $translatedText,
			language          	: $language,
			language_before     : $language_before,
			category          	: $category,
			category_before     : $category_before,
			_method     		: "PUT"
      }

      $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          type      : "POST",
          url       : $base_url + "/translates/adminUpdate",
          data      : data,
          dataType  : 'json',
          beforeSend: function() {
              $(".alert-danger").hide();
          },
          complete  : function(data) {
            if(data.status == 200){
              	$('#edit-translate-modal').modal('toggle');
              	dataTable.ajax.reload(); 
            }else{
            	$.each(data.responseJSON.errors, function( index, value ) {
                    $('#error-' + index).show();
                    $('#error-' + index).html(value);
                });
                $().toastmessage('showErrorToast', data.responseJSON.message);
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
  });
</script>
@endsection

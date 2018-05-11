@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.16/api/fnReloadAjax.js"></script>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="header-text mb-4 mt-2">
                <span class="border-bottom">
                Add new translation
                </span>
            </h1>
            <input id="source-file" type="file" class="d-none" accept=".xlsx, .xls, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
            <input id="translated-folder" type="file" onchange="getfolder(event)" class="d-none" webkitdirectory mozdirectory msdirectory odirectory directory multiple />
            <div class="form-group row">
                <div class="col-sm-1 lh-38">
                    Category:
                </div>
                <div class="col-sm-3">
                    {{ Form::select('category', $categories, null, ['class' => 'form-control', 'id' => 'category-select', 'placeholder'=> 'Select a category']) }}
                </div>
                <div class="col-sm-2 text-right lh-38">
                    Source file:
                </div>
                <div class="col-sm-4">
                    {{ Form::text('path_to_source', '', ['class' => 'form-control', 'id' => 'source-file-name', 'placeholder' => 'Path to source file', 'readonly' => 'true']) }}
                </div>
                <div class="col-sm-2">
                    <div class="btn btn-primary" id="browse-source">Browse</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-2 lh-38">
                    Translated folder:
                </div>
                <div class="col-sm-8">
                    {{ Form::text('path_to_source', '', ['class' => 'form-control', 'id' => 'translated-folder-name', 'placeholder' => 'Path to folder contains all translated files', 'readonly' => 'true']) }}
                </div>
                <div class="col-sm-2">
                    <div class="btn btn-primary" id="browse-translated">Browse</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12 text-center">
                    <div class="btn btn-primary" id="import-btn">Import</div>
                </div>
            </div>

            <div class="group-miss" style="display: none;">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <table class="table" id="missed-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Source text</th>
                                    <th scope="col">Missed language</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12 text-center">
                        <div class="btn btn-primary translate-all">Translate all</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var table = null;
    var source_file;
    var translated_files;
    

    $(document).ready(function(){
        $base_url = $('base').attr('href');

        var dataObject = [
            { 
                data: "source_text",
                class: "source_text-field"
            },
            { 
                data: "language_name",
                class: "language_name-field"
            },
            { 
                data: "action", 
                class: "action-field",
                orderable: false
            },
        ];

        table = $('#missed-table').DataTable({
            aaSorting: [],
            stateSave: true,
            columns: dataObject,
            pageLength: 25,
        });

        $('#browse-source').click(function(){
            $('#source-file').click();
        });

        $('#source-file').change(function(event){
            source_file = event.target.files;
            if($(this).val().length > 0){
                $('input[name=excel]').removeClass('hide');
                var fileExtension = ['xlsx', 'xls', 'csv'];
                if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                    $().toastmessage('showErrorToast', "The File is not formatted correctly");
                    $('#source-file-name').val('');
                    $(this).val('').clone(true);
                }else{
                    $('#source-file-name').val($(this).val());
                }
            }
        });

        $('#browse-translated').click(function(){
            $('#translated-folder').click();
        });

        $('#import-btn').click(function(){
            if($('#category-select').val() == ''){
                $.ajsrConfirm({
                    message: "Please select a category!",
                    confirmButton: "OK",
                    cancelButton : "Cancel",
                    showCancel: false,
                    nineCorners: false,
                });
                return;
            }else{
                if($('#source-file-name').val() == ''){
                    $.ajsrConfirm({
                        message: "Please select a source file!",
                        confirmButton: "OK",
                        cancelButton : "Cancel",
                        showCancel: false,
                        nineCorners: false,
                    });
                    return;
                }else{
                    if($('#translated-folder-name').val() == ''){
                        $.ajsrConfirm({
                            message: "Please select a translated folder!",
                            confirmButton: "OK",
                            cancelButton : "Cancel",
                            showCancel: false,
                            nineCorners: false,
                        });
                        return;
                    }else{
                        importSource();
                    }
                }
            }
        });

        function importSource(){
            var formData = new FormData();

            $.each(source_file, function( key, value ) {
                formData.append(key, value);
                formData.append('category', $('#category-select').val());
            });

            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: $base_url + "/translates/uploadSourceFile",
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function( xhr ) {

                },
                complete: function(data) {
                    if(data.status == 200){
                        importTranslate();
                    }
                }
            });
        }

        function importTranslate(){
            var formData = new FormData();

            $.each(translated_files, function( key, value ) {
                formData.append(key, value);
                formData.append('category', $('#category-select').val());
            });

            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: $base_url + "/translates/uploadTranslateFolder",
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function( xhr ) {

                },
                complete: function(data) {
                    if(data.status == 200){
                        $.ajsrConfirm({
                            message: "Files uploaded successfully!",
                            confirmButton: "OK",
                            cancelButton : "Cancel",
                            showCancel: false,
                            nineCorners: false,
                        });
                        $.each(data.responseJSON.listTextMiss, function( index, value ) {
                            var keyword         = $(this)[0].keyword;
                            var source_text     = $(this)[0].source_text;
                            var language_id     = $(this)[0].language_id;
                            var language_code   = $(this)[0].language_code;
                            var language_name   = $(this)[0].language_name;
                            var category_id     = $(this)[0].category_id;

                            var row = table.row.add( {
                                "source_text"   : source_text,
                                "language_name" : language_name,
                                "action"        : "<div class='translate_text' data-key='"+keyword+"' data-language='"+language_id+"' data-source='"+source_text+"' data-language-code='"+language_code+"' data-category='"+category_id+"'><u>Translate</u></div>"
                            } );
                        });
                        table.draw();
                        addAction();
                        if(data.responseJSON.listTextMiss.length > 0){
                            $('.group-miss').show();
                        }else{
                            $('.group-miss').hide();
                        }
                    }else{
                        $.ajsrConfirm({
                            message: "Upload error!",
                            confirmButton: "OK",
                            cancelButton : "Cancel",
                            showCancel: false,
                            nineCorners: false,
                        });
                        return;
                    }
                }
            });
        }

        function addAction(){
            $('.translate_text').off('click');
            $('.translate_text').click(function(){
                var sefl            = $(this).parent().parent();
                var key             = $(this).attr('data-key');
                var source          = $(this).attr('data-source');
                var language        = $(this).attr('data-language');
                var language_code   = $(this).attr('data-language-code');
                var category        = $(this).attr('data-category');

                var data = {
                    keyword         : key,
                    source          : source,
                    language        : language,
                    language_code   : language_code,
                    category        : category                    
                }

                $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: $base_url + "/translate/auto",
                    data: data,
                    dataType: 'json',
                    complete: function(data) {
                        if(data.status == 200){
                            table
                                .row( sefl )
                                .remove()
                                .draw();
                        }
                    }
                });
            });
        }

        $('.translate-all').click(function(){
            $('.translate_text').each(function( index ) {
                var sefl            = $(this).parent().parent();
                var key             = $(this).attr('data-key');
                var source          = $(this).attr('data-source');
                var language        = $(this).attr('data-language');
                var language_code   = $(this).attr('data-language-code');
                var category        = $(this).attr('data-category');

                var data = {
                    keyword         : key,
                    source          : source,
                    language        : language,
                    language_code   : language_code,
                    category        : category                    
                }

                $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: $base_url + "/translate/auto",
                    data: data,
                    dataType: 'json',
                    complete: function(data) {
                        if(data.status == 200){
                            table
                                .row( sefl )
                                .remove()
                                .draw();
                            if(!table.data().count()){
                                $('.group-miss').hide();
                            }
                        }
                    }
                });
            });
        });
    });

    function getfolder(e) {
        translated_files = e.target.files;
        console.log(translated_files)
        var path = translated_files[0].webkitRelativePath;
        var Folder = path.split("/");
        $('#translated-folder-name').val(Folder[0]);
    }
</script>
@endsection

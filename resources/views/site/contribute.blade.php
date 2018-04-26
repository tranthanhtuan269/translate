@extends('layouts.app')

@section('content')
<style type="text/css">
    .custom-label{
        line-height: 38px;
    }
    .head-hold{
        padding: 8px;
        border:1px solid #ccc;
        text-align: center
    }
    .body-hold{
        min-height: 300px;
        border:1px solid #ccc;
        border-top:none;
        display: table;
        width: 100%;
        text-align: left;
        position: relative;
    }
    .body-hold>span.text-content{
        padding: 8px;
        display: table-cell;
        vertical-align: middle;
    }
    .body-hold>.button-group{
        position: absolute;
        bottom: 6px;
        left: 2%;
        width: 100%;
    }
    .body-hold>.button-group>#improve_btn{
        width: 96%;
    }
    .body-hold>.button-group>#save_btn,
    .body-hold>.button-group>#cancel_btn{
        width: 48%;
    }
    .pointer{
        cursor: pointer;
    }
    .body-hold>span.text-editor{
        display: none;
    }
    .body-hold>span.text-editor>#comment{
        border: none;
    }
</style>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="header-text">Language contributor</h1>

            <div class="form-group row">
                <div class="col-sm-1 custom-label">Category:</div>
                <div class="col-sm-5">
                    {{ Form::select('category', $categories, null, ['placeholder' => 'Pick a category', 'class' => 'form-control']) }}
                </div>
                <div class="col-sm-1 custom-label">Language:</div>
                <div class="col-sm-5">
                    {{ Form::select('language', $languages, null, ['placeholder' => 'Pick a language', 'class' => 'form-control']) }}
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-sm-6" id="source-hold">
                    <div class="head-hold"><span class="prev-text"><i class="fas fa-chevron-left mr-3 pointer"></i></span>SOURCE TEXT (<span id="currentNumber">1</span>/<span id="totalNumber">12</span>)<span class="next-text"><i class="fas fa-chevron-right ml-3 pointer"></i></span></div>
                    <div class="body-hold">
                        <span class="text-content">aaa</span>
                    </div>
                </div>
                <div class="col-sm-6" id="translate-hold">
                    <div class="head-hold">TRANSLATED TEXT</div>
                    <div class="body-hold">
                        <span class="text-content">aaa</span>
                        <span class="text-editor"><textarea class="form-control" id="comment" rows="10"></textarea></span>
                        <div class="button-group">
                            <button class="btn btn-primary" id="improve_btn">Improve this translation</button>
                            <button class="btn btn-primary d-none" id="save_btn">Improve</button>
                            <button class="btn btn-default d-none" id="cancel_btn">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    setTimeout(function(){
       window.location.reload(1);
    }, 1000000);

    $(document).ready(function(){
        $listText = [];
        $('.next-text').click(function(){
            var currentText = parseInt($('#currentNumber').html());
            var totalText   = parseInt($('#totalNumber').html());

            if(currentText == totalText){
                return;   
            }else{
                $('#currentNumber').html(currentText+1);
            }
        });

        $('.prev-text').click(function(){
            var currentText = parseInt($('#currentNumber').html());
            var totalText   = parseInt($('#totalNumber').html());

            if(currentText == 1) {
                return;
            }else{
                $('#currentNumber').html(currentText-1);
            } 
        });

        $( "select[name=category]" ).change(function() {
            if($( "select[name=category]" ).val() != ''){
                if($( "select[name=language]" ).val() != ''){
                    // request
                    RequestGetTranslate();
                }
            }
        });

        $( "select[name=language]" ).change(function() {
            if($( "select[name=language]" ).val() != ''){
                if($( "select[name=category]" ).val() != ''){
                    // request
                    RequestGetTranslate();
                }
            }
        });

        $('#translate-hold .button-group #improve_btn').click(function(){
            var $translate_content = $('#translate-hold .text-content').html();
            $('#translate-hold .text-editor #comment').val($translate_content);
            BeforeProcess();
        });

        $('#translate-hold .button-group #save_btn').click(function(){
            var $translate_content  = $('#translate-hold .text-editor #comment').val();
            var category            = $('select[name=category]').val();
            var language            = $('select[name=language]').val();
            var slug                = $('input[name=slug]').val();
            
            var _self   = $(this);
            var id      = $(this).attr('data-id');
            var data    = {
                category            : category,
                language            : language,
                slug                : slug,
                translate           : $translate_content,
                _method             : "PUT"
            };
            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN'    : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: baseURL+"/contributor/save",
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

            AfterProcess();
        });

        $('#translate-hold .button-group #cancel_btn').click(function(){
            $translate_content = $('#translate-hold .text-content').html();
            $('#translate-hold .text-editor #comment').val($translate_content);
            AfterProcess();
        });

        function BeforeProcess(){
            $('#translate-hold .text-content').hide();
            $('#translate-hold .text-editor').show();
            $('#translate-hold .button-group #save_btn').removeClass('d-none');
            $('#translate-hold .button-group #cancel_btn').removeClass('d-none');
            $('#translate-hold .button-group #improve_btn').addClass('d-none');
        }

        function AfterProcess(){
            $('#translate-hold .text-content').show();
            $('#translate-hold .text-editor').hide();
            $('#translate-hold .button-group #save_btn').addClass('d-none');
            $('#translate-hold .button-group #cancel_btn').addClass('d-none');
            $('#translate-hold .button-group #improve_btn').removeClass('d-none');
        }

        function RequestGetTranslate(){
            var category            = $('select[name=category]').val();
            var language            = $('select[name=language]').val();
            var data    = {
                category            : category,
                language            : language
            };
            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN'    : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: baseURL+"/contributor/get",
                data: data,
                method: "GET",
                dataType:'json',
                success: function (response) {
                    var html_data = '';
                    if(response.status == 200){

                    }else{
                      $().toastmessage('showErrorToast', response.Message);
                    }
                },
                error: function (data) {
                  $().toastmessage('showErrorToast', "Login failed. Please check your internet connection and try again.");
                }
            });
        }
    });
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="header-text mb-4 mt-2">
                <span class="border-bottom">
                Language contributor
                </span>
            </h1>

            <div class="form-group row">
                <div class="col-sm-1 custom-label font-weight-bold">Category:</div>
                <div class="col-sm-5">
                    {{ Form::select('category', $categories, null, ['placeholder' => 'Pick a category', 'class' => 'form-control']) }}
                </div>
                <div class="col-sm-1 custom-label font-weight-bold">Language:</div>
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
                    <div class="head-hold"><span class="prev-text"><i class="fas fa-chevron-left mr-3 pointer"></i></span> <span class="font-weight-bold"> SOURCE TEXT (<span id="currentNumber">0</span>/<span id="totalNumber">0</span>)</span><span class="next-text"><i class="fas fa-chevron-right ml-3 pointer"></i></span></div>
                    <div class="body-hold">
                        <span class="text-content"></span>
                    </div>
                </div>
                <div class="col-sm-6" id="translate-hold">
                    <div class="head-hold"> <span class="font-weight-bold">TRANSLATED TEXT</span></div>
                    <div class="body-hold">
                        <span class="text-content" contenteditable="false"></span>
                        <div class="button-group">
                            <button class="btn btn-primary" id="improve_btn" disabled>Improve this translation</button>
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
    $(document).ready(function(){
        var $listText = [];
        var currentText = 0;
        var totalText = 0;
        $('.next-text').click(function(){
            currentText = parseInt($('#currentNumber').html());
            totalText   = parseInt($('#totalNumber').html());

            if(currentText == totalText){
                return;   
            }else{
                $('#currentNumber').html(currentText+1);
                importText(currentText);
            }
        });

        $('.prev-text').click(function(){
            currentText = parseInt($('#currentNumber').html());
            totalText   = parseInt($('#totalNumber').html());

            if(currentText == 1) {
                return;
            }else{
                console.log(currentText);
                $('#currentNumber').html(currentText-1);
                importText(currentText-2);
            } 
        });

        $( "select[name=category]" ).change(function() {
            if($( "select[name=language]" ).val() != '' && $( "select[name=category]" ).val() != ''){
                // request
                RequestGetTranslate();
            }
        });

        $( "select[name=language]" ).change(function() {
            if($( "select[name=language]" ).val() != '' && $( "select[name=category]" ).val() != ''){
                // request
                RequestGetTranslate();
            }
        });

        $('#translate-hold .button-group #improve_btn').click(function(){
            var $translate_content = $('#translate-hold .text-content').html();
            BeforeProcess();
        });

        $('#translate-hold .button-group #save_btn').click(function(){
            var $source_content     = $('#source-hold .text-content').html();
            var $translate_content  = $('#translate-hold .text-content').html();
            var category            = $('select[name=category]').val();
            var language            = $('select[name=language]').val();
            var keyword                = $('#source-hold>.body-hold>.text-content').attr('data-keyword');
            
            var _self   = $(this);
            var id      = $(this).attr('data-id');
            var data    = {
                category            : category,
                language            : language,
                keyword             : keyword,
                source_text         : $source_content,
                trans_text          : $translate_content,
                _method             : "PUT"
            };
            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN'    : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: baseURL+"/contributor",
                data: data,
                method: "POST",
                dataType:'json',
                success: function (response) {
                    var html_data = '';
                    if(response.status == 200){
                        $().toastmessage('showSuccessToast', response.Message);
                        console.log('currentText: ' + currentText);
                        console.log('totalText: ' + totalText);
                        if(currentText + 1 < totalText){
                            // pop object from array
                            $listText.splice(currentText,1);
                            totalText = totalText -1;
                            importText(currentText);
                        }else{
                            if(totalText > 1){
                                // pop object from array
                                $listText.splice(currentText,1);
                                totalText = totalText -1;
                                importText(currentText-1);    
                            }else{
                                // pop object from array
                                $listText.splice(currentText,1);
                                loadNull();
                            }
                        }
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
            $('#translate-hold .text-content').html($translate_content);
            AfterProcess();
        });

        function BeforeProcess(){
            // $('#translate-hold .text-content').hide();
            // $('#translate-hold .text-editor').show();
            $('#translate-hold .text-content').attr('contenteditable', true);
            $('#translate-hold .button-group #save_btn').removeClass('d-none');
            $('#translate-hold .button-group #cancel_btn').removeClass('d-none');
            $('#translate-hold .button-group #improve_btn').addClass('d-none');
        }

        function AfterProcess(){
            // $('#translate-hold .text-content').show();
            // $('#translate-hold .text-editor').hide();
            $('#translate-hold .text-content').attr('contenteditable', false);
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
            $.ajax({
                url: baseURL+"/contributor/getData",
                data: data,
                method: "GET",
                dataType:'json',
                success: function (response) {
                    var html_data = '';
                    if(response.status == 200){
                        $listText = response.translateTexts;
                        totalText = $listText.length;
                        if(totalText == 0){
                            loadNull();
                        }else{
                            // load the first object
                            importText(0);
                            $('#improve_btn').removeAttr("disabled");    
                        }
                    }else{
                        // show error message
                        $().toastmessage('showErrorToast', response.Message);
                    }
                },
                error: function (data) {
                  $().toastmessage('showErrorToast', "Login failed. Please check your internet connection and try again.");
                }
            });
        }

        function importText(id) {
            // set current text to 1
            $('#currentNumber').html(id + 1);
            // set total of text equal $listText.length
            $('#totalNumber').html($listText.length);
            $('#source-hold>.body-hold>.text-content').html($listText[id].source_text);
            $('#source-hold>.body-hold>.text-content').attr('data-keyword', $listText[id].keyword);
            $('#translate-hold>.body-hold>.text-content').html($listText[id].trans_text);
        }

        function loadNull(){
            $('#currentNumber').html('0');
            $('#totalNumber').html('0');
            $('#source-hold>.body-hold>.text-content').html('');
            $('#translate-hold>.body-hold>.text-content').html('');
            $('#improve_btn').attr("disabled", "disabled");
        }

        function changeDataOfList( keyword, trans ) {
            for (var i in $listText) {
                if ($listText[i].keyword == keyword) {
                    $listText[i].trans = trans;
                    break; //Stop this loop, we found it!
                }
            }
        }
    });
</script>
@endsection

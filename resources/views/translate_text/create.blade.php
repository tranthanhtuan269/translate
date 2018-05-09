@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="header-text mb-4 mt-2">
                <span class="border-bottom">
                Add new translation
                </span>
            </h1>
            
            <div class="form-group row">
                <div class="offset-sm-4 col-sm-4 link-list">
                    <a href="{{ url('/') }}/translates/create" class="active">Manual</a>|<a href="{{ url('/') }}/translates/create-form-file"> From files</a> 
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-sm-4 custom-label-right font-weight-bold">Source text:</div>
                <div class="col-sm-4">
                    {{ Form::text('source_text', '', ['class' => 'form-control', 'placeholder' => 'Source text']) }}
                    <div class="alert alert-danger" id="sourceText-error" role="alert" style="display: none;">
                        This is a danger alert—check it out!
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4 custom-label-right font-weight-bold">Translated text:</div>
                <div class="col-sm-4">
                    {{ Form::text('translated_text', '', ['class' => 'form-control', 'placeholder' => 'Translated text']) }}
                    <div class="alert alert-danger" id="translatedText-error" role="alert" style="display: none;">
                        This is a danger alert—check it out!
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4 custom-label-right font-weight-bold">In language:</div>
                <div class="col-sm-4">
                    {{ Form::select('language', $languages, null, ['class' => 'form-control', 'id' => 'language-select', 'placeholder'=> 'Select a language']) }}
                    <div class="alert alert-danger" id="language-error" role="alert" style="display: none;">
                        This is a danger alert—check it out!
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4 custom-label-right font-weight-bold">Category:</div>
                <div class="col-sm-4">
                    {{ Form::select('category', $categories, null, ['class' => 'form-control', 'id' => 'category-select', 'placeholder'=> 'Select a category']) }}
                    <div class="alert alert-danger" id="category-error" role="alert" style="display: none;">
                        This is a danger alert—check it out!
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4 custom-label-right font-weight-bold">Status:</div>
                <div class="col-sm-4">
                    {{ Form::select('status', [0 => 'Auto', 1 => 'Contributor', 2 => 'Comfirmed'], null, ['class' => 'form-control', 'id' => 'status-select', 'placeholder'=> 'All']) }}
                    <div class="alert alert-danger" id="status-error" role="alert" style="display: none;">
                        This is a danger alert—check it out!
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="offset-sm-4 col-sm-4 text-center">
                    <div class="btn btn-primary" id="save-change">Add new</div>
                    <div class="btn btn-secondary" id="cancel">Cancel</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $base_url = $('base').attr('href');

        $('#save-change').click(function(){
            var source_text         = $('input[name=source_text]').val();
            var translated_text     = $('input[name=translated_text]').val();
            var language            = $('#language-select').val();
            var category            = $('#category-select').val();
            var status              = $('#status-select').val();

            var data    = {
                source_text         : source_text,
                translated_text     : translated_text,
                language            : language,
                category            : category,
                status              : status
            };

            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: $base_url + "/translates",
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    $('.alert-danger').hide();
                },
                complete: function(data) {
                    if(data.status == 200){
                        $().toastmessage('showSuccessToast', data.responseJSON.Message);
                        clearForm();
                    }else{
                        $.each(data.responseJSON.errors, function( index, value ) {
                            $('#' + index + '-error').show();
                            $('#' + index + '-error').html(value);
                        });
                        $().toastmessage('showErrorToast', data.responseJSON.message);
                    }
                }
            });
        });

        $('#cancel').click(function(){
            clearForm();
        });

        function clearForm(){
            $('input[name=source_text]').val('');
            $('input[name=translated_text]').val('');
            $('#language-select').val('');
            $('#category-select').val('');
            $('#status-select').val('');
        }
    });
</script>
@endsection

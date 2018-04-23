@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-4">
        <div class="row">
            <div class="col-sm-12 card mb-4 box-shadow pr-0 pl-0">
                <div class="card-header" id="category-header">
                    <h4 class="my-0 font-weight-normal"><i class="far fa-list-alt"></i> Category</h4>
                </div>
                <div class="card-body pb-0 text-left" id="category-body">
                    <div class="alert alert-primary pl-4" role="alert">
                        Weather
                        <span class="float-right"><i class="fas fa-trash"></i></span>
                    </div>
                    <div class="alert alert-dark pl-4" role="alert">
                        Music
                        <span class="float-right"><i class="fas fa-trash"></i></span>
                    </div>
                    <div class="alert alert-dark pl-4" role="alert">
                        File Manager
                        <span class="float-right"><i class="fas fa-trash"></i></span>
                    </div>
                    <div class="alert alert-light pl-0 mb-0" role="alert">
                        <div class="form-group row">
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="Category Name ...">
                            </div>
                            <span type="button" class="btn btn-primary col-sm-3"><i class="fas fa-plus"></i> Create</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 card mb-4 box-shadow pr-0 pl-0">
                <div class="card-header" id="language-header">
                    <h4 class="my-0 font-weight-normal"><i class="fas fa-language"></i> Language</h4>
                </div>
                <div class="card-body pb-0 text-left" id="language-body">
                    <div class="alert alert-primary pl-4" role="alert">
                        English
                        <span class="float-right"><i class="fas fa-trash"></i></span>
                    </div>
                    <div class="alert alert-dark pl-4" role="alert">
                        France
                        <span class="float-right"><i class="fas fa-trash"></i></span>
                    </div>
                    <div class="alert alert-dark pl-4" role="alert">
                        Vietnamese
                        <span class="float-right"><i class="fas fa-trash"></i></span>
                    </div>
                    <div class="alert alert-light pl-0 mb-0" role="alert">
                        <div class="form-group row">
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="Language Name ...">
                            </div>
                            <span type="button" class="btn btn-primary col-sm-3"><i class="fas fa-plus"></i> Create</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-8 pr-0">
        <div class="col-sm-12 card mb-4 box-shadow pr-0 pl-0">
            <div class="card-header" id="translate-header">
                <h4 class="my-0 font-weight-normal float-left">Text Translate ( Weather - English )</h4>
                <span class="float-right">
                    <span id="upload-file">
                        <i class="fas fa-cloud-upload-alt mr-2" style="font-size:24px; cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Upload file"></i>
                    </span>
                    <span id="download-file">
                        <i id="download-file" class="fas fa-cloud-download-alt"  style="font-size:24px; cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Download file"></i>
                    </span>
                </span>
                <form class="d-none">
                    <input type="file" id="file-upload">
                </form>
            </div>
            <div class="card-body pb-0 text-left" id="translate-body">
                <div class="input-group pt-2 pb-2 mb-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="">password</span>
                    </div>
                    <input type="text" class="form-control" value="Passwords must be at least six characters and match the confirmation.">
                    <span class="btn btn-secondary ml-2" data-toggle="tooltip" data-placement="top" title="Translate"><i class="fas fa-exchange-alt"></i></span>
                    <span class="btn btn-secondary ml-2" data-toggle="tooltip" data-placement="top" title="Save"><i class="fas fa-save"></i></span>
                    <span class="btn btn-secondary ml-2" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fas fa-trash"></i></span>
                </div>
                <div class="input-group pt-2 pb-2 mb-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="">reset</span>
                    </div>
                    <input type="text" class="form-control" value="Your password has been reset!">
                    <span class="btn btn-secondary ml-2" data-toggle="tooltip" data-placement="top" title="Translate"><i class="fas fa-exchange-alt"></i></span>
                    <span class="btn btn-secondary ml-2" data-toggle="tooltip" data-placement="top" title="Save"><i class="fas fa-save"></i></span>
                    <span class="btn btn-secondary ml-2" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fas fa-trash"></i></span>
                </div>
                <div class="input-group pt-2 pb-2 mb-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="">sent</span>
                    </div>
                    <input type="text" class="form-control" value="We have e-mailed your password reset link!">
                    <span class="btn btn-secondary ml-2" data-toggle="tooltip" data-placement="top" title="Translate"><i class="fas fa-exchange-alt"></i></span>
                    <span class="btn btn-secondary ml-2" data-toggle="tooltip" data-placement="top" title="Save"><i class="fas fa-save"></i></span>
                    <span class="btn btn-secondary ml-2" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fas fa-trash"></i></span>
                </div>
                <div class="input-group pt-2 pb-2 mb-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="">token</span>
                    </div>
                    <input type="text" class="form-control" value="This password reset token is invalid.">
                    <span class="btn btn-secondary ml-2" data-toggle="tooltip" data-placement="top" title="Translate"><i class="fas fa-exchange-alt"></i></span>
                    <span class="btn btn-secondary ml-2" data-toggle="tooltip" data-placement="top" title="Save"><i class="fas fa-save"></i></span>
                    <span class="btn btn-secondary ml-2" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fas fa-trash"></i></span>
                </div>
                <div class="input-group pt-2 pb-2 mb-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="">user</span>
                    </div>
                    <input type="text" class="form-control" value="We can't find a user with that e-mail address.">
                    <span class="btn btn-secondary ml-2" data-toggle="tooltip" data-placement="top" title="Translate"><i class="fas fa-exchange-alt"></i></span>
                    <span class="btn btn-secondary ml-2" data-toggle="tooltip" data-placement="top" title="Save"><i class="fas fa-save"></i></span>
                    <span class="btn btn-secondary ml-2" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fas fa-trash"></i></span>
                </div>

                <div class="alert alert-light pl-0 pt-1 mb-3" role="alert">
                    <div class="form-group row p-0 mb-0">
                        <div class="col-sm-5">
                            <input type="text" class="form-control" placeholder="Text Input ...">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" placeholder="Text Translate ...">
                        </div>
                        <span type="button" class="btn btn-primary col-sm-2" data-toggle="tooltip" data-placement="top" title="Add"><i class="fas fa-plus"></i> Add</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#category-header').click(function(){
            $('#category-body').toggle('slow');
        });

        $('#language-header').click(function(){
            $('#language-body').toggle('slow');
        });

        $('#upload-file').click(function(){
            $('#file-upload').click();
        });

        $('#file-upload').change(function(){
            if($(this).val().length > 0){
                var fileExtension = ['json'];
                if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                    $().toastmessage('showErrorToast', "The File is not formatted correctly");
                }
            }
        });

        $('#download-file').click(function(){
            alert('export file');
        });
    });
</script>
@endsection
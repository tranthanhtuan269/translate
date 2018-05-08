@extends('layouts.app')

@section('content')
<style type="text/css">
    .pointer{
        cursor: pointer;
    }
</style>

<div class="row">
    <div class="offset-sm-1 col-sm-10">
        <div class="row">
            <div class="col-sm-12 card mb-4 box-shadow pr-0 pl-0">
                <div class="card-header" id="role-header">
                    <h4 class="my-0 font-weight-normal"><i class="fas fa-plus-circle"></i> Create a Role</h4>
                </div>
                <div class="card-body pb-0 text-left" id="role-body">
                    {!! Form::open(['url' => 'role', 'id' => 'create-translate-form']) !!}
                        <div class="form-group row">
                            <label for="sourceText_upd" class="col-sm-4 col-form-label">Source Text</label>
                            <div class="col-sm-8">
                                <input type="hidden" id="translateID_upd" value="">
                                <input type="text" class="form-control" id="sourceText_upd" placeholder="Source Text">
                                <div class="alert alert-danger mt-2 px-3 py-1" role="alert" id="error-source-text">
                                  This is a danger alert—check it out!
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="translatedText_upd" class="col-sm-4 col-form-label">Translated Text</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="translatedText_upd" placeholder="Translated Text">
                                <div class="alert alert-danger mt-2 px-3 py-1" role="alert" id="error-translated-text">
                                  This is a danger alert—check it out!
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="language_upd" class="col-sm-4 col-form-label">Language</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="language_upd" placeholder="Language">
                                <div class="alert alert-danger mt-2 px-3 py-1" role="alert" id="error-language">
                                  This is a danger alert—check it out!
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="category_upd" class="col-sm-4 col-form-label">Category</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="category_upd" placeholder="Category">
                                <div class="alert alert-danger mt-2 px-3 py-1" role="alert" id="error-category">
                                  This is a danger alert—check it out!
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="status_upd" class="col-sm-4 col-form-label">Status</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="status_upd" placeholder="Status">
                                <div class="alert alert-danger mt-2 px-3 py-1" role="alert" id="error-status">
                                  This is a danger alert—check it out!
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="offset-sm-5 col-sm-2">
                                <div id="create-role-btn" class="btn btn-primary mb-2">Submit</div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var permission_checked = '';
        $('#create-role-btn').click(function(){
            $.each($('.permission-check'), function( index, value ) {
                if($(this).prop('checked')){
                    permission_checked += $(this).attr('data-id') + ',';
                }
            });
            $('input[name=permission-checked]').val(permission_checked);
            $('#create-role-form').submit();
        });
    });
</script>
@endsection
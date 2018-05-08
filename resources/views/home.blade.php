@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="header-text mb-4 mt-2">
                <span class="border-bottom">
                    Multi-language translator
                </span>
            </h1>

            <div class="form-group row">
                <div class="col-sm-2 input-files-label font-weight-bold">Input files: </div>
                <div class="col-sm-8 input-files">
                    <input name="browse-file" type="file" class="d-none" multiple>
                    <div class="text-content"></div>
                </div>
                <div class="col-sm-2 input-browse">
                    <div class="btn btn-primary input-browse-btn">Browse</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-2 custom-label font-weight-bold">Category:</div>
                <div class="col-sm-4">
                    {{ Form::select('category', $categories, null, ['placeholder' => 'Pick a category', 'class' => 'form-control']) }}
                </div>
                <div class="col-sm-2 custom-label font-weight-bold">Translate to:</div>
                <div class="col-sm-4">
                    {{ Form::select('translateGroup', $translateGroup, null, ['placeholder' => 'Pick a group', 'class' => 'form-control']) }}
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-10 offset-2 text-center">
                    <div class="btn btn-lg btn-primary translate-btn">TRANSLATE NOW</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-sm-2 custom-label font-weight-bold">Result:</div>
                <div class="col-sm-10" id="translate-hold">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th scope="col" class="text-center" width="50%">Input file</th>
                          <th scope="col" class="text-center" width="50%">Progress <!-- <span class="download-btn">Download</span> --><a href="{{ url('/') }}/uploads/{{ Auth::user()->id }}/zip/archive.zip" class="download-btn d-none">Download</a></th>
                        </tr>
                      </thead>
                      <tbody id="translate-return-body">
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

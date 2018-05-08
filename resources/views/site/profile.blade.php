@extends('layouts.app')

@section('content')
<script src="http://jcrop-cdn.tapmodo.com/v0.9.12/js/jquery.Jcrop.min.js"></script>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="header-text mb-4 mt-2">
                <span class="border-bottom">
                Profile management
                </span>
            </h1>
            <div class="form-group row">
                <div class="col-sm-4 custom-label-right font-weight-bold">Email:</div>
                <div class="col-sm-4">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4 custom-label-right font-weight-bold">Username:</div>
                <div class="col-sm-4">
                    {{ Form::text('name', Auth::user()->name, ['class' => 'form-control']) }}
                    <div class="alert alert-danger" id="name-error" role="alert" style="display: none;">
                        This is a danger alert—check it out!
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4 custom-label-right font-weight-bold">Avatar:</div>
                <div class="col-sm-8">
                    <input type="hidden" name="avatar" value="{{ Auth::user()->avatar }}">
                    {{ Form::file('avatar-file', ['class' => 'd-none', 'id' => 'avatar-file', 'accept' => 'image/gif, image/jpeg, image/png']) }}
                    @if(strlen(Auth::user()->avatar) > 0)
                    <img src="{{ url('/') }}/images/avatar/{{ Auth::user()->avatar }}" class="avatar-upd" width="150" 
                    height="150">
                    @else
                    <img src="{{ url('/') }}/images/avatar/if_ninja-simple_479476.svg" class="avatar-upd" width="150" 
                    height="150">
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4 custom-label-right font-weight-bold">New password:</div>
                <div class="col-sm-4">
                    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Enter new password...']) }}
                    <div class="alert alert-danger" id="password-error" role="alert" style="display: none;">
                        This is a danger alert—check it out!
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4 custom-label-right font-weight-bold">Retype New Password:</div>
                <div class="col-sm-4">
                    {{ Form::password('repassword', ['class' => 'form-control', 'placeholder' => 'Enter new password again...']) }}
                    <div class="alert alert-danger" id="repassword-error" role="alert" style="display: none;">
                        This is a danger alert—check it out!
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-sm-4 col-sm-4 text-center">
                    <div class="btn btn-primary" id="save-change">Save changes</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="change-avatar" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg modal-image">

    <!-- Modal content-->
    <div class="modal-content">
        <form id="form" >
            <div class="modal-header">
                <h4 class="modal-title">Select new avatar</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar"
                    aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:80%">
                        80%
                    </div>
                </div>
                <input id="file" type="file" class="d-none" accept="image/*">
                <div id="views"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="load-btn">Load image</button>
                <button type="button" class="btn btn-primary d-none" id="submit-btn">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
  </div>
</div>

<link rel="stylesheet" href="http://jcrop-cdn.tapmodo.com/v0.9.12/css/jquery.Jcrop.min.css" type="text/css" />

<script type="text/javascript">
    $(document).ready(function(){
        $base_url = $('base').attr('href');
        var $file = null;
        var crop_max_width = 400;
        var crop_max_height = 400;
        var jcrop_api;
        var canvas;
        var context;
        var image;

        var prefsize;

        $('#change-avatar').on('shown.bs.modal', function (e) {
            e.preventDefault();
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
            if ($.inArray($($file).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                $.ajsrConfirm({
                    message: "Only formats are allowed : "+fileExtension.join(", "),
                    confirmButton: "OK",
                    cancelButton : "Cancel",
                    showCancel: false,
                    nineCorners: false,
                });
                return;
            }
            loadImage($file);
        });

        $('.avatar-upd').click(function(){
            $('#avatar-file').click();
        });

        $("#avatar-file").change(function() {
            $file = this;
            if($(this).val().length > 0){
                $('.progress').removeClass('d-none');
                loadImage(this);
            }
        });

        $('#load-btn').click(function(){
            $('#avatar-file').val("");
            $('#change-avatar').modal('hide');
            $('#avatar-file').click();
        });

        $('#change-avatar-btn').click(function(){
            $('#avatar-file').val("");
            $('#avatar-file').click();
        });

        function loadImage(input) {
          if (input.files && input.files[0]) {
            var reader = new FileReader();
            canvas = null;
            reader.onload = function(e) {
              image = new Image();
              image.onload = validateImage;
              image.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
            $('#submit-btn').removeClass('d-none');
          }
        }

        function validateImage() {
            $('.progress').addClass('d-none');
            if (canvas != null) {
                image = new Image();
                image.onload = restartJcrop;
                image.src = canvas.toDataURL('image/png');

                $("#form").submit();
            } else restartJcropOpen();
        }

        function restartJcropOpen() {
            if(image.width < 160 || image.height < 160 || image.width > 3000 || image.height > 3000){
                $("#views").empty();
                $.ajsrConfirm({
                    message: "Image must be between 160 x 160 — 3,000 x 3,000 pixels. Please select a different image",
                    confirmButton: "OK",
                    cancelButton : "Cancel",
                    showCancel: false,
                    nineCorners: false,
                });
              }else{
                $('#change-avatar').modal('show');
                restartJcrop();
              }
        }

        function restartJcrop() {
          if (jcrop_api != null) {
            jcrop_api.destroy();
          }
          $("#views").empty();
          $("#views").append("<canvas id=\"canvas\">");
          canvas = $("#canvas")[0];
          context = canvas.getContext("2d");
          canvas.width = image.width;
          canvas.height = image.height;
          var imageSize = (image.width > image.height)? image.height : image.width;
          imageSize = (imageSize > 800)? 800: imageSize;
          context.drawImage(image, 0, 0);
          $("#canvas").Jcrop({
            onSelect: selectcanvas,
            onRelease: clearcanvas,
            boxWidth: crop_max_width,
            boxHeight: crop_max_height,
            setSelect: [0,0,imageSize,imageSize],
            aspectRatio: 1,
            bgOpacity:   .4,
            bgColor:     'black'
          }, function() {
            jcrop_api = this;
          });
          clearcanvas();
          selectcanvas({x:0,y:0,w:imageSize,h:imageSize});
        }

        function clearcanvas() {
          prefsize = {
            x: 0,
            y: 0,
            w: canvas.width,
            h: canvas.height,
          };
        }

        function selectcanvas(coords) {
          prefsize = {
            x: Math.round(coords.x),
            y: Math.round(coords.y),
            w: Math.round(coords.w),
            h: Math.round(coords.h)
          };
        }

        $('#submit-btn').click(function(){
            canvas.width = prefsize.w;
            canvas.height = prefsize.h;
            context.drawImage(image, prefsize.x, prefsize.y, prefsize.w, prefsize.h, 0, 0, canvas.width, canvas.height);
            validateImage();
        });

        $("#form").submit(function(e) {
          e.preventDefault();
          $('#change-avatar').modal('hide');
          formData = new FormData($(this)[0]);
          formData.append("base64", canvas.toDataURL('image/png'));

          $.ajaxSetup(
          {
              headers:
              {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          $.ajax({
            url: "{{ url('/') }}/images/uploadImage",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $("#image-loading").show();
            },
            success: function(data) {
                if(data.code == 200){
                    $('.avatar-upd').attr('src', "{{ url('/') }}/images/avatar/" + data.image_url);
                    $('input[name=avatar]').val(data.image_url);
                    $('#change-avatar').modal('hide');
                }else{
                    $.ajsrConfirm({
                        message: "An error occurred during save process, please try again",
                        confirmButton: "OK",
                        cancelButton : "Cancel",
                        showCancel: false,
                        nineCorners: false,
                    });
                    return;
                }
                $('#avatar-image').on('load', function () {
                    $("#image-loading").hide();
                });
            },
            error: function(data) {
                alert("Error");
            },
            complete: function(data) {}
          });
        });

        $('#save-change').click(function(){
            var name = $('input[name=name]').val();
            var avatar = $('input[name=avatar]').val();
            var password = $('input[name=password]').val();
            var repassword = $('input[name=repassword]').val();

            var data    = {
                _method           : "PUT",
                name              : name,
                avatar            : avatar,
                password          : password,
                repassword        : repassword
            };

            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: $base_url + "/user/updateSefl",
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    $('.alert-danger').hide();
                },
                complete: function(data) {
                    if(data.status == 200){
                        $().toastmessage('showSuccessToast', data.responseJSON.Message);
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
    });
</script>
@endsection

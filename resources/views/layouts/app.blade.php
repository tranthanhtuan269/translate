<!DOCTYPE html>
<html>
<head>
    <title>TOH Translate Tool</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="{{ url('/') }}/js/jquery-3.2.1.min.js"></script>
    <script src="{{ url('/') }}/js/popper.min.js"></script>
    <link rel="stylesheet" href="{{ url('/') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/css/jquery.toastmessage.css">
    <script defer src="{{ url('/') }}/js/all.js"></script>
    <script src="{{ url('/') }}/js/alertify.js"></script>
    <script src="{{ url('/') }}/js/jquery.toastmessage.js"></script>
    <script src="{{ url('/') }}/js/bootstrap.min.js"></script>
    <base href="{{ url('/') }}" target="_self">
</head>
<body>
  <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
    <h5 class="my-0 mr-md-auto font-weight-normal">TOH Translate Tool</h5>
    <!-- <nav class="my-2 my-md-0 mr-md-3">
      <a class="p-2 text-dark" href="#">Features</a>
      <a class="p-2 text-dark" href="#">Enterprise</a>
      <a class="p-2 text-dark" href="#">Support</a>
      <a class="p-2 text-dark" href="#">Pricing</a>
    </nav> -->
    @if ( Auth::check() )
      @if( Auth::user()->id == 1)
      <a class="btn btn-outline-primary mr-2" href="{{ url('user') }}">User</a>
      <a class="btn btn-outline-primary mr-2" href="{{ url('role') }}">Role</a>
      <a class="btn btn-outline-primary mr-2" href="{{ url('permission') }}">Permission</a>
      @endif
      <a class="btn btn-outline-primary mr-2" href="{{ url('category') }}">Category</a>
      <a class="btn btn-outline-primary mr-2" href="{{ url('language') }}">Language</a>
      <a class="btn btn-outline-primary mr-2" href="{{ url('translate') }}">Translate</a>
      <a class="btn btn-outline-primary mr-2" href="{{ url('logout') }}">Logout</a>
    @else
      <a class="btn btn-outline-primary mr-2" href="#">Contribute</a>
      <a class="btn btn-outline-primary" href="#" data-toggle="modal" data-target="#login-form">Sign in</a>
    @endif
  </div>

  <div class="container-fluid">
      @yield('content')
  </div>

  <div id="login-form" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Sign In</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <label for="inputUsername" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="inputEmail" placeholder="Email">
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
              <input type="password" class="form-control" id="inputPassword" placeholder="Password">
            </div>
          </div>
          <div class="form-check">
            <div class="offset-sm-2 col-sm-6">
              <input class="form-check-input" type="checkbox" value="" id="rememberMe">
              <label class="form-check-label" for="rememberMe">
                Remember Me
              </label>
            </div>
          </div>
          <div class="row pt-2">
            <div class="offset-sm-2 col-sm-10">
              <span id="error-text"></span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a href="">Forgot password</a>
          <button type="button" class="btn btn-primary" id="signInBtn">Sign In</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ url('/') }}/js/script.js"></script>
  <script type="text/javascript">
    var baseURL = $('base').attr('href');
    $('#signInBtn').click(function(){
      var inputEmail      = $('#inputEmail').val();
      var inputPassword   = $('#inputPassword').val();
      var rememberMe      = $('#rememberMe').is(":checked");
      var data            = {
        email           : inputEmail,
        password        : inputPassword,
        remember        : rememberMe
      };
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        url: baseURL+"/loginAjax",
        data: data,
        method: "POST",
        dataType:'json',
        success: function (response) {
            var html_data = '';
            if(response.status == 200){
              location.reload();
            }else{
              $('#error-text').html('<div class="alert alert-danger" role="alert">' + response.Message + '</div>');
            }
        },
        error: function (data) {
          $('#login-form').modal('toggle');
          $().toastmessage('showErrorToast', "Login failed. Please check your internet connection and try again.");
        }
      });
    });
  </script>
</body>
</html>
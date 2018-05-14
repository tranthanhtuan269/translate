<!DOCTYPE html>
<html>
<head>
    <title>TOH Translation Tool</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="{{ url('/') }}/js/jquery-3.2.1.min.js"></script>
    <script src="{{ url('/') }}/js/popper.min.js"></script>
    <link rel="stylesheet" href="{{ url('/') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/css/jquery.toastmessage.css">
    <script defer src="{{ url('/') }}/js/all.js"></script>
    <script src="{{ url('/') }}/js/alertify.js"></script>
    <script src="{{ url('/') }}/js/jquery.toastmessage.js"></script>
    <script src="{{ url('/') }}/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ url('/') }}/css/style.css">
    <link rel="stylesheet" href="{{ url('/') }}/css/tmplt-default.css">

    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <base href="{{ url('/') }}" target="_self">
</head>
<body>
  @if ( Auth::check() )
  <div class="position-relative align-items-center pb-3 px-3 px-md-4 mb-3 box-shadow nav-holder navbar-main-top">
    <h5 class="my-1 font-weight-normal nav-left"><a href="{{ url('/') }}/home" class="text-dark company-name"><img src="{{ url('/') }}/images/SVG/logo_tab_bar.svg" width="80%" /></a></h5>
    <ul class="nav justify-content-center nav-center">
      <li class="nav-item dropdown">
        <a class="nav-link text-center color-white border-right-1" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Parameter <br/>Configuration</a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="{{ url('category') }}">Category</a>
          <a class="dropdown-item" href="{{ url('language') }}">Language</a>
          <a class="dropdown-item" href="{{ url('groups') }}">Translate Group</a>
          @if( Auth::user()->id == 1)
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ url('user') }}">User</a>
          <a class="dropdown-item" href="{{ url('role') }}">Role</a>
          <a class="dropdown-item" href="{{ url('permission') }}">Permission</a>
          @endif
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link text-center color-white border-right-1" href="{{ url('translates') }}">Translation <br />Management</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-center color-white" href="{{ url('translates/review') }}">Review <br />Contribute</a>
      </li>
    </ul>
    <ul class="nav justify-content-center nav-right">
      <li class="nav-item dropdown">
        <a class="nav-link text-center color-white" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        @if(strlen(Auth::user()->avatar) > 0)
        <img src="{{ url('/') }}/images/avatar/{{ Auth::user()->avatar }}" width="60" height="60" class="img-thumbnail rounded-circle" />
        @else
        <img src="{{ url('/') }}/images/avatar/if_ninja-simple_479476.svg" width="60" height="60" class="img-thumbnail rounded-circle" />
        @endif
        {{ Auth::user()->name }}</a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="{{ url('profile') }}">Profile</a>
          <a class="dropdown-item" href="{{ url('logout') }}">Logout</a>
        </div>
      </li>
    </ul>
  </div>
  @else
  <div class="position-relative align-items-center pb-3 px-3 px-md-4 mb-3 bg-white box-shadow nav-holder navbar-top">
    <h5 class="my-0 font-weight-normal nav-left"><a href="{{ url('/') }}" class="text-dark company-name"><img src="{{ url('/') }}/images/SVG/LOGO.svg" width="80%" /></a></h5>
    <ul class="nav justify-content-center nav-right">
      @if(Route::currentRouteAction() == 'App\Http\Controllers\SiteController@welcome')
      <li class="nav-item">
        <a class="nav-link text-center nav-btn" href="{{ url('contributor') }}">Language Contributor</a>
      </li>
      @endif
      @if(Route::currentRouteAction() == 'App\Http\Controllers\ContributorController@index')
      <li class="nav-item">
        <a class="nav-link text-center nav-btn" href="javascript:void(0)"  data-toggle="modal" data-target="#login-form">Login</a>
      </li>
      @endif
    </ul>
  </div>
  @endif

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

  <script src="{{ url('/') }}/js/ajsr-jq-confirm.min.js"></script>
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
              // location.reload();
              window.location.href = "{{ url('/') }}/home";
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
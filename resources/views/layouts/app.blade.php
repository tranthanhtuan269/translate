<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="http://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/css/jquery.toastmessage.css">
    <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="http://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.rawgit.com/alertifyjs/alertify.js/v1.0.10/dist/js/alertify.js"></script>
    <script src="{{ url('/') }}/js/jquery.toastmessage.js"></script>
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
      <a class="btn btn-outline-primary mr-2" href="#">Contribute</a>
      <a class="btn btn-outline-primary" href="#">Sign in</a>
    </div>

    <div class="container-fluid">
        @yield('content')
    </div>
    <script src="{{ url('/') }}/js/script.js"></script>
</body>
</html>
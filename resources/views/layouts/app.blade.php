<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Event Management</title>

    <base href="../">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/assets/css/bootstrap.css') }}" rel="stylesheet">
    <!-- Custom styles -->
    <link href="{{ asset('/assets/css/custom.css') }}" rel="stylesheet">
</head>

<body>
    @include('layouts.header')
    <div class="container-fluid">
        <div class="row">
            @yield('content')
        </div>
    </div>

</body>

</html>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Hội thảo kỹ năng nghề TP Hà Nội 2023</title>

    <base href="./">
    <!-- Bootstrap core CSS -->
    <link href="{{asset('assets/css/bootstrap.css')}}" rel="stylesheet">
    <!-- Custom styles -->
    <link href="{{asset('assets/css/custom.css')}}" rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row">
        <main class="col-md-6 mx-sm-auto px-4">
            <div class="pt-3 pb-2 mb-3 border-bottom text-center">
                <h1 class="h2">Hội thảo kỹ năng nghề TP Hà Nội 2023</h1>
            </div>

            <form class="form-signin" action="/login" method="POST">
                @csrf
                <h1 class="h3 mb-3 font-weight-normal">Đăng nhập</h1>
                <input type="email" id="inputEmail" name="email" class="form-control mt-3"
                placeholder="Email" autofocus autocomplete="off">
                <input type="password" id="inputPassword" name="password" 
                autocomplete="off" class="form-control mt-3" placeholder="Mật khẩu">
                @if($error)
                    <span class="message text-danger mb-3 d-inline-block">{{$error}}</span>
                @endif
                <button class="btn btn-lg btn-primary btn-block" id="login" type="submit">Đăng nhập</button>
            </form>

        </main>
    </div>
</div>
</body>
</html>

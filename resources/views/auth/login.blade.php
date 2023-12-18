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
    <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
    <!-- Custom styles -->
    <link href="{{ asset('assets/css/custom.css')}}" rel="stylesheet">
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
                    <label for="inputEmail" class="sr-only">Email</label>
                    <input type="email" id="inputEmail" name="email" class="form-control mb-2" placeholder="Email"
                        autofocus>
                    @if($errors->has('email'))
                    <p class="text-danger mb-0">{{ $errors->first('email') }}</p>
                    @endif
                    <label for="inputPassword" class="sr-only">Mật khẩu</label>
                    <input type="password" id="inputPassword" name="password" class="form-control my-2"
                        placeholder="Mật khẩu">
                    @if($errors->has('password'))
                    <p class="text-danger mb-3">{{ $errors->first('password') }}</p>
                    @endif
                    @if($errors->has('message'))
                    <p class="text-danger mb-3">{{ $errors->first('message') }}</p>
                    @endif
                    <button class="btn btn-lg btn-primary btn-block" id="login" type="submit">Đăng nhập</button>
                </form>

            </main>
        </div>
    </div>
</body>

</html>
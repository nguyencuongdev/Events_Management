<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Hội thảo kỹ năng nghề TP Hà Nội 2023</title>

    <base href="../">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/assets/css/bootstrap.css') }}" rel="stylesheet">
    <!-- Custom styles -->
    <link href="{{ asset('/assets/css/custom.css') }}" rel="stylesheet">
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <main class="col-md-6 mx-sm-auto px-4">
                <div class="pt-3 pb-2 mb-3 border-bottom text-center">
                    <h1 class="h2 text-primary">Hội thảo kỹ năng nghề TP Hà Nội 2023</h1>
                </div>

                <form class="form-signin" action="/login" method="POST">
                    @csrf
                    <h1 class="h3 mb-3 font-weight-normal">Đăng nhập</h1>

                    <label for="inputEmail" class="sr-only">Email</label>
                    <input type="text" id="inputEmail" name="email" class="form-control 
                        {{ ($errors->has('email') || $errors->has('message')) ? 'is-invalid' : '' }}"
                        value="{{ old('email') }}" placeholder="Email" autofocus>
                    @error('email')
                    <p class="invalid-feedback mt-1 mb-2 mx-2">{{ $message }}</p>
                    @enderror
                    <label for="inputPassword" class="sr-only mt-3">Mật khẩu</label>
                    <input type="password" id="inputPassword" name="password" class="form-control mt-3
                       {{ ($errors->has('password') || $errors->has('message')) ? 'is-invalid' : '' }}"
                        placeholder=" Mật khẩu">
                    @error('password')
                    <p class="invalid-feedback mt-1 mb-2 mx-2">{{ $message }}</p>
                    @enderror
                    @error('message')
                    <p class="invalid-feedback mt-1 mb-2 mx-2">{{ $message }}</p>
                    @enderror
                    <button class="btn btn-lg btn-primary btn-block" id="login" type="submit">Đăng nhập</button>
                </form>

            </main>
        </div>
    </div>
</body>

</html>
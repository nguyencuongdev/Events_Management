@extends('layouts.app')
@section('content')
<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="/">
                    Quản lý sự kiện
                </a>
            </li>
        </ul>
    </div>
</nav>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Quản lý sự kiện</h1>
    </div>

    <div class="mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h2 class="h4">Tạo sự kiện mới</h2>
        </div>
    </div>

    <form class="needs-validation" novalidate action="/edit/events/{{ $inforEvent->slug }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputName">Tên</label>
                <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                <input type="text" class="form-control is-invalid" id="inputName" name="name" placeholder="" value="">
                <div class="invalid-feedback">
                    Tên không được để trống.
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputSlug">Slug</label>
                <input type="text" class="form-control" id="inputSlug" name="slug" placeholder="" value="">
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputDate">Ngày</label>
                <input type="text" class="form-control" id="inputDate" name="date" placeholder="yyyy-mm-dd" value="">
            </div>
        </div>

        <hr class="mb-4">
        <button class="btn btn-primary" type="submit">Lưu sự kiện</button>
        <a href="/" class="btn btn-link">Bỏ qua</a>
    </form>

</main>
@endsection
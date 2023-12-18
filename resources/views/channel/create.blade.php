@extends('layouts.app')
@section('content')
<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="/">Quản lý sự kiện</a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>{{ $inforEvent->name }}</span>
        </h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="/detail/events/{{ $inforEvent->slug }}">
                    Tổng quan
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Báo cáo</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item"><a class="nav-link" href="/report/events/{{ $inforEvent->slug }}">Công suất phòng</a>
            </li>
        </ul>
    </div>
</nav>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div class="border-bottom mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h1 class="h2">{chèn tên sự kiện}</h1>
        </div>
        <span class="h6">{chèn ngày sự kiện}</span>
    </div>

    <div class="mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h2 class="h4">Tạo kênh mới</h2>
        </div>
    </div>

    <form class="needs-validation" novalidate action="events/detail.html">

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

        <hr class="mb-4">
        <button class="btn btn-primary" type="submit">Lưu kênh</button>
        <a href="events/detail.html" class="btn btn-link">Bỏ qua</a>
    </form>

</main>
@endsection
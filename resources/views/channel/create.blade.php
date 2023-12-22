@extends('layouts.app')
@section('content')
<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="/">Quản lý sự kiện</a></li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>{{ $event->name }}</span>
        </h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="/events/{{ $event->slug }}">
                    Tổng quan
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Báo cáo</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" href="/report/event/{{ $event->slug }}">
                    Công suất phòng
                </a>
            </li>
        </ul>
    </div>
</nav>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div class="border-bottom mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h1 class="h2">{{ $event->name }}</h1>
        </div>
        <span class="h6">{{ date('d-m-Y',strtotime($event->date)) }}</span>
    </div>

    <div class="mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h2 class="h4">Tạo kênh mới</h2>
        </div>
    </div>

    <form class="needs-validation" novalidate action="/events/{{ $event->slug }}/channels" method="POST">
        @csrf
        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputName">Tên</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="inputName" name="name"
                    placeholder="" value="">
                @error('name')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="mb-4">
        <button class="btn btn-primary" type="submit">Lưu kênh</button>
        <a href="/events/{{ $event->slug }}" class="btn btn-link">Bỏ qua</a>
    </form>

</main>
@endsection
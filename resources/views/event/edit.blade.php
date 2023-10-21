@extends('layouts.app')
<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="events/index.html">Quản lý sự kiện</a></li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>{{$infor_event->name }}</span>
        </h6>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link active" href="events/detail.html">Tổng quan</a></li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Báo cáo</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item"><a class="nav-link" href="reports/index.html">Công suất phòng</a></li>
        </ul>
    </div>
</nav>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div class="border-bottom mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h1 class="h2">{{ $infor_event->name }}</h1>
        </div>
    </div>

    <form class="needs-validation" novalidate action="/event/edit/{{ $infor_event->slug }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputName">Tên</label>
                <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                <input type="text" class="form-control {{ $error['name'] ? 'is-invalid' : '' }}" id="inputName"
                    placeholder="" name="name" value="{{ $data['name'] }}">
                <div class="invalid-feedback">
                    {{ $error['name'] }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputSlug">Slug</label>
                <input type="text" class="form-control {{ $error['slug'] ? 'is-invalid' : '' }}" name="slug"
                    id="inputSlug" placeholder="" value="{{ $data['slug'] }}">
                <div class="invalid-feedback">
                    {{ $error['slug'] }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputDate">Ngày</label>
                <input type="text" class="form-control {{ $error['date'] ? 'is-invalid' : '' }}" name="date"
                    id="inputDate" placeholder="yyyy-mm-dd" value="{{$data['date'] }}">
                <div class="invalid-feedback">
                    {{ $error['date'] }}
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <button class="btn btn-primary" type="submit">Lưu</button>
        <a href="/event/detail/{{ $infor_event->slug }}" class="btn btn-link">Bỏ qua</a>
    </form>

</main>
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
                <a class="nav-link" href="/report/events/{{ $event->slug }}">
                    Công suất phòng
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
            <h2 class="h4">Sửa thông tin sự kiện</h2>
        </div>
    </div>

    <form class="needs-validation" novalidate action="/events/{{ $event->slug }}" method="POST">
        @csrf
        @method("PUT")
        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputName">Tên</label>
                <input type="text" class="form-control" id="inputName" name="name" placeholder="" @if($event)
                    value="{{ $event->name }}" @else value="{{ old('name') }}" @endif>
                @error('name')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputSlug">Slug</label>
                <input type="text" class="form-control" id="inputSlug" name="slug" placeholder="" @if ($event)
                    value="{{ $event->slug }}" @else value="{{ old('slug') }}" @endif>
                @error('slug')
                <p class=" text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputDate">Ngày</label>
                <input type="date" class="form-control" id="inputDate" name="date" placeholder="yyyy-mm-dd" @if ($event)
                    value="{{ $event->date }}" @else value="{{ old('date') }}" @endif>
                @error('date')
                <p class=" text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="mb-4">
        <button class="btn btn-primary" type="submit">Lưu sự kiện</button>
        <a href="/" class="btn btn-link">Bỏ qua</a>
    </form>

</main>
@endsection
@extends('layouts.app')
<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="/">Quản lý sự kiện</a></li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>{{ $infor_event->name }}</span>
        </h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="/event/detai/{{ $infor_event->slug }}">
                    Tổng quan
                </a>
            </li>
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
        <span class="h6">{{ date('d-m-Y', strtotime($infor_event->date)) }}</span>
    </div>

    <div class="mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h2 class="h4">Tạo phòng mới</h2>
        </div>
    </div>

    <form class="needs-validation" novalidate action="/event/{{ $infor_event->slug }}/new/room" method="POST">
        @csrf
        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputName">Tên</label>
                <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                <input type="text" class="form-control {{ $error['name'] ? 'is-invalid' : '' }}" id="inputName"
                    name="name" placeholder="" value="{{$data['name']}}">
                <div class="invalid-feedback">
                    {{ $error['name'] }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="selectChannel">Kênh</label>
                <select class="form-control" id="selectChannel" name="channel">
                    @foreach ($channel_list as $channel)
                    <option value="{{ $channel->id }}" {{ $data['channel']==$channel->id ? 'selected' : '' }}>
                        {{$channel->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputCapacity">Công suất</label>
                <input type="number" class="form-control {{ $error['capacity'] ? 'is-invalid' : '' }}"
                    id="inputCapacity" name="capacity" placeholder="" value="{{$data['capacity']}}">
                <div class="invalid-feedback">
                    {{ $error['capacity'] }}
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <button class="btn btn-primary" type="submit">Lưu phòng</button>
        <a href="/event/detail/{{ $infor_event->slug }}" class="btn btn-link">Bỏ qua</a>
    </form>

</main>
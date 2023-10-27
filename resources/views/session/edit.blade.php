@extends('layouts.app')
@section('content')
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
                <a class="nav-link active" href="/event/detail/{{ $infor_event->slug }}">
                    Tổng quan
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Báo cáo</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" href="/event/{{$infor_event->slug}}/report/capacity/room">
                    Công suất phòng
                </a>
            </li>
        </ul>
    </div>
</nav>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div class="border-bottom mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h1 class="h2">{{ $infor_event->name }}</h1>
        </div>
        <span class="h6">{{ date('d-m-Y',strtotime($infor_event->date)) }}</span>
    </div>

    <div class="mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h2 class="h4">Thông tin phiên</h2>
        </div>
    </div>

    <form class="needs-validation" novalidate action="/event/{{ $infor_event->slug }}/session/{{ $data['id']}}"
        method="post">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="selectType">Loại</label>
                <select class="form-control" id="selectType" name="type">
                    <option value="talk" selected>Talk</option>
                    <option value="workshop">Workshop</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputTitle">Tiêu đề</label>
                <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                <input type="text" class="form-control {{$error['title'] ? 'is-invalid' : ''}}" id="inputTitle"
                    name="title" placeholder="" value="{{ $data['title'] }}">
                <div class="invalid-feedback">
                    {{$error['title']}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputSpeaker">Người trình bày</label>
                <input type="text" class="form-control {{$error['speaker'] ? 'is-invalid' : ''}}" id="inputSpeaker"
                    name="speaker" placeholder="" value="{{ $data['speaker'] }}">
                <div class="invalid-feedback">
                    {{ $error['speaker'] }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="selectRoom">Kênh / Phòng</label>
                <select class="form-control {{$error['room'] ? 'is-invalid' : ''}}" id="selectRoom" name="room">
                    @foreach ($room_list as $room)
                    <option value="{{ $room->id }}" {{ ($data['room']==$room->id) ? 'selected' : '' }}>
                        {{ $room->channel_name }} / {{ $room->name }}
                    </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{$error['room']}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputCost">Chi phí</label>
                <input type="number" class="form-control {{$error['cost'] ? 'is-invalid' : ''}}" id="inputCost"
                    name="cost" placeholder="" value="{{ $data['cost'] }}">
                <div class="invalid-feedback">
                    {{$error['cost']}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-6 mb-3">
                <label for="inputStart">Bắt đầu</label>
                <input type="text" class="form-control {{$error['start'] ? 'is-invalid' : ''}}" id="inputStart"
                    name="start" placeholder="yyyy-mm-dd HH:MM" value="{{ $data['start'] }}">
                <div class="invalid-feedback">
                    {{$error['start']}}
                </div>
            </div>
            <div class="col-12 col-lg-6 mb-3">
                <label for="inputEnd">Kết thúc</label>
                <input type="text" class="form-control {{$error['end'] ? 'is-invalid' : ''}}" id="inputEnd" name="end"
                    placeholder="yyyy-mm-dd HH:MM" value="{{ $data['end'] }}">
                <div class="invalid-feedback">
                    {{$error['end']}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <label for="textareaDescription">Mô tả</label>
                <textarea class="form-control {{$error['description'] ? 'is-invalid' : ''}}" id="textareaDescription"
                    name="description" placeholder="" rows="5">{{ $data['description'] }}
                </textarea>
                <div class="invalid-feedback">
                    {{$error['description']}}
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <button class="btn btn-primary" type="submit">Lưu phiên</button>
        <a href="/event/detail/{{ $infor_event->slug }}" class="btn btn-link">Bỏ qua</a>
    </form>

</main>

@endsection
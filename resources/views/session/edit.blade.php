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
            <h2 class="h4">Sửa thông tin phiên</h2>
        </div>
    </div>

    <form class="needs-validation" novalidate action="/sessions/{{ $session->id }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="selectType">Loại</label>
                <select class="form-control" id="selectType" name="type">
                    <option value="talk" @if($session->type == 'talk') selected @endif>
                        Talk
                    </option>
                    <option value="workshop" @if($session->type=='workshop') selected @endif>
                        Workshop
                    </option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputTitle">Tiêu đề</label>
                <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="inputTitle"
                    name="title" placeholder="" value="{{ $session->title }}">
                @error('title')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputSpeaker">Người trình bày</label>
                <input type="text" class="form-control @error('speaker') is-invalid @enderror" id="inputSpeaker"
                    name="speaker" placeholder="" value="{{ $session->speaker }}">
                @error('speaker')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="selectRoom">Phòng</label>
                <select class="form-control" id="selectRoom" name="room">
                    @foreach($rooms as $room)
                    <option value="{{ $room->id }}" @if($session->room->id==$room->id) selected @endif>
                        {{ $room->name }} / {{ $room->channel->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputCost">Chi phí</label>
                <input type="number" class="form-control @error('cost') is-invalid @enderror" id="inputCost" name="cost"
                    placeholder="" value="{{ $session->cost}}">
                @error('cost')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-6 mb-3">
                <label for="inputStart">Bắt đầu</label>
                <input type="text" class="form-control @error('start') is-invalid @enderror" id="inputStart"
                    name="start" placeholder="yyyy-mm-dd HH:MM" value="{{ date('H:i',strtotime($session->start)) }}">
                @error('start')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
            <div class="col-12 col-lg-6 mb-3">
                <label for="inputEnd">Kết thúc</label>
                <input type="text" class="form-control @error('end') is-invalid @enderror" id="inputEnd" name="end"
                    placeholder="yyyy-mm-dd HH:MM" value="{{ date('H:i',strtotime($session->end)) }}">
                @error('end')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <label for="textareaDescription">Mô tả</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="textareaDescription"
                    name="description" placeholder="" rows="5">{{ $session->description}}</textarea>
                @error('description')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="mb-4">
        <button class="btn btn-primary" type="submit">Lưu phiên</button>
        <a href="/events/{{ $event->slug }}" class="btn btn-link">Bỏ qua</a>
    </form>

</main>
@endsection
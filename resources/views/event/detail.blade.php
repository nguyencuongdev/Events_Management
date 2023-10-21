@extends('layouts.app')
@section('content')
<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="/">Quản lý sự kiện</a></li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>{{ $infor_event->name ?? 'không xác định'}}</span>
        </h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="/event/detail/{{ $infor_event->slug }}">Tổng quan</a>
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
    <div class="border-bottom mb-3 pt-3 pb-2 event-title">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h1 class="h2">{{ $infor_event->name}}</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <a href="/event/edit/{{$infor_event->slug}}" class="btn btn-sm btn-outline-secondary">
                        Sửa sự kiện
                    </a>
                </div>
            </div>
        </div>
        <span class="h6">{{ date('d-m-Y',strtotime($infor_event->date)) }}</span>
    </div>

    <!-- Tickets -->
    <div id="tickets" class="mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h2 class="h4">Vé</h2>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <a href="/event/new/ticket/{{ $infor_event->slug }}" class="btn btn-sm btn-outline-secondary">
                        Tạo vé mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row tickets">
        {{-- tickets --}}
        @if(count($ticket_list) > 0)
        @for ($i = 0; $i < count($ticket_list); $i++) <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body ticket-item">
                    <h5 class="card-title">{{ $ticket_list[$i]->name }}</h5>
                    <p class="card-text mb-2">Giá: {{ number_format($ticket_list[$i]->cost,2,'.',',') }}đ</p>
                    @if ($ticket_list[$i]->special_validity)
                    @if (json_decode($ticket_list[$i]->special_validity)->date ?? false)
                    <p class="card-text">
                        Có sẵn đến ngày {{
                        date('d-m-Y',strtotime(json_decode($ticket_list[$i]->special_validity)->date))
                        }}
                    </p>
                    @elseif(json_decode($ticket_list[$i]->special_validity)->amount ?? false)
                    <p class="card-text">
                        {{ json_decode($ticket_list[$i]->special_validity)->amount}} vé sẵn có
                    </p>
                    @endif
                    @else
                    <p class="card-text">__</p>
                    @endif
                </div>
            </div>
    </div>
    @endfor
    @else
    <p class="col text-center">Chưa có phát hành vé nào!</p>
    @endif
    </div>

    <!-- Sessions -->
    <div id="sessions" class="mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h2 class="h4">Phiên</h2>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <a href="/event/new/session/{{ $infor_event->slug }}" class="btn btn-sm btn-outline-secondary">
                        Tạo phiên mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive sessions">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Thời gian</th>
                    <th>Loại</th>
                    <th class="w-100">Tiêu đề</th>
                    <th>Người trình bày</th>
                    <th>Kênh / phòng</th>
                </tr>
            </thead>
            <tbody>
                @if(count($session_list) > 0)
                @foreach ($session_list as $session)
                <tr>
                    <td class="text-nowrap">
                        {{ date('d-m-Y H:i:s',strtotime($session->start)) }} ->
                        {{date('d-m-Y H:i:s',strtotime($session->end)) }}
                    </td>
                    <td>{{ $session->type }}</td>
                    <td><a href="/event/session/{{ $infor_event->slug }}">{{ $session->title }}</a></td>
                    <td class="text-nowrap">{{ $session->speaker }}</td>
                    <td class="text-nowrap">
                        {{ $session->channel_name }} / {{ $session->room_name }}
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td class="w-100 text-center" colspan="5">Không có phiên nào!</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Channels -->
    <div id="channels" class="mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h2 class="h4">Kênh</h2>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <a href="channels/create.html" class="btn btn-sm btn-outline-secondary">
                        Tạo kênh mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row channels">
        @if(count($channel_list) > 0)
        @for($i = 0; $i < count($channel_list); $i++) <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body channel-item">
                    <h5 class="card-title channel-title">
                        {{$channel_list[$i]->name}}
                    </h5>
                    <p class="card-text">
                        {{ $count_session_of_rooms[$i]->count}} phiên,
                        {{$channel_list[$i]->count_room}} phòng
                    </p>
                </div>
            </div>
    </div>
    @endfor
    @else
    <p class="col text-center">Không có kênh nào!</p>
    @endif
    </div>

    <!-- Rooms -->
    <div id="rooms" class="mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h2 class="h4">Phòng</h2>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <a href="rooms/create.html" class="btn btn-sm btn-outline-secondary">
                        Tạo phòng mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive rooms">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tên</th>
                    <th>Công suất</th>
                </tr>
            </thead>
            <tbody>
                @if(count($room_list) > 0)
                @foreach ($room_list as $room)
                <tr>
                    <td>{{$room->name}}</td>
                    <td>{{ $room->capacity }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td class="text-center" colspan="2">Không có phòng nào!</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

</main>
@endsection
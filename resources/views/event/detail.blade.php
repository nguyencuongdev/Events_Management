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
    <div class="border-bottom mb-3 pt-3 pb-2 event-title">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h1 class="h2">{{ $event->name }}</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <a href="/events/{{ $event->slug }}/edit" class="btn btn-sm btn-outline-secondary">
                        Sửa sự kiện
                    </a>
                </div>
            </div>
        </div>
        <span class="h6">Ngày {{ date('d-m-Y',strtotime($event->date))}}</span>
    </div>

    <!-- Tickets -->
    <div id="tickets" class="mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h2 class="h4">Vé</h2>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <a href="/events/{{ $event->slug }}/tickets/create" class="btn btn-sm btn-outline-secondary">
                        Tạo vé mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row tickets">
        @foreach($tickets as $ticket)
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="/tickets/{{ $ticket->id }}/edit">{{ $ticket->name }}</a>
                    </h5>
                    <p class="card-text">{{ $ticket->cost }}</p>
                    @php
                    $special_validity = json_decode($ticket->special_validity);
                    if($special_validity && $special_validity->type === 'date')
                    echo "<p class='card-text'>Có hiệu lực đến ".
                        date('d-m-Y',strtotime($special_validity->date)).
                        "</p>";
                    else if($special_validity && $special_validity->type === 'amount')
                    echo "<p class='card-text'>".
                        $special_validity->amount.
                        " vé có sẵn</p>";
                    else
                    echo "<p class='card-text'>----</p>"
                    @endphp
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Sessions -->
    <div id="sessions" class="mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h2 class="h4">Phiên</h2>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <a href="/events/{{ $event->slug }}/sessions/create" class="btn btn-sm btn-outline-secondary">
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
                    <th>Kênh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sessions as $session)
                <tr>
                    <td class="text-nowrap">{{ date('H:i',strtotime($session->start))}} - {{
                        date('H:i',strtotime($session->end))}}</td>
                    <td>{{ $session->type }}</td>
                    <td>
                        <a href="/sessions/{{ $session->id }}/edit">{{ $session->title }}</a>
                    </td>
                    <td class="text-nowrap">{{ $session->speaker }}</td>
                    <td class="text-nowrap">
                        {{ $session->room->channel->name }} / {{ $session->room->name }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Channels -->
    <div id="channels" class="mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h2 class="h4">Kênh</h2>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <a href="/events/{{ $event->slug }}/channels/create" class="btn btn-sm btn-outline-secondary">
                        Tạo kênh mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row channels">
        @foreach($channels as $channel)
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="/channels/{{ $channel->id }}/edit">{{ $channel->name }}</a>
                    </h5>
                    <p class="card-text">
                        {{ $channel->sessions->count() }} Phiên, {{ $channel->rooms->count() }} phòng
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Rooms -->
    <div id="rooms" class="mb-3 pt-3 pb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <h2 class="h4">Phòng</h2>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <a href="/events/{{ $event->slug }}/rooms/create" class="btn btn-sm btn-outline-secondary">
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
                @foreach($rooms as $room)
                <tr>
                    <td>
                        <a href="/rooms/{{ $room->id }}/edit">{{ $room->name }}</a>
                    </td>
                    <td>{{ $room->capacity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</main>
@endsection
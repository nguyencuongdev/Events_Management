@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{asset('assets/css/Chart.min.css')}}">
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
            <h2 class="h4">Công suất phòng</h2>
        </div>
    </div>
    <canvas id="chart"></canvas>
</main>
<script src="{{ asset('assets/js/Chart.min.js') }}"></script>
<script>
    const sessions = @json($titleOfSessions);
    const capacityRooms = @json($capacityOfRooms);
    console.log(capacityRooms);
    const amountAttendeeRegisted = @json($amountAttendeeRegisted);

    const ctx = document.querySelector('#chart').getContext('2d');
    const barChart = new Chart(ctx,{
        type: 'bar',
        data: {
            labels: sessions,
            datasets: [
                {
                    label: 'Số lượng người tham dự đã đăng ký',
                    data: amountAttendeeRegisted,
                    backgroundColor: amountAttendeeRegisted.map(
                        (attendee,index) => (attendee > capacityRooms[index]) ? 'red' : '#14b8a6'
                    )
                },
                {
                    label: 'Công suất phòng',
                    data: capacityRooms,
                    backgroundColor: '#38bdf8',
                },
            ]
        },
        options: {
            title: {
                display: true,
                text: 'Báo cáo công suất phòng so với người tham dự',
                position: 'bottom',
            },
            legend: {
                display:true,
                position: 'right',
            }
        }
    })
</script>
@endsection
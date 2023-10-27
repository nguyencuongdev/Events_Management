@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{asset('assets/css/Chart.min.css')}}">
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
                <a class="nav-link" href="/event/{{ $infor_event->slug }}/report/capacity/room">
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
            <h2 class="h4">Công suất phòng</h2>
        </div>
    </div>

    <canvas id="myBarChart"></canvas>

</main>
<script src="{{ asset('assets/js/Chart.min.js') }}"></script>
<script>
    const list_session = @json($list_sessions);
    const sessions = list_session.map(session => session.title);

    const capacity_list = @json($capacity_rooms);
    const capacities = capacity_list.map(capacity =>  capacity.capacity);

    const attendee_list = @json($list_count_attendee);
    const attendees = attendee_list.map(attendee => attendee.count_attendess);
    
    const ctx = document.querySelector('#myBarChart').getContext('2d');
    const barChart = new Chart(ctx,{
        type: 'bar',
        data: {
            labels: sessions,
            datasets: [
                {
                    label: 'Số lượng người tham dự đã đăng ký',
                    data: attendees,
                    backgroundColor: attendees.map((attendee,index) =>
                        (attendee > capacities[index]) ? 'red' : '#14b8a6'
                    )
                },
                {
                    label: 'Công suất phòng',
                    data: capacities,
                    backgroundColor: '#38bdf8',
                },
            ]
        },
        options: {
            title: {
                display: true,
                text: 'Báo cáo công suất phòng so với người tham dự',
                position: 'bottom',
                fontSize: 16
            },
            scales: {
                x:{
                    beginAtZero: true,
                },
                y: {
                    beginAtZero: true,
                    title: {
                       enabled: true,
                        text: 'Công suất phòng'
                    }
                }
            },
            legend: {
                display:true,
                position: 'right',
            }
        }
   })
</script>
@endsection
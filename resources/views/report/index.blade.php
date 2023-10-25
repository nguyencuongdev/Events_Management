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
    // Dữ liệu về các session và các giá trị tương ứng
    // Dữ liệu về các session và các giá trị tương ứng
   const sessions = ["Session 1", "Session 2", "Session 3", "Session 4"];
   const capacities = [50, 70, 60, 80]; // Công suất của phòng
   const attendees = [45, 60, 55, 75]; // Số lượng người tham dự đã đăng ký

   const ctx = document.querySelector('#myBarChart').getContext('2d');
   const barChart = new Chart(ctx,{
        type: 'bar',
        data: {
            labels: sessions,
            datasets: [
                {
                    label: 'Công suất phòng',
                    data: capacities,
                    backgroundColor: '#38bdf8',
                },
                {
                    label: 'Số lượng người tham dự đã đăng ký',
                    data: attendees,
                    backgroundColor: attendees.map((attendee,index) =>
                        (attendee > capacities[index]) ? 'red' : '#14b8a6'
                    )

                }
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
                        text: 'Số lượng người tham dự'
                    }
                }
            },
            legend: {
                display:true,
                position: 'right',
            },
            tooltips:{
               enabled: false,
            }
        }
   })
</script>
@endsection
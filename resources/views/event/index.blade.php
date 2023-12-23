@extends('layouts.app')
@section('content')
<nav class="col-md-2 d-none d-md-block bg-light sidebar">
  <div class="sidebar-sticky">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link active" href="/">Quản lý sự kiện</a>
      </li>
    </ul>
  </div>
</nav>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản lý sự kiện</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
      <div class="btn-group mr-2">
        <a href="/events/create" class="btn btn-sm btn-outline-secondary">Tạo sự kiện mới</a>
      </div>
    </div>
  </div>

  <div class="row events">
    @if(count($events) > 0)
    @foreach ($events as $event)
    <div class="col-md-4">
      <div class="card mb-4 shadow-sm">
        <a href="/events/{{ $event->slug }}" class="btn text-left event">
          <div class="card-body">
            <h5 class="card-title">{{ $event->name }}</h5>
            <p class="card-subtitle">{{ date('d-m-Y',strtotime($event->date)) }}</p>
            <hr>
            <p class="card-text">{{ $event->registrations->count() }} người đăng ký</p>
          </div>
        </a>
      </div>
    </div>
    @endforeach
    @else
    <h3 class="text-center col-12">Không có sự kiện nào</h3>
    @endif
  </div>

</main>
@endsection
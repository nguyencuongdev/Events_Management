<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Event Backend</title>

    <base href="../" />
    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- Custom styles -->
    <link href="assets/css/custom.css" rel="stylesheet" />
  </head>

  <body>
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="events/index.html"
        >Nền tảng sự kiện</a
      >
      <span class="navbar-organizer w-100">{{$currentUser->name }}</span>
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" id="logout" href="/logout">Đăng xuất</a>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" href="events/index.html"
                  >Quản lý sự kiện</a
                >
              </li>
            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
          >
            <h1 class="h2">Quản lý sự kiện</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group mr-2">
                <a
                  href="events/create.html"
                  class="btn btn-sm btn-outline-secondary"
                  >Tạo sự kiện mới</a
                >
              </div>
            </div>
          </div>

          <div class="row events">
            @foreach ($events as $event)
                <div class="col-md-4">
                  <div class="card mb-4 shadow-sm">
                    <a href="{{ $event->slug }}" class="btn text-left event">
                      <div class="card-body">
                        <h5 class="card-title">{{$event->name}}</h5>
                        <p class="card-subtitle">{{$event->date }}</p>
                        <hr />
                        <p class="card-text">{{ $event->count_registration }} người đăng ký</p>
                      </div>
                    </a>
                  </div>
                </div>
            @endforeach
          </div>
        </main>
      </div>
    </div>
  </body>
</html>

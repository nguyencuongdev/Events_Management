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
            <h2 class="h4">Tạo vé mới</h2>
        </div>
    </div>

    <form class="needs-validation" novalidate action="/event/new/ticket/{{ $infor_event->slug }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputName">Tên</label>
                <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                <input type="text" class="form-control {{ $error['name'] ? 'is-invalid' : ''}}" id="inputName"
                    name="name" placeholder="" value="{{ $data['name'] }}">
                <div class="invalid-feedback">
                    {{ $error['name'] }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputCost">Giá</label>
                <input type="number" class="form-control {{ $error['cost'] ? 'is-invalid' : ''}}" id="inputCost"
                    name="cost" placeholder="" value="{{ $data['cost'] }}">
                <div class="invalid-feedback">
                    {{ $error['cost'] }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="selectSpecialValidity">Hiệu lực đặc biệt</label>
                <select class="form-control" id="selectSpecialValidity" name="special_validity"
                    value="{{ $data['type_specialValidity'] }}">
                    <option value="Không" {{ $data['type_specialValidity']=='Không' ? 'selected' : '' }}>
                        Không
                    </option>
                    <option value="amount" {{ $data['type_specialValidity']=='amount' ? 'selected' : '' }}>
                        Số lượng giới hạn
                    </option>
                    <option value="date" {{ $data['type_specialValidity']=='date' ? 'selected' : '' }}>
                        Có thể mua đến ngày
                    </option>
                </select>
                <div class="col px-0 limit-amount mt-3">
                    <label for="inputAmount">Số lượng vé tối đa được bán</label>
                    <input type="number" class="form-control {{ $error['specialValidity'] ? 'is-invalid' : ''}}"
                        id="inputAmount" name="limit-amount" placeholder="" value="0">
                    <div class="invalid-feedback">
                        {{ $error['specialValidity'] }}
                    </div>
                </div>
                <div class="col px-0 limit-time mt-3">
                    <label for="inputValidTill">Vé có thể được bán đến</label>
                    <input type="date" class="form-control {{ $error['specialValidity'] ? 'is-invalid' : ''}}"
                        id="inputValidTill" name="limit-time" placeholder="yyyy-mm-dd HH:MM" value="">
                    <div class="invalid-feedback">
                        {{ $error['specialValidity'] }}
                    </div>
                </div>
            </div>
        </div>

        <hr class="mb-4">
        <button class="btn btn-primary" type="submit">Lưu vé</button>
        <a href="/event/detail/{{ $infor_event->slug }}" class="btn btn-link">Bỏ qua</a>
    </form>
</main>
<script>
    const selectElement = document.querySelector('#selectSpecialValidity');
    const elementLimitAmount = document.querySelector('.limit-amount');
    const elementLimitTime = document.querySelector('.limit-time');
    
    if(selectElement.value == 'amount'){
        elementLimitTime.style.display = 'none';
        elementLimitAmount.style.display = 'block';
    }
    else if(selectElement.value == 'date'){
        elementLimitAmount.style.display = 'none';
        elementLimitTime.style.display = 'block';
    }else{
        elementLimitAmount.style.display = 'none';
        elementLimitTime.style.display = 'none';
    }

    selectElement.addEventListener('change', (e)=>{
        let value = e.target.value;
        if(value == 'amount'){ 
            elementLimitTime.style.display = 'none';
            elementLimitTime.querySelector('input').value = '';
            elementLimitAmount.style.display = 'block';
        }
        else if(value == 'date'){
            elementLimitAmount.style.display = 'none';
            elementLimitAmount.querySelector('input').value= '0';
            elementLimitTime.style.display = 'block';
        }else{
            elementLimitAmount.style.display = 'none';
            elementLimitTime.style.display = 'none';
            elementLimitTime.querySelector('input').value  = '';
            elementLimitAmount.querySelector('input').value = '0';
        }
    })
</script>
@endsection
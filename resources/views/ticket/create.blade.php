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
            <h2 class="h4">Tạo vé mới</h2>
        </div>
    </div>

    <form class="needs-validation" novalidate action="/events/{{ $event->slug }}/tickets" method="POST">
        @csrf
        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputName">Tên</label>
                <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="inputName" name="name"
                    placeholder="" value="{{ old('name') }}">
                @error('name')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="inputCost">Giá</label>
                <input type="number" class="form-control @error('cost') is-invalid @enderror" id="inputCost" name="cost"
                    placeholder="" value="{{ old('cost') ? old('cost') : 0 }}">
                @error('cost')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4 mb-3">
                <label for="selectSpecialValidity">Hiệu lực đặc biệt</label>
                <select class="form-control mb-3" id="specialValidity" name="special_validity">
                    <option value="no" {{ old('special_validity')==='no' ? 'selected' : '' }}>
                        Không
                    </option>
                    <option value="amount" {{ old('special_validity')==='amount' ? 'selected' : '' }}>
                        Số lượng giới hạn
                    </option>
                    <option value="date" {{ old('special_validity')==='date' ? 'selected' : '' }}>
                        Có thể mua đến ngày
                    </option>
                </select>
                <div class="limit-amount">
                    <label for="amount">Số lượng vé tối đa được bán</label>
                    <input type="number" class="form-control 
                    {{ old('special_validity') === 'amount' && $errors->has('amount') ? 'is-invalid' : '' }}"
                        id="amount" name="amount" placeholder="" value="{{ old('amount') ? old('amount') : 0 }}">
                    @if(old('special_validity') === 'amount' && $errors->has('amount'))
                    <p class="invalid-feedback">{{ $errors->first('amount') }}</p>
                    @endif
                </div>

                <div class="limit-date">
                    <label for="date">Vé có thể được bán đến</label>
                    <input type="text" class="form-control 
                    {{ old('special_validity') === 'date' && $errors->has('date') ? 'is-invalid' : '' }}" id="date"
                        name="date" placeholder="yyyy-mm-dd HH:MM" value="{{ old('date') }}">
                    @if(old('special_validity') === 'date' && $errors->has('date'))
                    <p class="invalid-feedback">{{ $errors->first('date') }}</p>
                    @endif
                </div>
            </div>
        </div>


        <hr class="mb-4">
        <button class="btn btn-primary" type="submit">Lưu vé</button>
        <a href="/events/{{ $event->slug }}" class="btn btn-link">Bỏ qua</a>
    </form>

    <script>
        const specialValitidy = document.querySelector('#specialValidity');
        const date = document.querySelector('.limit-date');
        const valueDate = date.querySelector('#date');//giá trị ngày giới hạn mở bán
        const amount = document.querySelector('.limit-amount');
        const valueAmount = amount.querySelector('#amount');//giá trị số lượng vé giới hạn

        switch(specialValitidy.value){
                case 'date':
                    amount.style.display = 'none';
                    break;
                case 'amount':
                    date.style.display = 'none';
                    break;
                default:
                    date.style.display = 'none';
                    amount.style.display = 'none';
        }
        
        specialValitidy.addEventListener('change',(event) => {
            let type = event.target.value;
            switch(type){
                case 'date':
                    valueAmount.value = 0;
                    amount.style.display = 'none';
                    date.style.display = 'block';
                    break;
                case 'amount':
                    valueDate.value = '';
                    date.style.display = 'none';
                    amount.style.display = 'block';
                    break;
                default:
                    valueDate.value = '';
                    valueAmount.value = 0;
                    date.style.display = 'none';
                    amount.style.display = 'none';
            }
        } )
    </script>
</main>
@endsection
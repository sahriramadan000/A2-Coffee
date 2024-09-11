@extends('admin.layouts.app')

@push('style-link')
<link href="{{ asset('src/assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('src/assets/css/light/components/tabs.css') }}" rel="stylesheet" type="text/css">

<link href="{{ asset('src/assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('src/assets/css/dark/components/tabs.css') }}" rel="stylesheet" type="text/css">
{{-- Date Picker --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    .hilang{
    display: none !important;
  }
</style>
@endpush

@section('breadcumbs')
<nav class="breadcrumb-style-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">A2 Coffee & Eatry</li>
    </ol>
</nav>
@endsection

@section('content')
@include('admin.components.alert')
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <form action="" method="get" class="row g-3 align-items-center">
        {{-- <div class="row g-3 align-item-cente"> --}}
           <div class="col-12 col-md-3">
               <label class="form-label"> Period :</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bx bx-calendar-minus"></i></span>
                    <select class="form-control select2" data-placeholder="Choose one" id="daterange" name="type">
                        <option value="day" {{ (Request::get('type') == 'day') ? 'selected' : ''}}>Daily </option>
                        <option value="monthly" {{ (Request::get('type') == 'monthly') ? 'selected' : '' }}>Monthly </option>
                        <option value="yearly" {{ (Request::get('type') == 'yearly') ? 'selected' : '' }}>Yearly </option>
                    </select>
                </div>
           </div>
           <div class="col-12 col-md-4">
                <div class="" id="datepicker-date-area">
                    <label class="form-label"> Date :</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                        <input type="text" name="start_date" id="date" value="{{Request::get('start_date') ?? date('Y-m-d')}}" autocomplete="off" class="datepicker-date form-control time" required>
                    </div>
                </div>
                <div class="hilang" id="datepicker-month-area">
                    <label class="form-label"> Month :</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                        <input type="text" name="month" id="month" value="{{ Request::get('month') ?? date('Y-m') }}" autocomplete="off" class="datepicker-month form-control time" required>
                    </div>
                </div>
                <div class="hilang" id="datepicker-year-area">
                    <label class="form-label"> Year :</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                        <input type="text" name="year" id="year" value="{{ Request::get('year') ?? date('Y') }}" autocomplete="off" class="datepicker-year form-control" required>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="form-group mt-4">
                    <button  id="generate" class="btn btn-primary btn-sm p-2 w-100">
                        Generate
                    </button>
                </div>
            </div>
        {{-- </div> --}}

        <div class="col-12 col-md-4 col-lg-6" style="z-index: -99999999">
            <div class="card radius-10 bg-secondary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-white">Total Penjualan</p>
                            <h4 class="my-1 text-white">Rp. {{ number_format($acceptSales,0) }}</h4>
                        </div>
                        <div class="widgets-icons bg-white text-danger ms-auto">
                            <i class="bx bx-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-6">
            <div class="card radius-10 bg-secondary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-white">Terima Pembayaran</p>
                            <h4 class="my-1 text-white">Rp. {{ number_format($totalUnpaidSales,0) }}</h4>
                        </div>
                        <div class="widgets-icons bg-white text-danger ms-auto">
                            <i class="bx bx-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-6">
            <div class="card radius-10 bg-secondary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-white">Total Transaksi</p>
                            <h4 class="my-1 text-white">{{ number_format($totalTransaction,0) }}</h4>
                        </div>
                        <div class="widgets-icons bg-white text-danger ms-auto">
                            <i class="bx bx-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-6">
            <div class="card radius-10 bg-secondary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-white">Total Product</p>
                            <h4 class="my-1 text-white">{{ number_format($totalProduct,0) }}</h4>
                        </div>
                        <div class="widgets-icons bg-white text-danger ms-auto">
                            <i class="bx bx-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form><!--end row-->
    <div class="widget widget-chart-one">
        <div class="widget-content">
            <canvas id="analyticDashboard"></canvas>
        </div>
    </div>
</div>

@endsection
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        var hourlyOrders = @json($hourlyOrders);
        var labels = [];
        var orderData = [];
        var revenueData = [];

        for (var hour = 0; hour < 24; hour++) {
            var label = hour < 10 ? '0' + hour : hour;
            labels.push(label);
            orderData.push(hourlyOrders[label]?.count || 0);
            revenueData.push(hourlyOrders[label]?.revenue || 0);
        }
        console.log('data', hourlyOrders);

        var ctx = document.getElementById('analyticDashboard').getContext('2d');
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Orders per Hour',
                    data: orderData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: true,
                }, {
                    label: 'Revenue per Hour',
                    data: revenueData,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1,
                    fill: true,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });

</script>

<script>
    $('.datepicker-date').datepicker({
      format: "yyyy-mm-dd",
        startView: 2,
        minViewMode: 0,
        language: "id",
        daysOfWeekHighlighted: "0",
        autoclose: true,
        todayHighlight: true,
        toggleActive: true,
        container: '#datepicker-date-area'
    });

    $('.datepicker-month').datepicker({
        format: "yyyy-mm",
        startView: 2,
        minViewMode: 1,
        language: "id",
        daysOfWeekHighlighted: "0",
        autoclose: true,
        todayHighlight: true,
        toggleActive: true,
        container: '#datepicker-month-area'
    });

    $('.datepicker-year').datepicker({
        format: "yyyy",
        startView: 2,
        minViewMode: 2,
        language: "id",
        daysOfWeekHighlighted: "0",
        autoclose: true,
        todayHighlight: true,
        toggleActive: true,
        container: '#datepicker-year-area'
    });

    let rangeNow = $('#daterange').val();
    if (rangeNow == 'day') {
        $('#datepicker-date-area').removeClass('hilang');
        const element = document.querySelector('#datepicker-date-area')
        element.classList.add('animated', 'fadeIn')
        // Hilangkan Month
        $('#datepicker-month-area').addClass('hilang');
        $('#datepicker-year-area').addClass('hilang');

    } else if(rangeNow == 'monthly') {
        $('#datepicker-month-area').removeClass('hilang');
        const element = document.querySelector('#datepicker-month-area')
        element.classList.add('animated', 'fadeIn')
        // Hilangkan Date
        $('#datepicker-date-area').addClass('hilang');
        $('#datepicker-year-area').addClass('hilang');
    } else {
        $('#datepicker-year-area').removeClass('hilang');
        const element = document.querySelector('#datepicker-year-area')
        element.classList.add('animated', 'fadeIn')
        // Hilangkan Date
        $('#datepicker-date-area').addClass('hilang');
        $('#datepicker-month-area').addClass('hilang');
    }

    $('#daterange').on('change', function () {
        val = $(this).val();
        if (val == 'day') {
            $('#datepicker-date-area').removeClass('hilang');
            const element = document.querySelector('#datepicker-date-area')
            element.classList.add('animated', 'fadeIn')
            // Hilangkan Month
            $('#datepicker-month-area').addClass('hilang');
            $('#datepicker-year-area').addClass('hilang');

        } else if(val == 'monthly') {
            $('#datepicker-month-area').removeClass('hilang');
            const element = document.querySelector('#datepicker-month-area')
            element.classList.add('animated', 'fadeIn')
            // Hilangkan Date
            $('#datepicker-date-area').addClass('hilang');
            $('#datepicker-year-area').addClass('hilang');
        } else {
            $('#datepicker-year-area').removeClass('hilang');
            const element = document.querySelector('#datepicker-year-area')
            element.classList.add('animated', 'fadeIn')
            // Hilangkan Date
            $('#datepicker-date-area').addClass('hilang');
            $('#datepicker-month-area').addClass('hilang');
        }
    })
</script>
@endpush

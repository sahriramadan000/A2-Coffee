@extends('admin.layouts.app')

@push('style-link')
<link href="{{ asset('src/assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('src/assets/css/light/components/tabs.css') }}" rel="stylesheet" type="text/css">

<link href="{{ asset('src/assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('src/assets/css/dark/components/tabs.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/src/tomSelect/tom-select.default.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/light/tomSelect/custom-tomSelect.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/dark/tomSelect/custom-tomSelect.css') }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    .hilang{
    display: none !important;
  }

  .dt-buttons {
    margin-top: 10px;
    margin-bottom: 10px;
}

.dt-buttons .btn {
    margin-right: 5px;
}

</style>
@endpush

@section('breadcumbs')
<nav class="breadcrumb-style-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
    <div class="card">
        <div class="card-body">
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
                <div class="col-12 col-md-3">
                    <label class="form-label">User :</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                        <select class="form-control @error('user_id') is-invalid @enderror" id="user" name="user_id" >
                            <option value="All" selected>All</option>
                            @foreach ($account_users as $account_user)
                            <option value="{{ $account_user->fullname }}" {{ Request::get('user_id') == $account_user->fullname ? 'selected' : '' }}>
                                {{ $account_user->fullname }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="form-group mt-4">
                        <button  id="generate" class="btn btn-primary btn-sm p-2 w-100">
                            Generate
                        </button>
                    </div>
                    {{-- <button type="button" class="btn btn-primary px-4">Submit</button> --}}
                </div>
            {{-- </div> --}}
            </form><!--end row-->
        </div>
    </div>
</div>

<div class="row mt-3" style="z-index: -99999999">
    <div class="col-12 col-md-4 col-lg-6">
        <div class="card radius-10 bg-danger">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-white">Total Stock In</p>
                        <h4 class="my-1 text-white">{{ number_format($total_stock_in,0) }}</h4>
                    </div>
                    <div class="widgets-icons bg-white text-danger ms-auto">
                        <i class="bx bx-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing mt-2">
    @include('admin.components.alert')
    <div class="widget-content widget-content-area br-8">
        <table id="stock-ins-table" class="table dt-table-hover" style="width:100%">
            <thead>
                <tr>
                    <th width="7%">No</th>
                    <th>Product Name</th>
                    <th>Stock In</th>
                    <th>Description</th>
                    <th class="no-content" width="10%">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="modalContainer"></div>
@endsection

@push('js-src')
<script src="{{ asset('src/plugins/src/tomSelect/tom-select.base.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
@endpush

@push('js')
<script>
     // getData
     $('#stock-ins-table').DataTable({
        processing: true,
        serverSide:true,
        ajax: {
        url: "{{ route('stock-ins.get-data') }}",
        data: function(d) {
            d.type = $('#daterange').val(); 
            d.user_id = $('#user').val(); 
            d.start_date = $('#date').val(); 
            d.month = $('#month').val(); 
            d.year = $('#year').val(); 
        },
        error: function(xhr, textStatus, errorThrown) {
            $('#stock-ins-table').DataTable().clear().draw();
            // console.log(xhr.responseText);
            alert('There was an error fetching data. Please try again later.');
        }
        },
        columns: [
            {
                "data": 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {data: 'product', name:'product'},
            {data: 'stock_in', name:'stock_in'},
            {
                data: 'description',
                name: 'description',
                render: function(data, type, row) {
                    return data ? data : '-';  // Replace null/empty with an empty string
                }
            },
            
            {data: 'action', name:'action'},
        ],
        "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-center mt-md-0 mt-3'f<'toolbar align-self-center'>>>>" +
        "<'table-responsive'tr>" +
        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
        "oLanguage": {
            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
            "sInfo": "Showing page _PAGE_ of _PAGES_",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "Search...",
        "sLengthMenu": "Results :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [10, 20, 50],
        "pageLength": 10
    });

    $("div.toolbar").html('<button class="ms-2 btn btn-primary stock-ins-add" type="button" data-bs-target="#tabs-add-stock-in">'+
                            '<span>Create Stock In</span>'+
                            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>'+
                        '</button>');

    // Event create by Modal
    $(document).on('click', '.stock-ins-add', function() {
        var getTarget = $(this).data('bs-target');

        $.get("{{ route('stock-ins.modal-add') }}", function(data) {
            $('#modalContainer').html(data);
            $(`${getTarget}`).modal('show');
            $(`${getTarget}`).on('shown.bs.modal', function () {
                // Multi Select
                var tagsSelect = new TomSelect(".tags-select",{
                    plugins: ['remove_button'],
                });

                $('#select-all-checkbox-tags').change(function() {
                    if(this.checked) {
                        var allOptions = $('.tags-select option').map(function() {
                            return $(this).val();
                        }).get();
                        tagsSelect.setValue(allOptions);
                    } else {
                        tagsSelect.clear();
                    }
                });

                var addonsSelect = new TomSelect(".addons-select",{
                    plugins: ['remove_button'],
                });

                $('#select-all-checkbox-addons').change(function() {
                    if(this.checked) {
                        var allOptions = $('.addons-select option').map(function() {
                            return $(this).val();
                        }).get();
                        addonsSelect.setValue(allOptions);
                    } else {
                        addonsSelect.clear();
                    }
                });
            });
        });

    });

    // Event Edit by Modal
    $(document).on('click', '.stock-ins-edit-table', function() {
        var productId = $(this).data('bs-target');
        var parts = productId.split("-");
        var id = parseInt(parts[1]);

        $.get("{{ url('stock-ins/modal-edit') }}/" + id, function(data) {
            $('#modalContainer').html(data);
            $(`${productId}`).modal('show');
            $(`${productId}`).on('shown.bs.modal', function () {
                // Multi Select
                var tagsSelect = new TomSelect(".tags-select",{
                    plugins: ['remove_button'],
                });

                $('#select-all-checkbox-tags').change(function() {
                    if(this.checked) {
                        var allOptions = $('.tags-select option').map(function() {
                            return $(this).val();
                        }).get();
                        tagsSelect.setValue(allOptions);
                    } else {
                        tagsSelect.clear();
                    }
                });

                var addonsSelect = new TomSelect(".addons-select",{
                    plugins: ['remove_button'],
                });

                $('#select-all-checkbox-addons').change(function() {
                    if(this.checked) {
                        var allOptions = $('.addons-select option').map(function() {
                            return $(this).val();
                        }).get();
                        addonsSelect.setValue(allOptions);
                    } else {
                        addonsSelect.clear();
                    }
                });
            });
        });
    });

    // Event Delete by Modal
    $(document).on('click', '.stock-ins-delete-table', function() {
        var productId = $(this).data('bs-target');
        var parts = productId.split("-");
        var id = parseInt(parts[1]);

        $.get("{{ url('stock-in/modal-delete') }}/" + id, function(data) {
            $('#modalContainer').html(data);
            $(`${productId}`).modal('show');
        });
    });

    $('#cost_price').on('keyup', function() {
        handleInput('cost_price');
    });

    $('#selling_price').on('keyup', function() {
        handleInput('selling_price');
    });

    $('#price_discount').on('keyup', function() {
        handleInput('price_discount');
    });

    $(document).on('keyup', '#cost_price', function(event) {
        if (event.target && event.target.id === 'cost_price') {
            handleInput('cost_price');
        }
    });

    $(document).on('keyup', '#selling_price', function(event) {
        if (event.target && event.target.id === 'selling_price') {
            handleInput('selling_price');
        }
    });

    $(document).on('keyup', '#price_discount', function(event) {
        if (event.target && event.target.id === 'price_discount') {
            handleInput('price_discount');
        }
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

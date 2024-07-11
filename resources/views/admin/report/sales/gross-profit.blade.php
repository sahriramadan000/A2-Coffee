@extends('admin.layouts.app')

@push('style-link')
<link href="{{ asset('src/assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('src/assets/css/light/components/tabs.css') }}" rel="stylesheet" type="text/css">

<link href="{{ asset('src/assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('src/assets/css/dark/components/tabs.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('breadcumbs')
<nav class="breadcrumb-style-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
    @include('admin.components.alert')
    <div class="widget-content widget-content-area br-8">
        <table id="report-gross-table" class="table dt-table-hover" style="width:100%">
            <thead>
                <tr>
                    <th width="7%">No</th>
                    <th>Nama Kasir</th>
                    <th>Nama Customer</th>
                    <th>Menu</th>
                    <th>Metode Pembayaran</th>
                    <th>Sub Total</th>
                    <th>Type Discount</th>
                    <th>Discount Price</th>
                    <th>Discount Percent</th>
                    <th>Service</th>
                    <th>PB01</th>
                    <th>Total</th>
                    <th class="no-content" width="10%">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="modalContainer"></div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // getData
        $('#report-gross-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('report.sales.get-report-gross') }}",
                error: function(xhr, textStatus, errorThrown) {
                    $('#report-gross-table').DataTable().clear().draw();
                    console.log(xhr.responseText);
                    alert('There was an error fetching data. Please try again later.');
                }
            },
            columns: [
                {
                    "data": 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {data: 'cashier_name', name: 'cashier_name'},
                {data: 'customer_name', name: 'customer_name'},
                {data: 'order_products', name: 'order_products', orderable: false, searchable: false},
                {data: 'payment_method', name: 'payment_method'},
                {
                    data: 'subtotal',
                    render: function(data) {
                        if (data) {
                            return 'Rp. ' + formatRupiah(data);
                        } else {
                            return '-';
                        }
                    }
                },
                {data: 'type_discount', name: 'type_discount'},
                {
                    data: 'price_discount',
                    render: function(data) {
                        if (data) {
                            return 'Rp. ' + formatRupiah(data);
                        } else {
                            return '-';
                        }
                    }
                },
                {
                    data: 'percent_discount',
                    render: function(data) {
                        if (data) {
                            return data + '%';
                        } else {
                            return '-';
                        }
                    }
                },
                {
                    data: 'service',
                    render: function(data) {
                        if (data) {
                            return 'Rp. ' + formatRupiah(data);
                        } else {
                            return '-';
                        }
                    }
                },
                {
                    data: 'pb01',
                    render: function(data) {
                        if (data) {
                            return 'Rp. ' + formatRupiah(data);
                        } else {
                            return '-';
                        }
                    }
                },
                {
                    data: 'total',
                    render: function(data) {
                            if (data) {
                                return 'Rp. ' + formatRupiah(data);
                            } else {
                                return '-';
                            }
                        }
                },
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-center mt-md-0 mt-3'f<'toolbar align-self-center'>>>>" +
            "<'table-responsive'tr>" +
            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": {
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y1="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
                "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [10, 20, 50],
            "pageLength": 10
        });
    });
</script>
@endpush

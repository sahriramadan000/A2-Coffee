@extends('admin.layouts.app')

@push('style-link')
<link href="{{ asset('src/assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('src/assets/css/light/components/tabs.css') }}" rel="stylesheet" type="text/css">

<link href="{{ asset('src/assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('src/assets/css/dark/components/tabs.css') }}" rel="stylesheet" type="text/css">

<link href="{{ asset('src/assets/css/light/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('src/assets/css/dark/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />

<style>
    .btn-custom {
        border-radius: 15px !important;
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
    @include('admin.components.alert')
    <div class="widget widget-card-one">
        <div class="widget-content">

            <div class="media">
                <div class="w-img">
                    <img src="../src/assets/img/profile-19.jpeg" alt="avatar">
                </div>
                <div class="media-body d-flex justify-content-between align-items-center ms-2">
                    <div class="">
                        <h5 class="fw-bold mb-0 pb-0">{{ Auth::user()->fullname }}</h5>
                        <p class="meta-date-time">{{ str_replace(['-', '_'], ' ',Auth::user()->getRoleNames()[0]) }}</p>

                        <ul class="list-group mt-3">
                            <li class="list-group-item bg-transparent border-0 p-0">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="flex-1">
                                        <svg class="text-white" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mg-b-0 text-white opacity-75">{{ Auth::user()->phone ?? 'Nomer telephone belum di cantumkan' }}</p>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item bg-transparent border-0 p-0">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="flex-1">
                                        <svg class="text-white" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mg-b-0 text-white opacity-75">{{ Auth::user()->email ?? 'Email belum di cantumkan' }}</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="">
                        <button class="btn btn-success btn-custom" id="attendanceButton">Check In</button>
                    </div>
                </div>
            </div>

            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing bg-transparent">
                @include('admin.components.alert')
                <div class="widget-content widget-content-area br-8 border-0" style="background: transparent !important; box-shadow:none;">
                    <table id="attendances-table" class="table dt-table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="7%">No</th>
                                <th>Date</th>
                                <th>Check in</th>
                                <th>Check Out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalContainer"></div>

@endsection

@push('js')
<script>
    $(document).ready(function() {
        let attendanceData = null;

        $.ajax({
            url: `{{ route('attendances.check') }}`,
            type: 'GET',
            success: function(data) {
                if (data.code == 404) {
                    $('#attendanceButton')
                        .removeClass('btn-danger')
                        .addClass('btn-success')
                        .text('Check In')
                        .off('click')
                        .on('click', function() {
                            postCheckIn();
                        });
                } else if (data.code == 200 && data.data.check_out == null && data.data.check_in) {
                    attendanceData = data.data;
                    $('#attendanceButton')
                        .removeClass('btn-success')
                        .addClass('btn-danger')
                        .text('Check Out')
                        .off('click')
                        .on('click', function() {
                            postCheckOut(attendanceData.id);
                        });
                } else {
                    $('#attendanceButton')
                        .removeClass('btn-danger')
                        .addClass('btn-success')
                        .addClass('disabled')
                        .text('Check In');
                }
            },
            error: function(xhr, status, error) {
                console.error('Failed to absensi: ', error);
            }
        });

        function postCheckIn() {
            $.ajax({
                url: `{{ route('attendances.store') }}`,
                type: 'POST',
                data: {
                    _token: `{{ csrf_token() }}`,
                    check_in: getIndonesiaTime(),
                },
                success: function(data) {
                    alert('Check In successful');
                    location.reload(); // Reload the page to refresh the button state
                },
                error: function(xhr, status, error) {
                    console.error('Failed to Check In: ', error);
                    alert('Failed to Check In');
                }
            });
        }

        function postCheckOut(attendanceId) {
            $.ajax({
                url: `{{ route('attendances.update', '') }}/${attendanceId}`,
                type: 'PUT',
                data: {
                    _token: `{{ csrf_token() }}`,
                    check_out: getIndonesiaTime(),
                },
                success: function(data) {
                    alert('Check Out successful');
                    location.reload(); // Reload the page to refresh the button state
                },
                error: function(xhr, status, error) {
                    console.error('Failed to Check Out: ', error);
                    alert('Failed to Check Out');
                }
            });
        }

        function getIndonesiaTime() {
            const now = new Date();

            // Convert the current time to the Indonesia time zone (UTC+7)
            const options = {
                timeZone: 'Asia/Jakarta',
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };

            // Format the time to YYYY-MM-DD HH:MM:SS
            const formatter = new Intl.DateTimeFormat('en-GB', options);
            const parts = formatter.formatToParts(now);
            const formattedDateTime = `${parts[4].value}-${parts[2].value}-${parts[0].value} ${parts[6].value}:${parts[8].value}:${parts[10].value}`;

            return formattedDateTime;
        }

        // getData
        $('#attendances-table').DataTable({
            processing: true,
            serverSide:true,
            ajax: {
            url: "{{ route('attendances.get-data') }}",
                error: function(xhr, textStatus, errorThrown) {
                    $('#attendances-table').DataTable().clear().draw(); // Bersihkan tabel
                    console.log(xhr.responseText); // Tampilkan pesan kesalahan di konsol browser
                    alert('There was an error fetching data. Please try again later.'); // Tampilkan pesan kesalahan kepada pengguna
                }
            },
            columns: [
                {
                        "data": 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                },
                {data: 'date', name:'date'},
                {data: 'check_in', name:'check_in'},
                {data: 'check_out', name:'check_out'},
                {
                    data: 'status',
                    render: function(data) {
                        if (data == 'on_time') {
                            return `<span class="badge badge-success">${data}</span>`;
                        } else {
                            return `<span class="badge badge-danger">${data}</span>`;
                        }
                    }
                },
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

        // Event create by Modal
        $(document).on('click', '.attendances-add', function() {
            var getTarget = $(this).data('bs-target');

            $.get("{{ route('attendances.modal-add') }}", function(data) {
                $('#modalContainer').html(data);
                $(`${getTarget}`).modal('show');
            });
        });
    });
</script>
@endpush

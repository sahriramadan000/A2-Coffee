@extends('admin.layouts.app')

@push('style-link')
<link href="{{ asset('src/assets/css/light/components/list-group.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('src/assets/css/dark/components/list-group.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('src/assets/css/light/dashboard/dash_2.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('src/assets/css/dark/dashboard/dash_2.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('breadcumbs')
<nav class="breadcrumb-style-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Sync</a></li>
        <li class="breadcrumb-item active" aria-current="page">A2 Coffee & Eatry</li>
    </ol>
</nav>
@endsection

@section('content')
@include('admin.components.alert')
<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-wallet-one">
        <div class="wallet-info text-center mb-3">
            <p class="wallet-title mb-3">SYCN DATA ORDER</p>
        </div>

        <hr>

        <div class="wallet-action text-center d-flex justify-content-around">
            <button class="btn btn-danger _effect--ripple waves-effect waves-light" onclick="syncDataS()" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                <span class="btn-text-inner">Sync Cloud to Local</span>
            </button>

            <button class="btn btn-success _effect--ripple waves-effect waves-light" onclick="syncData()" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                <span class="btn-text-inner">Sync Local to Cloud</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function syncData() {
        Swal.fire({
            title: 'Processing',
            text: 'Please wait...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: `http://localhost:3000/sync-data`,
            type: 'POST',
            data: {
                _token: `{{ csrf_token() }}`,
            },
            success: function(data) {
                Swal.close();
                Swal.fire({
                    icon: 'success',
                    title: 'Check In successful',
                });
            },
            error: function(xhr, status, error) {
                Swal.close();
                console.error('Failed to Check In: ', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Check In',
                    text: error,
                });
            }
        });
    }
</script>
@endpush

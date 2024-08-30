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
    .dark-grey{
        color: #515365 !important;
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
@include('admin.components.alert')
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
                <div class="col-12 col-md-2">
                    <div class="form-group mt-4">
                        <button  id="generate" class="btn btn-primary btn-sm p-2 w-100">
                            Generate
                        </button>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="form-group mt-4">
                        <a href="{{ route('pos') }}" class="btn btn-danger px-4">Back</a>
                    </div>
                </div>
            {{-- </div> --}}
            </form><!--end row-->
        </div>
    </div>
</div>
<div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
    @foreach ($orders as $item)
        <div class="accordion" id="accordionExample-{{ $item->id }}">
            <div class="accordion-item" style="border-color: #3d3d3d !important;">
                <h2 class="accordion-header" id="headingOne-{{ $item->id }}">
                    @if ($item->payment_status == "Paid")
                        <button class="accordion-button collapsed bg-success" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{ $item->id }}" aria-expanded="false" aria-controls="collapseOne-{{ $item->id }}">
                    @elseif ($item->payment_status == "Unpaid" && $item->payment_method == "Open Bill")
                        <button class="accordion-button collapsed bg-warning" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{ $item->id }}" aria-expanded="false" aria-controls="collapseOne-{{ $item->id }}">
                    @else
                        <button class="accordion-button collapsed bg-danger" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{ $item->id }}" aria-expanded="false" aria-controls="collapseOne-{{ $item->id }}">
                    @endif

                        <div class="ms-3 d-block">
                            <h6 class="mb-0">#{{ $item->no_invoice }}</h6>
                            <?php
                            $invoiceNumber = $item->no_invoice;
                            $parts = explode('-', $invoiceNumber); // Memisahkan nomor invoice menjadi bagian terpisah
                            $lastPart = end($parts); // Mengambil bagian terakhir dari nomor invoice

                            // Menambahkan 'CUST' setelah tanda '-' terakhir
                            $newInvoiceNumber = $parts[0] . '-' .'CUST'.$lastPart ;

                            ?>
                            <h3 class="mb-0">{{ $item->table ?? $newInvoiceNumber }}</h3>
                            <div class="mt-1">
                                <span class="badge badge-light-primary mb-2 me-4">{{ $item->payment_method }}</span>
                            </div>
                        </div>
                    </button>
                </h2>
                <div id="collapseOne-{{ $item->id }}" class="accordion-collapse collapse" aria-labelledby="headingOne-{{ $item->id }}" data-bs-parent="#accordionExample-{{ $item->id }}">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <ul class="list-group list-group-flush">
                                    @php
                                        $groupedOrderProducts = $item->orderProducts
                                            ->filter(function ($product) {
                                                return !$product->cancel_menu; // Only include products that are not canceled
                                            })
                                            ->groupBy('name')
                                            ->map(function ($products) use ($item) {
                                                $totalQty = $products->sum('qty');
                                                $totalPrice = $products->sum(function ($product) {
                                                    return $product->selling_price * $product->qty;
                                                });
                                                $note = $products->pluck('note')->filter()->first(); // Get the first non-null note
                                                $key = Crypt::encrypt($item->id . '-' . implode(',', $products->pluck('id')->toArray()) . '-' . $totalQty);

                                                return [
                                                    'name' => $products->first()->name,
                                                    'qty' => $totalQty,
                                                    'total_price' => $totalPrice,
                                                    'ids' => $products->pluck('id')->toArray(),
                                                    'note' => $note,
                                                    'key' => $key,
                                                ];
                                            });
                                    @endphp

                                    @foreach ($groupedOrderProducts as $orderProduct)
                                    <li class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h4 style="color: #515365">{{ $orderProduct['name'] }}</h4>
                                            {{-- <a href="#!" type="button" onclick="ModalEditQtyProduct('{{ route('modal-edit-qty-product', $orderProduct['key']) }}', '{{ $orderProduct['key'] }}', '{{ csrf_token() }}')" class="cursor-pointer" data-bs-target="#modal-add-qty-product-{{ $orderProduct['key'] }}" style="border-bottom: 1px dashed #bfbfbf; font-size:12px;"> --}}
                                                <small style="color: #515365">x{{ $orderProduct['qty'] }} </small>
                                            {{-- </a> --}}
                                        </div>
                                        <small style="color: #515365">Note: {{ $orderProduct['note'] ?? '' }} </small>
                                        <p class="mb-1">Rp. {{ number_format($orderProduct['total_price'], 0) }}</p>
                                        @if ($item->payment_status != 'Paid')
                                        {{-- <form action="{{ route('cancel-order-product') }}" method="POST">
                                            @csrf
                                            @foreach ($orderProduct['ids'] as $id)
                                                <input type="hidden" name="product_ids[]" value="{{ $id }}">
                                            @endforeach
                                            <input type="hidden" name="order_id" value="{{ $item->id }}">
                                            <input type="hidden" name="key" value="{{ $orderProduct['key'] }}">
                                            <button class="btn btn-sm btn-danger">Cancel</button>
                                        </form>      --}}
                                        @endif

                                    </li>
                                    @endforeach

                                </ul>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card">
                                    <div class="card-header bg-card-head py-2 px-3 text-center">
                                        <span class="tx-bold text-lg text-white" style="font-size:20px;">
                                            Summary Order
                                        </span>
                                    </div>

                                    @php
                                        $totalPrice = 0;
                                    @endphp

                                    @foreach ($item->orderProducts as $orderProduct)
                                        @php
                                        // Calculate the running total for each item
                                        $totalPrice += $orderProduct->price_discount * $orderProduct->qty ;
                                        @endphp
                                    @endforeach

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h4 class="mb-1 dark-grey"><strong>Sub total</strong></h4>
                                                <span>Rp.{{ number_format($item->subtotal,0) }}</span>
                                            </div>
                                        </li>

                                        @if ($item->is_coupon == true)
                                        {{-- @if ($item->is_coupon == false ) --}}
                                        <li class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between align-items-center">
                                                @foreach ($item->orderCoupons as $orderCoupon)
                                                <div class="d-flex flex-row align-items-center">
                                                    <h4 class="mb-1 dark-grey"><strong>Coupon</strong></h4>
                                                    <span class="fs-6"> ({{ $orderCoupon->name ?? '-' }})</span>
                                                </div>
                                                <span>Rp.{{ number_format($orderCoupon->discount_value,0) }}</span>
                                                @endforeach
                                            </div>
                                        </li>
                                        @elseif($item->type_discount)
                                        <li class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between align-items-center">
                                                <div class="d-flex flex-row align-items-center">
                                                    <h4 class="mb-1 dark-grey"><strong>Discount</strong></h4>
                                                    <span class="fs-6"> ({{ $item->type_discount ?? '-' }})</span>
                                                </div>
                                                <span>
                                                    @if($item->type_discount == 'percent')
                                                        {{ $item->percent_discount }}%
                                                    @else
                                                        Rp.{{ number_format($item->price_discount,0) }}
                                                    @endif
                                                </span>
                                            </div>
                                        </li>
                                        @endif
                                        <li class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h4 class="mb-1 dark-grey"><strong>Service :</strong></h4>
                                                <span>Rp.{{ number_format($item->service,0) }}</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h4 class="mb-1 dark-grey"><strong>PB01 :</strong></h4>
                                                <span>Rp.{{ number_format($item->pb01,0) }}</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h4 class="mb-1 dark-grey"><strong>Total Payment :</strong></h4>
                                                <span>Rp.{{ number_format($item->total,0) }}</span>
                                            </div>
                                        </li>
                                            <li class="list-group-item">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h4 class="mb-1 dark-grey"><strong>Metode Pembayaran</strong></h4>
                                                    <span>{{ $item->payment_method }}</span>
                                                </div>
                                            </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <a href="{{ route('print-bill', $item->id) }}" type="submit" class="btn btn-sm w-100 btn-primary">Print Bill</a>
                                                </div>
                                                <div class="col-lg-4">
                                                    <a href="{{ route('print-struk', $item->id) }}" type="submit" class="btn btn-sm w-100 btn-warning">Print Struk</a>
                                                </div>
                                                @can('void')
                                                @if($item->payment_method != 'Return')
                                                <div class="col-lg-4">
                                                    <button type="button" class="btn btn-sm w-100 btn-danger" data-bs-toggle="modal" data-bs-target="#return-{{ $item->id }}">Refund</button>
                                                </div>
                                                @endif
                                                @endcan
                                                <div class="modal fade" id="return-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="returnLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <form action="{{ route('return-order', $item->id) }}" method="POST">
                                                            @method('patch')
                                                            @csrf
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="returnLabel">Apakah Anda Yakin Ingin Mereturn Order</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="key" class="form-label">Enter Key</label>
                                                                        <input type="text" class="form-control" id="key" name="key" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                @if ($item->payment_status != 'Paid' && !($item->payment_status == 'Unpaid' && $item->payment_method == 'Return'))
                                                    <div class="col-lg-6 mx-auto mt-4">
                                                        <button type="button" class="btn btn-sm w-100 btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal-{{ $item->id }}">
                                                            Update Payment
                                                        </button>
                                                    </div>
                                                @endif


                                                <div class="modal fade" id="exampleModal-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <form action="{{ route('update-payment', $item->id) }}" method="POST">
                                                            @method('patch')
                                                            @csrf
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Apakah Anda Yakin Ingin Menyelesaikan Pembayaran</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <h6 class="mb-3">Discount Or Coupon</h6>
                                                                        <select name="type" class="type-select form-control form-control-sm payment-method" data-modal-id="{{ $item->id }}">
                                                                            <option selected value="">Select Discount Or Coupon</option>
                                                                            <option value="Coupon">Coupon</option>
                                                                            <option value="Discount">Discount</option>
                                                                        </select>
                                                                    </div>
                                                
                                                                    <div class="form-group discount-group" style="display: none;">
                                                                        <h6 class="mt-3">Discount</h6>
                                                                        <select name="type_discount" class="form-control select-type-discount" data-modal-id="{{ $item->id }}">
                                                                            <option selected value="Price">Price</option>
                                                                            <option value="Percent">Percent</option>
                                                                        </select>
                                                                    </div>
                                                
                                                                    <div class="row type-discount-group" style="display: none;">
                                                                        <div class="col-12 col-md-6">
                                                                            <div class="form-group mt-3">
                                                                                <div class="input-group">
                                                                                    <span class="input-group-text">Rp.</span>
                                                                                    <input type="text" class="form-control input-price" aria-label="Price" name="discount_price">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                
                                                                        <div class="col-12 col-md-6">
                                                                            <div class="input-group mt-3">
                                                                                <input type="number" class="form-control input-percent" min="0" max="100" aria-label="percent" name="discount_percent" disabled>
                                                                                <span class="input-group-text">%</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                
                                                                    <div class="form-group mt-2 cash-input coupon-group" style="display: none;">
                                                                        <label for="cash" class="form-label">Coupon</label>
                                                                        <select class="form-select mb-3 select-coupon" name="coupon_id" aria-label="Default select example">
                                                                            <option selected disabled>Select Coupon</option>
                                                                            @foreach ($coupons as $coupon)
                                                                            <option value="{{ $coupon->id }}">{{ $coupon->name }} <small>({{ $coupon->type == 'Percentage Discount' ? 'Percent: '. $coupon->discount_value.'%' : 'Price: Rp.'. number_format($coupon->discount_value, 0, ',', '.') }})</small></option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                
                                                                    <div class="form-group">
                                                                        <h6 class="mt-3">Metode Payment</h6>
                                                                        <select name="payment_method" class="form-control form-control-sm payment-method">
                                                                            <option selected value="Transfer Bank">Transfer Bank</option>
                                                                            <option value="EDC BCA">EDC BCA</option>
                                                                            <option value="EDC BRI">EDC BRI</option>
                                                                            <option value="EDC BNI">EDC BNI</option>
                                                                            <option value="EDC PANIN">EDC PANIN</option>
                                                                            <option value="Qris BCA">Qris BCA</option>
                                                                            <option value="Qris BRI">Qris BRI</option>
                                                                            <option value="Qris BNI">Qris BNI</option>
                                                                            <option value="Qris MANDIRI">Qris MANDIRI</option>
                                                                            <option value="Cash">Cash</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group mt-2 cash-input" style="display: none;">
                                                                        <label for="cash" class="form-label">Cash</label>
                                                                        <input type="text" name="cash" value="{{ old('cash') }}" class="form-control form-control-sm" placeholder="Ex:50.000" aria-describedby="cash">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div id="modalContainer"></div>
@endsection

@push('js')
<script>
    function ModalEditQtyProduct(url = '/modal-edit-qty-product', key, token) {
    var getTarget = `#modal-edit-qty-product-${key}`; // Ensure key is correctly passed
    $.ajax({
        url: url,
        type: 'GET',
        success: function(data) {
            $('#modalContainer').html(data); // Load the modal content
            $(getTarget).modal('show'); // Show the modal with the correct ID
            $(getTarget).on('shown.bs.modal', function () {
                // Now bind the event handlers here
                $("#btn-add").on("click", function() {
                    var input = $(this).siblings("input[type='number']");
                    var value = parseInt(input.val());
                    input.val(value + 1);
                });

                $("#btn-min").on("click", function() {
                    var input = $(this).siblings("input[type='number']");
                    var value = parseInt(input.val());
                    if (value > 1) {
                        input.val(value - 1);
                    }
                });

                $(".qty-add").on("change", function() {
                    if ($(this).val() < 0) {
                        $(this).val(0);
                    }
                });

                $('#updateQtyCartButton').on('click', function() {
                    const quantity = $('#qty-add').val();
                    const route = $(this).data('route');
                    const token = $(this).data('token');

                    updateCartQuantity(key, quantity, route, token, getTarget);
                });
            });
        },
        error: function(xhr, status, error) {
            console.error('Failed to load Product: ', error);
        }
    });
}


function updateCartQuantity(key, quantity, url, token, modalSelector) {
    $.ajax({
        url: url,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
        },
        data: {
            "key": key,
            "quantity": quantity,
        },
        success: function(response) {
            console.log(response);
            window.location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Failed to update cart item: ', error);
        }
    });
}
</script>
<script>
    $(document).ready(function() {
        // Toggle fields on page load based on the selected type
        $('.type-select').each(function() {
            toggleFields($(this));
        });

        // Event listener for change on the "Type" select
        $('.type-select').on('change', function() {
            toggleFields($(this));
        });

        function toggleFields(typeSelect) {
            var selectedType = typeSelect.val();
            var modalId = typeSelect.data('modal-id');
            var discountGroup = typeSelect.closest('.modal-body').find('.discount-group');
            var typeDiscountGroup = typeSelect.closest('.modal-body').find('.type-discount-group');
            var couponGroup = typeSelect.closest('.modal-body').find('.coupon-group');

            if (selectedType === 'Coupon') {
                couponGroup.show();
                discountGroup.hide();
                typeDiscountGroup.hide();
            } else if (selectedType === 'Discount') {
                couponGroup.hide();
                discountGroup.show();
                typeDiscountGroup.show();
            } else {
                couponGroup.hide();
                discountGroup.hide();
                typeDiscountGroup.hide();
            }
        }

        // Handle Discount Type change
        $('.select-type-discount').on('change', function() {
            var selectedValue = $(this).val();
            var modalBody = $(this).closest('.modal-body');
            var inputPrice = modalBody.find('.input-price');
            var inputPercent = modalBody.find('.input-percent');

            if (selectedValue == 'Price') {
                inputPrice.prop('disabled', false);
                inputPercent.prop('disabled', true).val(''); // Disable and clear the Percent input
            } else if (selectedValue == 'Percent') {
                inputPercent.prop('disabled', false);
                inputPrice.prop('disabled', true).val(''); // Disable and clear the Price input
            }
        });

        // Handle Rupiah formatting on price input
        $('.input-price').on('keyup', function() {
            handleInput($(this));
        });

        function formatRupiah(angka) {
            var numberString = angka.toString().replace(/\D/g, '');
            var ribuan = numberString.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            return ribuan;
        }

        function handleInput(inputField) {
            var input = inputField.val().replace(/\D/g, '');
            var formattedInput = formatRupiah(input);
            inputField.val(formattedInput);
        }
    });


    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.payment-method').forEach(selectInput => {
            const modalId = selectInput.getAttribute('data-modal-id');
            const cashInput = document.getElementById(`cashInput-${modalId}`);

            function handleCashInputDisplay() {
                if (selectInput.value === 'Cash') {
                    cashInput.style.display = 'block';
                } else {
                    cashInput.style.display = 'none';
                }
            }

            // Set initial state
            handleCashInputDisplay();

            // Add change event listener
            selectInput.addEventListener('change', handleCashInputDisplay);
        });
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
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

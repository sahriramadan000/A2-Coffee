<div class="modal fade" id="modal-my-order" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">My Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 m-0">
                <div class="container p-3">
                    <ul class="nav nav-pills justify-content-center mb-3" role="tablist">
                        {{-- <li class="nav-item w-50" role="presentation">
                            <a class="nav-link active" data-bs-toggle="pill" href="#primary-pills-home" role="tab" aria-selected="false" tabindex="-1">
                                <div class="text-center">
                                    <div class="tab-title">On Hold</div>
                                </div>
                            </a>
                        </li> --}}
                        <li class="nav-item w-50" role="presentation">
                            <a class="nav-link" data-bs-toggle="pill" href="#primary-pills-profile" role="tab" aria-selected="true">
                                <div class="text-center">
                                    <div class="tab-title">Paid</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item w-50" role="presentation">
                            <a class="nav-link" data-bs-toggle="pill" href="#primary-open-bill" role="tab" aria-selected="true">
                                <div class="text-center">
                                    <div class="tab-title">Open Bill</div>
                                </div>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content tab-style" id="pills-tabContent">
                        <div class="tab-pane fade active show" id="primary-pills-home" role="tabpanel">
                            <div class="list-group">
                                @forelse ($onhold_orders as $onhold)
                                <a href="javascript:;" class="list-group-item list-group-item-action" aria-current="true" id="onhold-{{ $onhold->key }}">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div class="align-content-center">
                                            <h6 class="p-0 m-0">{{ ($onhold->name ?? 'No Name') }}</h6>
                                            <small class="p-0 m-0">Key: {{ $onhold->key }}</small>
                                        </div>
                                        <div class="">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <button type="button" class="btn py-1 px-2 m-0 btn-success" onclick="openOnholdOrder('{{ route('open-on-hold-order') }}','{{ $onhold->key }}', '{{ csrf_token() }}')">
                                                    <small class="text-white">Open</small>
                                                </button>
                                                <button type="button" class="btn py-1 px-2 m-0 btn-danger" onclick="deleteOnholdOrder('{{ route('delete-on-hold-order') }}', '{{ $onhold->key }}', '{{ csrf_token() }}')">
                                                    <small class="text-white">Delete</small>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @empty
                                <a href="javascript:;" class="list-group-item list-group-item-action" aria-current="true">
                                    <div class="d-flex w-100 justify-content-center">
                                        <p class="mb-1">No order on hold.</p>
                                    </div>
                                </a>
                                @endforelse
                            </div>
                        </div>
                        <div class="tab-pane fade" id="primary-pills-profile" role="tabpanel">
                            <div class="list-group">
                                @forelse ($order_paids as $order_paid)
                                <div class="list-group-item list-group-item-action" aria-current="true">
                                    <div class="d-flex w-100 justify-content-between">
                                        <div class="">
                                            <p class="mb-1" style="font-size:14px;"><span class="fw-medium">No Invoice:</span> {{ $order_paid->no_invoice ?? '-' }}</p>
                                            <p class="mb-1" style="font-size:14px;"><span class="fw-medium">Cashier:</span> {{ $order_paid->cashier_name ?? '-' }}</p>
                                            <p class="mb-1" style="font-size:14px;"><span class="fw-medium">Metode Pembayaran:</span> {{ $order_paid->payment_method ?? '-' }}</p>
                                            <p class="mb-1" style="font-size:14px;"><span class="fw-medium">Total:</span> Rp.{{ number_format($order_paid->total, 0, ',', '.' ) ?? '-' }}</p>
                                        </div>
                                        <div class="">
                                            <p class="mb-1" style="font-size:14px;"><span class="fw-medium">Customer:</span> {{ $order_paid->customer_name ?? '-' }}</p>
                                            <p class="mb-1" style="font-size:14px;"><span class="fw-medium">Date:</span> {{ date('d-m-Y H:i', strtotime($order_paid->created_at)) }}</p>
                                            {{-- <div class="btn-group mt-2" role="group" aria-label="Basic example">
                                                <a href="{{ route('print-customer',$order_paid->id) }}" class="btn py-1 px-2 m-0 btn-warning" type="button">
                                                    <small class="text-white">Print</small>
                                                </a>
                                                <a href="{{ route('print-struk',$order_paid->id) }}" class="btn py-1 px-2 m-0 btn-danger" type="button">
                                                    <small class="text-white">Print Struk</small>
                                                </a>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <a href="javascript:;" class="list-group-item list-group-item-action" aria-current="true">
                                    <div class="d-flex w-100 justify-content-center">
                                        <p class="mb-1">No data transaction.</p>
                                    </div>
                                </a>
                                @endforelse
                            </div>
                        </div>
                        <div class="tab-pane fade" id="primary-open-bill" role="tabpanel">
                            <div class="list-group">
                                @forelse ($order_open_bills as $order_open_bill)
                                <div class="list-group-item list-group-item-action" aria-current="true">
                                    <div class="d-flex w-100 justify-content-between">
                                        <div class="">
                                            <p class="mb-1" style="font-size:14px;"><span class="fw-medium">No Invoice:</span> {{ $order_open_bill->no_invoice ?? '-' }}</p>
                                            <p class="mb-1" style="font-size:14px;"><span class="fw-medium">Cashier:</span> {{ $order_open_bill->cashier_name ?? '-' }}</p>
                                            <p class="mb-1" style="font-size:14px;"><span class="fw-medium">Metode Pembayaran:</span> {{ $order_open_bill->payment_method ?? '-' }}</p>
                                            <p class="mb-1" style="font-size:14px;"><span class="fw-medium">Total:</span> Rp.{{ number_format($order_open_bill->total, 0, ',', '.' ) ?? '-' }}</p>
                                        </div>
                                        <div class="">
                                            <p class="mb-1" style="font-size:14px;"><span class="fw-medium">Customer:</span> {{ $order_open_bill->customer_name ?? '-' }}</p>
                                            <p class="mb-1" style="font-size:14px;"><span class="fw-medium">Date:</span> {{ date('d-m-Y H:i', strtotime($order_open_bill->created_at)) }}</p>
                                            <div class="btn-group mt-2" role="group" aria-label="Basic example">
                                                <button type="button" class="btn py-1 px-2 m-0 btn-warning" onclick="openBillOrder('{{ route('open-bill-order') }}','{{ $order_open_bill->id }}', '{{ csrf_token() }}','{{ $order_open_bill->inputer }}','{{ $order_open_bill->table }}')">
                                                    <small class="text-white">Open</small>
                                                </button>
                                                {{-- <a href="{{ route('print-customer',$order_open_bill->id) }}" class="btn py-1 px-2 m-0 btn-warning" type="button">
                                                    <small class="text-white">Print</small>
                                                </a> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <a href="javascript:;" class="list-group-item list-group-item-action" aria-current="true">
                                    <div class="d-flex w-100 justify-content-center">
                                        <p class="mb-1">No data transaction.</p>
                                    </div>
                                </a>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    function openBillOrder(url, id, token,inputer,table) {
        console.log('Inputer:', inputer);
        $.ajax({
            url: url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "_token": token,
                "id": id,
                "inputer": inputer,
                "table": table,
            },
            success: function(response) {
                console.log(response);
                $('#cart-product').empty();
                $.each(response.data, function(index, cart) {
                    var addList = `<tr class="table-cart text-white">`+
                                        `<td class="td-cart">`+
                                            `<div class="d-flex justify-content-between">`+
                                                `<div class="">`+
                                                    `<p class="p-0 m-0 text-white">`+
                                                        `${cart.name}`+
                                                    `</p>`+
                                                `</div>`+

                                                `<div>`+
                                                    `<a href="/delete-item/${index}" class="" style="border-bottom: 1px dashed red;">`+
                                                        `<i class='bx bx-trash font-14 text-danger'></i>`+
                                                    `</a>`+
                                                `</div>`+
                                            `</div>`+
                                        `</td>`+
                                        `<td class="td-cart">${cart.quantity}</td>`+
                                        `<input type="hidden" name="qty[]" id="quantityInput" class="form-control qty" min="0"  value="${cart.quantity}">`+
                                        `<td class="td-cart">Rp.${numberFormat(cart.price)}</td>`+
                                    `</tr>`;

                    $('#cart-product').append(addList);
                });

                $('#subtotal-cart').text(`Rp.${formatRupiah(response.subtotal)}`);
                $('#tax-cart').text(`Rp.${response.tax}`);
                $('#total-cart').text(`Rp.${formatRupiah(response.total)}`);
                $('#modal-my-order').modal('hide');
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error('Failed to load Product: ', error);
            }
        });
    }
</script>
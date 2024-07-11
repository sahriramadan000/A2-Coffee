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
                        <li class="nav-item w-50" role="presentation">
                            <a class="nav-link active" data-bs-toggle="pill" href="#primary-pills-home" role="tab" aria-selected="false" tabindex="-1">
                                <div class="text-center">
                                    <div class="tab-title">On Hold</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item w-50" role="presentation">
                            <a class="nav-link" data-bs-toggle="pill" href="#primary-pills-profile" role="tab" aria-selected="true">
                                <div class="text-center">
                                    <div class="tab-title">Paid</div>
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
                                            <div class="btn-group mt-2" role="group" aria-label="Basic example">
                                                {{-- <a href="{{ route('kasir.dashboard-detail-kasir.show',$order_paid->id) }}" class="btn py-1 px-2 m-0 btn-warning" type="button"> --}}
                                                <a href="#!" class="btn py-1 px-2 m-0 btn-warning" type="button">
                                                    <small class="text-white">Print</small>
                                                </a>
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

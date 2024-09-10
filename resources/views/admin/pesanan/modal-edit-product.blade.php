<div class="modal fade" id="modal-edit-product-{{ $order_id }}-{{ str_replace(' ', '-',$product_name) }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Qty</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 m-0">
                <div class="row justify-content-center py-3">
                    <div class="col-12 col-md-12">
                        <ul class="list-group list-group-flush">
                            @foreach ($productDetail as $orderProduct)
                            <li class="list-group-item" style="background: transparent;">
                                <div class="d-flex w-100 justify-content-between align-items-start">
                                    <h4 style="color: #ffffff">{{ $orderProduct->name }}</h4>
                                    <div class="d-flex align-items-center ml-auto gap-3">
                                        <small style="border-bottom: 1px dashed #bfbfbf; color: #ffffff; cursor:pointer;" onclick="ModalEditQtyProduct('{{ route('modal-edit-qty-product', $orderProduct->id) }}', '{{ $orderProduct->id }}', '{{ $order_id }}', '{{ str_replace(' ', '-',$product_name) }}', '{{ csrf_token() }}')">x{{ $orderProduct->qty }}</small>
                                    </div>
                                </div>
                                <span class="text-white">Timestamp: {{ date('d-m-Y H:i:s', strtotime($orderProduct->created_at)) }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit-qty-cart-{{ $key }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Qty</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 m-0">
                <div class="row justify-content-center py-3">
                    <div class="col-12 col-md-8">
                        <div class="w-100 d-flex justify-content-center">
                            <button class="btn btn-default text-white" id="btn-min" style="background: #0c0f1d !important; padding:5px 10px !important;border-color:#000;opacity:0.5;">-</button>
                            <input type="number" name="qty" id="qty-add" class="qty-add form-control rounded-0 text-center p-1 bg-transparent border-0 w-50" value="{{ $quantity }}">
                            <button class="btn btn-default text-white" id="btn-add" style="background: #0c0f1d !important; padding:5px 10px !important;border-color:#000;opacity:0.5;">+</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="updateQtyCartButton" data-route="{{ route('update-cart-qty') }}" data-token="{{ csrf_token() }}" class="btn btn-primary">Update Cart</button>
            </div>
        </div>
    </div>
</div>

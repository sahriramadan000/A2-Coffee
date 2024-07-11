<div class="modal fade" id="modal-add-coupon" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Coupon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 m-0">
                <div class="row mt-3">
                    <div class="col-lg-12 px-4 mb-3">
                        <select class="form-select mb-3" id="select-coupon" aria-label="Default select example">
                            <option selected disabled>Select Coupon</option>
                            @foreach ($coupons as $coupon)
                            <option value="{{ $coupon->id }}">{{ $coupon->name }} <small>({{ $coupon->type == 'Percentage Discount' ? 'Percent: '. $coupon->discount_value.'%' : 'Price: Rp.'. number_format($coupon->discount_value, 0, ',', '.') }})</small></option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-coupon">Save changes</button>
            </div>
        </div>
    </div>
</div>

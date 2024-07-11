<div class="modal fade" id="modal-add-discount" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Set Discount</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: white !important;"></button>
            </div>
            <div class="modal-body p-0 m-0">
                <div class="row mt-3">
                    <div class="col-lg-12 px-4 mb-3">
                        <select class="form-select mb-3" id="select-type-discount" aria-label="Default select example">
                            <option selected disabled>Select Discount Type</option>
                            <option value="price">Price</option>
                            <option value="percent">Percent</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 ps-4 mb-3">
                        <div class="input-group">
                            <span class="input-group-text">Rp.</span>
                            <input type="text" class="form-control" aria-label="Price" name="input-price" id="input-price" disabled>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 pe-4 mb-3">
                        <div class="input-group">
                            <input type="number" class="form-control" min="0" max="100" aria-label="percent" name="input-percent" id="input-percent" disabled>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>

                    {{-- <hr>
                    <div class="col-12 col-md-6 ps-4 mb-3">
                        <label class="mb-2" for="">Ongkir</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp.</span>
                            <input type="text" class="form-control" aria-label="ongkir" name="ongkir-price" id="ongkir-price" >
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-discount">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-notification" id="tabs-{{ $product->id }}-delete-product" tabindex="-1" role="dialog" aria-labelledby="tabsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="mt-0 modal-content" action="{{ route('products.destroy', $product->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <div class="icon-content m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-inbox"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg>
                    </div>
                </div>

                <div class="text-center mb-3 mt-3">
                    <h4 class="mb-0">DELETE PRODUCT</h4>
                </div>

                <p class="modal-text text-center">Apakah anda yakin ingin menghapus data ini? ({{ $product->name }})</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light-dark" type="button" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>


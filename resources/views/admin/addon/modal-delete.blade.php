<div class="modal fade modal-notification" id="tabs-{{ $addon->id }}-delete-addon" tabindex="-1" role="dialog" aria-labelledby="tabsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="mt-0 modal-content" action="{{ route('addons.destroy', $addon->id) }}" method="post">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <div class="icon-content m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                    </div>
                </div>

                <div class="text-center mb-3 mt-3">
                    <h4 class="mb-0">DELETE ADDON</h4>
                </div>

                <p class="modal-text text-center">Apakah anda yakin ingin menghapus data ini? ({{ $addon->name }})</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light-dark" type="button" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>


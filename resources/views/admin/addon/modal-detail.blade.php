<div class="modal fade modal-notification" id="tabs-{{ $addonId }}-detail-addon" tabindex="-1" role="dialog" aria-labelledby="tabsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <div class="icon-content m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                </div>

                <div class="text-center mb-3 mt-3">
                    <h4 class="mb-0">DETAIL ADDON</h4>
                </div>


                <div class="mt-0 row">
                    <ul class="list-group list-group-media">
                        @forelse ($childrens as $children)
                            <li class="list-group-item bg-transparent">
                                <div class="media d-flex justify-content-between align-items-center">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="me-3">
                                            <h5 class="mb-0 pb-0">{{ $loop->iteration }}</h5>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="tx-inverse mb-0 pb-0">{{ $children->name }} (<small class="mg-b-0 my-0 py-0 {{ $children->status ? 'text-success' : 'text-danger' }}">{{ $children->status ? 'Active' : 'Inactive' }}</small>)</h6>
                                            <div class=" d-flex">
                                                <a href="#" type="button" class="text-warning addons-edit-table" data-bs-target="#tabs-{{ $children->id }}-edit-addon">Edit</a> |
                                                <a href="#" type="button" class="text-danger addons-delete-table"  data-bs-target="#tabs-{{ $children->id }}-delete-addon">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <span class="badge bg-primary rounded-3">Rp. {{ number_format($children->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </li>
                        @empty
                        <li class="list-group-item bg-transparent">
                            <div class="media justify-content-center">
                                <h4 class="text-center">Data Not Found!</h4>
                            </div>
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light-dark" type="button" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


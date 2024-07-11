<div class="modal fade" id="modal-add-customer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 m-0">
                <div class="container p-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group" style="justify-content: center !important;">
                                <button class="btn btn-primary text-white d-flex align-items-center" type="button"><i class='bx bx-search-alt me-0' style="font-size:1.2rem;"></i></button>
                                <input type="text" class="form-control typeahead tt-query" autocomplete="off" spellcheck="false" placeholder="Search Customer...">
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-12 col-md-12 text-center mb-3">
                    <div class="list-group">
                        @forelse ($customers as $customer)
                        <a href="javascript:;" type="button" class="list-group-item select-customer list-group-item-action" aria-current="true" data-customer-id="{{ $customer->id }}">
                            <div class="d-flex w-100 justify-content-start">
                                <h6 class="mb-1">{{ $customer->name }}</h6>
                            </div>
                        </a>
                        @empty
                        <a href="javascript:;" class="list-group-item list-group-item-action" aria-current="true">
                            <div class="d-flex w-100 justify-content-center">
                                <h6 class="mb-1">No data customer</h6>
                            </div>
                        </a>
                        @endforelse
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>

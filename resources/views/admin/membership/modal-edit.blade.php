<div class="modal fade modal-notification" id="tabs-{{ $membership->id }}-edit-membership" tabindex="-1" role="dialog" aria-labelledby="tabsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="mt-0 modal-content" action="{{ route('memberships.update', $membership->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <div class="icon-content m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    </div>
                </div>

                <div class="text-center mb-3 mt-3">
                    <h4 class="mb-0">EDIT Membership</h4>
                </div>


                <div class="mt-0 row">
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="name">Customer</label>
                            <select name = "customer_id"  class="form-select @error('customer_id') is-invalid @enderror" aria-label="customer_id" id="floatingSelect" name="customer_id">
                                <option value="">-- Select Customer --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $membership->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="membership_type">Membership Type</label>
                            <select class="form-control form-control-lg" name="membership_type" id="membership_type">
                                <option value="silver" {{ ($membership->membership_type == 'silver') ? 'selected' : '' }}>Silver</option>
                                <option value="gold" {{ ($membership->membership_type == 'gold') ? 'selected' : '' }}>Gold</option>
                                <option value="platinum" {{ ($membership->membership_type == 'platinum') ? 'selected' : '' }}>Gold</option>
                            </select>

                            @if($errors->has('status'))
                                <p class="text-danger">{{ $errors->first('status') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" class="form-control form-control-sm" placeholder="Ex:Susu Sachet" aria-label="start_date" id="start_date" value="{{ $product->start_date ?? old('start_date') }}">

                            @if($errors->has('start_date'))
                                <p class="text-danger">{{ $errors->first('start_date') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" class="form-control form-control-sm" placeholder="Ex:Susu Sachet" aria-label="end_date" id="end_date" value="{{ $product->end_date ?? old('end_date') }}">

                        </div>
                    </div>

                    <div class="col-12 col-md-12">
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select class="form-control form-control-sm" name="status" id="status">
                                <option value="active" {{ ($membership->status == 'active') ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ ($membership->status == 'inactive') ? 'selected' : '' }}>Enactive</option>
                                <option value="expired" {{ ($membership->status == 'expired') ? 'selected' : '' }}>Expired</option>
                            </select>

                            @if($errors->has('status'))
                                <p class="text-danger">{{ $errors->first('status') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light-dark" type="button" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>


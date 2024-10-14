<div class="modal fade modal-notification" id="memberships-add-table" tabindex="-1" role="dialog" aria-labelledby="membershipsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('memberships.store') }}" method="POST" class="modal-content">
        @csrf
        @method('POST')
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <div class="icon-content m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    </div>
                </div>

                <div class="text-center mb-3 mt-3">
                    <h4 class="mb-0">ADD TABLE</h4>
                </div>

                <div class="mt-0 row">
                    <div class="col-12 col-md-6">
                        <div class="form-group"style="text-align: left">
                            <label class="form-label">Customer</label>
                            <select class="form-select mr-sm-2 @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" style="width:100%">
                                <option disabled selected>Choose Customer</option>
                                @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <div class="form-group">
                            <label for="is_discount" class="text-white" style="opacity: .8;">Membership Type</label>
                            <select class="form-control form-control-sm" name="membership_type" id="membership_type">
                                <option selected value="silver">Silver</option>
                                <option value="gold">Gold</option>
                                <option value="platinum">Platinum</option>
                            </select>

                            @if($errors->has('membership_type'))
                                <p class="text-danger">{{ $errors->first('membership_type') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" class="form-control form-control-sm" placeholder="Ex:Brian" aria-label="start_date" id="start_date" value="{{ old('start_date') }}">

                            @if($errors->has('start_date'))
                                <p class="text-danger">{{ $errors->first('start_date') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" class="form-control form-control-sm" placeholder="Ex:Brian" aria-label="end_date" id="end_date" value="{{ old('end_date') }}">

                            @if($errors->has('end_date'))
                                <p class="text-danger">{{ $errors->first('end_date') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-12 mb-3">
                        <div class="form-group">
                            <label for="is_discount" class="text-white" style="opacity: .8;">Status</label>
                            <select class="form-control form-control-sm" name="status" id="status">
                                <option selected value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="expired">Expired</option>
                            </select>

                            @if($errors->has('status'))
                                <p class="text-danger">{{ $errors->first('status') }}</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>


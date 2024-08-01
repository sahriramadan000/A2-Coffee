<div class="modal fade modal-notification" id="tabs-add-table" tabindex="-1" role="dialog" aria-labelledby="tabsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('tables.store') }}" method="POST" class="modal-content">
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
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label for="code">Code</label>
                            <input type="text" name="code" class="form-control form-control-sm" placeholder="Ex:CUST00001" aria-label="code" id="code" value="{{ $code ?? old('code') }}" readonly>

                            @if($errors->has('code'))
                                <p class="text-danger">{{ $errors->first('code') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control form-control-sm" placeholder="Ex:Brian" aria-label="name" id="name" value="{{ old('name') }}">

                            @if($errors->has('name'))
                                <p class="text-danger">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select class="form-control form-control-sm" name="status" id="status">
                                <option selected value="active">Active</option>
                                <option value="hidden">Hidden</option>
                            </select>

                            @if($errors->has('status'))
                                <p class="text-danger">{{ $errors->first('status') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="status">Status Position</label>
                            <select class="form-control form-control-sm" name="status_position" id="status_position">
                                <option selected value="Open">Open</option>
                                <option value="Close">Close</option>
                                <option value="None">None</option>
                            </select>

                            @if($errors->has('status_position'))
                                <p class="text-danger">{{ $errors->first('status_position') }}</p>
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


<div class="modal fade modal-notification" id="tabs-add-material" tabindex="-1" role="dialog" aria-labelledby="tabsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('materials.store') }}" method="post" class="modal-content">
        @csrf
        @method('POST')
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <div class="icon-content m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-archive"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg>
                    </div>
                </div>

                <div class="text-center mb-3 mt-3">
                    <h4 class="mb-0">ADD MATERIAL</h4>
                </div>

                <div class="mt-0 row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label for="code">Code</label>
                            <input type="text" name="code" class="form-control form-control-sm" placeholder="Ex:MTR00001" aria-label="code" id="code" value="{{ $code ?? old('code') }}" readonly>

                            @if($errors->has('code'))
                                <p class="text-danger">{{ $errors->first('code') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control form-control-sm" placeholder="Ex:Susu Sachet" aria-label="name" id="name" value="{{ old('name') }}">

                            @if($errors->has('name'))
                                <p class="text-danger">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="unit">Unit</label>
                            <input type="text" name="unit" class="form-control form-control-sm" placeholder="Ex:KG" aria-label="unit" id="unit" value="{{ old('unit') }}">

                            @if($errors->has('unit'))
                                <p class="text-danger">{{ $errors->first('unit') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="minimum_stock">Minimum Stock</label>
                            <input type="number" min="0" name="minimum_stock" class="form-control form-control-sm" placeholder="Ex:12" aria-label="minimum_stock" id="minimum_stock" value="{{ old('minimum_stock') }}">

                            @if($errors->has('minimum_stock'))
                                <p class="text-danger">{{ $errors->first('minimum_stock') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="supplier_id">Supplier</label>
                            <select class="form-control form-control-sm" name="supplier_id" id="supplier_id">
                                <option selected disabled value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->fullname }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('supplier_id'))
                                <p class="text-danger">{{ $errors->first('supplier_id') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Ex:Susu describe">{{ old('description') }}</textarea>

                            @if($errors->has('description'))
                                <p class="text-danger">{{ $errors->first('description') }}</p>
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


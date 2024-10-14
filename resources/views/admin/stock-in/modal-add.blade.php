<div class="modal fade modal-notification" id="tabs-add-stock-in" tabindex="-1" role="dialog" aria-labelledby="stock-insModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('stock-ins.store') }}" method="POST" class="modal-content">
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
                            <label class="form-label">Product Name</label>
                            <select class="form-select mr-sm-2 @error('product_id') is-invalid @enderror" id="product_id" name="product_id" style="width:100%">
                                <option disabled selected>Choose Product</option>
                                @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="stock_in">Stock In</label>
                            <input type="number" name="stock_in" class="form-control form-control-lg" placeholder="Ex:10" aria-label="stock_in" id="stock_in" value="{{ old('stock_in') }}">

                            @if($errors->has('stock_in'))
                                <p class="text-danger">{{ $errors->first('stock_in') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Ex:describe">{{ old('description') }}</textarea>

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


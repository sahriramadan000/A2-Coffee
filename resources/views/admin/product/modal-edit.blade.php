<div class="modal fade modal-notification" id="tabs-{{ $product->id }}-edit-product" tabindex="-1" role="dialog" aria-labelledby="tabsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="mt-0 modal-content" action="{{ route('products.update', $product->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <div class="icon-content m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                </div>

                <div class="text-center mb-3 mt-3">
                    <h4 class="mb-0">EDIT PRODUCT</h4>
                </div>

                <div class="simple-pill">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-details-icon-tab" data-bs-toggle="pill" data-bs-target="#pills-details-icon" type="button" role="tab" aria-controls="pills-details-icon" aria-selected="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                Details
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-price-icon-tab" data-bs-toggle="pill" data-bs-target="#pills-price-icon" type="button" role="tab" aria-controls="pills-price-icon" aria-selected="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                Price
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-stock-icon-tab" data-bs-toggle="pill" data-bs-target="#pills-stock-icon" type="button" role="tab" aria-controls="pills-stock-icon" aria-selected="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                Stock
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-others-icon-tab" data-bs-toggle="pill" data-bs-target="#pills-others-icon" type="button" role="tab" aria-controls="pills-others-icon" aria-selected="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sliders"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
                                Others
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-details-icon" role="tabpanel" aria-labelledby="pills-details-icon-tab" tabindex="0">
                            <div class="mt-0 row">
                                <div class="col-12 col-md-6">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="code">Code</label>
                                            <input type="text" name="code" class="form-control form-control-sm" placeholder="Ex:MTR00001" aria-label="code" id="code" value="{{ $product->code ?? old('code') }}" readonly>

                                            @if($errors->has('code'))
                                                <p class="text-danger">{{ $errors->first('code') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" class="form-control form-control-sm" placeholder="Ex:Susu Sachet" aria-label="name" id="name" value="{{ $product->name ?? old('name') }}">

                                            @if($errors->has('name'))
                                                <p class="text-danger">{{ $errors->first('name') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="category">Category</label>
                                            <select class="form-control form-control-sm" name="category" id="category">
                                                <option value="food" {{ $product->category == 'food' ? 'selected' : '' }}>Food</option>
                                                <option value="drink" {{ $product->category == 'drink' ? 'selected' : '' }}>Drink</option>
                                            </select>

                                            @if($errors->has('category'))
                                                <p class="text-danger">{{ $errors->first('category') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="col-12">
                                        <div class="form-group mb-3 text-left">
                                            <label for="picture">Picture</label>
                                            <img src="{{ $product->picture ? asset('images/products/'.$product->picture) : 'https://ui-avatars.com/api/?name=No+Image' }}" alt="" class="d-block mx-auto p-1 bg-black mb-2" style="width: 73px !important; border-radius:50%;">
                                            <input type="file" class="form-control file-upload-input" name="picture" aria-label="picture" id="picture">

                                            @if($errors->has('picture'))
                                                <p class="text-danger">{{ $errors->first('picture') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="status">Status</label>
                                            <select class="form-control form-control-sm" name="status" id="status">
                                                <option value="true" {{ $product->status == true ? 'selected' : '' }}>Active</option>
                                                <option value="false" {{ $product->status == false ? 'selected' : '' }}>Inactive</option>
                                            </select>

                                            @if($errors->has('status'))
                                                <p class="text-danger">{{ $errors->first('status') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Ex:Susu describe">{{ $product->description ?? old('description') }}</textarea>

                                        @if($errors->has('description'))
                                            <p class="text-danger">{{ $errors->first('description') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-price-icon" role="tabpanel" aria-labelledby="pills-price-icon-tab" tabindex="0">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="cost_price" class="text-white" style="opacity: .8;">Cost Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" name="cost_price" id="cost_price" class="form-control form-control-sm" aria-label="Cost Price" placeholder="Ex:10.000" value="{{ $product->cost_price ?? old('cost_price') }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="selling_price" class="text-white" style="opacity: .8;">Selling Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" name="selling_price" id="selling_price" class="form-control form-control-sm" aria-label="Selling Price" placeholder="Ex:10.000" value="{{ $product->selling_price ?? old('selling_price') }}">
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <h4 class="mb-1 pb-1 text-center">Discount</h4>
                                    <hr class="p-0 m-0" style="border-bottom: 1px solid white;">
                                </div>

                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="is_discount" class="text-white" style="opacity: .8;">Status Discount</label>
                                        <select class="form-control form-control-sm" name="is_discount" id="is_discount">
                                            <option value="true" {{ $product->is_discount == true ? 'selected' : '' }}>Active</option>
                                            <option value="false" {{ $product->is_discount == false ? 'selected' : '' }}>Inactive</option>
                                        </select>

                                        @if($errors->has('is_discount'))
                                            <p class="text-danger">{{ $errors->first('is_discount') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 col-md-7 mb-3">
                                    <label for="price_discount" class="text-white" style="opacity: .8;">Price Discount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" name="price_discount" id="price_discount" class="form-control form-control-sm" aria-label="Price Discount" placeholder="Ex:10.000" value="{{ $product->price_discount ?? 0 }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-5 mb-3">
                                    <label for="percent_discount" class="text-white" style="opacity: .8;">Percent Discount</label>
                                    <div class="input-group">
                                        <input type="number" name="percent_discount" id="percent_discount" min="0" max="100" class="form-control form-control-sm" aria-label="Percent Discount" placeholder="Ex:50" value="{{ $product->percent_discount ?? 0 }}">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-stock-icon" role="tabpanel" aria-labelledby="pills-stock-icon-tab" tabindex="0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="stock_per_day">Stock Per Day</label>
                                        <input type="number" name="stock_per_day" min="0" id="stock_per_day" class="form-control form-control-sm" placeholder="Ex:200" aria-label="stock_per_day" value="{{ $product->stock_per_day ?? old('stock_per_day') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="minimum_stock">Minimum Stock</label>
                                        <input type="number" name="minimum_stock" min="0" id="minimum_stock" class="form-control form-control-sm" placeholder="Ex:10" aria-label="minimum_stock" value="{{ $product->minimum_stock ?? old('minimum_stock') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-others-icon" role="tabpanel" aria-labelledby="pills-others-icon-tab" tabindex="0">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between">
                                            <label for="tag_id" class="text-white" style="opacity: .8;">Tags</label>
                                            <div class="">
                                                <label for="select-all-checkbox">Select All</label>
                                                <input type="checkbox" id="select-all-checkbox">
                                            </div>
                                        </div>
                                        <select id="tags-select" class="tags-select" name="tag_id[]" multiple placeholder="Select a tag..." autocomplete="off">
                                            @foreach ($tags as $tag)
                                                <option value="{{ $tag->id }}" {{ in_array($tag->id, $product->tags->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $tag->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @if($errors->has('tag_id'))
                                            <p class="text-danger">{{ $errors->first('tag_id') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between">
                                            <label for="addon_id" class="text-white" style="opacity: .8;">Addons</label>
                                            <div class="">
                                                <label for="select-all-checkbox">Select All</label>
                                                <input type="checkbox" id="select-all-checkbox-addons">
                                            </div>
                                        </div>
                                        <select id="addons-select" class="addons-select" name="addon_id[]" multiple placeholder="Select a addon..." autocomplete="off">
                                            @foreach ($addons as $addon)
                                                <option value="{{ $addon->id }}" {{ in_array($addon->id,  $product->addons->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $addon->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @if($errors->has('addon_id'))
                                            <p class="text-danger">{{ $errors->first('addon_id') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
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


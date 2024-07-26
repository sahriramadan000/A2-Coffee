<div class="modal fade modal-notification" id="tabs-{{ $addon->id }}-edit-addon" tabindex="-1" role="dialog" aria-labelledby="tabsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="mt-0 modal-content" action="{{ route('addons.update', $addon->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <div class="icon-content m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                    </div>
                </div>

                <div class="text-center mb-3 mt-3">
                    <h4 class="mb-0">EDIT ADDON</h4>
                </div>


                <div class="mt-0 row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control form-control-sm" placeholder="Ex:Desert" aria-label="name" id="name" value="{{ $addon->name ?? old('name') }}">

                            @if($errors->has('name'))
                                <p class="text-danger">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="price">Price</label>
                            <input type="text" name="price" class="form-control form-control-sm" placeholder="Ex:10000" aria-label="price" id="price" value="{{ $addon->price ?? old('price') }}">

                            @if($errors->has('price'))
                                <p class="text-danger">{{ $errors->first('price') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="position">Position</label>
                            <input type="number" name="position" class="form-control form-control-sm" placeholder="Ex:1" min="0" aria-label="position" id="position" value="{{ $addon->position ?? old('position') }}">

                            @if($errors->has('position'))
                                <p class="text-danger">{{ $errors->first('position') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="choose">Choose</label>
                            <input type="number" name="choose" class="form-control form-control-sm" placeholder="Ex:1" min="0" max="10" aria-label="choose" id="choose" value="{{ $addon->choose ?? old('choose') }}">

                            @if($errors->has('choose'))
                                <p class="text-danger">{{ $errors->first('choose') }}</p>
                            @endif
                        </div>
                    </div>

                     <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="status_optional">Status Optional</label>
                            <select class="form-control form-control-sm" name="status_optional" id="status_optional">
                                <option value="1" {{ (($addon->status_optional ?? old('status_optional')) == true) ? 'selected' : ''  }}>Yes</option>
                                <option value="0" {{ (($addon->status_optional ?? old('status_optional')) == false) ? 'selected' : ''  }}>No</option>
                            </select>

                            @if($errors->has('status_optional'))
                                <p class="text-danger">{{ $errors->first('status_optional') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="parent_id">Parent Addon</label>
                            <select class="form-control form-control-sm" name="parent_id" id="parent_id">
                                <option selected value="">No Parent Addon</option>
                                @foreach ($parents as $parent)
                                    @if ($parent->id != $addon->id && $addon->children->count() == 0)
                                    <option value="{{ $parent->id }}" {{ (($addon->parent_id ?? old('parent_id')) == $parent->id) ? 'selected' : ''  }}>{{ $parent->name }}</option>
                                    @endif
                                @endforeach
                            </select>

                            @if($errors->has('parent_id'))
                            <p class="text-danger">{{ $errors->first('parent_id') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select class="form-control form-control-sm" name="status" id="status">
                                <option value="1" {{ $addon->status == true ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $addon->status == false ? 'selected' : '' }}>Inactive</option>
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


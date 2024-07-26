<div class="modal fade modal-notification" id="tabs-{{ $tag->id }}-edit-tag" tabindex="-1" role="dialog" aria-labelledby="tabsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="mt-0 modal-content" action="{{ route('tags.update', $tag->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <div class="icon-content m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                    </div>
                </div>

                <div class="text-center mb-3 mt-3">
                    <h4 class="mb-0">EDIT TAG</h4>
                </div>


                <div class="mt-0 row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control form-control-sm" placeholder="Ex:Desert" aria-label="name" id="name" value="{{ $tag->name ?? old('name') }}">

                            @if($errors->has('name'))
                                <p class="text-danger">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="position">Position</label>
                            <input type="number" name="position" class="form-control form-control-sm" placeholder="Ex:1" min="0" aria-label="position" id="position" value="{{ $tag->position ?? old('position') }}">

                            @if($errors->has('position'))
                                <p class="text-danger">{{ $errors->first('position') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select class="form-control form-control-sm" name="status" id="status">
                                <option value="1" {{ $tag->status == true ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $tag->status == false ? 'selected' : '' }}>Inactive</option>
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


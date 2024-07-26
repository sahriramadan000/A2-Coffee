<div class="modal fade modal-notification" id="tabs-other-setting" tabindex="-1" role="dialog" aria-labelledby="tabsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="mt-0 modal-content" action="{{ route('other-settings.update', ($other_setting->id ?? 0)) }}" method="post">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <div class="icon-content m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    </div>
                </div>

                <div class="text-center mb-3 mt-3">
                    <h4 class="mb-0">EDIT OTHER SETTING</h4>
                </div>


                <div class="mt-0 row">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="pb01">PB01</label>
                        <div class="input-group">
                            <input type="number" name="pb01" class="form-control form-control-sm" placeholder="Ex:11" aria-label="pb01" id="pb01" min="0" max="100" value="{{ $other_setting->pb01 ?? old('pb01') }}">
                            <span class="input-group-text">%</span>

                            @if($errors->has('pb01'))
                                <p class="text-danger">{{ $errors->first('pb01') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="layanan">Layanan</label>
                        <div class="input-group">
                            <input type="text" name="layanan" class="form-control form-control-sm" placeholder="Ex:5" aria-label="layanan" id="layanan" min="0" max="100" value="{{ $other_setting->layanan ?? old('layanan') }}">
                            <span class="input-group-text">%</span>

                            @if($errors->has('layanan'))
                                <p class="text-danger">{{ $errors->first('layanan') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="time_start">Open Outlet</label>
                            <input type="time" name="time_start" class="form-control form-control-sm" aria-label="time_start" id="time_start" value="{{ date('H:i', strtotime($other_setting->time_start)) }}">

                            @if($errors->has('time_start'))
                                <p class="text-danger">{{ $errors->first('time_start') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="time_close">Close Outlet</label>
                            <input type="time" name="time_close" class="form-control form-control-sm" aria-label="time_close" id="time_close" value="{{ date('H:i', strtotime($other_setting->time_close)) }}">

                            @if($errors->has('time_close'))
                                <p class="text-danger">{{ $errors->first('time_close') }}</p>
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


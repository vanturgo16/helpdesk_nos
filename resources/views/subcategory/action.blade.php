<button type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#edit{{ $data->id }}">
    <i class="mdi mdi-file-edit label-icon"></i> {{ __('messages.edit') }}
</button>
@if($data->is_active == 1)
    <button type="button" class="btn btn-sm btn-danger waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#disable{{ $data->id }}">
        <i class="mdi mdi-window-close label-icon"></i> {{ __('messages.disable') }}
    </button>
@else
    <button type="button" class="btn btn-sm btn-success waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#enable{{ $data->id }}">
        <i class="mdi mdi-check label-icon"></i> {{ __('messages.enable') }}
    </button>
@endif

{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Update --}}
    <div class="modal fade" id="edit{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.edit') }} {{ __('messages.sub_category') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('subcategory.update', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">{{ __('messages.category') }}</label> <label class="text-danger">*</label>
                                <select class="form-control select2" name="id_mst_category" required>
                                    <option value="" disabled selected>- {{ __('messages.select') }} -</option>
                                    @foreach($categories as $item)
                                        <option value="{{ $item->id }}" @if($data->id_mst_category == $item->id) selected @endif>{{ $item->category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">{{ __('messages.subcategory_name') }}</label> <label class="text-danger">*</label>
                                <input class="form-control" type="text" name="sub_category" value="{{ $data->sub_category }}" placeholder="Input {{ __('messages.subcategory_name') }}.." required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">{{ __('messages.sla') }}</label> <label class="text-danger">*</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" placeholder="Input {{ __('messages.sla') }}.." name="sla" value="{{ $data->sla }}" required>
                                    <span class="input-group-text">{{ __('messages.minutes') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light"><i class="mdi mdi-update label-icon"></i>{{ __('messages.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Modal Disable --}}
    <div class="modal fade" id="disable{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.disable') }} {{ __('messages.sub_category') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('subcategory.disable', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            {{ __('messages.are_u_sure') }} <b>{{ __('messages.disable') }}</b> {{ __('messages.this_sub_cat') }}?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light">
                            <i class="mdi mdi-window-close label-icon"></i>{{ __('messages.disable') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Modal Enable --}}
    <div class="modal fade" id="enable{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.enable') }} {{ __('messages.sub_category') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('subcategory.enable', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            {{ __('messages.are_u_sure') }} <b>{{ __('messages.enable') }}</b> {{ __('messages.this_sub_cat') }}?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-success waves-effect btn-label waves-light">
                            <i class="mdi mdi-check label-icon"></i>{{ __('messages.enable') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/formLoad.js') }}"></script>

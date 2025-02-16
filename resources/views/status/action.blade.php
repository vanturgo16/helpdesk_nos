{{-- <button type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#edit{{ $data->id }}">
    <i class="mdi mdi-file-edit label-icon"></i> Edit
</button> --}}
@if($data->is_active == 1)
    <button type="button" class="btn btn-sm btn-danger waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#disable{{ $data->id }}">
        <i class="mdi mdi-window-close label-icon"></i> Disable
    </button>
@else
    <button type="button" class="btn btn-sm btn-success waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#enable{{ $data->id }}">
        <i class="mdi mdi-check label-icon"></i> Enable
    </button>
@endif

{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Update --}}
    <div class="modal fade" id="edit{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('status.update', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Status Name</label> <label class="text-danger">*</label>
                                <input class="form-control" type="text" name="status" value="{{ $data->status }}" placeholder="Input Status Name.." required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light"><i class="mdi mdi-update label-icon"></i>Update</button>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Disable Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('status.disable', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            Are You Sure to <b>Disable</b> This Status?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light">
                            <i class="mdi mdi-window-close label-icon"></i>Disable
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
                    <h5 class="modal-title" id="staticBackdropLabel">Enable Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('status.enable', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            Are You Sure to <b>Enable</b> This Status?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success waves-effect btn-label waves-light">
                            <i class="mdi mdi-check label-icon"></i>Enable
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/formLoad.js') }}"></script>

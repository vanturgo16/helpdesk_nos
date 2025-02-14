@extends('layouts.master')
@section('konten')

<div class="page-content">
    {{-- MAIN CARD --}}
    <div class="card">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Manage Institution Profile</h5>
            </div>
        </div>
        <div class="card-body contentScroll">
            {{-- BASIC --}}
            <div class="card">
                <div class="card-header bg-light py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Basic Information</h5>
                        <a href="#" type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#editBasic">
                            <i class="mdi mdi-file-edit label-icon"></i> Edit
                        </a>
                        {{-- Modal Edit --}}
                        <div class="modal fade" id="editBasic" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Edit Basic Information</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form class="formLoad" action="" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">Institution Number</label> <label class="text-danger">*</label>
                                                    <input class="form-control" type="text" name="institution_number" placeholder="Input Institution Number.." value="{{ $data->institution_number }}" required>
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">Institution Name</label> <label class="text-danger">*</label>
                                                    <input class="form-control" type="text" name="name" placeholder="Input Institution Name.." value="{{ $data->name }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" id="btnUpdHeadCheck" class="btn btn-success waves-effect btn-label waves-light">
                                                <i class="mdi mdi-update label-icon"></i>Update
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <div><span class="fw-bold">Institution Number :</span></div>
                                <span>
                                    <span>{{ $data->institution_number ?? '-' }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <div><span class="fw-bold">Institution Name :</span></div>
                                <span>
                                    <span>{{ $data->name ?? '-' }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ABOUT --}}
            <div class="card">
                <div class="card-header bg-light py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>About</h5>
                        <a href="#" type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#editAbout">
                            <i class="mdi mdi-file-edit label-icon"></i> Edit
                        </a>
                        {{-- Modal Edit --}}
                        <div class="modal fade" id="editAbout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Edit About Institution</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form class="formLoad" action="" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row mb-3">
                                                
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" id="btnUpdHeadCheck" class="btn btn-success waves-effect btn-label waves-light">
                                                <i class="mdi mdi-update label-icon"></i>Update
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <div class="form-group">
                                <div><span class="fw-bold">About :</span></div>
                                <span>
                                    <span>{!! $data->about ?? '-' !!}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <div><span class="fw-bold">Vision :</span></div>
                                <span>
                                    <span>{{ $data->vision ?? '-' }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <div><span class="fw-bold">Mission :</span></div>
                                <span>
                                    <span>{{ $data->mission ?? '-' }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ADDRESS --}}
            <div class="card">
                <div class="card-header bg-light py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Address</h5>
                        <a href="#" type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#editAddress">
                            <i class="mdi mdi-file-edit label-icon"></i> Edit
                        </a>
                        {{-- Modal Edit --}}
                        <div class="modal fade" id="editAddress" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Edit Address</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form class="formLoad" action="" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row mb-3">
                                                
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" id="btnUpdHeadCheck" class="btn btn-success waves-effect btn-label waves-light">
                                                <i class="mdi mdi-update label-icon"></i>Update
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <div class="form-group">
                                <div><span class="fw-bold">Address :</span></div>
                                <span>
                                    <span>{{ $data->address }}@if(!empty($data->subdistrict)), {{ $data->subdistrict }}@endif @if(!empty($data->district)) {{ $data->district }},@endif @if(!empty($data->city)) {{ $data->city }},@endif @if(!empty($data->provincies)) {{ $data->provincies }},@endif @if(!empty($data->postal_code)) {{ $data->postal_code }}.@endif</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- IMAGE & LOGO --}}
            <div class="card">
                <div class="card-header bg-light py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Image & Logo</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <div><span class="fw-bold">Institution Image :</span></div>
                                <input type="file" class="form-control mt-2">
                                <span><img src="{{ asset($data->path_image) }}" alt="" style="width: 100%"></span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Logo Square Light :</span></div>
                                        <input type="file" class="form-control mt-2">
                                        <span><img src="{{ asset($data->path_logo_sq_light) }}" alt="" style="width: 100%"></span>
                                    </div>
                                </div>
                                <div class="col-lg-8 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Logo Vertical Light :</span></div>
                                        <input type="file" class="form-control mt-2">
                                        <span><img src="{{ asset($data->path_logo_vert_light) }}" alt="" style="width: 100%"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Logo Square Dark :</span></div>
                                        <input type="file" class="form-control mt-2">
                                        <span><img src="{{ asset($data->path_logo_sq_dark) }}" alt="" style="width: 100%"></span>
                                    </div>
                                </div>
                                <div class="col-lg-8 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Logo Vertical Dark :</span></div>
                                        <input type="file" class="form-control mt-2">
                                        <span><img src="{{ asset($data->path_logo_vert_dark) }}" alt="" style="width: 100%"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light p-2"></div>
    </div>
</div>

@endsection
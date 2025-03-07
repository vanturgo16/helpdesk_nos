@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="row custom-margin">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="page-title-left">
                    <a href="{{ route('ticket.index') }}" class="btn btn-light waves-effect btn-label waves-light">
                        <i class="mdi mdi-arrow-left label-icon"></i> Back To List Ticket
                    </a>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('ticket.index') }}">List Ticket</a></li>
                        <li class="breadcrumb-item active"> Detail ({{ $data->no_ticket }})</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="card card-shadow">
        <div class="card-header p-3" style="background-color: #dddddd;">
            <div class="row">
                <div class="col-lg-6">
                    <h4 class="text-bold">Detail Ticket</h4>
                </div>
                <div class="col-lg-6">
                    <div class="text-end">
                        <h4>
                            @if($data->status == 0)
                                <span class="badge bg-secondary text-white"><i class="fas fa-play-circle"></i> Requested</span>
                            @elseif($data->status == 1)
                                <span class="badge bg-warning text-white"><i class="fas fa-spinner"></i> In-Progress</span>
                            @elseif($data->status == 2)
                                <span class="badge bg-success text-white"><i class="fas fa-check"></i> Done</span>
                            @endif
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body" style="background-color: #eeeeee;">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>No Ticket :</span></div>
                    <span>{{ $data->no_ticket }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>Priority :</span></div>
                    <span>{{ $data->priority }}</span>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>Category :</span></div>
                    <span>{{ $data->category }}</span>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>Sub-Category :</span></div>
                    <span>{{ $data->sub_category }}</span>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>Report Date :</span></div>
                    <span>{{ $data->report_date ? \Carbon\Carbon::parse($data->report_date)->format('Y-m-d H:i') : '-' }}</span>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>Created Date :</span></div>
                    <span>{{ $data->created_at ? \Carbon\Carbon::parse($data->created_at)->format('Y-m-d H:i') : '-' }}</span>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>Target Solved Date :</span></div>
                    <span>{{ $data->target_solved_date ? \Carbon\Carbon::parse($data->target_solved_date)->format('Y-m-d H:i') : '-' }}</span>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>Actual Closed Date :</span></div>
                    <span>{{ $data->closed_date ? \Carbon\Carbon::parse($data->closed_date)->format('Y-m-d H:i') : '-' }}</span>
                </div>                
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>Duration :</span></div>
                    <span>{{ $data->duration ?? '-' }}</span>
                </div>
                <div class="col-lg-12 mb-3">
                    <div class="fw-bold"><span>Attachment :</span></div>
                    <span>
                        @if($data->file_1)
                            <a href="{{ url($data->file_1) }}" target="_blank" class="btn btn-sm btn-info" type="button">
                                <span class="badge bg-light text-dark"><i class="fas fa-eye fa-sm"></i></span> Show
                            </a>
                        @else 
                            -
                        @endif
                    </span>
                </div>
                <div class="col-lg-12 mb-3">
                    <div class="fw-bold"><span>Notes :</span></div>
                    <span>{{ $data->notes }}</span>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    @include('ticket.assign.index')
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="card card-shadow">
        <div class="card-header p-3" style="background-color: #dddddd;">
            <div class="row">
                <div class="col-lg-6">
                    <h4 class="text-bold">Ticket Activity</h4>
                </div>
                <div class="col-lg-6">
                    @if(in_array($emailUser, $userAssign))
                        <div class="text-end">
                            @if($lastAssign->accept_date)
                                @if($lastAssign->preclosed_date)
                                    <button type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#reAssign">
                                        <i class="mdi mdi-close-circle label-icon"></i> Re-assign Ticket
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#addActivity">
                                        <i class="mdi mdi-plus label-icon"></i> Add Activity
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#preClose">
                                        <i class="mdi mdi-close-circle label-icon"></i> Pre-close Ticket
                                    </button>
                                    
                                    {{-- Modal Add Activity --}}
                                    <div class="modal fade" id="addActivity" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Add Activity</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form class="formLoad" action="{{ route('ticket.addActivity', encrypt($data->id)) }}" id="formadd" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body text-start">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Message (max 255 character)</label> <label class="text-danger">*</label>
                                                                    <textarea name="message" rows="8" cols="50" class="form-control" placeholder="Input Message.." required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Upload Attachment</label>
                                                                    <input type="file" name="attachment" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light"><i class="bx bx-plus label-icon"></i>Add</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else 
                                <button type="button" class="btn btn-sm btn-success waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#acceptTicket">
                                    <i class="mdi mdi-check label-icon"></i> Accept Ticket
                                </button>
                                {{-- Modal Accept --}}
                                <div class="modal fade" id="acceptTicket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-top" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Accept Ticket</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form class="formLoad" action="{{ route('ticket.accept', encrypt($lastAssign->id)) }}" id="formadd" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        Are You Sure to <b>Accept</b> This Ticket?
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="bx bx-check label-icon"></i>Accept Ticket</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else 
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body" style="background-color: #eeeeee;">
            <div class="row">
                @include('ticket.activity.index')
            </div>
        </div>
    </div>
</div>

@endsection
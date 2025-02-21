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
    <div class="card">
        <div class="card-header p-3 bg-light">
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
                                <span class="badge bg-warning text-white"><i class="fa-solid fa-spinner"></i> In-Progress</span>
                            @elseif($data->status == 2)
                                <span class="badge bg-success text-white"><i class="fa-solid fa-square-check"></i> Done</span>
                            @endif
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
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
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>Report Date :</span></div>
                    <span>{{ $data->report_date ?? '-' }}</span>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>Target Solved Date :</span></div>
                    <span>{{ $data->target_solved_date ?? '-' }}</span>
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
    <div class="card">
        <div class="card-header p-3">
            <div class="row">
                <div class="col-lg-6">
                    <h4 class="text-bold">Ticket Activity</h4>
                </div>
                <div class="col-lg-6">
                    <div class="text-end">
                        <button type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#addActivity">
                            <i class="mdi mdi-plus label-icon"></i> Add Activity
                        </button>
                        <button type="button" class="btn btn-sm btn-success waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#preClose">
                            <i class="mdi mdi-close-circle label-icon"></i> Pre-close Ticket
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @include('ticket.activity.index')
            </div>
        </div>
    </div>
</div>

@endsection
@extends('layouts.master')
@section('konten')

<div class="page-content">
    <div class="row custom-margin">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="page-title-left">
                    <a href="{{ route('ticket.index') }}" class="btn btn-light waves-effect btn-label waves-light">
                        <i class="mdi mdi-arrow-left label-icon"></i> {{ __('messages.back_to_list') }} {{ __('messages.ticket') }}
                    </a>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('ticket.index') }}">{{ __('messages.ticket_list') }}</a></li>
                        <li class="breadcrumb-item active"> Detail ({{ $data->no_ticket }})</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="card card-shadow">
        <div class="card-header p-3 header-detail-a">
            <div class="row">
                <div class="col-lg-6">
                    <h4 class="text-bold">Detail {{ __('messages.ticket') }}</h4>
                </div>
                <div class="col-lg-6">
                    <div class="text-end">
                        <h4>
                            @if($data->status == 0)
                                <span class="badge bg-secondary text-white"><i class="fas fa-play-circle"></i> Requested</span>
                            @elseif($data->status == 1)
                                <span class="badge bg-warning text-white"><i class="fas fa-spinner"></i> In-Progress</span>
                            @elseif($data->status == 2)
                                <span class="badge bg-success text-white"><i class="fas fa-check"></i> Closed</span>
                            @endif
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body body-detail-a">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>No {{ __('messages.ticket') }} :</span></div>
                    <span>{{ $data->no_ticket }}</span>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>{{ __('messages.requestor') }} :</span></div>
                    <span>{{ $data->requestorName }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>{{ __('messages.priority') }} :</span></div>
                    <span>{{ $data->priority }}</span>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>{{ __('messages.category') }} :</span></div>
                    <span>{{ $data->category }}</span>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>{{ __('messages.sub_category') }} :</span></div>
                    <span>{{ $data->sub_category }}</span>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>{{ __('messages.report_date') }} :</span></div>
                    <span>{{ $data->report_date ? \Carbon\Carbon::parse($data->report_date)->format('Y-m-d H:i') : '-' }}</span>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>{{ __('messages.created_date') }} :</span></div>
                    <span>{{ $data->created_at ? \Carbon\Carbon::parse($data->created_at)->format('Y-m-d H:i') : '-' }}</span>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>{{ __('messages.target_date') }} :</span></div>
                    <span>{{ $data->target_solved_date ? \Carbon\Carbon::parse($data->target_solved_date)->format('Y-m-d H:i') : '-' }}</span>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>{{ __('messages.actual_close_date') }} :</span></div>
                    <span>{{ $data->closed_date ? \Carbon\Carbon::parse($data->closed_date)->format('Y-m-d H:i') : '-' }}</span>
                </div>                
                <div class="col-lg-4 mb-3">
                    <div class="fw-bold"><span>{{ __('messages.duration') }} :</span></div>
                    <span>{{ $data->duration ?? '-' }}</span>
                </div>
                <div class="col-lg-12 mb-3">
                    <div class="fw-bold"><span>Attachment :</span></div>
                    <span>
                        @if($data->file_1)
                            <a href="{{ url($data->file_1) }}" target="_blank" class="btn btn-sm btn-info" type="button">
                                <span class="badge bg-light text-dark"><i class="fas fa-eye fa-sm"></i></span> {{ __('messages.show') }}
                            </a>
                        @else 
                            -
                        @endif
                    </span>
                </div>
                <div class="col-lg-12 mb-3">
                    <div class="fw-bold"><span>{{ __('messages.notes') }} :</span></div>
                    <span>{{ $data->notes }}</span>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-6">
                    <h5 class="text-bold">{{ __('messages.assign_list') }}</h5>
                </div>
                <div class="col-6">
                    <div class="text-end">
                        {{-- Close Ticket By Requestor --}}
                        @if($emailUser == $data->created_by && $data->status == 1)
                            <button type="button" class="btn btn-sm btn-success waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#closeTicket">
                                <i class="mdi mdi-close-circle label-icon"></i> {{ __('messages.close') }} {{ __('messages.ticket') }}
                            </button>
                            {{-- Modal Close --}}
                            <div class="modal fade" id="closeTicket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-top" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.close') }} {{ __('messages.ticket') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form class="formLoad" action="{{ route('ticket.close', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body text-start">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">{{ __('messages.message') }} (max 255 character)</label> <label class="text-danger">*</label>
                                                            <textarea name="message" rows="8" cols="50" class="form-control" placeholder="Input {{ __('messages.message') }}.." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">{{ __('messages.upload') }} Attachment</label>
                                                            <input type="file" name="attachment" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                                                <button type="submit" class="btn btn-warning waves-effect btn-label waves-light"><i class="mdi mdi-close-circle label-icon"></i>{{ __('messages.close') }} {{ __('messages.ticket') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{-- Accept, Preclose, Reassign --}}
                        @if(in_array($emailUser, $userAssign))
                            @if($lastAssign->accept_date)
                                @if($lastAssign->preclosed_date)
                                    @if($data->status == 1)
                                        <button type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#reAssign">
                                            <i class="mdi mdi-export label-icon"></i> Re Assign {{ __('messages.ticket') }}
                                        </button>
                                        {{-- Modal Re Assign --}}
                                        <div class="modal fade" id="reAssign" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-top" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Re Assign {{ __('messages.ticket') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form class="formLoad" action="{{ route('ticket.reAssign', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body text-start">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">{{ __('messages.department') }}</label> <label class="text-danger">*</label>
                                                                        <select class="form-control select2" name="department" required>
                                                                            <option value="" disabled selected>- {{ __('messages.select') }} -</option>
                                                                            @foreach($departments as $item)
                                                                                <option value="{{ $item }}">{{ $item }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">{{ __('messages.message') }} (max 255 character)</label> <label class="text-danger">*</label>
                                                                        <textarea name="message" rows="8" cols="50" class="form-control" placeholder="Input {{ __('messages.message') }}.." required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">{{ __('messages.upload') }} Attachment</label>
                                                                        <input type="file" name="attachment" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                                                            <button type="submit" class="btn btn-info waves-effect btn-label waves-light"><i class="mdi mdi-export label-icon"></i>Re Assign {{ __('messages.ticket') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <button type="button" class="btn btn-sm btn-warning waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#preClose">
                                        <i class="mdi mdi-close-circle label-icon"></i> Pre-close {{ __('messages.ticket') }}
                                    </button>
                                    {{-- Modal Pre Close --}}
                                    <div class="modal fade" id="preClose" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Pre-close {{ __('messages.ticket') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form class="formLoad" action="{{ route('ticket.preClose', encrypt($lastAssign->id)) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body text-start">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">{{ __('messages.message') }} (max 255 character)</label> <label class="text-danger">*</label>
                                                                    <textarea name="message" rows="8" cols="50" class="form-control" placeholder="Input {{ __('messages.message') }}.." required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">{{ __('messages.upload') }} Attachment</label>
                                                                    <input type="file" name="attachment" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                                                        <button type="submit" class="btn btn-warning waves-effect btn-label waves-light"><i class="mdi mdi-close-circle label-icon"></i>Pre-close</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <button type="button" class="btn btn-sm btn-success waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#acceptTicket">
                                    <i class="mdi mdi-check label-icon"></i> {{ __('messages.accept') }} {{ __('messages.ticket') }}
                                </button>
                                {{-- Modal Accept --}}
                                <div class="modal fade" id="acceptTicket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-top" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.accept') }} {{ __('messages.ticket') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form class="formLoad" action="{{ route('ticket.accept', encrypt($lastAssign->id)) }}" id="formadd" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        {{ __('messages.are_u_sure') }} <b>{{ __('messages.accept') }}</b> {{ __('messages.this_ticket') }}?
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                                                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="bx bx-check label-icon"></i>{{ __('messages.accept') }} {{ __('messages.ticket') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="col-12">
                    @include('ticket.assign.index')
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="card card-shadow">
        <div class="card-header p-3 header-detail-a">
            <div class="row">
                <div class="col-lg-6">
                    <h4 class="text-bold">{{ __('messages.ticket_activity') }}</h4>
                </div>
                <div class="col-lg-6">
                    {{-- Activity --}}
                    @if(in_array($emailUser, $userAssign))
                        <div class="text-end">
                            @if($lastAssign->accept_date && !$lastAssign->preclosed_date)
                                <button type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#addActivity">
                                    <i class="mdi mdi-plus label-icon"></i> {{ __('messages.add') }} {{ __('messages.ticket_activity') }}
                                </button>
                                {{-- Modal Add Activity --}}
                                <div class="modal fade" id="addActivity" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-top" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.add') }} {{ __('messages.ticket_activity') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form class="formLoad" action="{{ route('ticket.addActivity', encrypt($data->id)) }}" id="formadd" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body text-start">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">{{ __('messages.message') }} (max 255 character)</label> <label class="text-danger">*</label>
                                                                <textarea name="message" rows="8" cols="50" class="form-control" placeholder="Input {{ __('messages.message') }}.." required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">{{ __('messages.upload') }} Attachment</label>
                                                                <input type="file" name="attachment" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                                                    <button type="submit" class="btn btn-primary waves-effect btn-label waves-light"><i class="bx bx-plus label-icon"></i>{{ __('messages.add') }}</button>
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
        <div class="card-body body-detail-a">
            <div class="row">
                @include('ticket.activity.index')
            </div>
        </div>
    </div>
</div>

@endsection
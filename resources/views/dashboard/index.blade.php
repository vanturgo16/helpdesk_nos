@extends('layouts.master')
@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center mt-3">
                            <div class="col-12">
                                <div class="text-center">
                                    <h5>{{ __('messages.welcome') }}</h5>
                                    <p class="text-muted">{{ __('messages.welcome_sub') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-lg-12 text-center mt-4">
                <div class="maintenance-cog-icon text-primary pt-4">
                    <i class="mdi mdi-cog spin-right display-3"></i>
                    <i class="mdi mdi-cog spin-left display-4 cog-icon"></i>
                </div>
                <h3 class="mt-4">Site is Under Development</h3>
                <p>Please wait....</p>
            </div> --}}
        </div>


        <div class="card">
            <div class="card-header bg-light header-detail-a">
                <h5 class="mb-0">
                    {{ __('messages.ticket_summary') }}
                    @if(!in_array(auth()->user()->role, ['Super Admin', 'Admin']))
                        {{ __('messages.ticket_summary_sub') }}
                    @endif
                </h5>
            </div>
            <div class="card-body body-detail-a">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="card card-h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Requested</span>
                                        <h4 class="mb-3">
                                            <span class="counter-value" data-target="{{ $totalReq }}">0</span>
                                        </h4>
                                    </div>
                                </div>
                                <div class="text-nowrap">
                                    <span class="badge bg-success-subtle text-success">+{{ $totalReqToday }}</span>
                                    <span class="ms-1 text-muted font-size-13">{{ __('messages.today') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card card-h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <span class="text-info mb-3 lh-1 d-block text-truncate">In-Progress</span>
                                        <h4 class="mb-3">
                                            <span class="text-info counter-value" data-target="{{ $totalInProgress }}">0</span>
                                        </h4>
                                    </div>
                                </div>
                                <div class="text-nowrap">
                                    <span class="badge bg-success-subtle text-success">+{{ $totalInProgressToday }}</span>
                                    <span class="ms-1 text-muted font-size-13">{{ __('messages.today') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card card-h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <span class="text-success mb-3 lh-1 d-block text-truncate">Closed</span>
                                        <h4 class="mb-3">
                                            <span class="text-success counter-value" data-target="{{ $totalClosed }}">0</span>
                                        </h4>
                                    </div>
                                </div>
                                <div class="text-nowrap">
                                    <span class="badge bg-success-subtle text-success">+{{ $totalClosedToday }}</span>
                                    <span class="ms-1 text-muted font-size-13">{{ __('messages.today') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card card-h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <span class="fw-bold text-primary mb-3 lh-1 d-block text-truncate">Total</span>
                                        <h4 class="mb-3">
                                            <span class="text-primary counter-value" data-target="{{ $total }}">0</span>
                                        </h4>
                                    </div>
                                </div>
                                <div class="text-nowrap">
                                    <span class="badge bg-success-subtle text-success">+{{ $totalToday }}</span>
                                    <span class="ms-1 text-muted font-size-13">{{ __('messages.today') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
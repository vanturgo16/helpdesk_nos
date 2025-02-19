@extends('layouts.master')
@section('konten')
<div class="page-content">
    <div class="row custom-margin">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="page-title-left">
                    <a href="{{ route('user.index') }}" class="btn btn-light waves-effect btn-label waves-light">
                        <i class="mdi mdi-arrow-left label-icon"></i> Back To List Users
                    </a>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Manage Users</a></li>
                        <li class="breadcrumb-item active"> Edit User</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="card">
        <div class="card-header">
            <h4 class="text-bold">Edit User</h4>
        </div>
        <form class="formLoad" action="{{ route('user.update', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Name</label><label style="color: darkred">*</label>
                        <input class="form-control" type="text" name="name" value="{{ $data->name }}" placeholder="Input Name.." required>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Email</label><label style="color: darkred">*</label>
                        <input class="form-control" type="email" name="email" value="{{ $data->email }}" placeholder="Input Email.." required>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Dealer Type</label> <label class="text-danger">*</label>
                        <select class="form-control select2" name="dealer_type" required>
                            <option value="" disabled selected>- Select Dealer Type -</option>
                            @foreach($dealerTypes as $item)
                                <option value="{{ $item->name_value }}" {{ $data->dealer_type == $item->name_value ? 'selected' : '' }}>
                                    {{ $item->name_value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Dealer Name</label><label style="color: darkred">*</label>
                        <input class="form-control" type="text" name="dealer_name" value="{{ $data->dealer_name }}" placeholder="Input Dealer Name.." required>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Department</label> <label class="text-danger">*</label>
                        <select class="form-control select2" name="department" required>
                            <option value="" disabled selected>- Select Department -</option>
                            @foreach($departments as $item)
                                <option value="{{ $item->name_value }}" {{ $data->department == $item->name_value ? 'selected' : '' }}>
                                    {{ $item->name_value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Role</label> <label class="text-danger">*</label>
                        <select class="form-control select2" name="role" required>
                            <option value="" disabled selected>- Select Role -</option>
                            @foreach($roleUsers as $item)
                                <option value="{{ $item->name_value }}" {{ $data->role == $item->name_value ? 'selected' : '' }}>
                                    {{ $item->name_value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row text-end">
                    <div>
                        <a href="javascript:location.reload();" type="button" class="btn btn-secondary waves-effect btn-label waves-light">
                            <i class="mdi mdi-reload label-icon"></i>Reset
                        </a>
                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light">
                            <i class="mdi mdi-update label-icon"></i>Update
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
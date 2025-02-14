@extends('layouts.master')
@section('konten')

<div class="page-content">
    {{-- MAIN CARD --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    @if(Auth::user()->role == 'Super Admin')
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#addNew"><i class="mdi mdi-plus label-icon"></i> Add New</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="addNew" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form class="formLoad" action="{{ route('dropdown.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Category</label> <label class="text-danger">*</label>
                                                    <select class="form-control select2" name="category" required>
                                                        <option value="" disabled selected>- Select -</option>
                                                        @foreach($categories as $item)
                                                            <option value="{{ $item->category }}">{{ $item->category }}</option>
                                                        @endforeach
                                                        <option disabled>──────────</option>
                                                        <option value="NewCat">Add New Category</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3" id="newCat" style="display: none;">
                                                    <label class="form-label">New Category</label> <label class="text-danger">*</label>
                                                    <input type="text" name="addcategory" class="form-control" placeholder="Input New Category.." required>
                                                </div>
                                                <script>
                                                    $(document).ready(function () {
                                                        $('select[name="category"]').on("change", function () {
                                                            const isNewCategory = $(this).val() === "NewCat";
                                                            $("#newCat").toggle(isNewCategory);
                                                            $('input[name="addcategory"]').prop("required", isNewCategory);
                                                        });
                                                    });
                                                </script>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">Name Value</label> <label class="text-danger">*</label>
                                                    <input class="form-control" type="text" name="name_value" placeholder="Input Name Value.." required>
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">Code Format</label>
                                                    <input class="form-control" type="text" name="code_format" placeholder="Input Code Format.. (Optional)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="mdi mdi-plus label-icon"></i>Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <h4 class="text-bold">Manage Dropdown</h4>
                    </div>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered dt-responsive nowrap w-100" id="ssTable">
                <thead class="table-light">
                    <tr>
                        <th class="align-middle text-center">No</th>
                        <th class="align-middle text-center">Category</th>
                        <th class="align-middle text-center">Name Value</th>
                        <th class="align-middle text-center">Code Format</th>
                        <th class="align-middle text-center">Status</th>
                        <th class="align-middle text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#ssTable').DataTable({
            processing: true,
            serverSide: true,
            scrollY: '100vh',
            ajax: '{!! route('dropdown.index') !!}',
            columns: [{
                data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    className: 'align-middle text-center',
                },
                {
                    data: 'category',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-bold',
                },
                {
                    data: 'name_value',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        var name_value = '';
                        name_value = row.name_value.length > 35 ? row.name_value.substr(0, 35) + '...' : row.name_value;
                        name_value = '<span title="' + row.name_value + '">' + name_value + '</span>';
                        return name_value;
                    },
                },
                {
                    data: 'code_format',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        var html;
                        if (row.code_format) {
                            html = row.code_format;
                        } else {
                            html = '-';
                        }
                        return html;
                    },
                },
                {
                    data: 'is_active',
                    orderable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html;
                        if (row.is_active == 1) {
                            html = '<span class="badge bg-success text-white" title="Active"><i class="mdi mdi-check"></i></span>';
                        } else {
                            html = '<span class="badge bg-danger text-white" title="Inactive"><i class="mdi mdi-window-close"></i></span>';
                        }
                        return html;
                    },
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-middle text-center',
                },
            ],
            drawCallback: function(settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var lastCategory = null;
                var rowspan = 1;
                api.column(1, { page: 'current' }).data().each(function(category, i) {
                    if (lastCategory === category) {
                        rowspan++;
                        $(rows).eq(i).find('td:eq(1)').remove();
                    } else {
                        if (lastCategory !== null) {
                            $(rows).eq(i - rowspan).find('td:eq(1)').attr('rowspan', rowspan);
                        }
                        lastCategory = category;
                        rowspan = 1;
                    }
                });
                if (lastCategory !== null) {
                    $(rows).eq(api.column(1, { page: 'current' }).data().length - rowspan).find('td:eq(1)').attr('rowspan', rowspan);
                }
            }
        });
    });
</script>

@endsection
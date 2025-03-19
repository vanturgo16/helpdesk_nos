@extends('layouts.master')
@section('konten')
<div class="page-content">
    {{-- MAIN CARD --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    <a href="{{ route('createTicket.index') }}" type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light"><i class="mdi mdi-plus label-icon"></i> {{ __('messages.ticket_create') }}</a>
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <h4 class="text-bold mb-0">{{ __('messages.ticket_list') }}</h4>
                        <span class="badge bg-info text-white">{{ __('messages.year') }}: {{ \Carbon\Carbon::createFromFormat('Y', $year)->translatedFormat('Y') }}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-secondary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#modalFilter">
                            <i class="mdi mdi-filter label-icon"></i> Filter {{ __('messages.year') }}
                        </button>
                        {{-- Modal Filter --}}
                        <div class="modal fade" id="modalFilter" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Filter {{ __('messages.year') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form class="formLoad" action="{{ route('ticket.index') }}" method="GET" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body p-4">
                                            <div class="row">
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">{{ __('messages.year') }}</label> <label class="text-danger">*</label>
                                                    <input type="number" class="form-control" name="year" value="{{ $year }}" min="1900" max="2100" step="1" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="mdi mdi-eye label-icon"></i></i>{{ __('messages.show') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered dt-responsive w-100" id="ssTable" style="font-size: small">
                <thead class="table-light">
                    <tr>
                        <th class="align-middle text-center">#</th>
                        <th class="align-middle text-center">No. {{ __('messages.ticket') }}</th>
                        <th class="align-middle text-center">{{ __('messages.category') }}</th>
                        <th class="align-middle text-center">{{ __('messages.requestor') }}</th>
                        <th class="align-middle text-center">Target Close</th>
                        <th class="align-middle text-center">Aging</th>
                        <th class="align-middle text-center">{{ __('messages.last_assign') }}</th>
                        <th class="align-middle text-center">Status</th>
                        <th class="align-middle text-center">{{ __('messages.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(function() {
        var url = '{!! route('ticket.datas') !!}';
        var dataTable = $('#ssTable').DataTable({
            processing: true,
            serverSide: true,
            scrollY: '100vh',
            ajax: {
                url: url,
                type: 'GET',
                data: function(d) {
                    d.filterPriority = $('#filterPriority').val();
                    d.filterStatus = $('#filterStatus').val();
                    d.year = '{{ $year }}';
                }
            },
            columns: [{
                data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
                {
                    data: 'no_ticket',
                    name: 'no_ticket',
                    orderable: true,
                    searchable: true,
                    className: 'align-top fw-bold',
                    render: function(data, type, row) {
                        let badgeClass = 'bg-info';
                        if (row.priority === 'Low') {
                            badgeClass = 'bg-secondary';
                        } else if (row.priority === 'Medium') {
                            badgeClass = 'bg-warning';
                        } else if (row.priority === 'High') {
                            badgeClass = 'bg-danger';
                        }

                        return `${data}<br><span class="badge ${badgeClass}">${row.priority}</span>`;
                    },
                },
                {
                    data: 'category',
                    name: 'category',
                    orderable: true,
                    render: function(data, type, row) {
                        return '<b>' + row.category + '</b><br>' + row.sub_category;
                    },
                },
                {
                    data: 'created_by',
                    name: 'created_by',
                    searchable: true,
                    orderable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        return row.created_by + '<br><b>At.</b> ' + dayjs(row.created).format('YYYY-MM-DD HH:mm');
                    },
                },
                {
                    data: 'targetDate',
                    name: 'targetDate',
                    searchable: true,
                    orderable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        let targetDate = `<small><b>Target:</b> ${dayjs(data).format('YYYY-MM-DD HH:mm')}</small>`;
                        let actualDate = row.closedDate 
                            ? `<br><small><b>Actual:</b> ${dayjs(row.closedDate).format('YYYY-MM-DD HH:mm')}</small>` 
                            : '<br><small><b>Actual:</b> -</small>';

                        return targetDate + actualDate;
                    },
                },
                {
                    data: null,
                    name: 'aging',
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        if (!row.created) return ''; // Ensure created date exists

                        let createdDate = dayjs(row.created);
                        let endDate = row.closedDate ? dayjs(row.closedDate) : dayjs(); // Use now if closedDate is missing

                        let diffDays = endDate.diff(createdDate, 'day');
                        let diffHours = endDate.diff(createdDate, 'hour') % 24;
                        let diffMinutes = endDate.diff(createdDate, 'minute') % 60;

                        let agingText = `<strong>${diffDays}</strong>d, <strong>${diffHours}</strong>h, <strong>${diffMinutes}</strong>m`;

                        // Highlight in red if past targetDate and ticket is still open
                        if (!row.closedDate && row.targetDate && endDate.isAfter(dayjs(row.targetDate))) {
                            return `<small class="text-danger">${agingText}</small>`;
                        }

                        return `<small>${agingText}</small>`;
                    },
                },
                {
                    data: 'lastAssign',
                    name: 'lastAssign',
                    orderable: false,
                    searchable: false,
                    className: 'align-top',
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        let html;
                        if (data === 0) {
                            html = `<span class="badge bg-secondary text-white"><i class="fas fa-play-circle"></i> Requested</span>`;
                        } else if (data === 1) {
                            html = `<span class="badge bg-warning text-white"><i class="fas fa-spinner"></i> In-Progress</span>`;
                        } else {
                            html = `<span class="badge bg-success text-white"><i class="fas fa-check"></i> Done</span>`;
                        }
                        return html;
                    },
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
                {
                    data: 'sub_category',
                    name: 'sub_category',
                    searchable: true,
                    visible: false
                },
                {
                    data: 'created',
                    name: 'created',
                    searchable: true,
                    visible: false
                },
            ],
        });
        $('#vertical-menu-btn').on('click', function() {
            setTimeout(function() {
                dataTable.columns.adjust().draw();
                window.dispatchEvent(new Event('resize'));
            }, 10);
        });
    });
</script>

<script>
    $(function() {
        // Hide Length Datatable
        $('.dataTables_wrapper .dataTables_length').hide();

        // Length
        var lengthDropdown = `
            <label>
                <select id="lengthDT">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </label>
        `;
        $('.dataTables_length').before(lengthDropdown);
        $('#lengthDT').select2({ minimumResultsForSearch: Infinity, width: '60px' });
        $('#lengthDT').on('change', function() {
            var newLength = $(this).val();
            var table = $("#ssTable").DataTable();
            table.page.len(newLength).draw();
        });

        // Filter Type
        let priorities = @json($priorities);
        var filterPriority = `
            <label>
                <select id="filterPriority">
                    <option value="">-- All Priority --</option>
                    ${priorities.map(priority => `<option value="${priority.priority}">${priority.priority}</option>`).join('')}
                </select>
            </label>
        `;
        $('.dataTables_length').before(filterPriority);
        $('#filterPriority').select2({width: '150px' });
        $('#filterPriority').on('change', function() { $("#ssTable").DataTable().ajax.reload(); });

        // Filter Status
        var filterStatus = `
            <label>
                <select id="filterStatus">
                    <option value="">-- All Status --</option>
                    <option value="0">Requested</option>
                    <option value="1">In-Progress</option>
                    <option value="2">Done</option>
                </select>
            </label>
        `;
        $('.dataTables_length').before(filterStatus);
        $('#filterStatus').select2({width: '200px' });
        $('#filterStatus').on('change', function() { $("#ssTable").DataTable().ajax.reload(); });
    });
</script>

@endsection
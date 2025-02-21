@extends('layouts.master')
@section('konten')
<div class="page-content">
    {{-- MAIN CARD --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    @if(in_array(Auth::user()->role, ['Super Admin', 'Admin']))
                        <a href="{{ route('createTicket.index') }}" type="button" class="btn btn-primary waves-effect btn-label waves-light"><i class="mdi mdi-plus label-icon"></i> Add New Ticket</a>
                    @endif
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <h4 class="text-bold">List Ticket</h4>
                    </div>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered dt-responsive w-100" id="ssTable">
                <thead class="table-light">
                    <tr>
                        <th class="align-middle text-center">#</th>
                        <th class="align-middle text-center">No. Ticket</th>
                        <th class="align-middle text-center">Priority</th>
                        <th class="align-middle text-center">Category</th>
                        <th class="align-middle text-center">Requestor</th>
                        <th class="align-middle text-center">Target Closed</th>
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
                },
                {
                    data: 'priority',
                    name: 'priority',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        let badgeClass = 'bg-info';
                        if (data === 'Low') {
                            badgeClass = 'bg-secondary';
                        } else if (data === 'Medium') {
                            badgeClass = 'bg-warning';
                        } else if (data === 'High') {
                            badgeClass = 'bg-danger';
                        }
                        return `<span class="badge ${badgeClass}">${data}</span>`;
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
                        return row.created_by + '<br><b>At.</b>' + row.created;
                    },
                },
                {
                    data: 'target_solved_date',
                    name: 'target_solved_date',
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        if (!data) return '-';
                        let date = new Date(data);
                        let day = String(date.getDate()).padStart(2, '0');
                        let month = String(date.getMonth() + 1).padStart(2, '0');
                        let year = date.getFullYear();
                        return `${day}-${month}-${year}`;
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        let html;
                        if (data === 1) {
                            html = `<span class="badge bg-success text-white">Done</span>`;
                        } else {
                            html = `<span class="badge bg-warning">Requested</span>`;
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
                    <option value="1">Done</option>
                </select>
            </label>
        `;
        $('.dataTables_length').before(filterStatus);
        $('#filterStatus').select2({width: '200px' });
        $('#filterStatus').on('change', function() { $("#ssTable").DataTable().ajax.reload(); });
    });
</script>

@endsection
<table class="table table-bordered dt-responsive w-100" id="ssTableAssign">
    <thead class="table-light">
        <tr>
            <th class="align-middle text-center">Assign By</th>
            <th class="align-middle text-center">Assign To</th>
            <th class="align-middle text-center">Message</th>
            <th class="align-middle text-center">Date Detail</th>
            <th class="align-middle text-center">Status</th>
        </tr>
    </thead>
</table>

<script>
    $(function() {
        var url = '{!! route('ticket.assign.datas', encrypt($data->id)) !!}';
        var dataTable = $('#ssTableAssign').DataTable({
            processing: true,
            serverSide: true,
            info: false,
            paging: false,
            searching: false,
            lengthChange: false,
            ajax: {
                url: url,
                type: 'GET',
            },
            columns: [
                {
                    data: 'assignBy',
                    name: 'assignBy',
                    searchable: true,
                    orderable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        return row.assignBy + '<b> - ' + row.department + '</b>';
                    },
                },
                {
                    data: 'assign_to_dept',
                    name: 'assign_to_dept',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'preclosed_message',
                    name: 'preclosed_message',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'dateDetail',
                    name: 'dateDetail',
                    orderable: true,
                    searchable: true,
                    className: 'align-top p-0',
                },
                {
                    data: 'assign_status',
                    name: 'assign_status',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        let html;
                        if (data === 1) {
                            html = `<span class="badge bg-success text-white">Close</span>`;
                        } else {
                            html = `<span class="badge bg-info">Open</span>`;
                        }
                        return html;
                    },
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
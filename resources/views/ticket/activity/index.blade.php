<table class="table table-bordered dt-responsive w-100" id="ssTable">
    <thead class="table-light">
        <tr>
            <th class="align-middle text-center">#</th>
            <th class="align-middle text-center">Status</th>
            <th class="align-middle text-center">Message</th>
            <th class="align-middle text-center">Attachment</th>
            <th class="align-middle text-center">Action By</th>
            <th class="align-middle text-center">Action</th>
        </tr>
    </thead>
</table>

<script>
    $(function() {
        var url = '{!! route('ticket.log.datas', encrypt($data->id)) !!}';
        var dataTable = $('#ssTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
                type: 'GET',
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
                    data: 'description',
                    name: 'description',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'message',
                    name: 'message',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'attachment',
                    name: 'attachment',
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
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
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
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
<table class="table table-bordered dt-responsive w-100" id="ssTable">
    <thead class="table-light">
        <tr>
            <th class="align-middle text-center">Action By</th>
            <th class="align-middle text-center">Message</th>
            <th class="align-middle text-center">Attachment</th>
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
            columns: [
                {
                    data: 'created',
                    name: 'created',
                    searchable: true,
                    orderable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        return row.created_by + '<br><b>At.</b>' + row.created;
                    },
                },
                {
                    data: 'message',
                    name: 'message',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        return '<b>' + row.description + '</b><br>' + row.message;
                    },
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
                    visible: false
                },
                {
                    data: 'description',
                    name: 'description',
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
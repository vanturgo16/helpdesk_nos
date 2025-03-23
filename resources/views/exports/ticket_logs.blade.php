<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No. Ticket</th>
            <th>Assign By</th>
            <th>Assign Date</th>
            <th>Assign To Dept</th>
            <th>Accept Date</th>
            <th>Pre Close Date</th>
            <th>Pre Close Message</th>
            <th>Created Assign</th>
            <th>Updated Assign</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $data)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $data->no_ticket ?? '-' }}</td>
                <td>{{ $data->assign_by ?? '-' }}</td>
                <td>{{ $data->assign_date ?? '-' }}</td>
                <td>{{ $data->assign_to_dept ?? '-' }}</td>
                <td>{{ $data->accept_date ?? '-' }}</td>
                <td>{{ $data->preclosed_date ?? '-' }}</td>
                <td>{{ $data->preclosed_message ?? '-' }}</td>
                <td>{{ $data->createdAssignTicket ?? '-' }}</td>
                <td>{{ $data->updatedAssignTicket ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

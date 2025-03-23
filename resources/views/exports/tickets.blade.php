<table>
    <thead>
        <!-- Export Details -->
        <tr>
            <th colspan="10"><strong>Export Details</strong></th>
        </tr>
        <tr>
            <td colspan="2">Priority</td>
            <td colspan="8">: {{ $priority }}</td>
        </tr>
        <tr>
            <td colspan="2">Status PR</td>
            <td colspan="8">: {{ $status }}</td>
        </tr>
        <tr>
            <td colspan="2">Created Date</td>
            <td colspan="8">: {{ $dateFrom }} - {{ $dateTo }}</td>
        </tr>
        <tr>
            <td colspan="2">Exported By</td>
            <td colspan="8">: {{ $exportedBy }} at {{ $exportedAt }}</td>
        </tr>
        <tr><td colspan="10"></td></tr>

        <!-- Column Headers -->
        <tr>
            <th>No</th>
            <th>No. Ticket</th>
            <th>Priority</th>
            <th>Category</th>
            <th>Sub Category</th>
            <th>Notes</th>
            <th>Report Date</th>
            <th>Created By</th>
            <th>Attachment 1</th>
            <th>Target Solved Date</th>
            <th>Closed Date</th>
            <th>Closed Note</th>
            <th>Duration</th>
            <th>Status</th>
            <th>Created Ticket</th>
            <th>Updated Ticket</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datas as $index => $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                @php
                    $logRow = $logRowMap[$row->no_ticket] ?? null;
                @endphp
                <td>
                    @if($logRow)
                        <a href="#'Ticket Assign Log'!B{{ $logRow }}">
                            {{ $row->no_ticket }}
                        </a>
                    @else
                        {{ $row->no_ticket }}
                    @endif
                </td>
                <td>{{ $row->priority ?? '-' }}</td>
                <td>{{ $row->category ?? '-' }}</td>
                <td>{{ $row->sub_category ?? '-' }}</td>
                <td>{{ $row->notes ?? '-' }}</td>
                <td>{{ $row->report_date ?? '-' }}</td>
                <td>{{ $row->created_by ?? '-' }}</td>
                <td>
                    @if($row->file_1)
                        <a href="{{ url($row->file_1) }}">View File</a>
                    @else 
                        -
                    @endif
                </td>
                <td>{{ $row->target_solved_date ?? '-' }}</td>
                <td>{{ $row->closed_date ?? '-' }}</td>
                <td>{{ $row->closed_notes ?? '-' }}</td>
                <td>{{ $row->duration ?? '-' }}</td>
                <td>
                    @switch($row->status)
                        @case(0)
                            Requested
                            @break
                        @case(1)
                            In-Progress
                            @break
                        @case(2)
                            Closed
                            @break
                        @default
                            -
                    @endswitch
                </td>                    
                <td>{{ $row->created_at ?? '-' }}</td>
                <td>{{ $row->updated_at ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

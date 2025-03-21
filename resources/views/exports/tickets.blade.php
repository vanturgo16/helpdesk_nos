@php
    $rowCounts = [];
    $rowIndex = 1; // Start numbering from 1
    foreach ($datas as $data) {
        $rowCounts[$data->id_ticket] = isset($rowCounts[$data->id_ticket]) ? $rowCounts[$data->id_ticket] + 1 : 1;
    }
    $printedIds = [];
@endphp

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
        @foreach($datas as $data)
            <tr>
                @if (!isset($printedIds[$data->id_ticket]))
                    @php
                        $rowspan = $rowCounts[$data->id_ticket] ?? 1;
                        $printedIds[$data->id_ticket] = true;
                    @endphp
                    <td rowspan="{{ $rowspan }}">{{ $rowIndex++ }}</td>
                    <td rowspan="{{ $rowspan }}">{{ $data->no_ticket ?? '-' }}</td>
                    <td rowspan="{{ $rowspan }}">{{ $data->priority ?? '-' }}</td>
                    <td rowspan="{{ $rowspan }}">{{ $data->category ?? '-' }}</td>
                    <td rowspan="{{ $rowspan }}">{{ $data->sub_category ?? '-' }}</td>
                    <td rowspan="{{ $rowspan }}">{{ $data->notes ?? '-' }}</td>
                    <td rowspan="{{ $rowspan }}">{{ $data->report_date ?? '-' }}</td>
                    <td rowspan="{{ $rowspan }}">{{ $data->created_by ?? '-' }}</td>
                    <td rowspan="{{ $rowspan }}">
                        @if($data->file_1)
                            <a href="{{ url($data->file_1) }}">View File</a>
                        @else 
                            -
                        @endif
                    </td>
                    <td rowspan="{{ $rowspan }}">{{ $data->target_solved_date ?? '-' }}</td>
                    <td rowspan="{{ $rowspan }}">{{ $data->closed_date ?? '-' }}</td>
                    <td rowspan="{{ $rowspan }}">{{ $data->closed_notes ?? '-' }}</td>
                    <td rowspan="{{ $rowspan }}">{{ $data->duration ?? '-' }}</td>
                    <td rowspan="{{ $rowspan }}">
                        @switch($data->status)
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
                    <td rowspan="{{ $rowspan }}">{{ $data->createdTicket ?? '-' }}</td>
                    <td rowspan="{{ $rowspan }}">{{ $data->updatedTicket ?? '-' }}</td>
                @endif
                <!-- Columns without merging -->
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

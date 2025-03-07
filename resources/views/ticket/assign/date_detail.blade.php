<table class="mb-0" style="border-collapse: collapse;">
    <tbody>
        <tr>
            <th class="align-top px-2 py-0" style="font-weight: bold; border: none;">
                <small>Assign Date</small>
            </th>
            <td class="align-top px-2 py-0" style="border: none;">
                <small>: {{ $data->assign_date ? \Carbon\Carbon::parse($data->assign_date)->format('Y-m-d H:i') : '-' }}</small>
            </td>
        </tr>
        <tr>
            <th class="align-top px-2 py-0" style="font-weight: bold; border: none;">
                <small>Accept Date</small>
            </th>
            <td class="align-top px-2 py-0" style="border: none;">
                <small>: {{ $data->accept_date ? \Carbon\Carbon::parse($data->accept_date)->format('Y-m-d H:i') : '-' }}</small>
            </td>
        </tr>
        <tr>
            <th class="align-top px-2 py-0" style="font-weight: bold; border: none;">
                <small>Preclose Date</small>
            </th>
            <td class="align-top px-2 py-0" style="border: none;">
                <small>: {{ $data->preclosed_date ? \Carbon\Carbon::parse($data->preclosed_date)->format('Y-m-d H:i') : '-' }}</small>
            </td>
        </tr>
    </tbody>
</table>

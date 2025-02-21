<table class="mb-0" style="border-collapse: collapse;">
    <tbody>
        <tr>
            <th class="align-top px-2 py-0" style="font-weight: bold; border: none;">
                <small>Assign Date</small>
            </th>
            <td class="align-top px-2 py-0" style="border: none;">
                <small>: {{ $data->assign_date }}</small>
            </td>
        </tr>
        <tr>
            <th class="align-top px-2 py-0" style="font-weight: bold; border: none;">
                <small>Accept Date</small>
            </th>
            <td class="align-top px-2 py-0" style="border: none;">
                <small>: {{ $data->accept_date ?? '-' }}</small>
            </td>
        </tr>
        <tr>
            <th class="align-top px-2 py-0" style="font-weight: bold; border: none;">
                <small>Preclose Date</small>
            </th>
            <td class="align-top px-2 py-0" style="border: none;">
                <small>: {{ $data->preclosed_date ?? '-' }}</small>
            </td>
        </tr>
    </tbody>
</table>

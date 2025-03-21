<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket Re Assign Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td {
            padding: 5px;
            vertical-align: top;
        }
        .small-text {
            font-size: 12px;
            color: #555;
        }
    </style>
</head>
<body>
    <p>Dear Department {{ $assignToDept }},<br>
    
    <p>We would like to inform you that a ticket has been submitted and assigned to your department (Re Assign).<br>
    <span class="small-text">Kami ingin memberitahukan bahwa tiket telah diajukan dan ditugaskan ke departemen Anda (Re Assign).</span></p>
    
    <p>Below are the details of the ticket:<br>
    <span class="small-text">Berikut adalah detail tiket:</span></p>
    
    <table>
        <tr>
            <td><strong>Ticket No.</strong><br><span class="small-text">No. Tiket</span></td>
            <td>:</td>
            <td>{{ $dataTicket->no_ticket }}</td>
        </tr>
        <tr>
            <td><strong>Priority</strong><br><span class="small-text">Prioritas</span></td>
            <td>:</td>
            <td>{{ $dataTicket->priority }}</td>
        </tr>
        <tr>
            <td><strong>Category</strong><br><span class="small-text">Kategori</span></td>
            <td>:</td>
            <td>{{ $dataTicket->category }}</td>
        </tr>
        <tr>
            <td><strong>Sub Category</strong><br><span class="small-text">Sub Kategori</span></td>
            <td>:</td>
            <td>{{ $dataTicket->sub_category }}</td>
        </tr>
        <tr>
            <td><strong>Re Assign Notes</strong><br><span class="small-text">Catatan Re Assign</span></td>
            <td>:</td>
            <td>{{ $messageContent }}</td>
        </tr>
        <tr>
            <td><strong>Target Solved Date</strong><br><span class="small-text">Tanggal Target Penyelesaian</span></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($dataTicket->target_solved_date)->format('Y-m-d H:i') }}</td>
        </tr>
    </table>
    
    <p>Best regards,<br>
    Hormat kami,</p>
    
    <p>{{ $assignBy }}</p>
</body>
</html>

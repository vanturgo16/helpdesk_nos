<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket Close Notification</title>
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
    <p>Dear Department 
        {{ implode(', ', $assignToDept) }},
    </p>
    <br>    
    
    <p>We would like to inform you that this ticket has been Closed.<br>
    <span class="small-text">Kami ingin memberitahukan bahwa tiket ini telah di tutup.</span></p>
    
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
            <td><strong>Notes</strong><br><span class="small-text">Catatan</span></td>
            <td>:</td>
            <td>{{ $dataTicket->notes }}</td>
        </tr>
        <tr>
            <td><strong>Created Ticket Date</strong><br><span class="small-text">Tanggal Pembuatan Tiket</span></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($dataTicket->created_at)->format('Y-m-d H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Report Date</strong><br><span class="small-text">Tanggal Laporan</span></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($dataTicket->report_date)->format('Y-m-d H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Target Solved Date</strong><br><span class="small-text">Tanggal Target Penyelesaian</span></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($dataTicket->target_solved_date)->format('Y-m-d H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Closed Date</strong><br><span class="small-text">Tanggal Close</span></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($dataTicket->closed_date)->format('Y-m-d H:i') }}</td>
        </tr>        
        <tr>
            <td><strong>Duration</strong><br><span class="small-text">Durasi</span></td>
            <td>:</td>
            <td>{{ $dataTicket->duration }}</td>
        </tr>
        <tr>
            <td><strong>Closed Message</strong><br><span class="small-text">Pesan Close</span></td>
            <td>:</td>
            <td>{{ $dataTicket->closed_notes }}</td>
        </tr>
    </table>
    
    <p>Best regards,<br>
    Hormat kami,</p>
    
    <p>{{ $closeBy }}</p>
</body>
</html>

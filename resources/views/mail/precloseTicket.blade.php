<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket PreClose Notification</title>
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
    <p>Dear {{ $dataTicket->requestorName }},<br>
    
    <p>We would like to inform you that the ticket you requested has been processed (Pre-Close).<br>
    <span class="small-text">Kami ingin memberitahukan bahwa tiket yang Anda ajukan telah diproses (Pre-Close).</span></p>
    
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
            <td><strong>Target Solved Date</strong><br><span class="small-text">Tanggal Target Penyelesaian</span></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($dataTicket->target_solved_date)->format('Y-m-d H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Pre Close Date</strong><br><span class="small-text">Tanggal Pre Close</span></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($dataAssign->preclosed_date)->format('Y-m-d H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Pre Close Message</strong><br><span class="small-text">Pesan Pre Close</span></td>
            <td>:</td>
            <td>{{ $dataAssign->preclosed_message }}</td>
        </tr>
    </table>
    
    <p>Please check it. If it is correct, please take the action to close the ticket.<br>
    <span class="small-text">Mohon untuk mengeceknya, apabila sudah sesuai mohon dilakukan aksi close ticket.</span></p><br>
    
    <p>Best regards,<br>
    Hormat kami,</p>
    
    <p>{{ $precloseBy }}</p>
</body>
</html>

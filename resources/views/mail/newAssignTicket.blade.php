<!DOCTYPE html>
<html>
<body>
    <span>
        Dear Department {{ $assignToDept }},
        <br> We would like to inform you that we have submitted a ticket assigned to your department.
        <br> Here are the details of the ticket:
        <br>
        <br>

        <table cellspacing="0" cellpadding="0">
            <tr>
                <td class="vertical-align: top;"><span><b>No. Ticket</b></span></td>
                <td class="vertical-align: top;"><span>&nbsp;&nbsp;:&nbsp;&nbsp;</span></td>
                <td class="vertical-align: top;">
                    <span>
                        {{ $dataTicket->no_ticket }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="vertical-align: top;"><span><b>Priority</b></span></td>
                <td class="vertical-align: top;"><span>&nbsp;&nbsp;:&nbsp;&nbsp;</span></td>
                <td class="vertical-align: top;">
                    <span>
                        {{ $dataTicket->priority }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="vertical-align: top;"><span><b>Category</b></span></td>
                <td class="vertical-align: top;"><span>&nbsp;&nbsp;:&nbsp;&nbsp;</span></td>
                <td class="vertical-align: top;">
                    <span>
                        {{ $dataTicket->category }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="vertical-align: top;"><span><b>Sub Category</b></span></td>
                <td class="vertical-align: top;"><span>&nbsp;&nbsp;:&nbsp;&nbsp;</span></td>
                <td class="vertical-align: top;">
                    <span>
                        {{ $dataTicket->sub_category }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="vertical-align: top;"><span><b>Notes</b></span></td>
                <td class="vertical-align: top;"><span>&nbsp;&nbsp;:&nbsp;&nbsp;</span></td>
                <td class="vertical-align: top;">
                    <span>
                        {{ $dataTicket->notes }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="vertical-align: top;"><span><b>Target Solved Date</b></span></td>
                <td class="vertical-align: top;"><span>&nbsp;&nbsp;:&nbsp;&nbsp;</span></td>
                <td class="vertical-align: top;">
                    <span>
                        {{ $dataTicket->target_solved_date }}
                    </span>
                </td>
            </tr>
        </table>

        <br>
        <br>
        <br> {{ $requester }} <br>
        <br> [Requestor] <br>

    </span>
</body>
</html>
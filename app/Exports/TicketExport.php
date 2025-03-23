<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TicketExport implements WithMultipleSheets
{
    protected $tickets;
    protected $logs;
    protected $request;

    public function __construct($tickets, $logs, $request)
    {
        $this->tickets = $tickets;
        $this->logs = $logs;
        $this->request = $request;
    }

    public function sheets(): array
    {
        return [
            new TicketSheetExport($this->tickets, $this->logs, $this->request),
            new TicketLogSheetExport($this->logs),
        ];
    }
}

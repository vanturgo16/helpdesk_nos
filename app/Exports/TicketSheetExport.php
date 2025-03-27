<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class TicketSheetExport implements FromView, WithStyles, ShouldAutoSize, WithTitle
{
    protected $datas;
    protected $logs;
    protected $priority;
    protected $status;
    protected $dateFrom;
    protected $dateTo;
    protected $exportedBy;
    protected $exportedAt;

    public function __construct($datas, $logs, $request)
    {
        $this->datas = $datas;
        $this->logs  = $logs ;
        $rowMap = [];
        $currentRow = 2; // start from 2 because header is at row 1
        foreach ($logs as $log) {
            $ticketNo = $log->no_ticket;
            if (!isset($rowMap[$ticketNo])) {
                $rowMap[$ticketNo] = $currentRow;
            }
            $currentRow++;
        }
        $this->logRowMap = $rowMap;
        $this->priority = $request->priority ?? 'Semua Prioritas';
        $this->status = $request->status ?? 'Semua Status';
        $this->dateFrom = $request->dateFrom ?? '-';
        $this->dateTo = $request->dateTo ?? '-';
        $this->exportedBy = auth()->user()->email;
        $this->exportedAt = now()->format('d-m-Y H:i:s');
    }

    public function view(): View
    {
        return view('exports.tickets', [
            'datas' => $this->datas,
            'logRowMap' => $this->logRowMap,
            'priority' => $this->priority,
            'status' => $this->status,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'exportedBy' => $this->exportedBy,
            'exportedAt' => $this->exportedAt,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = $sheet->getHighestColumn();
        $totalRows = $sheet->getHighestRow();

        $sheet->getStyle("A7:{$lastColumn}7")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D3D3D3'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $sheet->getStyle("A7:{$lastColumn}{$totalRows}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $sheet->getStyle("A7:{$lastColumn}{$totalRows}")->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    }
    public function title(): string
    {
        return 'Ticket List';
    }
}


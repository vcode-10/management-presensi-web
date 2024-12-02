<?php

namespace App\Exports;

use App\Models\Attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class AttendanceExport implements FromCollection, WithHeadings, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct(Carbon $startDate, Carbon $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Attendance::join('users', 'attendances.user_id', '=', 'users.id')
            ->whereBetween('attendances.hour_came', [$this->startDate, $this->endDate])
            ->get(['users.nip', 'users.name', 'attendances.hour_came', 'attendances.home_time', 'attendances.status']);
    }

    public function title(): string
    {
        return 'Absen Bulan ' . $this->startDate->format('F Y');
    }

    public function headings(): array
    {
        return [
            'Teacher Name',
            'Time In',
            'Time Out',
            'Status',
        ];
    }
}
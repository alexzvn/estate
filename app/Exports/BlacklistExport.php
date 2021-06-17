<?php

namespace App\Exports;

use App\Models\Blacklist;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class BlacklistExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    protected $blacklists;

    public function __construct(Collection $blacklists) {
        $this->blacklists = $blacklists;
    }

    public function headings(): array {
        return [
            'Số điện thoại',
            'Tên',
            'Danh mục',
            'Thành phố',
            'Lượt xuất',
            'Tạo lúc'
        ];
    }

    public function map($row): array {
        return [
            $row->phone,
            $row->name,
            $row->category,
            $row->province->name ?? '',
            $row->export_count,
            $row->created_at->format('d/m/Y')
        ];
    }

    public function increaseCount()
    {
        $this->blacklists->each(function (Blacklist $blacklist) {
            $blacklist->export_count++;

            $blacklist->save();
        });
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->blacklists;
    }
}

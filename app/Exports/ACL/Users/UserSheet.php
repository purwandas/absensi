<?php

namespace App\Exports\ACL\Users;

use App\Components\Filters\ACL\UserFilter;
use App\Models\ACL\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserSheet implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize, WithStyles, WithStrictNullComparison
{
    protected $params, $title;

    public function __construct($params = [], $title = 'Data')
    {
        $this->params = $params;
        $this->title = $title;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $filters = new UserFilter(new Request($this->params));

        $data = User::filter($filters)
                    ->join('roles','users.role_id','roles.id')
                    ->select('users.name','users.email','roles.name as role')
                    ->get();

        return $data;
    }
    
    public function headings(): array
    {
        $headings = ['NAME','EMAIL','ROLE'];

        return $headings;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true,'color' => ['rgb' => 'ffffff']], 
                'fill' => ['fillType' => Fill::FILL_SOLID,'startColor' => ['rgb' => '0a8f76']]
            ],
        ];
    }

}

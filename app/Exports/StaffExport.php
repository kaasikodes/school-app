<?php

namespace App\Exports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StaffExport implements  WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
      // TO do
      // departments inclusion in excel sheet
        return [

            'first name',
            'middle name',
            'last name',
            'staff no',
            'email',

        ];
    }


}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromArray;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;

class InvitesExport implements   WithProperties, WithHeadings,  WithEvents,  WithStrictNullComparison
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function properties(): array
    {
        return [
            'creator'        => 'Caasi Media',
            'lastModifiedBy' => 'Caasi Media',
            'title'          => 'Invitations Template',
            'description'    => 'Template to bulk upload Invitations',
            'subject'        => 'Invitations',
            'keywords'       => 'Invitations,export,spreadsheet',
            'category'       => 'Invitations',
            'manager'        => 'Kaasi Kodes',
            'company'        => 'Caasi Media',
        ];
    }
    public function headings(): array
    {
        return [

            'email',
            'user type',

        ];
    }

    public function registerEvents(): array
    {
        
        return [
            // handle by a closure.
            AfterSheet::class => function(AfterSheet $event) {

                // get layout counts (add 1 to rows for heading row)
                $row_count = 200;
                $column_count = 11;

                // set dropdown column
                $user_type_column = 'B';
                $userTypes = ['custodian', 'staff', 'admin', 'student'];
               
                // Custodian gender
                $genderValidation = $event->sheet->getCell("{$user_type_column}2")->getDataValidation();
                $genderValidation->setType(DataValidation::TYPE_LIST );
                $genderValidation->setErrorStyle(DataValidation::STYLE_INFORMATION );
                $genderValidation->setAllowBlank(false);
                $genderValidation->setShowInputMessage(true);
                $genderValidation->setShowErrorMessage(true);
                $genderValidation->setShowDropDown(true);
                $genderValidation->setErrorTitle('Input error');
                $genderValidation->setError('Value is not in list.');
                $genderValidation->setPromptTitle('Pick from list');
                $genderValidation->setPrompt('Please pick a value from the drop-down list.');
                $genderValidation->setFormula1(sprintf('"%s"',implode(',',$userTypes)));

                // clone validation to remaining rows
                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$user_type_column}{$i}")->setDataValidation(clone $genderValidation);
                }



                // =============================

                // set columns to autosize
                for ($i = 1; $i <= $column_count; $i++) {
                    $column = Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }


}

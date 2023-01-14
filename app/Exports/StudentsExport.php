<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Level;
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

class StudentsExport implements   WithProperties, WithHeadings,  WithEvents,  WithStrictNullComparison
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $schoolId;

    public function __construct(int $schoolId)
    {
        $this->schoolId = $schoolId;
    }


    public function properties(): array
    {
        return [
            'creator'        => 'Caasi Media',
            'lastModifiedBy' => 'Caasi Media',
            'title'          => 'Students Template',
            'description'    => 'Template to bulk upload students',
            'subject'        => 'Students',
            'keywords'       => 'students,export,spreadsheet',
            'category'       => 'Students',
            'manager'        => 'Kaasi Kodes',
            'company'        => 'Caasi Media',
        ];
    }

    public function headings(): array
    {
      // TO do
      // departments inclusion in excel sheet
        return [

            'student first name',
            'student middle name',
            'student last name',
            'student gender',
            'student ID',
            'student email',
            'student phone',
            'student current class',
            'custodian name',
            'custodian phone',
            'custodian occupation',
            'custodian gender',





        ];
    }

    // public function collection()
    // {
    //     // store the results for later use
    //     $this->results = Level::all();
    //
    //     return $this->results;
    // }

    public function registerEvents(): array
    {
        
        return [
            // handle by a closure.
            AfterSheet::class => function(AfterSheet $event) {

                // get layout counts (add 1 to rows for heading row)
                $row_count = 200;
                $column_count = 11;

                // set dropdown column
                $current_class_column = 'H';
                $gender_column = ['D','L'];


                // set dropdown options
                $levels = Level::where('school_id',$this->schoolId)->get()->pluck('name')->toArray();
                $genders = ['male','female'];

                // set CURRENT CLASS dropdown list for first data row
                $currentClassValidation = $event->sheet->getCell("{$current_class_column}2")->getDataValidation();
                $currentClassValidation->setType(DataValidation::TYPE_LIST );
                $currentClassValidation->setErrorStyle(DataValidation::STYLE_INFORMATION );
                $currentClassValidation->setAllowBlank(false);
                $currentClassValidation->setShowInputMessage(true);
                $currentClassValidation->setShowErrorMessage(true);
                $currentClassValidation->setShowDropDown(true);
                $currentClassValidation->setErrorTitle('Input error');
                $currentClassValidation->setError('Value is not in list.');
                $currentClassValidation->setPromptTitle('Pick from list');
                $currentClassValidation->setPrompt('Please pick a value from the drop-down list.');
                $currentClassValidation->setFormula1(sprintf('"%s"',implode(',',$levels)));

                // clone validation to remaining rows
                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$current_class_column}{$i}")->setDataValidation(clone $currentClassValidation);
                }


                // set GENDER dropdown list for first data row
                // Student gender
                $genderValidation = $event->sheet->getCell("{$gender_column[0]}2")->getDataValidation();
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
                $genderValidation->setFormula1(sprintf('"%s"',implode(',',$genders)));

                // clone validation to remaining rows
                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$gender_column[0]}{$i}")->setDataValidation(clone $genderValidation);
                }

                // Custodian gender
                $genderValidation = $event->sheet->getCell("{$gender_column[1]}2")->getDataValidation();
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
                $genderValidation->setFormula1(sprintf('"%s"',implode(',',$genders)));

                // clone validation to remaining rows
                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$gender_column[1]}{$i}")->setDataValidation(clone $genderValidation);
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

    // public function columnFormats(): array
    // {
    //     return [
    //         'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
    //         'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
    //     ];
    // }

    // public function array(): array
    // {
    //     $result = [];
    //
    //     for ($i=0; $i < 500 ; $i++) {
    //       array_push($result, [1,3,4,5,5,6,]);
    //     }
    //
    //     return $result;
    // }




}

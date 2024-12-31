<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;

class UserImport implements SkipsUnknownSheets, WithStartRow, WithHeadingRow, SkipsEmptyRows, WithCalculatedFormulas
{

    public function headingRow(): int
    {
        return 4;
    }

    public function startRow(): int
    {
        return 6;
    }
    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }
    use WithConditionalSheets;
    public function conditionalSheets(): array
    {
        return [
            0 => $this
        ];
    }
    
    public function rules(): array
    {
        return [
            '0' => ['required','string'],
            '1' => ['required','string'],
            '2' => ['required','string'],
            '3' => ['required','string'],
            '4' => ['required','string'],
            '5' => ['required','string']
        ];
    }
}
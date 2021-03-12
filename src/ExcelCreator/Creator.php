<?php


namespace ExcelCreator;


use Exchanger\IExcelCreator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Creator implements IExcelCreator
{
    private static function prepareData(array $data): array
    {
        $items = [];

        foreach ($data['items'] as $item) {
            $items[] = [
                $item['item']['id'],
                $item['item']['barcode'],
                $item['item']['name'],
                $item['amount'],
                $item['amount'] * $item['price'],
            ];
        }

        return $items;
    }

    /**
     * @param string $xlsxFilename
     * @param array $data
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public static function Create(string $xlsxFilename, array $data): Xlsx
    {
        $spreadsheet = new Spreadsheet();
        $rowNum = 1;

        $columns = [
            [
                'name' => 'ID',
                'dataType' => NumberFormat::FORMAT_NUMBER,
            ],
            [
                'name' => 'ШК',
                'dataType' => NumberFormat::FORMAT_TEXT,
            ],
            [
                'name' => 'Название',
                'dataType' => NumberFormat::FORMAT_TEXT,
            ],
            [
                'name' => 'Кол-во',
                'dataType' => NumberFormat::FORMAT_NUMBER,
            ],
            [
                'name' => 'Сумма',
                'dataType' => NumberFormat::FORMAT_NUMBER,
            ],
        ];

        $activeSheet = $spreadsheet
            ->setActiveSheetIndex(0);

        foreach ($columns as $columnNum => $column) {
            $activeSheet->getColumnDimensionByColumn($columnNum + 1)->setAutoSize(true);
            $cell = $activeSheet->getCellByColumnAndRow($columnNum + 1, $rowNum);
            $cell->getStyle()
                ->applyFromArray([
                    'font' => [
                        'name' => 'Calibri',
                        'size' => 11,
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ]
                ]);
            $cell->setValue($column['name']);
        }

        $activeSheet->setAutoFilterByColumnAndRow(1, $rowNum, $columnNum + 1, $rowNum);

        $rowNum++;

        $clearData = self::prepareData($data);

        foreach ($clearData as $row) {
            foreach ($row as $columnNum => $column) {
                $cell = $activeSheet->getCellByColumnAndRow($columnNum + 1, $rowNum);
                $cell
                    ->getStyle()
                    ->applyFromArray([
                        'font' => [
                            'name' => 'Calibri',
                            'size' => 11,
                            'bold' => false,
                        ],
                        'alignment' => [
                            'horizontal' => $columns[$columnNum]['dataType'] === NumberFormat::FORMAT_TEXT ? Alignment::HORIZONTAL_LEFT : Alignment::HORIZONTAL_RIGHT,
                            'vertical' => Alignment::VERTICAL_BOTTOM,
                        ]
                    ])
                    ->getNumberFormat()
                    //->setFormatCode($columns[$columnNum]['dataType']);
                    ->setFormatCode('#');
                $cell->setValue($column);
            }
            $rowNum++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->setPreCalculateFormulas(false);

        return $writer;
    }
}

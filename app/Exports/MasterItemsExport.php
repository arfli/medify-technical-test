<?php

namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MasterItemsExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    WithColumnWidths,
    WithColumnFormatting
{
    protected $rowNumber = 0;

    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }
    /**
     * Get the data collection with categories relationship
     */
    public function collection()
    {
        $query = MasterItem::with('categories')->orderBy('id');
    
        if (!empty($this->filters['kode'])) {
            $query->where('kode', 'like', '%' . $this->filters['kode'] . '%');
        }
    
        if (!empty($this->filters['nama'])) {
            $query->where('nama', 'like', '%' . $this->filters['nama'] . '%');
        }
    
        if (!empty($this->filters['harga_min'])) {
            $query->where('harga_beli', '>=', $this->filters['harga_min']);
        }
    
        if (!empty($this->filters['harga_max'])) {
            $query->where('harga_beli', '<=', $this->filters['harga_max']);
        }
    
        return $query->get();
    }
    

    /**
     * Define column headings
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Kategori',
            'Nama Item',
            'Nama Supplier',
            'Harga Beli (Rp)',
            'Laba (%)',
            'Harga Jual (Rp)',
        ];
    }

    /**
     * Map data for each row
     */
    public function map($item): array
    {
        $this->rowNumber++;

        // Get category names from relationship
        $categoryNames = $item->categories->pluck('nama')->toArray();
        $categoryNamesStr = !empty($categoryNames) ? implode(', ', $categoryNames) : '-';

        // Calculate harga jual
        $hargaJual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);

        return [
            $this->rowNumber,
            $categoryNamesStr,
            $item->nama,
            $item->supplier,
            $item->harga_beli,
            $item->laba,
            $hargaJual,
        ];
    }

    /**
     * Style the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        // Style header
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Style all data rows
        $sheet->getStyle('A2:G' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Center align for No column
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Right align for price columns
        $sheet->getStyle('E2:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        
        // Center align for Laba column
        $sheet->getStyle('F2:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }

    /**
     * Define column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // No
            'B' => 35,  // Nama Kategori
            'C' => 35,  // Nama Item
            'D' => 20,  // Nama Supplier
            'E' => 18,  // Harga Beli
            'F' => 12,  // Laba
            'G' => 18,  // Harga Jual
        ];
    }

    /**
     * Format columns
     */
    public function columnFormats(): array
    {
        return [
            'E' => '#,##0',  // Harga Beli
            'F' => '0"%"',   // Laba
            'G' => '#,##0',  // Harga Jual
        ];
    }
}
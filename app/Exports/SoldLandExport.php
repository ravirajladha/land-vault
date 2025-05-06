<?php

namespace App\Exports;

use App\Models\Sold_land;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SoldLandExport implements FromCollection, WithHeadings, WithMapping
{
    protected $soldLands;

    public function __construct(array $soldLands)
    {
        $this->soldLands = $soldLands;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->soldLands);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Serial Number', 'State', 'District Number', 'District', 'Village Number', 'Village', 
            'Survey Number', 'Wet Land', 'Dry Land', 'Plot', 'Traditional Land', 'Total Area', 
            'Total Area Unit', 'Total Wet Land', 'Total Dry Land', 'Gap', 'Sale Amount', 
            'Total Sale Amount', 'Registration Office', 'Register Number', 'Register Date', 
            'Sale Date', 'Book Number', 'Name of the Purchaser', 'Balance Land', 'Remark', 
            'Latitude', 'Longitude', 'Created At', 'Updated At'
        ];
    }

    /**
     * @param $soldLand
     * @return array
     */
    public function map($soldLand): array
    {
        static $serialNumber = 1; // Static variable to maintain serial number count
        
        return [
            $serialNumber++, // Serial Number
            $soldLand['state'] ?? '',
            $soldLand['district_number'] ?? '',
            $soldLand['district'] ?? '',
            $soldLand['village_number'] ?? '',
            $soldLand['village'] ?? '',
            $soldLand['survey_number'] ?? '',
            $soldLand['wet_land'] ?? '',
            $soldLand['dry_land'] ?? '',
            $soldLand['plot'] ?? '',
            $soldLand['traditional_land'] ?? '',
            $soldLand['total_area'] ?? '',
            $soldLand['total_area_unit'] ?? '',
            $soldLand['total_wet_land'] ?? '',
            $soldLand['total_dry_land'] ?? '',
            $soldLand['gap'] ?? '',
            $soldLand['sale_amount'] ?? '',
            $soldLand['total_sale_amount'] ?? '',
            $soldLand['registration_office'] ?? '',
            $soldLand['register_number'] ?? '',
            isset($soldLand['register_date']) ? Carbon::parse($soldLand['register_date'])->format('d-M-Y') : '--',
            isset($soldLand['sale_date']) ? Carbon::parse($soldLand['sale_date'])->format('d-M-Y') : '--',
            $soldLand['book_number'] ?? '',
            $soldLand['name_of_the_purchaser'] ?? '',
            $soldLand['balance_land'] ?? '',
            $soldLand['remark'] ?? '',
            $soldLand['latitude'] ?? '',
            $soldLand['longitude'] ?? '',
            isset($soldLand['created_at']) ? Carbon::parse($soldLand['created_at'])->format('d-M-Y H:i') : '--',
            isset($soldLand['updated_at']) ? Carbon::parse($soldLand['updated_at'])->format('d-M-Y H:i') : '--',
        ];
    }
}

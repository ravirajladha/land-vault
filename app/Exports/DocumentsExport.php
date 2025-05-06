<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Set;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class DocumentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $documents;

    public function __construct(array $documents)
    {
        $this->documents = $documents;
        // dd($documents);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->documents);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Serial Number', 'Index Id', 'Document Name', 'Doc Identifier ID','Document Type', 'Category', 'Subcategory', 'Number of Pages', 'Current State', 'State', 'Alternate State', 'Current District', 'District', 
            'Alternate District', 'Current Village', 'Village', 'Alternate Village', 
            'Current Taluk', 'Taluk', 'Alternate Taluk', 'Locker No', 'Area', 'Dry Land', 
            'Wet Land', 'Unit', 'Set ID', 'Issued Date', 'Doc No', 'Survey No',  'Latitude', 'Longitude', 'Created At', 'Updated At'
        ];
    }

    /**
     * @param $document
     * @return array
     */
    public function map($document): array
    {
    
        // dd($document);
        static $serialNumber = 1;
        // Ensure $document is an array and contains necessary keys
        $categoryNames = $this->getCategoryNames($document['category_id'] ?? '');
        $subcategoryNames = $this->getSubcategoryNames($document['subcategory_id'] ?? '');
        $setNames = $this->getSetNames($document['set_id'] ?? '');

        $mappedData = [
            $serialNumber++,
            $document['temp_id'] ?? '',
            $document['name'] ?? '',
            $document['doc_identifier_id'] ?? '',
            $document['document_type_name'] ?? '',
            $categoryNames,
            $subcategoryNames,
            $document['number_of_page'] ?? '',
            $document['current_state'] ?? '',
            $document['state'] ?? '',
            $document['alternate_state'] ?? '',
            $document['current_district'] ?? '',
            $document['district'] ?? '',
            $document['alternate_district'] ?? '',
            $document['current_village'] ?? '',
            $document['village'] ?? '',
            $document['alternate_village'] ?? '',
            $document['current_taluk'] ?? '',
            $document['taluk'] ?? '',
            $document['alternate_taluk'] ?? '',
            $document['locker_id'] ?? '',
            $document['area'] ?? '',
            $document['dry_land'] ?? '',
            $document['wet_land'] ?? '',
            $document['unit'] ?? '',
            $setNames,
            $document['issued_date'] ?? '',
            $document['doc_no'] ?? '',
            $document['survey_no'] ?? '',
           
            $document['latitude'] ?? '',
            $document['longitude'] ?? '',
            isset($document['created_at']) ? Carbon::parse($document['created_at'])->format('d-M-Y H:i') : '--',
            isset($document['updated_at']) ? Carbon::parse($document['updated_at'])->format('d-M-Y H:i') : '--',
        ];
        // dd($mappedData);
        $childData = [];
        if (isset($document['document_type_name']) && isset($document['id'])) {
            $childData = $this->getChildDataWithLabels($document['document_type_name'], $document['id']);
        }
    // dd($mappedData);
        // Append child data to the parent document row
        return array_merge($mappedData, $childData);
        // return $mappedData;
    }
    protected function getChildDataWithLabels($tableName, $docId)
    {
        // dd($tableName);
        // Query the child table dynamically based on document type and document ID
        $childRows = DB::table($tableName)
                      ->where('doc_id', $docId)
                      ->get();
    
        $childData = [];
        
        // Loop through each child record and format it as "field_name: value"
        foreach ($childRows as $row) {
            foreach ((array) $row as $field => $value) {
                // Format as "field_name: value"
                $childData[] = $field . ': ' . ($value ?? '');
            }
        }
    
        return $childData; // Return the array of "field_name: value"
    }
    /**
     * Get category names by IDs
     *
     * @param string $categoryIds
     * @return string
     */
    protected function getCategoryNames($categoryIds)
    {
        if (!empty($categoryIds) && $categoryIds !== '--') {
            $ids = explode(',', $categoryIds);
            return Category::whereIn('id', $ids)->pluck('name')->implode(', ');
        }
        return '--';
    }

    /**
     * Get subcategory names by IDs
     *
     * @param string $subcategoryIds
     * @return string
     */
    protected function getSubcategoryNames($subcategoryIds)
    {
        if (!empty($subcategoryIds) && $subcategoryIds !== '--') {
            $ids = explode(',', $subcategoryIds);
            return Subcategory::whereIn('id', $ids)->pluck('name')->implode(', ');
        }
        return '--';
    }

    /**
     * Get set names by IDs
     *
     * @param string $setIds
     * @return string
     */
    protected function getSetNames($setIds)
    {
        if (!empty($setIds) && $setIds !== '--') {
            $ids = json_decode($setIds, true);
            if (is_array($ids) && !is_null($ids)) {
                return Set::whereIn('id', $ids)->pluck('name')->implode(', ');
            }
        }
        return '--';
    }
}
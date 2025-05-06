<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Set;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ChildDocumentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $documents;


    protected $childColumns = []; // To hold dynamic child column names

    public function __construct(array $documents)
    {
        Log::info("Initializing export with documents", ['documents' => $documents]);
        $this->documents = $documents;
        $this->setDynamicChildColumns(); // Dynamically set child columns
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->documents);
    }

    /**
     * Dynamically set child column names from the first document's child data.
     */
    protected function setDynamicChildColumns()
    {
        if (!empty($this->documents) && isset($this->documents[0]['child_data']) && !empty($this->documents[0]['child_data'])) {
            // Check the first child row to dynamically get column names
            $firstChildData = $this->documents[0]['child_data'][0] ?? [];
            $this->childColumns = array_keys((array)$firstChildData); // Extract column names from child data
        }
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $parentHeadings = [
            'Serial Number', 'Index Id', 'Document Name', 'Document Type', 'Category', 'Subcategory', 
            'Number of Pages', 'Current State', 'State', 'Alternate State', 'Current District', 
            'District', 'Alternate District', 'Current Village', 'Village', 'Alternate Village', 
            'Current Taluk', 'Taluk', 'Alternate Taluk', 'Locker No', 'Area', 'Dry Land', 
            'Wet Land', 'Unit', 'Set ID', 'Issued Date', 'Court Case No', 'Advocate Name', 
            'Case Status', 'Case Result', 'Doc No', 'Survey No', 'Doc Identifier ID', 'Latitude', 
            'Longitude', 'Created At', 'Updated At'
        ];

        // Dynamically add child-specific column names
        $childHeadings = array_map(function($columnName) {
            return  ucwords(str_replace('_', ' ', $columnName));
        }, $this->childColumns);

        return array_merge($parentHeadings, $childHeadings); // Merge parent and child headings
    }

    /**
     * @param $document
     * @return array
     */
    public function map($document): array
    {
        Log::info("Mapping document", ['document' => $document]);
        static $serialNumber = 1;

        $categoryNames = $this->getCategoryNames($document['category_id'] ?? '');
        $subcategoryNames = $this->getSubcategoryNames($document['subcategory_id'] ?? '');
        $setNames = $this->getSetNames($document['set_id'] ?? '');

        // Base parent document data mapping
        $mappedData = [
            $serialNumber++,
            $document['temp_id'] ?? '',
            $document['name'] ?? '',
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
            $document['locker_no'] ?? '',
            $document['area'] ?? '',
            $document['dry_land'] ?? '',
            $document['wet_land'] ?? '',
            $document['unit'] ?? '',
            $setNames,
            $document['issued_date'] ?? '',
            $document['court_case_no'] ?? '',
            $document['advocate_name'] ?? '',
            $document['case_status'] ?? '',
            $document['case_result'] ?? '',
            $document['doc_no'] ?? '',
            $document['survey_no'] ?? '',
            $document['doc_identifier_id'] ?? '',
            $document['latitude'] ?? '',
            $document['longitude'] ?? '',
            isset($document['created_at']) ? Carbon::parse($document['created_at'])->format('d-M-Y H:i') : '--',
            isset($document['updated_at']) ? Carbon::parse($document['updated_at'])->format('d-M-Y H:i') : '--',
        ];

  
      // Handle child data
      $childData = [];
      if (isset($document['child_data']) && is_array($document['child_data'])) {
          foreach ($document['child_data'] as $childRow) {
              foreach ($this->childColumns as $column) {
                  $childData[] = $childRow->{$column} ?? ''; // Append each child field
              }
          }
      }

      return array_merge($mappedData, $childData);
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
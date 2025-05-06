<?php

namespace App\Exports;

use App\Models\Document_assignment;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssignedDocumentsExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = DB::table('document_assignments')
            ->join('receivers', 'document_assignments.receiver_id', '=', 'receivers.id')
            ->join('receiver_types', 'receivers.receiver_type_id', '=', 'receiver_types.id')
            ->join('master_doc_datas', 'document_assignments.doc_id', '=', 'master_doc_datas.id');

        if (!empty($this->filters['receiver_type'])) {
            $query->where('receivers.receiver_type_id', $this->filters['receiver_type']);
        }

        if (!empty($this->filters['receiver_id'])) {
            $query->where('document_assignments.receiver_id', $this->filters['receiver_id']);
        }

        if (!empty($this->filters['doc_id'])) {
            $query->where('master_doc_datas.id', $this->filters['doc_id']);
        }

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('document_assignments.created_at', '>=', $this->filters['start_date']);
        }

        if (!empty($this->filters['end_date'])) {
            $query->whereDate('document_assignments.created_at', '<=', $this->filters['end_date']);
        }

       $data =  $query->get([
            // 'document_assignments.id as assignment_id',
            'receivers.name as receiver_name',
            'receiver_types.name as receiver_type_name',
            'document_assignments.created_at as created_at',
            'master_doc_datas.name as document_name',
            'master_doc_datas.category_id as category_id',
            'master_doc_datas.subcategory_id as subcategory_id',
            'master_doc_datas.location as location',
            'master_doc_datas.locker_id as locker_id',
            'master_doc_datas.category as category',
            'master_doc_datas.document_type_name as document_type_name',
            'master_doc_datas.current_state as current_state',
            'master_doc_datas.state as state',
            'master_doc_datas.alternate_state as alternate_state',
            'master_doc_datas.current_district as current_district',
            'master_doc_datas.district as district',
            'master_doc_datas.alternate_district as alternate_district',
            'master_doc_datas.current_taluk as current_taluk',
            'master_doc_datas.taluk as taluk',
            'master_doc_datas.alternate_taluk as alternate_taluk',
            'master_doc_datas.current_village as current_village',
            'master_doc_datas.village as village',
            'master_doc_datas.alternate_village as alternate_village',
            'master_doc_datas.issued_date as issued_date',
            'master_doc_datas.area as area',
            'master_doc_datas.dry_land as dry_land',
            'master_doc_datas.wet_land as wet_land',
            'master_doc_datas.unit as unit',
            'master_doc_datas.old_locker_number as old_locker_number',
            'master_doc_datas.latitude as latitude',
            'master_doc_datas.longitude as longitude',
          
            'master_doc_datas.survey_no as survey_no',
        ]);

   

  // Add serial numbers
  $data = $data->map(function ($item, $key) {
    $item = (array) $item; // Convert item to an array
    $item = ['serial_number' => $key + 1] + $item; // Add the serial number at the start of the array
    return $item;
});

return collect($data);
    }

    public function headings(): array
    {
        return [
            'Sl. No.',
            'Receiver Name',
            'Receiver Type',
            'Created At',
            'Document Name',
            'Category ID',
            'Subcategory ID',
            'Location',
            'Locker ID',
            'Category',
            'Document Type Name',
            'Current State',
            'State',
            'Alternate State',
            'Current District',
            'District',
            'Alternate District',
            'Current Taluk',
            'Taluk',
            'Alternate Taluk',
            'Current Village',
            'Village',
            'Alternate Village',
            'Issued Date',
            'Area',
            'Dry Land',
            'Wet Land',
            'Unit',
            'Old Locker Number',
            'Latitude',
            'Longitude',
          
            'Survey No',
        ];
    }
}

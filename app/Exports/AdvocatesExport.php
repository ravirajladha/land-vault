<?php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class AdvocatesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        // dd("inside");

        // Create a query that joins the necessary tables and applies the filters
        $query = DB::table('advocate_documents')
            ->join('advocates', 'advocate_documents.advocate_id', '=', 'advocates.id')
            ->join('master_doc_datas', 'advocate_documents.doc_id', '=', 'master_doc_datas.id');
// dd($this->filters['advocate_id']);
        // Apply filters
        if (!empty($this->filters['advocate_id'])) {
            $query->where('advocate_documents.advocate_id', $this->filters['advocate_id']);
        }

        if (!empty($this->filters['doc_id'])) {
            $query->where('advocate_documents.doc_id', $this->filters['doc_id']);
        }

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('advocate_documents.created_at', '>=', $this->filters['start_date']);
        }

        if (!empty($this->filters['end_date'])) {
            $query->whereDate('advocate_documents.created_at', '<=', $this->filters['end_date']);
        }

        // Get the relevant data
        return $query->select([
            'advocate_documents.id as assignment_id',
            'advocates.name as advocate_name',
            'advocate_documents.created_at as created_at',

            'master_doc_datas.name as document_name',
            'master_doc_datas.doc_identifier_id as doc_identifier_id',
            DB::raw('COALESCE(NULLIF(advocate_documents.case_name, ""), "--") as case_name'),
            DB::raw('COALESCE(NULLIF(advocate_documents.case_status, ""), "--") as case_status'),
            DB::raw('COALESCE(NULLIF(advocate_documents.court_name, ""), "--") as court_name'),
            DB::raw('COALESCE(NULLIF(advocate_documents.court_case_location, ""), "--") as court_case_location'),
            DB::raw('COALESCE(NULLIF(advocate_documents.plaintiff_name, ""), "--") as plaintiff_name'),
            DB::raw('COALESCE(NULLIF(advocate_documents.defendant_name, ""), "--") as defendant_name'),
        
            DB::raw('COALESCE(NULLIF(advocate_documents.case_result, ""), "--") as case_result'),
            DB::raw('COALESCE(NULLIF(advocate_documents.notes, ""), "--") as notes'),
            'advocate_documents.created_at',
            'advocate_documents.updated_at',
        ])->get();
    }

    public function headings(): array
    {
        return [
            'Assignment ID',
            'Advocate Name',
            'Created At',
            'Document Name',
            'Document Identifier Id',
            'Case Name',
            'Case Status',
            'Court Name',
            'Court Case Location',
            'Plaintiff Name',
            'Defendant Name',
            'Case Result',
            'Notes',
            'Created At',
            'Updated At'
        ];
    }

    public function map($document): array
    {
        return [
            $document->assignment_id,
            $document->advocate_name,
            $this->formatDate($document->created_at), // Format date as needed
            $document->document_name ?? '--', 
            $document->doc_identifier_id ?? '--', 
            $document->case_name ?? '--',
            $document->case_status ?? '--',
            $document->court_name ?? '--',
            $document->court_case_location ?? '--',
            $document->plaintiff_name ?? '--',
            $document->defendant_name ?? '--',
       
            $document->case_result ?? '--',
            $document->notes ?? '--',
            $this->formatDate($document->created_at), // Handle date formatting
            $this->formatDate($document->updated_at),
        ];
    }

    private function formatDate($date)
    {
        return $date ? Carbon::parse($date)->format('d-M-Y H:i') : '--';
    }
}

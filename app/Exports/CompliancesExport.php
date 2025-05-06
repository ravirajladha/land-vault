<?php
namespace App\Exports;

use App\Models\Compliance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class CompliancesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Compliance::select(
                'compliances.*', 
                'master_doc_datas.name as document_name', 
                'master_doc_types.name as document_type_name'
            )
            ->leftJoin('master_doc_datas', 'compliances.doc_id', '=', 'master_doc_datas.id')
            ->leftJoin('master_doc_types', 'compliances.document_type', '=', 'master_doc_types.id')
            ->orderBy('compliances.created_at', 'desc');

        // Apply filters based on request data
        if (!empty($this->filters['document_name'])) {
            $query->where('master_doc_datas.name', 'like', '%' . $this->filters['document_name'] . '%');
        }

        if (!empty($this->filters['document_type_name'])) {
            $query->where('master_doc_types.name', 'like', '%' . $this->filters['document_type_name'] . '%');
        }

        if (!empty($this->filters['start_due_date'])) {
            $query->whereDate('compliances.due_date', '>=', $this->filters['start_due_date']);
        }

        if (!empty($this->filters['end_due_date'])) {
            $query->whereDate('compliances.due_date', '<=', $this->filters['end_due_date']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Document Name',
            'Document Type',
            'Due Date',
            'Recurrence Interval',
            'Status',
            'Is Recurring',
            'Created At'
        ];
    }

    public function map($compliance): array
    {
        // Format the due date and created_at date as d-M-Y (e.g., 11-Jun-2024)
        $formattedDueDate = Carbon::parse($compliance->due_date)->format('d-M-Y');
        $formattedCreatedAt = Carbon::parse($compliance->created_at)->format('d-M-Y');

        // Split the recurrence_interval (e.g., 1_months to "1 months")
        $recurrenceInterval = $compliance->recurrence_interval ? str_replace('_', ' ', $compliance->recurrence_interval) : 'N/A';

        return [
            $compliance->id,
            $compliance->document_name,
            $compliance->document_type_name,
            $formattedDueDate,              // Formatted due date
            $recurrenceInterval,            // Split recurrence interval
            $compliance->status,
            $compliance->is_recurring ? 'Yes' : 'No',
            $formattedCreatedAt             // Formatted created_at
        ];
    }
}


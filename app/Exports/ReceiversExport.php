<?php


namespace App\Exports;

use App\Models\Receiver;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReceiversExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Receiver::with('receiverType')
            ->withCount('documentAssignments');

        if (!empty($this->filters['name'])) {
            $query->where('name', 'like', '%' . $this->filters['name'] . '%');
        }

        if (!empty($this->filters['email'])) {
            $query->where('email', 'like', '%' . $this->filters['email'] . '%');
        }

        if (!empty($this->filters['phone'])) {
            $query->where('phone', 'like', '%' . $this->filters['phone'] . '%');
        }

        if (!empty($this->filters['receiver_type'])) {
            $query->where('receiver_type_id', $this->filters['receiver_type']);
        }
        if (!empty($this->filters['doc_id'])) {
            $query->whereHas('documentAssignments', function($q) {
                $q->where('doc_id', $this->filters['doc_id']);
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Sl. No.',
            'Name',
            'Phone',
            'City',
            'Email Id',
            'Type',
            'No. Of Documents',
            'Status'
        ];
    }

    public function map($receiver): array
    {
        return [
            $receiver->id,
            $receiver->name,
            $receiver->phone,
            $receiver->city,
            $receiver->email,
            $receiver->receiverType->name,
            $receiver->document_assignments_count,
            $receiver->status
        ];
    }
}

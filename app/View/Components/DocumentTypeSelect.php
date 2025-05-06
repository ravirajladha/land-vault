<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use App\Models\Master_doc_type;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

class DocumentTypeSelect extends Component
{
    /**
     * Create a new component instance.
     */
    public $documentTypes;
    public $isStatus;

    public function __construct($isStatus)
    {
        Log::info('is status',['isStatus' => $isStatus]);
        $this->isStatus = filter_var($isStatus, FILTER_VALIDATE_BOOLEAN);
        Log::info('is status',['isStatus' => $isStatus]);

        // $this->isStatus = $isStatus;
        // $this->documentTypes = Master_doc_type::all();
        $this->documentTypes = Master_doc_type::orderBy('name')->get();
        // Iterate over each document type
        $this->documentTypes->each(function ($documentType) {
            // Calculate the count of approved documents for the current document type
            $approvedDocumentsCount = DB::table('master_doc_datas')
                ->where('document_type', $documentType->id)
                ->where('status_id', $this->isStatus ? 1 : 0) 
                ->count();

            // Add the count of approved documents to the current document type object
            $documentType->approved_documents_count = $approvedDocumentsCount;
        });
        // Fetch all document types
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.document-type-select');
    }
}

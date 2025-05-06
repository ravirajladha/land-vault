<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\CompliancesExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Schema;
use App\Models\{ Master_doc_type, Compliance,Master_doc_data};

class ComplianceController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function showCompliances(Request $request)
{
    // Step 1: Fetch unique doc_id from compliances and their corresponding document names and types
    $uniqueDocuments = Compliance::select(
        'compliances.doc_id',
        'master_doc_datas.name as document_name',
        'master_doc_types.name as document_type_name'
    )
        ->leftJoin('master_doc_datas', 'compliances.doc_id', '=', 'master_doc_datas.id')
        ->leftJoin('master_doc_types', 'compliances.document_type', '=', 'master_doc_types.id')
        ->distinct()
        ->get();

    // Step 2: Build the main query for compliances, including filters
    $query = Compliance::select(
        'compliances.*',
        'master_doc_datas.name as document_name',
        'master_doc_types.name as document_type_name'
    )
        ->leftJoin('master_doc_datas', 'compliances.doc_id', '=', 'master_doc_datas.id')
        ->leftJoin('master_doc_types', 'master_doc_datas.document_type_name', '=', 'master_doc_types.id')
        ->orderBy('compliances.created_at', 'desc');

    // Apply filters for start_due_date and end_due_date
    if ($request->has('start_due_date') && !empty($request->start_due_date)) {
        $startDate = Carbon::parse($request->start_due_date)->format('Y-m-d');
        $query->whereDate('compliances.due_date', '>=', $startDate);
    }

    if ($request->has('end_due_date') && !empty($request->end_due_date)) {
        $endDate = Carbon::parse($request->end_due_date)->format('Y-m-d');
        $query->whereDate('compliances.due_date', '<=', $endDate);
    }

    if ($request->has('document_name') && !empty($request->document_name)) {
        $query->where('master_doc_datas.name', 'like', '%' . $request->document_name . '%');
    }
    // dd($request->all()  );
    // Apply is_recurring filter
    if ($request->has('is_recurring')) {
        $query->where('compliances.is_recurring', $request->is_recurring);
    }

    // Apply status filter
    if ($request->has('status')) {
        $query->where('compliances.status', $request->status);
    }

    // Step 3: Log the query for debugging
    // dd($query->toSql(), $query->getBindings());

    // Step 4: Execute the query to fetch compliances
    $compliances = $query->get();

    // Step 5: Fetch child_id for each compliance from the respective child table based on document_type_name
    foreach ($compliances as $compliance) {
        $documentTypeId = $compliance->document_type; // Assuming document_type is the ID from master_doc_types

        // Fetch the table name from master_doc_types based on document_type ID
        $documentTypeName = DB::table('master_doc_types')->where('id', $documentTypeId)->value('name');

        // Check if the table exists
        if (Schema::hasTable($documentTypeName)) {
            // Fetch the child_id from the dynamic child table
            $childId = DB::table($documentTypeName)
                ->where('doc_id', $compliance->doc_id) // Assuming 'doc_id' is the foreign key in the child table
                ->value('id'); // Assuming 'id' is the child_id in the child table

            // Assign the child_id to the compliance object
            $compliance->child_id = $childId;
        } else {
            $compliance->child_id = null; // If table doesn't exist, set to null
        }
    }

    // Step 6: Retrieve unique lists for filtering purposes (document names and types)
    $documentTypes = Master_doc_type::orderBy('name')->distinct()->get();
    $documents = Master_doc_data::select('id', 'name')->distinct()->get();

    // Step 7: Return the data to the view
    return view('pages.compliances.compliances', [
        'compliances' => $compliances, // Now includes child_id
        'uniqueDocuments' => $uniqueDocuments, // For filtering: unique document names and types
        'documentTypes' => $documentTypes,
        'documents' => $documents,
    ]);
}


    // public function showCompliances(Request $request)
    // {
    //     // Step 1: Fetch unique doc_id from compliances and their corresponding document names and types
    //     $uniqueDocuments = Compliance::select(
    //         'compliances.doc_id',
    //         'master_doc_datas.name as document_name',
    //         'master_doc_types.name as document_type_name'
    //     )
    //         ->leftJoin('master_doc_datas', 'compliances.doc_id', '=', 'master_doc_datas.id')
    //         ->leftJoin('master_doc_types', 'compliances.document_type', '=', 'master_doc_types.id')
    //         ->distinct()
    //         ->get();

    //      dd($request->all());
    //     // Step 2: Build the main query for compliances, including filters
    //     $query = Compliance::select(
    //         'compliances.*',
    //         'master_doc_datas.name as document_name',
    //         'master_doc_types.name as document_type_name'
    //     )
    //         ->leftJoin('master_doc_datas', 'compliances.doc_id', '=', 'master_doc_datas.id')
    //         ->leftJoin('master_doc_types', 'master_doc_datas.document_type_name', '=', 'master_doc_types.id')
    //         ->orderBy('compliances.created_at', 'desc');


    //     // Apply filters for start_due_date and end_due_date
    //     if ($request->has('start_due_date') && !empty($request->start_due_date)) {
    //         $startDate = Carbon::parse($request->start_due_date)->format('Y-m-d');
    //         $query->whereDate('compliances.due_date', '>=', $startDate);
    //     }

    //     if ($request->has('end_due_date') && !empty($request->end_due_date)) {
    //         $endDate = Carbon::parse($request->end_due_date)->format('Y-m-d');
    //         $query->whereDate('compliances.due_date', '<=', $endDate);
    //     }

    //     // Apply filters
    //     if ($request->has('document_name')) {
    //         $query->where('master_doc_datas.name', 'like', '%' . $request->document_name . '%');
    //     }

    //     // if ($request->has('document_type_name')) {
    //     //     $query->where('master_doc_types.name', 'like', '%' . $request->document_type_name . '%');
    //     // }

    //     if ($request->has('is_recurring')) {
    //         $query->where('compliances.is_recurring', $request->is_recurring);
    //     }

    //     if ($request->has('status')) {
    //         $query->where('compliances.status', $request->status);
    //     }

    //     // Step 3: Execute the query to fetch compliances
    //     $compliances = $query->get();
    //     // dd($compliances);
    //     // Step 4: Fetch child_id for each compliance from the respective child table based on document_type_name
    //     foreach ($compliances as $compliance) {
    //         // Get the document type name for this compliance
    //         $documentTypeId = $compliance->document_type; // Assuming document_type is the ID from master_doc_types

    //         // Fetch the table name from master_doc_types based on document_type ID
    //         $documentTypeName = DB::table('master_doc_types')->where('id', $documentTypeId)->value('name');
    //         // dd($documentType);
    //         // Construct the child table name and check if it exists
    //         if (Schema::hasTable($documentTypeName)) {
    //             // Fetch the child_id from the dynamic child table
    //             $childId = DB::table($documentTypeName)
    //                 ->where('doc_id', $compliance->doc_id) // Assuming 'doc_id' is the foreign key in the child table
    //                 ->value('id'); // Assuming 'id' is the child_id in the child table

    //             // Assign the child_id to the compliance object
    //             $compliance->child_id = $childId;
    //         } else {
    //             $compliance->child_id = null; // If table doesn't exist, set to null
    //         }
    //     }

    //     // Step 5: Retrieve unique lists for filtering purposes (document names and types)
    //     $documentTypes = Master_doc_type::orderBy('name')->distinct()->get();
    //     $documents = Master_doc_data::select('id', 'name')->distinct()->get();
    //     // dd($compliances);
    //     // Step 6: Return the data to the view
    //     return view('pages.compliances.compliances', [
    //         'compliances' => $compliances, // Now includes child_id
    //         'uniqueDocuments' => $uniqueDocuments, // For filtering: unique document names and types
    //         'documentTypes' => $documentTypes,
    //         'documents' => $documents,
    //     ]);
    // }

    public function compliancesExport(Request $request)
    {

        // dd("sdfsdf");
        // dd($request->all());
        return Excel::download(new CompliancesExport($request->all()), 'advocates_assigned_documents.xlsx');
    }
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validatedData = $request->validate([
                'document_type' => 'required|exists:master_doc_types,id',
                'document_id' => 'required|exists:master_doc_datas,id',
                'name' => 'required|string|max:255',
                'due_date' => 'required|date',
                'is_recurring' => 'sometimes|boolean',
                'recurrence_interval' => 'nullable|string|in:1_months,3_months,6_months,12_months',
            ]);

            // Create a new compliance record
            $compliance = new Compliance();
            $compliance->document_type = $validatedData['document_type'];
            $compliance->doc_id = $validatedData['document_id'];
            $compliance->name = $validatedData['name'];
            $compliance->due_date = $validatedData['due_date'];
            $compliance->is_recurring = $request->has('is_recurring') ? 1 : 0;

            // Store recurrence interval if it's a recurring compliance
            if ($compliance->is_recurring && $request->filled('recurrence_interval')) {
                $compliance->recurrence_interval = $validatedData['recurrence_interval'];
            }

            $compliance->created_by = Auth::user()->id;
            $compliance->save();

            // Flash success message
            session()->flash('toastr', ['type' => 'success', 'message' => 'Compliance created successfully.']);
        } catch (Exception $e) {
            // Log the error for debugging
            logger()->error('Error in creating compliance: ' . $e->getMessage());

            // Flash error message to session
            session()->flash('toastr', ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }

        return back();
    }



    public function statusChangeCompliance(Request $request, $id, $action)
    {
        // \Log::info('Status change requested for compliance ID: ' . $id . ' with action: ' . $action);
        $compliance = Compliance::findOrFail($id);
        $compliance->status = $action == "settle" ? 1 : 2;
        $compliance->save();
        // $this->notificationService->createComplianceNotification('updated', $compliance);

        // $this->createNotification("updated", $compliance);
        return response()->json([

            'success' => 'Status updated successfully.',
            'newStatus' => $compliance->status
        ]);
    }





    public function toggleIsRecurring(Request $request, $id)
    {
        $compliance = Compliance::findOrFail($id);

        // Deactivate the compliance
        if ($compliance->is_recurring) {
            $compliance->is_recurring = 0;
            $compliance->save();
            session()->flash('toastr', ['type' => 'error', 'message' => 'Compliance recurring deactivated successfully.']);
            return redirect()->back()->with('success', 'Compliance deactivated successfully.');
        }

        // Reactivate the compliance - update OTP and expiry
        else {

            $compliance->is_recurring = 1; // Set status to active
            $compliance->save();

            session()->flash('toastr', ['type' => 'success', 'message' => 'Compliance recurring reactivated successfully.']);
            return redirect()->back()->with('success', 'Compliance recurring reactivated successfully.');
        }
    }

}

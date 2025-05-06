<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\{Receiver, Receiver_type, Master_doc_type, Advocate, Advocate_documents, Master_doc_data};
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AdvocatesExport;

class AdvocateController extends Controller
{
    //receiver types function

    //receivers
    public function showAdvocates(Request $request)
    {
        $query = Advocate::withCount('documentAssignments');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->input('phone') . '%');
        }


        // dd($request->input('doc_id'));
        if ($request->filled('doc_id')) {
            $query->whereHas('documentAssignments', function ($q) use ($request) {
                $q->where('doc_id', $request->input('doc_id'));
            });
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $documentTypes = Master_doc_type::orderBy('name')->get();
        $documents = Master_doc_data::select('id', 'name')->get();
        $uniqueDocuments = Master_doc_data::whereIn('id', function ($query) {
            $query->select('doc_id')
                ->from('advocate_documents')
                ->distinct();
        })->orderBy('name')->get();
        return view('pages.advocates.advocate.index', [
            'data' => $data,

            'documentTypes' => $documentTypes,
            'documents' => $uniqueDocuments
        ]);

    }

    public function exportAdvocates(Request $request)
    {
        return Excel::download(new AdvocatesExport($request->all()), 'advocates.xlsx');
    }

    public function addAdvocate(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|size:10|regex:/^\d{10}$/',
            'email' => 'required|string|email|max:255|unique:receivers,email',
            'address' => 'required|string',

        ]);

        // Create a new receiver
        $receiver = new Advocate;
        $receiver->name = $request->name;
        $receiver->phone = $request->phone;
        $receiver->email = $request->email;
        $receiver->address = $request->address;

        $receiver->created_by = Auth::user()->id; // or Auth::user()->id;
        $receiver->save();

        // Return a JSON response indicating success
        return response()->json(['success' => 'Advocate added successfully.']);
    }

    public function updateAdvocate(Request $request)
    {
        Log::info("update advocate details", $request->all());
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:advocates,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:advocates,email,' . $request->id,
            'address' => 'required|string',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $advocate = Advocate::findOrFail($request->id);
            $advocate->name = $request->name;
            $advocate->phone = $request->phone;
            $advocate->email = $request->email;
            $advocate->address = $request->address;
            $advocate->status = $request->status;

            // Add any additional fields you want to update here

            $advocate->save();

            return response()->json(['success' => 'Advocate updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the advocate.'], 500);
        }
    }
    public function showAssignedDocument()
    {
        $documentAssignments = Advocate_documents::with(['advocate',  'document'])->orderBy('created_at', 'desc')->get();

        $documentTypes = Master_doc_type::orderBy('name')->get();

        return view('pages.assign-document.assign-documents', [
            'documentAssignments' => $documentAssignments,
            'documentTypes' => $documentTypes,
        ]);
    }

    public function showAdvocateAssignedDocument(Request $request, $advocateId)
    {
        // Start a query to filter document assignments by advocate ID
        $documentAssignmentsQuery = Advocate_documents::with(['advocate', 'document.documentType'])
            ->where('advocate_id', $advocateId);
    
        // Apply filters based on the request
        if ($request->has('doc_id') && !empty($request->input('doc_id'))) {
            $documentAssignmentsQuery->where('doc_id', $request->input('doc_id'));
        }
    
        if ($request->has('case_result') && !empty($request->input('case_result'))) {
            $documentAssignmentsQuery->where('case_result', $request->input('case_result'));
        }
    
        if ($request->has('plaintiff_name') && !empty($request->input('plaintiff_name'))) {
            $documentAssignmentsQuery->where('plaintiff_name', $request->input('plaintiff_name'));
        }
    
        if ($request->has('defendant_name') && !empty($request->input('defendant_name'))) {
            $documentAssignmentsQuery->where('defendant_name', $request->input('defendant_name'));
        }
    
        // Execute the query and paginate the results
        $documentAssignments = $documentAssignmentsQuery->orderBy('created_at', 'desc')->paginate(10);
    
        // Retrieve the lists of document types and receiver types for dropdowns
        $documentTypes = Master_doc_type::all();
        $receiverTypes = Receiver_type::where('status', 1)->get();
    
        // Retrieve the advocate details
        $advocate = Advocate::find($advocateId);
      // Process each document assignment to retrieve the child_id
      foreach ($documentAssignments as $assignment) {
        $documentTypeName = $assignment->document->documentType->name;

        // Build the table name dynamically
        $childDocument = DB::table($documentTypeName)
            ->where('doc_id', $assignment->doc_id)
            ->first();

        if ($childDocument) {
            $assignment->child_id = $childDocument->id;
        }
    }
        // Retrieve all advocates for the dropdown
        $advocates = Advocate::all();
    
        // Get unique plaintiff names and defendant names for the filters
      // Fetch distinct non-null, non-empty plaintiff names
$plaintiff_names = Advocate_documents::where('advocate_id', $advocateId)
->whereNotNull('plaintiff_name')
->where('plaintiff_name', '!=', '')
->select('plaintiff_name')
->distinct()
->get();

// Fetch distinct non-null, non-empty defendant names
$defendant_names = Advocate_documents::where('advocate_id', $advocateId)
->whereNotNull('defendant_name')
->where('defendant_name', '!=', '')
->select('defendant_name')
->distinct()
->get();

// Fetch distinct non-null, non-empty case results
$unique_case_results = Advocate_documents::where('advocate_id', $advocateId)
->whereNotNull('case_result')
->where('case_result', '!=', '')
->select('case_result')
->distinct()
->get();

    // dd($documentAssignments);
    
        // Return the view with the necessary data
        return view('pages.advocates.assign-document.index', [
            'documentAssignments' => $documentAssignments,
            'documentTypes' => $documentTypes,
            'receiverTypes' => $receiverTypes,
            'advocate' => $advocate,
            'advocateId' => $advocateId,
            'advocates' => $advocates,
            'plaintiff_names' => $plaintiff_names,  // Pass to view
            'defendant_names' => $defendant_names,  // Pass to view
            'unique_case_results' => $unique_case_results  // Pass to view
        ]);
    }
    

    public function getReceiversByType($typeId)
    {
        $receivers = Receiver::where('receiver_type_id', $typeId)->get();
        return response()->json(['receivers' => $receivers]);
    }

    public function getActiveReceiversByType($typeId)
    {
        $receivers = Receiver::where('receiver_type_id', $typeId)->where('status', true)->get();
        return response()->json(['receivers' => $receivers]);
    }

    public function assignDocumentsToAdvocate(Request $request)
    {
        // dd($request->all());
        // Define validation rules
        $rules = [
            'document_id' => 'required|exists:master_doc_datas,id', // Assuming documents table exists
            'advocate_id' => 'required|exists:advocates,id', // Assuming advocates table exists
            'case_name' => 'nullable|string|max:255',
            'case_status' => 'nullable|string|max:255',
            // 'start_date' => 'nullable|date',
            // 'end_date' => 'nullable|date',
            'court_name' => 'nullable|string|max:255',
            'court_case_location' => 'nullable|string|max:255',
            'plaintiff_name' => 'nullable|string|max:255',
            'defendant_name' => 'nullable|string|max:255',
            // 'urgency_level' => 'nullable|string|max:255',
            'case_result' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            // 'submission_deadline' => 'nullable|date'
        ];

        // Validate the request
        $validatedData = $request->validate($rules);

        // Check advocate status
        $advocate = Advocate::find($validatedData['advocate_id']);
        if (!$advocate || $advocate->status != 1) {
            session()->flash('toastr', ['type' => 'error', 'message' => 'Advocate is not active.']);
            return redirect()->back();
        }

        // dd($validatedData['case_result']);
        $advocate_id = $validatedData['advocate_id'];
        // Create the assignment
        $assignment = Advocate_documents::create([
            'doc_id' => $validatedData['document_id'],
            'advocate_id' => $validatedData['advocate_id'],
            'case_name' => $validatedData['case_name'] ?? null,
            'case_status' => $validatedData['case_status'] ?? null,
            // 'start_date' => $validatedData['start_date'] ?? null,
            // 'end_date' => $validatedData['end_date'] ?? null,
            'court_name' => $validatedData['court_name'] ?? null,
            'court_case_location' => $validatedData['court_case_location'] ?? null,
            'plaintiff_name' => $validatedData['plaintiff_name'] ?? null,
            'defendant_name' => $validatedData['defendant_name'] ?? null,
            // 'urgency_level' => $validatedData['urgency_level'] ?? null,
            'case_result' => $validatedData['case_result'] ?? null,
            'notes' => $validatedData['notes'] ?? null,
            // 'submission_deadline' => $validatedData['submission_deadline'] ?? null,
            'created_by' => Auth::user()->id,
        ]);


        if ($request->location == "all" || $request->location == "user") {
            return redirect()->route('advocate.documents.assigned.show', ['advocate_id' => $validatedData['advocate_id']]);
        } elseif ($request->location == "review") {
            return redirect()->back()->with('success', 'Assignment created successfully.');
        } else {
            return redirect()->back()->with('success', 'Assignment created successfully.');
        }
    }

    public function editDocumentAssignment($id)
    {
        Log::info("edit document assignment", ['id' => $id]);
    
        // Load the advocate name along with the document assignment
        $assignment = Advocate_documents::with(['document', 'advocate'])->find($id); // Assuming 'advocate' is a relationship
        Log::info("edit document assignment", ['assignment' => $assignment]);
    
        if (!$assignment) {
            return response()->json(['error' => 'Document assignment not found.'], 404);
        }
    
        // Retrieve all advocates
        $advocates = Advocate::all();
    
        return response()->json([
            'assignment' => $assignment,
            'advocates' => $advocates
        ]);
    }
    
    
    public function updateDocumentAssignment(Request $request, $id)
    {
        // Log::info("update document assignment", ['id' => $id]);
        // Define validation rules
        $rules = [
            'advocate_id' => 'required|exists:advocates,id',
            'case_name' => 'nullable|string|max:255',
            'case_status' => 'nullable|string|max:255',
            // 'start_date' => 'nullable|date',
            // 'end_date' => 'nullable|date',
            'court_name' => 'nullable|string|max:255',
            'court_case_location' => 'nullable|string|max:255',
            'plaintiff_name' => 'nullable|string|max:255',
            'defendant_name' => 'nullable|string|max:255',
            // 'urgency_level' => 'nullable|string|max:255',
            'case_result' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            // 'submission_deadline' => 'nullable|date'
        ];

        // Validate the request
        $validatedData = $request->validate($rules);

        // Find the assignment
        $assignment = Advocate_documents::find($id);
        if (!$assignment) {
            return redirect()->back()->with('error', 'Document assignment not found.');
        }

        // Update the assignment
        $assignment->update($validatedData);
        return redirect()->back()->with('success', 'Assignment updated successfully.');
      
    }

    public function destroy($id)
    {
        $assignment = Advocate_documents::find($id);
        if (!$assignment) {
            return redirect()->back()->with('error', 'Document assignment not found.');
        }

        // Update the status to 0 instead of deleting
        $assignment->status = 0;
        $assignment->save();

        return redirect()->back()->with('success', 'Assignment status updated successfully.');

        // return redirect()->route('advocate.documents.assigned.show', ['advocate_id' => $assignment->advocate_id])
        //                  ->with('success', 'Assignment status updated successfully.');
    }

    public function bulkUploadAdvocateAssignDocument(Request $request)
    {
        // dd("sdfsdf");
        $request->validate([
            'document' => 'required|file|mimes:csv,txt|max:10240', // Adjust max file size as needed
        ]);
    
        $filePath = $request->file('document')->getRealPath();
        $file = fopen($filePath, 'r');
    
        // Skip the first row (assuming it contains headers)
        fgetcsv($file);
    
        DB::beginTransaction();
    
        try {
            while (($line = fgetcsv($file)) !== false) {
                if (!empty($line[1])) { // Use the second column (index 1) as the temp_id
                    if (array_filter($line)) {
                        // Convert dates from dd/mm/yyyy or dd-mm-yyyy to yyyy-mm-dd
                        // $startDate = $this->convertDateFormat($line[4]);
                        // $endDate = $this->convertDateFormat($line[5]);
                        // $submissionDeadline = $this->convertDateFormat($line[12]);
    
                        // Check if advocate exists by name
                        $advocateName = $line[9] ?? null; // Assuming advocate name is in column 13

                        // dd($advocateName);
                        if ($advocateName) {
                            $advocate = DB::table('advocates')->where('name', $advocateName)->first();
    
                            if (!$advocate) {
                                // If advocate doesn't exist, insert and retrieve the ID
                                $advocateId = DB::table('advocates')->insertGetId([
                                    'name' => $advocateName,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            } else {
                                // If advocate exists, get the advocate ID
                                $advocateId = $advocate->id;
                            }
                            //   dd($advocateId);
                        } else {
                            throw new \Exception("Advocate name not provided in the CSV.");
                        }
    
                        // Extract data from each row, adjusting indexes as necessary
                        $data = [
                            'case_name' => $line[2] ?? null,
                            'case_status' => $line[3] ?? null,
                            'court_name' => $line[4] ?? null,
                            'court_case_location' => $line[5] ?? null,
                            'plaintiff_name' => $line[6] ?? null,
                            'defendant_name' => $line[7] ?? null,
                            'notes' => $line[8] ?? null,
                            'advocate_id' => $advocateId, // Using advocate ID
                            'case_result' => $line[10] ?? null,
                            // 'status' => $line[11] ?? null,
                            'created_by' => Auth::user()->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
    
                        // Validate the data
                        $validator = Validator::make($data, [
                            // Your validation rules here
                        ]);
    
                        if ($validator->fails()) {
                            throw new \Exception('Validation failed for one or more rows.');
                        }
    
                        // Retrieve the doc_id using temp_id from master_doc_data table
                        $doc_id = DB::table('master_doc_datas')
                            ->where('temp_id', $line[1]) // Assuming the second column (line[1]) is the temp_id
                            ->value('id');
    
                        if (!$doc_id) {
                            throw new \Exception("Document ID not found for temp_id: {$line[1]}");
                        }
    
                        // Assign the doc_id to the data
                        $data['doc_id'] = $doc_id;
    
                        // Insert data into advocate_documents table
                        DB::table('advocate_documents')->insert($data);
                    }
                }
            }
    
            DB::commit();
    
            // Close the file
            fclose($file);
    
            // Redirect or return a response
            return redirect()->back()->with('success', 'Bulk upload completed successfully.');
        } catch (\Exception $e) {
            Log::error('Bulk upload failed: ' . $e->getMessage());
            DB::rollBack();
    
            // Close the file
            fclose($file);
    
            // Redirect back with error message
            return redirect()->back()->with('error', 'Bulk upload failed. ' . $e->getMessage());
        }
    }
    
    private function convertDateFormat($date)
    {
        if (!$date) {
            return null;
        }

        $dateFormats = ['d/m/Y', 'd-m-Y'];
        foreach ($dateFormats as $format) {
            try {
                return Carbon::createFromFormat($format, trim($date))->format('Y-m-d');
            } catch (\Exception $e) {
                // Continue to the next format
            }
        }

        return null; // If none of the formats match, return null
    }
}

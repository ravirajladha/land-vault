<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\{Receiver, Receiver_type, Master_doc_type, Master_doc_data, Table_metadata, Document_assignment, Compliance, Set, State};
use Illuminate\Support\Facades\Auth;


class SetController extends Controller
{
    public function viewSet()
    {
        $data = Set::paginate(20);
        
        // Initialize an empty array to hold the counts
        $setCounts = [];

        // Retrieve all set_id entries that are not null
        $setIds = DB::table('master_doc_datas')
            ->whereNotNull('set_id')
            ->get(['set_id']);

        // Loop through each set_id entry, decode it, and count the occurrences
        foreach ($setIds as $setIdEntry) {
            $idsArray = json_decode($setIdEntry->set_id, true); // Decode JSON string to PHP array
            if (is_array($idsArray)) { // Ensure it's an array
                foreach ($idsArray as $setId) {
                    if (!isset($setCounts[$setId])) {
                        $setCounts[$setId] = 0;
                    }
                    $setCounts[$setId]++;
                }
            }
        }


        // Pass the counts and the set data to the view
        return view('pages.sets.set', [
            'data' => $data,
            'setCounts' => $setCounts
        ]);
    }

    public function viewDocumentsForSet($setId)
    {
        $get_set_detail  = Set::where('id', $setId)->first();
        // dd($get_set_detail);
        // Retrieve all distinct document_type_names (child table names) where the set_id is present
        $documentTypes = DB::table('master_doc_datas')
            ->select('document_type_name')
            ->whereRaw("JSON_CONTAINS(set_id, '\"$setId\"')")
            ->distinct()
            ->orderBy('document_type_name', 'asc')
            ->pluck('document_type_name');


        $documentsDetails = collect();

        // For each document type name, get the associated documents from the child table
        foreach ($documentTypes as $documentType) {
            if (Schema::hasTable($documentType)) {
                // Get the documents from the child table where their doc_id matches an id in master_doc_data
                $documents = DB::table($documentType)
                    ->join('master_doc_datas', 'master_doc_datas.id', '=', "$documentType.doc_id")
                    ->whereRaw("JSON_CONTAINS(master_doc_datas.set_id, '\"$setId\"')")
                    ->select("$documentType.*") // Select all columns from the child table
                    ->get();

                // Merge the documents into the documentsDetails collection
                $documentsDetails = $documentsDetails->merge($documents);
            }
        }
        // dd($documentsDetails);
        // Pass the documents details to the view
        return view('pages.documents.documents-for-set', [
            'documentsDetails' => $documentsDetails,
            'setId' => $setId,
            'get_set_detail' => $get_set_detail,
        ]);
    }


    public function addSet(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255|unique:sets', // Unique validation rule added
        ]);

        // Check for duplicate set name
        $existingSet = Set::where('name', $request->name)->first();

        if ($existingSet) {
            return response()->json(['error' => 'Set name already exists.'], 422); // Return error response for duplicate name
        }

        // Create a new set
        $set = new Set;
        $set->name = $request->name;
        $set->created_by =  Auth::user()->id;
        // Save the set to the database
        $set->save();

        return response()->json(['success' => 'Set added successfully.']);
    }

    public function updateSet(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sets,id',
            'name' => 'required|string|max:255', // Validation rules as per your requirements
        ]);

        try {
            $set = Set::findOrFail($request->id);
            $set->name = $request->name;
            $set->save();

            return response()->json(['success' => 'Set updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the set.'], 500);
        }
    }

    public function viewUpdatedSets()
    {
        $sets = Set::get(); // Assuming Set is your model name
        return response()->json($sets);
    }
}

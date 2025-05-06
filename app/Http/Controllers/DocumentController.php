<?php

namespace App\Http\Controllers;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Services\DocumentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\Auth;
use App\Services\DocumentTableService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Response;
use App\Models\{Receiver, Receiver_type, Master_doc_type, Master_doc_data, Table_metadata, Document_assignment, Compliance, Set, State, DocumentStatusLog, Advocate_documents, Advocate, Document_transaction, Category, Sold_land};

class DocumentController extends Controller
{


    public function document_type()
    {
        // Fetch all document types
        $doc_types = Master_doc_type::orderBy('name')->get();

        // Create an empty array to store counts
        $doc_counts = [];

        // Loop through each document type and fetch the count of records in master_doc_data
        foreach ($doc_types as $doc_type) {
            $count = Master_doc_data::where('document_type', $doc_type->id) // Assuming 'id' is the foreign key in master_doc_data referring to document_type
                ->count();

            // Add the count to the array with document type name as the key
            $doc_counts[$doc_type->id] = $count;
        }
        // throw new \Exception("This is a test exception.");

        return view('pages.documents.document_type', ['doc_types' => $doc_types, 'doc_counts' => $doc_counts]);
    }

    public function addDocumentType(Request $req, DocumentTableService $documentTypeService)
    {
        // First, validate the request data to ensure 'type' is provided
        $validatedData = $req->validate([
            'type' => 'required|string', // Add other validation rules as needed
        ]);

        // Call a method of the service to create the document type
        $result = $documentTypeService->createDocumentType($validatedData['type']);

        // Assuming the service returns a boolean indicating success or failure
        if ($result->wasRecentlyCreated) {
            session()->flash('toastr', ['type' => 'success', 'message' => 'Document Type Created Successfully']);
            return redirect('/document_type');
        } else {
            session()->flash('toastr', ['type' => 'warning', 'message' => 'Duplicate Document Type Found']);

            return redirect('/document_type')->with('error', 'Table already exists.');
        }
    }

    public function add_document_first()
    {
        $doc_type = Master_doc_type::orderBy('name')->get();
        $sets = Set::get();
        $states = State::all();
        $categories = Category::with('subcategories')->get();
        return view('pages.documents.add_document_first', ['doc_type' => $doc_type, 'sets' => $sets, 'states' => $states, 'categories' => $categories]);
    }

    public function documentCreationContinue(Request $req)
    {
        // Extracting parameters from the request
        $tableName = $req->input('table_name');
        $id = $req->input('id');
        $document_data = $req->input('document_data');

        // Retrieve document metadata
        $columnMetadata = Table_metadata::where('table_name', $tableName)
            ->get();

        // Retrieve the actual document data
        $documentData = DB::table($tableName)->where('doc_id', $document_data->doc_id)->first();

        // Render the view with all the necessary data
        return view('pages.documents.document-creation-continue', [
            'columnMetadata' => $columnMetadata,
            'documentData' => $documentData,
            'table_name' => $tableName,
            'doc_id' => $id,
            'document_data' => $documentData,

        ]);
    }

    public function add_document1(Request $req)
    {

        // Log::info('Request Data: ', $req->all());
        $tableName = $req->type;
        $master_doc_id = $req->master_doc_id;

        if (Schema::hasTable($tableName)) {
            $columns = Schema::getColumnListing($tableName);
        }
        $existingRecord = DB::table($tableName)->where('doc_id', $master_doc_id)->first();
        // Prepare the data for update or insert
        $updateData = ['doc_type' => $tableName];
        foreach ($columns as $column) {
            if (!in_array($column, ['id', 'created_at', 'updated_at', 'status', 'doc_type', 'doc_id', 'document_name'])) {
                if ($req->hasFile($column)) {
                    $file_paths = [];
                    foreach ($req->file($column) as $input) {
                        $extension = $input->getClientOriginalExtension();
                        $filename = Str::random(4) . time() . '.' . $extension;
                        $path = $input->move('uploads', $filename);
                        $file_paths[] = 'uploads/' . $filename;
                    }
                    $updateData[$column] = implode(',', $file_paths);
                } elseif ($req->input($column) !== null) {
                    // If there's a new value, update with the new value
                    $updateData[$column] = $req->input($column);
                } elseif ($existingRecord && $existingRecord->$column !== null) {
                    // If no new value and no file is uploaded, keep the existing value
                    $updateData[$column] = $existingRecord->$column;
                }
            }
        }

        if ($existingRecord) {
            // Update the existing record
            // dd($tableName, $updateData);
            $updateResult =   DB::table($tableName)->where('doc_id', $master_doc_id)->update($updateData);
            // Log::info('Update Result: ', ['result' => $updateResult]);
            // dd($documentId->id);
        } else {
            // Insert a new record with the doc_id
            $updateData['doc_id'] = $master_doc_id; // Assuming 'doc_id' is the column name
            $documentId = DB::table($tableName)->insertGetId($updateData); // This is the new
            // dd("insert");
            // Log::info('Insert Result: ', ['id' => $documentId]);
        }
        $documentId = DB::table($tableName)->where('doc_id', $master_doc_id)->value('id');

        // dd($documentId);
        session()->flash('toastr', ['type' => 'success', 'message' => 'Document added successfully']);

        return redirect('/review_doc/' . $tableName . '/' . $documentId);
    }

    public function add_document(Request $req)
    {
            // $req->validate([
            //     'pdf_file_path' => 'file|mimes:pdf,png,jpeg,jpg|max:10240'  // Max file size is 10MB
            // ]);
        
        
        DB::beginTransaction(); // Start the transaction
    
        try {
            $tableName = $req->type;
            $master_doc_id = $req->master_doc_id;
    
            // Log request and document identifiers
            Log::info('Request Data', $req->all());
            Log::info('Table Name', ['tableName' => $tableName]);
            Log::info('Master Document ID', ['master_doc_id' => $master_doc_id]);
    
            // Fetch the document_identifier_id
            $document_identifier_id = DB::table('master_doc_datas')
                ->where('id', $master_doc_id)
                ->value('doc_identifier_id');
    
            Log::info('Document Identifier ID', ['document_identifier_id' => $document_identifier_id]);
    
            if (Schema::hasTable($tableName)) {
                $columns = Schema::getColumnListing($tableName);
                Log::info('Columns in Table', ['columns' => $columns]);
            }
    
            $existingRecord = DB::table($tableName)->where('doc_id', $master_doc_id)->first();
            Log::info('Existing Record', ['existingRecord' => $existingRecord]);
    
            $updateData = ['doc_type' => $tableName];
    
            foreach ($columns as $column) {
                if (!in_array($column, ['id', 'created_at', 'updated_at', 'status', 'doc_type', 'doc_id', 'document_name'])) {
    
                    if ($req->hasFile($column)) {
                        Log::info('Handling File Upload for Column', ['column' => $column]);
    
                        // Ensure file is valid and uploaded without errors
                        if ($req->file($column)->isValid()) {
                            $file_paths = [];
                            $uploadPath = ($column === 'pdf_file_path') ? 'uploads/documents' : 'uploads/other_documents';
    
                            // Delete old files if they exist
                            if ($existingRecord && $existingRecord->$column) {
                                Log::info('Deleting Old Files', ['old_files' => $existingRecord->$column]);
                                $oldFiles = explode(',', $existingRecord->$column);
                                foreach ($oldFiles as $oldFile) {
                                    if (file_exists($oldFile)) {
                                        unlink($oldFile); // Delete old file
                                        Log::info('File Deleted', ['file' => $oldFile]);
                                    }
                                }
                            }
    
                            // Handle the file upload
                            $extension = $req->file($column)->getClientOriginalExtension();
    
                            // Append document_identifier_id to the filename if it exists
                            $fileIdentifier = $document_identifier_id ? $document_identifier_id . '_' : '';
                            $filename = $fileIdentifier . Str::random(4) . time() . '.' . $extension;
    
                            $path = $req->file($column)->move($uploadPath, $filename);
                            Log::info('File Uploaded', ['filename' => $filename, 'path' => $path]);
    
                            $file_paths[] = $uploadPath . '/' . $filename;
    
                            $updateData[$column] = implode(',', $file_paths);
                        } else {
                            Log::error('File upload failed for column', ['column' => $column, 'error' => $req->file($column)->getError()]);
                        }
                    } elseif ($req->input($column) !== null) {
                        Log::info('Updating Input for Column', ['column' => $column, 'value' => $req->input($column)]);
                        $updateData[$column] = $req->input($column);
                    } elseif ($existingRecord && $existingRecord->$column !== null) {
                        Log::info('Using Existing Value for Column', ['column' => $column, 'value' => $existingRecord->$column]);
                        $updateData[$column] = $existingRecord->$column;
                    }
                }
            }
    
            // Log the updateData before insert/update
            Log::info('Update Data', ['updateData' => $updateData]);
    
            // Update or insert the record
            if ($existingRecord) {
                DB::table($tableName)->where('doc_id', $master_doc_id)->update($updateData);
                Log::info('Record Updated', ['table' => $tableName, 'doc_id' => $master_doc_id]);
            } else {
                $updateData['doc_id'] = $master_doc_id; // Assuming 'doc_id' is the column name
                DB::table($tableName)->insertGetId($updateData);
                Log::info('New Record Inserted', ['table' => $tableName, 'doc_id' => $master_doc_id]);
            }
    
            // Commit the transaction if everything is successful
            DB::commit();
    
            // Get the document ID to redirect
            $documentId = DB::table($tableName)->where('doc_id', $master_doc_id)->value('id');
            Log::info('Document ID Retrieved', ['documentId' => $documentId]);
    
            session()->flash('toastr', ['type' => 'success', 'message' => 'Document added successfully']);
            return redirect('/review_doc/' . $tableName . '/' . $documentId);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction if something fails
    
            // Log the error
            Log::error('Document upload failed', ['error' => $e->getMessage()]);
            session()->flash('toastr', ['type' => 'error', 'message' => 'Document upload failed. Please try again.']);
            return redirect()->back();
        }
    }
    
    




    public function update_document(Request $req)
    {
        $id = $req->id;
        $tableName = $req->type;
        $status = $req->status; // Assuming this is passed in the request
        $message = $req->holdReason ?? null; // Assuming the hold message is passed in the request
        // dd($message);
        if (Schema::hasTable($tableName)) {
            $columns = Schema::getColumnListing($tableName);
        }

        $document = DB::table($tableName)->where('id', $id)->first();

        if (!$document) {
            // Handle the case where the document doesn't exist
            return redirect()->back()->withErrors(['error' => 'Document not found']);
        }

        // if ($document->status == 1) {
        //     // Document is already approved, return with an error message
        //     return redirect()->back()->withErrors(['error' => 'Document is already approved']);
        // }

        // Prepare data for updating the individual document table
        $updateData = ['status' => $status];

        // Update the record in the individual document table
        DB::table($tableName)->where('id', $id)->update($updateData);

        // Prepare data for updating the master document table
        $updateDataMaster = ['status_id' => $status];

        // If the status is 'Hold', add additional fields for the message and timestamp

        if ($status == 2) { // Assuming '2' represents the 'Hold' status
            $updateDataMaster['rejection_timestamp'] = now(); // Set the current timestamp
        }
        // dd($message);
        if (($status == 2 || $status == 3)  && !empty($message)) {
            // Update with hold reason if provided
            $updateDataMaster['rejection_message'] = $message;
        }

        // Get the ID of the currently authenticated user
        $userId = auth()->id(); // or Auth::id() if you are using the Facade

        // Include the user ID in the update data for the master document table
        $updateDataMaster['updated_by'] = $userId;
        $updateDataMaster['reviewed_by'] = $userId;
        // Update the status in the master document table using the doc_id from the individual document
        Master_doc_data::where('id', $document->doc_id)->update($updateDataMaster);
        $master_data   =  Master_doc_data::where('id', $id)->first();

        //this log was necessary, as the reviewer was starting the review. so for the backup, the status has been recorded in the database.
        $logData = [
            'document_id' => $document->doc_id,
            'status' => $status,
            'message' => $message ?? null,
            'created_by' => $userId,
            'temp_id' => $master_data->temp_id ?? null, // Assuming temp_id is retrieved from $document
        ];

        DocumentStatusLog::create($logData);

        session()->flash('toastr', ['type' => 'success', 'message' => 'Document status updated successfully']);

        // Redirect back with a success message or to a different page
        return redirect('/review_doc/' . $tableName . '/' . $id . '#docVerification')->with('success', 'Document status updated successfully');
    }
    public function updateStatusMessage(Request $request, $logId)
    {
        // Validate the request
        $validatedData = $request->validate([
            'id' => 'required', // Ensuring 'id' is not empty
            'type' => 'required', // Ensuring 'type' is not empty
            'message' => 'required' // Ensuring 'message' is not empty
        ]);

        // If validation passes, execute the rest of the code
        $log = DocumentStatusLog::findOrFail($logId);
        $log->message = $request->message;
        $log->save();

        // Redirect back to the specific document section with a success message
        return redirect('/review_doc/' . $request->type . '/' . $request->id . '#docVerification')
            ->with('success', 'Document status updated successfully');
    }


    public function add_document_data(Request $req, DocumentService $documentService)
    {
        $result = $documentService->saveDocumentData($req->all());

        if ($result['status'] === 'fail') {
            return back()->withErrors($result['errors'])->withInput();
        }

        // dd($result['status']);
        // Prepare data for the redirection
        $redirectData = [
            'table_name' => $result['table_name'],
            'id' => $result['id'],
            'document_data' => $result['document_data'],
            // Add other data as needed
        ];


        return $this->documentCreationContinue(new Request([
            'table_name' => $result['table_name'],
            'id' => $result['id'],
            'document_data' => $result['document_data'],
        ]));
    }

    public function updateFirstDocumentData(Request $req, $doc_id, DocumentService $documentService)
    {
        $result = $documentService->saveDocumentData($req->all(), $doc_id);

        if ($result['status'] === 'fail') {
            return back()->withErrors($result['errors'])->withInput();
        }

        session()->flash('toastr', ['type' => 'success', 'message' => 'Please fill the other details.']);

        $redirectData = [
            'table_name' => $result['table_name'],
            'id' => $result['id'],
            'document_data' => $result['document_data'],
        ];


        return $this->documentCreationContinue(new Request([
            'table_name' => $result['table_name'],
            'id' => $result['id'],
            'document_data' => $result['document_data'],
        ]));
    }

    public function view_doc_first()
    {
        $doc_type = Master_doc_type::get();
        return view('pages.view_doc_first', ['doc_type' => $doc_type]);
    }


    public function document_field(Request $req, $table = null)
    {
        $doc_types = Master_doc_type::all();
        $tableName = $table ?? $req->type ?? null;

        if (!$tableName || !Schema::hasTable($tableName)) {
            abort(404, 'Table not found.');
        }

        // Fetch the column details from `table_metadata` for the given table
        $columnDetails = Table_metadata::where('table_name', $tableName)
            ->orderBy('column_name')->get(['column_name', 'data_type', 'special']);

        return view('pages.documents.document_field', [
            'tableName' => $tableName,
            'columnDetails' => $columnDetails,
        ]);
    }
    public function add_document_field(Request $req)
    {
        $type = strtolower($req->type);
        $fields = $req->fields; // Array of fields
        $fieldType = $req->field_type; // Array of field types corresponding to the fields
        $duplicateColumns = [];
        $documentType = Master_doc_type::where('name', $type)->lockForUpdate()->first(); // Lock the row for update
        $table_id = $documentType->id;
        $special = $req->has('specialCheckbox') ? 1 : 0;

        if (!Schema::hasTable($type) && !$documentType->id) {
            session()->flash('toastr', ['type' => 'warning', 'message' => 'Table does not exist.']);
            return redirect('/document_field')->with('error', 'Table does not exist.');
        }

        $columns = Schema::getColumnListing($type);
        $existingMetadataColumns = Table_metadata::where('table_name', $type)->pluck('column_name')->toArray();
        $allExistingColumns = array_merge($columns, $existingMetadataColumns);

        foreach ($fields as $index => $field) {
            $columnName = strtolower(str_replace(' ', '_', $field));
            if (in_array($columnName, $allExistingColumns)) {
                $duplicateColumns[] = $columnName;
            }
        }

        if (!empty($duplicateColumns)) {
            $duplicates = implode(', ', $duplicateColumns);
            session()->flash('toastr', ['type' => 'error', 'message' => "Duplicate columns: {$duplicates}."]);
            return redirect('/document_field' . '?type=' . $type)->with('error', "Duplicate columns: {$duplicates}.");
        }

        // Perform the schema changes outside of a transaction
        Schema::table($type, function (Blueprint $table) use ($type, $fields, $fieldType, $table_id, $allExistingColumns) {
            foreach ($fields as $index => $field) {
                $columnName = strtolower(str_replace(' ', '_', $field));
                if (!in_array($columnName, $allExistingColumns)) {
                    $table->text($columnName)->nullable();
                }
            }
        });

        // Begin the transaction for metadata insertion
        DB::beginTransaction();
        try {
            foreach ($fields as $index => $field) {
                $columnName = strtolower(str_replace(' ', '_', $field));
                if (!in_array($columnName, $allExistingColumns)) {
                    // Insert the new column details into table_metadata within the transaction
                    Table_metadata::insert([
                        'table_name' => $type,
                        'table_id' => $table_id,
                        'column_name' => $columnName,
                        'special' => $special,
                        'data_type' => $fieldType[$index],
                        'created_by' => Auth::user()->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            DB::commit(); // Only commit the metadata transactions
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect('/document_field' . '?type=' . $type)->with('error', 'An error occurred while adding fields.');
        }

        session()->flash('toastr', ['type' => 'success', 'message' => 'Fields added successfully.']);
        return redirect('/document_field' . '?type=' . $type)->with('success', 'Columns added successfully.');
    }

    public function updateDocumentFieldName(Request $request, $tableName, $oldColumnName)
    {
        // Validate the request
        $validated = $request->validate([
            'newFieldName' => 'required|string|max:255',
        ]);

        $newColumnName = str_replace(' ', '_', $validated['newFieldName']);

        // Check if the special checkbox is checked
        $special = $request->has('specialCheckbox') ? 1 : 0;

        // Update special column in metadata first
        Table_metadata::where('table_name', $tableName)
            ->where('column_name', $oldColumnName)
            ->update(['special' => $special]);
        if (!Schema::hasTable($tableName)) {
            return back()->with('error', 'Table does not exist.');
        }

        if (!Schema::hasColumn($tableName, $oldColumnName)) {
            return back()->with('error', 'Column does not exist.');
        }

        if (Schema::hasColumn($tableName, $newColumnName)) {
            return back()->with('error', 'New column name already exists.');
        }

        $columnType = $this->getColumnType($tableName, $oldColumnName);
        // Check if the special checkbox is checked

        try {

            // Log::info('Preparing to rename column in table ' . $tableName);

            // Rename the column outside of transaction due to possible implicit commit
            DB::statement("ALTER TABLE `$tableName` CHANGE `$oldColumnName` `$newColumnName` $columnType");
            // Log::info('Starting transaction for table ' . $tableName);
            DB::beginTransaction();

            // Update metadata within the transaction
            Table_metadata::where('table_name', $tableName)
                ->where('column_name', $oldColumnName)
                ->update(['column_name' => $newColumnName]);

            DB::commit();
            // Log::info('Transaction committed for table ' . $tableName);

            return back()->with('success', 'Field name updated successfully.');
        } catch (\Exception $e) {
            // Only roll back if a transaction is active
            if (DB::transactionLevel() > 0) {
                Log::error('Rolling back transaction for table ' . $tableName);
                DB::rollBack();
            }

            // Log::error('Error while renaming column in table ' . $tableName . ': ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating the field name. ' . $e->getMessage());
        }
    }




    private function getColumnType($tableName, $columnName)
    {
        // Retrieve the column type directly from the information schema
        $columnData = DB::selectOne(
            "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
                                      WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?",
            [env('DB_DATABASE'), $tableName, $columnName]
        );

        // Return the column type or null if not found
        return $columnData ? $columnData->COLUMN_TYPE : null;
    }




    public function updateDocumentFieldName1(Request $request, $tableName, $oldColumnName)
    {
        // Validate the request
        // dd($oldColumnName);
        $validated = $request->validate([
            'newFieldName' => 'required|string|max:255',
        ]);

        // Check if the table and old column exist
        if (!Schema::hasTable($tableName)) {
            return back()->with('error', 'Table does not exist.');
        }

        if (!Schema::hasColumn($tableName, $oldColumnName)) {
            return back()->with('error', 'Column does not exist.');
        }

        Schema::table($tableName, function ($table) use ($oldColumnName, $validated) {
            $table->renameColumn($oldColumnName, $validated['newFieldName']);
        });

        // Rename the column

        try {
            DB::beginTransaction();

            // Rename the column using raw SQL statement
            Schema::table($tableName, function ($table) use ($oldColumnName, $validated) {
                $table->renameColumn($oldColumnName, $validated['newFieldName']);
            });

            // Clear table cache to prevent issues with column not found errors
            // DB::statement('FLUSH TABLES');

            // Update the column name in table_metadata
            Table_metadata::where('table_name', $tableName)
                ->where('column_name', $oldColumnName)
                ->update(['column_name' => $validated['newFieldName']]);

            DB::commit();

            return back()->with('success', 'Field name updated successfully.');
        } catch (\Exception $e) {


            // Log the error or handle it as per your application's error handling policies
            return back()->with('error', 'An error occurred while updating the field name. ' . $e->getMessage());
        }
    }

    //  * Edit Document Basic Details
    //  * 
    //  * Retrieves and displays the editing interface for basic details of a specific document.
    //  * This function checks if the requested document exists and verifies its status before proceeding.
    //  * Only documents with a status other than 1 are accessible; otherwise, a 403 Forbidden error is returned.
    //  * 
    //  * @param int $id The unique identifier of the document to be edited.
    //  * @return \Illuminate\Http\Response Returns a view with the document's basic details for editing if the document exists and is accessible.
    //  * If the document's status_id is 1 or if the document does not exist, it returns a 403 Forbidden error page.

    public function edit_document_basic_detail($id)
    {
        $doc_type = Master_doc_type::orderBy('name')->get();

        // Retrieve the document by id
        $document = Master_doc_data::where('id', $id)->first();
        $categories = Category::with('subcategories')->get();
        // If document not found or status_id is 1, show error page
        if (!$document || $document->status_id == 1) {
            // Use abort(404) if you want a "Not Found" response
            // or abort(403) for a "Forbidden" response, depending on your preference
            return abort(403); // Or use abort(404) for a "Not Found" response
        }

        // Proceed if document exists and status_id is not 1
        $states = State::all();
        $sets = Set::all();
        // Split comma-separated IDs into arrays
        $selectedCategories = explode(',', $document->category_id);
        $selectedSubcategories = explode(',', $document->subcategory_id);
        //  dd($selectedCategories, $selectedSubcategories);
        return view('pages.documents.edit_document_first', [
            'doc_type' => $doc_type,
            'document' => $document,
            'sets' => $sets,
            'states' => $states,
            'categories' => $categories,
            'selectedCategories' => $selectedCategories,
            'selectedSubcategories' => $selectedSubcategories,
        ]);
    }
    public function review_doc($table, $id)
    {
        $tableName = $table;
        if (Schema::hasTable($tableName)) {
            $columnMetadata = Table_metadata::where('table_name', $tableName)
                ->orWhere('column_name', 'special') // Include "special" column metadata
                ->get()
                ->keyBy('column_name'); // This will help you to easily find metadata by column name.
        }

        //     dd($columnMetadata);
        $master_doc_type = Master_doc_type::where("name", $tableName)->first();
        $document = DB::table($tableName)->where('id', $id)->first();
        // dd($document);
        $get_document_master_data = Master_doc_data::where('id', $document->doc_id)->first();
        $document_logs = DocumentStatusLog::where("document_id", $document->doc_id)
            ->join('users', 'document_status_logs.created_by', '=', 'users.id')
            ->select('document_status_logs.*', 'users.name as creator_name')
            ->get();

        // Fetch category and subcategory names
        $categoryNames = [];
        $subcategoryNames = [];

        if (!empty($get_document_master_data->category_id)) {
            $categoryIds = explode(',', $get_document_master_data->category_id);
            $categoryNames = DB::table('categories')
                ->whereIn('id', $categoryIds)
                ->pluck('name', 'id');
        }

        if (!empty($get_document_master_data->subcategory_id)) {
            $subcategoryIds = explode(',', $get_document_master_data->subcategory_id);
            $subcategoryNames = DB::table('subcategories')
                ->whereIn('id', $subcategoryIds)
                ->pluck('name', 'id');
        }
        // dd($get_document_logs);
        // Since SQL stores set_id as text, ensure the IDs are cast to string if they are not already
        $set_ids = json_decode($get_document_master_data->set_id, true) ?? [];
        $set_ids = array_map('strval', $set_ids);
        $masterDataEntries = Master_doc_data::all()->filter(function ($entry) use ($set_ids, $document) {
            $entrySetIds = json_decode($entry->set_id, true);
            // Check if $entrySetIds is an array
            if (!is_array($entrySetIds)) {
                $entrySetIds = []; // Assign an empty array if it's not already an array
            }
            return count(array_intersect($set_ids, $entrySetIds)) > 0 && $entry->id != $document->doc_id;
        });

        //  dd($masterDataEntries);
        $matchingData[] = null;
        foreach ($masterDataEntries as $entry) {
            $tableName1 = $entry->document_type_name;
            // Fetch data from the respective table using doc_id
            $data = DB::table($tableName1)
                ->where('doc_id', $entry->id)
                ->first();

            if ($data) {
                // Add the data to the matchingData array
                $matchingData[] = $data;
            } else {
                $matchingData[] = null;
            }
        }
        //    dd($matchingData);
        $compliances = Compliance::with(['documentType', 'document'])->where('doc_id', $document->doc_id)->orderBy('created_at', 'desc')
            ->get();
        $assigned_docs = Document_assignment::with(['documentType', 'document'])->where('doc_id', $document->doc_id)->orderBy('created_at', 'desc')
            ->get();
        $receiverTypes = Receiver_type::where('status', 1)->get();
        $assigned_advocate_docs =   Advocate_documents::with(['advocate',  'document'])->where('doc_id', $document->doc_id)->orderBy('created_at', 'desc')->get();
        $advocates = Advocate::orderBy('created_at', 'desc')->get();
        $documentTransactions = Document_transaction::where('doc_id', $document->doc_id)
            ->with('creator') // Eager load the creator relationship
            ->get();
        // dd($categoryNames, $subcategoryNames);
        // dd($master_doc_type->id);
        return view('pages.documents.review_doc', [
            'columnMetadata' => $columnMetadata,
            'document' => $document,
            'tableName' => $tableName,
            'id' => $id,
            'master_data' => $get_document_master_data,
            'matchingData' => $matchingData,
            'compliances' => $compliances,
            'document_logs' => $document_logs,
            'document_id' => $document->doc_id,
            'doc_type' => $master_doc_type,
            'documentAssignments' => $assigned_docs,
            'receiverTypes' => $receiverTypes,
            'assigned_advocate_docs' => $assigned_advocate_docs,
            'advocates' => $advocates,
            'document_transactions' => $documentTransactions,
            'categoryNames' => $categoryNames,
            'subcategoryNames' => $subcategoryNames,
        ]);
    }




    public function viewUploadedDocuments()
    {
        $basePath = base_path();
        // dd($basePath);
        // Construct the path to the public/uploads directory relative to the base path
        $basePath = '/home4/kodstecu/ahobila.kods.app';
        $uploadsPath = $basePath . '/uploads/documents';
        // Get the path to the public/uploads directory
        // $uploadsPath = public_path('uploads/documents');

        // Check if the uploads directory exists
        if (!File::exists($uploadsPath)) {
            return 'Uploads directory does not exist.';
        }

        // Get the list of files in the uploads directory
        $documents = File::files($uploadsPath);

        // Initialize an array to store file information
        $fileInfoList = [];
        // dd($documents);
        // Iterate over each file to extract information
        foreach ($documents as $file) {
            // Get the file name
            $filename = $file->getFilename();

            // Get the file size
            $size = $file->getSize();
            $extension = $file->getExtension();

            // Get the last modified time (uploaded date)
            $uploadedDate = $file->getMTime();

            // Push the file information into the array
            $fileInfoList[] = [
                'name' => $filename,
                'size' => $size,
                'extension' => $extension,
                'uploaded_date' => date('H:i:s m-d-Y', $uploadedDate) // Format the date as needed
            ];
        }

        return view('pages.documents.uploaded-documents', compact('fileInfoList'));
    }

    public function deleteFile($filename)
    {
        $filePath = public_path('uploads/documents/' . $filename);

        if (File::exists($filePath)) {
            File::delete($filePath);
            return redirect()->back()->with('success', 'File deleted successfully.');
        }

        return redirect()->back()->with('error', 'File not found.');
    }


    public function uploadFiles(Request $request)
    {
        // Validate the uploaded files
        $request->validate([
            'files.*' => 'required|file|mimes:pdf|max:500000', // Max size in bytes (500 MB)
        ]);
        Log::info('File upload request received');
        // Process the uploaded files
        if ($request->hasFile('files')) {
            $file_paths = [];
            foreach ($request->file('files') as $file) {
                $originalFilename = $file->getClientOriginalName(); // Get the original filename
                $path = $file->move('uploads/documents', $originalFilename); // Store the file in the specified directory
                $file_paths[] = $path;
            }

            // Optionally, you can save the file paths to a database or perform additional processing here

            // Return a success response
            session()->flash('toastr', ['type' => 'success', 'message' => 'Document uploaded successfully']);
            return response()->json(['message' => 'Files uploaded successfully', 'file_paths' => $file_paths], 200);
        }
        // Return an error response if no files were uploaded
        return response()->json(['message' => 'No files uploaded'], 400);
    }
    public function storeTransaction(Request $request)
    {
        Log::info("asdding the transaction has been", ['data', $request->all()]);
        $request->validate([
            'doc_id' => 'required|integer',
            'transaction_type' => 'required|in:taken,returned',
            'notes' => 'nullable|string',
        ]);

        // Check for existing transactions with the same doc_id that are not returned
        $existingTransaction = Document_transaction::where('doc_id', $request->doc_id)
            ->where('transaction_type', '!=', 'returned')
            ->first();
        if ($existingTransaction) {
            // Return response indicating the document needs to be settled first
            session()->flash('toastr', ['type' => 'error', 'message' => 'A document log already exists that is not returned. Please settle the previous transaction first.']);
            return redirect()->back()->withErrors('A document log already exists that is not returned. Please settle the previous transaction first.');
        }

        $transaction = Document_transaction::create([
            'doc_id' => $request->doc_id,
            'created_by' => Auth::user()->id,
            'transaction_type' => $request->transaction_type,
            'notes' => $request->notes,
        ]);
        $masterDocData = Master_doc_data::where('id', $request->doc_id)->first();
        if ($masterDocData) {
            $masterDocData->transaction_type = $request->transaction_type;
            $masterDocData->save();
        }
        session()->flash('toastr', ['type' => 'success', 'message' => 'Transaction created successfully']);

        return redirect()->back()->with('success', 'Transaction created successfully.');
    }
    public function updateTransaction(Request $request, $id)
    {
        $request->validate([
            'transaction_type' => 'required|in:taken,returned',
            'notes' => 'nullable|string',
        ]);

        $transaction = Document_transaction::findOrFail($id);
        $transaction->update([
            'transaction_type' => $request->transaction_type,
            'notes' => $request->notes,
        ]);
          // Update the transaction_type in the master_doc_data table
    $masterDocData = Master_doc_data::where('id', $transaction->doc_id)->first();
    if ($masterDocData) {
        $masterDocData->transaction_type = $request->transaction_type;
        $masterDocData->save();
    }
        session()->flash('toastr', ['type' => 'success', 'message' => 'Transaction updated successfully']);

        return redirect()->back()->with('success', 'Transaction updated successfully.');
    }


    public function getDocumentTransactionById($id)
    {
        Log::info("edit document transaction detail", ['id' => $id]);
        $transaction = Document_transaction::find($id);
        if (!$transaction) {
            return response()->json(['error' => 'Document transaction not found.'], 404);
        }

        return response()->json($transaction);
    }
    public function fetchDistricts($state)
    {
        try {
            // Fetch the data from the database
            $districtsData = Master_doc_data::where('current_state', $state)
                ->pluck('current_district'); // Use pluck to get the column directly

            // Initialize an empty collection to store districts
            $districts = collect();

            // Process each district data
            foreach ($districtsData as $data) {
                // Split by comma and trim spaces
                $splitDistricts = collect(explode(',', $data))->map(function ($item) {
                    return Str::of($item)->trim();
                });

                // Merge with the main districts collection
                $districts = $districts->merge($splitDistricts);
            }

            // Remove duplicates, sort, and reject empty/null values
            $districts = $districts->unique()
                ->sort()
                ->reject(function ($value) {
                    $stringValue = (string) $value;
                    return $stringValue === '' || is_null($stringValue);
                })
                ->values();

            // Log the districts array for debugging
            // Log::info('Fetched districts', ['districts' => $districts]);

            // Return the response as JSON
            return Response::json($districts);
        } catch (\Exception $e) {
            // Log any exception that occurs
            Log::error('Error fetching districts', ['error' => $e->getMessage()]);
            return Response::json(['error' => 'An error occurred while fetching districts'], 500);
        }
    }

    public function fetchVillages($district)
    {
        try {
            // Fetch the data from the database
            $villagesData = Master_doc_data::where('current_district', $district)
                ->pluck('current_village'); // Use pluck to get the column directly

            // Initialize an empty collection to store villages
            $villages = collect();

            // Process each village data
            foreach ($villagesData as $data) {
                // Split by comma and trim spaces
                $splitVillages = collect(explode(',', $data))->map(function ($item) {
                    return Str::of($item)->trim();
                });

                // Merge with the main villages collection
                $villages = $villages->merge($splitVillages);
            }

            // Remove duplicates, sort, and reject empty/null values
            $villages = $villages->unique()
                ->sort()
                ->reject(function ($value) {
                    $stringValue = (string) $value;
                    return $stringValue === '' || is_null($stringValue);
                })
                ->values();

            // Log the villages array for debugging
            // Log::info('Fetched villages', ['villages' => $villages]);

            // Return the response as JSON
            return Response::json($villages);
        } catch (\Exception $e) {
            // Log any exception that occurs
            Log::error('Error fetching villages', ['error' => $e->getMessage()]);
            return Response::json(['error' => 'An error occurred while fetching villages'], 500);
        }
    }
    public function fetchDistrictsForSold($state)
    {
        try {
            // Fetch the data from the database
            $districtsData = Sold_land::where('state', $state)
                ->pluck('district'); // Use pluck to get the column directly

            // Initialize an empty collection to store districts
            $districts = collect();

            // Process each district data
            foreach ($districtsData as $data) {
                // Split by comma and trim spaces
                $splitDistricts = collect(explode(',', $data))->map(function ($item) {
                    return Str::of($item)->trim();
                });

                // Merge with the main districts collection
                $districts = $districts->merge($splitDistricts);
            }

            // Remove duplicates, sort, and reject empty/null values
            $districts = $districts->unique()
                ->sort()
                ->reject(function ($value) {
                    $stringValue = (string) $value;
                    return $stringValue === '' || is_null($stringValue);
                })
                ->values();

            // Log the districts array for debugging
            // Log::info('Fetched districts', ['districts' => $districts]);

            // Return the response as JSON
            return Response::json($districts);
        } catch (\Exception $e) {
            // Log any exception that occurs
            Log::error('Error fetching districts', ['error' => $e->getMessage()]);
            return Response::json(['error' => 'An error occurred while fetching districts'], 500);
        }
    }

    public function fetchVillagesForSold($district)
    {
        try {
            // Fetch the data from the database
            $villagesData = Sold_land::where('district', $district)
                ->pluck('village'); // Use pluck to get the column directly

            // Initialize an empty collection to store villages
            $villages = collect();

            // Process each village data
            foreach ($villagesData as $data) {
                // Split by comma and trim spaces
                $splitVillages = collect(explode(',', $data))->map(function ($item) {
                    return Str::of($item)->trim();
                });

                // Merge with the main villages collection
                $villages = $villages->merge($splitVillages);
            }

            // Remove duplicates, sort, and reject empty/null values
            $villages = $villages->unique()
                ->sort()
                ->reject(function ($value) {
                    $stringValue = (string) $value;
                    return $stringValue === '' || is_null($stringValue);
                })
                ->values();

            // Log the villages array for debugging
            // Log::info('Fetched villages', ['villages' => $villages]);

            // Return the response as JSON
            return Response::json($villages);
        } catch (\Exception $e) {
            // Log any exception that occurs
            Log::error('Error fetching villages', ['error' => $e->getMessage()]);
            return Response::json(['error' => 'An error occurred while fetching villages'], 500);
        }
    }

    public function showDocumentTransactions()
    {
        // Log::info(["document transaction", []]);

        $documentTransactions = Document_transaction::with('creator')->orderBy('created_at', 'desc')->paginate(10);
        // dd($documentTransactions);
        foreach ($documentTransactions as $transaction) {
            // Fetch the document type name dynamically based on the doc_id
            $masterDocData = Master_doc_data::where('id', $transaction->doc_id)->first();

            if ($masterDocData) {
                // Build the table name dynamically
                $transaction->document_name = $masterDocData->name;
                $transaction->document_type_name = $masterDocData->document_type_name;
                $childDocument = DB::table($masterDocData->document_type_name)
                    ->where('doc_id', $transaction->doc_id)
                    ->first();

                if ($childDocument) {
                    $transaction->child_id = $childDocument->id;
                }
            }
        }

        // Log::info(["document transaction", $documentTransactions]);
        return view('pages.documents.document-logs', compact('documentTransactions'));
    }
}

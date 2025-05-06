<?php
// File: app/Services/BulkUploadService.php

namespace App\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\LazyCollection;
use App\Models\Table_metadata;
use App\Models\Master_doc_data;
use App\Services\DocumentTableService;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;




class BulkUploadService
{
    protected $documentTypeService;

    public function __construct()
    {
        $this->documentTableService = new DocumentTableService();
    }


    public function handleUpload($path)
    {
      
        $stats = [
            'total' => 0,
            'inserted' => 0,
            'updated' => 0,
            'not_used' => 0,  // Assuming 'not_used' means skipped due to validation or other reasons
        ];

        $batchId = (string) Str::uuid();
        // Open the file
        $handle = fopen($path, 'r');

        // Create a LazyCollection to process the CSV
        LazyCollection::make(function () use ($handle) {
            while (($line = fgetcsv($handle)) !== false) {
                yield $line;
            }
        })
            ->skip(2) // Skip the header of the CSV file.
            ->chunk(10000) // Process in chunks of 1000 rows.
            ->each(function ($chunk) use (&$stats, $batchId) {
                // Prepare an array to hold all rows for batch insertion.
                $rowsToInsert = [];

                foreach ($chunk as $row) {
                    // Trim leading and trailing spaces from each element in the row
                    $trimmedRow = array_map('trim', $row);
                    // Check for scripts or potentially harmful content
                    if ($this->containsScript($trimmedRow)) {
                        // Log or handle the detection of harmful content
                        continue; // Skip this row
                    }
                    // Apply your validation and logic here.
                    if ($trimmedRow[6]) {

                        $processedRow = $this->processRow($trimmedRow, $batchId);
                    } else {
                        $stats['not_used']++;
                    }
                    if ($processedRow) {
                        $rowsToInsert[] = $processedRow;
                    }
                }

                // Perform the batch insert. very efficient
                // dd($rowsToInsert);
                $masterDocData =  Master_doc_data::upsert($rowsToInsert, ['temp_id']);

                $stats['total'] += count($rowsToInsert);
                // Assuming all operations are insertions for simplicity
                // $stats['inserted'] += count($rowsToInsert);

            });

        // Close the file handle
        fclose($handle);
        //the below function calls and find the data which is not added in the subsequent table, and refresh the sub table with the data

        $dynamicStats =  $this->insertIntoDynamicTables($batchId);
        // Combine the stats
        // dd($stats, $dynamicStats);
        $stats['inserted'] += $dynamicStats['inserted'];
        // $stats['updated'] += $dynamicStats['updated'];
        $stats['updated'] += $dynamicStats['updated'];
        // dd($stats);
        //$stats results are coming wrong, need to work on that.
        return $stats;
    }

    // Function to check if a row contains script or potentially harmful content
    private function containsScript($row)
    {
        foreach ($row as $value) {
            if (preg_match('/<\s*script\s*>/', $value)) {
                return true;
            }
        }
        return false;
    }

    protected function insertIntoDynamicTables($batchId)
    {
        $dynamicStats = [
            'inserted' => 0,
            'updated' => 0,
            'not_used' => 0,
        ];

        // Retrieve all distinct table names from the `master_doc_data` table for the given batch_id
        $tableNames = Master_doc_data::where('batch_id', $batchId)
            ->distinct()
            ->pluck('document_type_name');

        foreach ($tableNames as $tableName) {
            if (Schema::hasTable($tableName)) {
                // Get the relevant records from `master_doc_data` for this table and batch_id
                $records = Master_doc_data::where('document_type_name', $tableName)
                    ->where('batch_id', $batchId)
                    ->get();

                foreach ($records as $record) {
                    // Check if a record with the same doc_id exists in the dynamic table
                    $existingRecord = DB::table($tableName)->where('doc_id', $record->id)->first();

                    if ($existingRecord) {
                        // The record exists, so we'll increment the 'updated' count.
                        // Update the existing record with new data if necessary
                        DB::table($tableName)->where('doc_id', $record->id)->update([
                            // ... include fields that should be updated
                            'pdf_file_path' => "uploads/documents/" . $record->temp_id . ".pdf",
                        ]);
                        $dynamicStats['updated']++;
                    } else {
                        // The record does not exist, it's a new insertion.
                        DB::table($tableName)->insert([
                            'doc_id' => $record->id,
                            'doc_type' => $tableName,
                            'document_name' => $record->name,
                            'pdf_file_path' => "uploads/documents/" . $record->temp_id . ".pdf",
                            // ... include other fields that should be inserted
                        ]);
                        $dynamicStats['inserted']++;
                    }
                }
            } else {
                // Optionally handle the case where the table does not exist
                $dynamicStats['not_used']++;
            }
        }

        return $dynamicStats;
    }

    //the dates were not added properly in the excel, so the function convertes into formatted


    //   child bulk upload start
    protected function processRow($row, $batchId)
    {
        // dd($row);
        $dateFormats = ['d-m-Y', 'd/m/Y'];
        $formattedDate = null;
        foreach ($dateFormats as $format) {
            try {
                $formattedDate = Carbon::createFromFormat($format, trim($row[19]))->toDateString();
                continue; // Format matched, break out of the loop
            } catch (\Exception $e) {
                // Catch the exception and continue trying other formats
            }
        }

        // Assign a code based on the unit
        // $unit = strtolower(trim($row[21]));
        // $unitCode = null;
        // if (strpos($unit, 'acres') !== false) {
        //     $unitCode = 1;
        // } elseif (strpos($unit, 'square') !== false) {
        //     $unitCode = 2;
        // }
        $document_type_name = strtolower(str_replace(' ', '_', $row[6]));

        // Find existing record by temp_id
        $existingRecord = Master_doc_data::where('temp_id', $row[1])->first();
        $setsInput = $row[26];

        // Clean up the input by removing extra spaces and exploding it into an array
        $setsArray = array_map('trim', explode(',', $setsInput));

        // Filter out any empty values or convert 'null' to null
        $setsArray = array_filter($setsArray, function ($value) {
            return $value !== '' && strtolower($value) !== 'null';
        });

        // If there are no valid sets, set $setsArray to an empty array
        if (empty($setsArray)) {
            $setsArray = [];
        }

        // If you want to add double quotes around each value in the array, you can use array_map again
        $setsArray = array_map(function ($value) {
            return '"' . intval($value) . '"';
        }, $setsArray);

        $setsJson = '[' . implode(',', $setsArray) . ']'; // Join array elements into a JSON array

        $data = [
            'temp_id' => $row[1],
            'name' => $row[2],
            'location' => $row[3],
            'locker_id' => isset($row[4]) ? (int) $row[4] : null,
            'number_of_page' => isset($row[5]) ? (int) $row[5] : null,
            'document_type_name' => $document_type_name,
            'current_state' => isset($row[7]) && !empty(trim($row[7])) ? $row[7] : 'N/A',
            'state' => isset($row[8]) && !empty(trim($row[8])) ? $row[8] : 'N/A',
            'alternate_state' => isset($row[9]) && !empty(trim($row[9])) ? $row[9] : 'N/A',
            'current_district' => isset($row[10]) && !empty(trim($row[10])) ? $row[10] : 'N/A',
            'district' => isset($row[11]) && !empty(trim($row[11])) ? $row[11] : 'N/A',
            'alternate_district' =>  isset($row[12]) && !empty(trim($row[12])) ? $row[12] : 'N/A',
            'current_taluk' => isset($row[13]) && !empty(trim($row[13])) ? $row[13] : 'N/A',
            'taluk' => isset($row[14]) && !empty(trim($row[14])) ? $row[14] : 'N/A',
            'alternate_taluk' => isset($row[15]) && !empty(trim($row[15])) ? $row[15] : 'N/A',
            'current_village' => isset($row[16]) && !empty(trim($row[16])) ? $row[16] : 'N/A',
            'village' => isset($row[17]) && !empty(trim($row[17])) ? $row[17] : 'N/A',
            'alternate_village' => isset($row[18]) && !empty(trim($row[18])) ? $row[18] : 'N/A',
            'issued_date' => $formattedDate,
            'area' => $row[20],
            'unit' =>  strtolower(trim($row[21])),
            'dry_land' => $row[22],
            'wet_land' => $row[23],
            'garden_land' => $row[24],
            'old_locker_number' => $row[25],
            'set_id' => $setsJson,
            'physically' => $row[27],
            'category' => $row[29],
            'survey_no' => $row[30],
            'doc_no' => $row[31],
            //     'advocate_name' => $row[32],
            // 'court_case_no' => $row[33],
            //     'case_status' => $row[34],
            //         'case_result' => $row[35],
                    // 'category_id' => $row[37],
            'bulk_uploaded' => 1,
            'created_by' => Auth::user()->id,
            'batch_id' => $batchId,
        ];

        if ($existingRecord) {
            // If record exists, update it
            //updating the document_type_name will give error, as it will search for the document_type and the table too on view document page
            // dd($data);
            unset($data['document_type_name']);
            $existingRecord->update($data);
        } else {
            // If record does not exist, create it
            $documentType = $this->documentTableService->createDocumentType($document_type_name);
            $data['document_type'] = $documentType->id;
            Master_doc_data::create($data);
        }

        return null; // Return null to indicate no new row to be inserted
    }

    public function handleChildUpload($path)
    {
        $collections = Excel::toCollection(null, $path);

        // Assuming there is only one sheet and the first row contains headers
        $headers = $collections[0][1]; // First row of the first sheet is headers
        $rows = $collections[0]->slice(2); // Rest of the rows with actual data

        foreach ($rows as $row) {
            $tempId = $row[1]; // Replace 0 with the index of temp_id in your file
            $masterRecord = Master_doc_data::where('temp_id', $tempId)->first();

            if ($masterRecord) {
                $tableName = $masterRecord->document_type_name;
                $tableId = $masterRecord->document_type;
                if (Schema::hasTable($tableName)) {
                    // Check and create any missing columns in the table
                    $this->checkAndCreateMissingColumns($tableName, $tableId, $headers);

                    $docId = $masterRecord->id;
                    // Map the Excel row to the table columns
                    $dataToUpdate = $this->mapRowToTableColumns($headers, $row, $docId, $tableName);

                    // Insert or update the record in the child table
                    DB::table($tableName)->updateOrInsert(['doc_id' => $docId], $dataToUpdate);
                }
            }
        }

        // Return some result or status
        return [
            'success' => true,
            // 'inserted' => $numberInserted,
            // 'updated' => $numberUpdated,
            // ... other stats
        ];
    }

    protected function mapRowToTableColumns($headers, $row, $docId, $tableName)
    {
        $mappedData = ['doc_id' => $docId]; // Start with the doc_id

        $tableColumns = Schema::getColumnListing($tableName); // Get current columns in the table

        foreach ($headers as $index => $header) {
            if ($index > 1 && in_array($header, $tableColumns) && isset($row[$index])) {
                $mappedData[$header] = $row[$index];
            }
        }

        return $mappedData;
    }

    protected function checkAndCreateMissingColumns($tableName, $tableId, $headers)
    {
        // Get current columns in the table
        $tableColumns = Schema::getColumnListing($tableName);
        // dd($tableName);
        // Define an array of columns to exclude from creation
        $excludedHeaders = ['sl_no', 'document_id'];
        // dd($headers);
        foreach ($headers as $index => $header) {
            $cleanedHeader = str_replace(' ', '_', trim($header));
            // Skip if the header is in the list of excluded headers
            if (in_array(strtolower($cleanedHeader), array_map('strtolower', $excludedHeaders))) {
                continue;
            }

            // Check if the column exists in the table
            if (!in_array($cleanedHeader, $tableColumns)) {
                // add the column detail in the table_metadata
                Table_metadata::insert([
                    'table_name' => $tableName,
                    'column_name' => $cleanedHeader,
                    'table_id' => $tableId,
                    'data_type' => 1, // Assuming '1' is the default data type
                    'created_by' => Auth::user()->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Column does not exist, create it
                Schema::table($tableName, function (Blueprint $table) use ($cleanedHeader) {
                    $table->text($cleanedHeader)->nullable();
                });
            }
        }
    }
}

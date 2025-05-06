<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\{sold_land};
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Exports\SoldLandExport;
use Maatwebsite\Excel\Facades\Excel;
class SoldLandController extends Controller
{



// Export Sold Land data to Excel
public function exportSoldLand(Request $request)
{
    // Fetch the filtered sold land data, or you can fetch all data based on your needs
    $soldLands = Sold_land::where(function ($query) use ($request) {
        if ($request->input('state')) {
            $query->where('state', $request->input('state'));
        }
        // Add more filter conditions based on the request inputs
    })->get()->toArray();

    // Export the data using the SoldLandExport class
    return Excel::download(new SoldLandExport($soldLands), 'sold_land_data.xlsx');
}

    public function view(Request $request)
    {
        $area_range_start = $request->input('area_range_start');
        $area_range_end = $request->input('area_range_end');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        // Get all sold lands by default
        $query = DB::table('sold_lands');

        // Filter by survey number if provided
        if ($request->has('survey_number')) {
            $query->where('survey_number', 'like', '%' . $request->input('survey_number') . '%');
        }

        // Filter by district if provided
        $query->when($request->filled('state'), function ($query) use ($request) {
            $query->where('state', $request->input('state'));
        });
        $query->when($request->filled('district'), function ($query) use ($request) {
            $query->where('district', $request->input('district'));
        });

        // // Filter by village if provided
        $query->when($request->filled('village'), function ($query) use ($request) {
            $query->where('village', $request->input('village'));
        });



        // dd($start_date);
        if ($start_date && $end_date) {
            // Convert dates to Carbon instances to ensure correct format and handle any timezone issues
            $start = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay(); // Ensures the comparison includes the start of the start_date
            $end = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay(); // Ensures the comparison includes the end of the end_date
            $query->whereBetween('register_date', [$start, $end]);
            // dd($query);
        } elseif ($start_date) {
            // If only start_date is provided
            $start = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay();
            $query->where('register_date', '>=', $start);
        } elseif ($end_date) {
            // If only end_date is provided
            $end = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay();
            $query->where('register_date', '<=', $end);
        }




        $area_range_start  = intval($area_range_start);
        $area_range_end  = intval($area_range_end);
        // dd(232323);
        $area_unit = $request->input('area_unit');
        if ($area_range_start !== null || $area_range_end !== null) {
            $query->where(function ($q) use ($area_range_start, $area_range_end, $area_unit) {
                if ($area_unit) {
                    if ($area_unit === 'Acres') {
                        // dd(23);
                        // dd($area_range_start,$area_range_end);
                        // Search for both acres and cents
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('total_area_unit', 'acres and cents')
                                ->where('total_area', '>=', $area_range_start)
                                ->where('total_area', '<=', $area_range_end);
                        });
                        // Convert acres to square feet and search
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('total_area_unit', 'Square Feet')
                                ->where('total_area', '>=', $area_range_start * 43560)
                                ->where('total_area', '<=', $area_range_end * 43560);
                        });
                    } elseif ($area_unit === 'Square Feet') {
                        // Search for square feet
                        // dd(22);
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('total_area_unit', 'Square Feet')
                                ->where('total_area', '>=', $area_range_start)
                                ->where('total_area', '<=', $area_range_end);
                        });
                        // Convert square feet to acres and cents and search
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('total_area_unit', 'Acres and Cents')
                                ->where('total_area', '>=', $area_range_start / 43560)
                                ->where('total_area', '<=', $area_range_end / 43560);
                        });
                    }
                }
            });
        }


        // Get the filtered sold lands
        $data = $query->orderBy('created_at', 'desc')->get();

        // $data = DB::table('sold_lands')->orderBy('created_at', 'desc')->get();
        $uniqueVillages = DB::table('sold_lands')->whereNotNull('village')->where('village', '<>', '')->distinct()->pluck('village')->toArray();
        $uniqueDistricts = DB::table('sold_lands')->whereNotNull('district')->where('district', '<>', '')->distinct()->pluck('district')->toArray();
        $uniqueStates = DB::table('sold_lands')->whereNotNull('state')->where('state', '<>', '')->distinct()->pluck('state')->toArray();

// dd($uniqueVillages);
        // Log the SQL query being executed
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        Log::info("SQL query", ['query' => $sql, 'bindings' => $bindings]);
        return view('pages.sold-lands.index', [
            'data' => $data,
            'uniqueVillages' => $uniqueVillages,
            'uniqueDistricts' => $uniqueDistricts,
            'uniqueStates' => $uniqueStates,
            'start_date' => $start_date,
        ]);
    }
    public function add()
    {
        $data = DB::table('sold_lands')->orderBy('created_at', 'desc')->get();

        return view('pages.sold-lands.add', [
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'state' => 'nullable|string|max:255',
            'district_number' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'village_number' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'survey_number' => 'nullable|string|max:255',
            'wet_land' => 'nullable|string|max:255',
            'dry_land' => 'nullable|string|max:255',
            'plot' => 'nullable|string|max:255',
            'traditional_land' => 'nullable|string|max:255',
            'total_area' => 'nullable|string|max:255',
            'total_area_unit' => 'nullable|string|max:255',
            'total_wet_land' => 'nullable|string|max:255',
            'total_dry_land' => 'nullable|string|max:255',
            'gap' => 'nullable|string|max:255',
            'sale_amount' => 'nullable|string|max:255',
            'total_sale_amount' => 'nullable|string|max:255',
            'registration_office' => 'nullable|string|max:255',
            'register_number' => 'nullable|string|max:255',
            'register_date' => 'nullable|date',
            'book_number' => 'nullable|string|max:255',
            'name_of_the_purchaser' => 'nullable|string|max:255',
            'balance_land' => 'nullable|string|max:255',
            'remark' => 'nullable|string|max:255',
            'latitude' => ['nullable', 'string', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude' => ['nullable', 'string', 'regex:/^[-]?(([1]?[0-7]?[0-9])\.(\d+))|(180(\.0+)?)$/'],
        ]);

        // Add the 'created_by' field with the current user's ID
        $requestData = $request->all();
        // Check if all fields in the request are empty

        // dd($requestData);

        if ($request->hasFile('pdf_file')) {
            $filePaths = [];
            foreach ($request->file('pdf_file') as $input) {
                $extension = $input->getClientOriginalExtension();
                $filename = Str::random(4) . time() . '.' . $extension;
                $path = $input->move('uploads', $filename);
                $filePaths[] = 'uploads/' . $filename;
            }
            $validatedData['pdf_file'] = implode(',', $filePaths);
        }
        $isEmpty = true;
        foreach ($validatedData  as $key => $value) {
            if ($key !== '_token' && !empty($value)) {
                // At least one field contains data, so set $isEmpty to false and break the loop
                $isEmpty = false;
                break;
            }
        }

        // If all fields are empty, redirect back with an error message
        if ($isEmpty) {
            return redirect()->back()->with('error', 'Please provide data for at least one field.');
        }

        $requestData['created_by'] = Auth::id();

        // Attempt to create the sold land details
        $soldLand = Sold_land::create($validatedData);

        // Redirect or return a response
        session()->flash('toastr', ['type' => 'success', 'message' => 'Sold Land Details Added Successfully']);

        if ($soldLand) {
            // Redirect with success message
            return redirect()->route('soldLand.view')->with('success', 'Sold Land details added successfully.');
        } else {
            // Redirect back to the add route with an error message
            return redirect()->route('soldLand.add')->with('error', 'Failed to add sold land details. Please try again.');
        }
    }


    // Show the form for editing the specified sold land detail
    public function edit($id)
    {
        $soldLand = Sold_land::findOrFail($id);
        return view('pages.sold-lands.add', ['soldLand' => $soldLand]);
    }

    // Update the specified sold land detail in the database
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'state' => 'nullable|string|max:255',
            'district_number' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'village_number' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'survey_number' => 'nullable|string|max:255',
            'wet_land' => 'nullable|string|max:255',
            'dry_land' => 'nullable|string|max:255',
            'plot' => 'nullable|string|max:255',
            'traditional_land' => 'nullable|string|max:255',
            'total_area' => 'nullable|string|max:255',
            'total_area_unit' => 'nullable|string|max:255',
            'total_wet_land' => 'nullable|string|max:255',
            'total_dry_land' => 'nullable|string|max:255',
            'gap' => 'nullable|string|max:255',
            'sale_amount' => 'nullable|string|max:255',
            'total_sale_amount' => 'nullable|string|max:255',
            'registration_office' => 'nullable|string|max:255',
            'register_number' => 'nullable|string|max:255',
            'register_date' => 'nullable|date',
            'book_number' => 'nullable|string|max:255',
            'name_of_the_purchaser' => 'nullable|string|max:255',
            'balance_land' => 'nullable|string|max:255',
            'remark' => 'nullable|string|max:255',
            'latitude' => ['nullable', 'string', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude' => ['nullable', 'string', 'regex:/^[-]?(([1]?[0-7]?[0-9])\.(\d+))|(180(\.0+)?)$/'],
        ]);

        $isEmpty = true;
        foreach ($validatedData as $key => $value) {
            if ($key !== '_token' && !empty($value)) { // Check for null instead of empty string, as null is considered when the field is not present in the request
                $isEmpty = false;
                break;
            }
        }
        if ($isEmpty) {
            return redirect()->back()->with('error', 'Please provide data for at least one field.');
        }



        // Find the sold land detail by ID
        $soldLand = Sold_land::findOrFail($id);

        // Add the 'updated_by' field with the current user's ID
        $validatedData['updated_by'] = Auth::id();

        // Update the sold land detail with the validated data
        $soldLand->update($validatedData);

        // Redirect or return a response
        return view('pages.sold-lands.show', ['soldLands' => $soldLand, 'id' => $id]);

        // return redirect()->route('soldLand.index')->with('success', 'Sold Land details updated successfully.');
    }


    public function storeOrUpdate(Request $request, $id = null)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'state' => 'nullable|string|max:255',
            'district_number' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'village_number' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'survey_number' => 'nullable|string|max:255',
            'wet_land' => 'nullable|string|max:255',
            'dry_land' => 'nullable|string|max:255',
            'plot' => 'nullable|string|max:255',
            'traditional_land' => 'nullable|string|max:255',
            'total_area' => 'nullable|string|max:255',
            'total_area_unit' => 'nullable|string|max:255',
            'total_wet_land' => 'nullable|string|max:255',
            'total_dry_land' => 'nullable|string|max:255',
            'gap' => 'nullable|string|max:255',
            'sale_amount' => 'nullable|string|max:255',
            'total_sale_amount' => 'nullable|string|max:255',
            'registration_office' => 'nullable|string|max:255',
            'register_number' => 'nullable|string|max:255',
            'register_date' => 'nullable|date',
            'sale_date' => 'nullable|date',
            'book_number' => 'nullable|string|max:255',
            'name_of_the_purchaser' => 'nullable|string|max:255',
            'balance_land' => 'nullable|string|max:255',
            'remark' => 'nullable|string|max:255',
            'file' => 'nullable|mimes:pdf|max:10240',
            'latitude' => ['nullable', 'string', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude' => ['nullable', 'string', 'regex:/^[-]?(([1]?[0-7]?[0-9])\.(\d+))|(180(\.0+)?)$/'],
        ]);

        // Check if any field contains data
        $isEmpty = true;
        foreach ($validatedData as $value) {
            if (!empty($value)) {
                // At least one field contains data, so set $isEmpty to false and break the loop
                $isEmpty = false;
                break;
            }
        }

        // If all fields are empty, redirect back with an error message
        if ($isEmpty) {
            return redirect()->back()->with('error', 'Please provide data for at least one field.');
        }

        // Add the 'created_by' field with the current user's ID if creating a new record
        if (is_null($id)) {
            $validatedData['created_by'] = Auth::id();
        } else {
            // If updating an existing record, add the 'updated_by' field with the current user's ID
            $validatedData['updated_by'] = Auth::id();
        }
        // dd($request->file('file'));
        // Handle file upload for PDFs
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // Ensure the uploaded file is valid
            if ($file->isValid()) {
                $extension = $file->getClientOriginalExtension();
                $filename = Str::random(4) . time() . '.' . $extension;
                // Move the uploaded file to the desired location
                $path = $file->move('uploads', $filename);
                // Store the file path in the validated data
                $validatedData['file'] = 'uploads/' . $filename;
            } else {
                // Handle invalid file upload
                return redirect()->back()->with('error', 'Invalid file uploaded.');
            }
        }

        // dd($validatedData['file']);
        // Check if $id is provided to determine if it's an update or create operation
        if (is_null($id)) {
            // Attempt to create the sold land details using validated data
            $soldLand = Sold_land::create($validatedData);
        } else {
            // Find the sold land detail by ID and update it with the validated data
            $soldLand = Sold_land::findOrFail($id);
            $soldLand->update($validatedData);
        }


        // Redirect or return a response
        if ($soldLand) {
            // Redirect with success message
            return redirect()->route('soldLand.view')->with('success', 'Sold Land details ' . (is_null($id) ? 'added' : 'updated') . ' successfully.');
        } else {
            // Redirect back with an error message
            return redirect()->route('soldLand.' . (is_null($id) ? 'add' : 'edit'), $id)->with('error', 'Failed to ' . (is_null($id) ? 'add' : 'update') . ' sold land details. Please try again.');
        }
    }



    public function show($id)
    {
        // Retrieve the sold land detail by ID
        $soldLands = Sold_land::findOrFail($id);
        // dd($soldLands);
        // Return a view withdd the sold land details
        return view('pages.sold-lands.show', ['soldLands' => $soldLands, 'id' => $id]);
    }


    public function bulkUploadSoldLandData(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:csv,txt|max:10240', // Adjust max file size as needed
        ]);
    
        $filePath = $request->file('document')->getRealPath();
        $file = fopen($filePath, 'r');
    
        // Skip the first two rows
        fgetcsv($file); // Skip first row
        fgetcsv($file); // Skip second row
    
        DB::beginTransaction();
    
        try {
            while (($line = fgetcsv($file)) !== false) {
                // Remove the first two columns (temp_id and index_id) for checking
                $remainingColumns = array_slice($line, 2); // Start from the third column
    
                // If the remaining columns are not empty, process the row
                if (array_filter($remainingColumns)) {
                    $dateFormats = ['d-m-Y', 'd/m/Y'];
                    $formattedDate = null;
                    foreach ($dateFormats as $format) {
                        try {
                            $formattedDate = Carbon::createFromFormat($format, trim($line[19]))->toDateString();
                            break; // Format matched, break out of the loop
                        } catch (\Exception $e) {
                            // Continue trying other formats
                        }
                    }
    
                    $data['register_date'] = $formattedDate ?? null;
    
                    // Extract data from each row
                    $data = [
                        'index_id' => $line[1] ?? null,
                        'state' => $line[2] ?? null,
                        'district_number' => $line[3] ?? null,
                        'district' => $line[4] ?? null,
                        'village_number' => $line[5] ?? null,
                        'village' => $line[6] ?? null,
                        'survey_number' => $line[7] ?? null,
                        'wet_land' => $line[8] ?? null,
                        'dry_land' => $line[9] ?? null,
                        'plot' => $line[10] ?? null,
                        'traditional_land' => $line[11] ?? null,
                        'total_area' => $line[12] ?? null,
                        'total_area_unit' => $line[13] ?? null,
                        'total_wet_land' => $line[14] ?? null,
                        'total_dry_land' => $line[15] ?? null,
                        'gap' => $line[16] ?? null,
                        'sale_amount' => $line[17] ?? null,
                        'total_sale_amount' => $line[18] ?? null,
                        'registration_office' => $line[19] ?? null,
                        'register_number' => $line[20] ?? null,
                        'register_date' => $formattedDate ?? null,
                        'book_number' => $line[22] ?? null,
                        'name_of_the_purchaser' => $line[23] ?? null,
                        'balance_land' => $line[24] ?? null,
                        'remark' => $line[25] ?? null,
                   
                        'created_by' => Auth::user()->id,
                    ];
    
                    // Validate the data
                    $validator = Validator::make($data, [
                        'index_id' => 'nullable|string|max:255',
                        'district_number' => 'nullable|string|max:255',
                        'district' => 'nullable|string|max:255',
                        'village_number' => 'nullable|string|max:255',
                        'village' => 'nullable|string|max:255',
                        'survey_number' => 'nullable|string|max:255',
                        'wet_land' => 'nullable|string|max:255',
                        'dry_land' => 'nullable|string|max:255',
                        'plot' => 'nullable|string|max:255',
                        'traditional_land' => 'nullable|string|max:255',
                        'total_area' => 'nullable|string|max:255',
                        'total_area_unit' => 'nullable|string|max:255',
                        'total_wet_land' => 'nullable|string|max:255',
                        'total_dry_land' => 'nullable|string|max:255',
                        'gap' => 'nullable|string|max:255',
                        'sale_amount' => 'nullable|string|max:255',
                        'total_sale_amount' => 'nullable|string|max:255',
                        'registration_office' => 'nullable|string|max:255',
                        'register_number' => 'nullable|string|max:255',
                        'book_number' => 'nullable|string|max:255',
                        'name_of_the_purchaser' => 'nullable|string|max:255',
                        'balance_land' => 'nullable|string|max:255',
                        'remark' => 'nullable|string|max:255',
                      
                    ]);
    
                    if ($validator->fails()) {
                        throw new \Exception('Validation failed for one or more rows.');
                    }
    
                    // Create or update the SoldLand record
                    Sold_land::updateOrCreate(['index_id' => $data['index_id']], $data);
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
    

    public function bulkUploadSoldLandData1(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:csv,txt|max:10240', // Adjust max file size as needed
        ]);

        $filePath = $request->file('document')->getRealPath();
        $file = fopen($filePath, 'r');

        // Skip the first two rows
        fgetcsv($file); // Skip first row
        fgetcsv($file); // Skip second row

        DB::beginTransaction();

        try {
            while (($line = fgetcsv($file)) !== false) {
                if (!empty($line[0])) {
                    if (array_filter($line)) {

                        $dateFormats = ['d-m-Y', 'd/m/Y'];
                        $formattedDate = null;
                        foreach ($dateFormats as $format) {
                            try {
                                $formattedDate = Carbon::createFromFormat($format, trim($line[19]))->toDateString();
                                break; // Format matched, break out of the loop
                            } catch (\Exception $e) {
                                // Catch the exception and continue trying other formats
                            }
                        }

                        $data['register_date'] = $formattedDate ?? null;

                        // Extract data from each row
                        $data = [
                            // 'index_id' => $line[0] ?? null,
                            'index_id' => $line[1] ?? null,
                            'state' => $line[2] ?? null,
                            'district_number' => $line[3] ?? null,
                            'district' => $line[4] ?? null,
                            'village_number' => $line[5] ?? null,
                            'village' => $line[6] ?? null,
                            'survey_number' => $line[7] ?? null,
                            'wet_land' => $line[8] ?? null,
                            'dry_land' => $line[9] ?? null,
                            'plot' => $line[10] ?? null,
                            'traditional_land' => $line[11] ?? null,
                            'total_area' => $line[12] ?? null,
                            'total_area_unit' => $line[13] ?? null,
                            'total_wet_land' => $line[14] ?? null,
                            'total_dry_land' => $line[15] ?? null,
                            'gap' => $line[16] ?? null,
                            'sale_amount' => $line[17] ?? null,
                            'total_sale_amount' => $line[18] ?? null,
                            'registration_office' => $line[19] ?? null,
                            'register_number' => $line[20] ?? null,
                            'register_date' => $formattedDate ?? null,
                            'book_number' => $line[22] ?? null,
                            'name_of_the_purchaser' => $line[23] ?? null,
                            'balance_land' => $line[24] ?? null,
                            'remark' => $line[25] ?? null,
                            'sale_date' => $line[26] ?? null,
                            'created_by' => Auth::user()->id,


                            // Add other fields here...
                        ];

                        // Validate the data
                        $validator = Validator::make($data, [
                            'index_id' => 'nullable|string|max:255',
                            'district_number' => 'nullable|string|max:255',
                            'district' => 'nullable|string|max:255',
                            'village_number' => 'nullable|string|max:255',
                            'village' => 'nullable|string|max:255',
                            'survey_number' => 'nullable|string|max:255',
                            'wet_land' => 'nullable|string|max:255',
                            'dry_land' => 'nullable|string|max:255',
                            'plot' => 'nullable|string|max:255',
                            'traditional_land' => 'nullable|string|max:255',
                            'total_area' => 'nullable|string|max:255',
                            'total_area_unit' => 'nullable|string|max:255',
                            'total_wet_land' => 'nullable|string|max:255',
                            'total_dry_land' => 'nullable|string|max:255',
                            'gap' => 'nullable|string|max:255',
                            'sale_amount' => 'nullable|string|max:255',
                            'total_sale_amount' => 'nullable|string|max:255',
                            'registration_office' => 'nullable|string|max:255',
                            'register_number' => 'nullable|string|max:255',
                            // 'register_date' => 'nullable|date',
                            'book_number' => 'nullable|string|max:255',
                            'name_of_the_purchaser' => 'nullable|string|max:255',
                            'balance_land' => 'nullable|string|max:255',
                            'remark' => 'nullable|string|max:255',
                            'sale_date' => 'nullable|string|max:255',
                            // Add validation rules for other fields...
                        ]);

                        if ($validator->fails()) {
                            throw new \Exception('Validation failed for one or more rows.');
                        }

                        // Create or update the SoldLand record, if the index_id  is unique it will create else update
                        Sold_land::updateOrCreate(['index_id' => $data['index_id']], $data);
                    }
                }
            }
            DB::commit();

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
}

<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Receiver_type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exports\ChildDocumentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\{Receiver,AssignedDocumentsExport,AdvocatesExport};

use App\Models\{Master_doc_type, Master_doc_data, Category,Advocate,Advocate_documents};

class ReportController extends Controller
{
    public function documentsAssignedToReceivers(Request $request)
    {
        // dd($request->all());
        $receiverTypes = Receiver_type::all();
        $receivers = Receiver::all();

        $assigned_documents =  DB::table('document_assignments')
        ->join('master_doc_datas', 'document_assignments.doc_id', '=', 'master_doc_datas.id')
        ->select('document_assignments.doc_id', 'master_doc_datas.name as document_name')
        ->distinct()
        ->get();

        $documents = Master_doc_data::all();
        // $documents = Document::all();

        $query = DB::table('document_assignments')
            // ->join('documents', 'document_assignments.document_id', '=', 'documents.id')
            ->join('receivers', 'document_assignments.receiver_id', '=', 'receivers.id')
            ->join('receiver_types', 'receivers.receiver_type_id', '=', 'receiver_types.id')
            // ->join('master_doc_sheet', 'document_assignments.doc_id', '=', 'master_doc_sheet.doc_id')
            ->join('master_doc_datas', 'document_assignments.doc_id', '=', 'master_doc_datas.id');

        if ($request->filled('receiver_type')) {
            $query->where('receivers.receiver_type_id', $request->input('receiver_type'));
        }

        if ($request->filled('receiver_id')) {
            $query->where('document_assignments.receiver_id', $request->input('receiver_id'));
        }

        if ($request->filled('doc_id')) {
            $query->where('master_doc_datas.id', $request->input('doc_id'));
        }

        if ($request->filled('start_date')) {
            $query->whereDate('document_assignments.created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('document_assignments.created_at', '<=', $request->input('end_date'));
        }

        $assignedDocuments = $query->paginate(10, [
            'document_assignments.id as assignment_id',
            'receivers.name as receiver_name',
            'receiver_types.name as receiver_type_name',
            'document_assignments.created_at as created_at',
            DB::raw('COALESCE(NULLIF(master_doc_datas.name, ""), "--") as document_name'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.category_id, ""), "--") as category_id'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.subcategory_id, ""), "--") as subcategory_id'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.location, ""), "--") as location'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.locker_id, ""), "--") as locker_id'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.category, ""), "--") as category'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.document_type_name, ""), "--") as document_type_name'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.current_state, ""), "--") as current_state'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.state, ""), "--") as state'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.alternate_state, ""), "--") as alternate_state'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.current_district, ""), "--") as current_district'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.district, ""), "--") as district'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.alternate_district, ""), "--") as alternate_district'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.current_taluk, ""), "--") as current_taluk'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.taluk, ""), "--") as taluk'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.alternate_taluk, ""), "--") as alternate_taluk'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.current_village, ""), "--") as current_village'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.village, ""), "--") as village'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.alternate_village, ""), "--") as alternate_village'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.issued_date, ""), "--") as issued_date'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.area, ""), "--") as area'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.dry_land, ""), "--") as dry_land'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.wet_land, ""), "--") as wet_land'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.unit, ""), "--") as unit'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.old_locker_number, ""), "--") as old_locker_number'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.latitude, ""), "--") as latitude'),
            DB::raw('COALESCE(NULLIF(master_doc_datas.longitude, ""), "--") as longitude'),

            DB::raw('COALESCE(NULLIF(master_doc_datas.survey_no, ""), "--") as survey_no'),
        ]);
        foreach ($assignedDocuments as $document) {
            // Check category_id and handle '--'
            if ($document->created_at) {
                $document->created_at_formatted = Carbon::parse($document->created_at)->format('d-M-Y H:i');
            } else {
                $document->created_at_formatted = '--'; // or any default value you prefer
            }
            if (isset($document->category_id) && $document->category_id !== '--' && !empty($document->category_id)) {
                $categoryIds = explode(',', $document->category_id);
                $document->category_names = DB::table('categories')
                    ->whereIn('id', $categoryIds)
                    ->pluck('name')
                    ->implode(', ');
            } else {
                $document->category_names = '--';
            }

            // Check subcategory_id and handle '--'
            if (isset($document->subcategory_id) && $document->subcategory_id !== '--' && !empty($document->subcategory_id)) {
                $subcategoryIds = explode(',', $document->subcategory_id);
                $document->subcategory_names = DB::table('subcategories')
                    ->whereIn('id', $subcategoryIds)
                    ->pluck('name')
                    ->implode(', ');
            } else {
                $document->subcategory_names = '--';
            }
        }

        // return($assignedDocuments);

        // dd($assignedDocuments);
        return view('pages.reports.documents-assigned-to-receivers', compact('receivers', 'receiverTypes', 'assigned_documents', 'assignedDocuments'));
    }




    public function documentsAssignedToReceiversExport(Request $request)
    {
        return Excel::download(new AssignedDocumentsExport($request->all()), 'assigned_documents.xlsx');
    }



    public function documentsAssignedToAdvocates(Request $request)
    {
        // dd($request->all());

        $advocates = Advocate::all();
        $assigned_documents = DB::table('advocate_documents')
        ->join('master_doc_datas', 'advocate_documents.doc_id', '=', 'master_doc_datas.id')
        ->select('advocate_documents.doc_id', 'master_doc_datas.name as name', 'master_doc_datas.doc_identifier_id as doc_identifier_id')
        // ->groupBy('advocate_documents.doc_id', 'master_doc_datas.name')
        ->get();

        // dd($assigned_documents);
// dd(count($assigned_documents));
        $query = DB::table('advocate_documents')
            // ->join('documents', 'document_assignments.document_id', '=', 'documents.id')
            ->join('advocates', 'advocate_documents.advocate_id', '=', 'advocates.id')
            // ->join('master_doc_sheet', 'document_assignments.doc_id', '=', 'master_doc_sheet.doc_id')
            ->join('master_doc_datas', 'advocate_documents.doc_id', '=', 'master_doc_datas.id');

        if ($request->filled('doc_id')) {
            $query->where('master_doc_datas.id', $request->input('doc_id'));
        }

        if ($request->filled('advocate_id')) {
            $query->where('advocate_documents.advocate_id', $request->input('advocate_id'));
        }

        if ($request->filled('start_date')) {
            $query->whereDate('advocate_documents.created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('advocate_documents.created_at', '<=', $request->input('end_date'));
        }
        if ($request->filled('case_status')) {
            $query->where('advocate_documents.case_status', $request->input('case_status'));
        }
        if ($request->filled('case_result')) {
            $query->where('advocate_documents.case_result', $request->input('case_result'));
        }


    // Get the distinct case_result and case_status
    $unique_case_results = DB::table('advocate_documents')
    ->select('case_result')
    ->distinct()
    ->get();

$unique_case_statuses = DB::table('advocate_documents')
    ->select('case_status')
    ->distinct()
    ->get();

        // Add pagination here
        $assignedDocuments = $query->paginate(10, [
            'advocate_documents.id as assignment_id',
            'advocates.name as advocate_name',
            'master_doc_datas.name as document_name',
            'master_doc_datas.doc_identifier_id as doc_identifier_id',
            DB::raw('COALESCE(NULLIF(advocate_documents.case_name, ""), "--") as case_name'),
            DB::raw('COALESCE(NULLIF(advocate_documents.case_status, ""), "--") as case_status'),
            DB::raw('COALESCE(NULLIF(advocate_documents.court_name, ""), "--") as court_name'),
            DB::raw('COALESCE(NULLIF(advocate_documents.court_case_location, ""), "--") as court_case_location'),
            DB::raw('COALESCE(NULLIF(advocate_documents.court_case_location, ""), "--") as document_name'),
            DB::raw('COALESCE(NULLIF(advocate_documents.plaintiff_name, ""), "--") as plaintiff_name'),
            DB::raw('COALESCE(NULLIF(advocate_documents.defendant_name, ""), "--") as defendant_name'),
            // DB::raw('COALESCE(NULLIF(advocate_documents.urgency_level, ""), "--") as urgency_level'),
            DB::raw('COALESCE(NULLIF(advocate_documents.case_result, ""), "--") as case_result'),
            DB::raw('COALESCE(NULLIF(advocate_documents.notes, ""), "--") as notes'),

            DB::raw('COALESCE(NULLIF(advocate_documents.status, ""), "--") as status'),
   'advocate_documents.created_at as created_at',
   'advocate_documents.updated_at as updated_at',
        ]);

        foreach ($assignedDocuments as $document) {
            // Check category_id and handle '--'
            if ($document->created_at) {
                $document->created_at_formatted = Carbon::parse($document->created_at)->format('d-M-Y H:i');
            } else {
                $document->created_at_formatted = '--'; // or any default value you prefer
            }
            if (isset($document->category_id) && $document->category_id !== '--' && !empty($document->category_id)) {
                $categoryIds = explode(',', $document->category_id);
                $document->category_names = DB::table('categories')
                    ->whereIn('id', $categoryIds)
                    ->pluck('name')
                    ->implode(', ');
            } else {
                $document->category_names = '--';
            }

            // Check subcategory_id and handle '--'
            if (isset($document->subcategory_id) && $document->subcategory_id !== '--' && !empty($document->subcategory_id)) {
                $subcategoryIds = explode(',', $document->subcategory_id);
                $document->subcategory_names = DB::table('subcategories')
                    ->whereIn('id', $subcategoryIds)
                    ->pluck('name')
                    ->implode(', ');
            } else {
                $document->subcategory_names = '--';
            }
        }
        return view('pages.reports.documents-assigned-to-advocates', compact('advocates', 'assigned_documents', 'assignedDocuments','unique_case_results','unique_case_statuses'));
        // return view('pages.reports.documents-assigned-to-advocates', compact('advocates', 'assigned_documents', 'assignedDocuments'));
    }

    public function documentsAssignedToAdvocatesExport(Request $request)
    {
        // dd("sdfsdf");
        return Excel::download(new AdvocatesExport($request->all()), 'advocates_assigned_documents.xlsx');
    }

    public function childDocumentReports(Request $request)
    {
        $filters = $request->only([
            'type', 'number_of_pages', 'state', 'district', 'village', 'locker_no', 'start_date',
            'end_date', 'area_range_start', 'area_range_end', 'area_unit','court_case_no', 'advocate_name','case_result',
            'doc_no', 'survey_no', 'categories', 'subcategories', 'locker_ids', 'doc_identifiers', 'doc_name', 'doc_status', 'logs'
        ]);

        // Store filters in session
        session(['document_filters' => $filters]);

        return $this->getFilteredDocuments($filters);
    }

    public function getFilteredDocuments($filters = null)
    {
        if (!$filters) {
            $filters = session('document_filters', []);
        }

    
        $caseResults = Advocate_documents::pluck('case_result')
        ->unique()
        ->sort()
        ->reject(fn ($value) => empty($value) || $value === " " || $value === "N/A" || is_null($value))
        ->values();
    
    $advocateNames = Advocate::pluck('name')
        ->unique()
        ->sort()
        ->reject(fn ($value) => empty($value) || $value === " " || $value === "N/A" || is_null($value))
        ->values();
    
    $courtCaseNos = Advocate_documents::pluck('court_case_location')
        ->unique()
        ->sort()
        ->reject(fn ($value) => empty($value) || $value === " " || $value === "N/A" || is_null($value))
        ->values();
    
    $states = Master_doc_data::pluck('current_state')
        ->flatMap(fn ($item) => collect(explode(',', $item))->map(fn ($i) => Str::of($i)->trim()))
        ->unique()
        ->sort()
        ->reject(fn ($value) => empty($value) || $value === " " || $value === "N/A" || is_null($value))
        ->values();
    
    $districts = Master_doc_data::pluck('current_district')
        ->flatMap(fn ($item) => collect(explode(',', $item))->map(fn ($i) => Str::of($i)->trim()))
        ->unique()
        ->sort()
        ->reject(fn ($value) => empty($value) || $value === " " || $value === "N/A" || is_null($value))
        ->values();
    
    $villages = Master_doc_data::pluck('current_village')
        ->flatMap(fn ($item) => collect(explode(',', $item))->map(fn ($i) => Str::of($i)->trim()))
        ->unique()
        ->sort()
        ->reject(fn ($value) => empty($value) || $value === " " || $value === "N/A" || is_null($value))
        ->values();
    
    $doc_nos = Master_doc_data::pluck('doc_no')
        ->flatMap(fn ($item) => collect(explode(',', $item))->map(fn ($i) => Str::of($i)->trim()))
        ->unique()
        ->sort()
        ->reject(fn ($value) => empty($value) || $value === " " || $value === "N/A" || is_null($value))
        ->values();
    
    $survey_nos = Master_doc_data::pluck('survey_no')
        ->flatMap(fn ($item) => collect(explode(',', $item))->map(fn ($i) => Str::of($i)->trim()))
        ->unique()
        ->sort()
        ->reject(fn ($value) => empty($value) || $value === " " || $value === "N/A" || is_null($value))
        ->values();
        
        $categories = Category::all();
        $lockers = Master_doc_data::whereNotNull('locker_id')->where('locker_id', '!=', '')->distinct()->pluck('locker_id');
        $docIdentifiers = Master_doc_data::whereNotNull('doc_identifier_id')->where('doc_identifier_id', '!=', '')->distinct()->pluck('doc_identifier_id');

        // Fetch filtered documents with pagination
        // $documents = $this->filterDocuments(
        //     $filters['type'] ?? null,
        //     $filters['state'] ?? null,
        //     $filters['district'] ?? null,
        //     $filters['village'] ?? null,
        //     $filters['start_date'] ?? null,
        //     $filters['end_date'] ?? null,
        //     $filters['area_range_start'] ?? null,
        //     $filters['area_range_end'] ?? null,
        //     $filters['area_unit'] ?? null,
        //     $filters['doc_no'] ?? null,
        //     $filters['survey_no'] ?? null,
        //     $filters['categories'] ?? null,
        //     $filters['subcategories'] ?? null,
        //     $filters['doc_name'] ?? null,
        //     $filters['doc_identifiers'] ?? null,
        //     $filters['locker_ids'] ?? null,
        //     $filters['doc_status'] ?? null,
        //     $filters['logs'] ?? null,
        //     10000
        // );

        $documents = $this->filterDocuments(
            $filters['type'] ?? null,
            $filters['state'] ?? null,
            $filters['district'] ?? null,
            $filters['village'] ?? null,
            $filters['start_date'] ?? null,
            $filters['end_date'] ?? null,
            $filters['area_range_start'] ?? null,
            $filters['area_range_end'] ?? null,
            $filters['area_unit'] ?? null,
            $filters['court_case_no'] ?? null,
            $filters['advocate_name'] ?? null,
            $filters['case_result'] ?? null,
            $filters['doc_no'] ?? null,
            $filters['survey_no'] ?? null,
            $filters['categories'] ?? null,
            $filters['subcategories'] ?? null,
            $filters['doc_name'] ?? null,
            $filters['doc_identifiers'] ?? null,
            $filters['locker_ids'] ?? null,
            $filters['doc_status'] ?? null,
            $filters['logs'] ?? null,
            10000
        );

        // Prepare data for the view
        $data = [
            'documents' => $documents,
            'doc_type' => Master_doc_type::orderBy('name')->get(),
            'selected_type' => $filters['type'] ?? null,
            'states' => $states,
            'districts' => $districts,
            'villages' => $villages,
            'area_unit' => $filters['area_unit'] ?? null,
            'categories' => $categories,
            'lockers' => $lockers,
            'docIdentifiers' => $docIdentifiers,
            'survey_nos' => $survey_nos,
            'doc_nos' => $doc_nos,
            'courtCaseNos' => $courtCaseNos,
            'advocateNames' => $advocateNames,
            'caseResults' => $caseResults,
            'filters' => $filters,
        ];

        return view('pages.reports.child-documents', $data);
    }

    public function filterDocuments(
        $typeId = null,
        $state = null,
        $district = null,
        $village = null,
        $start_date = null,
        $end_date = null,
        $area_range_start = null,
        $area_range_end = null,
        $area_unit = null,
        $court_case_no = null,
        $advocate_name = null,
        $case_result = null,
        $doc_no = null,
        $survey_no = null,
        $category_id = null,
        $subcategory_id = null,
        $doc_name = null,
        $doc_identifier_id = null,
        $locker_id = null,
        $doc_status = null,
        $logs = null,
        $perPage = 10000
    ) {
        // Build the query with all the filters
        $query = Master_doc_data::query();

        if ($advocate_name) {
            // Find the advocate by name
            $advocate = DB::table('advocates')->where('name', 'like', '%' . $advocate_name . '%')->first();

            if ($advocate) {
                $advocateId = $advocate->id;

                // Search in the advocate_documents table for doc_id with the advocate_id
                $advocateDocsQuery = DB::table('advocate_documents')->where('advocate_id', $advocateId);

             
                // Get doc_ids from the advocate_documents table
                $docIds = $advocateDocsQuery->pluck('doc_id');

                // Apply the doc_ids filter to the main query
                $query->whereIn('id', $docIds);
            }
        }

        if ($court_case_no) {
            // If advocate_name is not available, but court_case_no or case_result is provided

            $advocateDocsQuery = DB::table('advocate_documents');

            // Filter by court case number if provided
            if ($court_case_no) {
                $advocateDocsQuery->where('court_case_location', 'like', '%' . $court_case_no . '%');
            }
            $docIds = $advocateDocsQuery->pluck('doc_id');

            // Apply the doc_ids filter to the main query
            $query->whereIn('id', $docIds);
        }

        if ($case_result) {
            // If advocate_name is not available, but court_case_no or case_result is provided

            $advocateDocsQuery = DB::table('advocate_documents');

            // Filter by case result if provided
            if ($case_result) {
                $advocateDocsQuery->where('case_result', 'like', '%' . $case_result . '%');
            }

            $docIds = $advocateDocsQuery->pluck('doc_id');

            // Apply the doc_ids filter to the main query
            $query->whereIn('id', $docIds);
        }


        // Apply filters to the query
        if ($typeId) {
            $query->where('document_type', explode('|', $typeId)[0]); // Assuming document_type filtering
        }else{
            $query->where('document_type', 47); // 
        }

        if ($locker_id) {
            $lockerNos = is_array($locker_id) ? $locker_id : explode(',', $locker_id);
            $query->where(function ($q) use ($lockerNos) {
                foreach ($lockerNos as $locker_id) {
                    $q->orWhere('locker_id', 'like', '%' . $locker_id . '%');
                }
            });
        }

        if ($doc_identifier_id) {
            $docIdentifiers = is_array($doc_identifier_id) ? $doc_identifier_id : explode(',', $doc_identifier_id);
            $query->where(function ($q) use ($docIdentifiers) {
                foreach ($docIdentifiers as $docIdentifier) {
                    $q->orWhere('doc_identifier_id', 'like', '%' . $docIdentifier . '%');
                }
            });
        }

        if ($category_id) {
            $categories = is_array($category_id) ? $category_id : explode(',', $category_id);
            $query->where(function ($q) use ($categories) {
                foreach ($categories as $cat) {
                    $q->orWhereRaw("FIND_IN_SET(?, category_id)", [$cat]);
                }
            });
        }

        if ($subcategory_id) {
            $subcategories = is_array($subcategory_id) ? $subcategory_id : explode(',', $subcategory_id);
            $query->where(function ($q) use ($subcategories) {
                foreach ($subcategories as $subcat) {
                    $q->orWhereRaw("FIND_IN_SET(?, subcategory_id)", [$subcat]);
                }
            });
        }

        if ($doc_no) {
            $query->where('doc_no', $doc_no);
        }

        if ($doc_status !== null) {
            $query->where('status_id', $doc_status);
        }

        if ($logs) {
            $query->where('transaction_type', $logs);
        }

        if ($doc_name) {
            $query->where('name', 'like', '%' . $doc_name . '%');
        }
   if ($survey_no) {
            $survey_nos = is_array($survey_no) ? $survey_no : explode(',', $survey_no);
            $query->where(function ($q) use ($survey_nos) {
                foreach ($survey_nos as $survey_no) {
                    $q->orWhere(function ($subQuery) use ($survey_no) {
                        $subQuery->whereRaw("FIND_IN_SET(?, survey_no)", [$survey_no]);
                    });
                }
            });
        }

        if ($start_date && $end_date) {
            $start = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay();
            $end = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay();
            $query->whereBetween('issued_date', [$start, $end]);
        } elseif ($start_date) {
            $start = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay();
            $query->where('issued_date', '>=', $start);
        } elseif ($end_date) {
            $end = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay();
            $query->where('issued_date', '<=', $end);
        }
        
        if ($state) {
            $query->whereRaw("FIND_IN_SET(?, current_state)", [$state]);
        }
        if ($district) {
            $query->whereRaw("FIND_IN_SET(?, current_district)", [$district]);
        }
        if ($village) {
            $query->where('current_village', 'like', '%' . $village . '%');
        }

        if ($area_range_start !== null || $area_range_end !== null) {
            $query->where(function ($q) use ($area_range_start, $area_range_end, $area_unit) {
                if ($area_unit) {
                    if ($area_unit === 'Acres') {
                        // Search for both acres and cents
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('unit', 'acres and cents');
                            if ($area_range_start !== null && $area_range_end !== null) {
                                $q->whereBetween('area', [$area_range_start, $area_range_end]);
                            } elseif ($area_range_start !== null) {
                                $q->where('area', '>=', $area_range_start);
                            } elseif ($area_range_end !== null) {
                                $q->where('area', '<=', $area_range_end);
                            }
                        });

                        // Convert acres to square feet and search
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('unit', 'Square Feet');
                            if ($area_range_start !== null && $area_range_end !== null) {
                                $q->whereBetween('area', [$area_range_start * 43560, $area_range_end * 43560]);
                            } elseif ($area_range_start !== null) {
                                $q->where('area', '>=', $area_range_start * 43560);
                            } elseif ($area_range_end !== null) {
                                $q->where('area', '<=', $area_range_end * 43560);
                            }
                        });
                    } elseif ($area_unit === 'Square Feet') {
                        // Search for square feet
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('unit', 'Square Feet');
                            if ($area_range_start !== null && $area_range_end !== null) {
                                $q->whereBetween('area', [$area_range_start, $area_range_end]);
                            } elseif ($area_range_start !== null) {
                                $q->where('area', '>=', $area_range_start);
                            } elseif ($area_range_end !== null) {
                                $q->where('area', '<=', $area_range_end);
                            }
                        });

                        // Convert square feet to acres and cents and search
                        $q->orWhere(function ($q) use ($area_range_start, $area_range_end) {
                            $q->where('unit', 'acres and cents');
                            if ($area_range_start !== null && $area_range_end !== null) {
                                $q->whereBetween('area', [$area_range_start / 43560, $area_range_end / 43560]);
                            } elseif ($area_range_start !== null) {
                                $q->where('area', '>=', $area_range_start / 43560);
                            } elseif ($area_range_end !== null) {
                                $q->where('area', '<=', $area_range_end / 43560);
                            }
                        });
                    }
                }
            });
        }

        // Log::info('SQL Query', ['query' => $query->toSql(), 'bindings' => $query->getBindings()]);

        // Fetch the filtered data
        $filteredData = $perPage ? $query->paginate($perPage) : $query->get();
        // dd($filteredData);
        // Loop through the filtered data to attach related table IDs
        foreach ($filteredData as $item) {
            $documentType = $item->document_type_name;

            // Determine the corresponding table name based on documentType
            $tableName = $documentType;

            try {
                // Query the corresponding table using masterDocId
                $tableEntry = DB::table($tableName)
                    ->where('doc_id', $item->id)
                    ->orderBy('document_name')
                    ->first();

                // Attach tableId to the $item
                $item->tableId = $tableEntry ? $tableEntry->id : null;
            } catch (\Exception $e) {
                // Log the exception if the table does not exist or query fails
                Log::error("Failed to query table: {$tableName}. Error: " . $e->getMessage());
                $item->tableId = null;
            }
        }

        return $filteredData;
    }

    public function exportFilteredDocuments(Request $request)
    {
        // Log::info("Export Request Data", ['request' => $request->all()]);
    
        // Get the filters from the request and decode them
        $filters = json_decode($request->input('filters'), true);  // Decode the JSON filters
    // dd($filters);
        if (!isset($filters['data']) || empty($filters['data'])) {
            return response()->json(['error' => 'No documents found for export.'], 404);
        }
    
        // Assuming all documents have the same document_type_name, get it from the first document
        $firstDocument = $filters['data'][0];  // Get the first document
        // dd("first document detail", $firstDocument);
        $documentTypeName = $firstDocument['document_type_name'] ?? null;
    
        if (!$documentTypeName) {
            return response()->json(['error' => 'Document type name is required for export.'], 400);
        }
    
        // Log::info("Document Type Name", ['document_type_name' => $documentTypeName]);
    
        // Fetch all data based on the filters, ensuring the type is applied
        $documents = collect($filters['data']);  // Use the filtered documents
    
    // Use map to update the documents collection with child data
    $documents = $documents->map(function ($document) {
        // Query the child table using document_type_name and doc_id
        $childData = DB::table($document['document_type_name'])
                        ->where('doc_id', $document['id'])  // Assuming 'id' is the doc_id
                        ->get();  // Fetch all related child rows

        // Convert child data to an array if found, otherwise assign an empty array
        $document['child_data'] = $childData->isNotEmpty() ? $childData->toArray() : [];

        return $document;  // Return the modified document
    });

    // Convert collection to array
    $documentsArray = $documents->toArray();

    // Log::info("Final Documents for Export", ['documents' => $documentsArray]);
    // dd($documentsArray);
        // Log final documents for export
        // Log::info("Final Documents for Export", ['documents' => $documentsArray]);

        // Export the data to Excel using Laravel Excel
        return Excel::download(new ChildDocumentsExport($documentsArray), 'filtered_documents.xlsx');
    }
    

  
}

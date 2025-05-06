<x-app-layout pageTitle="Your Page Title">

    <x-header />

    <x-sidebar />

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nouislider/distribute/nouislider.min.css"> --}}

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            {{-- $tableName --}}
            <div class="row page-titles">

                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                    <li class="breadcrumb-item "><a href="javascript:void(0)">View Document</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">All Document</a></li>
                </ol>

            </div>
            {{-- new card start --}}

            <div class="row">
                <div class="col-xl-12">

                    <div class="filter cm-content-box box-primary">
                        <div class="content-title SlideToolHeader">
                            <h4>
                                Search Document
                            </h4>
                            <div class="tools">
                                <a href="javascript:void(0);" class="expand handle"><i
                                        class="fal fa-angle-down"></i></a>
                            </div>
                        </div>
                        {{-- {{ dd($request->all()) }} --}}
                        <div class="cm-content-body  form excerpt">
                            <div class="card-body">
                                <form action="{{ url('/') }}/filter-document" method="POST">
                                    @csrf
                                    <div class="row">

                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Select Document Type </label>
                                            <select id="single-select-abc2" class="form-select form-control"
                                                style="width:100%;" name="type">
                                                <option value="" selected>Select Document Type</option>
                                                @foreach ($doc_type as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('type', $filters['type'] ?? '') == $item->id ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $item->name)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4 col-xl-4">
                                            <label class="form-label">Document Name</label>
                                            <input name="doc_name" class="form-control"
                                                placeholder="Enter Document Name" type="text"
                                                value="{{ old('doc_name', $filters['doc_name'] ?? '') }}">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label"> Document Identifiers </label>
                                            <select class="form-select form-control" id="dynamic-option-creation"
                                                name="doc_identifiers[]" multiple>
                                                <option selected disabled>Select Document Identifiers</option>
                                                @foreach ($docIdentifiers as $docIdentifier)
                                                    <option value="{{ $docIdentifier }}"
                                                        {{ collect(old('doc_identifiers', $filters['doc_identifiers'] ?? []))->contains($docIdentifier) ? 'selected' : '' }}>
                                                        {{ $docIdentifier }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label"> State <span data-bs-container="body"
                                                    data-bs-toggle="popover" data-bs-placement="top"
                                                    data-bs-content="The selection of State is mandatory to select District and Village. District and Village gets filter through the selection of State.">
                                                    <i class="fas fa-info-circle"></i>
                                                </span></label>
                                            <select class="form-select form-control" id="single-select-abctest3"
                                                name="state" aria-label="State select">
                                                <option value="" selected>Select State</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state }}"
                                                        {{ old('state', $filters['state'] ?? '') == $state ? 'selected' : '' }}>
                                                        {{ $state }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div id="loader-3" class="loader" style="display:none;"></div>
                                        </div>

                                        <div class="mb-3 col-md-4">
                                            <label class="form-label"> District</label>
                                            <select class="form-select form-control" id="single-select-abctest4"
                                                name="district" aria-label="District select">
                                                <option value="" selected>Select District</option>
                                            </select>
                                            <div id="loader-4" class="loader" style="display:none;"></div>
                                        </div>

                                        <div class="mb-3 col-md-4">
                                            <label class="form-label"> Village</label>
                                            <select class="form-select form-control" id="single-select-abctest5"
                                                name="village" aria-label="Village select">
                                                <option value="" selected>Select Village</option>
                                            </select>
                                            <div id="loader-5" class="loader" style="display:none;"></div>
                                        </div>


                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Document Date (Start)</label>
                                            <div class="input-hasicon">
                                                <input name="start_date" type="date" class="form-control  solid"
                                                    value="{{ old('start_date', $filters['start_date'] ?? '') }}">
                                                <div class="icon"><i class="far fa-calendar"></i></div>
                                            </div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Document Date (End)</label>
                                            <div class="input-hasicon">
                                                <input name="end_date" type="date" class="form-control  solid"
                                                    value="{{ old('end_date', $filters['end_date'] ?? '') }}">
                                                <div class="icon"><i class="far fa-calendar"></i></div>
                                            </div>
                                        </div>

                                        <div class="mb-3 col-md-4 col-xl-4">
                                            <label class="form-label">Minimum Area Size</label>
                                            <input name="area_range_start" class="form-control"
                                                placeholder="Enter Minimum Area Size" type="number"
                                                value="{{ old('area_range_start', $filters['area_range_start'] ?? '') }}">
                                        </div>
                                        <div class="mb-3 col-md-4 col-xl-4">
                                            <label class="form-label">Maximum Area Size</label>
                                            <input name="area_range_end" class="form-control"
                                                placeholder="Enter Maximum Area Size" type="number"
                                                value="{{ old('area_range_end', $filters['area_range_end'] ?? '') }}">
                                        </div>
                                        <div class="mb-3 col-md-4 col-xl-4">
                                            <label class="form-label">Select Area Unit (Optional)</label>
                                            <select class="form-control" id="area-unit-dropdown" name="area_unit">
                                                <option value="">Select Unit</option>
                                                <option value="Acres"
                                                    {{ old('area_unit', $filters['area_unit'] ?? '') == 'Acres' ? 'selected' : '' }}>
                                                    Acres and Cents
                                                </option>
                                                <option value="Square Feet"
                                                    {{ old('area_unit', $filters['area_unit'] ?? '') == 'Square Feet' ? 'selected' : '' }}>
                                                    Square Feet
                                                </option>
                                            </select>
                                        </div>

                                        <div class="mb-3 col-md-4 col-xl-4">
                                            <label class="form-label">Court Case</label>
                                            <select id="single-select-abc8" class="form-select form-control"
                                                style="width:100%;" name="court_case_no">
                                                <option value="" selected>Select Court Case</option>
                                                @foreach ($courtCaseNos as $court_case_no)
                                                    <option value="{{ $court_case_no }}"
                                                        {{ old('court_case_no', $filters['court_case_no'] ?? '') == $court_case_no ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $court_case_no)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4 col-xl-4">
                                            <label class="form-label">Advocate Name</label>
                                            <select id="single-select-abc13" class="form-select form-control"
                                                style="width:100%;" name="advocate_name">
                                                <option value="" selected>Select Advocate Name</option>
                                                @foreach ($advocateNames as $advocateName)
                                                    <option value="{{ $advocateName }}"
                                                        {{ old('advocate_name', $filters['advocate_name'] ?? '') == $advocateName ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $advocateName)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4 col-xl-4">
                                            <label class="form-label">Case Result</label>
                                            <select id="single-select-abc14" class="form-select form-control"
                                                style="width:100%;" name="case_result">
                                                <option value="" selected>Select Case Result</option>
                                                @foreach ($caseResults as $caseResult)
                                                    <option value="{{ $caseResult }}"
                                                        {{ old('case_result', $filters['case_result'] ?? '') == $caseResult ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $caseResult)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3 col-md-4 col-xl-4">
                                            <label class="form-label">Case Status</label>
                                            <select id="single-select-abc14" class="form-select form-control"
                                                style="width:100%;" name="case_status">
                                                <option value="" selected>Select Case Status</option>
                                                @foreach ($caseStatuses as $caseStatus)
                                                    <option value="{{ $caseStatus }}"
                                                        {{ old('case_status', $filters['case_status'] ?? '') == $caseStatus ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $caseStatus)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Plaintiff Name</label>
                                            <select id="dynamic-option-creation3" class="form-select form-control" name="plaintiff_name[]" multiple>
                                                <option selected disabled>Select Plaintiff Name</option>
                                                @foreach ($plaintiffNames as $plaintiffName)
                                                    <option value="{{ $plaintiffName }}"
                                                        {{ collect(old('plaintiff_name', $filters['plaintiff_name'] ?? []))->contains($plaintiffName) ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $plaintiffName)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Defendant Name</label>
                                            <select id="dynamic-option-creation4" class="form-select form-control" name="defendant_name[]" multiple>
                                                <option selected disabled>Select Defendant Name</option>
                                                @foreach ($defendantNames as $defendantName)
                                                    <option value="{{ $defendantName }}"
                                                        {{ collect(old('defendant_name', $filters['defendant_name'] ?? []))->contains($defendantName) ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $defendantName)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        

                                        <div class="mb-3 col-md-4 col-xl-4">
                                            <label class="form-label">Document No</label>
                                  

                                            <select id="single-select-abc6" class="form-select form-control"
                                                style="width:100%;" name="doc_no">
                                                <option value="" selected>Select Document No</option>
                                                @foreach ($doc_nos as $doc_no)
                                                    <option value="{{ $doc_no }}"
                                                        {{ old('doc_no', $filters['doc_no'] ?? '') == $doc_no ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $doc_no)) }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Survey Numbers</label>
                                            <select id="dynamic-option-creation2" class="form-select form-control" name="survey_no[]" multiple>
                                                <option selected disabled>Select Survey No</option>
                                                @foreach ($survey_nos as $survey_no)
                                                    <option value="{{ $survey_no }}"
                                                        {{ collect(old('survey_no', $filters['survey_no'] ?? []))->contains($survey_no) ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $survey_no)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label"> Locker IDs </label>
                                            <select class="form-select form-control" id="single-select-abc9"
                                                name="locker_ids[]" multiple>
                                                <option selected disabled>Select Locker IDs</option>
                                                @foreach ($lockers as $locker)
                                                    <option value="{{ $locker }}"
                                                        {{ collect(old('locker_ids', $filters['locker_ids'] ?? []))->contains($locker) ? 'selected' : '' }}>
                                                        {{ $locker }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Categories</label>
                                            <select class="form-select form-control" id="category-select"
                                                name="categories[]" multiple>
                                                <option selected disabled>Select Categories</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ collect(old('categories', $filters['categories'] ?? []))->contains($category->id) ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="mb-3 col-md-6">
                                            <label class="form-label"> Subcategories </label>
                                            <select class="form-select form-control" id="subcategory-select"
                                                name="subcategories[]" multiple>
                                                <!-- Subcategories will be populated dynamically -->
                                            </select>
                                        </div>

                                      

                                     

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Status</label>
                                            <select class="form-select form-control" id="single-select-abc11"
                                                name="doc_status">
                                                <option selected disabled>Select Document Status</option>
                                                <option value="0"
                                                    {{ old('doc_status', $filters['doc_status'] ?? '') == '0' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="1"
                                                    {{ old('doc_status', $filters['doc_status'] ?? '') == '1' ? 'selected' : '' }}>
                                                    Approve</option>
                                                <option value="2"
                                                    {{ old('doc_status', $filters['doc_status'] ?? '') == '2' ? 'selected' : '' }}>
                                                    Hold</option>
                                                <option value="3"
                                                    {{ old('doc_status', $filters['doc_status'] ?? '') == '3' ? 'selected' : '' }}>
                                                    Reviewer Feedback</option>
                                            </select>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Document Logs</label>
                                            <select class="form-select form-control" id="single-select-abc12"
                                                name="logs">
                                            {{-- <select class="form-select form-control" id="dynamic-option-creation2"
                                                name="logs"> --}}
                                                <option selected disabled>Select Document Logs</option>
                                                <option value="taken"
                                                    {{ old('logs', $filters['logs'] ?? '') == 'taken' ? 'selected' : '' }}>
                                                    Taken</option>
                                                <option value="returned"
                                                    {{ old('logs', $filters['logs'] ?? '') == 'returned' ? 'selected' : '' }}>
                                                    Returned</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <div class="text-end">
                                            {{-- <a href="{{ url('/') }}/filter-document" class="btn-link"><button class="btn btn-dark"><i
                                                class="fas fa-filter"></i>&nbsp;Reset Filter</button></a> --}}
                                            <a href="{{ url('/') }}/filter-document" class="btn btn-dark"><i
                                                    class="fas fa-refresh"></i>&nbsp;Reset</a>

                                            <button class="btn btn-secondary" type="submit"><i
                                                    class="fas fa-filter"></i>&nbsp;Filter</button>
                                        </div>
                                    </div>
                            </div>
                            </form>

                            <!-- In your view -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Document</h4>
                            {{-- <button id="exportButton" class="btn btn-primary float-end"><i
                                    class="fas fa-file-export"></i>&nbsp;Export</button> --}}
                            {{-- <form action="{{ route('documents.export') }}" method="GET">
                                        <button type="submit" class="btn btn-primary float-end"><i
                                            class="fas fa-file-export"></i>&nbsp;Export to Excel</button>
                                    </form> --}}
                            <!-- Hidden form for exporting data -->
                            {{-- <form id="export-form" action="{{ route('documents.export') }}" method="POST" style="display:none;">
                                        @csrf
                                        <textarea name="documents">{{ json_encode($documents->items()) }}</textarea>
                                    </form> --}}

                            <!-- Export button -->
                            {{-- <button onclick="document.getElementById('export-form').submit();" class="btn btn-success">Export to Excel</button> --}}
                            <form method="POST" action="{{ route('documents.export') }}">
                                @csrf
                                <input type="hidden" name="filters" value="{{ json_encode($documents) }}">
                                <button type="submit" class="btn btn-primary"><i
                                    class="fas fa-download"></i>&nbsp;Export CSV</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="table-responsive">
                                    <table class="table table-striped table-responsive-sm" style="width:100%"
                                        id="filter-table" style="min-width: 845px;font-size: 12px;">
                                        {{-- <table id="example2" lass="display table-hover"  > --}}
                                        <thead>
                                            <tr>
                                                {{-- <th>
                                                    <div class="custom-control d-inline custom-checkbox ms-2">
                                                        <input type="checkbox" class="form-check-input" id="checkAll"
                                                            required="">
                                                        <label class="form-check-label" for="checkAll"></label>
                                                    </div>
                                                </th> --}}
                                                <th scope="col">Sl. No.</th>
                                                <th scope="col">Document Name</th>
                                                <th scope="col">Category</th>
                                                <th scope="col">Document Type</th>
                                                <th scope="col">Village</th>
                                                <th scope="col">District</th>
                                                <th scope="col">Area</th>

                                                <th scope="col">Locker Id</th>
                                                <th scope="col">ID</th>
                                                <th scope="col">Status</th>
                                                @if ($user && $user->hasPermission('Main Document View '))
                                                    <th scope="col">Action</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($documents as $index => $item)
                                                <tr>

                                                    <th scope="row">{{ $index + 1 }}</th>
                                                    <td scope="row">{{ $item->name }}</td>
                                                    <td scope="row">
                                                        @php $categoryFound = false; @endphp
                                                        
                                                        @foreach($categories as $category)
                                                            @if($item->category_id == $category->id)
                                                                {{ $category->name }}
                                                                @php $categoryFound = true; @endphp
                                                                @break
                                                            @endif
                                                        @endforeach
                                                    
                                                        @if(!$categoryFound)
                                                            -- <!-- Display this if no category is matched -->
                                                        @endif
                                                    </td>
                                                    

                                                    <td scope="row">
                                                        {{ ucWords(str_replace('_', ' ', $item->document_type_name)) }}
                                                    </td>

                                                    <td>{{ $item->current_village ? $item->current_village : '--' }}
                                                    </td>
                                                    <td>{{ $item->current_district ? $item->current_district : '--' }}
                                                    </td>
                                                    <td>{{ $item->area ? $item->area : '--' }}
                                                        {{-- ({{ $item->unit ? ($item->unit === 'acres and cents' ? 'A&C' : 'SqFt') : '--' }}) --}}
                                                        {{ $item->unit }}
                                                    </td>
                                                    <td>{{ $item->locker_id ? $item->locker_id : '--' }}</td>
                                                    <td>{{ $item->doc_identifier_id ? $item->doc_identifier_id : '--' }}</td>
                                                    <td>
                                                        @php
                                                            $statusClasses = [
                                                                '0' => 'badge-danger text-danger',
                                                                '1' => 'badge-success text-success',
                                                                '2' => 'badge-warning text-warning',
                                                                '3' => 'badge-warning text-dark',
                                                            ];
                                                            $statusTexts = [
                                                                '0' => 'Pending',
                                                                '1' => 'Accepted',
                                                                '2' => 'Hold',
                                                                '3' => 'Feedback',
                                                            ];
                                                            $statusId = strval($item->status_id); // Convert to string to match array keys
                                                            $statusClass =
                                                                $statusClasses[$statusId] ??
                                                                'badge-secondary text-secondary'; // Default class if key doesn't exist
$statusText = $statusTexts[$statusId] ?? 'Unknown'; // Default text if key doesn't exist
                                                        @endphp

                                                        <span class="badge light {{ $statusClass }}">
                                                            <i class="fa fa-circle {{ $statusClass }} me-1"></i>
                                                            {{ $statusText }}
                                                        </span>
                                                    </td>
                                                    @if ($user && $user->hasPermission('Main Document View '))
                                                        <td>

                                                            @if ($item->status_id == 1)
                                                                <a href="{{ url('/') }}/review_doc/{{ $item->document_type_name }}/{{ $item->tableId }}"
                                                                    style="padding: 0.25rem 0.5rem; font-size: 0.65rem;"
                                                                    class="btn btn-primary">
                                                                    <i class="fas fa-eye"></i> View
                                                                </a>
                                                            @else
                                                                <a href="{{ url('/') }}/review_doc/{{ $item->document_type_name }}/{{ $item->tableId }}"
                                                                    style="padding: 0.25rem 0.5rem; font-size: 0.65rem;"
                                                                    class="btn btn-secondary">
                                                                    <i class="fas fa-list-check"></i> Review
                                                                </a>
                                                            @endif

                                                            </a>
                                                        </td>
                                                    @endif

                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                    {{-- <div class="d-flex justify-content-center">
                                    {{ $documents->links() }}
                                </div> --}}
                                    <div class="row">
                                        <div class="col">
                                            {{-- {{ $documents->links('vendor.pagination.custom') }} --}}
                                            {{ $documents->appends(request()->except('page'))->links('vendor.pagination.custom') }}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('layouts.footer')


</x-app-layout>

<script>
    document.querySelector('form').onsubmit = function() {
        document.getElementById('preloader').style.display = 'block'; // Show loader
    };

    window.onload = function() {
        document.getElementById('preloader').style.display = 'none'; // Hide loader on page load
    };
</script>
<style>
    #loader {
        display: none;
        position: fixed;
        z-index: 999;
        height: 2em;
        width: 2em;
        overflow: visible;
        margin: auto;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }
</style>

<script>
    $("#single-select-abc1").select2();

    $(".single-select-abc1-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    $("#single-select-abc2").select2();

    $(".single-select-abc2-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    $("#single-select-abc3").select2();

    $(".single-select-abc3-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    $("#single-select-abc4").select2();

    $(".single-select-abc4-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    $("#single-select-abc5").select2();

    $(".single-select-abc5-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    $("#single-select-abc6").select2();

    $(".single-select-abc6-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    $("#single-select-abc7").select2();

    $(".single-select-abc7-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    $("#single-select-abc8").select2();

    $(".single-select-abc8-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    $("#single-select-abc9").select2();

    $(".single-select-abc9-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    $("#single-select-abc10").select2();

    $(".single-select-abc10-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true,
        tags:true
    });
    $("#single-select-abc11").select2();

    $(".single-select-abc11-placeholder").select2({
        placeholder: "Select a doc log",
        allowClear: true
    });
    $("#single-select-abc12").select2();

    $(".single-select-abc12-placeholder").select2({
        placeholder: "Select the doc status",
        allowClear: true
    });
    $("#single-select-abc13").select2();

    $(".single-select-abc13-placeholder").select2({
        placeholder: "Select the doc status",
        allowClear: true
    });
    $("#single-select-abc14").select2();

    $(".single-select-abc14-placeholder").select2({
        placeholder: "Select the doc status",
        allowClear: true
    });
    // $("#dynamic-option-creation2").select2();

    // $(".dynamic-option-creation2").select2({
    //     placeholder: "Select the doc status",
    //     allowClear: false,
    //     tags:true,
    // });
    // $("#single-select-abctest3").select2();

    // $(".single-select-abctest3-placeholder").select2({
    //     placeholder: "Select a state",
    //     allowClear: true
    // });
</script>


<style>
    .loader {
        border: 4px solid #f3f3f3;
        border-radius: 50%;
        border-top: 4px solid #3498db;
        width: 20px;
        height: 20px;
        animation: spin 2s linear infinite;
        display: inline-block;
        vertical-align: middle;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
<script>
    function resetDropdown(id) {
        const dropdown = document.getElementById(id);
        const text = id.replace('single-select-abctest', ''); // Extract number suffix
        const label = {
            '3': 'State',
            '4': 'District',
            '5': 'Village'
        } [text] || 'Select'; // Map number to label, default to 'Select'

        dropdown.innerHTML = `<option value="" selected>Select ${label}</option>`;
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    document.getElementById('single-select-abctest3').addEventListener('change', function() {
        updateSelections('state', this.value);
    });

    document.getElementById('single-select-abctest4').addEventListener('change', function() {
        updateSelections('district', this.value);
    });

    function updateSelections(type, value) {
        if (type === 'state') {
            resetDropdown('single-select-abctest4');
            resetDropdown('single-select-abctest5');
        } else if (type === 'district') {
            resetDropdown('single-select-abctest5');
        }

        let url = '';
        switch (type) {
            case 'state':
                url = `/api/fetch/districts/${value}`;
                fetchDropdownData(url, 'single-select-abctest4');
                targetId = 'single-select-abctest4';
                break;
            case 'district':
                url = `/api/fetch/villages/${value}`;
                fetchDropdownData(url, 'single-select-abctest5');
                targetId = 'single-select-abctest5';
                break;
            default:
                console.error('Unhandled selection type:', type);
                return;
        }
        showLoader(targetId);
        fetchDropdownData(url, targetId);
    }

    function fetchDropdownData(url, targetId, selectedValue = '') {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const dropdown = document.getElementById(targetId);
                const loader = document.getElementById('loader-' + targetId.replace('single-select-abctest', ''));
                console.log("data", data);
                resetDropdown(targetId); // Reset with correct default text
                if (Array.isArray(data)) {
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item;
                        option.textContent = item;
                        if (item === selectedValue) {
                            option.selected = true;
                        }
                        dropdown.appendChild(option);
                    });
                } else {
                    console.error('Data format error:', data);
                }
                hideLoader(targetId);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                hideLoader(targetId);
            });
    }

    function showLoader(targetId) {
        const loaderId = 'loader-' + targetId.replace('single-select-abctest', '');
        const loader = document.getElementById(loaderId);
        if (loader) {
            loader.style.display = 'inline-block';
        }
    }

    function hideLoader(targetId) {
        const loaderId = 'loader-' + targetId.replace('single-select-abctest', '');
        const loader = document.getElementById(loaderId);
        if (loader) {
            loader.style.display = 'none';
        }
    }
    // Initialize dropdowns based on previous selections
    document.addEventListener('DOMContentLoaded', function() {
        const selectedState = document.getElementById('single-select-abctest3').value;
        const selectedDistrict = document.getElementById('single-select-abctest4').value;

        if (selectedState) {
            updateSelections('state', selectedState);
        }

        if (selectedState && selectedDistrict) {
            setTimeout(() => {
                updateSelections('district', selectedDistrict);
            }, 500); // Adjust timeout as necessary to ensure state dropdown is populated first
        }
    });
</script>
<script>
    $(document).ready(function() {
        // Initialize select2
        $("#category-select").select2({
            placeholder: "Select Categories",
            allowClear: true
        });
        $("#subcategory-select").select2({
            placeholder: "Select Subcategories",
            allowClear: true
        });

        // Populate subcategories on page load based on pre-selected categories
        populateSubcategories();

        $('#category-select').on('change', function() {
            populateSubcategories();
        });

        function populateSubcategories() {
            var selectedCategories = $('#category-select').val();
            var subcategories = [];

            // Collect selected subcategories to retain selection
            var previouslySelectedSubcategories = {!! json_encode(old('subcategories', [])) !!};

            @foreach ($categories as $category)
                if (selectedCategories && selectedCategories.includes('{{ $category->id }}')) {
                    @foreach ($category->subcategories as $subcategory)
                        subcategories.push({
                            id: '{{ $subcategory->id }}',
                            name: '{{ $subcategory->name }}',
                            category: '{{ $category->name }}'
                        });
                    @endforeach
                }
            @endforeach

            var subcategorySelect = $('#subcategory-select');
            subcategorySelect.empty();

            if (subcategories.length > 0) {
                subcategories.forEach(function(subcategory) {
                    var isSelected = previouslySelectedSubcategories.includes(subcategory.id
                        .toString()) ? 'selected' : '';
                    subcategorySelect.append('<option value="' + subcategory.id + '" ' + isSelected +
                        '>' + subcategory.category + ' - ' + subcategory.name + '</option>');
                });
            } else {
                subcategorySelect.append('<option selected disabled>No Subcategories Available</option>');
            }

            // Reinitialize select2 for subcategory select
            $('#subcategory-select').select2({
                placeholder: "Select Subcategories",
                allowClear: true
            });
            //     $("#subcategory-select").select2({
            //     placeholder: "Select Subcategories",
            //     allowClear: true
            // });
        }
    });
</script>

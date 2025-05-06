<x-app-layout>


    <x-header />
    <x-sidebar />

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-body">
                <div class="row page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Assigned Document</a></li>
                    </ol>
                </div>
                <div class="row">
                    <div class="col-xl-12">

                        <div class="filter cm-content-box box-primary">
                            <div class="content-title SlideToolHeader">
                                <h4>
                                    Search Advocates
                                </h4>
                                <div class="tools">
                                    <a href="javascript:void(0);" class="expand handle"><i
                                            class="fal fa-angle-down"></i></a>
                                </div>
                            </div>
                            <div class="cm-content-body  form excerpt">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form action="{{ route('documentsAssignedToAdvocates.index') }}" method="GET" class="row">

                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Advocates</label>
                                                    <select class="form-select form-control" name="advocate_id"
                                                        id="single-select-abc1">
                                                        <option value="">Select Advocate</option>
                                                        @foreach ($advocates as $type)
                                                            <option value="{{ $type->id }}"
                                                                {{ request()->input('advocate_id') == $type->id ? 'selected' : '' }}>
                                                                {{ $type->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Document ID</label>
                                                    <select class="form-select form-control" id="single-select-abc2" name="doc_id">
                                                        <option value="">Select Document ID</option>
                                                        @foreach ($assigned_documents as $doc)
                                                            <option value="{{ $doc->doc_id }}" {{ request()->input('doc_id') == $doc->doc_id ? 'selected' : '' }}>
                                                                {{ $doc->doc_identifier_id }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                               


                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Case Status</label>
                                                    <select class="form-select form-control" id="single-select-abc2" name="case_status">
                                                        <option value="">Select Case Status</option>
                                                        @foreach ($unique_case_statuses as $doc)
                                                            <option value="{{ $doc->case_status }}" {{ request()->input('case_status') == $doc->case_status ? 'selected' : '' }}>
                                                                {{ $doc->case_status }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Case Result</label>
                                                    <select class="form-select form-control" id="single-select-abc3" name="case_result">
                                                        <option value="">Select Case Result</option>
                                                        @foreach ($unique_case_results as $doc)
                                                            <option value="{{ $doc->case_result }}" {{ request()->input('case_result') == $doc->case_result ? 'selected' : '' }}>
                                                                {{ $doc->case_result }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary"><i
                                                        class="fas fa-filter"></i>&nbsp;Filter</button>
                                                    <a href="{{ route('assignedDocumentsToAdvocates.export', request()->all()) }}"
                                                        class="btn btn-success"><i
                                                        class="fas fa-download"></i>&nbsp;Export to Excel</a>
                                                    <a href="{{ url('/') }}/documents-assigned-to-advocates"
                                                        class="btn btn-dark"><i
                                                        class="fas fa-refresh"></i>&nbsp;Reset</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

          
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">

                                    <div class="table-responsive">
                                        @if($assignedDocuments->isNotEmpty())
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Assignment ID</th>
                                                    <th>Advocate Name</th>
                                                    {{-- <th>Created At</th> --}}
                                                    <th>Document Name</th>
                                                    <th>Document Id</th>
                                                    <th>Case Name</th> <!-- Replaced Category ID with Case Name -->
                                                    <th>Case Status</th> <!-- Replaced Subcategory ID with Case Status -->
                                                    <th>Court Name</th>
                                                    <th>Court Case Location</th>
                                                    <th>Plaintiff Name</th>
                                                    <th>Defendant Name</th>
                                            
                                                    <th>Case Result</th>
                                                    <th>Notes</th>
                                                    <th>Created At</th> <!-- Duplicate Created At to match your data -->
                                                    <th>Updated At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($assignedDocuments as $document)
                                                <tr>
                                                    <td>{{ $document->assignment_id }}</td>
                                                    <td>{{ $document->advocate_name }}</td>
                                                    {{-- <td>{{ $document->created_at_formatted }}</td> --}}
                                                    <td>{{ $document->document_name }}</td>
                                                    <td>{{ $document->doc_identifier_id }}</td>
                                                    <td>{{ $document->case_name }}</td>
                                                    <td>{{ $document->case_status }}</td>
                                                    <td>{{ $document->court_name }}</td>
                                                    <td>{{ $document->court_case_location }}</td>
                                                    <td>{{ $document->plaintiff_name }}</td>
                                                    <td>{{ $document->defendant_name }}</td>
                                                    {{-- <td>{{ $document->urgency_level }}</td> --}}
                                                    <td>{{ $document->case_result }}</td>
                                                    <td>{{ $document->notes }}</td>
                                                    <td>{{ $document->created_at }}</td>
                                                    <td>{{ $document->updated_at }}</td>
                                                  
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        <div class="row">
                                            <div class="col">
                                                {{ $assignedDocuments->links('vendor.pagination.custom') }}
                                                {{-- {{ $assignedDocuments->appends(request()->except('page'))->links('vendor.pagination.custom') }} --}}
    
                                            </div>
                                        </div>
                                    @else
                                        <p>No assigned documents found.</p>
                                    @endif
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
</script>

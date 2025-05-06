@php
    use Carbon\Carbon;
@endphp

<x-app-layout>


    <x-header />
    <x-sidebar />

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-body">
                <div class="row page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/advocates">Advocates</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Assigned Document</a></li>
                    </ol>
                </div>

                {{-- Display success message --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Display validation errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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
                                            <form action="{{ route('advocate.documents.assigned.show', ['advocate_id' => $advocateId]) }}" method="GET" class="row">

                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Document ID</label>
                                                    <select class="form-select form-control" id="single-select-abc2" name="doc_id">
                                                        <option value="">Select Document ID</option>
                                                        {{-- {{ dd($documentAssignments) }} --}}
                                                        @foreach ($documentAssignments as $doc)
                                                            <option value="{{ $doc->doc_id }}" {{ request()->input('doc_id') == $doc->doc_id ? 'selected' : '' }}>
                                                                {{ $doc->document->name   }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Case Result</label>
                                                    <select class="form-select form-control" id="single-select-abc3" name="case_result">
                                                        <option value="">Select Case Result</option>
                                                        @foreach ($unique_case_results as $result)
                                                            <option value="{{ $result->case_result }}" {{ request()->input('case_result') == $result->case_result ? 'selected' : '' }}>
                                                                {{ $result->case_result }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                
                                                <!-- New Plaintiff Name Filter -->
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Plaintiff Name</label>
                                                    <select class="form-select form-control" name="plaintiff_name">
                                                        <option value="">Select Plaintiff</option>
                                                        @foreach ($plaintiff_names as $plaintiff)
                                                            <option value="{{ $plaintiff->plaintiff_name }}" {{ request()->input('plaintiff_name') == $plaintiff->plaintiff_name ? 'selected' : '' }}>
                                                                {{ $plaintiff->plaintiff_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                
                                                <!-- New Defendant Name Filter -->
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Defendant Name</label>
                                                    <select class="form-select form-control" name="defendant_name">
                                                        <option value="">Select Defendant</option>
                                                        @foreach ($defendant_names as $defendant)
                                                            <option value="{{ $defendant->defendant_name }}" {{ request()->input('defendant_name') == $defendant->defendant_name ? 'selected' : '' }}>
                                                                {{ $defendant->defendant_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary"><i
                                                        class="fas fa-filter"></i>&nbsp;Filter</button>
                                                    {{-- <a href="{{ route('assignedDocumentsToAdvocates.export', request()->all()) }}" class="btn btn-success">Export to Excel</a> --}}
                                                    <a href="{{ route('advocate.documents.assigned.show', ['advocate_id' => $advocateId]) }}" class="btn btn-dark"><i
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

                                        <table class="table table-responsive-sm">

                                            Assigned documents to the Advocate : {{ $advocate->name }}

                                            {{-- {{ dd($documentAssignments) }} --}}
                                            @if ($user && $user->hasPermission('Add Assigned Docs to Advocate'))
                                                <button class="btn btn-success btn-sm assign-doc-btn float-end flex"
                                                    title="Assign Document to the Advocate" data-bs-toggle="modal"
                                                    data-bs-target="#assignDocumentModal"
                                                    data-receiver-id="{{ $advocateId }}"><i
                                                        class="fas fa-plus-square"></i>&nbsp;Assign Document
                                                </button>
                                            @endif
                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl. No.</th>

                                                    <th scope="col">Document Name </th>
                                                    <th scope="col">Document Type </th>
                                                    <th scope="col">Case Name </th>
                                                    <th scope="col">Case Status </th>

                                                    <th scope="col">Court Name </th>
                                                    <th scope="col">Court Case Location </th>
                                                    <th scope="col">Plaintiff Name </th>
                                                    <th scope="col">Defendent Name </th>

                                                    <th scope="col">Case Result </th>
                                                    <th scope="col">Notes </th>

                                                    <th scope="col">Status </th>
                                                    <th scope="col">Created At </th>
                                                    <th scope="col">Action </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    if (!function_exists('formatDocumentType')) {
                                                        function formatDocumentType($documentTypeName)
                                                        {
                                                            return ucwords(str_replace('_', ' ', $documentTypeName));
                                                        }
                                                    }
                                                @endphp
                                                @foreach ($documentAssignments as $index => $item)
                                                    <tr>
                                                        <th scope="row">{{ $index + 1 }}</th>

                                                        <td>
                                                            <a href="/review_doc/{{ $item->document->document_type_name }}/{{ $item->child_id }}"
                                                                style="color: #1714c9; text-decoration: underline;">
                                                                {{ $item->document->name }}
                                                            </a>
                                                        </td>

                                                        </td>
                                                        <td>
                                                            {{ $item->document->document_type_name ? formatDocumentType($item->document->document_type_name) : '--' }}
                                                        </td>
                                                        <td>{{ $item->case_name ?? '--' }}</td>
                                                        <td>{{ $item->case_status ?? '--' }}</td>
                                                        <td>{{ $item->court_name ?? '--' }}</td>
                                                        <td>{{ $item->court_case_location ?? '--' }}</td>
                                                        <td>{{ $item->plaintiff_name ?? '--' }}</td>
                                                        <td>{{ $item->defendant_name ?? '--' }}</td>
                                                        <td>{{ $item->case_result ?? '--' }}</td>
                                                        <td>{{ $item->notes ?? '--' }}</td>

                                                        <td>
                                                            @if (isset($item->status))
                                                                @switch($item->status)
                                                                    @case('1')
                                                                        <span class="badge bg-success">Active</span>
                                                                    @break

                                                                    @case('0')
                                                                        <span class="badge bg-warning">Inactive</span>
                                                                    @break

                                                                    @default
                                                                        <span>{{ $item->status }}</span>
                                                                @endswitch
                                                            @else
                                                                --
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $item->created_at ? Carbon::parse($item->created_at)->format('d-M-Y') : '--' }}
                                                        </td>

                                                        <td>
                                                            <div class="d-flex">

                                                                @if ($user && $user->hasPermission('Update Assigned Docs to Advocate'))
                                                                    @if ($item->status == 1)
                                                                        <button
                                                                            class="btn btn-primary btn-sm edit-doc-btn"
                                                                            title="Edit Document Assignment"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editDocumentModal"
                                                                            data-id="{{ $item->id }}">
                                                                            <i class="fas fa-edit"></i> Edit
                                                                        </button>
                                                                        <form
                                                                            action="{{ route('documentAdvocateAssignment.destroy', $item->id) }}"
                                                                            method="POST"
                                                                            style="display:inline-block;">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                class="btn btn-danger btn-sm"
                                                                                onclick="return confirm('Are you sure you want to disable this assignment?');">
                                                                                <i class="fas fa-trash"></i> Disable
                                                                            </button>
                                                                        </form>
                                                                    @else
                                                                        <button type="button"
                                                                            class="btn btn-primary btn-sm  "
                                                                            data-bs-container="body"
                                                                            data-bs-toggle="popover"
                                                                            data-bs-placement="top"
                                                                            data-bs-content="The assigned document is already inactive, due to which edit option is no more avaiable."><i
                                                                                class="fas fa-info-circle"></i></button>
                                                                    @endif
                                                                @endif
                                                                @if ($user && !$user->hasPermission('Update Assigned Docs to Advocate'))
                                                                    --
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if ($documentAssignments->isEmpty())
                                            <p>No document assignments available.</p>
                                        @endif
                                        <div class="row">
                                            <div class="col">
                                                {{ $documentAssignments->links('vendor.pagination.custom') }}
                                            </div>
                                        </div>
                                        {{-- @endif --}}
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
{{-- add modal  --}}
<div class="modal fade" id="assignDocumentModal">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Update Form -->
                <form action="{{ url('/') }}/assign-documents-to-advocate" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="receiverId" name="id">
                    <!-- Hidden fields inside the form -->
                    <input type="hidden" id="modalReceiverId" name="advocate_id">

                    <input type="hidden" name="location" value="user">

                    <div class="row">
                        <x-document-type-select :is_status="0" />
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="case_name" class="form-label">Case Name</label>
                                    <input type="text" class="form-control" id="case_name" name="case_name">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="case_status" class="form-label">Case Status</label>
                                    <input type="text" class="form-control" id="case_status" name="case_status">
                                </div>
                            </div>
                            {{-- <div class="col-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date">
                                </div>
                            </div> --}}
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="court_name" class="form-label">Court Name</label>
                                    <input type="text" class="form-control" id="court_name" name="court_name">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="court_case_location" class="form-label">Court Case Location</label>
                                    <input type="text" class="form-control" id="court_case_location"
                                        name="court_case_location">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="plaintiff_name" class="form-label">Plaintiff Name</label>
                                    <input type="text" class="form-control" id="plaintiff_name"
                                        name="plaintiff_name">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="defendant_name" class="form-label">Defendant Name</label>
                                    <input type="text" class="form-control" id="defendant_name"
                                        name="defendant_name">
                                </div>
                            </div>

                            {{-- <div class="col-4">
                                <div class="mb-3">
                                    <label for="edit_urgency_level" class="form-label">Priority Level</label>
                                    <select class="form-control" id="urgency_level" name="urgency_level">
                                        <option value="high">High</option>
                                        <option value="medium">Medium</option>
                                        <option value="low">Low</option>
                                    </select>
                                </div>
                            </div> --}}


                            {{-- <div class="col-4">
                                <div class="mb-3">
                                    <label for="submission_deadline" class="form-label">Submission Deadline</label>
                                    <input type="date" class="form-control" id="submission_deadline"
                                        name="submission_deadline">
                                </div>
                            </div> --}}

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="case_result" class="form-label">Case Result</label>
                                    <input type="text" class="form-control" id="case_result" name="case_result">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Assign Document</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Document Assignment Modal -->
<div class="modal fade" id="editDocumentModal">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Document Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Edit Form -->
                {{-- <form action="{{ url('/') }}/update-document-assignment" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') --}}
                <form id="editDocumentForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editAssignmentId" name="assignment_id">
                    <div class="row">

                        <div class="col-12">
                            <div class="mb-3">
                                <label for="edit_document_name" class="form-label">Document Name</label>
                                <input type="text" class="form-control" id="edit_document_name"
                                    name="document_name" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">

                                <label for="edit_advocate_id" class="form-label">Advocate</label>
                                <select class="form-control" id="edit_advocate_id" name="advocate_id">
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="edit_case_name" class="form-label">Case Name</label>
                                <input type="text" class="form-control" id="edit_case_name" name="case_name">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="edit_case_status" class="form-label">Case Status</label>
                                <input type="text" class="form-control" id="edit_case_status" name="case_status">
                            </div>
                        </div>
                    
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="edit_court_name" class="form-label">Court Name</label>
                                <input type="text" class="form-control" id="edit_court_name" name="court_name">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="edit_court_case_location" class="form-label">Court Case Location</label>
                                <input type="text" class="form-control" id="edit_court_case_location"
                                    name="court_case_location">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="edit_plaintiff_name" class="form-label">Plaintiff Name</label>
                                <input type="text" class="form-control" id="edit_plaintiff_name"
                                    name="plaintiff_name">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="edit_defendant_name" class="form-label">Defendant Name</label>
                                <input type="text" class="form-control" id="edit_defendant_name"
                                    name="defendant_name">
                            </div>
                        </div>
                  
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="edit_case_result" class="form-label">Case Result</label>
                                <input type="text" class="form-control" id="edit_case_result" name="case_result">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="edit_notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Document Assignment</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const assignDocButtons = document.querySelectorAll('.assign-doc-btn');
        const editDocButtons = document.querySelectorAll('.edit-doc-btn');

        assignDocButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const receiverId = button.getAttribute('data-receiver-id');
                document.getElementById('modalReceiverId').value = receiverId;
            });
        });

        editDocButtons.forEach(button => {
            button.addEventListener('click', async (event) => {
                const assignmentId = button.getAttribute('data-id');
                const response = await fetch(`/document-assignment/${assignmentId}/edit`);
                console.log("response: " + response)
                const {
                    assignment,
                    advocates
                } = await response.json();
                console.log("assignment", assignment);
                document.getElementById('editAssignmentId').value = assignment.id;
                document.getElementById('edit_document_name').value = assignment.document
                    .name;
                document.getElementById('edit_case_name').value = assignment.case_name;
                document.getElementById('edit_case_status').value = assignment.case_status;
                // document.getElementById('edit_start_date').value = assignment.start_date;
                // document.getElementById('edit_end_date').value = assignment.end_date;
                document.getElementById('edit_court_name').value = assignment.court_name;
                document.getElementById('edit_case_result').value = assignment.case_result;
                document.getElementById('edit_court_case_location').value = assignment
                    .court_case_location;
                document.getElementById('edit_plaintiff_name').value = assignment
                    .plaintiff_name;
                document.getElementById('edit_defendant_name').value = assignment
                    .defendant_name;
                // document.getElementById('edit_urgency_level').value = assignment
                //     .urgency_level;
                // document.getElementById('edit_urgency_level').value = assignment.urgency_level.toLowerCase(); // Ensure the value matches "high", "medium", or "low"
                // document.getElementById('edit_submission_deadline').value = assignment
                // .submission_deadline;
                document.getElementById('edit_notes').value = assignment.notes;


                // Populate the advocate dropdown
                const advocateSelect = document.getElementById('edit_advocate_id');
                advocateSelect.innerHTML = '';
                advocates.forEach(advocate => {
                    const option = document.createElement('option');
                    option.value = advocate.id;
                    option.textContent = advocate.name;
                    // Set selected advocate
                    if (advocate.id === assignment.advocate_id) {
                        option.selected = true;
                    }
                    advocateSelect.appendChild(option);
                });


                const form = document.getElementById('editDocumentForm');
                form.action = `/document-assignment/${assignment.id}`;
            });
        });
    });
</script>

<script>
    // Fetch documents based on the selected document type
    document.addEventListener('DOMContentLoaded', () => {
        const assignDocButtons = document.querySelectorAll('.assign-doc-btn');
        assignDocButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const receiverId = button.getAttribute('data-receiver-id');
                const receiverTypeId = button.getAttribute('data-receiver-type-id');

                // Set the receiver's ID and type in the hidden fields
                document.getElementById('modalReceiverId').value = receiverId;
                document.getElementById('modalReceiverTypeId').value = receiverTypeId;
            });
        });
    });
</script>

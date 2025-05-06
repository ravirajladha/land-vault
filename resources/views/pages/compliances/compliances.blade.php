<x-app-layout>


    <x-header />
    <x-sidebar />

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-body">
                <div class="row page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item active"><a href="/compliances">Compliances</a></li>

                    </ol>
                </div>

                {{-- Filter card start --}}
                <div class="row">
                    <div class="col-xl-12">
                        <div class="filter cm-content-box box-primary">
                            <div class="content-title SlideToolHeader">
                                <h4>
                                    Search Compliances
                                </h4>
                                <div class="tools">
                                    <a href="javascript:void(0);" class="expand handle"><i
                                            class="fal fa-angle-down"></i></a>
                                </div>
                            </div>
                            <div class="cm-content-body form excerpt">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form action="{{ route('compliances.index') }}" method="GET">
                                                <div class="row">
                                                    <!-- Document Type Dropdown -->
                                                    <div class="mb-3 col-md-4">
                                                        <label for="document_type" class="form-label">Document
                                                            Type</label>
                                                        <select id="document_type" class="form-select form-control"
                                                            name="document_type_name">
                                                            <option value="">Select Document Type</option>
                                                            @foreach ($uniqueDocuments->unique('document_type_name') as $document)
                                                                <option value="{{ $document->document_type_name }}"
                                                                    {{ request()->input('document_type_name') == $document->document_type_name ? 'selected' : '' }}>
                                                                    {{ $document->document_type_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Document Name Dropdown -->
                                                    <div class="mb-3 col-md-4">
                                                        <label for="document_name" class="form-label">Document
                                                            Name</label>
                                                        <select id="document_name" class="form-select form-control"
                                                            name="document_name">
                                                            <option value="">Select Document Name</option>
                                                            @foreach ($uniqueDocuments->unique('document_name') as $document)
                                                                <option value="{{ $document->document_name }}"
                                                                    {{ request()->input('document_name') == $document->document_name ? 'selected' : '' }}>
                                                                    {{ $document->document_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Start Due Date -->
        <div class="mb-3 col-md-4">
            <label for="start_due_date" class="form-label">Start Due Date</label>
            <input type="date" id="start_due_date" class="form-control" name="start_due_date"
                value="{{ request()->input('start_due_date') }}">
        </div>

                                                    <div class="mb-3 col-md-4">
                                                        <label for="end_due_date" class="form-label">End Due Date</label>
                                                        <input type="date" id="end_due_date" class="form-control" name="end_due_date"
                                                            value="{{ request()->input('end_due_date') }}">
                                                    </div>
                                            

                                                    <div class="mb-3 col-md-4">
                                                        <label for="is_recurring" class="form-label">Is Recurring?</label>
                                                        <select id="is_recurring" class="form-select form-control" name="is_recurring">
                                                            <option value="">Select Recurring</option>
                                                            <option value="1" {{ request()->input('is_recurring') == '1' ? 'selected' : '' }}>Yes</option>
                                                            <option value="0" {{ request()->input('is_recurring') == '0' ? 'selected' : '' }}>No</option>
                                                        </select>
                                                    </div>
                                            
                                                    <!-- Status -->
                                                    <div class="mb-3 col-md-4">
                                                        <label for="status" class="form-label">Status</label>
                                                        <select id="status" class="form-select form-control" name="status">
                                                            <option value="">Select Status</option>
                                                            <option value="1" {{ request()->input('status') == '1' ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ request()->input('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3 col-md-4">
                                                        <button type="submit" class="btn btn-primary"><i
                                                            class="fas fa-filter"></i>&nbsp;Filter</button>
                                                        <a href="{{ url('/') }}/compliances"
                                                            class="btn btn-dark ms-2"><i
                                                            class="fas fa-refresh"></i>&nbsp;Reset</a>
                                                            <a href="{{ route('compliances.export', request()->all()) }}" class="btn btn-success"><i
                                                                class="fas fa-download"></i>&nbsp;Export to Excel</a>

                                                    </div>
                                                </div>
                                            </form>
                                      
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Filter card end --}}
                {{-- Main complaince data table start --}}
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table id="example3" class="display" style="min-width: 845px">
                                        @if ($user && $user->hasPermission('Add Compliances'))
                                            <button type="button" class="btn btn-success mb-2 float-end btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#exampleModalCenter"> <i
                                                    class="fas fa-square-plus"></i>&nbsp;Add Compliance</button>
                                        @endif
                                        <thead>
                                            <tr>
                                                <th scope="col">Sl. No.</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Document Type </th>
                                                <th scope="col">Document Name </th>
                                                <th scope="col">Due Date</th>
                                                <th scope="col">Is Recurring </th>
                                                <th scope="col">Recurrence Months </th>

                                                {{-- <th scope="col">Status </th> --}}
                                                @if ($user && $user->hasPermission('Update Compliances Status'))
                                                    <th scope="col">Submit </th>
                                                @endif
                                                @if ($user && $user->hasPermission('Update Compliance Recurring Status'))
                                                    <th scope="col">Is Recurring Action</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($compliances as $index => $item)
                                                <tr data-item-id="{{ $item->id }}">
                                                    <th scope="row">{{ $index + 1 }}</th>

                                                    <td>{{ $item->rec }}</td>
                                                    <td>{{ ucwords(str_replace('_', ' ', $item->documentType->name)) }}
                                                    </td>
                                                    <td><a style="color: #1714c9; text-decoration: underline;"
                                                            href="/review_doc/{{ $item->documentType->name }}/{{ $item->child_id }}">{{ $item->document->name }}</a>
                                                    </td>
                                                    <td>{{ date('d-M-Y', strtotime($item->due_date)) }}</td>

                                                    <td> {!! $item->is_recurring
                                                        ? '<span class="badge bg-success">Yes</span>'
                                                        : '<span class="badge bg-danger">No</span>' !!}</td>
                                                    <td>
                                                        @if($item->is_recurring)
                                                        @php
                                                            // Split the recurrence_interval by the underscore character
                                                            $recurrenceParts = explode('_', $item->recurrence_interval);
                                                            // Get the number and the period (e.g., 1 and months)
                                                            $number = $recurrenceParts[0];
                                                            $period = ucfirst(rtrim($recurrenceParts[1], 's')); // Capitalize the period and remove the trailing 's'
                                                        @endphp
                                                        <span class="badge bg-success">Yes ({{ $number }} {{ $period }}{{ $number > 1 ? 's' : '' }})</span>
                                                    @else
                                                        <span class="badge bg-danger">No</span>
                                                    @endif
                                                    
                                                    </td>

                                                    @if ($user && $user->hasPermission('Update Compliances Status'))
                                                        <td class="action-cell" style="padding:0 0">
                                                            <!-- Action buttons based on status -->
                                                            @if ($item->status == 0)
                                                                <!-- Show buttons only if status is Pending -->
                                                                <button class="btn btn-sm btn-success toggle-status"
                                                                    data-id="{{ $item->id }}"
                                                                    data-action="settle"><i class="fas fa-thumbs-up"
                                                                        title="Click to Settle the Compliances"></i></button>
                                                                <button class="btn btn-sm btn-danger toggle-status"
                                                                    data-id="{{ $item->id }}"
                                                                    data-action="cancel"
                                                                    title="Click to Cancel the Compliances"><i
                                                                        class="fas fa-cancel"></i></button>
                                                            @elseif($item->status == 1)
                                                                <span class="badge bg-success"
                                                                    title="Click to Settle the Compliance Status">Settled</span>
                                                            @elseif($item->status == 2)
                                                                <span class="badge bg-danger"
                                                                    title="Click to Cancel the Compliance Status">Cancelled</span>
                                                            @else
                                                                <span class="badge bg-success">Unknown data</span>
                                                            @endif
                                                        </td>
                                                    @endif
                                                    @if ($user && $user->hasPermission('Update Compliance Recurring Status'))
                                                        <td>
                                                            <button type="button"
                                                                title="{{ $item->is_recurring ? 'Click to disable the recurring status of the Compliance' : 'Click to activate the Recurring Status of the Compliance' }}"
                                                                class="btn btn-sm {{ $item->is_recurring ? 'btn-danger' : 'btn-success' }}"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#confirmationModal"
                                                                data-action="{{ route('compliances.isRecurring.toggle', $item->id) }}">
                                                                {{ $item->is_recurring ? 'Deactivate' : 'Activate' }}
                                                            </button>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Main complaince data table end --}}


            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Compliances</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form theme-form projectcreate">
                        <form id="myAjaxForm" action="{{ url('/') }}/create-compliances" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <x-document-type-select :is_status="1" />

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="document" class="form-label">Name</label>
                                        <input class="form-control" type="text" name="name"
                                            placeholder="Enter name for the Compliance" required required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="document" class="form-label">Due Date</label>
                                        <input class="form-control" type="date" name="due_date" required required>
                                    </div>
                                </div>

                          
                                <div class="mb-3 row">
                                    <div class="col-sm-6">Is Recurring?</div>
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input class="form-check-input" id="is_recurring_checkbox" name="is_recurring" type="checkbox" value="1">
                                            <label class="form-check-label" for="is_recurring_checkbox">
                                                Yes
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 row" id="recurrence_interval_container" style="display: none;">
                                    <div class="col-sm-6">Time Period ?</div>
                                    <div class="col-sm-6">
                                        <select class="form-select form-control" id="recurrence_interval" name="recurrence_interval">
                                            <option value="">Select Time Period</option>
                                            <option value="1_months">1 Month </option>
                                            <option value="3_months">3 Months</option>
                                            <option value="6_months">6 Months</option>
                                            <option value="12_months">12 Months</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <div id="loader" style="display: none;">
                        Loading...
                    </div>
                    <button type="submit" class="btn btn-success" id="submitBtn">Submit Form</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal starts for making the is_recurring of the complainces activate and deactivate --}}
    <div class="modal fade" id="confirmationModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to <span id="actionType">activate/deactivate</span> this
                    compliances Is
                    Recurring Status?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footer')


</x-app-layout>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Fetch documents based on the selected document type
    function fetchDocuments(documentTypeId) {
        $.ajax({
            url: '/get-documents/' + documentTypeId,
            type: 'GET',
            success: function(response) {
                var documentSelect = $('#document');
                documentSelect.empty();

                // Check if the response has documents
                if (response.documents && response.documents.length > 0) {
                    $.each(response.documents, function(key, document) {
                        documentSelect.append(new Option(document.name, document.id));
                    });
                } else {
                    // If there are no documents, show an alert and add a default 'No documents' option
                    alert('No documents available for this document type.');
                    documentSelect.append(new Option('No documents available', ''));
                }
            },
            error: function(xhr, status, error) {
                // Handle any Ajax errors here
                alert('An error occurred while fetching the documents.');
            }
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('myAjaxForm');
        var submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function() {
            // Disable the submit button
            submitBtn.disabled = true;
        });
    });
</script>
<script>
    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-id');
            const action = this.getAttribute('data-action');
            // console.log(itemId);
            Swal.fire({
                title: `Are you sure you want to ${action} this item?`,
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, do it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the action (e.g., sending AJAX request to the server)
                    // Replace `your_route_here` with the actual route
                    // Add necessary data or headers as per your requirement
                    fetch(`/status-change-compliance/${itemId}/${action}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                status: action
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire(
                                'Updated!',
                                `The item has been ${action}ed.`,
                                'success'
                            );
                            // location.reload(true);

                            updateTableRow(itemId, data.newStatus);
                            // Optionally, refresh the page or update the DOM as needed
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            })
        });
    });
</script>
<script>
    //just after any ajax changes is made,this will udpate the table
    function updateTableRow(itemId, newStatus) {
        console.log(itemId);
        const row = document.querySelector(`tr[data-item-id="${itemId}"]`);
        const statusCell = row.querySelector('.status-cell');
        const actionCell = row.querySelector('.action-cell');

        // Update the status cell based on the new status
        switch (newStatus) {
            case 0: // Pending
                // statusCell.innerHTML = '<span class="badge bg-warning text-dark">Pending</span>';
                actionCell.innerHTML = `
                <button class="btn btn-sm btn-success toggle-status"
                        data-id="${itemId}"
                        data-action="settle"><i class="fas fa-thumbs-up"></i></button>
                <button class="btn btn-sm btn-danger toggle-status"
                        data-id="${itemId}"
                        data-action="cancel"><i class="fas fa-plus-cancel"></i></button>`;
                break;
            case 1: // Settled
                // statusCell.innerHTML = '<span class="badge bg-success">Settled</span>';
                actionCell.innerHTML = '<span class="badge bg-success">Settled</span>'; // Remove action buttons
                break;
            case 2: // Cancelled
                // statusCell.innerHTML = '<span class="badge bg-danger">Cancelled</span>';
                actionCell.innerHTML = '<span class="badge bg-danger">Cancelled</span>'; // Remove action buttons
                break;
            default:
                console.error('Unknown status');
        }
    }
</script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#confirmationModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var action = button.data('action'); // Extract info from data-* attributes
            var actionType = button.text().trim();
            var modal = $(this);

            // Update the modal's content.
            modal.find('.modal-body #actionType').text(actionType.toLowerCase());
            modal.find('#confirmBtn').off('click').on('click', function() {
                // Get CSRF token from meta tag
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                // Submit the form with the action set to the button's data-action attribute
                $('<form method="POST" action="' + action + '">' +
                    '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                    '</form>').appendTo('body').submit(); +
                '<input type="hidden" name="_method" value="POST">'
            });
        });
    });
</script>

<script>
    // Toggle recurrence months input field based on checkbox state
    const isRecurringCheckbox = document.getElementById('is_recurring_checkbox');
    const recurrenceMonthsField = document.getElementById('recurrence_months_field');

    isRecurringCheckbox.addEventListener('change', function() {
        if (this.checked) {
            recurrenceMonthsField.style.display = 'block';
            document.getElementById('recurrence_months').setAttribute('required', 'required');
        } else {
            recurrenceMonthsField.style.display = 'none';
            document.getElementById('recurrence_months').removeAttribute('required');
        }
    });
</script>
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
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const recurringCheckbox = document.getElementById('is_recurring_checkbox');
        const recurrenceIntervalContainer = document.getElementById('recurrence_interval_container');

        recurringCheckbox.addEventListener('change', function() {
            if (this.checked) {
                recurrenceIntervalContainer.style.display = 'flex'; // Show the dropdown
            } else {
                recurrenceIntervalContainer.style.display = 'none'; // Hide the dropdown
                // Optionally reset the value of the select dropdown
                document.getElementById('recurrence_interval').value = '';
            }
        });
    });
</script>
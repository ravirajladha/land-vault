<x-app-layout>
    <x-header />
    <x-sidebar />
    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-body">
                <div class="row page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Assign Document</a></li>
                    </ol>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModalCenter">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Assign Document Form</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="form theme-form projectcreate">
                                    <form id="myAjaxForm" action="{{ url('/') }}/assign-documents-to-receiver"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" name="location" value="all">
                                            <x-document-type-select :is_status="1" />


                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="receiverType" class="form-label">Receiver
                                                        Type</label>
                                                    <select class="form-control" id="receiverType" name="receiver_type"
                                                        onchange="fetchReceivers(this.value)" required>
                                                        <option value="">Select Receiver Type</option>
                                                        @foreach ($receiverTypes as $type)
                                                            <option value="{{ $type->id }}">{{ $type->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="receiver" class="form-label">Receiver</label>
                                                    <select class="form-control" id="receiver" name="receiver_id"
                                                        required>
                                                        <option value="">Select Receiver</option>
                                                        <!-- Options will be populated based on Receiver Type selection -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger light"
                                    data-bs-dismiss="modal">Close</button>
                                <div id="loader" style="display: none;">
                                    Loading...
                                </div>
                                <button type="submit" class="btn btn-primary" id="submitBtn">Submit Form</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header">
                                        <h4>Assigned Documents</h4>
                                        @if ($user && $user->hasPermission('Assign Document'))
                                            <button type="button" class="btn btn-success mb-2 float-end btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#exampleModalCenter"> <i
                                                    class="fas fa-square-plus"></i>&nbsp;Assign Document</button>
                                        @endif
                                    </div>
                                    <div class="table-responsive">
                                        <table id="example3" class="display" style="min-width: 845px">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl. No.</th>
                                                    <th scope="col">Receiver Name</th>
                                                    <th scope="col">Receiver Type</th>
                                            
                                                    <th scope="col">Document Type </th>
                                                    <th scope="col">Document Name </th>
                                                    <th scope="col">Expires At </th>
                                                    <th scope="col">Email Viewed </th>
                                                    <th scope="col">Status </th>
                                                    @if ($user && $user->hasPermission('Update Document Assignment Status'))
                                                        <th scope="col">Action </th>
                                                    @endif


                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($documentAssignments as $index => $item)
                                                    <tr>
                                                        <th scope="row">{{ $index + 1 }}</th>
                                                        <td>{{ $item->receiver->name }}</td>
                                                        <td>{{ $item->receiverType->name }}</td>
                                                        <td>{{ ucwords(str_replace('_', ' ', $item->documentType->name)) }}
                                                        </td>
                                                        <td>  <a style="color: #1714c9; text-decoration: underline;" href="/review_doc/{{ $item->documentType->name }}/{{ $item->child_id }}">{{ $item->document->name }}</a></td>
                                                        <td>{{ \Carbon\Carbon::parse($item->expires_at)->format('g:i A, d-M-Y') }}
                                                        </td>

                                                        <td> {!! $item->first_viewed_at
                                                            ? '<span class="badge bg-success">Yes</span>'
                                                            : '<span class="badge bg-warning text-dark">Not Yet</span>' !!}</td>
                                                        <td> {!! $item->status
                                                            ? '<span class="badge bg-success">Active</span>'
                                                            : '<span class="badge bg-warning text-dark">Inactive</span>' !!}</td>


                                                        @if ($user && $user->hasPermission('Update Document Assignment Status'))
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-sm {{ $item->status ? 'btn-danger' : 'btn-success' }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#confirmationModal"
                                                                    data-action="{{ route('documents.assigned.toggleStatus', $item->id) }}">
                                                                    {{ $item->status ? 'Deactivate' : 'Activate' }}
                                                                </button>
                                                            </td>
                                                        @endif
                                                @endforeach
                                            </tbody>
                                        </table>
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




<div class="modal fade" id="confirmationModal">
    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to <span id="actionType">activate/deactivate</span> this document assignment?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
<script>
    function fetchReceivers(receiverTypeId) {
        $.ajax({
            url: '/get-active-receivers/' + receiverTypeId,
            type: 'GET',

            success: function(response) {
                console.log(response); // Console the response for debugging

                var receiverSelect = $('#receiver');
                receiverSelect.empty();
                $.each(response.receivers, function(key, receiver) {
                    receiverSelect.append(new Option(receiver.name, receiver.id));
                });
            },
            error: function(xhr, status, error) {
                console.error("Error: ", error); // Console error if AJAX request fails
                console.error("Status: ", status);
                console.error("Response: ", xhr.responseText);
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

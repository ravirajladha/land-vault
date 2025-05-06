<x-app-layout>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nouislider/distribute/nouislider.min.css">

    <x-header />
    <x-sidebar />

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-body">
                <div class="row page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Receivers</a></li>
                    </ol>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="filter cm-content-box box-primary">
                            <div class="content-title SlideToolHeader">
                                <h4>
                                    Search Receivers
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
                                            <form action="{{ route('receivers.index') }}" method="GET" class="row">
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Name</label>
                                                    <input name="name" class="form-control" placeholder="Enter Name"
                                                        value="{{ request()->input('name') }}">
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Email</label>
                                                    <input name="email" class="form-control" placeholder="Enter Email"
                                                        value="{{ request()->input('email') }}">
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Phone</label>
                                                    <input name="phone" class="form-control" placeholder="Enter Phone"
                                                        value="{{ request()->input('phone') }}">
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Receiver Type</label>
                                                    <select class="form-select form-control" name="receiver_type"
                                                        id="single-select-abc1">
                                                        <option value="">Select Receiver Type</option>
                                                        @foreach ($receiverTypes as $type)
                                                            <option value="{{ $type->id }}"
                                                                {{ request()->input('receiver_type') == $type->id ? 'selected' : '' }}>
                                                                {{ $type->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Document </label>
                                                    <select class="form-select form-control" id="single-select-abc2"
                                                        name="doc_id">
                                                        <option value="">Select Document </option>
                                                        @foreach ($documents as $doc)
                                                            <option value="{{ $doc->id }}"
                                                                {{ request()->input('doc_id') == $doc->id ? 'selected' : '' }}>
                                                                {{ $doc->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-12 d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-primary">Filter</button>
                                                    <a href="{{ route('receivers.export', request()->all()) }}"
                                                        class="btn btn-success ms-2">Export to Excel</a>
                                                    <a href="{{ url('/') }}/receivers"
                                                        class="btn btn-dark ms-2">Reset</a>
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
                                <div class="card-header">
                                    <h4>Receivers</h4>

                                    @if ($user && $user->hasPermission('Add Receivers'))
                                        <button type="button" class="btn btn-success mb-2 float-end"
                                            data-bs-toggle="modal" data-bs-target="#exampleModalCenter1"><i
                                                class="fas fa-plus-square"></i>&nbsp;Add
                                            Receiver</button>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">

                                        <table id="example3" class="display">

                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl. No.</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Phone</th>
                                                    <th scope="col">City</th>
                                                    <th scope="col">Email Id</th>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">No. of Document</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">View Assigned Documents</th>
                                                    @if ($user && $user->hasPermission('Update Receivers'))
                                                        <th scope="col">Action</th>
                                                    @endif
                                                    @if ($user && $user->hasPermission('Assign Document'))
                                                        <th scope="col">Assign Document</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $index => $item)
                                                    <tr>
                                                        <th scope="row">{{ $index + 1 }}</th>
                                                        <td>{{ $item->name }}</td>
                                                        <td>{{ $item->phone }}</td>
                                                        <td>{{ $item->city }}</td>
                                                        <td>{{ $item->email }}</td>
                                                        <td>{{ optional($item->receiverType)->name }}</td>
                                                        <td> {{ $item->document_assignments_count }}
                                                        </td>

                                                        <td>{!! $item->status
                                                            ? '<span class="badge bg-success">Active</span>'
                                                            : '<span class="badge bg-warning text-dark">Inactive</span>' !!}</td>
                                                        <td> <a href="/user-assign-documents/{{ $item->id }}"
                                                                title="View Assigned Documents"><u><b><span
                                                                            class="btn btn-secondary btn-sm edit-btn"><i
                                                                                class="fas fa-eye"></i></span></b></u></a>
                                                        </td>
                                                        <!-- Assuming you have a relation to get the receiver type name -->
                                                        @if ($user && $user->hasPermission('Update Receivers'))
                                                            <td>
                                                                <button title="Edit Reciever"
                                                                    class="btn btn-primary btn-sm edit-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#exampleModalCenter"
                                                                    data-receiver-id="{{ $item->id }}"
                                                                    data-receiver-name="{{ $item->name }}"
                                                                    data-receiver-phone="{{ $item->phone }}"
                                                                    data-receiver-city="{{ $item->city }}"
                                                                    data-receiver-email="{{ $item->email }}"
                                                                    data-receiver-type-id="{{ $item->receiver_type_id }}"
                                                                    data-receiver-status="{{ $item->status }}"><i
                                                                        class="fas fa-pencil-square"></i>&nbsp;</button>
                                                            </td>
                                                        @endif
                                                        @if ($user && $user->hasPermission('Assign Document'))
                                                            <td>
                                                                <button class="btn btn-success btn-sm assign-doc-btn"
                                                                    title="Assign Document to the Receiver"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#assignDocumentModal"
                                                                    data-receiver-id="{{ $item->id }}"
                                                                    data-receiver-type-id="{{ $item->receiver_type_id }}"><i
                                                                        class="fas fa-plus-square"></i>&nbsp;
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
              

            </div>
        </div>
    </div>


    {{-- add receiver modal form starts --}}
    <div class="modal fade" id="exampleModalCenter1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Receiver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form theme-form projectcreate">
                        <form id="myAjaxForm" action="{{ route('receivers.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="receiverName" class="form-label">Name&nbsp;<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" id="receiverName"
                                            placeholder="Enter Receiver's Name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="receiverEmail" class="form-label">Email&nbsp;<span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" id="receiverEmail"
                                            placeholder="Enter Receiver's Email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="receiverPhone" class="form-label">Phone&nbsp;<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="phone" id="receiverPhone"
                                            placeholder="Enter Receiver's Phone Number" pattern="\d{0,10}$"
                                            title="Please enter a valid phone number with up to 10 digits."
                                            maxlength="10">
                                    </div>
                                    <div class="mb-3">
                                        <label for="receiverCity" class="form-label">City&nbsp;<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="city" id="receiverCity"
                                            placeholder="Enter Receiver's City">
                                    </div>
                                    <div class="mb-3">
                                        <label for="receiverType" class="form-label">Receiver
                                            Type&nbsp;<span class="text-danger">*</span></label>
                                        <select class="form-control" id="receiverType" name="receiver_type_id">
                                            <option selected value="">Select Receiver Type</option>
                                            @foreach ($receiverTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Form</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    {{-- edit receiver modal starts --}}
    {{-- assign document to individual receiver starts --}}

    <div class="modal fade" id="assignDocumentModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Update Form -->
                    <form action="{{ url('/') }}/assign-documents-to-receiver" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="receiverId" name="id">
                        <!-- Hidden fields inside the form -->
                        <input type="hidden" id="modalReceiverId" name="receiver_id">
                        <input type="hidden" id="modalReceiverTypeId" name="receiver_type">
                        <input type="hidden" name="location" value="user">

                        <div class="row">
                            <x-document-type-select :is_status="1" />



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


    {{-- assign document to individual receiver ends --}}
    <div class="modal fade" id="exampleModalCenter">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Receiver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Update Form -->
                    <form id="updateReceiverForm">
                        <input type="hidden" id="receiverId" name="id">
                        <div class="mb-3">
                            <label for="receiverName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="receiverName" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="receiverPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="receiverPhone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="receiverCity" class="form-label">City</label>
                            <input type="text" class="form-control" id="receiverCity" name="city">
                        </div>
                        <div class="mb-3">
                            <label for="receiverEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="receiverEmail" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="receiverType" class="form-label">Receiver
                                Type</label>
                            <select class="form-control" id="receiverType" name="receiver_type_id">
                                @foreach ($receiverTypes as $type)
                                    <option value="{{ $type->id }}">
                                        {{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="receiverStatus" class="form-label">Status</label>
                            <select class="form-control" id="receiverStatus" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitUpdateForm()">Update
                        changes</button>
                </div>
            </div>
        </div>
    </div>
    {{-- edit receiver modal ends --}}

    @include('layouts.footer')


</x-app-layout>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}

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

<script>
    $(document).ready(function() {
        $('#myAjaxForm').on('submit', function(e) {
            e.preventDefault(); // prevent the form from 'submitting'

            var url = $(this).attr('action'); // get the target URL
            var formData = new FormData(this); // create a FormData object

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false, // tell jQuery not to process the data
                contentType: false, // tell jQuery not to set contentType
                success: function(response) {

                    if (response.success) {
                        toastr.success(response.success); // Display success toast
                    }
                    location.reload(true);

                    loadUpdatedReceivers();
                    $('#myAjaxForm')[0].reset();
                },
                error: function(error) {
                    console.log(error);
                    toastr.warning("Wrong format data added or duplicate result found");
                    if (error.responseJSON && error.responseJSON.error) {
                        toastr.error(error.responseJSON.error); // Display error toast
                    }
                }
            });
        });
    });


    // Update the receiver list
    function loadUpdatedReceivers() {
        $.ajax({
            url: '/get-updated-receivers', // Make sure this URL is defined in your routes
            type: 'GET',
            success: function(receivers) {
                var newTableContent = '';
                var assignDocButton =
                    '<button title="Assign Document to the Receiver" class="btn btn-success assign-doc-btn" data-bs-toggle="modal" data-bs-target="#assignDocumentModal" data-receiver-id="' +
                    receiver.id + '" data-receiver-type-id="' + receiver.receiver_type_id +
                    '"><i class="fas fa-plus-square"></i>&nbsp;</button>';
                $.each(receivers, function(index, receiver) {
                    var statusBadge = receiver.status ?
                        '<span class="badge bg-success">Active</span>' :
                        '<span class="badge bg-warning text-dark">Inactive</span>';
                    newTableContent += '<tr>' +
                        '<th scope="row">' + (index + 1) + '</th>' +
                        '<td>' + receiver.name + '</td>' +
                        '<td>' + receiver.phone + '</td>' +
                        '<td>' + receiver.city + '</td>' +
                        '<td>' + receiver.email + '</td>' +

                        '<td>' + receiver.receiver_type_name + '</td>' +
                        '<td>' + receiver.document_assignments_count + '</td>' +


                        '<td>' + statusBadge + '</td>' +
                        '<td><a title="View Assigned Documents" href="/user-assign-documents/' +
                        receiver.id +
                        '"><u><b><span class="badge bg-secondary"><i class="fas fa-eye"></i> </span></b></u></a></td>' +
                        // Make sure you have the receiver type name available
                        '<td><Button title="Edit Receiver" class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" data-receiver-id="' +
                        receiver.id + '" data-receiver-name="' + receiver.name +
                        '" data-receiver-phone="' + receiver.phone + '" data-receiver-city="' +
                        receiver.city + '" data-receiver-email="' + receiver.email +
                        '" data-receiver-type-id="' + receiver.receiver_type_id +
                        '" data-receiver-status="' + receiver.status +
                        '"><i class="fas fa-pencil-square"></i></Button></td>' +

                        '<td>' + assignDocButton + '</td>' +

                        '</tr>';
                });
                $('#example3 tbody').html(newTableContent);
            }
        });
    }
    // Pre-fill the update modal form when the Edit button is clicked
    $(document).ready(function() {
        $('#example3').on('click', '.edit-btn', function() {
            var receiverId = $(this).data('receiver-id');
            var receiverName = $(this).data('receiver-name');
            var receiverPhone = $(this).data('receiver-phone');
            var receiverCity = $(this).data('receiver-city');
            var receiverEmail = $(this).data('receiver-email');
            var receiverTypeId = $(this).data('receiver-type-id');
            var receiverStatus = $(this).data('receiver-status');

            // Update the form fields
            $('#updateReceiverForm #receiverId').val(receiverId);
            $('#updateReceiverForm #receiverName').val(receiverName);
            $('#updateReceiverForm #receiverPhone').val(receiverPhone);
            $('#updateReceiverForm #receiverCity').val(receiverCity);
            $('#updateReceiverForm #receiverEmail').val(receiverEmail);
            $('#updateReceiverForm #receiverTypeId').val(receiverTypeId);
            $('#updateReceiverForm #receiverStatus').val(receiverStatus);
        });
    });

    // Submit the updated receiver form
    function submitUpdateForm() {
        var formData = $('#updateReceiverForm').serialize();
        // console.log(formData);
        // AJAX call to update the receiver
        $.ajax({
            url: '/update-receiver', // Replace with your server's update URL
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#exampleModalCenter').modal('hide');
                toastr.success(response.success);
                location.reload(true);

                loadUpdatedReceivers(); // Update the receivers list
            },
            error: function(error) {
                toastr.error('An error occurred.');
                console.error(error);
            }
        });
    }

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

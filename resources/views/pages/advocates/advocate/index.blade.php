<x-app-layout>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}

    <x-header />
    <x-sidebar />

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-body">
                <div class="row page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Advocates</a></li>
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
                                            <form action="{{ route('advocates.index') }}" method="GET" class="row">
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

                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label">Document </label>
                                                    <select class="form-select form-control"  id="single-select-abc2"
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
                                                    <button type="submit" class="btn btn-primary">   <i class="fas fa-filter"></i>&nbsp;Filter</button>
                                                    <a href="{{ route('advocates.export', request()->all()) }}" class="btn btn-success ms-2">   <i class="fas fa-file-export"></i>&nbsp;Export to Excel</a>
                                                    <a href="{{ url('/') }}/advocates" class="btn btn-dark ms-2">  <i
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>Advocates</h4>
                                <span class="float-end">
                                    <button id="exportButton" class="btn btn-secondary btn-sm"
                                        style="margin-right: 1px;">
                                        <i class="fas fa-file-export"></i>&nbsp;Export
                                    </button>
                                    @if ($user && $user->hasPermission('Add Assigned Docs to Advocate'))
                                        <button type="button" class="btn btn-warning btn-sm" style="margin-right: 1px;"
                                            data-bs-toggle="modal" data-bs-target="#addDocumentTypeModal">
                                            <i class="fas fa-plus-square"></i>&nbsp; Bulk Upload (csv)
                                        </button>
                                    @endif
                                    @if ($user && $user->hasPermission('Add Advocates'))
                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#exampleModalCenter1">
                                            <i class="fas fa-plus-square"></i>&nbsp;Add Advocate
                                        </button>
                                    @endif
                                </span>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">

                                    <table id="example3" class="display">

                                        <thead>
                                            <tr>
                                                <th scope="col">Sl. No.</th>
                                                <th scope="col">Advocate Id</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Phone</th>
                                                <th scope="col">Address</th>
                                                <th scope="col">Email Id</th>

                                                <th scope="col">No. of Document</th>
                                                <th scope="col">Status</th>
                                                {{-- <th scope="col">View Assigned Documents</th> --}}

                                                <th scope="col">Action</th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $index => $item)
                                                <tr>
                                                    <th scope="row">{{ $index + 1 }}</th>
                                                    <td>Advocate: {{ $item->id }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->phone }}</td>
                                                    <td>{{ $item->address }}</td>
                                                    <td>{{ $item->email }}</td>

                                                    <td> {{ $item->document_assignments_count }}
                                                    </td>

                                                    <td>{!! $item->status
                                                        ? '<span class="badge bg-success">Active</span>'
                                                        : '<span class="badge bg-warning text-dark">Inactive</span>' !!}</td>

                                                    <!-- Assuming you have a relation to get the receiver type name -->



                                                    <td>
                                                        <div class="d-flex">
                                                            @if ($user && $user->hasPermission('View Assigned Docs to Advocate'))
                                                                <a href="/advocate-assign-documents/{{ $item->id }}"
                                                                    title="View Assigned Documents" class="me-2">
                                                                    <span class="btn btn-secondary btn-sm edit-btn"><i
                                                                            class="fas fa-eye"></i></span>
                                                                </a>
                                                            @endif
                                                            @if ($user && $user->hasPermission('Update Advocates'))
                                                                <button title="Edit Receiver"
                                                                    class="btn btn-primary btn-sm edit-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#exampleModalCenter"
                                                                    data-receiver-id="{{ $item->id }}"
                                                                    data-receiver-name="{{ $item->name }}"
                                                                    data-receiver-phone="{{ $item->phone }}"
                                                                    data-receiver-address="{{ $item->address }}"
                                                                    data-receiver-email="{{ $item->email }}"
                                                                    data-receiver-type-id="{{ $item->receiver_type_id }}"
                                                                    data-receiver-status="{{ $item->status }}">
                                                                    <i class="fas fa-pencil-square"></i>
                                                                </button>
                                                            @endif
                                                            @if ($user && !$user->hasPermission('View Assigned Docs to Advocate') && !user->hasPermission('Update Advocates'))
                                                                --
                                                            @endif
                                                        </div>
                                                    </td>



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
                    <h5 class="modal-title">Add Advocate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form theme-form projectcreate">
                        <form id="myAjaxForm" action="{{ route('advocates.store') }}" method="POST"
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
                                        <label for="receiverCity" class="form-label">Address&nbsp;<span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" name="address" id="receiverCity" placeholder="Enter Receiver's Address"></textarea>

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


    {{-- assign document to individual receiver ends --}}
    <div class="modal fade" id="exampleModalCenter">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Advocate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Update Form -->
                    <form id="updateAdvocateForm">
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
                            <label for="receiverCity" class="form-label">Address</label>
                            <textarea class="form-control" name="address" id="receiverAddress" placeholder="Enter Receiver's Address"></textarea>

                        </div>
                        <div class="mb-3">
                            <label for="receiverEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="receiverEmail" name="email">
                        </div>

                        <div class="mb-3">
                            <label for="receiverStatus" class="form-label">Status</label>
                            <select class="form-control"  id="single-select-abc1" name="status">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="modal fade" id="addDocumentTypeModal" tabindex="-1" aria-labelledby="addDocumentTypeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="card-title">Bulk Upload Assign Document to Advocate</h4>

                <div class="d-flex align-items-center">
                    <a href="/assets/sample/advocate_documents_sample.csv" download="sample.csv">
                        <button type="button" class="btn btn-dark btn-sm">
                            <i class="fas fa-download"></i>&nbsp; Download Sample CSV File
                        </button>
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <div class="card overflow-hidden">

                    <div class="card-body">
                        <form action="{{ url('/') }}/bulk-upload-advocate-assign-document" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label class="form-label">Bulk Upload (in csv file format)</label>
                                    <div class="fallback">
                                        <input name="document" type="file" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
            var receiverAddress = $(this).data('receiver-address');
            var receiverEmail = $(this).data('receiver-email');
            var receiverTypeId = $(this).data('receiver-type-id');
            var receiverStatus = $(this).data('receiver-status');

            // Update the form fields
            $('#updateAdvocateForm #receiverId').val(receiverId);
            $('#updateAdvocateForm #receiverName').val(receiverName);
            $('#updateAdvocateForm #receiverPhone').val(receiverPhone);
            $('#updateAdvocateForm #receiverAddress').val(receiverAddress);
            $('#updateAdvocateForm #receiverEmail').val(receiverEmail);
            $('#updateAdvocateForm #receiverTypeId').val(receiverTypeId);
            $('#updateAdvocateForm #receiverStatus').val(receiverStatus);
        });
    });

    // Submit the updated receiver form
    function submitUpdateForm() {
        var formData = $('#updateAdvocateForm').serialize();
        // console.log(formData);
        // AJAX call to update the receiver
        $.ajax({
            url: '/update-advocate', // Replace with your server's update URL
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
</script>
<script>
    document.getElementById('exportButton').addEventListener('click', function() {
        var table = document.getElementById('example3'); // Your table ID
        var rows = table.querySelectorAll('tr');
        var csv = [];

        for (var i = 0; i < rows.length; i++) {
            var row = [],
                cols = rows[i].querySelectorAll('td, th');

            for (var j = 0; j < cols.length - 1; j++) {
                // Clean the text content from the cell and escape double quotes
                var data = cols[j].innerText.replace(/"/g, '""');
                data = '"' + data + '"';
                row.push(data);
            }
            csv.push(row.join(','));
        }

        downloadCSV(csv.join('\n'));
    });

    function downloadCSV(csv) {
        var csvFile;
        var downloadLink;

        // CSV file
        csvFile = new Blob([csv], {
            type: "text/csv"
        });

        // Download link
        downloadLink = document.createElement("a");

        // File name
        downloadLink.download = 'export.csv';

        // Create a link to the file
        downloadLink.href = window.URL.createObjectURL(csvFile);

        // Hide download link
        downloadLink.style.display = "none";

        // Add the link to DOM
        document.body.appendChild(downloadLink);

        // Click download link
        downloadLink.click();
    }
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
</script>

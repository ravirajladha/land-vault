<x-app-layout>


    <x-header />
    <x-sidebar/>

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
                {{-- <div class="row">
					<div class="col-xl-12">
						
								<div class="filter cm-content-box box-primary">
									<div class="content-title SlideToolHeader">
										<div class="cpa">
											Add Blog Category
										</div>
										<div class="tools">
											<a href="javascript:void(0);" class="expand handle"><i class="fal fa-angle-down"></i></a>
										</div>
									</div>
									<div class="cm-content-body  form excerpt">
										<div class="card-body">
											<div class="mb-3">
											  <label  class="form-label">Name</label>
											  <input type="text" class="form-control" placeholder="Name">
											</div>
											<div class="mb-3">
												  <label  class="form-label">Slug</label>
												  <input type="text" class="form-control" placeholder="Slug">
											</div>
											<div class="mb-3">
											  <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>
											  <textarea class="form-control" id="exampleFormControlTextarea1" rows="8"></textarea>
											</div>
											<div>
												<button type="button" class="btn btn-primary">Save</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							</div> --}}
					

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
                                <form id="myAjaxForm" action="{{ url('/') }}/assign-documents-to-receiver" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="documentType" class="form-label">Document
                                    Type</label>
                                <select class="form-control" id="documentType"
                                    name="document_type" onchange="fetchDocuments(this.value)"
                                    required>
                                    <option value="">Select Document Type</option>
                                    @foreach ($documentTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="document" class="form-label">Document <i><span  style="font-size:10px;">(Only the approved documents are shown here.)</span></i></label>
                                <select class="form-control" id="document" name="document_id"
                                    required>
                                    <option value="">Select Document</option>
                                    <!-- Options will be populated based on Document Type selection -->
                                </select>
                            </div>

                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="receiverType" class="form-label">Receiver
                                    Type</label>
                                <select class="form-control" id="receiverType"
                                    name="receiver_type" onchange="fetchReceivers(this.value)"
                                    required>
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
                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
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
                                 
                                    <div class="table-responsive">
                                        <table id="example3" class="display" style="min-width: 845px">
                                    {{-- <button type="button" class="btn btn-success mb-2 float-end"   data-bs-toggle="modal" data-bs-target="#exampleModalCenter">Assign Document</button> --}}

                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl. No.</th>
                                                    <th scope="col">Receiver Name</th>
                                                    <th scope="col">Receiver Type</th>
                                                
                                                    <th scope="col">Document Type </th>
                                                    <th scope="col">Document Name </th>
                                                    <th scope="col">Expires At </th>

                                                    <th scope="col">Accepted </th>
                                                    <th scope="col">Status </th>
                                                    <th scope="col">Action </th>



                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($documentAssignments as $index => $item)
                                                    <tr>
                                                        <th scope="row">{{ $index + 1 }}</th>
                                                        <td>{{ $item->receiver->name }}</td>
                                                        <td>{{ $item->receiverType->name }}</td>
                                                        <td>{{ $item->documentType->name }}</td>
                                                        <td>
                                                            <a href="/review_doc/{{ $item->documentType->name }}/{{ $item->child_id }}" style="color: #1714c9; text-decoration: underline;">
                                                                {{ $item->document->name }}
                                                            </a>
                                                        </td>
                                                        
                                                        <td>{{ $item->expires_at }}</td>


                                                        <td> {!! $item->first_viewed_at
                                                            ? '<span class="badge bg-success">Yes</span>'
                                                            : '<span class="badge bg-warning text-dark">Not Yet</span>' !!}</td>
                                                        <td> {!! $item->status
                                                            ? '<span class="badge bg-success">Active</span>'
                                                            : '<span class="badge bg-warning text-dark">Inactive</span>' !!}</td>

                                                        <td>
                                                            @if ($item->status)
                                                                <button class="btn btn-sm btn-danger toggle-status"
                                                                    data-id="{{ $item->id }}"
                                                                    data-status="{{ $item->status }}">Deactivate</button>
                                                            @else
                                                                <button class="btn btn-sm btn-success toggle-status"
                                                                    data-id="{{ $item->id }}"
                                                                    data-status="{{ $item->status }}">Activate</button>
                                                            @endif
                                                        </td>




                                                        {{-- <td>
                                                    <a href="/document_field/{{ $item->name  }}"><button class="btn btn-success">Add Field</button></a>
                                                    <a href="/view_doc/{{ $item->name  }}"><button class="btn btn-primary">View</button></a></td> --}}
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
    </div>

    @include('layouts.footer')


</x-app-layout>
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

    // Fetch receivers based on the selected receiver type


    function fetchReceivers(receiverTypeId) {
        $.ajax({
            url: '/get-receivers/' + receiverTypeId,
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
        const buttons = document.querySelectorAll('.toggle-status');
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-id');
                const currentStatus = this.getAttribute('data-status') === '1';

                fetch(`/toggle-assigned-document-status/${itemId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            status: !currentStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(
                                `Document has been ${data.newStatus ? 'activated' : 'deactivated'}.`
                                );
                                location.reload(true);
                            // Update button class, text, and data-status attribute
                            this.setAttribute('data-status', data.newStatus ? '1' : '0');
                            if (data.newStatus) {
                                this.classList.remove('btn-success');
                                this.classList.add('btn-danger');
                                this.textContent = 'Deactivate';
                            } else {
                                this.classList.remove('btn-danger');
                                this.classList.add('btn-success');
                                this.textContent = 'Activate';
                            }

                            // Update the status badge
                            const statusCell = this.closest('tr').querySelector(
                                'td:nth-last-child(2)');
                            statusCell.innerHTML = data.newStatus ?
                                '<span class="badge bg-success">Active</span>' :
                                '<span class="badge bg-warning text-dark">Inactive</span>';
                        } else {
                            toastr.success('Success.');
                        }
                    })
                    .catch(error => {
                        location.reload(true);
                        toastr.success('Success.');
                        // toastr.error('An error occurred while changing status.');
                    });
            });
        });
    });
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
    <script></script>
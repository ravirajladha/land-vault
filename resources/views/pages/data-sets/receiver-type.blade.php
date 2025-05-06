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
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Set</a></li>
                    </ol>
                </div>


                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="title">Receiver Type</h4>
                                    <button type="button" class="btn btn-success btn-sm float-end"
                                        data-bs-toggle="modal" data-bs-target="#addDocumentTypeModal1">
                                        <i class="fas fa-plus"></i>&nbsp; Add Receiver Type
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        {{-- <div class="table-responsive"> --}}
                                        {{-- <table id="example3" class="display" style="min-width: 845px"> --}}
                                        <table id="example3" class="display">

                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl. No.</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $index => $item)
                                                    <tr>
                                                        <th scope="row">{{ $index + 1 }}</th>
                                                        <td>{{ $item->name }}</td>
                                                        <td> <button class="btn btn-primary edit-btn"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalCenter"
                                                                data-set-id="{{ $item->id }}"
                                                                data-set-name="{{ $item->name }}"><i
                                                                    class="fas fa-pencil"></i>&nbsp;Edit</button></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <!-- Modal (outside the loop) -->
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModalCenter">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Receiver Type</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Update Form -->
                                                        <form id="updateSetForm">
                                                            <div class="mb-3">
                                                                <label for="setName" class="form-label">Receiver Type
                                                                    Name</label>
                                                                <input type="text" class="form-control"
                                                                    id="setName" name="name">
                                                                <input type="hidden" id="setId" name="id">
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary"
                                                            onclick="submitUpdateForm()">Save changes</button>
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

            </div>
        </div>
    </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addDocumentTypeModal1" tabindex="-1" aria-labelledby="addDocumentTypeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDocumentTypeModalLabel">Add Recceiver Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="myAjaxForm" action="{{ url('/') }}/add-receiver-type" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form theme-form projectcreate">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Receiver Type
                                        </label>
                                        <input type="text" class="form-control" name="name"
                                            id="exampleInputEmail1" aria-describedby="emailHelp"
                                            placeholder="Enter Set Name">
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="" class="btn-link"></a>

                                    <button class="btn btn-secondary" type="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    @include('layouts.footer')


</x-app-layout>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}

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

                    loadUpdatedSets();
                    $('#myAjaxForm')[0].reset();
                },
                error: function(error) {
                    console.log(error);
                    toastr.warning("Duplicate set found");
                    if (error.responseJSON && error.responseJSON.error) {
                        toastr.error(error.responseJSON.error); // Display error toast
                    }
                }
            });
        });
    });


    function loadUpdatedSets() {
        $.ajax({
            url: '/get-updated-receiver-types',
            type: 'GET',
            success: function(sets) {
                var newTableContent = '';
                $.each(sets, function(index, set) {
                    newTableContent += '<tr>' +
                        '<th scope="row">' + (index + 1) + '</th>' +
                        '<td>' + set.name + '</td>' +
                        '<td><Button class="btn btn-primary">Edit</Button></td>' +
                        '</tr>';
                });
                $('#example3 tbody').html(newTableContent);
            }
        });
    }
    //set modal update
    $(document).ready(function() {
        $('.edit-btn').on('click', function() {
            var setId = $(this).data('set-id');
            var setName = $(this).data('set-name');

            // Prefill the form
            $('#updateSetForm #setName').val(setName);
            $('#updateSetForm #setId').val(setId);
        });
    });

    function submitUpdateForm() {
        var formData = $('#updateSetForm').serialize(); // Serialize form data
        console.log(formData);
        // AJAX call to update the set
        $.ajax({
            url: '/update-receiver-type', // Replace with your server's update URL
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                    'content') // Ensure this meta tag is available in your HTML
            },
            success: function(response) {
                // Handle success (e.g., close modal, show message, update table)
                toastr.success(response.success);
                location.reload(true);

                loadUpdatedSets();
            },
            error: function(error) {
                // Handle error
            }
        });
    }
</script>

<x-app-layout>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <x-header />
    <x-sidebar />

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-body">
                <div class="row page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>

                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Set</a></li>
                    </ol>
                </div>


                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title">Sets</h5>
                                    @if ($user && $user->hasPermission('Add Sets'))
                                        <button type="button" class="btn btn-success btn-sm float-end"
                                            data-bs-toggle="modal" data-bs-target="#addDocumentTypeModal">
                                            <i class="fas fa-plus-square"></i>&nbsp; Add Set
                                        </button>
                                    @endif
                                </div>
                                <div class="card-body">

                                    <div class="table-responsive">
                                        {{-- <div class="table-responsive"> --}}
                                        {{-- <table id="example3" class="display" style="min-width: 845px"> --}}
                                        <table id="example2" class="display">
                                            {{-- <table  class="table table-responsive-md"> --}}
                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl. No.</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Number of Documents</th>
                                                    @if ($user && $user->hasPermission('View Documents from Sets'))
                                                        <th scope="col">View Documents</th>
                                                    @endif
                                                    @if ($user && $user->hasPermission('Update Sets'))
                                                        <th scope="col">Action</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $index => $item)
                                                    <tr>
                                                        <th scope="row">{{ $index + 1 }}</th>
                                                        <td>{{ucwords($item->name) }}</td>
                                                        <td>{{ $setCounts[$item->id] ?? 0 }}</td>
                                                        <!-- Display the count for each set -->
                                                        @if ($user && $user->hasPermission('View Documents from Sets'))
                                                            <td><a href="/documents-for-set/{{ $item->id }}"><button
                                                                        class="btn btn-secondary edit-btn"><i
                                                                            class="fas fa-eye"></i>&nbsp;View</button></a>
                                                            </td>
                                                        @endif
                                                        @if ($user && $user->hasPermission('Update Sets'))
                                                            <td> <button class="btn btn-primary edit-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#exampleModalCenter"
                                                                    data-set-id="{{ $item->id }}"
                                                                    data-set-name="{{ $item->name }}"><i
                                                                        class="fas fa-pencil"></i>&nbsp;Edit</button>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="row">
                                            <div class="col">
                                                {{ $data->links('vendor.pagination.custom') }}
                                            </div>
                                        </div>
                                        <!-- Modal (outside the loop) -->
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModalCenter">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Set</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Update Form -->
                                                        <form id="updateSetForm">
                                                            <div class="mb-3">
                                                                <label for="setName" class="form-label">Update Set
                                                                    Name&nbsp;<span
                                                                    class="text-danger">*</span></label>
                                                                <input type="text" class="form-control"
                                                                    id="setName" name="name" required>
                                                                <input type="hidden" id="setId" name="id">
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary"
                                                            onclick="submitUpdateForm()">Submit Form</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- modal end --}}
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
    <div class="modal fade" id="addDocumentTypeModal" tabindex="-1" aria-labelledby="addDocumentTypeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDocumentTypeModalLabel">Add Set</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="myAjaxForm" action="{{ url('/') }}/add_set" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="documentType" class="form-label">Enter Set Name&nbsp;<span
                                class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="exampleInputEmail1"
                                aria-describedby="emailHelp" placeholder="Enter Set Name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @include('layouts.footer')


</x-app-layout>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
<!-- Latest compiled and minified jQuery -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<script>
    // $(document).ready(function() {
    //     $('#myAjaxForm').on('submit', function(e) {
    //         e.preventDefault(); // prevent the form from 'submitting'

    //         var url = $(this).attr('action'); // get the target URL
    //         var formData = new FormData(this); // create a FormData object

    //         $.ajax({
    //             url: url,
    //             type: 'POST',
    //             data: formData,
    //             processData: false, // tell jQuery not to process the data
    //             contentType: false, // tell jQuery not to set contentType
    //             success: function(response) {

    //                 if (response.success) {
    //                     toastr.success(response.success); // Display success toast
    //                 }
    //                 loadUpdatedSets();
    //             location.reload(true);
                    
    //                 $('#myAjaxForm')[0].reset();
    //             },
    //             error: function(error) {
    //                 console.log(error);
    //                 toastr.warning("Duplicate set found");
    //                 if (error.responseJSON && error.responseJSON.error) {
    //                     toastr.error(error.responseJSON.error); // Display error toast
    //                 }
    //             }
    //         });
    //     });
    // });


    // function loadUpdatedSets() {
    //     $.ajax({
    //         url: '/get-updated-sets',
    //         type: 'GET',
    //         success: function(sets) {
    //             var newTableContent = '';
    //             $.each(sets, function(index, set) {
    //                 // Assuming 'set.count' is the property that has the count for each set
    //                 // and 'set.id' is the property that contains the set ID.
    //                 newTableContent += '<tr>' +
    //                     '<th scope="row">' + (index + 1) + '</th>' +
    //                     '<td>' + set.name + '</td>' +
    //                     '<td>' + (set.count ?? 0) + '</td>' + // Display the count for each set
    //                     '<td><a href="/documents-for-set/' + set.id +
    //                     '"><button class="btn btn-secondary"><i class="fas fa-eye"></i>&nbsp;View</button></a></td>' +
    //                     '<td> <button class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" data-set-id="' +
    //                     set.id + '" data-set-name="' + set.name +
    //                     '"><i class="fas fa-pencil"></i>&nbsp;Edit</button></td>' +
    //                     '</tr>';
    //             });
    //             $('#example3 tbody').html(newTableContent);
    //         }
    //     });
    // }

    // //set modal update
    // $(document).ready(function() {
    //     $('.edit-btn').on('click', function() {
    //         var setId = $(this).data('set-id');
    //         var setName = $(this).data('set-name');

    //         // Prefill the form
    //         $('#updateSetForm #setName').val(setName);
    //         $('#updateSetForm #setId').val(setId);
    //     });
    // });

    // function submitUpdateForm() {
    //     var formData = $('#updateSetForm').serialize(); // Serialize form data
    //     console.log(formData);
    //     // AJAX call to update the set
    //     $.ajax({
    //         url: '/update-set', // Replace with your server's update URL
    //         type: 'POST',
    //         data: formData,
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
    //                 'content') // Ensure this meta tag is available in your HTML
    //         },
    //         success: function(response) {
    //             // Handle success (e.g., close modal, show message, update table)
    //             toastr.success(response.success);
    //             location.reload(true);

    //             loadUpdatedSets();
    //         },
    //         error: function(error) {
    //             // Handle error
    //         }
    //     });
    // }

    $(document).ready(function() {
    // Attach event listener to dynamically generated edit buttons
    $(document).on('click', '.edit-btn', function() {
        var setId = $(this).data('set-id');
        var setName = $(this).data('set-name');

        // Prefill the form with the set's data
        $('#updateSetForm #setName').val(setName);
        $('#updateSetForm #setId').val(setId);
    });

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
                loadUpdatedSets();
                location.reload(true);

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
        url: '/get-updated-sets',
        type: 'GET',
        success: function(sets) {
            var newTableContent = '';
            $.each(sets, function(index, set) {
                newTableContent += '<tr>' +
                    '<th scope="row">' + (index + 1) + '</th>' +
                    '<td>' + set.name + '</td>' +
                    '<td>' + (set.count ?? 0) + '</td>' +
                    '<td><a href="/documents-for-set/' + set.id +
                    '"><button class="btn btn-secondary"><i class="fas fa-eye"></i>&nbsp;View</button></a></td>' +
                    '<td> <button class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" data-set-id="' +
                    set.id + '" data-set-name="' + set.name +
                    '"><i class="fas fa-pencil"></i>&nbsp;Edit</button></td>' +
                    '</tr>';
            });
            $('#example3 tbody').html(newTableContent);
        }
    });
}

function submitUpdateForm() {
    var formData = $('#updateSetForm').serialize(); // Serialize form data
    console.log(formData);
    // AJAX call to update the set
    $.ajax({
        url: '/update-set', // Replace with your server's update URL
        type: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Ensure this meta tag is available in your HTML
        },
        success: function(response) {
            toastr.success(response.success);
            location.reload(true);
            loadUpdatedSets();
        },
        error: function(error) {
            console.log(error);
            toastr.error('Failed to update the set.');
        }
    });
}

</script>
{{-- the above submission or updation was able to do from normal posts, and was achieved asynchronoulsy. but due to regular clients feedback, it was stopped and normal call was made, but the check of duplicacy of the sets are achieved through ajax --}}
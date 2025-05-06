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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Subcategory</a></li>
                    </ol>
                </div>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="title">Subcategory</h4>
                                    <button type="button" class="btn btn-success btn-sm float-end"
                                        data-bs-toggle="modal" data-bs-target="#addSubcategoryModal">
                                        <i class="fas fa-plus"></i>&nbsp; Add Subcategory
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example3" class="display">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl. No.</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Category</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $index => $item)
                                                    <tr>
                                                        <th scope="row">{{ $index + 1 }}</th>
                                                        <td>{{ $item->name }}</td>
                                                        <td>{{ $item->category->name }}</td>
                                                        <td> <button class="btn btn-primary edit-btn"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editSubcategoryModal"
                                                                data-subcategory-id="{{ $item->id }}"
                                                                data-subcategory-name="{{ $item->name }}"
                                                                data-category-id="{{ $item->category_id }}"><i
                                                                    class="fas fa-pencil"></i>&nbsp;Edit</button></td>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addSubcategoryModal" tabindex="-1" aria-labelledby="addSubcategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubcategoryModalLabel">Add Subcategory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addSubcategoryForm" action="{{ url('/') }}/subcategories" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form theme-form projectcreate">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="subcategoryName" class="form-label">Subcategory Name</label>
                                        <input type="text" class="form-control" name="name" id="subcategoryName" placeholder="Enter Subcategory Name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="categorySelect" class="form-label">Category</label>
                                        <select class="form-control" name="category_id" id="categorySelect">
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-secondary" type="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editSubcategoryModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Subcategory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Update Form -->
                    <form id="updateSubcategoryForm">
                        <div class="mb-3">
                            <label for="editSubcategoryName" class="form-label">Subcategory Name</label>
                            <input type="text" class="form-control" id="editSubcategoryName" name="name">
                            <input type="hidden" id="editSubcategoryId" name="id">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitUpdateSubcategoryForm()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')

</x-app-layout>

<script>
    $(document).ready(function() {
        $('#addSubcategoryForm').on('submit', function(e) {
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

                    $('#addSubcategoryForm')[0].reset();
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        for (var field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                toastr.warning(errors[field][0]); // Display the first validation error message
                            }
                        }
                    } else {
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'An error occurred';
                        toastr.warning(errorMessage);
                    }
                }
            });
        });

        $('.edit-btn').on('click', function() {
            var subcategoryId = $(this).data('subcategory-id');
            var subcategoryName = $(this).data('subcategory-name');

            // Prefill the form
            $('#updateSubcategoryForm #editSubcategoryName').val(subcategoryName);
            $('#updateSubcategoryForm #editSubcategoryId').val(subcategoryId);
        });
    });

    function submitUpdateSubcategoryForm() {
        var formData = $('#updateSubcategoryForm').serialize(); // Serialize form data

        $.ajax({
            url: '/subcategories', // Replace with your server's update URL
            type: 'PUT',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Ensure this meta tag is available in your HTML
            },
            success: function(response) {
                toastr.success(response.success);
                location.reload(true);
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    for (var field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            toastr.warning(errors[field][0]); // Display the first validation error message
                        }
                    }
                } else {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'An error occurred';
                    toastr.warning(errorMessage);
                }
            }
        });
    }
</script>

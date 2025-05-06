<x-app-layout>


    <x-header />
    <x-sidebar />

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">

            <div class="page-body">
                <div class="container-fluid">
                    <div class="row page-titles">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <h3>
                                    Document Dynamic Fields</h3>
                            </div>
                            <div class="col-12 col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/fashboard">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="/add_fields_first">Document Field</a></li>
                                    <li class="breadcrumb-item active"><a href="javascript:void(0)"> Fields Detail</a>
                                    </li>
                                </ol>
                            </div>
                        </div>

                    </div>
                </div>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif



                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="title">Fields Detail</h4>
                                    @if ($user && $user->hasPermission('Add Document Fields'))
                                        <button type="button" class="btn btn-success btn-sm float-end"
                                            data-bs-toggle="modal" data-bs-target="#addDocumentTypeModal">
                                            <i class="fas fa-plus-square"></i>&nbsp; Add Document Fields
                                        </button>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">

                                        <table id="example3" class="display" style="min-width: 845px">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl. No.</th>
                                                    <th scope="col">Field name</th>
                                                    <th scope="col">Field type</th>
                                                    <th scope="col">Special Display</th>
                                                    @if ($user && $user->hasPermission('Update Document Fields'))
                                                        <th scope="col">Action</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($columnDetails as $index => $column)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ ucwords(str_replace('_', ' ', $column->column_name)) }}
                                                        </td>
                                                        <td>
                                                            @switch($column->data_type)
                                                                @case(1)
                                                                    Text
                                                                @break

                                                                @case(2)
                                                                    Number
                                                                @break

                                                                @case(3)
                                                                    Image
                                                                @break

                                                                @case(4)
                                                                    Pdf Files
                                                                @break

                                                                @case(5)
                                                                    Date
                                                                @break

                                                                @case(6)
                                                                    Video
                                                                @break

                                                                @default
                                                                    Unknown
                                                            @endswitch
                                                        </td>
                                                        <td>
                                                            @if ($column->special)
                                                                <span class="badge badge-success">Active</span>
                                                            @else
                                                                <span class="badge badge-secondary">Inactive</span>
                                                            @endif
                                                        </td>
                                                        
                                                        @if ($user && $user->hasPermission('Update Document Fields'))
                                                            <td>
                                                                <button type="button" class="btn btn-primary btn-sm"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editFieldNameModal{{ $column->column_name }}">
                                                                    <i class="fas fa-pencil-square"></i>&nbsp; Edit
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
    </div>


    @foreach ($columnDetails as $index => $column)
        <!-- Edit Field Name Modal -->
        <div class="modal fade" id="editFieldNameModal{{ $column->column_name }}" tabindex="-1"
            aria-labelledby="editFieldNameModalLabel{{ $column->column_name }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editFieldNameModalLabel{{ $column->column_name }}">Edit Field Name
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- Include the table name in the form action -->
                    <form
                        action="{{ url('/') }}/edit_document_field/{{ $tableName }}/{{ $column->column_name }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="fieldName" class="form-label">Update Field Name <span
                                        class="text-danger">*</span></span></label>
                                <div class="bootstrap-popover d-inline-block float-end mb-2">
                                    <button type="button" class="btn btn-primary btn-sm px-4 " data-bs-container="body"
                                        data-bs-toggle="popover" data-bs-placement="top"
                                        data-bs-content="Provide the Document Field without any space, separated thorugh underscore. It would work other way, but, mostly preferred without space."
                                        title="Verification Guidelines"><i class="fas fa-info-circle"></i></button>
                                </div>
                                <input type="text" class="form-control" name="newFieldName" id="fieldName"
                                    placeholder="Enter Updated Field Name" value="{{ $column->column_name }}" required>
                            </div>
                            <div class="mb-3 form-check">
                                <label class="form-check-label" for="specialCheckbox">Special Display</label>
                                <input type="checkbox" class="form-check-input" id="specialCheckbox" name="specialCheckbox"
                                    @if($column->special) checked @endif>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach



    <!-- Modal -->
    <div class="modal fade" id="addDocumentTypeModal" tabindex="-1" aria-labelledby="addDocumentTypeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDocumentTypeModalLabel">Add Fields to Document: {{ $tableName }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('/') }}/add_document_field" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" class="form-control" name="type" value="{{ ucwords($tableName) }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="documentType" class="form-label">Fields Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="fields[]" id="exampleInputEmail1"
                                aria-describedby="emailHelp" placeholder="Enter Field Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="documentType" class="form-label">Field Type <span
                                    class="text-danger">*</span></label>
                            <select class="form-select form-control" aria-label="Default select example"
                                name="field_type" required>
                                <option selected disabled>--Select Any--</option>
                                <option value="1">Text</option>
                                <option value="2">Number</option>
                                <option value="3">Image</option>
                                <option value="4">Pdf Files</option>
                                <option value="5">Date</option>
                                <option value="6">Video</option>
                            </select>
                        </div>
                        <div class="mb-3 form-check">
                            <label class="form-check-label" for="specialCheckbox">Special Display</label>
                            <input type="checkbox" class="form-check-input" id="specialCheckbox" name="specialCheckbox" >
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



<script></script>

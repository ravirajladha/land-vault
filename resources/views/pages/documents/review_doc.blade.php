@php
    use Carbon\Carbon;
@endphp



<x-app-layout>
    @php
        if (!function_exists('addOrdinalSuffix')) {
            function addOrdinalSuffix($num)
            {
                $num = (int) $num;
                if (!in_array($num % 100, [11, 12, 13])) {
                    switch ($num % 10) {
                        case 1:
                            return $num . 'st';
                        case 2:
                            return $num . 'nd';
                        case 3:
                            return $num . 'rd';
                    }
                }
                return $num . 'th';
            }
        }
    @endphp

    <x-header />
    <x-sidebar />

    <div class="content-body default-height ">
        <!-- row -->
        <div class="container-fluid">
            <div class="row page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>

                    <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Document Details</a></li>
                </ol>
            </div>

            <div class="container-fluid">
                {{-- @if ($user && $user->hasPermission('Update Basic Document Detail') && $master_data->status_id != 1)
                @endif --}}
                <div class="row">

                    <div class="split-pane">
                        <div class="pane left-pane">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Data </h4>
                                    @if ($user && $user->hasPermission('Update Basic Document Detail') && $master_data->status_id != 1)
                                        <a class="btn btn-primary float-end"
                                            href="{{ url('/') }}/edit_document_basic_detail/{{ $document->doc_id }}"
                                            rel="noopener noreferrer"><i class="fa fa-pencil"></i> Edit</a>
                                    @endif

                                </div>
                                <div class="card-body">


                                    <div class="table-responsive">
                                        <table class="table table-striped table-responsive-sm">
                                            <tbody>
                                                @if ($master_data)
                                                    @php
                                                        $latitude = null;
                                                        $longitude = null;
                                                    @endphp
                                                    {{-- @dd($master_data->getAttributes()) --}}
                                                    @foreach ($master_data->getAttributes() as $attribute => $value)
                                                        @if (
                                                            !(
                                                                $attribute == 'created_by' ||
                                                                $attribute == 'created_at' ||
                                                                $attribute == 'updated_at' ||
                                                                $attribute == 'status_id' ||
                                                                $attribute == 'set_id' ||
                                                                $attribute == 'batch_id' ||
                                                                $attribute == 'document_type' ||
                                                                $attribute == 'rejection_timestamp' ||
                                                                $attribute == 'bulk_uploaded' ||
                                                                $attribute == 'physically' ||
                                                                $attribute == 'temp_id' ||
                                                                $attribute == 'id'
                                                            ) &&
                                                                $value !== null &&
                                                                $value !== '')
                                                            @php
                                                                if ($attribute === 'document_type_name') {
                                                                    $value = ucWords(str_replace('_', ' ', $value));
                                                                }
                                                                if ($attribute === 'unit') {
                                                                    if ($value == 1) {
                                                                        $value = 'Acres and Cents';
                                                                    } elseif ($value == 2) {
                                                                        $vlaue = 'Square Feet';
                                                                    }
                                                                }
                                                                // Check for latitude and longitude

                                                                if ($attribute === 'longitude') {
                                                                    $longitude = $value;
                                                                }
                                                                if ($attribute === 'latitude') {
                                                                    $latitude = $value;
                                                                }
                                                                if ($attribute === 'issued_date') {
                                                                    try {
                                                                        $date = \Carbon\Carbon::createFromFormat(
                                                                            'Y-m-d',
                                                                            $value,
                                                                        );
                                                                        $issued_date = $date->format('d-M-Y');
                                                                        $value = $issued_date;
                                                                    } catch (\Exception $e) {
                                                                        // Handle the exception if the date format is incorrect
                                                                        $value = $value; // Keep original value if parsing fails
                                                                    }
                                                                }

                                                                // Handle category_id and subcategory_id
                                                                if ($attribute === 'category_id') {
                                                                    $categoryIds = explode(',', $value);
                                                                    $categoryNamesArray = [];
                                                                    foreach ($categoryIds as $id) {
                                                                        $categoryNamesArray[] =
                                                                            $categoryNames[$id] ?? $id;
                                                                    }
                                                                    $value = implode(', ', $categoryNamesArray);
                                                                }

                                                                if ($attribute === 'subcategory_id') {
                                                                    $subcategoryIds = explode(',', $value);
                                                                    $subcategoryNamesArray = [];
                                                                    foreach ($subcategoryIds as $id) {
                                                                        $subcategoryNamesArray[] =
                                                                            $subcategoryNames[$id] ?? $id;
                                                                    }
                                                                    $value = implode(', ', $subcategoryNamesArray);
                                                                }

                                                                $truncatedValue =
                                                                    strlen($value) > 35
                                                                        ? substr($value, 0, 35)
                                                                        : $value;
                                                            @endphp
                                                            <tr style="white-space: nowrap; overflow: hidden;">
                                                                <th style="padding: 5px;">
                                                                    {{ ucwords(str_replace('_', ' ', $attribute)) }}
                                                                </th>

                                                                <td style="padding: 5px;">
                                                                    @if (strlen($value) > 35)
                                                                        {{ $truncatedValue }}
                                                                        <span data-bs-toggle="modal"
                                                                            data-bs-target="#{{ $attribute }}Modal"
                                                                            style="cursor: pointer; text-decoration: underline;">
                                                                            ...
                                                                        </span>
                                                                    @else
                                                                        {{ $value }}
                                                                    @endif


                                                                </td>
                                                            </tr>

                                                            {{-- Remove comment to debug latitude and longitude --}}
                                                            <div class="modal fade" id="{{ $attribute }}Modal"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="{{ $attribute }}ModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered"
                                                                    role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="{{ $attribute }}ModalLabel">
                                                                                {{ ucwords(str_replace('_', ' ', $attribute)) }}
                                                                            </h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            {{ $value }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            {{-- {{ dd($latitude) }} --}}
                                                            @if ($latitude !== null && $longitude !== null)
                                                                <tr>
                                                                    <th style="padding: 5px;">Location</th>
                                                                    <td style="padding: 5px;">
                                                                        <a href="https://www.google.com/maps/search/{{ $latitude }},{{ $longitude }}"
                                                                            target="_blank" class="btn btn-primary"><i
                                                                                class="fa fa-location"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endif
                                                    @endforeach


                                                @endif

                                                <tr style="height: 20px;"></tr>
                                                @php
                                                    $normalColumns = [];
                                                    $specialColumns = [];
                                                @endphp

                                                @foreach ($columnMetadata as $meta)
                                                    @if (!in_array($meta->column_name, ['id', 'created_at', 'updated_at', 'status']))
                                                        @if (!in_array($meta->data_type, [3, 4, 6]))
                                                            @php
                                                                $columnName = ucwords(
                                                                    str_replace('_', ' ', $meta->column_name),
                                                                );
                                                                $value = $document->{$meta->column_name} ?? null;
                                                                $truncatedValue =
                                                                    strlen($value) > 35
                                                                        ? substr($value, 0, 35)
                                                                        : $value;
                                                                $modalTarget = $meta->column_name . 'Modal';
                                                                $isSpecial = $meta->special == 1; // Ensure you're comparing values, not types
                                                            @endphp

                                                            @if ($isSpecial)
                                                                @php
                                                                    $specialColumns[] = [
                                                                        'name' => $columnName,
                                                                        'value' => $value,
                                                                        'modalTarget' => $modalTarget,
                                                                    ];
                                                                @endphp
                                                            @else
                                                                @php
                                                                    $normalColumns[] = [
                                                                        'name' => $columnName,
                                                                        'value' => $value,
                                                                        'truncatedValue' => $truncatedValue,
                                                                        'modalTarget' => $modalTarget,
                                                                    ];
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endforeach

                                                @foreach ($normalColumns as $column)
                                                    @if ($column['value'] !== null && $column['value'] !== '')
                                                        <tr style="padding:0 0 0 0;">
                                                            <th style="padding: 5px;">{{ $column['name'] }}</th>
                                                            <td>
                                                                @if (strlen($column['value']) > 35)
                                                                    {{ $column['truncatedValue'] }}
                                                                    <span data-bs-toggle="modal"
                                                                        data-bs-target="#{{ $column['modalTarget'] }}"
                                                                        style="cursor: pointer; text-decoration: underline; padding: 0;">
                                                                        ...
                                                                    </span>
                                                                @else
                                                                    {{ $column['value'] }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <div class="modal fade" id="{{ $column['modalTarget'] }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="{{ $column['modalTarget'] }}Label"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered"
                                                                role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="{{ $column['modalTarget'] }}Label">
                                                                            {{ $column['name'] }}</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        {{ $column['value'] }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- </div> --}}
                                    {{-- </div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="divider"></div>
                        <div class="pane right-pane">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Files</h4>
                                </div>
                                <div class="card-body">


                                    <h3>File</h3>
                                    <div class="row">
                                        @php $counter = 0; @endphp
                                        @foreach ($columnMetadata as $column)
                                            @if (
                                                !(
                                                    $column->column_name == 'id' ||
                                                    $column->column_name == 'created_at' ||
                                                    $column->column_name == 'updated_at' ||
                                                    $column->column_name == 'status'
                                                ))
                                                @php
                                                    $columnName = ucWords(str_replace('_', ' ', $column->column_name));
                                                    $value = $document->{$column->column_name} ?? null;
                                                @endphp
                                                @if ($value !== null)
                                                    @if ($column->data_type == 3 || $column->data_type == 4 || $column->data_type == 6)
                                                        <h4 class="mt-2">{{ $columnName }}</h4>
                                                        @php $counter++; @endphp
                                                    @endif
                                                    @if ($column->data_type == 3)
                                                        <img src="{{ $document->{$column->column_name} ? url($document->{$column->column_name}) : $defaultImagePath }}"
                                                            alt="{{ $columnName }}" oncontextmenu="return false;">
                                                        <a href="{{ $document->{$column->column_name} ? url($document->{$column->column_name}) : $defaultImagePath }}"
                                                            target="_blank" rel="noopener noreferrer">Open Image in new
                                                            tab</a>

                                                        @php $counter++; @endphp
                                                    @elseif($column->data_type == 4)
                                                        <div class="pointer-events: auto;">
                                                            <div class="content-wrapper"
                                                                onclick="toggleFullscreen(this)">
                                                                <iframe
                                                                    src="{{ $document->{$column->column_name} ? url($document->{$column->column_name}) : $defaultPdfPath }}"
                                                                    width="100%" height="800"
                                                                    oncontextmenu="return false;"></iframe>
                                                            </div>
                                                            <a href="{{ $document->{$column->column_name} ? url($document->{$column->column_name}) : $defaultPdfPath }}"
                                                                target="_blank" rel="noopener noreferrer">Open PDF in
                                                                new tab</a>
                                                        </div>
                                                        {{-- #toolbar=0 --}}
                                                        @php $counter++; @endphp
                                                    @elseif($column->data_type == 6)
                                                        <video width="100%" height="500" controls
                                                            controlsList="nodownload">
                                                            <source
                                                                src="{{ $document->{$column->column_name} ? url($document->{$column->column_name}) : $defaultVideoPath }}"
                                                                type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                        @php $counter++; @endphp
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach


                                        @if ($document->pdf_file_path)
                                            @php
                                                // Get the file extension
                                                $extension = strtolower(
                                                    pathinfo($document->pdf_file_path, PATHINFO_EXTENSION),
                                                );
                                            @endphp

                                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg']))
                                                <h4 class="mt-2">Image File</h4>
                                                <div>

                                                    <img src="{{ url($document->pdf_file_path) }}" width="100%"
                                                        alt="Document Image">
                                                    <a href="{{ url($document->pdf_file_path) }}" target="_blank"
                                                        rel="noopener noreferrer">Open Image in new tab</a>
                                                </div>
                                            @elseif($extension === 'pdf')
                                                <h4 class="mt-2">PDF File</h4>
                                                {{-- <div class="pointer-events: auto;"> --}}
                                                <a href="{{ url($document->pdf_file_path) }}" target="_blank"
                                                    rel="noopener noreferrer">
                                                    <iframe src="{{ url($document->pdf_file_path) }}" width="100%"
                                                        height="800" frameborder="0"
                                                        oncontextmenu="return false;"></iframe>
                                                </a>
                                                <a href="{{ url($document->pdf_file_path) }}" target="_blank"
                                                    rel="noopener noreferrer">Open PDF in new tab</a>
                                                {{-- </div> --}}
                                            @else
                                                <div class="col-lg-12">
                                                    <p>No files to display.</p>
                                                </div>
                                            @endif
                                        @elseif ($counter == 0)
                                            <div class="col-lg-12">
                                                <p>No files to display.</p>
                                            </div>
                                        @endif

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>




                </div>
                <script>
                    $(function() {
                        $('[data-toggle="tooltip"]').tooltip()
                    })
                </script>

                @php
                    $hasNonEmptyValue = false;
                    foreach ($specialColumns as $specialColumn) {
                        if (!empty($specialColumn['value'])) {
                            $hasNonEmptyValue = true;
                            break; // Exit the loop early if any non-empty value is found
                        }
                    }
                @endphp

                @if ($hasNonEmptyValue)
                    <div class="card">
                        <div class="card-header">Insights</div>
                        <div class="card-body">
                            <div class="row">

                                @foreach ($specialColumns as $specialColumn)
                                    @if ($specialColumn['value'] !== null && $specialColumn['value'] !== '')
                                        <div class="col-xl-4 col-lg-4 col-xxl-4 col-sm-4">
                                            <div class="card text-white bg-dark">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex justify-content-between"><span
                                                            class="mb-0 mt-0 text-white"
                                                            style="font-size: 20px;padding-right: 30px;">{{ $specialColumn['name'] }}</span><strong
                                                            class="text-white">{{ $specialColumn['value'] }}</strong>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                            </div>
                        </div>
                    </div>
                @endif

                @if ($user && $user->hasPermission('Update Document Status'))

                    <div class="row mb-2" id="docVerification">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Doc Verification </h5>
                                    <div class="bootstrap-popover d-inline-block float-end">
                                        <button type="button" class="btn btn-primary btn-sm px-4 "
                                            data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top"
                                            data-bs-content="Four stages: Pending, Hold,
                                            Approve and Reviewer Feedback. To keep the document on hold and Reviewer Feedback, message is mandatory. "
                                            title="Verification Guidelines"><i
                                                class="fas fa-info-circle"></i></button>
                                    </div>
                                </div>

                                <div class="card-body">
                                    {{-- Status Form --}}
                                    {{-- @if ($document->status == 0 || $document->status == 2 || $document->status == 3) --}}

                                    @if(auth()->user()->id == 1 || count($document_logs) <= 5)
                                    {{-- Show the form to admin always and other users if document_logs count is 5 or less --}}
                                    <form action="{{ url('/') }}/update_document" method="post" class="mb-3">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $document->id }}">
                                        <input type="hidden" name="type" value="{{ $tableName }}">
                                        <div class="form-group">
                                            <select id="single-select" name="status" onchange="handleStatusChange(this)" class="form-select">
                                                <option value="0" {{ $document->status == 0 ? 'selected' : '' }}>Pending</option>
                                                <option value="1" {{ $document->status == 1 ? 'selected' : '' }}>Approve</option>
                                                <option value="2" {{ $document->status == 2 ? 'selected' : '' }}>Hold</option>
                                                <option value="3" {{ $document->status == 3 ? 'selected' : '' }}>Reviewer Feedback</option>
                                            </select>
                                            <input type="hidden" id="holdReason" name="holdReason">
                                        </div>
                                    </form>

                                    @else
                                   <span class="text-danger">Contact admin to change the status of the document.</span>
                                @endif
                                

                                    {{-- Rejection Message --}}
                                    @if ($document->status == 2 && $master_data->rejection_message)
                                        <div class="alert alert-dark">
                                            <strong>Hold Reason:</strong> {{ $master_data->rejection_message }}
                                            <div><small>
                                                    {{ \Carbon\Carbon::parse($master_data->rejection_timestamp)->format('m/d/Y') }}</small>
                                            </div>
                                        </div>
                                    @elseif($document->status == 0)
                                        <div class="alert alert-primary">
                                            <strong>Current Status : Pending</strong>

                                        </div>
                                    @elseif ($document->status == 3 && $master_data->rejection_message)
                                        <div class="alert alert-dark">
                                            <strong>Reviewer Feedback:</strong> {{ $master_data->rejection_message }}
                                            <div><small>
                                                    {{ \Carbon\Carbon::parse($master_data->rejection_timestamp)->format('m/d/Y') }}</small>
                                            </div>
                                        </div>
                                    @endif
                                    {{-- Document Status Logs --}}
                                    {{-- @if ($document->status != 1) --}}
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Serial Number</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                    <th>Reason</th>
                                                    <th>Created By</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($document_logs as $index => $log)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $log->status == 0 ? 'danger' : ($log->status == 1 ? 'success' : ($log->status == 2 ? 'dark' : 'warning')) }}">
                                                                @if ($log->status == 0)
                                                                    Pending
                                                                @elseif ($log->status == 1)
                                                                    Approved
                                                                @elseif ($log->status == 2)
                                                                    Hold
                                                                @elseif ($log->status == 3)
                                                                    Reviewer Feedback
                                                                @endif
                                                            </span>

                                                        </td>
                                                        <td>{{ date('H:i:s d/M/Y ', strtotime($log->created_at)) }}
                                                        </td>
                                                        <td>{{ $log->message ? $log->message : 'N/A' }}</td>
                                                        <td>{{ $log->creator_name }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editModal{{ $log->id }}">
                                                                <i class="fa fa-pencil"></i> Edit
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- @endif --}}
                                    @foreach ($document_logs as $index => $log)
                                        <!-- Modal -->
                                        <div class="modal fade" id="editModal{{ $log->id }}" tabindex="-1"
                                            aria-labelledby="editModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel">Edit Message</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('documents.statusMessage', $log->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="id"
                                                            value="{{ $document->id }}">
                                                        <input type="hidden" name="type"
                                                            value="{{ $tableName }}">
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="messageText"
                                                                    class="form-label">Message</label>
                                                                <textarea class="form-control" id="messageText" name="message" rows="3" required>{{ $log->message }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save
                                                                changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>

                            </div>
                        </div>
                    </div>
                @endif


                <div class="container-fluid">
                    <div class="row">
                        {{-- {{ dd($matchingData) }} --}}
                        @foreach ($matchingData as $data)
                            @if (!empty($data))
                                <div class="col-xl-6 col-lg-12 col-sm-12">
                                    <div class="card overflow-hidden">
                                        <div class="text-center p-3 overlay-box "
                                            style="background-image: url(images/big/img1.jpg);">
                                            {{-- <div class="profile-photo">
                                                <img src="images/profile/profile.png" width="100"
                                                    class="img-fluid rounded-circle" alt="">
                                            </div> --}}
                                            <h6 class="mt-3 mb-1 text-white">Common Document</h6>

                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between"><span
                                                    class="mb-0">Document Name</span> <strong
                                                    class="text-muted">{{ $data->document_name }} </strong></li>
                                            <li class="list-group-item d-flex justify-content-between"><span
                                                    class="mb-0">Document Type</span> <strong
                                                    class="text-muted">{{ ucwords(str_replace('_', ' ', $data->doc_type)) }}
                                                </strong></li>
                                        </ul>
                                        <div class="card-footer border-0 mt-0">
                                            <a href="{{ url('/') }}/review_doc/{{ $data->doc_type }}/{{ $data->id }}"
                                                target="_blank" type="button" class="btn btn-primary btn-block">View
                                                <i class="fa fa-eye"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- compliance data --}}
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-header" style="padding:0 0 0 0">
                                    <h5>Compliances</h5>


                                    @if ($user && $user->hasPermission('Add Compliances') && $master_data->status_id == 1)
                                        <button type="button" class="btn btn-success mb-2 float-end btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#exampleModalCenter"> <i
                                                class="fas fa-square-plus"></i>&nbsp;Add Compliance</button>
                                    @endif

                                </div>

                                <div class="table-responsive" style="padding:7px;">
                                    <table id="example3" class="display" style="min-width: 845px;">

                                        <thead>
                                            <tr>
                                                <th scope="col">Sl. No.</th>
                                                <th scope="col">Name</th>
                                                {{-- <th scope="col">Document Name </th>
                                <th scope="col">Document Type </th> --}}
                                                <th scope="col">Due Date</th>
                                                <th scope="col">Is Recurring </th>
                                                <th scope="col">Recurrence Months </th>

                                                {{-- <th scope="col">Status </th> --}}
                                                <th scope="col">Action </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($compliances as $index => $item)
                                                <tr data-item-id="{{ $item->id }}">
                                                    <th scope="row">{{ $index + 1 }}</th>

                                                    <td>{{ $item->name }}</td>
                                                    {{-- <td>{{ $item->documentType->name }}</td>
                                    <td>{{ $item->document->name }}</td> --}}
                                                    <td>{{ date('d-m-Y', strtotime($item->due_date)) }}</td>


                                                    <td> {!! $item->is_recurring
                                                        ? '<span class="badge bg-success">Yes</span>'
                                                        : '<span class="badge bg-warning text-dark">Not</span>' !!}</td>
                                                    <td>
                                                        @if ($item->is_recurring)
                                                            @php
                                                                // Split the recurrence_interval by the underscore character
                                                                $recurrenceParts = explode(
                                                                    '_',
                                                                    $item->recurrence_interval,
                                                                );
                                                                // Get the number and the period (e.g., 1 and months)
                                                                $number = $recurrenceParts[0];
                                                                $period = ucfirst(rtrim($recurrenceParts[1], 's')); // Capitalize the period and remove the trailing 's'
                                                            @endphp
                                                            <span class="badge bg-success">Yes ({{ $number }}
                                                                {{ $period }}{{ $number > 1 ? 's' : '' }})</span>
                                                        @else
                                                            <span class="badge bg-danger">No</span>
                                                        @endif

                                                    </td>
                                                    <td class="action-cell">
                                                        <!-- Action buttons based on status -->
                                                        @if ($item->status == 0)
                                                            <!-- Show buttons only if status is Pending -->
                                                            <button class="btn btn-sm btn-success toggle-status"
                                                                data-id="{{ $item->id }}" data-action="settle"><i
                                                                    class="fas fa-thumbs-up"></i></button>
                                                            <button class="btn btn-sm btn-danger toggle-status"
                                                                data-id="{{ $item->id }}" data-action="cancel"><i
                                                                    class="fas fa-cancel"></i></button>
                                                        @elseif($item->status == 1)
                                                            <span class="badge bg-success">Settled</span>
                                                        @elseif($item->status == 2)
                                                            <span class="badge bg-danger">Cancelled</span>
                                                        @else
                                                            <span class="badge bg-success">Unknown data</span>
                                                        @endif
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
                {{-- compliance data end --}}
                {{--        compliances modal start --}}


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
                                    <form id="myAjaxForm" action="{{ url('/') }}/create-compliances"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <input type="text" hidden value="{{ $document_id }}"
                                                name="document_id" required>
                                            <input type="text" hidden value="{{ $doc_type->id }}"
                                                name="document_type" required>
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
                                                    <input class="form-control" type="date" name="due_date"
                                                        required required>
                                                </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <div class="col-sm-6">Is Recurring?</div>
                                                <div class="col-sm-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="is_recurring_checkbox"
                                                            name="is_recurring" type="checkbox" value="1">
                                                        <label class="form-check-label" for="is_recurring_checkbox">
                                                            Yes
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- <div id="recurrence_months_field" style="display: none;" class="mb-3">
                                                <label class="form-label">Recurrence Months</label>
                                                <input class="form-control" type="number"  name="recurrence_months" id="recurrence_months" placeholder="in months (minimum 1)">
                                            </div> --}}

                                            <div class="mb-3 row " id="recurrence_months_field"
                                                style="display: none;">
                                                <div class="col-sm-6 mb-2">Time Period ?</div>
                                                {{-- <div class="col-sm-6"> --}}
                                                <select class="form-select " id="recurrence_interval"
                                                    name="recurrence_interval">
                                                    <option value="">Select Time Period</option>
                                                    <option value="1_months">1 Month </option>
                                                    <option value="3_months">3 Months</option>
                                                    <option value="6_months">6 Months</option>
                                                    <option value="12_months">12 Months</option>
                                                </select>
                                                {{-- </div> --}}
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
                                <button type="submit" class="btn btn-success" id="submitBtn">Submit Form</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{--        compliances modal end --}}

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-header">
                                    <h4>Assigned Documents</h4>
                                    @if ($user && $user->hasPermission('Assign Document') && $master_data->status_id == 1)
                                        <button type="button" class="btn btn-success mb-2 float-end btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#exampleModalCenter1"> <i
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
                                                <th scope="col">Document Name </th>
                                                <th scope="col">Document Type </th>
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
                                                    <td>{{ $item->document->name }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item->expires_at)->format('M d, Y, g:i A') }}
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
                {{-- start asssigned advocate document table --}}
                @if ($user && $user->hasPermission('View Assigned Docs to Advocate'))

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header">
                                        <h4>Assigned Advocates</h4>
                                        @if ($user && $user->hasPermission('Add Assigned Docs to Advocate'))
                                            <button class="btn btn-success btn-sm assign-doc-btn float-end flex"
                                                title="Assign Document to the Receiver" data-bs-toggle="modal"
                                                data-bs-target="#assignDocumentModal"
                                                data-document-id="{{ $document_id }}"><i
                                                    class="fas fa-plus-square"></i>&nbsp;Assign Advocate
                                            </button>
                                        @endif
                                    </div>
                                    <div class="table-responsive">
                                        <table id="example3" class="display" style="min-width: 845px">


                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl. No.</th>


                                                    <th scope="col">Advocate Name </th>
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

                                                @foreach ($assigned_advocate_docs as $index => $item)
                                                    <tr>
                                                        <th scope="row">{{ $index + 1 }}</th>

                                                        <td>{{ $item->advocate->name }}

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
                                                            @if ($user && $user->hasPermission('Update Assigned Docs to Advocate'))
                                                                @if ($item->status == 1)
                                                                    <button class="btn btn-primary btn-sm edit-doc-btn"
                                                                        title="Edit Document Assignment"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editDocumentModal"
                                                                        data-id="{{ $item->id }}">
                                                                        <i class="fas fa-edit"></i> Edit
                                                                    </button>

                                                                    <form
                                                                        action="{{ route('documentAdvocateAssignment.destroy', $item->id) }}"
                                                                        method="POST" style="display:inline-block;">
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
                                                                        data-bs-content="The assigned document is already inactive, due to \ edit option is no more avaiable."><i
                                                                            class="fas fa-info-circle"></i></button>
                                                                @endif
                                                            @endif
                                                            @if ($user && !$user->hasPermission('Delete Assigned Docs to Advocate'))
                                                                --
                                                            @endif
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
                @endif
                {{-- end asssigned advocate document table --}}
                {{-- start document transactions table --}}
                @if ($user && $user->hasPermission('View Document Logs'))

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card" id="documentTransactionsCard">
                                <div class="card-body">
                                    <div class="card-header">
                                        <h4>Document Logs</h4>
                                        @if ($user && $user->hasPermission('Add Document Logs'))
                                            <button class="btn btn-success btn-sm assign-doc-btn float-end flex"
                                                title="Assign Document to the Receiver" data-bs-toggle="modal"
                                                data-bs-target="#documentTransactionModal"
                                                data-document-id="{{ $document_id }}"><i
                                                    class="fas fa-plus-square"></i>
                                                Add Document Log&nbsp;
                                            </button>
                                        @endif
                                    </div>
                                    <div class="table-responsive">
                                        <table id="example3" class="display" style="min-width: 845px">
                                            <thead>
                                                <tr>
                                                    <th>Sl. No.</th>
                                                    <th>Created By</th>
                                                    <th>Log Type</th>
                                                    <th>Notes</th>
                                                    <th>Created At</th>
                                                    <th>Updated At</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ($document_transactions as $index => $item)
                                                    <tr>
                                                        <th scope="row">{{ $index + 1 }}</th>
                                                        <td>{{ $item->creator->name }}</td>
                                                        <td>{{ $item->transaction_type }}</td>
                                                        <td>{{ $item->notes }}</td>
                                                        <td>{{ date('H:i:s d/M/Y ', strtotime($item->created_at)) }}
                                                        <td>{{ date('H:i:s d/M/Y ', strtotime($item->updated_at)) }}

                                                        <td>
                                                            {{-- @if ($user && $user->hasPermission('Update Document Logs'))
                                                                <button
                                                                    class="btn btn-primary btn-sm edit-transaction-btn"
                                                                    data-id="{{ $item->id }}"> <i
                                                                        class="fas fa-edit"></i> Edit</button>
                                                            @endif --}}
                                                            @if ($user && $user->hasPermission('Update Document Logs'))
                                                                @if ($item->transaction_type == 'taken')
                                                                    <form
                                                                        action="{{ route('documentTransaction.update', $item->id) }}"
                                                                        method="POST" style="display:inline-block;">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <input type="hidden" name="transaction_type"
                                                                            value="returned">
                                                                        <button type="submit"
                                                                            class="btn btn-warning btn-sm"
                                                                            onclick="return confirm('Are you sure you want to settle this transaction?');">
                                                                            <i class="fas fa-check"></i> Settle
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <button type="submit"
                                                                        class="btn btn-success btn-sm">
                                                                        <i class="fas fa-check"></i> Settled
                                                                    </button>
                                                                @endif
                                                            @endif


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
                @endif
                {{-- end document transactions table --}}

                {{-- start assign document modal --}}

                <div class="modal fade" id="exampleModalCenter1">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Assign Document</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="form theme-form projectcreate">
                                    <form id="myAjaxForm" action="{{ url('/') }}/assign-documents-to-receiver"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">

                                            <input type="text" hidden value="{{ $document_id }}"
                                                name="document_id" required>
                                            <input type="text" hidden value="{{ $doc_type->id }}"
                                                name="document_type" required>
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
                                <button type="button" class="btn btn-danger light"
                                    data-bs-dismiss="modal">Close</button>
                                <div id="loader" style="display: none;">
                                    Loading...
                                </div>
                                <button type="submit" class="btn btn-success" id="submitBtn">Submit Form</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- end assign document modal --}}
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- start add transaction modal --}}

<!-- Document Transaction Modal -->
<div class="modal fade" id="documentTransactionModal" tabindex="-1" role="dialog"
    aria-labelledby="documentTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentTransactionModalLabel">Document Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="documentTransactionForm" action="{{ url('/document-transactions') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $document_id }}" name="doc_id">

                    <div class="mb-3">
                        <label for="transaction_type" class="form-label">Transaction Type <span
                                class="text-danger">*</span></label>
                        <select class="form-control" id="transaction_type" name="transaction_type">
                            <option value="taken" selected>Taken</option>
                            <option value="returned" disabled>Returned (Disabled)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary float-right">Save</button>
                </form>

                <div class="alert alert-info mt-3" role="alert">
                    ~ By default, a document needs to be taken. You can settle it later by updating the transaction type
                    to "Returned".
                </div>
                <div class="alert alert-info mt-3" role="alert">
                    ~ Document cant store transaction until an existing document is not settled.
                </div>
            </div>

        </div>
    </div>
</div>
{{-- end add transaction modal --}}
{{-- start modal edit for transaction --}}
<!-- Edit Document Log Modal -->
<div class="modal fade" id="editDocumentTransactionModal" tabindex="-1" role="dialog"
    aria-labelledby="editDocumentTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDocumentTransactionModalLabel">Edit Document
                    Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editDocumentTransactionForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_doc_id" name="doc_id">
                    <div class="mb-3">
                        <label for="edit_transaction_type" class="form-label">Log Type</label>
                        <select class="form-control" id="edit_transaction_type" name="transaction_type">
                            <option value="taken">Taken</option>
                            <option value="returned">Returned</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- end modal edit for transaction --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const transactionButtons = document.querySelectorAll('.transaction-btn');
        transactionButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const docId = button.getAttribute('data-document-id');
                alert(docId, "docu,ent_id")
                console.log('Document ID:', docId);
                document.getElementById('document_id').value = docId;
                console.log('Hidden input value:', document.getElementById('document_id')
                    .value); // Debug log
                document.getElementById('documentTransactionForm').reset();
                document.getElementById('documentTransactionForm').action =
                    '{{ url('/document-transactions') }}';
                document.getElementById('documentTransactionForm').method = 'POST';
                document.getElementById('documentTransactionModalLabel').textContent =
                    'Create Document Transaction';
                $('#documentTransactionModal').modal('show');
            });
        });

        const editTransactionButtons = document.querySelectorAll('.edit-transaction-btn');
        editTransactionButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const transactionId = button.getAttribute('data-id');
                console.log('Fetching transaction with ID:', transactionId); // Debug log
                const url = "{{ route('documentTransaction.show', ['id' => ':id']) }}"
                    .replace(
                        ':id', transactionId);
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        // if(data.success) {
                        console.log('Transaction data:', data); // Debug log
                        document.getElementById('edit_doc_id').value = data.doc_id;
                        document.getElementById('edit_transaction_type').value = data
                            .transaction_type;
                        document.getElementById('edit_notes').value = data.notes;
                        document.getElementById('editDocumentTransactionForm').action = url;
                        document.getElementById('editDocumentTransactionModalLabel')
                            .textContent = 'Edit Document Log';
                        $('#editDocumentTransactionModal').modal('show');
                        // } else {
                        //     console.error('Failed to fetch transaction data');
                        // }
                    })
                    .catch(error => {
                        console.error('Error fetching transaction data:', error);
                    });
            });
        });


    });
</script>




{{-- end  add transaction modal --}}
{{-- assign document --}}
<div class="modal fade" id="assignDocumentModal">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Advocate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Update Form -->
                <form action="{{ url('/') }}/assign-documents-to-advocate" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="documentId" name="document_id">

                    <input type="hidden" name="location" value="review">

                    <div class="row">

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="advocate_id" class="form-label">Select Advocate <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" id="advocate_id" name="advocate_id" required>
                                        <!-- Assuming you have an array of advocates -->
                                        @foreach ($advocates as $advocate)
                                            <option value="{{ $advocate->id }}">{{ $advocate->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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



                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="submission_deadline" class="form-label">Case Result</label>
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
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const assignDocButtons = document.querySelectorAll('.assign-doc-btn');
        assignDocButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const documentId = button.getAttribute('data-document-id');
                document.getElementById('documentId').value = documentId;
            });
        });
    });
</script>
{{-- end assign document modal --}}
{{-- start edit assign document modal --}}
<div class="modal fade" id="editDocumentModal">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Assigned Advocate Details</h5>
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
                        <div class="col-12">
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


                        <div class="col-4">
                            <div class="mb-3">
                                <label for="edit_submission_deadline" class="form-label">Case Result</label>
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
                <button type="submit" class="btn btn-primary">Update</button>
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

{{-- end edit assign document modal --}}
<script>
    function handleStatusChange(select) {
        if (select.value == "2") { // Assuming '2' is the value for 'Hold'
            const reason = window.prompt("Please enter the reason for holding: (* Mandatory)");
            if (reason) {
                document.getElementById('holdReason').value = reason;
                select.form.submit();
            } else {
                select.value =
                    "{{ $document->status }}"; // Revert back to the original value if no reason is provided
            }
        } else if (select.value == "3") {
            const reason = window.prompt("Please enter the feedback: (* Mandatory)");
            if (reason) {
                document.getElementById('holdReason').value = reason;
                select.form.submit();
            } else {
                select.value =
                    "{{ $document->status }}"; // Revert back to the original value if no reason is provided
            }
        } else {
            select.form.submit();
        }
    }
</script>

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
{{-- compliance scripts
     --}}
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
<style>
    .split-pane {
        display: flex;
        width: 100%;
        height: auto;
        /* Adjust based on your needs */
    }

    .pane {
        flex-grow: 1;
        flex-basis: 50%;
        /* Initially each pane takes up half the container */
        overflow: auto;
        transition: flex-basis 0.1s ease;
        /* Smooth transition for resizing */
    }

    .divider {
        background-color: #666;
        cursor: ew-resize;
        width: 5px;
        /* Adjust for handle width */
    }

    .content-wrapper {
        cursor: pointer;
        /* Indicates the element is clickable */
        overflow: hidden;
        /* Keeps everything neat */
    }

    /*iframe,*/
    /*img {*/
    /*    width: 100%;*/
    /* Ensures content fills the wrapper */
    /*    height: auto;*/
    /* Maintains aspect ratio for images */
    /*}*/
</style>
{{-- split window javascript --}}
<script>
    function toggleFullscreen(element) {
        if (!document.fullscreenElement && element.requestFullscreen) {
            element.requestFullscreen().catch(err => {
                alert(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
            });
        } else if (document.exitFullscreen) {
            document.exitFullscreen();
        }
    }


    document.addEventListener('DOMContentLoaded', function() {
        const divider = document.querySelector('.divider');
        let isDragging = false;

        divider.addEventListener('mousedown', function(e) {
            isDragging = true;
            e.preventDefault();
            document.addEventListener('mousemove', handleDrag, false);
            document.addEventListener('mouseup', stopDrag, false);
        });

        function handleDrag(e) {
            if (!isDragging) return;
            const splitPane = divider.closest('.split-pane');
            const leftPane = splitPane.querySelector('.left-pane');
            const deltaX = e.clientX - divider.getBoundingClientRect().left;

            const leftFlexBasis = ((e.clientX - splitPane.offsetLeft) / splitPane.offsetWidth) * 100;
            leftPane.style.flexBasis = `${leftFlexBasis}%`;
        }

        function stopDrag(e) {
            isDragging = false;
            document.removeEventListener('mousemove', handleDrag, false);
            document.removeEventListener('mouseup', stopDrag, false);
        }
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

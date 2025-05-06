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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Assigned Document</a></li>
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
                                            <form action="{{ route('documentsAssignedToReceivers.index') }}"
                                                method="GET" class="row">

                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Receivers Type</label>
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
                                                    <label class="form-label">Receivers </label>
                                                    <select class="form-select form-control" name="receiver_id"
                                                        id="single-select-abc2">
                                                        <option value="">Select Receiver</option>
                                                        @foreach ($receivers as $type)
                                                            <option value="{{ $type->id }}"
                                                                {{ request()->input('receiver_id') == $type->id ? 'selected' : '' }}>
                                                                {{ $type->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Document ID</label>
                                                    <select class="form-select form-control" id="single-select-abc3"
                                                        name="doc_id">
                                                        <option value="">Select Document ID</option>
                                                        @foreach ($assigned_documents as $doc)
                                                            <option value="{{ $doc->doc_id }}"
                                                                {{ request()->input('doc_id') == $doc->doc_id ? 'selected' : '' }}>
                                                                {{ $doc->document_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">Start Date</label>
                                                    <input name="start_date" type="date" class="form-control"
                                                        value="{{ request()->input('start_date') }}">
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label">End Date</label>
                                                    <input name="end_date" type="date" class="form-control"
                                                        value="{{ request()->input('end_date') }}">
                                                </div>
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary"><i
                                                        class="fas fa-filter"></i>&nbsp;Filter</button>
                                                    <a href="{{ route('assignedDocumentsToReceivers.export', request()->all()) }}"
                                                        class="btn btn-success"><i
                                                        class="fas fa-download"></i>&nbsp;Export to Excel</a>
                                                    <a href="{{ url('/') }}/documents-assigned-to-receivers"
                                                        class="btn btn-dark"><i
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
                            <div class="card-body">

                                <div class="table-responsive">
                                    @if ($assignedDocuments->isNotEmpty())
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Assignment ID</th>
                                                    <th>Receiver Name</th>
                                                    <th>Receiver Type Name</th>
                                                    <th>Created At</th>
                                                    <th>Document Name</th>
                                                    <th>Category ID</th>
                                                    <th>Subcategory ID</th>
                                                    <th>Location</th>
                                                    <th>Locker ID</th>
                                                    <th>Category</th>
                                                    <th>Document Type Name</th>
                                                    <th>Current State</th>
                                                    <th>State</th>
                                                    <th>Alternate State</th>
                                                    <th>Current District</th>
                                                    <th>District</th>
                                                    <th>Alternate District</th>
                                                    <th>Current Taluk</th>
                                                    <th>Taluk</th>
                                                    <th>Alternate Taluk</th>
                                                    <th>Current Village</th>
                                                    <th>Village</th>
                                                    <th>Alternate Village</th>
                                                    <th>Issued Date</th>
                                                    <th>Area</th>
                                                    <th>Dry Land</th>
                                                    <th>Wet Land</th>
                                                    <th>Unit</th>
                                                    <th>Old Locker Number</th>
                                                    <th>Latitude</th>
                                                    <th>Longitude</th>
                                                    <th>Court Case No</th>
                                                    <th>Survey No</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tbody>
                                                @foreach ($assignedDocuments as $document)
                                                    <tr>
                                                        <td>{{ ($assignedDocuments->currentPage() - 1) * $assignedDocuments->perPage() + $loop->iteration }}
                                                        </td>
                                                        <td>{{ $document->receiver_name }}</td>
                                                        <td>{{ $document->receiver_type_name }}</td>
                                                        <td>{{ $document->created_at_formatted }}</td>
                                                        <td>{{ $document->document_name }}</td>
                                                        <td>{{ $document->category_names }}</td>
                                                        <td>{{ $document->subcategory_names }}</td>
                                                        <td>{{ $document->location }}</td>
                                                        <td>{{ $document->locker_id }}</td>
                                                        <td>{{ $document->category }}</td>
                                                        <td>{{ $document->document_type_name }}</td>
                                                        <td>{{ $document->current_state }}</td>
                                                        <td>{{ $document->state }}</td>
                                                        <td>{{ $document->alternate_state }}</td>
                                                        <td>{{ $document->current_district }}</td>
                                                        <td>{{ $document->district }}</td>
                                                        <td>{{ $document->alternate_district }}</td>
                                                        <td>{{ $document->current_taluk }}</td>
                                                        <td>{{ $document->taluk }}</td>
                                                        <td>{{ $document->alternate_taluk }}</td>
                                                        <td>{{ $document->current_village }}</td>
                                                        <td>{{ $document->village }}</td>
                                                        <td>{{ $document->alternate_village }}</td>
                                                        <td>{{ $document->issued_date }}</td>
                                                        <td>{{ $document->area }}</td>
                                                        <td>{{ $document->dry_land }}</td>
                                                        <td>{{ $document->wet_land }}</td>
                                                        <td>{{ $document->unit }}</td>
                                                        <td>{{ $document->old_locker_number }}</td>
                                                        <td>{{ $document->latitude }}</td>
                                                        <td>{{ $document->longitude }}</td>
                                                        {{-- <td>{{ $document->court_case_no }}</td> --}}
                                                        <td>{{ $document->survey_no }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="row">
                                            <div class="col">
                                                {{-- {{ $documents->links('vendor.pagination.custom') }} --}}
                                                {{ $assignedDocuments->appends(request()->except('page'))->links('vendor.pagination.custom') }}

                                            </div>
                                        </div>
                                    @else
                                        <p>No assigned documents found.</p>
                                    @endif
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
</script>

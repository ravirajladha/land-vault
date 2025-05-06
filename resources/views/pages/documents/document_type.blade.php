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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Document Type</a></li>
                    </ol>
                </div>



                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="title">Document Type</h4>
                                    @if ($user && $user->hasPermission('Add Document Types'))
                                        <button type="button" class="btn btn-success btn-sm float-end"
                                            data-bs-toggle="modal" data-bs-target="#addDocumentTypeModal">
                                            <i class="fas fa-plus-square"></i>&nbsp; Add Document Type
                                        </button>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">

                                        <table id="example3" class="display" style="min-width: 845px">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl. No.</th>
                                                    <th scope="col">Document type</th>
                                                    <th scope="col">Number of Documents </th>
                                                    @if ($user && $user->hasPermission('View Documents by Document Type'))
                                       
                                                        <th scope="col">View Documents </th>
                                                    @endif

                                                    @if ($user && $user->hasPermission('View Document Fields'))
                                                        <th scope="col">Action </th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($doc_types as $index => $item)
                                                    <tr>
                                                        <th scope="row">{{ $index + 1 }}</th>
                                                        <td>{{ ucwords(str_replace('_', ' ', $item->name)) }}</td>
                                                        <td>{{ isset($doc_counts[$item->id]) ? $doc_counts[$item->id] : 0 }}
                                                        </td>
                                                        @if ($user && $user->hasPermission('View Documents by Document Type'))
                                                            <td>
                                                                <a href="/filter-document?type={{ $item->id }}"><button
                                                                        class="btn btn-primary btn-sm"><i
                                                                            class="fas fa-eye"></i>&nbsp;View</button></a>
                                                            </td>
                                                        @endif
                                                        @if ($user && $user->hasPermission('View Document Fields'))

                                                            <td>
                                                                <a href="/document_field/{{ $item->name }}"><button
                                                                        class="btn btn-success btn-sm"><i
                                                                            class="fas fa-plus-square"></i>&nbsp;Add
                                                                        Field</button></a>

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
    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="addDocumentTypeModal" tabindex="-1" aria-labelledby="addDocumentTypeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDocumentTypeModalLabel">Add Document Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('/') }}/add_document_type" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="documentType" class="form-label">Document Type<span
                                class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="type" id="documentType"
                                placeholder="Enter Document Type">
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

<x-app-layout>
    <x-header />
    <x-sidebar/>

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">

            <div class="page-body">
                <div class="container-fluid">


                    <div class="row page-titles">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <h3>
                                    Add Document</h3>
                            </div>
                            <div class="col-12 col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Add Document</a>
                                    </li>
                                </ol>
                            </div>
                        </div>

                    </div>
                    <form action="{{ url('/') }}/add_document" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
               
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3>Document Type: {{ ucwords(str_replace('_', ' ', $table_name)) }}</h3>
                                            <div class="form theme-form projectcreate">
                                                <div class="row">
                                                    <input type="hidden" value="{{ $table_name }}" name="type">
                                                    <input type="hidden" value="{{ $document_data->doc_id }}"
                                                        name="master_doc_id">
                                                    {{-- @if (count($columnMetadata) == 0)
                                                        <div class="col-lg-12">
                                                            <p>No additional fields to display. Please Submit the Form
                                                                to review the document.</p>
                                                        </div>
                                                    @endif --}}
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                    <label for="Default Pdf"
                                                        class="form-label">Upload Scanned Document (Max 2mb)</label>
                                                @if ($documentData->pdf_file_path)
                                                    <a href="{{ asset($documentData->pdf_file_path) }}"
                                                        target="_blank"><i
                                                            class="fa fa-eye"></i></a>
                                                @else
                                                    <i class="fa fa-eye-slash"></i>
                                                @endif
                                                <input type="file" class="form-control"
                                                    name="pdf_file_path"
                                                    id="pdf_file_path" accept=".pdf,image/png,image/jpeg"
                                                    >

                                                    @error('pdf_file_path')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                    </div>
                                                    </div>

                                                    @foreach ($columnMetadata as $meta)
                                                        @if (!in_array($meta->column_name, ['id', 'document_name', 'doc_id', 'created_at', 'updated_at', 'status', 'doc_type']))
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="{{ $meta->column_name }}"
                                                                        class="form-label">{{ ucfirst(str_replace('_', ' ', $meta->column_name)) }}</label>
                                                                    @switch($meta->data_type)
                                                                        @case(1)
                                                                            {{-- Text input --}}
                                                                            <i><label for="{{ $meta->column_name }}"
                                                                                    class="form-label"></label></i>

                                                                            <input type="text" class="form-control"
                                                                                name="{{ $meta->column_name }}"
                                                                                id="{{ $meta->column_name }}"
                                                                                value="{{ old($meta->column_name, $documentData->{$meta->column_name} ?? '') }}">
                                                                        @break

                                                                        @case(2)
                                                                            {{-- Numeric input --}}
                                                                            <i><label for="{{ $meta->column_name }}"
                                                                                    class="form-label">(Enter Number
                                                                                    Only)</label></i>
                                                                            <input type="number" class="form-control"
                                                                                name="{{ $meta->column_name }}"
                                                                                id="{{ $meta->column_name }}"
                                                                                value="{{ old($meta->column_name, $documentData->{$meta->column_name} ?? '') }}">
                                                                        @break

                                                                        @case(3)
                                                                            {{-- File input for images --}}
                                                                            <i><label for="{{ $meta->column_name }}"
                                                                                    class="form-label">(Add Image
                                                                                    Only)</label></i>
                                                                            @if ($documentData->{$meta->column_name})
                                                                                <a href="{{ asset($documentData->{$meta->column_name}) }}"
                                                                                    target="_blank"><i
                                                                                        class="fa fa-eye"></i></a>
                                                                            @else
                                                                                <i class="fa fa-eye-slash"></i>
                                                                            @endif
                                                                            <input type="file" class="form-control"
                                                                                name="{{ $meta->column_name }}[]"
                                                                                id="{{ $meta->column_name }}" accept="image/*"
                                                                                multiple>
                                                                        @break

                                                                        @case(4)
                                                                            <i><label for="{{ $meta->column_name }}"
                                                                                    class="form-label">(Add Pdf
                                                                                    Only)</label></i>
                                                                            @if ($documentData->{$meta->column_name})
                                                                                <a href="{{ asset($documentData->{$meta->column_name}) }}"
                                                                                    target="_blank"><i
                                                                                        class="fa fa-eye"></i></a>
                                                                            @else
                                                                                <i class="fa fa-eye-slash"></i>
                                                                            @endif
                                                                            <input type="file" class="form-control"
                                                                                name="{{ $meta->column_name }}[]"
                                                                                id="{{ $meta->column_name }}" accept=".pdf"
                                                                                multiple>
                                                                        @break

                                                                        @case(5)
                                                                            <i><label for="{{ $meta->column_name }}"
                                                                                    class="form-label">(Enter Date
                                                                                    Only)</label></i>
                                                                            <input type="date" class="form-control"
                                                                                name="{{ $meta->column_name }}"
                                                                                id="{{ $meta->column_name }}"
                                                                                value="{{ old($meta->column_name, $documentData->{$meta->column_name} ?? '') }}"
                                                                                required>
                                                                        @break

                                                                        @case(6)
                                                                            <i><label for="{{ $meta->column_name }}"
                                                                                    class="form-label">(Add Video
                                                                                    Only)</label></i>
                                                                            @if ($documentData->{$meta->column_name})
                                                                                <a href="{{ asset($documentData->{$meta->column_name}) }}"
                                                                                    target="_blank"><i
                                                                                        class="fa fa-eye"></i></a>
                                                                            @else
                                                                                <i class="fa fa-eye-slash"></i>
                                                                            @endif
                                                                            <input type="file" class="form-control"
                                                                                name="{{ $meta->column_name }}[]"
                                                                                id="{{ $meta->column_name }}" accept="video/*"
                                                                                multiple>
                                                                        @break
                                                                    @endswitch


                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach



                                                    <div class="col-md-12 my-auto">
                                                        <div class="text-end"><button class="btn btn-success"
                                                                type="submit">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')


</x-app-layout>



{{-- ... --}}

{{-- ... --}}

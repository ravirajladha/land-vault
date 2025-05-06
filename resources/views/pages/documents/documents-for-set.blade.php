<x-app-layout>

    <x-header />
    <x-sidebar/>

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            {{-- $tableName --}}
            <div class="row page-titles">

                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                    <li class="breadcrumb-item "><a href="/filter-document">View Document</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">All Document</a></li>
                </ol>

            </div>

            <div class="container-fluid">
                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">View Documents for Set: {{ $get_set_detail->name }} </h4>
                            
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example3" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                {{-- <th>
                                                    <div class="custom-control d-inline custom-checkbox ms-2">
                                                        <input type="checkbox" class="form-check-input" id="checkAll"
                                                            required="">
                                                        <label class="form-check-label" for="checkAll"></label>
                                                    </div>
                                                </th> --}}
                                                <th scope="col">Sl. No.</th>
                                                <th scope="col">Document Name</th>
                                                <th scope="col">Document Type</th>
                                               

                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- {{ dd($documentsDetails) }} --}}
                                            @foreach ($documentsDetails as $index => $item)
                                                {{-- @if (!$index == 0) --}}
                                                <tr>
                                                    {{-- <td>
                                                            <div class="form-check custom-checkbox ms-2">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="customCheckBox2" required="">
                                                                <label class="form-check-label"
                                                                    for="customCheckBox2"></label>
                                                            </div>
                                                        </td> --}}
                                                    <th scope="row">{{ $index + 1 }}</th>
                                                    <td scope="row">{{ $item->document_name }}  </td>
                                                    <td scope="row">{{  ucwords(str_replace('_', ' ', $item->doc_type)) }}</td>

                                                   
                                                    {{-- @if ($item->status == 0)
                                                        <td>
                                                            <span class="badge light badge-danger">
                                                                <i class="fa fa-circle text-danger me-1"></i>
                                                                Pending
                                                            </span>
                                                        </td>

                                                        <td><a href="{{ url('/') }}/review_doc/{{ $tableName }}/{{ $item->id }}"
                                                                type="button" class="btn btn-primary">Review</a>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <span class="badge light badge-success">
                                                                <i class="fa fa-circle text-success me-1"></i>
                                                                Accepted
                                                            </span>
                                                        </td>

                                                        <td><a href="{{ url('/') }}/review_doc/{{ $tableName }}/{{ $item->id }}"
                                                                type="button" class="btn btn-primary">View</a></td>
                                                    @endif --}}

                                                   
                                                <td>
                                                    @php
                                                        $statusClasses = ['0' => 'badge-danger text-danger', '1' => 'badge-success text-success', '2' => 'badge-warning text-warning'];
                                                        $statusTexts = ['0' => 'Pending', '1' => 'Accepted', '2' => 'Hold'];
                                                        $statusId = strval($item->status); // Convert to string to match array keys
        $statusClass = $statusClasses[$statusId] ?? 'badge-secondary text-secondary'; // Default class if key doesn't exist
        $statusText = $statusTexts[$statusId] ?? 'Unknown'; // Default text if key doesn't exist
                                                    @endphp
                                                
                                                    <span class="badge light {{ $statusClass }}">
                                                        <i class="fa fa-circle {{ $statusClass }} me-1"></i>
                                                        {{ $statusText }}
                                                    </span>
                                                </td>
                                                
                                                <td>
                                                    <a href="{{ url('/') }}/review_doc/{{ $item->doc_type }}/{{ $item->id }}"
                                                       type="button" class="btn btn-primary">
                                              
                                                        {{ $item->status == 1 ? 'View' : 'Review' }}
                                                    </a>
                                                </td>
                                                </tr>
                                                {{-- @endif --}}
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


    @include('layouts.footer')


</x-app-layout>

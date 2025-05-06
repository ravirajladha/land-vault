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
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Logs</a></li>

                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Action Logs</a></li>
                    </ol>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="filter cm-content-box box-primary">
                            <div class="content-title SlideToolHeader">
                                <h4>Filters</h4>
                                <div class="tools">
                                    <a href="javascript:void(0);" class="expand handle"><i
                                            class="fal fa-angle-down"></i></a>
                                </div>
                            </div>
                            <div class="cm-content-body form excerpt">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form action="{{ route('logs.action-logs') }}" method="GET">
                                                <div class="row">

                                                    <div class="mb-3 col-md-6">
                                                        <label for="start_due_date" class="form-label">Start
                                                            Date</label>
                                                        <input type="date" id="start_due_date" class="form-control"
                                                            name="start_due_date"
                                                            value="{{ request('start_due_date') }}">
                                                    </div>

                                                    <div class="mb-3 col-md-6">
                                                        <label for="end_due_date" class="form-label">End Date</label>
                                                        <input type="date" id="end_due_date" class="form-control"
                                                            name="end_due_date" value="{{ request('end_due_date') }}">
                                                    </div>

                                                    <div class="mb-3 col-md-6">
                                                        <label for="model_type" class="form-label">Model Type</label>
                                                        <select  class="form-select form-control"
                                                            name="model_type" id="single-select-abc1">
                                                            <option value="">Select Model</option>
                                                            @foreach ($uniqueModelTypes as $modelType)
                                                                <option value="{{ $modelType }}"
                                                                    {{ request('model_type') == $modelType ? 'selected' : '' }}>
                                                                    {{ $modelType }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3 col-md-6">
                                                        <label for="action_type" class="form-label">Action</label>
                                                        <select class="form-select form-control"
                                                            name="action_type" id="single-select-abc2">
                                                            <option value="">Select Action</option>
                                                            @foreach ($uniqueActionTypes as $actionType)
                                                                <option value="{{ $actionType }}"
                                                                    {{ request('action_type') == $actionType ? 'selected' : '' }}>
                                                                    {{ $actionType }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-12 d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-primary"><i
                                                            class="fas fa-filter"></i>&nbsp;Filter</button>
                                                        <a href="{{ route('logs.action-logs') }}"
                                                            class="btn btn-dark ms-2"><i
                                                            class="fas fa-refresh"></i>&nbsp;Reset</a>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="title">Action Logs</h5>

                            </div>
                            <div class="card-body">

                                <div class="table-responsive">
                                    {{-- <div class="table-responsive"> --}}
                                    {{-- <table id="example3" class="display" style="min-width: 845px"> --}}
                                    @if ($logs->isEmpty())
                                        <p>No logs available.</p>
                                    @else
                                        <table class="table table-responsive-md">
                                            {{--    <table id="example3" class="display"> --}}

                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>User ID</th>
                                                    <th>Model Type</th>
                                                    {{-- <th>Model ID</th> --}}
                                                    <th>Changes</th>
                                                    <th>Action</th>
                                                    <th>Created At</th>
                                                    {{-- <th>Updated At</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $counter = 0; // Calculate the starting index
                                                @endphp

                                                @foreach ($logs as $log)
                                                    @php $counter++; @endphp
                                                    <tr>
                                                        {{-- <td>{{ $counter }}</td>  --}}
                                                        <td>{{ $log->id }}</td> {{-- Display the user name --}}
                                                        <td>{{ $log->user_name }}</td> {{-- Display the user name --}}
                                                        <td>{{ ucwords(str_replace('_', ' ', class_basename($log->model_type))) }}
                                                        </td>
                                                        {{-- Remove namespace prefix --}}
                                                        <td style="max-width:200px; word-wrap:break-word;">
                                                            {{ $log->changes }}</td> {{-- Use max-width and word-wrap --}}
                                                        <td>{{ ucwords($log->action) }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($log->created_at)->format('H:i d-M-Y') }}

                                                        </td>


                                                        {{-- <td>{{ Carbon::parse($soldLands->created_at)->format('H:i d-M-Y') }}</td> --}}
                                                        {{-- <td>{{ \Carbon\Carbon::parse($log->updated_at)->format('H:i d/m/Y') }}</td>  --}}
                                                        {{-- Format the updated_at timestamp --}}
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                        {{-- <div class="row">
                                        {{ $logs->links() }}

                                        </div> --}}
                                        <div class="row">
                                            <div class="col">
                                                {{ $logs->links('vendor.pagination.custom') }}
                                            </div>
                                        </div>
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
</script>

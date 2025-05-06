@php
    use Carbon\Carbon;
@endphp
<x-app-layout>

    <x-header />
    <x-sidebar />

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">

            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-body">
                        <div class="row page-titles">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/users">Users</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0)">Reviewed Documents User
                                        Wise Count</a>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-xxl-12 col-sm-12">
                                <div class="card text-white bg-primary">
                                    <div class="row p-3">
                                        <div class="col-4">
                                            <span class="mb-0 text-white">Name :</span>
                                            <strong class="text-white">{{ $user_detail->name }}</strong>
                                        </div>
                                        <div class="col-4">
                                            <span class="mb-0 text-white">Email Id :</span>
                                            <strong class="text-white">{{ $user_detail->email }}</strong>
                                        </div>
                                        <div class="col-4">
                                            <span class="mb-0 text-white">Phone :</span>
                                            <strong class="text-white">{{ $user_detail->phone ?? 'xxxxxxxxx' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                      

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card text-black">
                 
                                        <div class="card-footer pt-0 pb-0 text-center">
                                            <div class="row">
                                                <div class="col-2 pt-3 pb-3 border-end"
                                                    style="background-color: #f8f9fa;">
                                                    <h3 class="mb-1 text-dark">Total Counts</h3>
                                                    {{-- <span>Total Counts</span> --}}
                                                </div>
                                                <div class="col-2 pt-3 pb-3 border-end"
                                                    style="background-color: #e9ecef;">
                                                    <h3 class="mb-1 text-primary">
                                                        {{ $data1['MasterDocData']['Pending'] }}</h3>
                                                    <span>Pending</span>
                                                </div>
                                                <div class="col-2 pt-3 pb-3 border-end"
                                                    style="background-color: #dee2e6;">
                                                    <h3 class="mb-1 text-primary">
                                                        {{ $data1['MasterDocData']['Approved'] }}</h3>
                                                    <span>Approved</span>
                                                </div>
                                                <div class="col-2 pt-3 pb-3 border-end"
                                                    style="background-color: #ced4da;">
                                                    <h3 class="mb-1 text-primary">{{ $data1['MasterDocData']['Hold'] }}
                                                    </h3>
                                                    <span>Hold</span>
                                                </div>
                                                <div class="col-2 pt-3 pb-3 border-end"
                                                    style="background-color: #adb5bd;">
                                                    <h3 class="mb-1 text-primary">
                                                        {{ $data1['MasterDocData']['Reviewer Feedback'] }}</h3>
                                                    <span>Review Feedback</span>
                                                </div>
                                                <div class="col-2 pt-3 pb-3 border-end"
                                                    style="background-color: #6c757d;">
                                                    <h3 class="mb-1 text-primary">{{ $data1['MasterDocData']['Total'] }}
                                                    </h3>
                                                    <span>Total </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12">

                                <div class="filter cm-content-box box-primary">
                                    <div class="content-title SlideToolHeader">
                                        <h4>
                                            Search Document
                                        </h4>
                                        <div class="tools">
                                            <a href="javascript:void(0);" class="expand handle"><i
                                                    class="fal fa-angle-down"></i></a>
                                        </div>
                                    </div>
                                    {{-- {{ dd($request->all()) }} --}}
                                    <div class="cm-content-body  form excerpt">
                                        <div class="card-body">
                            <form action="{{ url('users/' . $user_detail->id . '/reviewed-documents') }}" method="GET">
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <input type="date" name="start_date" class="form-control" value="{{ $start_date }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" name="end_date" class="form-control" value="{{ $end_date }}">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary"><i
                                            class="fas fa-filter"></i>&nbsp;Filter</button>
                                        <a href="{{ url('users/' . $user_detail->id . '/reviewed-documents') }}" class="btn btn-dark"><i
                                            class="fas fa-refresh"></i>&nbsp;Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        </div>
                        </div>
                        </div>
                            <div class="col-xl-12 col-lg-12 col-xxl-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Reviewed Documents Report</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive recentOrderTable">
                                            <table class="table verticle-middle table-responsive-md">
                                                <thead>
                                                
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Pending</th>
                                                        <th>Approved</th>
                                                        <th>Hold</th>
                                                        <th>Reviewer Feedback</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- <td>{{ Carbon::parse($date)->format('d-M-Y') }}</td> --}}
                                                    @foreach ($data as $date => $counts)
                                                        <tr>
                                                            <td>{{ $date }}</td>
                                                            <td>{{ $counts['Pending'] }}</td>
                                                            <td>{{ $counts['Approved'] }}</td>
                                                            <td>{{ $counts['Hold'] }}</td>
                                                            <td>{{ $counts['Reviewer Feedback'] }}</td>
                                                            <td>{{ $counts['Total'] }}</td>
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
    </div>

    @include('layouts.footer')


</x-app-layout>

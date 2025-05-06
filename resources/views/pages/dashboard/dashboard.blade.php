<x-app-layout>
    <x-header :route-name="Route::currentRouteName()" />
    <x-sidebar />
    <!--**********************************
            Content body start
        ***********************************-->
    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card tryal-gradient">
                                        <div class="card-body tryal row">
                                            <div class="col-xl-7 col-sm-7">
                                                <h2 class="mb-0">
                                                    <?php echo 'Namaskaram'; ?>
                                                </h2>

                                            </div>
                                            @if ($user && $user->hasPermission('Filter Document'))
                                                <div class="col-xl-5 col-sm-5 ">
                                                    <a href="/filter-document"><button class="btn btn-success"><i
                                                                class="fas fa-search"></i>&ensp;<u>Search
                                                                Documents</u></button></a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12">
                                    <div class="row">

                                        @php
                                            $bgClasses = ['bg-success', 'bg-info', 'bg-danger', 'bg-primary'];
                                            $classIndex = 0;
                                        @endphp
                                        @php
                                            //  dd($getCategoryDocumentCounts);
                                        @endphp
                                        @if (count($getCategoryDocumentCounts) > 0)
                                            @foreach ($getCategoryDocumentCounts as $category => $count)
                                                @if ($category !== '' && $category !== null)
                                                    <div class="col-xl-6 col-xxl-6 col-lg-6 col-sm-6">
                                                        {{-- <a href="/filter-document?category={{ urlencode($category) }}"> --}}
                                                            <div class="widget-stat card {{ $bgClasses[$classIndex] }}">
                                                                <div class="card-body p-4">
                                                                    <div class="media">


                                                                        <p class="mb-1"
                                                                            style="color: black; font-size: 16px;">
                                                                            {{ ucwords(str_replace('_', ' ', $category)) }}




                                                                        <div class="media-body text-white text-end">

                                                                            <h6 class="text-white">{{ $count }}
                                                                            </h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        {{-- </a> --}}
                                                    </div>
                                                @endif
                                                @php
                                                    $classIndex = ($classIndex + 1) % count($bgClasses); // Loop back to the beginning of the classes array
                                                @endphp
                                            @endforeach
                                        @endif

                                        <div class="col-xl-12 col-xxl-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-header border-0 pb-0">
                                                    <div>
                                                        <h4 class="card-title">Document Details</h4>
                                                        {{-- <p class="mb-0">Lorem ipsum dolor sit amet</p> --}}
                                                    </div>
                                                </div>
                                                <div class="card-body pb-0">
                                                    <div id="emailchart"> </div>
                                                    <div class="mb-3 mt-4">
                                                        <h4>Document Type (% - count)</h4>
                                                    </div>
                                                    <div class="scrollable-container"
                                                        style="max-height: 200px; overflow-y: auto;">
                                                        <div class="email-legend">
                                                            @php
                                                                $total = array_sum(
                                                                    $documentTypeWiseCounts['chartCounts'],
                                                                );
                                                            @endphp

                                                            @foreach ($documentTypeWiseCounts['chartLabels'] as $index => $label)
                                                                @php
                                                                    $count =
                                                                        $documentTypeWiseCounts['chartCounts'][$index];
                                                                    // $percentage =
                                                                    //     $total > 0 ? round(($count / $total) * 100) : 0;
                                                                    $percentage =
                                                                        $total > 0
                                                                            ? round(($count / $total) * 100, 2)
                                                                            : 0;
                                                                @endphp
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between mb-3">
                                                                    <span class="fs-16 text-gray">
                                                                        <svg class="me-2" width="20"
                                                                            height="20" viewBox="0 0 20 20"
                                                                            fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            {{-- Assuming you have a colors array or variable available that matches your chart colors --}}
                                                                            <rect width="20" height="20"
                                                                                rx="6"
                                                                                fill="{{ $documentTypeWiseCounts['colors'][$index] ?? '#CCCCCC' }}" />
                                                                        </svg>
                                                                        {{ $label }} ({{ $percentage }}%)
                                                                    </span>
                                                                    <h5 class="mb-0 font-w600">{{ $count }}
                                                                    </h5>
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <span class="fs-16 text-gray">
                                                            Total Documents
                                                        </span>
                                                        <h5 class="mb-0 font-w600">{{ $total }}</h5>
                                                    </div>
                                                </div>

                                                @if ($user && $user->hasPermission('View Document Types'))
                                                    <div class="card-footer border-0 pt-0">
                                                        <a href="/document_type"
                                                            class="btn btn-outline-primary btn-rounded d-block">View
                                                            Documents</a>

                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>



                            </div>

                        </div>
                        <div class="col-xl-6">
                            <div class="row">

                                <div class="col-xl-12">
                                    <div class="row">
                                        <div class="col-xl-6 col-xxl-6 col-lg-12 col-sm-12">
                                            <div class="widget-stat card bg-success">
                                                <div class="card-body p-4">
                                                    <div class="media">
                                                        <span class="me-3">
                                                            <i class="flaticon-381-file"></i>
                                                        </span>
                                                        <div class="media-body text-white text-end">
                                                            <p class="mb-1">Total Document Type</p>

                                                            <h3 class="text-white">
                                                                {{ $documentTypeWiseCounts['total_document_type'] }}
                                                            </h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>go
                                        </div>
                                        <div class="col-xl-6 col-xxl-6 col-lg-12 col-sm-12">
                                            <div class="widget-stat card bg-secondary">
                                                <div class="card-body  p-4">
                                                    <div class="media">
                                                        <span class="me-3">
                                                            <i class="flaticon-381-calendar-1"></i>
                                                        </span>
                                                        <div class="media-body text-white text-end">
                                                            <p class="mb-1">Total Document</p>
                                                            <h3 class="text-white">{{ $documentCount }}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @php
                                            $totalAcceptedCount = array_sum($documentTypeWiseCounts['acceptedCounts']);
                                            $acceptedProgress =
                                                $documentCount > 0 ? ($totalAcceptedCount / $documentCount) * 100 : 0;
                                        @endphp

                                        @php
                                            $totalNotAcceptedCount = array_sum(
                                                $documentTypeWiseCounts['notAcceptedCounts'],
                                            );
                                            // dd($totalNotAcceptedCount);
                                            $notAcceptedProgress =
                                                $documentCount > 0
                                                    ? ($totalNotAcceptedCount / $documentCount) * 100
                                                    : 0;
                                        @endphp
                                        @php
                                            $totalHoldedCount = array_sum($documentTypeWiseCounts['holdedCounts']);
                                            $totalReviewerHoldedCount = array_sum(
                                                $documentTypeWiseCounts['reviewerHoldCounts'],
                                            );
                                            // dd($totalNotAcceptedCount);
                                        @endphp
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="card">
                                                <div
                                                    class="card-body card-padding d-flex align-items-center justify-content-between">
                                                    <div class="w-75">
                                                        <h4 class="mb-3 text-nowrap">Pending Documents</h4>
                                                        <div class="progress default-progress">
                                                            <div class="progress-bar bg-gradient1 progress-animated"
                                                                style="width: {{ $notAcceptedProgress }}%; height:8px;"
                                                                role="progressbar">
                                                                <span class="sr-only">{{ $notAcceptedProgress }}%
                                                                    Complete</span>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2">
                                                            <p class="mb-0">
                                                                <strong
                                                                    class="text-danger me-2">{{ $totalHoldedCount }}</strong>on
                                                                Hold ~ <strong
                                                                    class="text-danger me-2">{{ $totalReviewerHoldedCount }}</strong>on
                                                                Reviewer Hold

                                                            </p>
                                                            {{-- <p class="mb-0">
                                                                <strong
                                                                    class="text-danger me-2">{{ $totalReviewerHoldedCount }}</strong>on
                                                                reviewer hold

                                                            </p> --}}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h2 class="fs-32 font-w700 mb-0">
                                                            {{ $totalNotAcceptedCount + $totalHoldedCount + $totalReviewerHoldedCount }}
                                                        </h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-sm-6">
                                            <div class="card">
                                                <div
                                                    class="card-body card-padding d-flex align-items-center justify-content-between">
                                                    <div class="w-75">
                                                        <h4 class="mb-3 text-nowrap">Approved Documents</h4>
                                                        {{-- Assuming you have a way to calculate the progress percentage --}}
                                                        <div class="progress default-progress">
                                                            <div class="progress-bar bg-gradient1 progress-animated"
                                                                style="width: {{ $acceptedProgress }}%; height:8px;"
                                                                role="progressbar">
                                                                <span class="sr-only">{{ $acceptedProgress }}%
                                                                    Complete</span>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2">
                                                            <p class="mb-0"><strong
                                                                    class="text-success me-2">{{ $documentCount - $totalAcceptedCount }}</strong>Remaining
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h2 class="fs-32 font-w700 mb-0">{{ $totalAcceptedCount }}
                                                        </h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-xxl-6 col-lg-12 col-sm-12">
                                            <div class="widget-stat card bg-primary">
                                                <div class="card-body p-4">
                                                    <div class="media">
                                                        <span class="me-3">
                                                            <i class="flaticon-381-user-7"></i>
                                                        </span>
                                                        <div class="media-body text-white text-end">
                                                            <p class="mb-1">Total Users</p>
                                                            <h3 class="text-white">
                                                                {{ $documentTypeWiseCounts['userCounts'] }}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-xxl-6 col-lg-12 col-sm-12">
                                            <div class="widget-stat card bg-info">
                                                <div class="card-body p-4">
                                                    <div class="media">
                                                        {{-- <span class="me-3">
															<i class="flaticon-381-heart"></i>
														</span> --}}
                                                        <span class="me-3">
                                                            <i class="flaticon-381-user-7"></i>
                                                        </span>
                                                        <div class="media-body text-white text-end">
                                                            <p class="mb-1">Total Receivers</p>
                                                            <h3 class="text-white">{{ $getRecieverCount }}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                            $totalAreaFeet = $getGeographicalCounts['totalAreaFeet']; // This is your area in square feet

                                            // Convert square feet to acres
                                            $totalAreaAcres =
                                                $totalAreaFeet / 43560 + $getGeographicalCounts['totalAreaAcre'];

                                        @endphp

                                        <div class="col-xl-12 col-lg-12 col-sm-12">
                                            <div class="card overflow-hidden">
                                                <div class="card-body" style="padding:0;">
                                                    <div class="text-center">

                                                    </div>
                                                </div>

                                                <div class="card-footer pt-0 pb-0 text-center">
                                                    <div class="row">
                                                        <div class="col-4 pt-3 pb-3 border-end">
                                                            <h3 class="mb-1">
                                                                {{ $getGeographicalCounts['districtCount'] }}</h3>
                                                            <span>Districts</span>
                                                        </div>
                                                        <div class="col-4 pt-3 pb-3 border-end">
                                                            <h3 class="mb-1">
                                                                {{ $getGeographicalCounts['villageCount'] }}</h3>
                                                            <span>Villages</span>
                                                        </div>
                                                        <div class="col-4 pt-3 pb-3">
                                                            <h3 class="mb-1">
                                                                {{ $getGeographicalCounts['talukCount'] }}</h3>
                                                            <span>Taluk</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-xl-12 col-xxl-12 col-lg-12">
                                            <div class="card border-0 pb-0">
                                                <div class="card-header border-0 pb-0">
                                                    <h4 class="card-title">Users Record on Document Status Change</h4>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div id="DZ_W_Todo3"
                                                        class="widget-media p-4 dlab-scroll height370">
                                                        <ul class="timeline">
                                                            @foreach ($users as $user)
                                                                <li>
                                                                    <div class="timeline-panel">
                                                                        <div class="media me-2">
                                                                            <img alt="image" width="50"
                                                                                src="/assets/images/avatar/avatar.jpg">
                                                                        </div>
                                                                        <div class="media-body">
                                                                            <h5 class="mb-1">{{ $user->name }}
                                                                                <small class="text-muted float-right">
                                                                                    <a href="/users/{{ $user->id }}/reviewed-documents"
                                                                                        class="btn btn-primary btn-xxs shadow"
                                                                                        style="float: right;">
                                                                                        <i class="fa fa-eye"></i>
                                                                                    </a>
                                                                                </small>
                                                                            </h5>

                                                                            <div class="row">
                                                                                <div
                                                                                    class="col-3 pt-3 pb-3 border-end">
                                                                                    <h3 class="mb-1">
                                                                                        {{ $user->todayCounts['Pending'] }}
                                                                                    </h3>
                                                                                    <span>Pending</span>
                                                                                </div>
                                                                                <div
                                                                                    class="col-3 pt-3 pb-3 border-end">
                                                                                    <h3 class="mb-1">
                                                                                        {{ $user->todayCounts['Approved'] }}
                                                                                    </h3>
                                                                                    <span>Approved</span>
                                                                                </div>
                                                                                <div class="col-3 pt-3 pb-3">
                                                                                    <h3 class="mb-1">
                                                                                        {{ $user->todayCounts['Hold'] }}
                                                                                    </h3>
                                                                                    <span>Hold</span>
                                                                                </div>
                                                                                <div class="col-3 pt-3 pb-3">
                                                                                    <h3 class="mb-1">
                                                                                        {{ $user->todayCounts['Reviewer Feedback'] }}
                                                                                    </h3>
                                                                                    <span>Reviewer Feedback</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($user && $user->hasPermission('View Advocates'))
                                        <div class="col-xl-12 col-lg-12 col-xxl-12 col-sm-12">
                                            <div class="card text-black">
                                                <div style="max-height: 300px; overflow-y: auto;"> <!-- Wrapper div with scrollable content -->
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item d-flex justify-content-between">
                                                            <a href="{{ route('advocates.index') }}"> 
                                                                <span class="mb-0">Advocates List</span>
                                                            </a>
                                                            <p>Document Counts</p>
                                                        </li>
                                                        @foreach ($getAdvocateDocumentCounts as $advocate)
                                                            <a href="{{ route('advocate.documents.assigned.show', $advocate['advocate_id']) }}">
                                                                <li class="list-group-item d-flex justify-content-between">
                                                                    <span class="mb-0">{{ $advocate['advocate_name'] }}</span>
                                                                    <strong>
                                                                        <u>{{ $advocate['document_count'] }}</u>
                                                                    </strong>
                                                                </li>
                                                            </a>
                                                        @endforeach
                                                    </ul>
                                                </div>
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
    </div>
    <!--**********************************
            Content body end
        ***********************************-->
    <!-- Modal -->
    <div class="modal fade" id="sendMessageModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form class="comment-form">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label required">Name </label>
                                    <input type="text" class="form-control" value="Author" name="Author"
                                        placeholder="Author">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">Email </label>
                                    <input type="text" class="form-control" value="Email" placeholder="Email"
                                        name="Email">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">Comment</label>
                                    <textarea rows="8" class="form-control" name="comment" placeholder="Comment"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3 mb-0">
                                    <input type="submit" value="Post Comment" class="submit btn btn-primary"
                                        name="submit">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footer')

    <!--**********************************
           Support ticket button start
        ***********************************-->

    <!--**********************************
           Support ticket button end
        ***********************************-->

</x-app-layout>

<script>
    var emailchart = function() {
        var options = {
            series: @json($documentTypeWiseCounts['chartCounts']),
            labels: @json($documentTypeWiseCounts['chartLabels']),
            chart: {
                type: 'donut',
                height: 230
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 0,
            },
            colors: @json($documentTypeWiseCounts['colors']),
            legend: {
                position: 'bottom',
                show: false
            },
            responsive: [{
                breakpoint: 1800,
                options: {
                    chart: {
                        height: 200
                    },
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#emailchart"), options);
        chart.render();
    }

    // Call the function to initialize the chart
    emailchart();
</script>
<script src="/assets/vendor/apexchart/apexchart.js"></script>

@php
    use Carbon\Carbon;

@endphp

<x-app-layout>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <x-header />
    <x-sidebar />

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-body">
                <div class="row page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>

                        <li class="breadcrumb-item active"><a href="/sold-land">Sold Land</a></li>
                        <li class="breadcrumb-item active"><a href="#">Sold Land Details</a></li>
                    </ol>
                </div>


                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="filter cm-content-box box-primary">
                                <div class="content-title SlideToolHeader">
                                    <div class="cpa">
                                        Sold Lands Detail

                                    </div>
                                    <div class="tools">
                                        @if ($user && $user->hasPermission('Update Sold Land'))
                                            <a href="/sold-land/{{ $id }}/edit" target="_blank"> <button
                                                    type="button" class="btn btn-success btn-sm float-end">
                                                    <i class="fas fa-pencil"></i>&nbsp; Edit
                                                </button></a>
                                        @endif
                                        <a href="javascript:void(0);" class="expand handle"><i
                                                class="fal fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="cm-content-body publish-content form excerpt">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-bordered table-striped verticle-middle table-responsive-sm">
                                             
                                                <tbody>
                                                    @if ($soldLands->district_number)
                                                        <tr>
                                                            <th>District Number</th>
                                                            <td>{{ $soldLands->district_number }}</td>
                                                        </tr>
                                                    @endif
                                                    @if ($soldLands->district)
                                                        <tr>
                                                            <th>District</th>
                                                            <td>{{ $soldLands->district }}</td>
                                                        </tr>
                                                    @endif
                                                    @if ($soldLands->district)
                                                        <tr>
                                                            <th>Village Number</th>
                                                            <td>{{ $soldLands->district }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->village)
                                                        <tr>
                                                            <th>Village</th>
                                                            <td>{{ $soldLands->village }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->survey_number)
                                                        <tr>
                                                            <th>Survey Number</th>
                                                            <td>{{ $soldLands->survey_number }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->wet_land)
                                                        <tr>
                                                            <th>Wet Land</th>
                                                            <td>{{ $soldLands->wet_land }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->dry_land)
                                                        <tr>
                                                            <th>Dru Land</th>
                                                            <td>{{ $soldLands->dry_land }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->plot)
                                                        <tr>
                                                            <th>Plot</th>
                                                            <td>{{ $soldLands->plot }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->traditional_land)
                                                        <tr>
                                                            <th>Traditional Land</th>
                                                            <td>{{ $soldLands->traditional_land }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->total_area)
                                                        <tr>
                                                            <th>Total Area</th>
                                                            <td>{{ $soldLands->total_area }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->total_area_unit)
                                                        <tr>
                                                            <th>Total Area Unit</th>
                                                            <td>{{ $soldLands->total_area_unit }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->total_wet_land)
                                                        <tr>
                                                            <th>Total Wet Land</th>
                                                            <td>{{ $soldLands->total_wet_land }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->total_dry_land)
                                                        <tr>
                                                            <th>Total Dry Land</th>
                                                            <td>{{ $soldLands->total_dry_land }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->gap)
                                                        <tr>
                                                            <th>Gap</th>
                                                            <td>{{ $soldLands->gap }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->sale_amount)
                                                        <tr>
                                                            <th>Sale Amount</th>
                                                            <td>{{ $soldLands->sale_amount }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->total_sale_amount)
                                                        <tr>
                                                            <th>Total Sale Amount</th>
                                                            <td>{{ $soldLands->total_sale_amount }}</td>
                                                        </tr>
                                                    @endif
                                                    @if ($soldLands->sale_date)
                                                        <tr>
                                                            <th>Sale Date</th>
                                                            <td>{{ Carbon::parse($soldLands->sale_date)->format('d-M-Y') }}
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->registration_office)
                                                        <tr>
                                                            <th>Registration Office</th>
                                                            <td>{{ $soldLands->registration_office }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->register_number)
                                                        <tr>
                                                            <th>Register Number</th>
                                                            <td>{{ $soldLands->register_number }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->register_date)
                                                        <tr>
                                                            <th>Register Date</th>
                                                            <td>{{ Carbon::parse($soldLands->register_date)->format('d-M-Y') }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if ($soldLands->book_number)
                                                        <tr>
                                                            <th>Book Number</th>
                                                            <td>{{ $soldLands->book_number }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->name_of_the_purchaser)
                                                        <tr>
                                                            <th>Name of the Purchaser</th>
                                                            <td>{{ $soldLands->name_of_the_purchaser }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->balance_land)
                                                        <tr>
                                                            <th>Balance Land</th>
                                                            <td>{{ $soldLands->balance_land }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->remark)
                                                        <tr>
                                                            <th>Remark</th>
                                                            <td>{{ $soldLands->remark }}</td>
                                                        </tr>
                                                    @endif

                                                    @if ($soldLands->file)
                                                        <!-- Assuming 'file' contains the path to the PDF file -->
                                                        <tr>
                                                            <th> Document</th>
                                                            <td>
                                                                <a href="{{ asset($soldLands->file) }}"
                                                                    target="_blank">View PDF</a>
                                                                <!-- Or embed a PDF viewer if you prefer -->
                                                                <!-- <embed src="{{ asset($soldLands->file) }}" type="application/pdf" width="100%" height="600px" /> -->
                                                            </td>
                                                        </tr>
                                                    @endif


                                                    @if ($soldLands->created_at)
                                                    <tr>
                                                        <th>Created At</th>
                                                        <td>{{ Carbon::parse($soldLands->created_at)->format('H:i d-M-Y') }}</td>
                                                    </tr>
                                                @endif
                                                    @if ($soldLands->latitude && $soldLands->longitude)
                                                        <tr>
                                                            <th>Latitude</th>
                                                            <td>{{ $soldLands->latitude }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Longitude</th>
                                                            <td>{{ $soldLands->longitude }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>View on Map</th>
                                                            <td>
                                                                <a href="https://www.google.com/maps/search/?api=1&query={{ $soldLands->latitude }},{{ $soldLands->longitude }}"
                                                                    target="_blank" class="btn btn-primary">View on
                                                                    Google Maps</a>
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    <!-- Continue with other attributes -->
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
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
<!-- Latest compiled and minified jQuery -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

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
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Sold Lands</a></li>

                            </ol>
                        </div>
                    </div>

                    <form id="soldLandForm"
                        action="{{ isset($soldLand) ? route('soldLand.storeOrUpdate', $soldLand->id) : route('soldLand.storeOrUpdate') }}"
                        method="post" enctype="multipart/form-data">

                        @csrf <!-- Include CSRF token -->


                        @csrf
                        <div class="container-fluid">
                            <div class="row">

                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Sold Land Details</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="basic-form">
                                                <div class="row">
                                                    @if ($errors->any())
                                                        <div class="alert alert-danger">
                                                            <ul>
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif

                                                    <div class="mb-3 col-md-4">
                                                        <div class="mb-3">
                                                            <label for="district_number" class="form-label">State
                                                            </label>
                                                            <input type="text" class="form-control" name="state"
                                                                id="state" aria-describedby="emailHelp"
                                                                placeholder="Enter State "
                                                                value="{{ old('state', $soldLand->state ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-md-4">
                                                        <div class="mb-3">
                                                            <label for="district_number" class="form-label">District
                                                                Number</label>
                                                            <input type="text" class="form-control"
                                                                name="district_number" id="district_number"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter District Number"
                                                                value="{{ old('district_number', $soldLand->district_number ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-md-4">
                                                        <div class="mb-3">
                                                            <label for="district" class="form-label">District</label>
                                                            <input type="text" class="form-control" name="district"
                                                                id="district" aria-describedby="emailHelp"
                                                                placeholder="Enter District"
                                                                value="{{ old('district', $soldLand->district ?? '') }}">
                                                        </div>
                                                    </div>

                                                    <div class="mb-3 col-md-6">
                                                        <div class="mb-3">
                                                            <label for="village_number" class="form-label">Village
                                                                Number</label>
                                                            <input type="text" class="form-control"
                                                                name="village_number" id="village_number"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter Village Number"
                                                                value="{{ old('village_number', $soldLand->village_number ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <div class="mb-3">
                                                            <label for="village" class="form-label">Village</label>
                                                            <input type="text" class="form-control" name="village"
                                                                id="village" aria-describedby="emailHelp"
                                                                placeholder="Enter Village"
                                                                value="{{ old('village', $soldLand->village ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <hr />

                                                    <div class="mb-3 col-md-6">
                                                        <div class="mb-3">
                                                            <label for="survey_number" class="form-label">Survey
                                                                Number</label>
                                                            <input type="text" class="form-control"
                                                                name="survey_number" id="survey_number"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter Survey Number"
                                                                value="{{ old('survey_number', $soldLand->survey_number ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <div class="mb-3">
                                                            <label for="plot" class="form-label">Plot</label>
                                                            <input type="text" class="form-control" name="plot"
                                                                id="plot" aria-describedby="emailHelp"
                                                                placeholder="Enter Plot"
                                                                value="{{ old('plot', $soldLand->plot ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <hr />

                                                    <div class="mb-3 col-md-6">
                                                        <div class="mb-3">
                                                            <label for="wet_land" class="form-label">Wet Land</label>
                                                            <input type="text" class="form-control"
                                                                name="wet_land" id="wet_land"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter Wet Land"
                                                                value="{{ old('wet_land', $soldLand->wet_land ?? '') }}">
                                                        </div>
                                                    </div>

                                                    <div class="mb-3 col-md-6">
                                                        <div class="mb-3">
                                                            <label for="dry_land" class="form-label">Dry Land</label>
                                                            <input type="text" class="form-control"
                                                                name="dry_land" id="dry_land"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter Dry Land"
                                                                value="{{ old('dry_land', $soldLand->dry_land ?? '') }}">
                                                        </div>
                                                    </div>

                                                    <div class="mb-3 col-md-6">
                                                        <div class="mb-3">
                                                            <label for="traditional_land"
                                                                class="form-label">Traditional Land</label>
                                                            <input type="text" class="form-control"
                                                                name="traditional_land" id="traditional_land"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter Traditional Land"
                                                                value="{{ old('traditional_land', $soldLand->traditional_land ?? '') }}">
                                                        </div>
                                                    </div>

                                                    <div class="mb-3 col-md-6">
                                                        <div class="mb-3">
                                                            <label for="total_area" class="form-label">Total
                                                                Area</label>
                                                            <input type="text" class="form-control"
                                                                name="total_area" id="total_area"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter Total Area"
                                                                value="{{ old('total_area', $soldLand->total_area ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-md-4">
                                                        <div class="mb-3">
                                                            <label for="total_area_unit" class="form-label">Total Area
                                                                Unit</label>
                                                            <input type="text" class="form-control"
                                                                name="total_area_unit" id="total_area_unit"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter Area Unit"
                                                                value="{{ old('total_area_unit', $soldLand->total_area_unit ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-md-4">
                                                        <div class="mb-3">
                                                            <label for="total_wet_land" class="form-label">Total Wet
                                                                Land</label>
                                                            <input type="text" class="form-control"
                                                                name="total_wet_land" id="total_wet_land"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter Total Wet Land"
                                                                value="{{ old('total_wet_land', $soldLand->total_wet_land ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-md-4">
                                                        <div class="mb-3">
                                                            <label for="total_dry_land" class="form-label">Total Dry
                                                                Land</label>
                                                            <input type="text" class="form-control"
                                                                name="total_dry_land" id="total_dry_land"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter Total Dry Land"
                                                                value="{{ old('total_dry_land', $soldLand->total_dry_land ?? '') }}">
                                                        </div>
                                                    </div>

                                                    <hr />
                                                    <div class="mb-3 col-md-4">
                                                        <div class="mb-3">
                                                            <label for="sale_amount" class="form-label">Sale
                                                                Amount</label>
                                                            <input type="text" class="form-control"
                                                                name="sale_amount" id="sale_amount"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter Sale Amount"
                                                                value="{{ old('sale_amount', $soldLand->sale_amount ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-md-4">
                                                        <div class="mb-3">
                                                            <label for="total_sale_amount" class="form-label">Total
                                                                Sale Amount</label>
                                                            <input type="text" class="form-control"
                                                                name="total_sale_amount" id="total_sale_amount"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter Total Sale Amount"
                                                                value="{{ old('total_sale_amount', $soldLand->total_sale_amount ?? '') }}">
                                                        </div>
                                                    </div>
                                                    {{-- <div class="mb-3 col-md-4">
                                                        <div class="mb-3">
                                                            <label for="total_sale_amount" class="form-label">Sale Date
                                                </label>
                                                            <input type="date" class="form-control"
                                                                name="sale_date" id="sale_date"
                                                                aria-describedby="emailHelp"
                                                               
                                                                value="{{ old('sale_date', $soldLand->sale_date ?? '') }}">
                                                        </div>
                                                    </div> --}}
                                                    <div class="mb-3 col-md-4">
                                                        <div class="mb-3">
                                                            <label for="registration_office"
                                                                class="form-label">Registration Office</label>
                                                            <input type="text" class="form-control"
                                                                name="registration_office" id="registration_office"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter Registration Office"
                                                                value="{{ old('registration_office', $soldLand->registration_office ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <div class="mb-3">
                                                            <label for="register_number" class="form-label">Register
                                                                Number</label>
                                                            <input type="text" class="form-control"
                                                                name="register_number" id="register_number"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter Register Number"
                                                                value="{{ old('register_number', $soldLand->register_number ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <div class="mb-3">
                                                            <label for="register_date" class="form-label">Register
                                                                Date</label>
                                                            <input type="date" class="form-control"
                                                                name="register_date" id="register_date"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter Register Date"
                                                                value="{{ old('register_date', $soldLand->register_date ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <div class="mb-3">
                                                            <label for="book_number" class="form-label">Book
                                                                Number</label>
                                                            <input type="text" class="form-control"
                                                                name="book_number" id="book_number"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter Book Number"
                                                                value="{{ old('book_number', $soldLand->book_number ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <div class="mb-3">
                                                            <label for="name_of_the_purchaser" class="form-label">Name
                                                                of the Purchaser</label>
                                                            <input type="text" class="form-control"
                                                                name="name_of_the_purchaser"
                                                                id="name_of_the_purchaser"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter Name of the Purchaser"
                                                                value="{{ old('name_of_the_purchaser', $soldLand->name_of_the_purchaser ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <div class="mb-3">
                                                            <label for="balance_land" class="form-label">Balance
                                                                Land</label>
                                                            <input type="text" class="form-control"
                                                                name="balance_land" id="balance_land"
                                                                aria-describedby="emailHelp"
                                                                placeholder="Enter Balance Land"
                                                                value="{{ old('balance_land', $soldLand->balance_land ?? '') }}">
                                                        </div>
                                                    </div>

                                                    <div class="mb-3 col-md-6">
                                                        <div class="mb-3">
                                                            <label for="gap" class="form-label">Gap</label>
                                                            <input type="text" class="form-control" name="gap"
                                                                id="gap" aria-describedby="emailHelp"
                                                                placeholder="Enter Gap"
                                                                value="{{ old('gap', $soldLand->gap ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Latitude</label>
                                                        <input type="text" name="latitude" class="form-control"
                                                            placeholder="Enter Latitude (-90 to +90)"
                                                            value="{{ old('latitude', $soldLand->latitude ?? '') }}">
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Longitude</label>
                                                        <input type="text" name="longitude" class="form-control"
                                                            placeholder="Enter Longitude (-180 to +180)"
                                                            value="{{ old('longitude', $soldLand->longitude ?? '') }}">
                                                    </div>
                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">Document Upload</label>
                                                        <input type="file" name="file" class="form-control">
                                                    </div>


                                                    <div class="mb-3 col-md-12">
                                                        <div class="mb-3">
                                                            <label for="remark" class="form-label">Remark</label>
                                                            <textarea class="form-control col-12" name="remark" id="remark" rows="4" placeholder="Enter Remark">{{ old('remark', $soldLand->remark ?? '') }}</textarea>

                                                        </div>
                                                    </div>
                                                </div>



                                            </div>

                                            {{-- <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox">
                                            <label class="form-check-label">
                                                Check me out
                                            </label>
                                        </div>
                                    </div> --}}

                                            <div class="card-footer">
                                                {{-- <a href="" class="btn-link"></a> --}}
                                                <div class="text-end">
                                                    <button class="btn btn-primary" id="submitButton"
                                                        type="submit">Submit</button>
                                                </div>
                                            </div>


                                            {{-- <button type="submit" class="btn btn-primary">Next</button> --}}

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
    $("#single-select-abc4").select2();

    $(".single-select-abc4-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
</script>
<script>
    document.getElementById('soldLandForm').addEventListener('submit', function(event) {
        const submitButton = document.getElementById('submitButton');
        const submittingMessage = document.getElementById('submittingMessage');

        // Disable the submit button and show a visual indication
        submitButton.disabled = true;
        submittingMessage.style.display = 'inline';

        // Prevent default form submission (optional, if using server-side validation)
        // event.preventDefault();

        // Simulate form submission time (optional, for user feedback)
        setTimeout(function() {
            // Re-enable the submit button and hide the message
            submitButton.disabled = false;
            submittingMessage.style.display = 'none';
        }, 2000); // Adjust delay as needed
    });
</script>





{{-- <script>
    // Disable the submit button and prevent multiple form submissions
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const submitButton = document.getElementById('submitButton');
        let formSubmitted = false;
        
        form.addEventListener('submit', function (event) {
            if (formSubmitted) {
                event.preventDefault(); // Prevent the form from being submitted again
                return;
            }
            
            submitButton.disabled = true; // Disable the submit button
            formSubmitted = true; // Set the flag to indicate that the form has been submitted
        });
    });
</script> --}}

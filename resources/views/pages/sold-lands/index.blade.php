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

                        <li class="breadcrumb-item active"><a href="/sold-land">Sold Land</a></li>
                    </ol>
                </div>


                <div class="row">
                    <div class="col-xl-12">

                        <div class="filter cm-content-box box-primary">
                            <div class="content-title SlideToolHeader">
                                <h4>
                                    Search Sold Land Document
                                </h4>
                                <div class="tools">
                                    <a href="javascript:void(0);" class="expand handle"><i
                                            class="fal fa-angle-down"></i></a>
                                </div>
                            </div>
                            <div class="cm-content-body  form excerpt">
                                <div class="card-body">
                                    <form action="{{ url('/') }}/sold-land" method="GET">
                                        @csrf
                                        <div class="row">
                                            <div class="mb-3 col-md-12 col-xl-12">
                                                <label class="form-label">Survey Number</label>
                                                <input name="survey_number" class="form-control"
                                                    placeholder="Enter Survery Number"
                                                    value="{{ request()->input('survey_number') }}">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label"> State <span data-bs-container="body" data-bs-toggle="popover"
                                                    data-bs-placement="top"
                                                    data-bs-content="The selection of State is mandatory to select District and Village. District and Village gets filter through the selection of State.">
                                                    <i class="fas fa-info-circle"></i>
                                                </span></label>
                                                <select class="form-select form-control" id="single-select-abctest3"
                                                    name="state" aria-label="State select">
                                                    <option value="" selected>Select State</option>
                                                    {{-- {{ dd($uniqueStates) }} --}}
                                                    @foreach ($uniqueStates as $state)
                                                        <option value="{{ $state }}"
                                                            {{ old('state') == $state ? 'selected' : '' }}>
                                                            {{ $state }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div id="loader-3" class="loader" style="display:none;"></div>
                                            </div>
    
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label"> District</label>
                                                <select class="form-select form-control" id="single-select-abctest4"
                                                    name="district" aria-label="District select">
                                                    <option value="" selected>Select District</option>
                                                </select>
                                                <div id="loader-4" class="loader" style="display:none;"></div>
                                            </div>
    
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label"> Village</label>
                                                <select class="form-select form-control" id="single-select-abctest5"
                                                    name="village" aria-label="Village select">
                                                    <option value="" selected>Select Village</option>
                                                </select>
                                                <div id="loader-5" class="loader" style="display:none;"></div>
                                            </div>
    


                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Register Date (Start)
                                                    {{ old('start_date') }}</label>
                                                <div class="input-hasicon">
                                                    <input name="start_date" type="date"
                                                        class="form-control  solid"
                                                        value="{{ request()->input('start_date') }}">
                                                    <div class="icon"><i class="far fa-calendar"></i></div>
                                                </div>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Register Date (End)</label>
                                                <div class="input-hasicon">
                                                    <input name="end_date" type="date"
                                                        class="form-control  solid"
                                                        value="{{ request()->input('end_date') }}">
                                                    <div class="icon"><i class="far fa-calendar"></i></div>
                                                </div>
                                            </div>

                                            <div class="mb-3 col-md-4 col-xl-4">
                                                <label class="form-label">Minimum Area Size</label>
                                                <input name="area_range_start" class="form-control"
                                                    placeholder="Enter Minimum Area Size"
                                                    value="{{ request()->input('area_range_start') }}">
                                            </div>

                                            <div class="mb-3 col-md-4 col-xl-4">
                                                <label class="form-label">Maximum Area Size</label>
                                                <input name="area_range_end" class="form-control"
                                                    placeholder="Enter Maximum Area Size"
                                                    value="{{ request()->input('area_range_end') }}">
                                            </div>

                                            <div class="mb-3 col-md-4 col-xl-4">
                                                <label class="form-label">Select Area Unit (Optional)</label>
                                                <select class="form-control" id="area-unit-dropdown" name="area_unit">
                                                    <option value="">Select Unit</option>
                                                    <option value="Acres"
                                                        {{ request()->input('area_unit') == 'Acres' ? 'selected' : '' }}>
                                                        Acres and Cents</option>
                                                    <option value="Square Feet"
                                                        {{ request()->input('area_unit') == 'Square Feet' ? 'selected' : '' }}>
                                                        Square Feet</option>
                                                </select>
                                            </div>
                                        </div>




                                        <div class="card-footer">
                                            <div class="text-end">      <a href="{{ url('/') }}/sold-land" class="btn btn-dark"><i
                                                class="fas fa-refresh"></i>&nbsp;Reset</a>
                                      <button class="btn btn-secondary" type="submit"><i
                                                        class="fas fa-filter"></i>&nbsp;Filter</button>
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="title mb-0">Sold Lands</h5>
                                <div class="button-group">
                                    <form action="{{ route('soldLand.export') }}" method="GET" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary btn-sm" style="margin-right: 10px;">
                                            <i class="fas fa-file-export"></i>&nbsp;Export
                                        </button>
                                    </form>
                                    @if ($user && $user->hasPermission('Add Sold Land'))
                                        <button type="button" class="btn btn-warning btn-sm" style="margin-right: 10px;" data-bs-toggle="modal" data-bs-target="#addDocumentTypeModal">
                                            <i class="fas fa-plus-square"></i>&nbsp; Bulk Upload
                                        </button>
                                        <a href="/sold-land-actions">
                                            <button type="button" class="btn btn-success btn-sm">
                                                <i class="fas fa-plus-square"></i>&nbsp; Add
                                            </button>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            

                            <div class="card-body">

                                <div class="table-responsive">
                                    {{-- <div class="table-responsive"> --}}
                                    {{-- <table id="example3" class="display" style="min-width: 845px"> --}}
                                    <table id="example3" class="display">

                                        <thead>
                                            <tr>
                                                <th scope="col">Sl. No.</th>
                                                <th scope="col">Survey Number</th>
                                                <th scope="col">District</th>
                                                <th scope="col">Village</th>
                                                <th scope="col">Total Area (Unit)</th>
                                                <th scope="col">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $index => $item)
                                                <tr>
                                                    <th scope="row">{{ $index + 1 }}</th>
                                                    <td>{{ $item->survey_number ? ucwords($item->survey_number) : '--' }}
                                                    </td>
                                                    <td>{{ $item->district ? ucwords($item->district) : '--' }}
                                                    </td>
                                                    <td>{{ $item->village ? ucwords($item->village) : '--' }}</td>
                                                    <td>{{ $item->total_area ? $item->total_area : '--' }}
                                                        ({{ $item->total_area_unit ? $item->total_area_unit : '--' }})
                                                    </td>


                                                    <!-- Display the count for each set -->
                                                    {{-- @if ($user && $user->hasPermission('View Documents from Sets'))
                                                            <td><a href="/documents-for-set/{{ $item->id }}"><button
                                                                        class="btn btn-secondary edit-btn"><i
                                                                            class="fas fa-eye"></i>&nbsp;View</button></a>
                                                            </td>
                                                        @endif --}}


                                                    <td>
                                                        <a href="/sold-land/{{ $item->id }}"><button
                                                                class="btn btn-secondary btn-sm  edit-btn"><i
                                                                    class="fas fa-eye"></i></button></a>
                                                        @if ($user && $user->hasPermission('Update Sold Land'))
                                                            <a href="/sold-land/{{ $item->id }}/edit">
                                                                <button class="btn btn-primary btn-sm edit-btn"><i
                                                                        class="fas fa-pencil"></i></button></a>
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <!-- Modal (outside the loop) -->
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModalCenter">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Set</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Update Form -->
                                                    <form id="updateSetForm">
                                                        <div class="mb-3">
                                                            <label for="setName" class="form-label">Update Set
                                                                Name&nbsp;<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" id="setName"
                                                                name="name" required>
                                                            <input type="hidden" id="setId" name="id">
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger light"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary"
                                                        onclick="submitUpdateForm()">Submit Form</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- modal end --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="addDocumentTypeModal" tabindex="-1" aria-labelledby="addDocumentTypeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="card-title">Bulk Upload Sold Land File</h4>

                    <div class="d-flex align-items-center">
                        <a href="/assets/sample/sold_land.csv" download="sample.csv">
                            <button type="button" class="btn btn-dark btn-sm">
                                <i class="fas fa-download"></i>&nbsp; Download Sample CSV File
                            </button>
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="card overflow-hidden">

                        <div class="card-body">
                            <form action="{{ url('/') }}/bulk-upload-sold-land-data" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label">Bulk Upload (in csv file format)</label>
                                        <div class="fallback">
                                            <input name="document" type="file" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-success">Submit</button>
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
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
<!-- Latest compiled and minified jQuery -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<script>
    $(document).ready(function() {
        $('#myAjaxForm').on('submit', function(e) {
            e.preventDefault(); // prevent the form from 'submitting'

            var url = $(this).attr('action'); // get the target URL
            var formData = new FormData(this); // create a FormData object

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false, // tell jQuery not to process the data
                contentType: false, // tell jQuery not to set contentType
                success: function(response) {

                    if (response.success) {
                        toastr.success(response.success); // Display success toast
                    }
                    loadUpdatedSets();
                    location.reload(true);

                    $('#myAjaxForm')[0].reset();
                },
                error: function(error) {
                    console.log(error);
                    toastr.warning("Duplicate set found");
                    if (error.responseJSON && error.responseJSON.error) {
                        toastr.error(error.responseJSON.error); // Display error toast
                    }
                }
            });
        });
    });


    function loadUpdatedSets() {
        $.ajax({
            url: '/get-updated-sets',
            type: 'GET',
            success: function(sets) {
                var newTableContent = '';
                $.each(sets, function(index, set) {
                    // Assuming 'set.count' is the property that has the count for each set
                    // and 'set.id' is the property that contains the set ID.
                    newTableContent += '<tr>' +
                        '<th scope="row">' + (index + 1) + '</th>' +
                        '<td>' + set.name + '</td>' +
                        '<td>' + (set.count ?? 0) + '</td>' + // Display the count for each set
                        '<td><a href="/documents-for-set/' + set.id +
                        '"><button class="btn btn-secondary"><i class="fas fa-eye"></i>&nbsp;View</button></a></td>' +
                        '<td> <button class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" data-set-id="' +
                        set.id + '" data-set-name="' + set.name +
                        '"><i class="fas fa-pencil"></i>&nbsp;Edit</button></td>' +
                        '</tr>';
                });
                $('#example3 tbody').html(newTableContent);
            }
        });
    }

    //set modal update
    $(document).ready(function() {
        $('.edit-btn').on('click', function() {
            var setId = $(this).data('set-id');
            var setName = $(this).data('set-name');

            // Prefill the form
            $('#updateSetForm #setName').val(setName);
            $('#updateSetForm #setId').val(setId);
        });
    });

    function submitUpdateForm() {
        var formData = $('#updateSetForm').serialize(); // Serialize form data
        console.log(formData);
        // AJAX call to update the set
        $.ajax({
            url: '/update-set', // Replace with your server's update URL
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                    'content') // Ensure this meta tag is available in your HTML
            },
            success: function(response) {
                // Handle success (e.g., close modal, show message, update table)
                toastr.success(response.success);
                location.reload(true);

                loadUpdatedSets();
            },
            error: function(error) {
                // Handle error
            }
        });
    }
</script>

<script>
    document.querySelector('form').onsubmit = function() {
        document.getElementById('preloader').style.display = 'block'; // Show loader
    };

    window.onload = function() {
        document.getElementById('preloader').style.display = 'none'; // Hide loader on page load
    };
</script>
<style>
    #loader {
        display: none;
        position: fixed;
        z-index: 999;
        height: 2em;
        width: 2em;
        overflow: visible;
        margin: auto;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }
</style>

<script>
    $("#single-select-abc1").select2();

    $(".single-select-abc1-placeholder").select2({
        placeholder: "Select a state",
        allowClear: true
    });
    $("#single-select-abc2").select2();

    $(".single-select-abc2-placeholder").select2({
        placeholder: "Select a District",
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

{{-- <script src="/assets/vendor/nouislider/nouislider.min.js"></script>
<script src="/assets/js/plugins-init/nouislider-init.js"></script> --}}
<script>
    document.getElementById('exportButton').addEventListener('click', function() {
        var table = document.getElementById('example3'); // Your table ID
        var rows = table.querySelectorAll('tr');
        var csv = [];

        for (var i = 0; i < rows.length; i++) {
            var row = [],
                cols = rows[i].querySelectorAll('td, th');

            for (var j = 0; j < cols.length - 1; j++) {
                // Clean the text content from the cell and escape double quotes
                var data = cols[j].innerText.replace(/"/g, '""');
                data = '"' + data + '"';
                row.push(data);
            }
            csv.push(row.join(','));
        }

        downloadCSV(csv.join('\n'));
    });

    function downloadCSV(csv) {
        var csvFile;
        var downloadLink;

        // CSV file
        csvFile = new Blob([csv], {
            type: "text/csv"
        });

        // Download link
        downloadLink = document.createElement("a");

        // File name
        downloadLink.download = 'export.csv';

        // Create a link to the file
        downloadLink.href = window.URL.createObjectURL(csvFile);

        // Hide download link
        downloadLink.style.display = "none";

        // Add the link to DOM
        document.body.appendChild(downloadLink);

        // Click download link
        downloadLink.click();
    }
</script>
<style>
    .loader {
        border: 4px solid #f3f3f3;
        border-radius: 50%;
        border-top: 4px solid #3498db;
        width: 20px;
        height: 20px;
        animation: spin 2s linear infinite;
        display: inline-block;
        vertical-align: middle;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
<script>
    function resetDropdown(id) {
        const dropdown = document.getElementById(id);
        const text = id.replace('single-select-abctest', ''); // Extract number suffix
        const label = {
            '3': 'State',
            '4': 'District',
            '5': 'Village'
        } [text] || 'Select'; // Map number to label, default to 'Select'

        dropdown.innerHTML = `<option value="" selected>Select ${label}</option>`;
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    document.getElementById('single-select-abctest3').addEventListener('change', function() {
        updateSelections('state', this.value);
    });

    document.getElementById('single-select-abctest4').addEventListener('change', function() {
        updateSelections('district', this.value);
    });

    function updateSelections(type, value) {
        if (type === 'state') {
            resetDropdown('single-select-abctest4');
            resetDropdown('single-select-abctest5');
        } else if (type === 'district') {
            resetDropdown('single-select-abctest5');
        }

        let url = '';
        switch (type) {
            case 'state':
                url = `/api/fetchForSold/districts/${value}`;
                fetchDropdownData(url, 'single-select-abctest4');
                targetId = 'single-select-abctest4';
                break;
            case 'district':
                url = `/api/fetchForSold/villages/${value}`;
                fetchDropdownData(url, 'single-select-abctest5');
                targetId = 'single-select-abctest5';
                break;
            default:
                console.error('Unhandled selection type:', type);
                return;
        }
        showLoader(targetId);
        fetchDropdownData(url, targetId);
    }

    function fetchDropdownData(url, targetId, selectedValue = '') {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const dropdown = document.getElementById(targetId);
                const loader = document.getElementById('loader-' + targetId.replace('single-select-abctest', ''));
                console.log("data", data);
                resetDropdown(targetId); // Reset with correct default text
                if (Array.isArray(data)) {
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item;
                        option.textContent = item;
                        if (item === selectedValue) {
                            option.selected = true;
                        }
                        dropdown.appendChild(option);
                    });
                } else {
                    console.error('Data format error:', data);
                }
                hideLoader(targetId);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                hideLoader(targetId);
            });
    }

    function showLoader(targetId) {
        const loaderId = 'loader-' + targetId.replace('single-select-abctest', '');
        const loader = document.getElementById(loaderId);
        if (loader) {
            loader.style.display = 'inline-block';
        }
    }

    function hideLoader(targetId) {
        const loaderId = 'loader-' + targetId.replace('single-select-abctest', '');
        const loader = document.getElementById(loaderId);
        if (loader) {
            loader.style.display = 'none';
        }
    }
    // Initialize dropdowns based on previous selections
    document.addEventListener('DOMContentLoaded', function() {
        const selectedState = document.getElementById('single-select-abctest3').value;
        const selectedDistrict = document.getElementById('single-select-abctest4').value;

        if (selectedState) {
            updateSelections('state', selectedState);
        }

        if (selectedState && selectedDistrict) {
            setTimeout(() => {
                updateSelections('district', selectedDistrict);
            }, 500); // Adjust timeout as necessary to ensure state dropdown is populated first
        }
    });
</script>
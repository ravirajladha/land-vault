<style>
    .bootstrap-popover .btn {
        padding: 2px 6px;
        /* Adjust padding for smaller size */
        font-size: 0.75rem;
        /* Adjust font size for smaller text */
    }
</style>
{{-- {{ dd($isStatus) }} --}}
{{-- @if ($isStatus) --}}
<div class="col-md-6">
    <div class="mb-3">
        <label for="documentType" class="form-label">Document Type </label>
        <div class="bootstrap-popover d-inline-block float-end ">
            @if ($isStatus)
            <button type="button" class="btn btn-primary btn-sm" data-bs-container="body" data-bs-toggle="popover"
                data-bs-placement="top"
                data-bs-content="The below filter contains [xx], this indicated the number of approved documents in each stage. To select the document, the user needs to select Document Type -> State -> District -> Village to filter and easily access the Document."
                title="Document Name Mandatory">
                <i class="fas fa-info-circle"></i>
            </button>
            @else
            <button type="button" class="btn btn-primary btn-sm" data-bs-container="body" data-bs-toggle="popover"
            data-bs-placement="top"
            data-bs-content="The below filter contains [xx], this indicated the number of documents in each stage. To select the document, the user needs to select Document Type -> State -> District -> Village to filter and easily access the Document."
            title="Document Name Mandatory">
            <i class="fas fa-info-circle"></i>
        </button>
            @endif
        </div>
        <select class="form-control" id="documentType" name="document_type"
            onchange="updateSelections('documentType', this.value)" required>
            <option value="">Select Document Type</option>
            @foreach ($documentTypes as $type)
                <option value="{{ $type->id }}">{{ ucwords(str_replace('_', ' ', $type->name)) }}
                    [{{ $type->approved_documents_count }}]</option>
                {{-- <option value="{{ $type->id }}">{{  $type->id }}</option> --}}
            @endforeach
        </select>
    </div>
</div>

<div class="col-md-6">
    <div class="mb-3">
        <label for="state" class="form-label">State</label>
        <select class="form-control" id="state" name="state_name" disabled
            onchange="updateSelections('state', this.value)" required>
            <option value="">Select State</option>
            <!-- Options will be populated dynamically -->
        </select>
    </div>
</div>

<div class="col-md-6">
    <div class="mb-3">
        <label for="district" class="form-label">District</label>
        <select class="form-control" id="district" name="district_name" disabled
            onchange="updateSelections('district', this.value)" required>
            <option value="">Select District</option>
            <!-- Options will be populated dynamically -->
        </select>
    </div>
</div>

<div class="col-md-6">
    <div class="mb-3">
        <label for="village" class="form-label">Village</label>
        <select class="form-control" id="village" name="village_name" disabled
            onchange="updateSelections('village', this.value)" required>
            <option value="">Select Village</option>
            <!-- Options will be populated dynamically -->
        </select>
    </div>
</div>

<div class="col-md-12">
    <div class="mb-3">
        <label for="document" class="form-label">Document <span class="text-danger">*</span></label>
        <div class="bootstrap-popover d-inline-block float-end ">
            @if ($isStatus)
            <button type="button" class="btn btn-primary btn-sm  " data-bs-container="body" data-bs-toggle="popover"
                data-bs-placement="top"
                data-bs-content="The document name gets filter on select of Document Type -> State -> District -> Village and the Document should be approved. "
                title="Document Name Mandatory"><i class="fas fa-info-circle"></i></button>
                @endif
        </div>
        <select class="form-control" id="document" name="document_id" disabled required>
            <option value="">Select Document</option>
            <!-- Options will be populated dynamically -->
        </select>
    </div>
</div>

{{-- here $isStatus is coming as a props, which means we need to check for the document status, the document has been approved or not else show all the documents  --}}
<script>
    const isStatus = @json($isStatus);

    function updateSelections(type, value) {
        console.log(isStatus, "is status");
        // const isStatus = @json($isStatus)
        // console.log($isStatus,"is status");
        // let isStatus = true;
        // Reset all child dropdowns when the top hierarchy is changed
        if (type === 'documentType') {
            resetDropdown('state');
            resetDropdown('district');
            resetDropdown('village');
            resetDropdown('document');
        } else if (type === 'state') {
            resetDropdown('district');
            resetDropdown('village');
            resetDropdown('document');
        } else if (type === 'district') {
            resetDropdown('village');
            resetDropdown('document');
        } else if (type === 'village') {
            resetDropdown('document');
        }
        let url = '';
        let target = '';
        let additionalData = {};
        console.log(additionalData);
        switch (type) {
            case 'documentType':
                url = `/api/fetch/states/${value}/${isStatus}`;
                target = 'state';
                break;
            case 'state':
                target = 'district';
                additionalData.doc_type_id = document.getElementById('documentType').value;
                url = `/api/fetch/districts/${value}/${isStatus}`;
                break;
            case 'district':
                target = 'village';
                additionalData = {
                    doc_type_id: document.getElementById('documentType').value,
                    state_name: document.getElementById('state').value,
                    district_name: document.getElementById('district').value,
                };
                url = `/api/fetch/villages/${value}/${isStatus}`;
                break;
            case 'village':
                target = 'document';
                additionalData = {
                    doc_type_id: document.getElementById('documentType').value,
                    state_name: document.getElementById('state').value,
                    district_name: document.getElementById('district').value,
                    village_name: document.getElementById('village').value
                };
                url = `/api/fetch/documents/${value}/${isStatus}`;
                break;
            default:
                console.error('Unhandled selection type:', type);
                return; // Early return if the type is not recognized
        }

        // Append additional data to the URL as query parameters if needed

        let queryString = '';
        if (Object.keys(additionalData).length > 0) {
            queryString = Object.keys(additionalData).map(key => {
                return `${encodeURIComponent(key)}=${encodeURIComponent(additionalData[key])}`;
            }).join('&');
        }

        if (queryString.length > 0) {
            url += `?${queryString}`; // Append the query string to the URL if it exists
        }

        // console.log('URL:', url); // Log the correct URL

        // Fetch call to the API
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data);
                populateDropdown(target, data);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }

    function resetDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        dropdown.innerHTML =
            `<option value="">Select ${dropdownId.charAt(0).toUpperCase() + dropdownId.slice(1)}</option>`;
        dropdown.disabled = true; // Disable dropdown
    }

    function populateDropdown(targetId, data) {
        const dropdown = document.getElementById(targetId);
        dropdown.innerHTML = `<option value="">Select ${targetId.charAt(0).toUpperCase() + targetId.slice(1)}</option>`;

        if (data.length === 0 && targetId === 'document') {
            alert('No approved documents available for the selected criteria.');
            return;
        }
        // console.log("targetid",targetId);
        data.forEach(item => {
            // console.log("item" , item)
            // If the item has a comma, split it into separate villages
            if (item && item.name) {
                if (item.name.includes(',')) {
                    let villages = item.name.split(',');
                    villages.forEach(village => {
                        dropdown.innerHTML +=
                            `<option value="${village.trim()}">${village.trim()}</option>`;
                    });
                } else if (targetId == "document") {
                    // console.log("here", item.document_id);
                    dropdown.innerHTML += `<option value="${item.document_id}">${item.name}</option>`;
                } else {
                    // console.log("here1", item.name);
                    dropdown.innerHTML +=
                        `<option value="${item.name}">${item.name} [${item.approved_documents}]</option>`;
                }
            }
        });

        dropdown.disabled = false; // Enable dropdown
    }

    // Call this function when the document is loaded or when a specific action occurs
    function initializeSelections() {
        // If a document type is already selected (e.g., when editing), you may want to initialize the dropdowns
        const selectedDocumentType = document.getElementById('documentType').value;
        if (selectedDocumentType) {
            updateSelections('documentType', selectedDocumentType);
        }
    }

    // Initialize the dropdowns when the page is loaded
    document.addEventListener('DOMContentLoaded', initializeSelections);
</script>

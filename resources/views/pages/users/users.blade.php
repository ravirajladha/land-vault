<x-app-layout>
    <x-header />
    <x-sidebar />
    <style>
        /* Style for bigger and darker checkbox */
        .form-check-input-custom {
            width: 20px;
            /* Adjust the width as needed */
            height: 20px;
            /* Adjust the height as needed */
            background-color: #f2eaea;
            /* Darker background color */
            border: 4px solid #050404;
            /* Darker border color */
            border-radius: 9px;
            /* Rounded corners */
            cursor: pointer;
            /* Show pointer cursor on hover */
        }

        /* Style for checkbox label */
        .checkbox-label {
            margin-left: 5px;
            /* Adjust margin as needed */
            color: #333;
            /* Text color */
        }
    </style>
    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-body">
                <div class="row page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="/users">Users</a></li>
                    </ol>
                </div>


                <div class="container-fluid">



                    <div class="row">
                        <div class="col-xl-12">

                            <div class="filter cm-content-box box-primary">
                                <div class="content-title SlideToolHeader">
                                    <h4>
                                        @if (isset($editUser))
                                            Update User
                                        @else
                                            Add User
                                        @endif
                                    </h4>
                                    <div class="tools">
                                        <a href="javascript:void(0);" class="expand handle"><i
                                                class="fal fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="cm-content-body  form excerpt">
                                    <div class="card-body">

                                        @if (isset($editUser))
                                            <form action="{{ route('users.update', $editUser->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                            @else
                                                <form action="{{ url('/register-user') }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                        @endif

                                        <div class="row">
                                            {{-- Username field --}}
                                            <div class="form-group mb-4 col-md-4 col-xl-4">
                                                <label class="form-label" for="username">Username</label>
                                                <input type="text" class="form-control" placeholder="Enter username"
                                                    id="username" name="name"
                                                    value="{{ old('name', isset($editUser) ? $editUser->name : '') }}"
                                                    required autofocus autocomplete="name">
                                                @error('name')
                                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Email field --}}
                                            <div class="form-group mb-4 col-md-4 col-xl-4">
                                                <label class="form-label" for="email">Email</label>
                                                <input type="email" name="email" class="form-control"
                                                    placeholder="hello@example.com" id="email"
                                                    value="{{ old('email', isset($editUser) ? $editUser->email : '') }}"
                                                    required autocomplete="username">
                                                @error('email')
                                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Phone field --}}
                                            <div class="form-group mb-4 col-md-4 col-xl-4">
                                                <label class="form-label" for="usersPhone">Phone</label>
                                                <input type="text" class="form-control" id="usersPhone"
                                                    name="phone" placeholder="Enter Receiver's Phone Number"
                                                    pattern="\d{0,10}"
                                                    title="Please enter a valid phone number with up to 10 digits."
                                                    value="{{ old('phone', isset($editUser) ? $editUser->phone : '') }}"
                                                    maxlength="10">

                                                @error('phone')
                                                    <div class="alert alert-danger mt-2 ">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-md-12 col-xl-12">
                                                <label class="form-label">Status</label>
                                                <select class="form-control" id="area-unit-dropdown" name="status">
                                                    <option value="">Select Status</option>
                                                    <option value="1"
                                                        {{ isset($editUser) && $editUser->status == '1' ? 'selected' : '' }}>
                                                        Active</option>
                                                    <option value="0"
                                                        {{ isset($editUser) && $editUser->status == '0' ? 'selected' : '' }}>
                                                        Inactive</option>
                                                </select>
                                                @error('status')
                                                    <div class="alert alert-danger mt-2 ">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Password fields --}}
                                            {{-- Note: Password fields should not be pre-populated for security reasons --}}
                                            <div class="mb-sm-4 mb-3 position-relative col-md-12 col-xl-12">
                                                <label class="form-label" for="dlab-password">Password</label>
                                                <div class="bootstrap-popover d-inline-block float-end mb-2">
                                                    <button type="button" class="btn btn-primary btn-sm px-4 "
                                                        data-bs-container="body" data-bs-toggle="popover"
                                                        data-bs-placement="top"
                                                        data-bs-content="The password must be at least 8 characters long, contain at least 1 uppercase letter, 1 lowercase letter, and 1 numeric digit."
                                                        title="Password Guidelines"><i
                                                            class="fas fa-info-circle"></i></button>
                                                </div>

                                                <input type="password" name="password"
                                                    @if (!isset($editUser)) required @endif
                                                    autocomplete="new-password" id="dlab-password" class="form-control">
                                                {{-- <span class="show-pass eye">
                                                    <i class="fa fa-eye-slash"></i>
                                                    <i class="fa fa-eye"></i>
                                                </span> --}}
                                                @error('password')
                                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-sm-4 mb-3 position-relative col-md-12 col-xl-12">
                                                <label class="form-label" for="dlab-password1">Confirm
                                                    Password</label>
                                                <input type="password" name="password_confirmation"
                                                    @if (!isset($editUser)) required @endif
                                                    autocomplete="new-password" id="dlab-password1"
                                                    class="form-control">
                                                <span class="show-pass1 eye">
                                                    <i class="fa fa-eye-slash"></i>
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                                @error('password_confirmation')
                                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            {{-- <div class="mb-sm-4 mb-3 position-relative">
                                                    <div class="mb-4">
                                                        <h4 class="card-title">Permissions</h4>
                                                        <p>The selected page and operations will be allowed for the
                                                            corresponding user.</p>
                                                    </div>
                                                    <select class="select2-width-75" multiple="multiple"
                                                        style="width:100%;" name="permissions[]">
                                                        <option value="" disabled selected>Select Document Type
                                                        </option>
                                                    
                                                        @foreach ($permissions as $permission)
                                                            <option value="{{ $permission->id }}">
                                                                {{ $permission->display_name }} -
                                                                {{ ['1' => 'Add', '2' => 'Read', '3' => 'Update'][$permission->action] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error :messages="$errors->get('permission')" class="mt-2" />
                                                </div> --}}
                                        </div>


                                        {{-- {{ dd($editUser) }} --}}

                                        @endif




                                        <div class="mb-3">
                                            {{-- <label for="receiverType" class="form-label">Permissions & Role</label> --}}
                                            <label class="form-label" for="password_confirmation">Permissions &
                                                Role</label>

                                            <div class="table-responsive">
                                                <table class="table  table-responsive-sm">
                                                    <tbody style="padding:0 0 0 0;">
                                                        @php
                                                            $userPermissionsDisplayNames = isset($editUser)
                                                                ? $editUser->permissions
                                                                    ->pluck('display_name')
                                                                    ->toArray()
                                                                : [];
                                                        @endphp

                                                        {{-- Ensure the function is declared once outside of any conditional blocks --}}
                                                        @if (!function_exists('generatePermissionCheckbox'))
                                                            @php
                                                                function generatePermissionCheckbox(
                                                                    $permissionsDisplayNames,
                                                                    $permissionDisplayName,
                                                                ) {
                                                                    $isChecked = in_array(
                                                                        $permissionDisplayName,
                                                                        $permissionsDisplayNames,
                                                                    )
                                                                        ? 'checked'
                                                                        : '';
                                                                    echo '<input type="checkbox" class="form-check-input form-check-input-custom" name="permissions[' .
                                                                        $permissionDisplayName .
                                                                        ']" value="' .
                                                                        $permissionDisplayName .
                                                                        '" ' .
                                                                        $isChecked .
                                                                        '>';
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <th>Module</th>
                                                                <th>Create</th>
                                                                <th>Read</th>
                                                                <th>Update</th>
                                                                <th>Update Status</th>
                                                           
                                                            </tr>
                                                            <tr>

                                                                {{-- {{ $permissions }} --}}
                                                            <tr>
                                                                <td class="module-name">Document</td>
                                                                <td>


                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Add Basic Document Form') @endphp


                                                                </td>
                                                                <td>
                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Main Document View') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Update Basic Document Detail') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Update Document Status') @endphp

                                                                </td>
                                                           
                                                            </tr>

                                                            <tr>
                                                                <td class="module-name">Document Type</td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Add Document Types') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Document Types') @endphp

                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                        
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Document Field</td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Add Document Fields') @endphp
                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Document Fields') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Update Document Fields') @endphp

                                                                </td>
                                                                <td></td>
                                                           
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Bulk Upload</td>
                                                                <td></td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Bulk Upload') @endphp

                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                           
                                                            
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Sold Land</td>
                                                                <td>@php generatePermissionCheckbox($userPermissionsDisplayNames, 'Add Sold Land') @endphp</td>
                                                                <td>
                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Sold Land') @endphp
                                                                </td>
                                                                <td> @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Update Sold Land') @endphp</td>
                                                                <td></td>
                                                              
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Sets</td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Add Sets') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Sets') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Update Sets') @endphp

                                                                </td>
                                                                <td></td>
                                                               
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Receivers</td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Add Receivers') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Receivers') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Update Receivers') @endphp

                                                                </td>
                                                                <td></td>
                                                              
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td class="module-name">Assign Document to Receivers</td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Assign Document') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Assigned Documents') @endphp

                                                                </td>
                                                                <td></td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Update Document Assignment Status') @endphp

                                                                </td>
                                                               
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Advocates</td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Add Advocates') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Advocates') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Update Advocates') @endphp

                                                                </td>
                                                                <td></td>
                                                               
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Assign Docs to Advocates</td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Add Assigned Docs to Advocate') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Assigned Docs to Advocate') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Update Assigned Docs to Advocate') @endphp

                                                                </td>
                                                                <td></td>
                                                               
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Compliances</td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Add Compliances') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Compliances') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Update Compliance Recurring Status') @endphp

                                                                </td>

                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Update Compliances Status') @endphp

                                                                </td>
                                                             
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Document Logs</td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Add Document Logs') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Document Logs') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Update Document Logs') @endphp

                                                                </td>

                                                                <td>
                                                                </td>
                                                               
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Configure</td>
                                                                <td></td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Configure') @endphp

                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                      
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Profile</td>
                                                                <td></td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Profile') @endphp

                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Update Profile') @endphp

                                                                </td>
                                                                <td></td>
                                                        
                                                            </tr>
                                                           
                                                            <tr>
                                                                <td class="module-name">Filter Doc</td>
                                                                <td></td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Filter Document') @endphp

                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                              
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">View Documents By Document Type
                                                                </td>
                                                                <td></td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Documents by Document Type') @endphp

                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                           
                                                            </tr>

                                                            <tr>
                                                                <td class="module-name">View Documents from Sets</td>
                                                                <td></td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Documents from Sets') @endphp
                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                       
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">View Uploaded PDF's</td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Add PDF') @endphp
                                                                </td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Uploaded PDF') @endphp
                                                                </td>
                                                                <td>

                                                                    {{-- @php generatePermissionCheckbox($userPermissionsDisplayNames, 'Delete PDF') @endphp --}}
                                                                </td>
                                                                <td></td>
                                                          
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">View Compliances Notification
                                                                </td>
                                                                <td></td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Compliance Notifications') @endphp
                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                               
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">View Receipients Notification
                                                                </td>
                                                                <td></td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Recipient Notifications') @endphp
                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                              
                                                            </tr>
                                                            <tr>
                                                                <td class="module-name">Reports
                                                                </td>
                                                                <td></td>
                                                                <td>

                                                                    @php generatePermissionCheckbox($userPermissionsDisplayNames, 'View Report') @endphp
                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                              
                                                            </tr>

                                                            </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>



                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="" class="btn-link"></a>
                                    <div class="text-end"><button class="btn btn-success" type="submit"><i
                                                class="fas fa-check"></i>&nbsp;Submit</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>




                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Users Detail</h4>
                                    {{-- <button type="button" class="btn btn-success mb-2 float-end"
                                        data-bs-toggle="modal" data-bs-target="#exampleModalCenter1"><i
                                            class="fas fa-plus-square"></i>&nbsp;Add
                                        User</button> --}}


                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        {{-- <h4>Receivers</h4> --}}


                                        {{-- <div class="table-responsive"> --}}
                                        {{-- <table id="example3" class="display" style="min-width: 845px"> --}}
                                        <table id="example3" class="display">

                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl. No.</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Phone</th>

                                                    <th scope="col">Email Id</th>

                                                    <th scope="col">Status</th>
                                                    <th scope="col">Action</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $index => $item)
                                                    <tr>
                                                        <th scope="row">{{ $index + 1 }}</th>
                                                        <td>{{ $item->name }}</td>
                                                        <td>{{ $item->phone }}</td>
                                                        <td>{{ $item->email }}</td>


                                                        <td>
                                                            @php
                                                                $statusClasses = [
                                                                    '1' => 'badge-success text-success',
                                                                    '0' => 'badge-warning text-warning',
                                                                ];
                                                                $statusTexts = [
                                                                    '0' => 'Inactive',
                                                                    '1' => 'Active',
                                                                ];
                                                                $statusId = strval($item->status); // Convert to string to match array keys
                                                                $statusClass =
                                                                    $statusClasses[$statusId] ??
                                                                    'badge-secondary text-secondary'; // Default class if key doesn't exist
$statusText = $statusTexts[$statusId] ?? 'Unknown'; // Default text if key doesn't exist
                                                            @endphp

                                                            <span class="badge light {{ $statusClass }}">
                                                                <i class="fa fa-circle {{ $statusClass }} me-1"></i>
                                                                {{ $statusText }}
                                                            </span>

                                                        </td>


                                                        <!-- Assuming you have a relation to get the receiver type name -->
                                                        <td>
                                                            <a
                                                                href="{{ route('users.show_reviewed_documents_users', $item->id) }}">
                                                                <button class="btn btn-secondary btn-sm edit-btn">
                                                                    View</button></a>
                                                            <a href="{{ route('users.edit', $item->id) }}">
                                                                <button class="btn btn-primary btn-sm edit-btn">
                                                                    <i
                                                                        class="fas fa-pencil-square"></i>&nbsp;</button></a>
                                                        </td>


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
    <style>
        .table tbody tr td:last-child,
        .table thead tr th:last-child {
            text-align: left;
        }

        th {
            font-weight: bold;
            /* Makes header text bolder */
            color: #333;
            /* Darker color for header text */
        }

        .module-name {
            font-weight: bold;
            /* Makes module name text bolder */
            color: #333;
            /* Darker color for module name */
        }

        .fa-eye {
            display: none;
        }
    </style>

    {{-- add receiver modal form starts --}}

    {{-- <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">Large modal</button>
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg"> --}}



    </div>
    </div>
    </div>

    {{-- edit receiver modal starts --}}
    {{-- assign document to individual receiver starts --}}






    @include('layouts.footer')


</x-app-layout>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}


{{-- template for crud operation for each users --}}

{{-- @php
$modules = ['Document', 'Bulk Upload', 'Document Field', 'Document Type','Sets', 'Bulk Upload', 'Receivers', 'Users','Compliances','Receiver Type'];
@endphp
      <div class="mb-3">
          <label for="receiverType" class="form-label">Permissions & Role</label>
              <div class="table-responsive">
                  <table class="table  table-responsive-sm">
                      <tbody style="padding:0 0 0 0;">
                  <tr>
                      <th>Module</th>
                      <th>Create</th>
                      <th>Read</th>
                      <th>Update</th>
                      <th>Delete</th>
                  </tr>
                  <tr>

                      @foreach ($modules as $module)
                      <tr>
                          <td class="module-name">{{ $module }}</td>
                          <td><input type="checkbox" class="form-check-input" name="create"></td>
                          <td><input type="checkbox" class="form-check-input" name="read"></td>
                          <td><input type="checkbox" class="form-check-input" name="update"></td>
                          <td><input type="checkbox" class="form-check-input" name="delete"></td>
                      </tr>
                  @endforeach
                  </tr>
                 
              </tbody>
              </table>
          </div>
      </div>
       --}}

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>  --}}
<script>
    $(document).ready(function() {
        // Toggle visibility for the first password field
        $('#dlab-password + .show-pass').on('click', function() {
            togglePasswordVisibility('#dlab-password', $(this));
        });

        // Toggle visibility for the confirm password field
        $('#dlab-password1 + .show-pass1').on('click', function() {
            togglePasswordVisibility('#dlab-password1', $(this));
        });

        function togglePasswordVisibility(inputSelector, toggleSpan) {
            let input = $(inputSelector);
            let eyeSlash = toggleSpan.find('.fa-eye-slash');
            let eye = toggleSpan.find('.fa-eye');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                eyeSlash.hide();
                eye.show();
            } else {
                input.attr('type', 'password');
                eye.show();
                eyeSlash.hide();
            }
        }
    });
</script>

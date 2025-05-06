        <!--**********************************
            Sidebar start
        ***********************************-->
        @php
            $user = Auth::user();
        @endphp


        <div class="dlabnav">
            <div class="dlabnav-scroll">
                <ul class="metismenu" id="menu">
                    <li><a href="/dashboard" aria-expanded="false">
                            <i class="fas fa-home"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>


                    </li>
                    {{-- @if (Auth::user()->type == 'admin') --}}
                    @if (
                        $user &&
                            ($user->hasPermission('Main Document View') ||
                                $user->hasPermission('Add Basic Document Form') ||
                                $user->hasPermission('View Assigned Documents') ||
                                $user->hasPermission('View Bulk Upload') ||
                                $user->hasPermission('View Profile') ||
                                $user->hasPermission('View Document Logs') ||
                                $user->hasPermission('View Uploaded PDF') ||
                                $user->hasPermission('View Document Types ')))
                        <li>
                            <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                                <i class="fas fa-clone"></i>
                                <span class="nav-text">Document </span>
                            </a>
                            <ul aria-expanded="false">
                                @if ($user && $user->hasPermission('Main Document View'))
                                    <li><a href="{{ url('/') }}/filter-document">View Documents</a></li>
                                @endif
                                @if ($user && $user->hasPermission('Add Basic Document Form'))
                                    <li><a href="{{ url('/') }}/add_document_first">Add Document</a></li>
                                @endif
                                @if ($user && $user->hasPermission('View Assigned Documents'))
                                    <li><a href="{{ url('/') }}/assign-documents">Assign Document</a></li>
                                @endif
                                @if ($user && $user->hasPermission('View Bulk Upload'))
                                    <li><a href="{{ url('/') }}/bulk-upload-master-data">Bulk Upload</a></li>
                                @endif
                                @if ($user && $user->hasPermission('View Document Types'))
                                    <li><a href="{{ url('/') }}/document_type">Document Type</a></li>
                                @endif


                                @if ($user && $user->hasPermission('View Document Logs'))
                                    <li><a href="{{ url('/') }}/document-transactions">Document Logs</a></li>
                                @endif
                                @if ($user && $user->hasPermission('View Uploaded PDF'))
                                    <li><a href="{{ url('/') }}/view-uploaded-documents">Uploaded PDF's</a></li>
                                @endif


                            </ul>
                        </li>
                    @endif

                    @if ($user && $user->hasPermission('View Sold Land'))
                        <li><a href="{{ url('/') }}/sold-land" aria-expanded="false">
                                <i class="fas fa-landmark"></i>
                                <span class="nav-text">Sold Lands</span>
                            </a>
                        </li>
                    @endif
                    @if ($user && $user->hasPermission('View Sets'))
                        <li><a href="{{ url('/') }}/set" aria-expanded="false">
                                <i class="fas fa-info-circle"></i>
                                <span class="nav-text">Sets</span>
                            </a>
                        </li>
                    @endif
                    @if ($user && $user->hasPermission('View Receivers'))
                        <li><a href="{{ url('/') }}/receivers" aria-expanded="false">
                                {{-- <i class="fas fa-user-circle"></i> --}}
                                <i class="fas fa-receipt"></i>
                                <span class="nav-text">Receivers</span>
                            </a>
                        </li>
                    @endif
                    @if ($user && $user->hasPermission('View Advocates'))
                        <li><a href="{{ url('/') }}/advocates" aria-expanded="false">
                                {{-- <i class="fas fa-user-circle"></i> --}}
                                <i class="fas fa-journal-whills"></i>
                                <span class="nav-text">Advocates</span>
                            </a>
                        </li>
                    @endif

                    @if ($user && $user->hasPermission('View Compliances'))
                        <li><a href="{{ url('/') }}/compliances" aria-expanded="false">
                                <i class="fas fa-procedures"></i>
                                <span class="nav-text">Compliances</span>
                            </a>
                        </li>
                    @endif
                    @if ($user && $user->hasPermission('View Users'))
                        <li><a href="{{ url('/') }}/users" aria-expanded="false" disabled>
                                <i class="fas fa-user-circle"></i>
                                <span class="nav-text">Users</span>
                            </a>
                        </li>
                    @endif
                 
                    @if ($user && $user->hasPermission('Configure'))
                        <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                                <i class="fas fa-table"></i>
                                <span class="nav-text">Configure</span>
                            </a>

                            <ul aria-expanded="false">
                                <li><a href="{{ url('/') }}/receiver-type">Receiver Type</a></li>
                                <li><a href="{{ url('/') }}/categories">Category</a></li>
                                <li><a href="{{ url('/') }}/subcategories">Subcategory</a></li>
                            </ul>
                        </li>
                    @endif
{{--only filters required  --}}
{{-- <li><a href="{{ url('/') }}/categories">Receivers</a></li>
<li><a href="{{ url('/') }}/subcategories">Advocates</a></li> --}}


                 {{-- report generation --}}

                 @if ($user && $user->hasPermission('View Report'))
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-table"></i>
                            <span class="nav-text">Report Generation</span>
                        </a>

                        <ul aria-expanded="false">
                            {{-- <li><a href="{{ url('/child-document-reports') }}">Documents (with child data) </a></li> --}}
                            <li><a href="{{ url('/') }}/documents-assigned-to-receivers" > Assigned Documents to Receivers </a></li>
                            <li><a href="{{ url('/') }}/documents-assigned-to-advocates" >Assigned Documents to Advocates </a></li>
                            {{-- <li><a href="{{ url('/') }}/subcategories">Users Work Progress <span class="badge bg-warning text-dark">In Progress</span></a></li> --}}
                        </ul>
                    </li>
                    @endif
{{-- compliances quartly work is pending --}}


                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-tools"></i>
                            <span class="nav-text">Settings</span>
                        </a>
                        <ul aria-expanded="false">
                            @if ($user && $user->hasPermission('View Profile'))
                                <li><a href="{{ url('/') }}/profile">Profile</a></li>
                            @endif
                            @if ($user && ($user->hasPermission('Http Request Logs') || $user->hasPermission('Action Logs')))
                                <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Logs</a>
                                    <ul aria-expanded="false">
                                        <li><a href="/action-logs">Action Logs</a></li>
                                        <li><a href="/http-request-logs">Http Request Logs</a></li>

                                    </ul>
                                </li>
                            @endif
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                            <li><a href="javascript:void()" aria-expanded="false"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    aria-expanded="false">

                                    {{-- <i class="fas fa-sign-out"></i> --}}
                                    <span class="nav-text">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </li> 

                </ul>
                <style>
                    .sidebar-container {
                        display: flex;
                        flex-direction: column;
                        height: 10vh;
                        /* Adjust the height as needed */
                        overflow-x: hidden;
                        /* Hide horizontal scrollbar */
                    }

                    .sidebar-footer {
                        /* margin-top: auto; */
                    }

                    .sidebar-content {
                        overflow-y: auto;
                    }
                </style>
                <div class="sidebar-container d-flex flex-column sidebar-footer progress-info">
                    <div class="sidebar-content overflow-auto sidebar-footer">
                        <div class="side-bar-profile sidebar-footer">

                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="side-bar-profile-img">
                                    <img src="/assets/images/avatar/avatar.jpg" alt="">
                                </div>
                                <div class="profile-info1">
                                    <h5>{{ Auth::user()->name }}</h5>
                                    <span>{{ Auth::user()->email }}</span>
                                </div>
                                <div class="profile-button">
                                    <i class="fas fa-caret-downd scale5 text-light"></i>
                                </div>
                            </div>
                            {{-- <div class="d-flex justify-content-between mb-2 progress-info">
						<span class="fs-12"><i class="fas fa-star text-orange me-2"></i>Task Progress</span>
						<span class="fs-12">20/45</span>
					</div>
					<div class="progress default-progress">
						<div class="progress-bar bg-gradientf progress-animated" style="width: 45%; height:8px;" role="progressbar">
							<span class="sr-only">45% Complete</span>
						</div>
					</div> --}}
                        </div>
                    </div>
                </div>
                <div class="sidebar-footer mt-auto">

                    <div class="copyright">
                        {{-- <p>Kods © 2023 All Rights Reserved</p> --}}
                        <a href="https://kodstech.com/" target="_blank">
                            <p class="fs-12">Powered by Kods</p>
                        </a>
                    </div>
                </div>


            </div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->

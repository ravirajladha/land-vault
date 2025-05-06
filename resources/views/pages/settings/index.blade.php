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
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0)">Application Settings</a>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Application Settings</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="basic-form">

                                           


                                                <form action="{{ route('project-settings.update') }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="row">
                                                    <!-- Project Name -->
                                                    <div class="mb-3 col-md-12">
                                                        <label for="project_name" class="form-label">Project
                                                            Name:</label>
                                                        <input type="text" class="form-select form-control"
                                                            name="project_name" value="{{ $data->project_name }}">
                                                    </div>
                                                    <!-- Logo -->
                                                    
                                                <div class="mb-3 col-md-6">
                                                    <label for="favicon" class="form-label">Favicon:</label>
                                                    <input type="file" class="form-select form-control"
                                                        name="favicon">
                                                    @if ($data->favicon)
                                                        <img src="{{ $data->favicon }}" alt="Favicon" class="mt-4"
                                                            style="max-width: 100px; max-height: 100px; ">
                                                    @endif
                                                </div>


                                                <!-- Logo -->
                                                <div class="mb-3 col-md-6">
                                                    <label for="logo" class="form-label">Logo:</label>
                                                    <input type="file" class="form-select form-control"
                                                        name="logo">
                                                    @if ($data->logo)
                                                        <img src="{{ $data->logo }}" alt="Logo" class="mt-4"
                                                            style="max-width: 100px; max-height: 100px;">
                                                    @endif
                                                </div>
                                                <button type="submit" class="btn btn-primary float-right">Update
                                                    Settings</button>


                                                    
                                                    
                                                    
                                                    
                                                    
                                                </div>
                                            </form>




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

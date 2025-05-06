<x-app-layout>


    <x-header />
    <x-sidebar/>

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">

            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-body">
                        <div class="row page-titles">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0)">Basic Fields Detail</a>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Basic Document Form</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="basic-form">

                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Select Type *</label>
                                                    <select class="form-select form-control"
                                                        aria-label="Default select example" name="type" required>
                                                        <option selected disabled>select</option>
                                                        @foreach ($doc_type as $item)
                                                            <option value="{{ $item->id }}|{{ $item->name }}">
                                                                {{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>




                                            </div>


                                            <button type="submit" class="btn btn-primary">Next</button>

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

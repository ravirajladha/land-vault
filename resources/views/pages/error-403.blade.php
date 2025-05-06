<x-app-layout>


    <x-header />
    <x-sidebar/>
<style>
    .centered-container {
    /* min-height: 100vh; */
    display: flex;
    align-items: center;
    justify-content: center;
}

</style>
    <div class="content-body default-height">
        <div class="container-fluid">
            <div class="page-body">
                <div class="fix-wrapper" style="min-height:70vh;">
                    <div class="container-fluid">
                        <div class="row justify-content-center align-items-center" >
                            <div class="col-md-6">
                                <div class="form-input-content text-center error-page">
                                    <h1 class="error-text font-weight-bold">403</h1>
                                    <h4><i class="fa fa-times-circle text-danger"></i> Forbidden Error!</h4>
                                    <p>You do not have permission to view this resource.</p>
                                    <div>
                                        <a class="btn btn-primary" href="/">Back to Home</a>
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

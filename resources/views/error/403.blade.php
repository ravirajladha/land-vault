<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- PAGE TITLE HERE -->
    <title>LandVault</title>


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Kods">
    <meta name="robots" content="index, follow">

    <meta name="keywords"
        content="	admin, admin dashboard, admin template, analytics, bootstrap, bootstrap5, bootstrap 5 admin template, modern, responsive admin dashboard, sales dashboard, sass, ui kit, web app, Fillow SaaS, User Interface (UI), User Experience (UX), Dashboard Design, SaaS Application, Web Application, Data Visualization, Analytics, Customization, Responsive Design, Bootstrap Framework, Charts and Graphs, Data Management, Reporting, Dark Mode, Mobile-Friendly, Dashboard Components, Integrations, Analytics Dashboard, API Integration, User Authentication">


    <meta name="description" content="Digitization Document Software">

    <meta property="og:title" content="Kods">
    <meta property="og:description" content="Digitization Document Software">
    <meta property="og:image" content="https://kodstech.com/">
    <meta name="format-detection" content="telephone=no">

    <meta name="twitter:title" content="Kods">
    <meta name="twitter:description" content="Digitization Document Software">
    <meta name="twitter:image" content="https://kodstech.com/">
    <meta name="twitter:card" content="summary_large_image">

    <!-- MOBILE SPECIFIC -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="/assets/logo/logo.jpg">
    <link href="/assets/vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">

</head>

<body>
    <div class="fix-wrapper">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-6">
                    <div class="form-input-content text-center error-page">
                        <h1 class="error-text  font-weight-bold">403</h1>
                        <h4><i class="fa fa-times-circle text-danger"></i> Forbidden Error!</h4>
                        <p>You do not have permission to view this resource.</p>
                        @if (session('code'))
                            <h1>Error {{ session('code') }}</h1>
                        @endif

                        @if (session('error'))
                            <p>{{ session('error') }}</p>
                        @endif
                        <div>
                            <a class="btn btn-primary" href="/">Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--**********************************
 Scripts
***********************************-->
    <!-- Required vendors -->
    <script src="/assets/vendor/global/global.min.js"></script>
    <script src="/assets/vendor/bootstrap-select/js/bootstrap-select.min.js"></script>
    <script src="/assets/js/custom.min.js"></script>
    <script src="/assets/js/dlabnav-init.js"></script>


</body>

</html>

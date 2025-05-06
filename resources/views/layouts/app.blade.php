<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- PAGE TITLE HERE -->


    <title>
        <x-page-title /> - LandVault
    </title>
    <script src="../../js/app.js"></script>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Kods">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="keywords" content="Documents management software">


    <meta name="description" content="Digitization Document Software">

    <meta property="og:title" content="Kods">
    <meta property="og:description" content="Documents management software.">
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


    <link rel="stylesheet" href="/assets/vendor/toastr/css/toastr.min.css">
    <link rel="stylesheet" href="/assets/vendor/select2/css/select2.min.css">

    <link href="/assets/vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">

    <link href="/assets/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="/assets/vendor/datatables/responsive/responsive.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/vendor/nouislider/nouislider.min.css">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body class="">
    <div class="">
        <div id="preloader">
            <div class="lds-ripple">
                <div></div>
                <div></div>
            </div>
        </div>
        <div id="main-wrapper">
            <main>
                {{-- {{ dd($pageTitle) }} --}}
                {{ $slot }}
            </main>
        </div>
    </div>

    <script src="/assets/vendor/global/global.min.js"></script>
    <script src="/assets/vendor/bootstrap-select/js/bootstrap-select.min.js"></script>

    <!-- counter -->
    <script src="/assets/vendor/counter/counter.min.js"></script>
    <script src="/assets/vendor/counter/waypoint.min.js"></script>
    <!-- Apex Chart -->
    <script src="/assets/vendor/apexchart/apexchart.js"></script>
    <script src="/assets/vendor/chart-js/chart.bundle.min.js"></script>
    <!-- Chart piety plugin files -->
    <script src="/assets/vendor/peity/jquery.peity.min.js"></script>
    <!-- Dashboard 1 -->
    <script src="/assets/js/dashboard/dashboard-1.js"></script>
    <script src="/assets/vendor/owl-carousel/owl.carousel.js"></script>
    <script src="/assets/js/custom.min.js"></script>
    <script src="/assets/js/dlabnav-init.js"></script>

    <script src="/assets/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="/assets/vendor/datatables/responsive/responsive.js"></script>
    <script src="/assets/js/plugins-init/datatables.init.js"></script>

    <!-- Datatable scripts footer-->
    {{-- toaster start --}}
    <script src="/assets/vendor/toastr/js/toastr.min.js"></script>
    <!-- All init script -->
    <script src="/assets/js/plugins-init/toastr-init.js"></script>
    <script src="/assets/vendor/select2/js/select2.full.min.js"></script>
    <script src="/assets/js/plugins-init/select2-init.js"></script>

    <script>
        // Check for session messages
        @if (session('toastr'))
            let toastrOptions = {
                positionClass: "toast-top-right",
                timeOut: 5000,
                closeButton: true,
                debug: false,
                newestOnTop: true,
                progressBar: true,
                preventDuplicates: true,
                onclick: null,
                showDuration: "300",
                hideDuration: "1000",
                extendedTimeOut: "1000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut",
                tapToDismiss: false
            };

            let toastrType = "{{ session('toastr.type') }}";
            let toastrMessage = "{{ session('toastr.message') }}";

            if (toastrType && toastrMessage) {
                toastr[toastrType](toastrMessage, " ", toastrOptions);
            }
        @endif
    </script>

</body>

</html>

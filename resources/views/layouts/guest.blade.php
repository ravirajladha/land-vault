{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

       
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

     
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
 --}}


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <!-- PAGE TITLE HERE -->
  <title>LandVault</title>
    
    
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="author" content="Kods">
  <meta name="robots" content="index, follow">

  <meta name="keywords" content="admin, admin dashboard, admin template, analytics, bootstrap, bootstrap5, bootstrap 5 admin template, modern, responsive admin dashboard, sales dashboard, sass, ui kit, web app, Fillow SaaS, User Interface (UI), User Experience (UX), Dashboard Design, SaaS Application, Web Application, Data Visualization, Analytics, Customization, Responsive Design, Bootstrap Framework, Charts and Graphs, Data Management, Reporting, Dark Mode, Mobile-Friendly, Dashboard Components, Integrations, Analytics Dashboard, API Integration, User Authentication">


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
  <link href="/assets/vendor/owl-carousel/owl.carousel.css" rel="stylesheet">
  <link rel="stylesheet" href="/assets/vendor/nouislider/nouislider.min.css">
  
  <!-- Style css -->
  <link href="/assets/css/style.css" rel="stylesheet">
  {{-- <link rel="stylesheet" href="/assets/vendor/toastr/css/toastr.min.css"> --}}
  
</head>

<body>
  <div class="fix-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-6">
                    <div class="card mb-0 h-auto">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <a href="/"><img class="logo-auth" style="width: 15rem;" src="/assets/logo/logo.jpg" alt=""></a>
                            </div>
                            {{ $slot }}
                            {{-- <h4 class="text-center mb-4">Sign in your account</h4>
                            <form action="index.html">
                                <div class="form-group mb-4">
                                    <label class="form-label" for="username">Username</label>
                                    <input type="text" class="form-control" placeholder="Enter username" id="username">
                                </div>
                                <div class="mb-sm-4 mb-3 position-relative">
                                    <label class="form-label" for="dlab-password">Password</label>
                                    <input type="password" id="dlab-password" class="form-control" value="123456">
                                    <span class="show-pass eye">
                                        <i class="fa fa-eye-slash"></i>
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                                <div class="form-row d-flex flex-wrap justify-content-between mb-2">
                                    <div class="form-group mb-sm-4 mb-1">
                                        <div class="form-check custom-checkbox ms-1">
                                            <input type="checkbox" class="form-check-input" id="basic_checkbox_1">
                                            <label class="form-check-label" for="basic_checkbox_1">Remember my preference</label>
                                        </div>
                                    </div>
                                    <div class="form-group ms-2">
                                        <a href="page-forgot-password.html">Forgot Password?</a>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                                </div>
                            </form>
                            <div class="new-account mt-3">
                                <p>Don't have an account? <a class="text-primary" href="page-register.html">Sign up</a></p>
                            </div> --}}
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
    {{-- <script src="/assets/vendor/toastr/js/toastr.min.js"></script>
    <script src="/assets/js/plugins-init/toastr-init.js"></script> --}}

	
</body>
</html>

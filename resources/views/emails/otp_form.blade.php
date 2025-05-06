<!DOCTYPE html>
<html lang="en">

<head>
 <!-- PAGE TITLE HERE -->
	<title>Management And Administration Website Templates | Fillow : Fillow Saas Admin Bootstrap 5 Template - Empowering Your Administration Work  | Dexignlabs</title>


	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Dexignlabs">
	<meta name="robots" content="index, follow">

	<meta name="keywords" content="	admin, admin dashboard, admin template, analytics, bootstrap, bootstrap5, bootstrap 5 admin template, modern, responsive admin dashboard, sales dashboard, sass, ui kit, web app, Fillow SaaS, User Interface (UI), User Experience (UX), Dashboard Design, SaaS Application, Web Application, Data Visualization, Analytics, Customization, Responsive Design, Bootstrap Framework, Charts and Graphs, Data Management, Reporting, Dark Mode, Mobile-Friendly, Dashboard Components, Integrations, Analytics Dashboard, API Integration, User Authentication">


	<meta name="description" content="Elevate your administrative efficiency and enhance productivity with the Fillow SaaS Admin Dashboard Template. Designed to streamline your tasks, this powerful tool provides a user-friendly interface, robust features, and customizable options, making it the ideal choice for managing your data and operations with ease.">

	<meta property="og:title" content="Fillow : Fillow Saas Admin Bootstrap 5 Template | Dexignlabs">
	<meta property="og:description" content="Elevate your administrative efficiency and enhance productivity with the Fillow SaaS Admin Dashboard Template. Designed to streamline your tasks, this powerful tool provides a user-friendly interface, robust features, and customizable options, making it the ideal choice for managing your data and operations with ease.">
	<meta property="og:image" content="https://fillow.dexignlab.com/xhtml/social-image.png">
	<meta name="format-detection" content="telephone=no">

	<meta name="twitter:title" content="Fillow : Fillow Saas Admin Bootstrap 5 Template | Dexignlabs">
	<meta name="twitter:description" content="Elevate your administrative efficiency and enhance productivity with the Fillow SaaS Admin Dashboard Template. Designed to streamline your tasks, this powerful tool provides a user-friendly interface, robust features, and customizable options, making it the ideal choice for managing your data and operations with ease.">
	<meta name="twitter:image" content="https://fillow.dexignlab.com/xhtml/social-image.png">
	<meta name="twitter:card" content="summary_large_image">

	<!-- MOBILE SPECIFIC -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="images/favicon.png">
    <link href="/assets/vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">

</head>

<body>
	 <div class="fix-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-6">
                    <div class="card mb-0 h-auto">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <a href="index.html"><img class="logo-auth" src="https://ahobila.kods.app/assets/logo/logo.jpg" alt="" style="width:100px;"></a>
                            </div>
                            <h4 class="text-center mb-4">OTP Verification</h4>
                            <p>Hello,  {{ $receiverName }}</p>
                            <p>We have sent a verification code
                              to your Email Id attached with this page URL </p>
                              <form method="POST" action="{{ route('otp.verify', ['token' => $token]) }}">
                                @csrf
                                <div class="mb-sm-4 mb-3 position-relative">
                                    <label class="form-label" for="dlab-password">Password</label>
                                    <input type="password" id="dlab-password" class="form-control" type="text" name="otp" pattern="\d{4}" maxlength="4" name="otp"  placeholder="Enter 4 digit otp"  >
                                    <span class="show-pass eye">
                                        <i class="fa fa-eye-slash"></i>
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-block">Unlock</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- #/ container -->
    <!-- Common JS -->
    <script src="/assets/vendor/global/global.min.js"></script>
    <script src="/assets/vendor/bootstrap-select/js/bootstrap-select.min.js"></script>
    <!-- Custom script -->
   <script src="/assets/js/custom.min.js"></script>
    <script src="/assets/js/dlabnav-init.js"></script>
    
	
</body>
</html>

@if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

@if($errors->has('otp'))
<div class="error">{{ $errors->first('otp') }}</div>  @endif
      </section>
      <script>
        const inputs = document.querySelectorAll('#inputs input');
        const otpInput = document.getElementById('otp');
    
        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                // Ensure the input is numeric and has only one character
                input.value = input.value.slice(0, 1).replace(/[^0-9]/g, '');
    
                // Move to next input if current is filled
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
    
                updateOtpValue();
            });
    
            input.addEventListener('keydown', (event) => {
                if (event.key === 'Backspace' && index > 0) {
                    setTimeout(() => {
                        // Clear previous input and move focus back on Backspace
                        if (input.value === '') {
                            inputs[index - 1].value = '';
                            inputs[index - 1].focus();
                        }
                        updateOtpValue();
                    }, 10); // Short delay to handle input value update
                }
            });
        });
    
        function updateOtpValue() {
            // Check if all fields are filled
            if (Array.from(inputs).every(i => i.value.length === 1)) {
                otpInput.value = Array.from(inputs).map(i => i.value).join('');
            } else {
                otpInput.value = ''; // Clear OTP value if not all fields are filled
            }
    
            // Logging for debugging - can be removed later
            console.log('Current OTP Value:', otpInput.value);
        }
    </script>
    

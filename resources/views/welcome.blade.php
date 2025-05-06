<x-guest-layout>

    <h4 class="text-center mb-4">Sign in your account</h4>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group mb-4">
            <label class="form-label" for="username">Email</label>
            {{-- replace the below value, if you need to remove the admin login which i have given directly in the input field to reduce the time of testing.
         --}}
            <input type="email" class="form-control" placeholder="Enter email" id="username" :value="old('email')"
                name="email" required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

        </div>
        <div class="mb-sm-4 mb-3 position-relative">
            <label class="form-label" for="dlab-password">Password</label>
            <input type="password" name="password" required autocomplete="current-password" id="dlab-password"
                class="form-control" placeholder="Enter Password">
            <span class="show-pass eye">
                <i class="fa fa-eye-slash"></i>
                <i class="fa fa-eye"></i>
            </span>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="form-row d-flex flex-wrap justify-content-between mb-2">
            <div class="form-group mb-sm-4 mb-1">
                {{-- <div class="form-check custom-checkbox ms-1">
                    <input id="remember_me" type="checkbox" class="form-check-input" id="basic_checkbox_1"
                        name="remember">
                    <label class="form-check-label" for="basic_checkbox_1">Remember me</label>
                   
                </div> --}}
            </div>
            {{-- <div class="form-group ms-2">
                <a href="page-forgot-password.html">Forgot Password?</a>
            </div> --}}
        </div>
        <div class="form-row justify-content-center align-items-center mb-2">
            <div class="col-auto">
                <div class="form-check custom-checkbox">
                    <canvas id="canvas"></canvas>
                </div>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <input style="border-radius: 25px; padding: 8px 15px; border: 1px solid #ced4da;" onKeyup="check_captcha(this.value)" name="code" placeholder="Enter Captcha" class="form-control">
                </div>
            </div>
        </div>
        


        {{-- </div> --}}
        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-block" id="login_btn" style="display:none;">Sign
                In</button>
        </div>
    </form>

    {{-- <div class="col-md-3">  <canvas id="canvas"></canvas></div>
<div class="col-md-4"> <input style="border-radius:25%;border-radius: 25px;padding: 10px;" onKeyup="check_captcha(this.value)" name="code" placeholder="Type Code" class="form-control"></div> --}}

    {{-- <div class="new-account mt-3">
        <p>Don't have an account? <a class="text-primary" href="{{ route('register') }}">Sign up</a></p>
    </div> --}}
</x-guest-layout>

<script type="text/javascript" src="/assets/js/jquery-captcha.js"></script>
<script>
    // step-1
    const captcha = new Captcha($('#canvas'), {
        length: 4
    });

    function check_captcha(val) {

        if (val.length == 4) {
            const ans = captcha.valid($('input[name="code"]').val());
            if (ans) {
                document.getElementById("login_btn").style.display = 'block';
                document.getElementById("valid").style.display = 'none';
            } else {
                captcha.refresh();
            }
        }
    }

    $(document).keypress(
        function(event) {
            if (event.which == '13') {
                event.preventDefault();
            }
        });
</script>

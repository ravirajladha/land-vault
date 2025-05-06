

<x-guest-layout pageTitle="Your Page Title">
<h4 class="text-center mb-4">Sign in your account</h4>
<x-auth-session-status class="mb-4" :status="session('status')" />
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="form-group mb-4">
        <label class="form-label" for="username">Email</label>
        <input type="email"  class="form-control" placeholder="Enter email" id="username" :value="old('email')" name="email" required autofocus autocomplete="username">
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>
    <div class="mb-sm-4 mb-3 position-relative">
        <label class="form-label" for="dlab-password">Password</label>
        <input  type="password"
        name="password"
        required autocomplete="current-password"  id="dlab-password" class="form-control" value="123456">
        <span class="show-pass eye">
            <i class="fa fa-eye-slash"></i>
            <i class="fa fa-eye"></i>
        </span>
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>
    {{-- <div class="form-row d-flex flex-wrap justify-content-between mb-2">
        <div class="form-group mb-sm-4 mb-1">
            <div class="form-check custom-checkbox ms-1">
                <input id="remember_me" type="checkbox"  class="form-check-input" id="basic_checkbox_1" name="remember" >
                <label class="form-check-label" for="basic_checkbox_1">Remember my preference</label>
                
            </div>
        </div>
        <div class="form-group ms-2">
            <a href="page-forgot-password.html">Forgot Password?</a>
        </div>
    </div> --}}
    <div class="text-center">
        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
    </div>
</form>
{{-- <div class="new-account mt-3">
    <p>Don't have an account? <a class="text-primary" href="{{ route('register') }}">Sign up</a></p>
</div> --}}
</x-guest-layout>
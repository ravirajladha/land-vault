{{-- need to remove the form tag, the registration form is ready --}}

{{-- <x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

   
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

     
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

     
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}


<x-guest-layout>

    <h4 class="text-center mb-4">Sign up your account</h4>
    {{-- <form method="POST" action="{{ route('register') }}">
        @csrf --}}
    <div class="form-group mb-4">
        <label class="form-label" for="username">Username</label>
        <input type="text" class="form-control" placeholder="Enter username" id="username" name="name"
            :value="old('name')" required autofocus autocomplete="name">
        <x-input-error :messages="$errors->get('name')" class="mt-2" />

    </div>
    <div class="form-group mb-4">
        <label class="form-label" for="email">Email</label>
        <input type="email" name="email" :value="old('email')" required autocomplete="username"
            class="form-control" placeholder="hello@example.com" id="email">
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>
    <div class="mb-sm-4 mb-3 position-relative">
        <label class="form-label" for="dlab-password">Password</label>
        <input type="password" name="password" required autocomplete="new-password" id="dlab-password"
            class="form-control" value="123456">
        <span class="show-pass eye">
            <i class="fa fa-eye-slash"></i>
            <i class="fa fa-eye"></i>
        </span>
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>
    <div class="mb-sm-4 mb-3 position-relative">
        <label class="form-label" for="dlab-password">Confirm Password</label>
        <input type="password" type="password" name="password_confirmation" required autocomplete="new-password"
            class="form-control" value="123456">
        <span class="show-pass eye">
            <i class="fa fa-eye-slash"></i>
            <i class="fa fa-eye"></i>
        </span>
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary btn-block">Sign up</button>
    </div>
    {{-- </form> --}}
    <div class="new-account mt-3">
        <p>Already have an account? <a class="text-primary"href="{{ route('login') }}">Sign in</a></p>
    </div>
</x-guest-layout>

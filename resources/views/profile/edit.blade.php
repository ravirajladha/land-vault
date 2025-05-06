{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>
 --}}



    <x-app-layout>

        <x-header/>
        <x-sidebar/>
    
        <div class="content-body default-height">
            <!-- row -->
            <div class="container-fluid">
                {{-- $tableName --}}
                <div class="row page-titles">
                  
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item "><a href="javascript:void(0)">Profile</a></li>
                    
                    </ol>
                
                </div>




                <div class="container-fluid">
                    <div class="row">
    
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                    @include('profile.partials.update-profile-information-form')
              
                </div>
                </div>
                </div>
            </div>
            </div>
            @if($user && $user->hasPermission('Update Profile'))

                <div class="container-fluid">
                    <div class="row">
    
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
               
                    @include('profile.partials.update-password-form')
                </div>
                </div>
                </div>
            </div>
            </div>
@endif
           
            {{-- <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div> --}}
        </div>
    </div>

</div>

</div>



@include('layouts.footer')


</x-app-layout>
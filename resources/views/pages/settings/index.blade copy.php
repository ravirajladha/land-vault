<!-- resources/views/project_settings/edit.blade.php -->
<x-app-layout>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <x-header />
    <x-sidebar />

    <form action="{{ route('project-settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        <!-- Project Name -->
        <label for="project_name">Project Name:</label>
        <input type="text" name="project_name" value="{{ $data->project_name }}">

        <!-- Logo -->
        <label for="logo">Logo:</label>
        <input type="file" name="logo">

        <!-- Favicon -->
        <label for="favicon">Favicon:</label>
        <input type="file" name="favicon">

        <button type="submit">Update Settings</button>
    </form>


    @include('layouts.footer')


</x-app-layout>

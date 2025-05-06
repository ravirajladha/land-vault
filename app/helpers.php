
<?php
use App\Models\ProjectSetting;

if (!function_exists('getProjectName')) {
    function getProjectName()
    {
        return ProjectSetting::first()->project_name;
    }
}

if (!function_exists('getProjectLogo')) {
    function getProjectLogo()
    {
        // Assuming you have a column named 'project_logo' in the project_settings table
        return ProjectSetting::first()->logo;
    }
}
if (!function_exists('getProjectFavicon')) {
    function getProjectFavicon()
    {
        // Assuming you have a column named 'project_logo' in the project_settings table
        return ProjectSetting::first()->favicon;
    }
}

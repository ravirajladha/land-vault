<?php
// app/Http/Controllers/ProjectSettingsController.php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProjectSetting;

class ProjectSettingsController extends Controller
{
    public function edit()
    {
        $projectSettings = ProjectSetting::firstOrFail();
       
        return view('pages.settings.index', ['data' => $projectSettings]);
    }

    public function update(Request $request)
    {
        $projectSettings = ProjectSetting::firstOrFail(); // Assuming only one row exists
    
        // Update project name
        $projectSettings->update([
            'project_name' => $request->input('project_name'),
        ]);
    
        // Update logo
        if ($request->hasFile('logo')) {
            // Validate logo file type
            $request->validate([
                'logo' => 'required|mimes:png,jpeg,jpg,webp|max:3048', // Max file size 3MB
            ]);
    
            // Delete previous logo if exists
            if ($projectSettings->logo) {
                // Delete previous logo file from public/uploads/settings directory
                if (file_exists(public_path($projectSettings->logo))) {
                    unlink(public_path($projectSettings->logo));
                }
            }
    
            $logo = $request->file('logo');
            $logoName = Str::random(4) . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/settings'), $logoName);
            $logoPath = '/uploads/settings/' . $logoName;
            $projectSettings->update(['logo' => $logoPath]);
        }
    
        // Update favicon
        if ($request->hasFile('favicon')) {
            // Validate favicon file type
            $request->validate([
                'favicon' => 'required|mimes:png,jpeg,jpg,webp|max:3048', // Max file size 3MB
            ]);
    
            // Delete previous favicon if exists
            if ($projectSettings->favicon) {
                // Delete previous favicon file from public/uploads/settings directory
                if (file_exists(public_path($projectSettings->favicon))) {
                    unlink(public_path($projectSettings->favicon));
                }
            }
    
            $favicon = $request->file('favicon');
            $faviconName = Str::random(4) . time() . '.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path('uploads/settings'), $faviconName);
            $faviconPath = '/uploads/settings/' . $faviconName;
            $projectSettings->update(['favicon' => $faviconPath]);
        }
    
        return redirect()->back()->with('success', 'Project settings updated successfully');
    }
    
    
}


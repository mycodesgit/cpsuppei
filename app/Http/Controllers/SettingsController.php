<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    //
    public function user_settings() {
        $setting = Setting::firstOrNew(['id' => 1]);
        return view('settings.account_settings', compact('setting'));
    }

    public function setting_list() {
        $setting = Setting::firstOrNew(['id' => 1]);
        return view('settings.system_name', compact('setting'));
    }

    public function upload(Request $request) {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('uploads'), $filename);
            $systemName = $request->input('system_name');

            $setting = Setting::find(1);

            if ($setting && $setting->photo_filename) {
                $oldPhotoPath = public_path('uploads') . '/' . $setting->photo_filename;
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }

            if (!$setting) {
                $setting = new Setting(['id' => 1]);
            }

            $setting->photo_filename = $filename;
            $setting->system_name = $systemName; 
            $setting->save();
        }

        return redirect()->back()->with('success', 'Photo uploaded successfully!');
    }
}

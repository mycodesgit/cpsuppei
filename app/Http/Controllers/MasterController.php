<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Office;
use App\Models\Campus;
use App\Models\User;
use App\Models\Setting;


class MasterController extends Controller
{
    //
    public function dashboard(){
        $camp = Campus::all();
        $userCount = User::count();
        $offCount = Office::count();
        $campusCount = Campus::count();
        $setting = Setting::firstOrNew(['id' => 1]);

        return view("home.dashboard", compact('camp', 'userCount', 'offCount', 'campusCount', 'setting'));
    }

    // public function topnavphoto() {
    //     $setting = Setting::firstOrNew(['id' => 1]);

    //     return view('layouts.master', compact('setting'));
    // }

    public function logout(){
        auth()->logout();
        return redirect()->route('getLogin')->with('success','You have been Successfully Logged Out');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Setting;
use App\Models\Office;
use App\Models\Campus;
use App\Models\User;
use App\Models\property;
use App\Models\Purchases;


class MasterController extends Controller
{
    //
    public function dashboard(){
        $setting = Setting::firstOrNew(['id' => 1]);

        $camp = Campus::all();
        $userCount = User::count();
        $offCount = Office::count();
        $campusCount = Campus::count();
        $propertyCount = property::count();

        $purchasePPECount = Purchases::where('properties_id', '3')->count();
        $purchaseHighCount = Purchases::where('properties_id', '1')->count();
        $purchaseLowCount = Purchases::where('properties_id', '2')->count();

        $MainPpeCount = Purchases::where('properties_id', '3')->whereIn('office_id', [2, 5, 6, 7, 8, 12, 13, 14, 15, 16, 17])->count();
        $MainHighCount = Purchases::where('properties_id', '1')->whereIn('office_id', [2, 5, 6, 7, 8, 12, 13, 14, 15, 16, 17])->count();
        $MainLowCount = Purchases::where('properties_id', '2')->whereIn('office_id', [2, 5, 6, 7, 8, 12, 13, 14, 15, 16, 17])->count();


        $IlogPpeCount = Purchases::where('properties_id', '3')->where('office_id', '7')->count();
        $IlogHighCount = Purchases::where('properties_id', '1')->where('office_id', '7')->count();
        $IlogLowCount = Purchases::where('properties_id', '2')->where('office_id', '7')->count();

        $CauayanPpeCount = Purchases::where('properties_id', '3')->where('office_id', '2')->count();
        $CauayanHighCount = Purchases::where('properties_id', '1')->where('office_id', '2')->count();
        $CauayanLowCount = Purchases::where('properties_id', '2')->where('office_id', '2')->count();

        $SipalayPpeCount = Purchases::where('properties_id', '3')->where('office_id', '5')->count();
        $SipalayHighCount = Purchases::where('properties_id', '1')->where('office_id', '5')->count();
        $SipalayLowCount = Purchases::where('properties_id', '2')->where('office_id', '5')->count();

        $HinobaanPpeCount = Purchases::where('properties_id', '3')->where('office_id', '6')->count();
        $HinobaanHighCount = Purchases::where('properties_id', '1')->where('office_id', '6')->count();
        $HinobaanLowCount = Purchases::where('properties_id', '2')->where('office_id', '6')->count();

        $HinigaranPpeCount = Purchases::where('properties_id', '3')->where('office_id', '12')->count();
        $HinigaranHighCount = Purchases::where('properties_id', '1')->where('office_id', '12')->count();
        $HinigaranLowCount = Purchases::where('properties_id', '2')->where('office_id', '12')->count();

        $MoisesPpeCount = Purchases::where('properties_id', '3')->where('office_id', '13')->count();
        $MoisesHighCount = Purchases::where('properties_id', '1')->where('office_id', '13')->count();
        $MoisesLowCount = Purchases::where('properties_id', '2')->where('office_id', '13')->count();

        $SancarlosPpeCount = Purchases::where('properties_id', '3')->where('office_id', '16')->count();
        $SancarlosHighCount = Purchases::where('properties_id', '1')->where('office_id', '16')->count();
        $SancarlosLowCount = Purchases::where('properties_id', '2')->where('office_id', '16')->count();

        $VictoriasPpeCount = Purchases::where('properties_id', '3')->where('office_id', '15')->count();
        $VictoriasHighCount = Purchases::where('properties_id', '1')->where('office_id', '15')->count();
        $VictoriasLowCount = Purchases::where('properties_id', '2')->where('office_id', '15')->count();


        return view("home.dashboard", compact('camp', 
                                            'userCount', 
                                            'offCount', 
                                            'campusCount', 
                                            'propertyCount', 
                                            'purchasePPECount', 
                                            'purchaseHighCount', 
                                            'purchaseLowCount',
                                            'MainPpeCount',
                                            'MainHighCount',
                                            'MainLowCount',
                                            'IlogPpeCount',
                                            'IlogHighCount',
                                            'IlogLowCount',
                                            'CauayanPpeCount',
                                            'CauayanHighCount',
                                            'CauayanLowCount',
                                            'SipalayPpeCount',
                                            'SipalayHighCount',
                                            'SipalayLowCount', 
                                            'HinobaanPpeCount',
                                            'HinobaanHighCount',
                                            'HinobaanLowCount',
                                            'HinigaranPpeCount',
                                            'HinigaranHighCount',
                                            'HinigaranLowCount', 
                                            'MoisesPpeCount',
                                            'MoisesHighCount',
                                            'MoisesLowCount', 
                                            'SancarlosPpeCount',
                                            'SancarlosHighCount',
                                            'SancarlosLowCount',
                                            'VictoriasPpeCount',
                                            'VictoriasHighCount',
                                            'VictoriasLowCount', 
                                            'setting'));
    }

    public function logout(){
        auth()->logout();
        return redirect()->route('getLogin')->with('success','You have been Successfully Logged Out');
    }
}

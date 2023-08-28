<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Setting;

class InventoryController extends Controller
{
    //
    public function inventoryRead() {
        $setting = Setting::firstOrNew(['id' => 1]);
        return view('inventory.invlist', compact('setting'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\Setting;
use App\Models\Accountable;

class EnduserController extends Controller
{
    public function accountableRead() {
        $setting = Setting::firstOrNew(['id' => 1]);
        $accnt = Accountable::all();
        return view('manage.enduser.accntlist', compact('setting', 'accnt'));
    }

    public function accountableCreate(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $accnt = Accountable::all();

        if ($request->isMethod('post')) {
            $request->validate([
                'person_accnt' => 'required|string|max:255',
            ]);

            $accntName = $request->input('person_accnt');
            $existingAccnt = Accountable::where('person_accnt', $accntName)->first();

            if ($existingAccnt) {
                return redirect()->route('accountableRead')->with('error', 'Accountable Person already exists!');
            }

            try {
                Accountable::create([
                    'person_accnt' => $request->input('person_accnt'),
                ]);

                return redirect()->route('accountableRead')->with('success', 'Item stored successfully!');
            } catch (\Exception $e) {
                return redirect()->route()->with('error', 'Failed to store Accountable!');
            }
        }
    }

    public function accountableEdit($id) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $accnt = Accountable::all();

        $selectedAccnt = Accountable::findOrFail($id);

        return view('manage.enduser.accntlist', compact('setting', 'accnt', 'selectedAccnt'));
    }

    public function accountableUpdate(Request $request) {
        $request->validate([
            'id' => 'required',
            'person_accnt' => 'required',
        ]);

        try {
            $accntName = $request->input('person_accnt');
            $existingAccnt = Accountable::where('person_accnt', $accntName)->where('id', '!=', $request->input('id'))->first();

            if ($existingAccnt) {
                return redirect()->back()->with('error', 'Item already exists!');
            }

            $accnt = Accountable::findOrFail($request->input('id'));
            $accnt->update([
                'person_accnt' => $accntName,
            ]);

            return redirect()->route('accountableEdit', ['id' => $accnt->id])->with('success', 'Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update Accountable!');
        }
    }

    public function accountableDelete($id){
        $accnt = Accountable::find($id);
        $accnt->delete();

        return response()->json([
            'status'=>200,
            'message'=>'Deleted Successfully',
        ]);
    }
}

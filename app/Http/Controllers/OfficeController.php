<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\Setting;
use App\Models\Office;

class OfficeController extends Controller
{
    //
    public function officeRead(){
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        return view('manage.office.list', compact('setting', 'office'));
    }

    public function officeCreate(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();

        if ($request->isMethod('post')) {
            $request->validate([
                'office_code' => 'required||numeric|digits:3',
                'office_name' => 'required',
                'office_abbr' => 'required',
                'office_officer' => 'required',
            ]);

            $officeName = $request->input('office_name');
            $existingOffice = Office::where('office_name', $officeName)->first();

            if ($existingOffice) {
                return redirect()->route('officeRead')->with('error', 'Office already exists!');
            }

            try {
                Office::create([
                    'office_code' => $request->input('office_code'),
                    'office_name' => $request->input('office_name'),
                    'office_abbr' => $request->input('office_abbr'),
                    'office_officer' => $request->input('office_officer'),
                ]);

                return redirect()->route('officeRead')->with('success', 'Office stored successfully!');
            } catch (\Exception $e) {
                return redirect()->route()->with('error', 'Failed to store Office!');
            }
        }
    }

    public function officeEdit($id) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();

        $selectedOffice = Office::findOrFail($id);

        return view('manage.office.list', compact('setting', 'office', 'selectedOffice'));
    }

    public function officeUpdate(Request $request) {
        $request->validate([
            'id' => 'required',
            'office_code' => 'required',
            'office_name' => 'required',
            'office_abbr' => 'required',
            'office_officer' => 'required',
        ]);

        try {
            $officeName = $request->input('office_name');
            $existingOffice = Office::where('office_name', $officeName)->where('id', '!=', $request->input('id'))->first();

            if ($existingOffice) {
                return redirect()->back()->with('error', 'Office already exists!');
            }

            $office = Office::findOrFail($request->input('id'));
            $office->update([
                'office_code' => $request->input('office_code'),
                'office_name' => $officeName,
                'office_abbr' => $request->input('office_abbr'), 
                'office_officer' => $request->input('office_officer'),
            ]);

            return redirect()->route('officeEdit', ['id' => $office->id])->with('success', 'Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update Office!');
        }
    }

    public function officeDelete($id){
        $office = Office::find($id);
        $office->delete();

        return response()->json([
            'status'=>200,
            'message'=>'Deleted Successfully',
        ]);
    }
}

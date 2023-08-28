<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\Setting;
use App\Models\Item;

class ItemController extends Controller
{
    //
    public function itemRead(){
        $setting = Setting::firstOrNew(['id' => 1]);
        $item = Item::all();
        return view('manage.items.list', compact('setting', 'item'));
    }

    public function itemCreate(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $item = Item::all();

        if ($request->isMethod('post')) {
            $request->validate([
                'item_name' => 'required|string|max:255',
            ]);

            $itemName = $request->input('item_name');
            $existingItem = Item::where('item_name', $itemName)->first();

            if ($existingItem) {
                return redirect()->route('itemRead')->with('error', 'Item already exists!');
            }

            try {
                Item::create([
                    'item_name' => $request->input('item_name'),
                ]);

                return redirect()->route('itemRead')->with('success', 'Item stored successfully!');
            } catch (\Exception $e) {
                return redirect()->route()->with('error', 'Failed to store unit!');
            }
        }
    }

    public function itemEdit($id) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $item = Item::all();

        $selectedItem = Item::findOrFail($id);

        return view('manage.items.list', compact('setting', 'item', 'selectedItem'));
    }

    public function itemUpdate(Request $request) {
        $request->validate([
            'id' => 'required',
            'item_name' => 'required',
        ]);

        try {
            $itemName = $request->input('item_name');
            $existingItem = Item::where('item_name', $itemName)->where('id', '!=', $request->input('id'))->first();

            if ($existingItem) {
                return redirect()->back()->with('error', 'Item already exists!');
            }

            $item = Item::findOrFail($request->input('id'));
            $item->update([
                'item_name' => $itemName,
            ]);

            return redirect()->route('itemEdit', ['id' => $item->id])->with('success', 'Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update item!');
        }
    }

    public function itemDelete($id){
        $item = Item::find($id);
        $item->delete();

        return response()->json([
            'status'=>200,
            'message'=>'Deleted Successfully',
        ]);
    }
}

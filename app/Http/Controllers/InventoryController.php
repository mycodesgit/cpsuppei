<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Inventory;
use App\Models\Purchases;
use App\Models\Office;
use App\Models\Accountable;
use App\Models\property;
use App\Models\Properties;
use App\Models\Unit;
use App\Models\Item;
use App\Models\Campus;
use App\Models\Category;
use App\Models\InvQR;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Setting;
use App\Models\InvSetting;
use PDF;

class InventoryController extends Controller
{
    public function inventoryREAD(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $accnt = Accountable::all();
        $item = Item::all();
        $unit = Unit::all();
        $category = Category::all();
        $currentPrice = floatval(str_replace(',', '', $request->input('item_cost'))) ?? 0;
        $property = Property::whereIn('id', [1, 2, 3])->get();
        $inventory = Inventory::join('offices', 'inventories.office_id', '=', 'offices.id')
                    ->join('property', 'inventories.properties_id', '=', 'property.id')
                    ->join('items', 'inventories.item_id', '=', 'items.id')
                    ->leftjoin('purchases', 'inventories.purch_id', '=', 'purchases.id')
                    ->select('inventories.*', 'offices.office_abbr', 'property.abbreviation', 'items.item_name', 'purchases.po_number')
                    ->get();
        return view('inventories.listajax', compact('setting', 'office', 'accnt', 'item', 'unit', 'property', 'currentPrice','category', 'inventory'));
    }

    public function inventoryppeREAD(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $accnt = Accountable::all();
        $item = Item::all();
        $unit = Unit::all();
        $category = Category::all();
        $currentPrice = floatval(str_replace(',', '', $request->input('item_cost'))) ?? 0;
        $property = Property::whereIn('id', [1, 2, 3])->get();
        $inventory = Inventory::join('offices', 'inventories.office_id', '=', 'offices.id')
                    ->join('property', 'inventories.properties_id', '=', 'property.id')
                    ->join('items', 'inventories.item_id', '=', 'items.id')
                    ->select('inventories.*', 'offices.office_abbr', 'property.abbreviation', 'items.item_name')
                    ->where('inventories.properties_id', '=', '3')
                    ->get();
        return view('inventories.list', compact('setting', 'office', 'accnt','item', 'unit', 'property', 'currentPrice','category', 'inventory'));
    }

    public function inventoryhighREAD(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $accnt = Accountable::all();
        $item = Item::all();
        $unit = Unit::all();
        $category = Category::all();
        $currentPrice = floatval(str_replace(',', '', $request->input('item_cost'))) ?? 0;
        $property = Property::whereIn('id', [1, 2, 3])->get();
        $inventory = Inventory::join('offices', 'inventories.office_id', '=', 'offices.id')
                    ->join('property', 'inventories.properties_id', '=', 'property.id')
                    ->join('items', 'inventories.item_id', '=', 'items.id')
                    ->select('inventories.*', 'offices.office_abbr', 'property.abbreviation', 'items.item_name')
                    ->where('inventories.properties_id', '=', '1')
                    ->get();
        return view('inventories.list', compact('setting', 'office', 'accnt', 'item', 'unit', 'property', 'currentPrice','category', 'inventory'));
    }

    public function getInventory() {
        $data = Inventory::join('offices', 'inventories.office_id', '=', 'offices.id')
                ->join('property', 'inventories.properties_id', '=', 'property.id')
                ->join('items', 'inventories.item_id', '=', 'items.id')
                ->leftjoin('purchases', 'inventories.purch_id', '=', 'purchases.id')
                ->select('inventories.*', 'offices.office_abbr', 'property.abbreviation', 'items.item_name', 'purchases.po_number')
                ->get();
        return response()->json(['data' => $data]);
    }

    public function inventorylowREAD(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $accnt = Accountable::all();
        $item = Item::all();
        $unit = Unit::all();
        $category = Category::all();
        $currentPrice = floatval(str_replace(',', '', $request->input('item_cost'))) ?? 0;
        $property = Property::whereIn('id', [1, 2, 3])->get();
        $inventory = Inventory::join('offices', 'inventories.office_id', '=', 'offices.id')
                    ->join('property', 'inventories.properties_id', '=', 'property.id')
                    ->join('items', 'inventories.item_id', '=', 'items.id')
                    ->select('inventories.*', 'offices.office_abbr', 'property.abbreviation', 'items.item_name')
                    ->where('inventories.properties_id', '=', '2')
                    ->get();
        return view('inventories.list', compact('setting', 'office', 'accnt', 'item', 'unit', 'property', 'currentPrice','category', 'inventory'));
    }

    public function inventoryintangibleREAD(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $accnt = Accountable::all();
        $item = Item::all();
        $unit = Unit::all();
        $category = Category::all();
        $currentPrice = floatval(str_replace(',', '', $request->input('item_cost'))) ?? 0;
        $property = Property::where('id', 4)->get();
        $inventory = Inventory::join('offices', 'inventories.office_id', '=', 'offices.id')
                    ->join('property', 'inventories.properties_id', '=', 'property.id')
                    ->join('items', 'inventories.item_id', '=', 'items.id')
                    ->select('inventories.*', 'offices.office_abbr', 'property.abbreviation', 'items.item_name')
                    ->where('inventories.properties_id', '=', '4')
                    ->get();
        return view('inventories.list', compact('setting', 'office', 'accnt', 'item', 'unit', 'property', 'currentPrice','category', 'inventory'));
    }

    public function inventoryStickerTemplate() {
        $setting = Setting::firstOrNew(['id' => 1]);
        return view('inventories.sticker', compact('setting'));
    }

    public function inventoryStickerTemplatePDF() {
        $setting = Setting::firstOrNew(['id' => 1]);
        $pdf = PDF::loadView('inventories.blankSticker')->setPaper('A4', 'portrait');
        return $pdf->stream();
    }

    public function inventoryREADcat($id) {
        $accounts = Properties::where('category_id', $id)
            ->select('account_number', 'account_title', 'code', 'id')
            ->get();
        
        $options = "";
        foreach ($accounts as $account) {
            $options .= "<option value='".$account->code."'>".$account->code.' '.$account->account_title."</option>";
        }

        return response()->json([
            "options" => $options,
        ]);

    }

    public function inventoryPrntSticker($id) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $user = User::where('role', '=', 'Supply Officer')->first();
        $inventory = Inventory::leftJoin('offices', 'inventories.office_id', '=', 'offices.id')
                    ->leftJoin('items', 'inventories.item_id', '=', 'items.id')
                    ->leftJoin('properties', 'inventories.selected_account_id', '=', 'properties.id')
                    ->leftJoin('accountable', 'inventories.person_accnt', '=', 'accountable.id')
                    ->select('inventories.*', 'inventories.id as pid', 'offices.*', 'items.*', 'properties.*', 'accountable.*' )
                    ->findOrFail($id);
                    $propertiesId = $inventory->properties_id;
        return view('inventories.modal-sticker', compact('setting', 'inventory', 'user', 'propertiesId'));
    }

    public function inventoryCreate(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $inventory = Inventory::all();
        $office = Office::where('id', $request->office_id)->first();
        $accnt = Accountable::find($request->person_accnt);
        $date = Carbon::now();
        $dateAcquired = $request->input('date_acquired');
        
        $accountablePer = !isset($request->person_accnt) ? $office->office_officer : $accnt->person_accnt;

        if ($dateAcquired) {
            $formattedDate = date('Y', strtotime($dateAcquired));
        } else {
            $formattedDate = $date->format('Y');
        }

        if ($request->input('item_cost') >= 50001) {
            $propertyCode = "06";
        } else {
            $propertyCode = "04";
        }
        $categoriesCode = $request->categories_id;
        $propertiesCode = $request->property_id;
        $accountID = $request->id;

        $lastItemNumber = Inventory::where([
            //'office_id' => $request->input('office_id'),
            'item_id' => $request->input('item_id'),
            'property_id' => $request->input('property_id'),
        ])->max('item_number');
        $newItemNumber = str_pad($lastItemNumber + 1, 3, '0', STR_PAD_LEFT);

        $officeCode = $office->office_code;
        $propertyCodeGen = $formattedDate.'-'.$propertyCode.'-'.$categoriesCode.'-'.$propertiesCode.'-'.$newItemNumber.'-'.$officeCode;

        if ($request->isMethod('post')) {
            $request->validate([
                'office_id' => 'required',
                'item_id' => 'required',
                'item_descrip' => 'required',
                'item_model' => 'required',
                'serial_number' => 'required',
                'unit_id' => 'required',
                'qty' => 'required',
                'item_cost' => 'required',
                'total_cost' => 'required',
                'properties_id' => 'required',
            ]);

            // try {
                Inventory::create([
                    'office_id' => $request->input('office_id'),
                    'item_id' => $request->input('item_id'),
                    'item_descrip' => $request->input('item_descrip'),
                    'item_model' => $request->input('item_model'),
                    'serial_number' => $request->input('serial_number'),
                    'date_acquired' => $request->input('date_acquired'),
                    'unit_id' => $request->input('unit_id'),
                    'qty' => $request->input('qty'),
                    'item_cost' => $request->input('item_cost'),
                    'total_cost' => $request->input('total_cost'),
                    'properties_id' => $request->input('properties_id'),
                    'categories_id' => $request->input('categories_id'),
                    'property_id' => $request->input('property_id'),
                    'item_number' => $newItemNumber,
                    'property_no_generated' => $propertyCodeGen,
                    'selected_account_id' => $request->input('selected_account_id'),
                    'remarks' => $request->input('remarks'),
                    'price_stat' => $request->input('price_stat'),
                    'person_accnt' => $request->input('person_accnt'),
                    'person_accnt_name' => $accountablePer,
                ]);

                return redirect()->back()->with('success', 'Purchase Item  stored successfully!');
            // } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to store Purchase Item!');
            // }
        }
    }

    public function inventoryEdit(Request $request, $id) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $inventory = Inventory::findOrFail($id);
        // $property = Property::whereIn('id', [1, 2, 3])->get();
        $propertyIds = ($inventory->properties_id == 4) ? [4] : [1, 2, 3];
        $property = Property::whereIn('id', $propertyIds)->get();
        
        $office = Office::all();
        $accnt = Accountable::all();
        $item = Item::all();
        $unit = Unit::all();
        $category = Category::all();

        $selectedOfficeId = $inventory->office_id;
        $selectedPerson = $inventory->person_accnt;
        $selectedItemId = $inventory->item_id;
        $selectedUnitId = $inventory->unit_id;
        $selectedCatId = $inventory->categories_id;
        $selectedAccId = $inventory->property_id;
        $selectedPropId = $inventory->properties_id;
        $category1 = Category::where('id', $selectedCatId)->first();
        $catcode = $category1->cat_code;

        $property1 = Properties::where('category_id', $catcode)
        ->where('property_id', $selectedPropId)
        ->get();

        $currentPrice = floatval(str_replace(',', '', $request->input('item_cost'))) ?? 0;

        return view('inventories.edit-inventory', compact('setting', 'property', 'property1', 'inventory', 'office', 'accnt', 'item', 'unit', 'category', 'selectedOfficeId', 'selectedPerson', 'selectedItemId', 'selectedUnitId', 'currentPrice', 'selectedCatId', 'selectedAccId', 'selectedPropId'));
    }

    public function inventoryUpdate(Request $request) {
        $inventory = Inventory::find($request->id);
        $office = Office::where('id', $inventory->office_id)->first();
        $date = Carbon::now();
        $dateAcquired = $request->input('date_acquired');

        if ($dateAcquired) {
            $formattedDate = date('Y', strtotime($dateAcquired));
        } else {
            $formattedDate = $date->format('Y');
        }

        if ($request->input('item_cost') >= 50001) {
            $propertyCode = "06";
        } else {
            $propertyCode = "04";
        }
        $categoriesCode = $request->categories_id;
        $propertiesCode = $request->property_id;
        $accountID = $request->id;

        $lastItemNumber = Inventory::where([
            //'office_id' => $request->input('office_id'),
            'item_id' => $request->input('item_id'),
            'property_id' => $request->input('property_id'),
        ])->max('item_number');
        $newItemNumber = str_pad($lastItemNumber + 1, 3, '0', STR_PAD_LEFT);
       
        $officeCode = $office->office_code;
        $propertyCodeGen = $formattedDate.'-'.$propertyCode.'-'.$categoriesCode.'-'.$propertiesCode.'-'.$inventory->item_number.'-'.$officeCode;


        $request->validate([
            'id' => 'required',
            'office_id' => 'required',
            'item_id' => 'required',
            'item_descrip' => 'required',
            'serial_number' => 'required',
            'unit_id' => 'required',
            'qty' => 'required',
            'item_cost' => 'required',
            'total_cost' => 'required',
            'properties_id' => 'required',
        ]);

       try {
            $inventory = Inventory::findOrFail($request->input('id'));
            $inventory->update([
                'office_id' => $request->input('office_id'),
                'item_id' => $request->input('item_id'),
                'item_descrip' => $request->input('item_descrip'),
                'item_model' => $request->input('item_model'),
                'serial_number' => $request->input('serial_number'),
                'date_acquired' => $request->input('date_acquired'),
                'unit_id' => $request->input('unit_id'),
                'qty' => $request->input('qty'),
                'item_cost' => $request->input('item_cost'),
                'total_cost' => $request->input('total_cost'),
                'properties_id' => $request->input('properties_id'),
                'categories_id' => $request->input('categories_id'),
                'property_id' => $request->input('property_id'),
                //'item_number' => $request->input('item_number'),
                'property_no_generated' => $propertyCodeGen,
                'selected_account_id' => $request->input('selected_account_id'),
                'remarks' => $request->input('remarks'),
                'price_stat' => $request->input('price_stat'),
                'person_accnt' => $request->input('person_accnt'),
            ]);

            return redirect()->route('inventoryEdit', ['id' => $inventory->id])->with('success', 'Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update item!');
        }
    }

    public function inventoryCat($id, $mode) {
        if ($id) {
            $accounts = Properties::where('category_id', $id)
                ->where('property_id', $mode)
                ->select('account_number', 'account_title', 'code', 'id')
                ->get();
    
            $options = "<option value='All' data-account-id='All' selected>All</option>"; 
    
            foreach ($accounts as $account) {
                $options .= "<option value='".$account->code."'  data-account-id='".$account->id."'>".$account->code.' '.$account->account_title."</option>";
            }
        } else {
            $options = "<option value=''>Select a category first</option>";
        }
    
        return response()->json([
            "options" => $options,
        ]);
    }

    public function invCatIcsPar($id, $mode) {
        $modeArray = explode(',', $mode);
        if ($id) {
            $accounts = Properties::join('property', 'property.id', '=', 'properties.property_id')
                ->where('properties.category_id', $id)
                ->whereIn('properties.property_id', $modeArray)
                ->select('properties.account_number', 'properties.account_title', 'properties.code', 'properties.id', 'property.abbreviation')
                ->get();
    
            $options = "<option value='All' data-account-id='All' selected>All</option>"; 
            
            foreach ($accounts as $account) {
                $options .= "<option value='".$account->code."' data-account-id='".$account->id."'>".$account->code.'|'.$account->abbreviation.'|'.$account->account_title."</option>";
            }
        } else {
            $options = "<option value=''>Select a category first</option>";
        }
    
        return response()->json([
            "options" => $options,
        ]);
    }    
    

    public function inventoryDelete($id){
        $puchase = Inventory::find($id);
        $puchase->delete();

        return response()->json([
            'status'=>200,
            'message'=>'Deleted Successfully',
        ]);
    }

    public function inventoryReportsOtption() {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $property = Property::all();
        $category = Category::all();

        return view('inventories.reportsOption', compact('setting', 'office', 'property', 'category'));
    }

    public function inventoryReportsOtptionGen(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $item = Item::all();
        $unit = Unit::all();
        $category = Category::all();

        $officeId = $request->query('office_id');
        $propertiesId = $request->query('properties_id');
        $categoriesId = $request->query('categories_id');
        $propId = $request->query('property_id');
        $startDate = $request->query('start_date_acquired');
        $endDate = $request->query('end_date_acquired');
        $selectId = $request->query('selected_account_id');

        $cond = ($categoriesId == 'All') ? '!=' : '=';

        $propId = ($categoriesId == 'All') ? $propId = '0' : $propId;
        $selectId = ($categoriesId == 'All') ? $selectId = '0' : $selectId;
        $categoriesId = ($categoriesId == 'All') ? $categoriesId = '0' : $categoriesId;

        $inventory = Inventory::join('offices', 'inventories.office_id', '=', 'offices.id')
            ->join('properties', 'inventories.selected_account_id', '=', 'properties.id')
            ->join('units', 'inventories.unit_id', '=', 'units.id')
            ->where('inventories.office_id', $officeId)
            ->where('inventories.properties_id', $propertiesId)
            ->where('inventories.categories_id', $cond, $categoriesId)
            ->where('inventories.property_id', $cond, $propId)
            ->where('inventories.selected_account_id', $cond, $selectId)
            ->where(function ($query) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $query->whereBetween('inventories.date_acquired', [$startDate, $endDate]);
                } elseif ($startDate) {
                    $query->where('inventories.date_acquired', '>=', $startDate);
                } elseif ($endDate) {
                    $query->where('inventories.date_acquired', '<=', $endDate);
                }
            })
            ->get();


        $data = [
            'inventory' => $purchase,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedPropertyId' => $selectId,
            'category_id' => $purchase
        ];

        $pdf = PDF::loadView('inventories.printRPCPPE', $data)->setPaper('Legal', 'landscape');
        return $pdf->stream();
    }

    public function geneCheck(Request $request){
        $qr = $request->query('q');
    
        $inventoryQuery = Inventory::where('property_no_generated', $qr)
            ->leftJoin('offices', 'inventories.office_id', '=', 'offices.id')
            ->leftJoin('accountable', 'inventories.person_accnt', '=', 'accountable.id')
            ->select(
                'inventories.id', 
                'inventories.remarks', 
                'inventories.property_no_generated', 
                'inventories.office_id', 
                'inventories.person_accnt_name', 
                'offices.office_officer', 
                'accountable.person_accnt as accntperson'
            );
        
        $count = $inventoryQuery->count();
    
        if ($count == 1) {
            $inventory = $inventoryQuery->first();
            
            $office = Office::where('id', '!=', 1)->select('id', 'office_name', 'office_officer')->get();
            $accnt = Accountable::select('id', 'person_accnt')->get();
            
            $data = [
                'invmatch' => $inventory,
                'office' => $office,
                'accnt' => $accnt,
            ];
    
            return response()->json(['data' => $data], 200);
    
        } elseif ($count > 1) {
            return 'multiple';
        }else{
            return '0';
        } 

    }
    

    public function geneQr(Request $request){
        $uid = $request->query('uid');
        $qr = $request->query('qrcode');
        $atype = $request->query('accnt_type');
        $paccount = $request->query('person_accnt');
        $remarks = $request->query('remarks');
        $comment = $request->query('comment');
        
        $inventory = Inventory::where('property_no_generated', $qr)->first();
        
        $inv_id = $inventory->id;

        InvQR::create([
            'uid' => $uid,
            'inv_id' => $inv_id,
            'accnt_type' => $atype,
            'person_accnt' => $paccount,
            'remarks' => $remarks,
            'comment' => $comment,
        ]);

        if($inventory){
            return "1";
        }else{
            return "0";
        }
    }

    public function inventoryStat(){
        $instat = InvSetting::first();

        return $instat->switch;
    }
    
    public function inventoryStatUp(){
        $instat = InvSetting::first();
        
        if ($instat) {
            $instat->switch = $instat->switch == 1 ? 0 : 1;
            
            $instat->save();
            
            return $instat->switch;
        }
    
        return null;
    }
}

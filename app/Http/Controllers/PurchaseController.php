<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Setting;
use App\Models\Purchases;
use App\Models\Office;
use App\Models\Accountable;
use App\Models\property;
use App\Models\Properties;
use App\Models\Unit;
use App\Models\Item;
use App\Models\Campus;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use PDF;


class PurchaseController extends Controller
{
    public function purchaseREAD(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $accnt = Accountable::all();
        $item = Item::all();
        $unit = Unit::all();
        $category = Category::all();
        $currentPrice = floatval(str_replace(',', '', $request->input('item_cost'))) ?? 0;
        $property = Property::whereIn('id', [1, 2, 3])->get();
        $purchase = Purchases::join('offices', 'purchases.office_id', '=', 'offices.id')
                            ->join('property', 'purchases.properties_id', '=', 'property.id')
                            ->join('items', 'purchases.item_id', '=', 'items.id')
                            ->select('purchases.*', 'offices.office_abbr', 'property.abbreviation', 'items.item_name')
                            ->get();
        return view('purchases.list', compact('setting', 'office', 'accnt', 'item', 'unit', 'property', 'currentPrice','category', 'purchase'));
    }

    public function purchaseppeREAD(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $accnt = Accountable::all();
        $item = Item::all();
        $unit = Unit::all();
        $category = Category::all();
        $currentPrice = floatval(str_replace(',', '', $request->input('item_cost'))) ?? 0;
        $property = Property::whereIn('id', [1, 2, 3])->get();
        $purchase = Purchases::join('offices', 'purchases.office_id', '=', 'offices.id')
                            ->join('property', 'purchases.properties_id', '=', 'property.id')
                            ->join('items', 'purchases.item_id', '=', 'items.id')
                            ->select('purchases.*', 'offices.office_abbr', 'property.abbreviation', 'items.item_name')
                            ->where('purchases.properties_id', '=', '3')
                            ->get();
        return view('purchases.list', compact('setting', 'office', 'accnt','item', 'unit', 'property', 'currentPrice','category', 'purchase'));
    }

    public function purchasehighREAD(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $accnt = Accountable::all();
        $item = Item::all();
        $unit = Unit::all();
        $category = Category::all();
        $currentPrice = floatval(str_replace(',', '', $request->input('item_cost'))) ?? 0;
        $property = Property::whereIn('id', [1, 2, 3])->get();
        $purchase = Purchases::join('offices', 'purchases.office_id', '=', 'offices.id')
                            ->join('property', 'purchases.properties_id', '=', 'property.id')
                            ->join('items', 'purchases.item_id', '=', 'items.id')
                            ->select('purchases.*', 'offices.office_abbr', 'property.abbreviation', 'items.item_name')
                            ->where('purchases.properties_id', '=', '1')
                            ->get();
        return view('purchases.list', compact('setting', 'office', 'accnt', 'item', 'unit', 'property', 'currentPrice','category', 'purchase'));
    }

    public function purchaselowREAD(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $accnt = Accountable::all();
        $item = Item::all();
        $unit = Unit::all();
        $category = Category::all();
        $currentPrice = floatval(str_replace(',', '', $request->input('item_cost'))) ?? 0;
        $property = Property::whereIn('id', [1, 2, 3])->get();
        $purchase = Purchases::join('offices', 'purchases.office_id', '=', 'offices.id')
                            ->join('property', 'purchases.properties_id', '=', 'property.id')
                            ->join('items', 'purchases.item_id', '=', 'items.id')
                            ->select('purchases.*', 'offices.office_abbr', 'property.abbreviation', 'items.item_name')
                            ->where('purchases.properties_id', '=', '2')
                            ->get();
        return view('purchases.list', compact('setting', 'office', 'accnt', 'item', 'unit', 'property', 'currentPrice','category', 'purchase'));
    }

    public function purchaseStickerTemplate() {
        $setting = Setting::firstOrNew(['id' => 1]);
        return view('purchases.sticker', compact('setting'));
    }

    public function purchaseStickerTemplatePDF() {
        $setting = Setting::firstOrNew(['id' => 1]);
        $pdf = PDF::loadView('purchases.blankSticker')->setPaper('A4', 'portrait');
        return $pdf->stream();
    }

    public function purchaseREADcat($id) {
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

    public function purchasePrntSticker($id) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $user = User::where('role', '=', 'Supply Officer')->first();
        $purchase = Purchases::join('offices', 'purchases.office_id', '=', 'offices.id')
            ->join('items', 'purchases.item_id', '=', 'items.id')
            ->join('properties', 'purchases.selected_account_id', '=', 'properties.id')
            ->select('purchases.*', 'purchases.id as pid', 'offices.*', 'items.*', 'properties.*')
            ->findOrFail($id);
            $propertiesId = $purchase->properties_id;
        return view('purchases.modal-sticker', compact('setting', 'purchase', 'user', 'propertiesId'));
    }

    public function purchaseCreate(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $purchase = Purchases::all();
        $office = Office::where('id', $request->office_id)->first();
        $accnt = Accountable::all();
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

        $lastItemNumber = Purchases::where([
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

            try {
                Purchases::create([
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
                ]);

                return redirect()->route('purchaseREAD')->with('success', 'Purchase Item  stored successfully!');
            } catch (\Exception $e) {
                return redirect()->route('purchaseREAD')->with('error', 'Failed to store unit!');
            }
        }
    }

    public function purchaseEdit(Request $request, $id) {
        $setting = Setting::firstOrNew(['id' => 1]);
        $purchase = Purchases::findOrFail($id);
        $property = Property::whereIn('id', [1, 2, 3])->get();
        $office = Office::all();
        $accnt = Accountable::all();
        $item = Item::all();
        $unit = Unit::all();
        $category = Category::all();

        $selectedOfficeId = $purchase->office_id;
        $selectedPerson = $purchase->person_accnt;
        $selectedItemId = $purchase->item_id;
        $selectedUnitId = $purchase->unit_id;
        $selectedCatId = $purchase->categories_id;
        $selectedAccId = $purchase->property_id;
        $selectedPropId = $purchase->properties_id;
        $category1 = Category::where('id', $selectedCatId)->first();
        $catcode = $category1->cat_code;

        $property1 = Properties::where('category_id', $catcode)
        ->where('property_id', $selectedPropId)
        ->get();

        $currentPrice = floatval(str_replace(',', '', $request->input('item_cost'))) ?? 0;

        return view('purchases.edit-purchase', compact('setting', 'property', 'property1', 'purchase', 'office', 'accnt', 'item', 'unit', 'category', 'selectedOfficeId', 'selectedPerson', 'selectedItemId', 'selectedUnitId', 'currentPrice', 'selectedCatId', 'selectedAccId', 'selectedPropId'));
    }

    public function purchaseUpdate(Request $request) {
        $purchase = Purchases::find($request->id);
        $office = Office::where('id', $purchase->office_id)->first();
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

        $lastItemNumber = Purchases::where([
            //'office_id' => $request->input('office_id'),
            'item_id' => $request->input('item_id'),
            'property_id' => $request->input('property_id'),
        ])->max('item_number');
        $newItemNumber = str_pad($lastItemNumber + 1, 3, '0', STR_PAD_LEFT);
       
        $officeCode = $office->office_code;
        $propertyCodeGen = $formattedDate.'-'.$propertyCode.'-'.$categoriesCode.'-'.$propertiesCode.'-'.$purchase->item_number.'-'.$officeCode;


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
            $purchase = Purchases::findOrFail($request->input('id'));
            $purchase->update([
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

            return redirect()->route('purchaseEdit', ['id' => $purchase->id])->with('success', 'Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update item!');
        }
    }

    public function purchaseCat($id, $mode) {
        if ($id) {
            $accounts = Properties::where('category_id', $id)
                ->where('property_id', $mode)
                ->select('account_number', 'account_title', 'code', 'id')
                ->get();

            $options = "";
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

    public function purchaseDelete($id){
        $puchase = Purchases::find($id);
        $puchase->delete();

        return response()->json([
            'status'=>200,
            'message'=>'Deleted Successfully',
        ]);
    }

    public function purchaseReportsOtption() {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $property = Property::all();
        $category = Category::all();

        return view('purchases.reportsOption', compact('setting', 'office', 'property', 'category'));
    }

    public function purchaseReportsOtptionGen(Request $request) {
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

        $purchase = Purchases::join('offices', 'purchases.office_id', '=', 'offices.id')
            ->join('properties', 'purchases.selected_account_id', '=', 'properties.id')
            ->join('units', 'purchases.unit_id', '=', 'units.id')
            ->where('purchases.office_id', $officeId)
            ->where('purchases.properties_id', $propertiesId)
            ->where('purchases.categories_id', $cond, $categoriesId)
            ->where('purchases.property_id', $cond, $propId)
            ->where('purchases.selected_account_id', $cond, $selectId)
            ->where(function ($query) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $query->whereBetween('purchases.date_acquired', [$startDate, $endDate]);
                } elseif ($startDate) {
                    $query->where('purchases.date_acquired', '>=', $startDate);
                } elseif ($endDate) {
                    $query->where('purchases.date_acquired', '<=', $endDate);
                }
            })
            ->get();


        $data = [
            'purchase' => $purchase,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedPropertyId' => $selectId,
            'category_id' => $purchase
        ];

        $pdf = PDF::loadView('purchases.printRPCPPE', $data)->setPaper('Legal', 'landscape');
        return $pdf->stream();
    }
}

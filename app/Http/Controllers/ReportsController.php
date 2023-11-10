<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Purchases;
use App\Models\Office;
use App\Models\property;
use App\Models\Properties;
use App\Models\Unit;
use App\Models\Item;
use App\Models\Campus;
use App\Models\Category;
use App\Models\User;
use App\Models\Accountable;
use Carbon\Carbon;
use PDF;
;

class ReportsController extends Controller
{
    public function rpcppeOption() {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $property = Property::whereIn('id', [3])->get();
        $category = Category::all();

        return view('reports.rpcppe_option', compact('setting', 'office', 'property', 'category'));
    }

    public function rpcppeOptionReportGen(Request $request) {
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
        $allOffice = ($officeId == 'All') ? '!=' : '=';


        $propId = ($categoriesId == 'All') ? $propId = '0' : $propId;
        $selectId = ($categoriesId == 'All') ? $selectId = '0' : $selectId;
        $categoriesId = ($categoriesId == 'All') ? $categoriesId = '0' : $categoriesId;

        $purchase = Purchases::join('offices', 'purchases.office_id', '=', 'offices.id')
            ->join('properties', 'purchases.selected_account_id', '=', 'properties.id')
            ->join('units', 'purchases.unit_id', '=', 'units.id')
            ->where('purchases.office_id', $allOffice, $officeId)
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

        $pdf = PDF::loadView('reports.rpcppe_option_reportsGen', $data)->setPaper('Legal', 'landscape');
        return $pdf->stream();
    }

    public function rpcsepOption() {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $property = Property::whereIn('id', [1, 2])->get();
        $category = Category::all();

        return view('reports.rpcsep_option', compact('setting', 'office', 'property', 'category'));
    }

    public function rpcsepOptionReportGen(Request $request) {
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
        $allOffice = ($officeId == 'All') ? '!=' : '=';

        $propId = ($categoriesId == 'All') ? $propId = '0' : $propId;
        $selectId = ($categoriesId == 'All') ? $selectId = '0' : $selectId;
        $categoriesId = ($categoriesId == 'All') ? $categoriesId = '0' : $categoriesId;
        $officeId = ($officeId == 'All') ? $officeId = '0' : $officeId;

        $purchase = Purchases::join('offices', 'purchases.office_id', '=', 'offices.id')
            ->join('properties', 'purchases.selected_account_id', '=', 'properties.id')
            ->join('units', 'purchases.unit_id', '=', 'units.id')
            ->where('purchases.office_id', $allOffice, $officeId)
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

        $pdf = PDF::loadView('reports.rpcsep_option_reportsGen', $data)->setPaper('Legal', 'landscape');
        return $pdf->stream();
    }

    public function icsOption() {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $property = Property::all();
        $category = Category::all();
        $purchase = Purchases::join('accountable', 'purchases.person_accnt', '=', 'accountable.id')
                    ->select('purchases.*', 'accountable.person_accnt')
                    ->get();
        return view('reports.ics_option', compact('setting', 'office', 'property', 'category', 'purchase'));
    }

    public function icsOptionReportGen(Request $request) {
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

        $pdf = PDF::loadView('reports.ics_option_reportsGen', $data)->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }

    public function parOption() {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $itemPurchase = Purchases::join('items', 'purchases.item_id', '=', 'items.id')
                            ->select('purchases.*', 'items.item_name')
                            ->get();
        $accnt = Accountable::all();
        return view('reports.par_option', compact('setting', 'office', 'itemPurchase', 'accnt'));
    }

    public function parOptionReportGen(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
         
        $officeId = $request->office_id;
        $userId = $request->person_accnt;
        $itemId = $request->item_id;

        $selectedItem = Item::whereIn('id', $itemId)->get();

        $relatedItems = Item::join('purchases', 'items.id', '=', 'purchases.item_id')
            ->join('units', 'purchases.unit_id', '=', 'units.id')
            ->join('accountable', 'purchases.person_accnt', '=', 'accountable.id')
            ->join('offices', 'purchases.office_id', '=', 'offices.id')
            ->select('purchases.*', 'items.*', 'items.id as itemid', 'units.*', 'accountable.person_accnt', 'offices.office_abbr')
            ->whereIn('purchases.id', $itemId)
            ->where('purchases.person_accnt', $userId)
            ->get();

        $data = [
            'selectedItem' => $selectedItem,
            'relatedItems' => $relatedItems,
        ];

        $pdf = PDF::loadView('reports.par_option_reportsGen', $data)->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }

    public function itemList($id) {
        $itempar = Purchases::where('person_accnt', $id)
            ->join('items', 'items.id', '=', 'purchases.item_id')
            ->select('purchases.*', 'items.*', 'purchases.id as pid')
            ->get();
        
        $options = "";
        foreach ($itempar as $parItem) {
            $options .= "<option value='".$parItem->pid."'>".$parItem->item_name.' '.$parItem->item_descrip."</option>";
        }

        return response()->json([
            "options" => $options,
        ]);

    }

}

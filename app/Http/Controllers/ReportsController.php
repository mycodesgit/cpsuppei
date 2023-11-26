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
        $officeId1 = $request->query('office_id');
        $propertiesId = $request->query('properties_id');
        $categoriesId = $request->query('categories_id');
        $propId = $request->query('property_id');
        $startDate = $request->query('start_date_acquired');
        $endDate = $request->query('end_date_acquired');
        $selectId = $request->query('selected_account_id');

        $formattedStartDate = $startDate ? date('M. d, Y', strtotime($startDate)) : '';
        $formattedEndDate = $endDate ? date('M. d, Y', strtotime($endDate)) : '';

        $cond = ($categoriesId == 'All') ? '!=' : '=';
        $allOffice = ($officeId == 'All') ? '!=' : '=';

        $propId = ($categoriesId == 'All') ? $propId = '0' : $propId;
        $selectId = ($categoriesId == 'All') ? $selectId = '0' : $selectId;
        $categoriesId = ($categoriesId == 'All') ? $categoriesId = '0' : $categoriesId;

        $officeId == 1 ? $filter = 'whereNotIn' : $filter = 'where';

        $officeIdsToCheck = $officeId == 1 ? [2, 5, 6, 7, 8, 12, 13, 14, 15, 16, 17] : $allOffice;


        $purchase = Purchases::join('offices', 'purchases.office_id', '=', 'offices.id')
            ->join('properties', 'purchases.selected_account_id', '=', 'properties.id')
            ->join('units', 'purchases.unit_id', '=', 'units.id')
            ->join('items', 'purchases.item_id', '=', 'items.id')
            ->where(function ($query) use ($officeId, $allOffice, $cond) {
                if ($officeId == 1) {
                    $query->whereNotIn('purchases.office_id', [2, 5, 6, 7, 8, 12, 13, 14, 15, 16, 17]);
                } else {
                    $query->where('purchases.office_id', $cond, $allOffice);
                }
            })
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

        $purchase1 = Purchases::join('offices', 'purchases.office_id', '=', 'offices.id')
            ->join('properties', 'purchases.selected_account_id', '=', 'properties.id')
            ->join('units', 'purchases.unit_id', '=', 'units.id')
            ->join('items', 'purchases.item_id', '=', 'items.id')
            ->where(function ($query) use ($officeId, $allOffice, $cond) {
                if ($officeId == 1) {
                    $query->whereNotIn('purchases.office_id', [2, 5, 6, 7, 8, 12, 13, 14, 15, 16, 17]);
                } else {
                    $query->where('purchases.office_id', $cond, $allOffice);
                }
            })
            ->where('purchases.properties_id', $propertiesId)
            ->where('purchases.categories_id', $cond, $categoriesId)
            ->where('purchases.property_id', $cond, $propId)
            ->where('purchases.selected_account_id', $cond, $selectId)
            ->where(function ($query) use ($startDate) {
                if ($startDate) {
                    $query->where('purchases.date_acquired', '<', $startDate);
                }
            })
            ->get();


        $purchase2 = Purchases::join('offices', 'purchases.office_id', '=', 'offices.id')
            ->join('properties', 'purchases.selected_account_id', '=', 'properties.id')
            ->join('units', 'purchases.unit_id', '=', 'units.id')
            ->join('items', 'purchases.item_id', '=', 'items.id')
            // ->where('purchases.office_id', $allOffice, $officeId)
            ->where(function ($query) use ($officeId, $allOffice, $cond) {
                if ($officeId == 1) {
                    $query->whereNotIn('purchases.office_id', [2, 5, 6, 7, 8, 12, 13, 14, 15, 16, 17]);
                } else {
                    $query->where('purchases.office_id', $cond, $allOffice);
                }
            })
            ->where('purchases.properties_id', $propertiesId)
            ->where('purchases.categories_id', $cond, $categoriesId)
            ->where('purchases.property_id', $cond, $propId)
            ->where('purchases.selected_account_id', $cond, $selectId)
            ->where(function ($query) use ($endDate) {
                if ($endDate) {
                    $query->where('purchases.date_acquired', '>', $endDate);
                }
            })
            ->get();

        $bforward = $purchase1->sum(function ($purchase) {
            return str_replace(',', '', $purchase->total_cost);
        });

        // Ensure $purchase2 is not null or empty before attempting to use it
        $bforward1 = $purchase2->isEmpty() ? 0 : $purchase2->sum(function ($purchase) {
            return str_replace(',', '', $purchase->total_cost);
        });



        $data = [
            'purchase' => $purchase,
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
            'selectedPropertyId' => $selectId,
            'category_id' => $purchase,
            'bforward' => $bforward, 
            'bforward1' => $bforward1, 
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

        $formattedStartDate = $startDate ? date('M. d, Y', strtotime($startDate)) : '';
        $formattedEndDate = $endDate ? date('M. d, Y', strtotime($endDate)) : '';

        $cond = ($categoriesId == 'All') ? '!=' : '=';
        $allOffice = ($officeId == 'All') ? '!=' : '=';

        $propId = ($categoriesId == 'All') ? $propId = '0' : $propId;
        $selectId = ($categoriesId == 'All') ? $selectId = '0' : $selectId;
        $categoriesId = ($categoriesId == 'All') ? $categoriesId = '0' : $categoriesId;
        $officeId = ($officeId == 'All') ? $officeId = '0' : $officeId;

        $purchase = Purchases::join('offices', 'purchases.office_id', '=', 'offices.id')
            ->join('properties', 'purchases.selected_account_id', '=', 'properties.id')
            ->join('units', 'purchases.unit_id', '=', 'units.id')
            ->join('items', 'purchases.item_id', '=', 'items.id')
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
            
        $purchase1 = Purchases::join('offices', 'purchases.office_id', '=', 'offices.id')
            ->join('properties', 'purchases.selected_account_id', '=', 'properties.id')
            ->join('units', 'purchases.unit_id', '=', 'units.id')
            ->join('items', 'purchases.item_id', '=', 'items.id')
            ->where('purchases.office_id', $allOffice, $officeId)
            ->where('purchases.properties_id', $propertiesId)
            ->where('purchases.categories_id', $cond, $categoriesId)
            ->where('purchases.property_id', $cond, $propId)
            ->where('purchases.selected_account_id', $cond, $selectId)
            ->where(function ($query) use ($startDate) {
                if ($startDate) {
                    $query->where('purchases.date_acquired', '<', $startDate);
                }
            })
            ->get();


        $purchase2 = Purchases::join('offices', 'purchases.office_id', '=', 'offices.id')
            ->join('properties', 'purchases.selected_account_id', '=', 'properties.id')
            ->join('units', 'purchases.unit_id', '=', 'units.id')
            ->join('items', 'purchases.item_id', '=', 'items.id')
            ->where('purchases.office_id', $allOffice, $officeId)
            ->where('purchases.properties_id', $propertiesId)
            ->where('purchases.categories_id', $cond, $categoriesId)
            ->where('purchases.property_id', $cond, $propId)
            ->where('purchases.selected_account_id', $cond, $selectId)
            ->where(function ($query) use ($endDate) {
                if ($endDate) {
                    $query->where('purchases.date_acquired', '>', $endDate);
                }
            })
            ->get();

        $bforward = $purchase1->sum(function ($purchase) {
            return str_replace(',', '', $purchase->total_cost);
        });

        // Ensure $purchase2 is not null or empty before attempting to use it
        $bforward1 = $purchase2->isEmpty() ? 0 : $purchase2->sum(function ($purchase) {
            return str_replace(',', '', $purchase->total_cost);
        });



        $data = [
            'purchase' => $purchase,
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
            'selectedPropertyId' => $selectId,
            'category_id' => $purchase,
            'bforward' => $bforward, 
            'bforward1' => $bforward1, 
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
         
        $id = $request->person_accnt;
        $itemId = $request->item_id;
        $pAccountable = $request->pAccountable;

        $selectedItem = Item::whereIn('id', $itemId)->get();
        $condAccnt = ($pAccountable == 'accountable') ? 'purchases.person_accnt' : 'purchases.office_id';

        if($pAccountable == 'accountable'){
            $relatedItems = Item::join('purchases', 'items.id', '=', 'purchases.item_id')
                ->join('units', 'purchases.unit_id', '=', 'units.id')
                ->join('offices', 'purchases.office_id', '=', 'offices.id')
                ->join('accountable', 'purchases.person_accnt', '=', 'accountable.id')
                ->select('purchases.*', 'items.*', 'offices.*', 'items.id as itemid', 'units.*', 'accountable.person_accnt')
                ->whereIn('purchases.id', $itemId)
                ->where($condAccnt, $id)
                ->get();
        }
        else{
         $relatedItems = Purchases::join('items', 'purchases.item_id', '=', 'items.id')
            ->join('units', 'purchases.unit_id', '=', 'units.id')
            ->join('offices', 'purchases.office_id', '=', 'offices.id')
            ->select('purchases.*', 'items.*', 'offices.*', 'offices.id as oid', 'items.id as itemid', 'units.*', 'offices.office_abbr', 'offices.office_officer')
            ->whereIn('purchases.id', $itemId)
            ->where($condAccnt, $id)
            ->get();
        }

        $data = [
            'selectedItem' => $selectedItem,
            'relatedItems' => $relatedItems,
        ];

        $pdf = PDF::loadView('reports.ics_option_reportsGen', $data)->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }

    public function icsgenOption(Request $request) {
        $id = $request->id;
        $type = $request->type;
        $pAccountable = $request->pAccountable;

        if ($type == 'campus') {
            $userAccountable = Accountable::where('off_id', $id)
                ->select('person_accnt', 'id')
                ->get();

            $officeAccountable = Office::where('id', $id)
                ->select('office_officer', 'id')
                ->get();

            $options = "";
            foreach ($userAccountable as $accnt) {
                $options .= "<option value='".$accnt->id."' data-person-cat='accountable' data-account-id='".$accnt->id."'>".$accnt->person_accnt."</option>";
            }
            foreach ($officeAccountable as $officeAccount) {
                $options .= "<option value='".$officeAccount->id."' data-person-cat='officeAccountable' data-account-id='".$officeAccount->id."'>".$officeAccount->office_officer."- Office Head</option>";
            }


        } else {
            if($pAccountable == 'officeAccountable'){
                $itempar = Purchases::join('items', 'items.id', '=', 'purchases.item_id')
                ->select('purchases.*', 'items.*', 'purchases.id as pid')
                ->where('office_id', $id)
                ->where('purchases.properties_id', '=', [1, 2])
                ->get();
            }else{
                 $itempar = Purchases::where('person_accnt', $id)
                ->join('items', 'items.id', '=', 'purchases.item_id')
                ->select('purchases.*', 'items.*', 'purchases.id as pid')
                ->where('purchases.properties_id', '=', [1, 2])
                ->get();
            }

            $options = "";
            foreach ($itempar as $parItem) {
                $options .= "<option value='".$parItem->pid."'>".$parItem->item_name.' '.$parItem->item_descrip."</option>";
            }
        }

        return response()->json([
            "options" => $options,
        ]);
    }

    public function parOption() {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        
        return view('reports.par_option', compact('setting', 'office'));
    }

    public function parOptionReportGen(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
         
        $id = $request->person_accnt;
        $itemId = $request->item_id;
        $pAccountable = $request->pAccountable;

        $selectedItem = Item::whereIn('id', $itemId)->get();
        $condAccnt = ($pAccountable == 'accountable') ? 'purchases.person_accnt' : 'purchases.office_id';

        if($pAccountable == 'accountable'){
            $relatedItems = Item::join('purchases', 'items.id', '=', 'purchases.item_id')
                ->join('units', 'purchases.unit_id', '=', 'units.id')
                ->join('offices', 'purchases.office_id', '=', 'offices.id')
                ->join('accountable', 'purchases.person_accnt', '=', 'accountable.id')
                ->select('purchases.*', 'items.*', 'offices.*', 'items.id as itemid', 'units.*', 'accountable.person_accnt')
                ->whereIn('purchases.id', $itemId)
                ->where($condAccnt, $id)
                ->get();
        }
        else{
         $relatedItems = Purchases::join('items', 'purchases.item_id', '=', 'items.id')
            ->join('units', 'purchases.unit_id', '=', 'units.id')
            ->join('offices', 'purchases.office_id', '=', 'offices.id')
            ->select('purchases.*', 'items.*', 'offices.*', 'offices.id as oid', 'items.id as itemid', 'units.*', 'offices.office_abbr', 'offices.office_officer')
            ->whereIn('purchases.id', $itemId)
            ->where($condAccnt, $id)
            ->get();
        }

        $data = [
            'selectedItem' => $selectedItem,
            'relatedItems' => $relatedItems,
        ];

        $pdf = PDF::loadView('reports.par_option_reportsGen', $data)->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }

    public function genOption(Request $request) {
        $id = $request->id;
        $type = $request->type;
        $pAccountable = $request->pAccountable;

        if ($type == 'campus') {
            $userAccountable = Accountable::where('off_id', $id)
                ->select('person_accnt', 'id')
                ->get();

            $officeAccountable = Office::where('id', $id)
                ->select('office_officer', 'id')
                ->get();

            $options = "";
            foreach ($userAccountable as $accnt) {
                $options .= "<option value='".$accnt->id."' data-person-cat='accountable' data-account-id='".$accnt->id."'>".$accnt->person_accnt."</option>";
            }
            foreach ($officeAccountable as $officeAccount) {
                $options .= "<option value='".$officeAccount->id."' data-person-cat='officeAccountable' data-account-id='".$officeAccount->id."'>".$officeAccount->office_officer."- Office Head</option>";
            }


        } else {
            if($pAccountable == 'officeAccountable'){
                $itempar = Purchases::join('items', 'items.id', '=', 'purchases.item_id')
                ->select('purchases.*', 'items.*', 'purchases.id as pid')
                ->where('office_id', $id)
                ->where('purchases.properties_id', '=', '3')
                ->get();
            }else{
                 $itempar = Purchases::where('person_accnt', $id)
                ->join('items', 'items.id', '=', 'purchases.item_id')
                ->select('purchases.*', 'items.*', 'purchases.id as pid')
                ->where('purchases.properties_id', '=', '3')
                ->get();
            }

            $options = "";
            foreach ($itempar as $parItem) {
                $options .= "<option value='".$parItem->pid."'>".$parItem->item_name.' '.$parItem->item_descrip."</option>";
            }
        }

        return response()->json([
            "options" => $options,
        ]);
    }

}

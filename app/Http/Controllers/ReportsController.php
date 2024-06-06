<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Inventory;
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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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

        $purchase = Inventory::join('offices', 'inventories.office_id', '=', 'offices.id')
            ->join('properties', 'inventories.selected_account_id', '=', 'properties.id')
            ->join('units', 'inventories.unit_id', '=', 'units.id')
            ->join('items', 'inventories.item_id', '=', 'items.id')
            ->where(function ($query) use ($officeId, $allOffice, $cond) {
                if ($officeId == 1) {
                    $query->whereNotIn('inventories.office_id', [2, 5, 6, 7, 8, 12, 13, 14, 15, 16, 17]);
                } else {
                    $query->where('inventories.office_id', $allOffice, $officeId);
                }
            })
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

        $purchase1 = Inventory::join('offices', 'inventories.office_id', '=', 'offices.id')
            ->join('properties', 'inventories.selected_account_id', '=', 'properties.id')
            ->join('units', 'inventories.unit_id', '=', 'units.id')
            ->join('items', 'inventories.item_id', '=', 'items.id')
            ->where(function ($query) use ($officeId, $allOffice, $cond) {
                if ($officeId == 1) {
                    $query->whereNotIn('inventories.office_id', [2, 5, 6, 7, 8, 12, 13, 14, 15, 16, 17]);
                } else {
                    $query->where('inventories.office_id', $allOffice, $officeId);
                }
            })
            ->where('inventories.properties_id', $propertiesId)
            ->where('inventories.categories_id', $cond, $categoriesId)
            ->where('inventories.property_id', $cond, $propId)
            ->where('inventories.selected_account_id', $cond, $selectId)
            ->where(function ($query) use ($startDate) {
                if ($startDate) {
                    $query->where('inventories.date_acquired', '<', $startDate);
                }
            })
            ->get();


        $purchase2 = Inventory::join('offices', 'inventories.office_id', '=', 'offices.id')
            ->join('properties', 'inventories.selected_account_id', '=', 'properties.id')
            ->join('units', 'inventories.unit_id', '=', 'units.id')
            ->join('items', 'inventories.item_id', '=', 'items.id')
            // ->where('inventories.office_id', $allOffice, $officeId)
            ->where(function ($query) use ($officeId, $allOffice, $cond) {
                if ($officeId == 1) {
                    $query->whereNotIn('inventories.office_id', [2, 5, 6, 7, 8, 12, 13, 14, 15, 16, 17]);
                } else {
                    $query->where('inventories.office_id', $cond, $allOffice);
                }
            })
            ->where('inventories.properties_id', $propertiesId)
            ->where('inventories.categories_id', $cond, $categoriesId)
            ->where('inventories.property_id', $cond, $propId)
            ->where('inventories.selected_account_id', $cond, $selectId)
            ->where(function ($query) use ($endDate) {
                if ($endDate) {
                    $query->where('inventories.date_acquired', '>', $endDate);
                }
            })
            ->get();

        $bforward = $purchase1->sum(function ($purchase1) {
            return str_replace(',', '', $purchase1->total_cost);
        });

        // Ensure $purchase2 is not null or empty before attempting to use it
        $bforward1 = $purchase2->isEmpty() ? 0 : $purchase2->sum(function ($purchase) {
            return str_replace(',', '', $purchase->total_cost);
        });

        $countBforward  = 0;
        $countBforward1 = 0;

        foreach($purchase1 as $purchaseData){
            if (is_numeric(str_replace(',', '', $purchaseData->qty))){
                $countBforward += (float) str_replace(',', '', $purchaseData->qty); 
            }
        }

        foreach($purchase as $purchaseData1){
            if (is_numeric(str_replace(',', '', $purchaseData1->qty))){
                $countBforward1 += (float) str_replace(',', '', $purchaseData1->qty);
            }
        }

        $data = [
            'purchase' => $purchase,
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
            'selectedPropertyId' => $selectId,
            'category_id' => $purchase,
            'bforward' => $bforward, 
            'bforward1' => $bforward1, 
            'countBforward' => $countBforward,
        ];

        if($request->file_type == "PDF"){
            $pdf = PDF::loadView('reports.rpcppe_option_reportsGen', $data)->setPaper('Legal', 'landscape');
            return $pdf->stream();
        }else {
            $filePath = public_path('Forms/RPCPPE Reports.xlsx');
            
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);

            $sheet = $spreadsheet->getActiveSheet();
            
            $row = 10;
            $overallTotal = 0;

            $sheetC3 = '';

            $sheetC3 = ($request->query('categories_id') != "All")  ? isset($purchase->first()->account_title_abbr) ? $purchase->first()->account_title_abbr : '' : 'All';
        
    
            
            $sheetC5 = 'As at ' . $formattedStartDate . ' to ' . $formattedEndDate;
            
            $sheet->mergeCells('C3:H3');
            $sheet->mergeCells('C5:H5');
            $sheet->setCellValue('C3', $sheetC3);
            $sheet->setCellValue('C4', '(Type of Property, Plant and Equipment)');
            $sheet->setCellValue('C5', $sheetC5);
            $sheet->setCellValue('F9', $countBforward);
            $sheet->mergeCells('G9:L9');
            $sheet->setCellValue('G9', number_format($bforward, 2));
            
            foreach ($purchase as $pur) {
                
                if (is_numeric(str_replace(',', '', $pur->total_cost))){
                    $overallTotal += str_replace(',', '', $pur->total_cost); 
                }

                $sheet->setCellValue('A' . $row, $pur->item_name);
                $sheet->setCellValue('B' . $row, $pur->item_descrip);
                $sheet->setCellValue('C' . $row, $pur->property_no_generated);
                $sheet->setCellValue('D' . $row, $pur->unit_name);
                $sheet->setCellValue('E' . $row, $pur->item_cost);
                $sheet->setCellValue('F' . $row, $pur->qty);
                $sheet->setCellValue('G' . $row, $pur->total_cost);
                $sheet->setCellValue('H' . $row, '');
                $sheet->setCellValue('I' . $row, $pur->qty);
                $sheet->setCellValue('J' . $row, '');
                $sheet->setCellValue('K' . $row, $pur->remarks);
                $sheet->setCellValue('L' . $row, $pur->office_name);
            
                $style = $sheet->getStyle('A' . $row . ':L' . $row);
                $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
                $row++;

            }
            
            $footerRow = $row;

            $rows = [
                ['label' => 'Total' . $countBforward1, 'value' => $overallTotal],
                ['label' => 'Grand Total' . $countBforward + $countBforward1, 'value' => $overallTotal + $bforward + $bforward1],
            ];
            
            foreach ($rows as $rowData) {
                $label = $rowData['label'];
                $value = $rowData['value'];
            
                $rangeAtoF = 'A' . $footerRow . ':F' . $footerRow;
                $sheet->mergeCells($rangeAtoF);
                $sheet->setCellValue('A' . $footerRow, $label)
                ->getStyle('A' . $footerRow)
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        
                $rangeGtoL = 'G' . $footerRow . ':L' . $footerRow;
                $sheet->mergeCells($rangeGtoL);
                $sheet->setCellValue('G' . $footerRow, number_format($value, 2));
            
                $footerStyleAtoF = $sheet->getStyle($rangeAtoF);
                $footerStyleAtoF->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $footerStyleAtoF->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
                $footerStyleGtoL = $sheet->getStyle($rangeGtoL);
                $footerStyleGtoL->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $footerStyleGtoL->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
                $footerRow++;
            }

            $footerRow = $row + 2;

            $rows = [
                ['Certified Correct by:', 'Approved by:', 'Verify by:'],
                ['', '', ''],
                ['MA. SOCORRO T. LLAMAS', 'ALADINO C. MORACA, Ph.D.', 'JERENIAS G. AGUI'],
                ['Administrative Officer V/Supply Officer designate', 'SUC President', 'State Auditor IV'],
            ];
            
            $rows = [
                ['Certified Correct by:', 'Approved by:', 'Verify by:'],
                ['', '', ''],
                ['MA. SOCORRO T. LLAMAS', 'ALADINO C. MORACA, Ph.D.', 'JERENIAS G. AGUI'],
                ['Administrative Officer V/Supply Officer designate', 'SUC President', 'State Auditor IV'],
            ];
            
            foreach ($rows as $rowData) {
                $label = $rowData[0];
                $value = $rowData[1];
            
                $rangeAtoB = 'A' . $footerRow . ':B' . $footerRow;
                $sheet->mergeCells($rangeAtoB);
                $sheet->setCellValue('A' . $footerRow, $label);
            
                $rangeCtoG = 'C' . $footerRow . ':G' . $footerRow;
                $sheet->mergeCells($rangeCtoG);
            
                $sheet->setCellValue('C' . $footerRow, is_numeric($value) ? number_format((float)$value, 2) : $value);
            
                $rangeHtoL = 'H' . $footerRow . ':L' . $footerRow;
                $sheet->mergeCells($rangeHtoL);
            
                $sheet->setCellValue('H' . $footerRow, is_numeric($value) ? number_format((float)$value, 2) : $value);
            
                $footerStyleAtoB = $sheet->getStyle($rangeAtoB);
                $footerStyleAtoB->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
                $footerStyleCtoG = $sheet->getStyle($rangeCtoG);
                $footerStyleCtoG->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
                $footerStyleHtoL = $sheet->getStyle($rangeHtoL);
                $footerStyleHtoL->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
                $footerRow++;
            }

            $randomFilename = 'RPCPPE_Reports_' . uniqid() . '.xlsx';

            $copyFilename = 'RPCPPE_Reports_' . date('dmYHis') . '.xlsx';
            $copyPath = public_path("Downloaded Form/" . $copyFilename);
            
            $writer = new Xlsx($spreadsheet);
            $writer->save($copyPath);
            
            return redirect()->back()->with('download', $copyFilename)->with('successcopy', 'File copied successfully!');
            
        }
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

        $officeId = $request->office_id;
        $propertiesId = $request->properties_id;
        $propId = $request->property_id;
        $categoriesId = $request->categories_id;
        $selectId = $request->selected_account_id;
        $startDate = $request->start_date_acquired;
        $endDate = $request->end_date_acquired;

        $condProperties = ($propertiesId == 'All') ? '!=' : '=';
        $condpropId = ($propId == 'All') ? '!=' : '=';
        $condCategories = ($categoriesId == 'All') ? '!=' : '=';
        $condselectId = ($selectId == 'All') ? '!=' : '=';
        
        $propertiesId = ($condProperties == 'All') ? '0' : $propertiesId;
        $categoriesId = ($condCategories == 'All') ? '0' : $categoriesId;
        $selectId = ($condselectId == 'All') ? '0' : $selectId;
        $propId = ($condpropId == 'All') ? '0' : $propId;

        // dd($propertiesId);
        
        $allOffice = ($officeId == 'All') ? '!=' : '=';
        $officeId = ($officeId == 'All') ? '0' : $officeId;
        
        $formattedStartDate = $startDate ? date('M. d, Y', strtotime($startDate)) : '';
        $formattedEndDate = $endDate ? date('M. d, Y', strtotime($endDate)) : '';
        
        $purchase = Inventory::join('offices', 'inventories.office_id', '=', 'offices.id')
            ->join('properties', 'inventories.selected_account_id', '=', 'properties.id')
            ->join('units', 'inventories.unit_id', '=', 'units.id')
            ->join('items', 'inventories.item_id', '=', 'items.id')
            ->where(function ($query) use ($officeId, $allOffice) {
                if ($officeId == 1) {
                    $query->whereNotIn('inventories.office_id', [2, 5, 6, 7, 8, 12, 13, 14, 15, 16, 17]);
                } else {
                    $query->where('inventories.office_id', $allOffice, $officeId);
                }
            })
            ->where(function ($query) use ($condProperties, $propertiesId) {
                if (!empty($propertiesId)) {
                    $query->where('inventories.properties_id', $condProperties, $propertiesId);
                }
            })
            ->where(function ($query) use ($condpropId, $propId) {
                if (!empty($propId)) {
                    $query->where('inventories.property_id', $condpropId, $propId);
                }
            })
            ->where('inventories.categories_id', $condCategories, $categoriesId)
            ->where(function ($query) use ($selectId) {
                if (!empty($selectId)) {
                    $query->where('inventories.selected_account_id', $selectId);
                }
            })
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
            
        $purchase1 = Inventory::join('offices', 'inventories.office_id', '=', 'offices.id')
            ->join('properties', 'inventories.selected_account_id', '=', 'properties.id')
            ->join('units', 'inventories.unit_id', '=', 'units.id')
            ->join('items', 'inventories.item_id', '=', 'items.id')
            ->where(function ($query) use ($officeId, $allOffice) {
                if ($officeId == 1) {
                    $query->whereNotIn('inventories.office_id', [2, 5, 6, 7, 8, 12, 13, 14, 15, 16, 17]);
                } else {
                    $query->where('inventories.office_id', $allOffice, $officeId);
                }
            })
            ->where(function ($query) use ($condProperties, $propertiesId) {
                if (!empty($propertiesId)) {
                    $query->where('inventories.properties_id', $condProperties, $propertiesId);
                }
            })
            ->where(function ($query) use ($condpropId, $propId) {
                if (!empty($propId)) {
                    $query->where('inventories.property_id', $condpropId, $propId);
                }
            })
            ->where('inventories.categories_id', $condCategories, $categoriesId)
            ->where(function ($query) use ($selectId) {
                if (!empty($selectId)) {
                    $query->where('inventories.selected_account_id', $selectId);
                }
            })
            ->where(function ($query) use ($startDate) {
                if ($startDate) {
                    $query->where('inventories.date_acquired', '<', $startDate);
                }
            })
            ->get();


        $purchase2 = Inventory::join('offices', 'inventories.office_id', '=', 'offices.id')
            ->join('properties', 'inventories.selected_account_id', '=', 'properties.id')
            ->join('units', 'inventories.unit_id', '=', 'units.id')
            ->join('items', 'inventories.item_id', '=', 'items.id')
            ->where('inventories.office_id', $allOffice, $officeId)
            ->where(function ($query) use ($condProperties, $propertiesId) {
                if (!empty($propertiesId)) {
                    $query->where('inventories.properties_id', $condProperties, $propertiesId);
                }
            })
            ->where(function ($query) use ($condpropId, $propId) {
                if (!empty($propId)) {
                    $query->where('inventories.property_id', $condpropId, $propId);
                }
            })
            ->where('inventories.categories_id', $condCategories, $categoriesId)
            ->where(function ($query) use ($selectId) {
                if (!empty($selectId)) {
                    $query->where('inventories.selected_account_id', $selectId);
                }
            })
            ->where(function ($query) use ($endDate) {
                if ($endDate) {
                    $query->where('inventories.date_acquired', '>', $endDate);
                }
            })
            ->get();

        $bforward = $purchase1->sum(function ($purchase) {
            return str_replace(',', '', $purchase->total_cost);
        });

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

        if($request->file_type == "PDF"){
            $pdf = PDF::loadView('reports.rpcsep_option_reportsGen', $data)->setPaper('Legal', 'landscape');
            return $pdf->stream();
        }else {
            $filePath = public_path('Forms/RPCSEP Reports.xlsx');
            
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            
            $sheet = $spreadsheet->getActiveSheet();
            
            $row = 10;
            $overallTotal = 0;

            $sheetC3 = '';

            if ($request->categories_id === 'All' || empty($categoriesId)) {
                if ($request->categories_id === 'All')
                    $sheetC3 = 'ALL';
                else {
                    $sheetC3 = isset($purchase->first()->account_title_abbr) ? $purchase->first()->account_title_abbr : '';
                }
            } elseif ($purchase->isEmpty()) {
                $sheetC3 = '____________________';
            }
            
            $sheetC5 = 'As at ' . $formattedStartDate . ' to ' . $formattedEndDate;
            
            $sheet->mergeCells('C3:H3');
            $sheet->mergeCells('C5:H5');
            $sheet->setCellValue('C3', $sheetC3);
            $sheet->setCellValue('C4', '(Type of Property, Plant and Equipment)');
            $sheet->setCellValue('C5', $sheetC5);

            $sheet->mergeCells('G9:L9');
            $sheet->setCellValue('G9', number_format($bforward, 2));
            
            foreach ($purchase as $pur) {
                
                if (is_numeric(str_replace(',', '', $pur->total_cost))){
                    $overallTotal += str_replace(',', '', $pur->total_cost); 
                }

                $sheet->setCellValue('A' . $row, $pur->item_name);
                $sheet->setCellValue('B' . $row, $pur->item_descrip);
                $sheet->setCellValue('C' . $row, $pur->property_no_generated);
                $sheet->setCellValue('D' . $row, $pur->unit_name);
                $sheet->setCellValue('E' . $row, $pur->item_cost);
                $sheet->setCellValue('F' . $row, $pur->qty);
                $sheet->setCellValue('G' . $row, $pur->total_cost);
                $sheet->setCellValue('H' . $row, '');
                $sheet->setCellValue('I' . $row, $pur->qty);
                $sheet->setCellValue('J' . $row, '');
                $sheet->setCellValue('K' . $row, $pur->remarks);
                $sheet->setCellValue('L' . $row, $pur->office_name);
            
                $style = $sheet->getStyle('A' . $row . ':L' . $row);
                $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
                $row++;

            }
            
            $footerRow = $row;

            $rows = [
                ['label' => 'Total', 'value' => $overallTotal],
                ['label' => 'Grand Total', 'value' => $overallTotal + $bforward + $bforward1],
            ];
            
            foreach ($rows as $rowData) {
                $label = $rowData['label'];
                $value = $rowData['value'];
            
                $rangeAtoF = 'A' . $footerRow . ':F' . $footerRow;
                $sheet->mergeCells($rangeAtoF);
                $sheet->setCellValue('A' . $footerRow, $label)
                ->getStyle('A' . $footerRow)
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        
                $rangeGtoL = 'G' . $footerRow . ':L' . $footerRow;
                $sheet->mergeCells($rangeGtoL);
                $sheet->setCellValue('G' . $footerRow, number_format($value, 2));
            
                $footerStyleAtoF = $sheet->getStyle($rangeAtoF);
                $footerStyleAtoF->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $footerStyleAtoF->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
                $footerStyleGtoL = $sheet->getStyle($rangeGtoL);
                $footerStyleGtoL->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $footerStyleGtoL->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
                $footerRow++;
            }

            $footerRow = $row + 2;

            $rows = [
                ['Certified Correct by:', 'Approved by:', 'Verify by:'],
                ['', '', ''],
                ['MA. SOCORRO T. LLAMAS', 'ALADINO C. MORACA, Ph.D.', ''],
                ['Administrative Officer IV/Supply Officer designate', 'SUC President', 'Signature over Printed Name of COA Representative'],
            ];
            
            $rows = [
                ['Certified Correct by:', 'Approved by:', 'Verify by:'],
                ['', '', ''],
                ['MA. SOCORRO T. LLAMAS', 'ALADINO C. MORACA, Ph.D.', ''],
                ['Administrative Officer IV/Supply Officer designate', 'SUC President', 'Signature over Printed Name of COA Representative'],
            ];
            
            foreach ($rows as $rowData) {
                $label = $rowData[0];
                $value = $rowData[1];
            
                $rangeAtoB = 'A' . $footerRow . ':B' . $footerRow;
                $sheet->mergeCells($rangeAtoB);
                $sheet->setCellValue('A' . $footerRow, $label);
            
                $rangeCtoG = 'C' . $footerRow . ':G' . $footerRow;
                $sheet->mergeCells($rangeCtoG);
            
                $sheet->setCellValue('C' . $footerRow, is_numeric($value) ? number_format((float)$value, 2) : $value);
            
                $rangeHtoL = 'H' . $footerRow . ':L' . $footerRow;
                $sheet->mergeCells($rangeHtoL);
            
                $sheet->setCellValue('H' . $footerRow, is_numeric($value) ? number_format((float)$value, 2) : $value);
            
                $footerStyleAtoB = $sheet->getStyle($rangeAtoB);
                $footerStyleAtoB->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
                $footerStyleCtoG = $sheet->getStyle($rangeCtoG);
                $footerStyleCtoG->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
                $footerStyleHtoL = $sheet->getStyle($rangeHtoL);
                $footerStyleHtoL->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
                $footerRow++;
            }

            $randomFilename = 'RPCPPE_Reports_' . uniqid() . '.xlsx';

            $copyFilename = 'RPCPPE_Reports_' . date('dmYHis') . '.xlsx';
            $copyPath = public_path("Downloaded Form/" . $copyFilename);
            
            $writer = new Xlsx($spreadsheet);
            $writer->save($copyPath);
            
            return redirect()->back()->with('download', $copyFilename)->with('successcopy', 'File copied successfully!');
            
        }
    }

    public function unserviceForm(){
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $property = Property::all();
        $category = Category::all();

        return view('reports.unserviceable_form', compact('setting', 'office', 'property', 'category'));
    }

    public function unserviceReport(Request $request){
        $setting = Setting::firstOrNew(['id' => 1]);
    
        $officeId = $request->office_id;
        $personaccountable = $request->person_accnt;
        $itemId = $request->item_id;
        $pAccountable = $request->pAccountable;
        $categoriesId = $request->categories_id;
        $propId = $request->property_id;
        $startDate = $request->start_date_acquired;
        $endDate = $request->end_date_acquired;
        $selectId = $request->selected_account_id;

        $formattedStartDate = $startDate ? date('M. d, Y', strtotime($startDate)) : '';
        $formattedEndDate = $endDate ? date('M. d, Y', strtotime($endDate)) : '';
        $datereport = $formattedStartDate.'-'.$formattedEndDate;
        $condcategories = ($categoriesId == 'All') ? '!=' : '=';
        $condpropid = ($propId == 'All') ? '!=' : '=';
        $conaccountid = ($selectId == 'All' || $selectId == '') ? '!=' : '=';
        $officecond = ($officeId == 'All') ? '!=' : '=';
    
        $categoriesId = ($categoriesId == 'All') ? '0' : $categoriesId;
        $propId = ($propId == 'All') ? '0' : $propId;
        $selectId = ($selectId == 'All') ? '0' : $selectId;
        $officeId = ($officeId == 'All') ? '0' : $officeId;
    
        $selectedItem = Item::whereIn('id', $itemId)->get();
        $condAccnt = ($pAccountable == 'accountable') ? 'inventories.person_accnt' : 'inventories.office_id';
    
        $unservicequery = Inventory::join('items', 'inventories.item_id', '=', 'items.id')
            ->join('units', 'inventories.unit_id', '=', 'units.id')
            ->join('offices', 'inventories.office_id', '=', 'offices.id')
            ->select('inventories.*', 'items.*', 'offices.*',  'offices.id as oid', 'items.id as itemid', 'units.*', 'offices.office_abbr', 'offices.office_officer');
    
        if ($itemId[0] != 'All' && !in_array('All', $itemId)) {
            $unservicequery->whereIn('inventories.id', $itemId);
        }
    
        if ($pAccountable == 'accountable') {
            $unservicequery->where($condAccnt, $personaccountable);
        } else {
            $unservicequery->where('inventories.office_id', $officecond, $officeId);
        }
    
        $unservitems = $unservicequery
            ->where('inventories.categories_id', $condcategories, $categoriesId)
            ->where('inventories.property_id', $condpropid, $propId)
            ->where('inventories.selected_account_id', $conaccountid, $selectId)
            ->where(function ($query) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $query->whereBetween('inventories.date_acquired', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                } elseif ($startDate) {
                    $query->where('inventories.date_acquired', '>=', $startDate . ' 00:00:00');
                } elseif ($endDate) {
                    $query->where('inventories.date_acquired', '<=', $endDate . ' 23:59:59');
                }
            })
            ->where('inventories.remarks', 'Unserviceable')
            ->get();
    // dd($unservitems);
        if($unservitems->isNotEmpty()){
            $pdf = PDF::loadView('reports.unserviceable_report', compact('selectedItem', 'unservitems', 'itemId', 'pAccountable', 'datereport'))->setPaper('Legal', 'landscape');
            return $pdf->stream();
        }else{
            return redirect()->back()->with('error', 'No Item Found Belong to this End User!');
        }
    }

    public function icsOption() {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $property = Property::all();
        $category = Category::all();
        $purchase = Inventory::join('accountable', 'inventories.person_accnt', '=', 'accountable.id')
                    ->select('inventories.*', 'accountable.person_accnt')
                    ->get();
        return view('reports.ics_option', compact('setting', 'office', 'property', 'category', 'purchase'));
    }

    public function icsOptionReportGen(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
    
        $officeId = $request->office_id;
        $personaccountable = $request->person_accnt;
        $itemId = $request->item_id;
        $pAccountable = $request->pAccountable;
        $categoriesId = $request->categories_id;
        $propId = $request->property_id;
        $startDate = $request->start_date_acquired;
        $endDate = $request->end_date_acquired;
        $selectId = $request->selected_account_id;

        $formattedStartDate = $startDate ? date('M. d, Y', strtotime($startDate)) : '';
        $formattedEndDate = $endDate ? date('M. d, Y', strtotime($endDate)) : '';
        $datereport = $formattedStartDate.'-'.$formattedEndDate;
        $condcategories = ($categoriesId == 'All') ? '!=' : '=';
        $condpropid = ($propId == 'All') ? '!=' : '=';
        $conaccountid = ($selectId == 'All' || $selectId == '') ? '!=' : '=';
        $officecond = ($officeId == 'All') ? '!=' : '=';
    
        $categoriesId = ($categoriesId == 'All') ? '0' : $categoriesId;
        $propId = ($propId == 'All') ? '0' : $propId;
        $selectId = ($selectId == 'All') ? '0' : $selectId;
        $officeId = ($officeId == 'All') ? '0' : $officeId;
    
        $selectedItem = Item::whereIn('id', $itemId)->get();
        $condAccnt = ($pAccountable == 'accountable') ? 'inventories.person_accnt' : 'inventories.office_id';
    
        $icsitemquery = Inventory::join('items', 'inventories.item_id', '=', 'items.id')
            ->join('units', 'inventories.unit_id', '=', 'units.id')
            ->join('offices', 'inventories.office_id', '=', 'offices.id')
            ->select('inventories.*', 'items.*', 'offices.*', 'offices.id as oid', 'items.id as itemid', 'units.*', 'offices.office_abbr', 'offices.office_officer');
    
        if ($itemId[0] != 'All' && !in_array('All', $itemId)) {
            $icsitemquery->whereIn('inventories.id', $itemId);
        }
    
        if ($pAccountable == 'accountable') {
            $icsitemquery->where($condAccnt, $personaccountable);
        } else {
            $icsitemquery->where('inventories.office_id', $officecond, $officeId);
        }
    
        $icsitems = $icsitemquery
            ->where('inventories.properties_id', '!=', 3)
            ->where('inventories.categories_id', $condcategories, $categoriesId)
            ->where('inventories.property_id', $condpropid, $propId)
            ->where('inventories.selected_account_id', $conaccountid, $selectId)
            ->where(function ($query) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $query->whereBetween('inventories.date_acquired', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                } elseif ($startDate) {
                    $query->where('inventories.date_acquired', '>=', $startDate . ' 00:00:00');
                } elseif ($endDate) {
                    $query->where('inventories.date_acquired', '<=', $endDate . ' 23:59:59');
                }
            })
            ->get();
    
        if($icsitems->isNotEmpty()){
            $pdf = PDF::loadView('reports.ics_option_reportsGen', compact('selectedItem', 'icsitems', 'itemId', 'pAccountable', 'datereport'))->setPaper('Legal', 'portrait');
            return $pdf->stream();
        }else{
            return redirect()->back()->with('error', 'No Item Found Belong to this End User!');
        }
    }

    public function parOption() {
        $setting = Setting::firstOrNew(['id' => 1]);
        $office = Office::all();
        $property = Property::all();
        $category = Category::all();
        return view('reports.par_option', compact('setting', 'office', 'property', 'category'));
    }

    public function parOptionReportGen(Request $request) {
        $setting = Setting::firstOrNew(['id' => 1]);
    
        $officeId = $request->office_id;
        $personaccountable = $request->person_accnt;
        $itemId = $request->item_id;
        $pAccountable = $request->pAccountable;
        $categoriesId = $request->categories_id;
        $propId = $request->property_id;
        $startDate = $request->start_date_acquired;
        $endDate = $request->end_date_acquired;
        $selectId = $request->selected_account_id;

        $formattedStartDate = $startDate ? date('M. d, Y', strtotime($startDate)) : '';
        $formattedEndDate = $endDate ? date('M. d, Y', strtotime($endDate)) : '';
        $datereport = $formattedStartDate.'-'.$formattedEndDate;
        $condcategories = ($categoriesId == 'All') ? '!=' : '=';
        $condpropid = ($propId == 'All') ? '!=' : '=';
        $conaccountid = ($selectId == 'All' || $selectId == '') ? '!=' : '=';
        $officecond = ($officeId == 'All') ? '!=' : '=';
    
        $categoriesId = ($categoriesId == 'All') ? '0' : $categoriesId;
        $propId = ($propId == 'All') ? '0' : $propId;
        $selectId = ($selectId == 'All') ? '0' : $selectId;
        $officeId = ($officeId == 'All') ? '0' : $officeId;
    
        $selectedItem = Item::whereIn('id', $itemId)->get();
        $condAccnt = ($pAccountable == 'accountable') ? 'inventories.person_accnt' : 'inventories.office_id';
    
        $paritemquery = Inventory::join('items', 'inventories.item_id', '=', 'items.id')
            ->join('units', 'inventories.unit_id', '=', 'units.id')
            ->join('offices', 'inventories.office_id', '=', 'offices.id')
            ->select('inventories.*', 'items.*', 'offices.*', 'offices.id as oid', 'items.id as itemid', 'units.*', 'offices.office_abbr', 'offices.office_officer');
    
        if ($itemId[0] != 'All' && !in_array('All', $itemId)) {
            $paritemquery->whereIn('inventories.id', $itemId);
        }
    
        if ($pAccountable == 'accountable') {
            $paritemquery->where($condAccnt, $personaccountable);
        } else {
            $paritemquery->where('inventories.office_id', $officecond, $officeId);
        }
    
        $paritems = $paritemquery
            ->where('inventories.properties_id', 3)
            ->where('inventories.categories_id', $condcategories, $categoriesId)
            ->where('inventories.property_id', $condpropid, $propId)
            ->where('inventories.selected_account_id', $conaccountid, $selectId)
            ->where(function ($query) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $query->whereBetween('inventories.date_acquired', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                } elseif ($startDate) {
                    $query->where('inventories.date_acquired', '>=', $startDate . ' 00:00:00');
                } elseif ($endDate) {
                    $query->where('inventories.date_acquired', '<=', $endDate . ' 23:59:59');
                }
            })
            ->get();

        if($paritems->isNotEmpty()){
            $pdf = PDF::loadView('reports.par_option_reportsGen', compact('selectedItem', 'paritems', 'itemId', 'pAccountable', 'datereport'))->setPaper('Legal', 'portrait');
            return $pdf->stream();
        }else{
            return redirect()->back()->with('error', 'No Item Found Belong to this End User!');
        }
        
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
                $itempar = Inventory::join('items', 'items.id', '=', 'inventories.item_id')
                ->select('inventories.*', 'items.*', 'inventories.id as pid')
                ->where('office_id', $id)
                ->get();
            }else{
                 $itempar = Inventory::where('person_accnt', $id)
                ->join('items', 'items.id', '=', 'inventories.item_id')
                ->select('inventories.*', 'items.*', 'inventories.id as pid')
                ->get();
            }

            $options = "";
            $options .= "<option>All</option>";
            foreach ($itempar as $parItem) {
                $options .= "<option value='".$parItem->pid."'>".$parItem->item_name.' '.$parItem->item_descrip."</option>";
            }
        }

        return response()->json([
            "options" => $options,
        ]);
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
                $itempar = Inventory::join('items', 'items.id', '=', 'inventories.item_id')
                ->select('inventories.*', 'items.*', 'inventories.id as pid')
                ->where('office_id', $id)
                ->where('inventories.properties_id', '!=', 3)
                ->get();
            }else{
                 $itempar = Inventory::where('person_accnt', $id)
                ->join('items', 'items.id', '=', 'inventories.item_id')
                ->select('inventories.*', 'items.*', 'inventories.id as pid')
                ->where('inventories.properties_id', '!=', 3)
                ->get();
            }

            $options = "";
            $options .= "<option>All</option>";
            foreach ($itempar as $parItem) {
                $options .= "<option value='".$parItem->pid."'>".$parItem->item_name.' '.$parItem->item_descrip."</option>";
            }
        }

        return response()->json([
            "options" => $options,
        ]);
    }

    public function genOptionUnserv(Request $request) {
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
                $itempar = Inventory::join('items', 'items.id', '=', 'inventories.item_id')
                ->select('inventories.*', 'items.*', 'inventories.id as pid')
                ->where('inventories.remarks', 'Unserviceable')
                ->where('office_id', $id)
                ->get();
            }else{
                 $itempar = Inventory::where('person_accnt', $id)
                ->join('items', 'items.id', '=', 'inventories.item_id')
                ->select('inventories.*', 'items.*', 'inventories.id as pid')
                ->where('inventories.remarks', 'Unserviceable')
                ->get();
            }

            $options = "";
            $options .= "<option>All</option>";
            foreach ($itempar as $parItem) {
                $options .= "<option value='".$parItem->pid."'>".$parItem->item_name.' '.$parItem->item_descrip."</option>";
            }
        }

        return response()->json([
            "options" => $options,
        ]);
    }

}

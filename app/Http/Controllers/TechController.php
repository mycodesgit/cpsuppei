<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Setting;
use App\Models\Repair;

class TechController extends Controller
{
   public function repairRead($prop, $issue, $urgency){ 
    $setting = Setting::firstOrNew(['id' => 1]);
      try {
       
         $datas = Inventory::where('property_no_generated', $prop)->first(['id']);
     
         if ($datas) {
             $id = $datas->id;
    
             $insert_issue = new Repair();
             $insert_issue->inv_id = $id;
             $insert_issue->issue = $issue;
             $insert_issue->urgency =$urgency;
             $insert_issue->save();  
     
             session()->flash('success', 'The operation was successful!');
             return redirect()->route('repairProp')->with('success', 'New issue added successful!');

         } else {
             throw new Exception("No inventory data found for the given property number.");
         }
     
     } catch (\Exception $e) {
       
         echo "Error: " . $e->getMessage();
     }
             }
    public function repairProp(){ 

    $setting = Setting::firstOrNew(['id' => 1]);

    try {
       
        $repairs = DB::table('repairs')
        ->join('inventories', 'repairs.inv_id', '=', 'inventories.id') 
        ->select('repairs.*', 'inventories.remarks', 'repairs.status','inventories.property_no_generated','repairs.id as repair_id', 'repairs.inv_id', 'inventories.qty','inventories.id','inventories.item_id','inventories.item_descrip') 
        ->get();
    
          return view('inventories.repairtech', compact('setting','repairs'));
            
    } catch (QueryException $e) {
       
        echo 'Query failed: ' . $e->getMessage();
    } catch (\Exception $e) {
       
        echo 'An error occurred: ' . $e->getMessage();
    }
        }

   public function editEssue($id){

   $setting = Setting::firstOrNew(['id' => 1]);
    try {
        
        $repairs = DB::table('repairs')
            ->join('inventories', 'repairs.inv_id', '=', 'inventories.id') 
            ->select('repairs.*', 'inventories.remarks', 'repairs.status','inventories.property_no_generated', 'repairs.inv_id','repairs.id as repair_id', 'inventories.qty','inventories.id','inventories.item_id','inventories.item_descrip') 
            ->get();
        
            $issues = Repair::find($id);
           
            return view('inventories.repairtech', compact('setting','repairs','issues'));
            
    } catch (QueryException $e) {
       
        echo 'Query failed: ' . $e->getMessage();
    } catch (\Exception $e) {
       
        echo 'An error occurred: ' . $e->getMessage();
    }
        }
    public function issueUpdate(Request $request){
 
    $issue = Repair::findOrFail($request->id);       

    $issue->status = $request->input('status');
    $issue->remarks = $request->input('remarks');
    $issue->urgency = $request->input('urgency');
    $issue->issue = $request->input('issue');

    $issue->save();

    return redirect()->back()->with('success', 'Updated Successfully');

        }
    public function issueInsertion(){
        $setting = Setting::firstOrNew(['id' => 1]);
        try {
             
        $datas = Inventory::where('item_descrip', $prop)->first(['id']);
           
        if ($datas) {
            $id = $datas->id;
            $insert_issue = new Repair();
            $insert_issue->inv_id = $id;
            $insert_issue->issue = $issue;
            $insert_issue->urgency =$urgency;
            $insert_issue->save();  
                session()->flash('success', 'The operation was successful!');
                return redirect()->route('repairProp')->with('success', 'New issue added successful!');
      
               } else {
                   throw new Exception("No inventory data found for the given property number.");
               }
           
           } catch (\Exception $e) {
             
               echo "Error: " . $e->getMessage();
           }

        }
    
}


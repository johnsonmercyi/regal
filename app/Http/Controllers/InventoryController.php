<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use DB;

class InventoryController extends Controller
{
    public function create(School $school){

        $existCat = DB::table('inventory_categories')->where('school_id', $school->id)->get();

        return view('inventory.create', compact('school', 'existCat'));
    }
    
    public function categoryIndex(School $school){

        $existCat = DB::table('inventory_categories')->where('school_id', $school->id)->get();
        return view('inventory.category', compact('school', 'existCat'));
    }
    
    public function sale(School $school){
        $allClass = DB::table('school_classes')->where([
            ['school_id', $school->id],
            ['status', '1'],
            ])->orderBy('level', 'ASC')->get();

        $invent = DB::table('inventory_items')->where('school_id', $school->id)->orderBy('name', 'ASC')->get();
        
        return view('inventory.sale', compact('school', 'allClass', 'invent'));
    }

    public function stock(School $school){


        return view('inventory.stock', compact('school'));
    }

    public function categoryCreate(Request $request){
        $catObj = $request->all();

        // dd($catObj);
        $catCreated = DB::table('inventory_categories')->insert($catObj);

        return response()->json(["created"=>$catCreated]);

    }

    public function itemCreate(Request $request){
        $itemObj = $request->all();

        // dd($itemObj);
        $itemCreated = DB::table('inventory_items')->insert($itemObj);

        return response()->json(["created"=>$itemCreated]);

    }

    public function invoiceStore(Request $request){
        $invoice = $request->all();
        
        $storeTrack = [];
        foreach($invoice as $item){
            /**********CHECK IF QTY IS AVAILABLE**************** */
            $itemQty = DB::table('inventory_items')->select('quantity')->where([
               [ 'school_id', $item['school_id']],
               [ 'id', $item['item_id']],
               ])->first();
            $canSell = $itemQty->quantity > $item['newQuant'];
            $storeTrack[$item['name']] = $canSell;
            if($canSell){
                unset($item['name']);
                $qtyUpdate = DB::table('inventory_items')->where([
                    [ 'school_id', $item['school_id']],
                    [ 'id', $item['item_id']],
                    ])->update(['quantity'=>$item['newQuant']]);
                unset($item['newQuant']);
                $invoiceStored = DB::table('inventory_sales')->insert($item);
            }
        }

        // dd($storeTrack);
        return response()->json(["success"=>$storeTrack]);
    }

    public function reportsIndex(School $school){

        return view('inventory.reports.index', compact('school'));
    }

    public function reportDaily(School $school){
        return view('inventory.reports.daily', compact('school'));
    }

    public function fetchDailyReport(Request $request){
        $dateVal = $request->all();

        $dailySale = DB::table('inventory_sales')->select('item_id', 'inventory_sales.quantity', 'inventory_sales.unit_price', 'inventory_items.name')->
                        leftJoin('inventory_items', 'inventory_items.id', '=', 'inventory_sales.item_id')->
                        where('sale_date', $dateVal["date"])->get();
        // dd($dailySale);
        $soldItemsId = DB::table('inventory_sales')->select('item_id')->where('sale_date', $dateVal["date"])->groupBy('item_id')->get();

        return response()->json(["dailySales"=>$dailySale, "itemsId"=>$soldItemsId]);
    }

    public function stockReport(School $school){
        $allStock = DB::table('inventory_items')->where('school_id', $school->id)->get();

        return view('inventory.reports.stock', compact('school', 'allStock'));
    }
    
    public function invoiceReport(School $school){
        return view('inventory.reports.invoice', compact('school'));
    }

    public function fetchInvoiceReport(Request $request){
        $dateVal = $request->all();

        $invoiceRep = DB::table('inventory_sales')->select('item_id', 'inventory_sales.invoice_no', 'inventory_sales.quantity', 'inventory_sales.unit_price', 'inventory_items.name', 
                    'inventory_sales.student_id', 'students.lastName', 'students.firstName', 'students.otherName', 'inventory_sales.sale_date')->
                        leftJoin('inventory_items', 'inventory_items.id', '=', 'inventory_sales.item_id')->
                        leftJoin('students', 'students.id', '=', 'inventory_sales.student_id')->
                        where('sale_date', $dateVal["date"])->get();
        // dd($dailySale);
        // $soldItemsId = DB::table('inventory_sales')->select('item_id')->where('sale_date', $dateVal["date"])->groupBy('item_id')->get();

        return response()->json(["invoiceRep"=>$invoiceRep]);
    }
}

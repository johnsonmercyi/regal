<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use DB;

class PayrollController extends Controller
{
    public function salaryIndex(Request $request){
        $school = $request->user()->school;

        $staffSal = DB::table('staff')->select('staff.id', 'staff.regNo', 'staff.firstName', 'staff.otherNames', 'staff.lastName', 'staff_salary.basic_salary')
            ->leftJoin('staff_salary', [['staff_salary.staff_id', '=', 'staff.id'],["staff_salary.school_id", '=', 'staff.school_id']])
            ->where([
                "staff.school_id" => $school->id,
                "staff.status" => '1',
            ])->orderBy('staff.lastName', 'ASC')->get();
        
        $staff = DB::table('staff')->select('staff.id', 'staff.regNo', 'staff.firstName', 'staff.otherNames', 'staff.lastName')
            ->where([
                "staff.school_id" => $school->id,
                "staff.status" => '1',
            ])->orderBy('staff.lastName', 'ASC')->get();

        return view('payroll.salary', compact('staffSal', 'school', 'staff'));
    }

    public function storeSalary(Request $request){
        $school = $request->user()->school;

        $salary = $request->all();

        // dd($salary);

        foreach ($salary as $key => $val) {
            DB::table('staff_salary')->updateOrInsert(
                ["staff_id" => $val["staff_id"], "school_id" => $school->id,],
                ["basic_salary" => $val['basic_salary']]
            );
        }

        return response()->json(["success" => true]);
    }
    
    public function payrollView(Request $request, School $school){
        // $school = $request->user()->school;

        
        return view("payroll.payroll", compact('school'));
    }


    public function getStaff(Request $request){
        $school = $request->user()->school;

        $data = $request->all();

        $dateInfo = explode('-', $data['payMonth']);

        $monthExists = DB::table('payroll')->where('school_id', $school->id)
            ->whereYear('pay_date', $dateInfo['0'])
            ->whereMonth('pay_date', $dateInfo['1'])
            ->exists();

        if($monthExists){
            return response()->json(["exists" => $monthExists]);
        }

        $payrollStructure = DB::table('payroll_structure')->where(['school_id' => $school->id, 'status' => '1'])->get();

        $staffSal = DB::table('staff')->select('staff.id', 'staff.regNo', 'staff.firstName', 'staff.otherNames', 'staff.lastName', 'staff_salary.basic_salary')
            ->leftJoin('staff_salary', [['staff_salary.staff_id', '=', 'staff.id'],["staff_salary.school_id", '=', 'staff.school_id']])
            ->where([
                "staff.school_id" => $school->id,
                "staff.status" => '1',
            ])->orderBy('staff.lastName', 'ASC')->get();

        $staff = DB::table('staff')->select('staff.id', 'staff.regNo', 'staff.firstName', 'staff.otherNames', 'staff.lastName')
            ->where([
                "staff.school_id" => $school->id,
                "staff.status" => '1',
            ])->orderBy('staff.lastName', 'ASC')->get();


        return response()->json(["staff" => $staff, "staffSal" => $staffSal, "payrollStructure" => $payrollStructure]);
    }
    
    public function storePayroll(Request $request){
        $data = $request->all();
        $school = $request->user()->school;
        
        $payrollData = $data['payPackArr'];
        $structure_id = $data['structure_id'];

        $dateInfo = explode('-', $payrollData[0]['pay_date']);
        $monthExists = DB::table('payroll')->where('school_id', $school->id)
            ->whereYear('pay_date', $dateInfo['0'])
            ->whereMonth('pay_date', $dateInfo['1'])
            ->exists();

        if($monthExists){
            return response()->json(["exists" => $monthExists]);
        }
        // dd($data);


        DB::beginTransaction();

        try {

            DB::table('payroll_structure_track')->insert([
                "school_id" => $school->id,
                "structure_id" => $structure_id,
                "pay_date" => $payrollData[0]['pay_date']
            ]);
    
            $payroll = DB::table('payroll')->insert($payrollData);
    
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json(["success" => false]);
        }
        

        return response()->json(["success" => $payroll]);
    }
    
    public function reportView(Request $request, School $school){
        
        return view("payroll.report", compact('school'));
    }

    public function reportFetch(Request $request){
        $school = $request->user()->school;

        $data = $request->all();

        $dateInfo = explode('-', $data['payMonth']);

        $structure = [];

        $structure_id = DB::table('payroll_structure_track')->select('structure_id')->where([
                "school_id" => $school->id,
            ])
            ->whereYear('pay_date', $dateInfo['0'])
            ->whereMonth('pay_date', $dateInfo['1'])
            ->first();

        if($structure_id){
            $structure = DB::table('payroll_structure')->where(['school_id' => $school->id, 'structure_id' => $structure_id->structure_id])->get();
        }


        $payroll = DB::table('payroll')->select('payroll.*', 'staff.firstName', 'staff.lastName', 'staff.otherNames')
            ->leftJoin('staff', [['payroll.staff_id', '=', 'staff.id'],["payroll.school_id", '=', 'staff.school_id']])
            ->where('payroll.school_id', $school->id)
            ->whereYear('pay_date', $dateInfo['0'])
            ->whereMonth('pay_date', $dateInfo['1'])
            ->orderBy('staff.lastName', 'ASC')->get();
        // dd($structure_id);
        if($payroll){
            return response()->json(["payroll" => $payroll, "structure" => $structure]);
        }

        return response()->json(["payroll" => $payroll]);
        
    }

    public function structureView(School $school){
        $existStructure = DB::table('payroll_structure')->where('school_id', $school->id)->select('structure_id')->groupBy('structure_id')->get();
        $allStructures = [];
        
        foreach($existStructure as $structure){
            $allStructures[] = DB::table('payroll_structure')->where([
                            ['school_id', $school->id],
                            ['structure_id', $structure->structure_id],
                        ])->orderBy('id', 'ASC')->get()->toArray();
        }

        return view('payroll.structure', compact("allStructures", "school"));
    }

    public function storeStructure(Request $request){
        $school_id = $request->user()->school_id;
        $structurePostData = $request->all();
        // dd($structureData);
        $structureData = [];
        $deActivate = DB::table('payroll_structure')
            ->where('school_id', $school_id)
            ->update(["status" => '0']);
        $laststructure_id = DB::table('payroll_structure')->select('structure_id')->where('school_id', $school_id)->max('structure_id');
        if($laststructure_id == null){
            $laststructure_id = 1;
        } else {
            $laststructure_id += 1;
        }

        foreach($structurePostData as $structure){
            $structure = $structure;
            $structure['structure_id'] = $laststructure_id;
            $structure['school_id'] = $school_id;
            $structureData[] = $structure;
        }
        // dd($structureData);
        $structureInsert = DB::table('payroll_structure')->insert($structureData);

        return response()->json(['success' => $structureInsert]);

    }

    public function activateStructure(Request $request){
        $school_id = $request->user()->school_id;
        $structure_id = $request->all()['structure_id'];

        DB::table('payroll_structure')->where(['school_id' => $school_id, 'status' => '1'])->update(['status' => '0']);

        $activated = DB::table('payroll_structure')
            ->where(['school_id' => $school_id, 'structure_id' => $structure_id])
            ->update(['status' => '1']);

        return response()->json(["success" => $activated]);
    }
}

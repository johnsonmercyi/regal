<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use App\Student;
use DB;

class HostelController extends Controller
{
    public function assignView(School $school){
        return view('hostel.assign', compact('school'));
    }

    public function assignVerify(Request $request){
        $stdId = $request->all();


        $student = Student::find($stdId['stdId']);
        // $school = $student->School;
        $croom = [];
        // dd($student);
        if(!empty($student)){$croom = $student->classroom();}
        return response()->json(['student'=>$student, 'croom'=>$croom]);
    }
}

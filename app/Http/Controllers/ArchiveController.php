<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use DB;
use Illuminate\Support\Facades\Hash;

class ArchiveController extends Controller
{
    
    public function archiveView(School $school){
        $recentDate = strtotime("This Month");
        $yr = date('Y', $recentDate);
        $mont = date('m', $recentDate);

        $students = DB::table('class_students_list')
            ->select('class_students_list.student_id', 'students.firstName', 'students.lastName',
                'students.otherName','students.regNo', 'school_classes.level', 'school_classes.suffix')
            ->where([
                ["class_students_list.school_id" , "=", $school->id],
                ["students.school_id" , "=", $school->id],
                ["school_classes.school_id" , "=", $school->id],
                ["class_students_list.status" , "=", 'Inactive']
            ])
            ->whereYear('class_students_list.updated_at', $yr)
            ->whereMonth('class_students_list.updated_at', '>', $mont)
            ->leftJoin('students', 'class_students_list.student_id', '=', 'students.id')
            ->leftJoin('school_classes', 'class_students_list.class_id', '=', 'school_classes.id')
            ->get();
        
        $allClass = DB::table('school_classes')->where([
            ['school_id', $school->id],
            ['status', '1'],
        ])->get();
        $acadSession = DB::table('academic_sessions')->get();
        
        return view('archive.index', compact('school', 'allClass', 'acadSession'));
    }

    public function searchStudents(Request $request){
        $data = $request->all();
        $nameArr = explode(' ', $data['studentName']);
        $school_id = $request->user()->school_id;

        if(count($nameArr) == 1){

            $students = DB::table('class_students_list')
                ->select('class_students_list.student_id', 'class_students_list.class_id', 'class_students_list.academic_session_id', 
                'class_students_list.status', 'students.firstName', 'students.lastName',
                 'students.otherName', 'students.regNo', 'school_classes.level', 'school_classes.suffix', 'academic_sessions.sessionName')
                ->leftJoin('students', 'class_students_list.student_id', '=', 'students.id')
                ->leftJoin('school_classes', 'school_classes.id', '=', 'class_students_list.class_id')
                ->leftJoin('academic_sessions', 'academic_sessions.id', '=', 'class_students_list.academic_session_id')
                ->where([
                    ['class_students_list.status', '=', 'Inactive'],
                    ['students.firstName', 'like', '%'.$nameArr[0].'%'],
                    ['class_students_list.school_id', '=', $school_id],
                    ['students.school_id', '=', $school_id],
                    ['school_classes.school_id', '=', $school_id],
                ])
                ->orWhere([
                    ['class_students_list.status', '=', 'Inactive'],
                    ['students.lastName', 'like', '%'.$nameArr[0].'%'],
                    ['class_students_list.school_id', '=', $school_id],
                    ['students.school_id', '=', $school_id],
                    ['school_classes.school_id', '=', $school_id],
                ])
                ->orderBy('class_students_list.academic_session_id', 'DESC')
                // ->groupBy('class_students_list.student_id')
                ->get();

        } 
        else
        {
            $students = DB::table('class_students_list')
                ->select('class_students_list.*', 'students.firstName', 'students.lastName',
                 'students.otherName', 'students.regNo', 'school_classes.level', 'school_classes.suffix', 'academic_sessions.sessionName')
                ->leftJoin('students', 'class_students_list.student_id', '=', 'students.id')
                ->leftJoin('school_classes', 'school_classes.id', '=', 'class_students_list.class_id')
                ->leftJoin('academic_sessions', 'academic_sessions.id', '=', 'class_students_list.academic_session_id')
                ->where([
                    ['class_students_list.status', '=', 'Inactive'],
                    ['students.firstName', 'like', '%'.$nameArr[0].'%'],
                    ['students.lastName', 'like', '%'.$nameArr[1].'%'],
                    ['class_students_list.school_id', '=', $school_id],
                    ['students.school_id', '=', $school_id],
                    ['school_classes.school_id', '=', $school_id],
                ])
                ->orWhere([
                    ['class_students_list.status', '=', 'Inactive'],
                    ['students.lastName', 'like', '%'.$nameArr[0].'%'],
                    ['students.firstName', 'like', '%'.$nameArr[1].'%'],
                    ['class_students_list.school_id', '=', $school_id],
                    ['students.school_id', '=', $school_id],
                    ['school_classes.school_id', '=', $school_id],
                ])
                ->orderBy('class_students_list.academic_session_id', 'DESC')
                // ->groupBy('class_students_list.student_id')
                ->get();
        }


        // dd($students);

        return response()->json(["students"=>$students]);
    }


    public function searchStaff(Request $request){
        $data = $request->all();
        $nameArr = explode(' ', $data['staffName']);
        $school_id = $request->user()->school_id;

        if(count($nameArr) == 1){

            $staff = DB::table('staff')
                ->where([
                    ['staff.status', '=', '0'],
                    ['staff.firstName', 'like', '%'.$nameArr[0].'%'],
                    ['staff.school_id', '=', $school_id],
                ])
                ->orWhere([
                    ['staff.status', '=', '0'],
                    ['staff.lastName', 'like', '%'.$nameArr[0].'%'],
                    ['staff.school_id', '=', $school_id],
                ])
                ->get();

        } 
        else
        {
            $staff = DB::table('staff')
                ->where([
                    ['staff.status', '=', '0'],
                    ['staff.firstName', 'like', '%'.$nameArr[0].'%'],
                    ['staff.lastName', 'like', '%'.$nameArr[1].'%'],
                    ['staff.school_id', '=', $school_id],
                ])
                ->orWhere([
                    ['staff.status', '=', '0'],
                    ['staff.lastName', 'like', '%'.$nameArr[0].'%'],
                    ['staff.firstName', 'like', '%'.$nameArr[1].'%'],
                    ['staff.school_id', '=', $school_id],
                ])
                ->get();
        }


        // dd($staff);

        return response()->json(["staff"=>$staff]);
    }


    public function restoreStudent(Request $request){
        $data = $request->all();
        $school_id = $request->user()->school_id;
        $termArr = ['1'=>"first_term", '2'=>"second_term", '3'=>"third_term"];

        // dd($data);
        $restored = DB::table('class_students_list')->updateOrInsert(
            [
                "academic_session_id" => $data["sessionId"],
                "student_id" => $data["studentId"],
                "school_id" => $school_id,
                "class_id" => $data["classId"],
            ],
            [
                $termArr[$data['termId']] => '1',
                "status" => "Active"
            ]
        );

        return response()->json(['status' => $restored]);
    }

    public function restoreStaff(Request $request){
        $data = $request->all();
        $school_id = $request->user()->school_id;
        // $termArr = ['1'=>"first_term", '2'=>"second_term", '3'=>"third_term"];
        $staff_id = $data['staffId'];

        // dd($data);
        $restored = DB::table('staff')
            ->where([
                'id' => $staff_id,
                "school_id" => $school_id,
            ])
            ->update([
                "status" => "1"
            ]);

        return response()->json(['status' => $restored]);
    }

}

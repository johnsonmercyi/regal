<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\School;

class TimetableController extends Controller
{


    public function viewTimeTable(School $school){
        
        // Fetching Classes
        $allClass = DB::table('school_classes')->where([
            ['school_id', $school->id],
            ['status', '1']
        ])->get();

        // Fetching Periods
        $periodTable = DB::table('period_table')->where([
            ['school_id', $school->id],
            ['status', '1'],
        ])->get();

        // Fetching Academic Sessions
        $acadSession = DB::table('academic_sessions')->get();
        // $viewSubjects = DB::table('period_table')->where([
        //     ['school_id', $school->id],
        //     ['status', '1'],
        //     ])->get();

        //Returning views with variables
       return view('timetable.viewtimetable', compact('school', 'allClass', 'periodTable', 'acadSession'));
    }

    public function AddTimeTable(School $school){
        $classTimeTable = DB::table('period_table')->where([
            ['school_id', $school->id],
            ['status', '1'],
        ])->get();
        $acadSession = DB::table('academic_sessions')->get();
        $allClass = DB::table('school_classes')->where([
            ['school_id', $school->id],
            ['status', '1'],
            ])->orderBy('level', 'ASC')->get();

        $subjectList = DB::table('subjects')->get();
        return view('timetable.addtimetable', compact('school', 'classTimeTable', 'subjectList', 'acadSession', 'allClass'));
    }

    public function getTimeTable(Request $request){
        //dd($request);

        $data = $request->all();
        // $data[....as it was declared in Js.......]
        $classTimeTable = DB::table('school_timetable')
        ->select('school_timetable.period_id', 'subject_id', 'day', 'subjects.title')
        ->leftJoin('subjects', 'subjects.id', '=', 'school_timetable.subject_id')
        // ->leftJoin('period_table', 'period_table.id', '=', 'school_timetable.period_id')
        ->where([
            ['class_id', $data['class_id']],
            ['school_timetable.school_id', $data['school_id']],
            //['period_table.school_id', $data['school_id']],
            ['academic_session_id', $data['academic_session_id']]
        ])->get();

        $teachersNames = DB::table('student_subjects')
            ->select('student_subjects.staff_id', 'student_subjects.subject_id', 'staff.id',  'staff.firstName', 'staff.lastName')
            ->leftJoin('staff', 'staff.id', '=', 'student_subjects.staff_id')
            // ->leftJoin('period_table', 'period_table.id', '=', 'school_timetable.period_id')
            ->where([
                ['student_subjects.class_id', $data['class_id']],
                ['student_subjects.academic_session_id', $data['academic_session_id']],
                ['student_subjects.school_id', $data['school_id']],
            ])->get();
        // $classTimeTable = DB::table('school_timetable')
        // ->select('school_timetable.period_id', 'school_timetable.subject_id', 'school_timetable.day', 'subjects.title', 'staff.id',  'staff.firstName', 'staff.lastName',  'student_subjects.staff_id')
        // ->leftJoin('subjects', 'subjects.id', '=', 'school_timetable.subject_id')
        // ->leftJoin('student_subjects', 'student_subjects.subject_id', '=', 'subjects.id')
        // ->leftJoin('staff', 'staff.id', '=', 'student_subjects.staff_id')
        // // ->leftJoin('period_table', 'period_table.id', '=', 'school_timetable.period_id')
        // ->where([
        //     ['school_timetable.class_id', $data['class_id']],
        //     ['school_timetable.school_id', $data['school_id']],
        //     //['period_table.school_id', $data['school_id']],
        //     ['school_timetable.academic_session_id', $data['academic_session_id']],
        //     ['student_subjects.class_id', $data['class_id']],
        //     ['student_subjects.academic_session_id', $data['academic_session_id']],
        //     ['student_subjects.school_id', $data['school_id']],
        //     ])->get();
        return response()->json(['success'=>$classTimeTable, 'teachersNames' => $teachersNames]);
    }

    public function storeTimeTable(Request $request, School $school){
        $data = $request->all();
        // dd($data);
        $inserted = true;
        foreach ($data as $key => $val) {            
            $inserted = DB::table('school_timetable')->updateOrInsert([
                'school_id' => $val['school_id'],
                'academic_session_id' => $val['academic_session_id'],
                'class_id' => $val['class_id'],
                'day' => $val['day'],
                'period_id' => $val['period_id'],
                'subject_id' => $val['subject_id']
            ]);
        }
        return response()->json(['success'=>$inserted]);

    }

    public function manageperiod(School $school){

        $viewPeriod = DB::table('period_table')->where([
            ['school_id', $school->id],
            ['status', '1'],
            ])->get();
        return view('timetable.manageperiod', compact('school', 'viewPeriod'));
    }
    
    public function storeperiod(Request $request){
        $data = $request->all();
        $inserted = true;
        foreach ($data as $key => $val) {
            $inserted = DB::table('period_table')->updateOrInsert([
                'school_id' => $val['school_id'],
                'period_name' => $val['period_name']],
                [
                'start_time' => $val['start_time'],
                'end_time' => $val['end_time'],
               ]);
        }
        return response()->json(['success'=>$inserted]);
    }

}
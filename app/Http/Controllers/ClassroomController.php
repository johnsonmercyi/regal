<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use App\Classroom;
use DB;

class ClassroomController extends Controller
{
    //
    public function index(School $school) {
        $classe = DB::table('school_classes')->where([
                    ['school_id', $school->id],
                    ['status', '1'],
                    ])->orderBy('level', 'ASC')->get();

        $allFormTeachersJoin = DB::table('form_teachers')->
                            select('form_teachers.staff_id', 'form_teachers.class_id', 'staff.firstName', 'staff.lastName', 'staff.otherNames')->
                            leftJoin('staff', 'staff.id', '=', 'form_teachers.staff_id')->
                            where([
                                ['form_teachers.academic_session_id', $school->academic_session_id],
                                ['form_teachers.school_id', $school->id],
                                ['staff.school_id', $school->id]
                            ]);

        $allClass = DB::table('school_classes')->where([
                            ['school_id', $school->id],
                            ['status', '1'],
                        ])->
                        leftJoinSub($allFormTeachersJoin, 'formT', function($join){
                        $join->on('formT.class_id', '=', 'school_classes.id');
                        })->orderBy('level', 'ASC')->get();

        $allFormTeachers = DB::table('form_teachers')->select('staff_id')->
                            where([['academic_session_id', $school->academic_session_id],
                            ['school_id', $school->id],
                            ])->get();

        //extract all the ids into array
        $idArray = [];
        foreach($allFormTeachers as $key=>$stid){
            foreach((array)$stid as $key2=>$val){
                $idArray[] = $val;
            }
        }
        // dd($idArray);
        $notFormTeachers = DB::table('staff')->select('id', 'firstName', 'lastName', 'otherNames')->
                            where('school_id', $school->id)->whereNotIn('id', $idArray)->orderBy('lastName', 'ASC')->get();
        return view('classroom.index', compact('allClass', 'school', 'notFormTeachers'));
    }

    public function getMembers( Request $request, $classroom){
        $data = $request->all();
        // dd($request);
        // $school = School::where('id', $data['schoolId'])->first();
        $school = School::where('id', $request->user()->school_id)->first();
        $term_field = 'class_students_list.'.$school->term_column();
        $classroom = Classroom::where([['id', '=', $classroom], ['school_id', '=', $school->id]])->first();
        $classMembs = DB::table('class_students_list')->
                        select('class_students_list.student_id', 'students.firstName', 'students.lastName', 'students.otherName', 'students.regNo')->
                        where([
                            ['class_students_list.class_id', $classroom->id],
                            ['class_students_list.academic_session_id', $data['sessionId']],
                            [$term_field, '1'],
                            ['class_students_list.school_id', $school->id],
                            ['students.school_id', $school->id],
                            ['class_students_list.status', 'Active'],
                        ])->
                        leftJoin('students', 'students.id', '=', 'class_students_list.student_id')->
                        orderBy('students.lastName', 'ASC')
                        ->get();

        return response()->json(['members'=>$classMembs]);
    }

    public function formTeacher(Request $request){
        $data = $request->all();

        $formInsert = DB::table('form_teachers')->updateOrInsert([
                        'academic_session_id'=>$data['sessionId'],
                        'class_id'=>$data['classId'],
                        'school_id'=>$data['schoolId']],
                        ['staff_id'=>$data['teacherId']]);
        
        return response()->json(['inserted'=>$formInsert]);
    }
    
    public function changeClass(School $school){
        $allClass = DB::table('school_classes')->select('id', 'level', 'suffix')->where([
            ['school_id', $school->id],
            ['status', '1'],
            ])->orderBy('level', 'ASC')->get();

        return view('students.changeClass', compact('school', 'allClass'));
    }

    public function moveToClass(Request $request){
        $data = $request->all();
        // dd($data);
        $movedStudents = [];
        foreach($data as $student){
           $moveAction = DB::table('class_students_list')->
                        where([['academic_session_id', $student['sessionId']], ['student_id', $student['studentId']], ['school_id', $student['schoolId']]])->
                        update(['class_id'=>$student['classId']]);
            if($moveAction){ $movedStudents[] = $student['studentId']; }
        }

        return response()->json(['movedStudents'=>$movedStudents]);
    }

    public function create(School $school){
        $sections = DB::table('school_sections')->where([
            ['school_id', '=', $school->id],
            ['status', '=', 'Active']
        ])->get();
        return view('classroom.create', compact('school', 'sections'));
    }
    
    public function store(Request $request){
        $data = $request->all();
        // dd($data);
        $oldclass = DB::table('school_classes')->where([
            ['school_id', '=', $data['schoolId']],
            ['level', '=', $data['level']],
            ['suffix', '=', $data['suffix']],
            // ['section_id', '=', $data['section_id']]
        ])->exists();
        if($oldclass){
            return response()->json(["exists"=>true]);
        } else {
            $classroom = DB::table('school_classes')->insert([
                'school_id' => $data['schoolId'],
                'level' => $data['level'],
                'suffix' => $data['suffix'],
                'section_id' => $data['section_id'],
                'status' => '1',
            ]);

            return response()->json(["success"=>true]);
        }
    }

    

    public function viewTermStatus(Request $request, School $school) {
        $allClass = DB::table('school_classes')->where([
            ['school_id', $school->id],
            ['status', '1'],
            ])->orderBy('level', 'ASC')->get();
        $acadSession = DB::table('academic_sessions')->get();

        return view('students.term', compact('school', 'allClass', 'acadSession'));

    }

    public function fetchStudentsByTerm(Request $request){
        $data = $request->all();
        $school = $request->user()->school;
        // dd($school);
        $termFields = ['1' => 'first_term', '2' => 'second_term', '3' => 'third_term'];

        $term_field = 'class_students_list.'.$termFields[$data['termId']].' as term_status';
        $classroom = Classroom::where([['id', '=', $data['classId']], ['school_id', '=', $school->id]])->first();

        $classMembs = DB::table('class_students_list')->
            select('class_students_list.student_id', $term_field, 'students.firstName', 'students.lastName', 'students.otherName', 'students.regNo')->
            where([
                ['class_students_list.class_id', $classroom->id],
                ['class_students_list.academic_session_id', $data['sessionId']],
                ['class_students_list.school_id', $school->id],
                ['students.school_id', $school->id],
                ['class_students_list.status', 'Active'],
            ])->
            leftJoin('students', 'students.id', '=', 'class_students_list.student_id')->
            orderBy('students.lastName', 'ASC')
            ->get();

        return response()->json(['members'=>$classMembs, 'term_id'=>$data['termId'], 'session_id'=>$data['sessionId']]);
    }

    public function updateStatus(Request $request) {
        $data = $request->all();
        // dd($data);
        $termFields = ['1' => 'first_term', '2' => 'second_term', '3' => 'third_term'];

        foreach ($data['statusArr'] as $key => $val) {
            
            DB::table('class_students_list')->where([
                'academic_session_id' => $data['session_id'],
                'student_id' => $val['student_id'],
                'school_id' => $data['schoolId']
            ])->update([
                $termFields[$data['term_id']] => $val['status']
            ]);
        }

        return response()->json(['status' => true]);
    }
}

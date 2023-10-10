<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use App\Classroom;
use App\Student;
use DB;

class CommentsController extends Controller
{
    public function makeClassComments(School $school){
        $allClass = DB::table('school_classes')->where([
            ['school_id', $school->id],
            ['status', '1'],
            ])->get();
        $acadSession = DB::table('academic_sessions')->get();

        return view('comments.makecomments', compact('school', 'allClass', 'acadSession'));
    }

    /**********FUNCTION TO SELECT CLASS, SESSION AND TERM FOR COMMENTS INSERTION********* */
    public function getCommentsClass(Request $request, $classroom){
        $formItems = $request->all();
        // dd($formItems['sessionId']);
        $school = School::where('id', $formItems['schoolId'])->first();
        $term_field = 'class_students_list.'.$school->term_column();
        $classroom = Classroom::where([['id', '=', $classroom], ['school_id', '=', $formItems['schoolId']]])->first();
        $studs = DB::table('students')->select('students.id', 'students.firstName', 'students.lastName', 'students.otherName')->
                        where([
                            ['class_students_list.academic_session_id', $formItems['sessionId']],
                            [$term_field, '1'],
                            ['class_students_list.class_id', $formItems['classId']],
                            ['class_students_list.status', 'Active'],
                            ['class_students_list.school_id', $formItems['schoolId']],
                            ['students.school_id', $formItems['schoolId']],
                        ])->leftJoin('class_students_list', 'class_students_list.student_id', '=', 'students.id')
                        ->orderBy('students.lastName', 'ASC')->get();

        $oldComments = DB::table('termly_comments')->select('student_id', 'formTeacherComment', 'headTeacherComment', 'passOrFail')->where([
                        ['academic_session_id', $formItems['sessionId']],
                        ['class_id', $formItems['classId']],
                        ['school_id', $formItems['schoolId']],
                        ['term_id', $formItems['termId']]
        ])->get();

        $scores = DB::table('scores')->select('scores.subject_id', 'scores.student_id', 'scores.TOTAL', 'subjects.code')->
                        leftJoin('subjects', 'scores.subject_id', '=', 'subjects.id')->
                        where([['scores.academic_session_id', $formItems['sessionId']],
                            ['scores.class_id', $formItems['classId']],
                            ['scores.term_id', $formItems['termId']],
                            ['scores.school_id', $formItems['schoolId']]
                    ])->get();

        return response()->json(["students"=>$studs, "oldComments"=>$oldComments, "scores"=>$scores]);

    }
    

    /**************FUNCTION TO INSERT A SUBMITTED COMMENT**************** */
    public function insert(Request $request, Student $student){
        // dd($request->all());
        $data = $request->all();
        if(isset($data['formTeacherComment'])){
            $commentMaker = 'formTeacherComment';
            $dataInsert = [$commentMaker=>$data[$commentMaker]];
        } else {
            $commentMaker = 'headTeacherComment';
            $dataInsert = isset($data['passOrFail']) ? 
                ([$commentMaker=>$data[$commentMaker], 'passOrFail'=>$data['passOrFail']]) :
                ( isset($data['promotedOrNotPromoted']) ?
                    [$commentMaker=>$data[$commentMaker], 'promotedOrNotPromoted'=>$data['promotedOrNotPromoted']] :
                    [$commentMaker=>$data[$commentMaker]] );
        }
        // dd($commentMaker);
        $commentDone = DB::table('termly_comments')->updateOrInsert(
                    ['student_id'=>$data['studentId'], 
                    'class_id'=>$data['classId'], 
                    'academic_session_id'=>$data['sessionId'], 
                    'school_id'=>$data['schoolId'], 
                    'term_id'=>$data['termId']],
                    $dataInsert
        );

        if($commentDone){
            $jsonRes = ['subCes'=>'Comment Inserted Successfully', 'data'=>$dataInsert, 'stdId'=>$data['studentId']];
        } else  {
            $jsonRes = ['subCes'=>'Unable to Insert Comment, Please Try Again'];
        }

        return response()->json($jsonRes);
    }

}

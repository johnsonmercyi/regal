<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\School;
use App\Classroom;

class NurseryScoresController extends Controller
{
    //

    /**
     * Show the form for creating a new student.
     */
    public function index(Request $request, School $school)
    {

        $allClass = DB::table('school_classes')->where([
            ['school_id', $school->id],
            ['status', '1'],
            ['section_id', '2'],
        ])->orderBy('level', 'ASC')->get();

        $assignedSubjects = DB::table('student_subjects')->select('subject_id')->where([
            ['academic_session_id', $school->academic_session_id],
            ['school_id', $school->id],
            ['section_id', '2'],
        ])->groupBy('subject_id')->get();

        $subjectsArr = [];

        if (count($assignedSubjects) > 0) {
            foreach ($assignedSubjects as $subt) {
                $subjectsArr[] = ((array)$subt)['subject_id'];
            }
        }

        $subjects = DB::table('nursery_subjects')->whereIn('id', $subjectsArr)->orderBy('title', 'ASC')->get();
        $acadSession = DB::table('academic_sessions')->get();
        
        // dd("Outcome: ", $subjects);
        return view('nursery_scores.index', compact('school', 'allClass', 'subjects', 'acadSession'));
    }

    /**
     * Function to check network availability before score submit
     */
    public function networkCheck()
    {
        return response()->json(["success" => true]);
    }


    /**
     * Function for fetching students who offer selected subject currently.
     */
    public function getStudents(Request $request, Classroom $classroom)
    {
        $data = (array) json_decode($request->input('data'));
        // dd($data['subjectId'],$data['sessionId']);
        $school_id = $request->user()->school_id;
        $getStudents = (array) DB::table('student_subjects')->select('students')->where([
            ['academic_session_id', '=', $data['sessionId']],
            ['subject_id', '=', $data['subjectId']],
            ['school_id', '=', $school_id],
            ['section_id', '=', "2"],
            ['class_id', '=', $data['classId']]
        ])->first();

        if ($getStudents) {
            $subjectStudents = $getStudents['students'];
            // dd($getStudents, $subjectStudents);

            if ($subjectStudents != 'All') {
                $subjectStudents = ((array) json_decode($subjectStudents))['students'];
            }
            // $allStudents = DB::table('students')->select('id', 'firstName', 'lastName', 'otherName')->where('currentClassId', $data['classId'])
            //                 ->joinSub($subjectStudents, 'subStudents', function($join){
            //                     $join->on('students.id', '=', 'subStudents.studentId');
            //                 })
            //                 ->get();

            if ($subjectStudents != 'All') {
                $allStudents = DB::table('students')->select('students.id', 'students.firstName', 'students.lastName', 'students.otherName')->whereIn('students.id', $subjectStudents)->orderBy('students.lastName', 'ASC')->get();
                // where('class_students_list.sessionId', $data['sessionId'])->                                
                // leftJoin('class_students_list', 'class_students_list.studentId', '=', 'students.id')->
                // count();
            } else {
                $allStudents = DB::table('students')->select('students.id', 'students.firstName', 'students.lastName', 'students.otherName')->where([
                        ['class_students_list.academic_session_id', $data['sessionId']],
                        ['class_students_list.class_id', $data['classId']],
                        ['class_students_list.school_id', $school_id],
                        ['students.school_id', $school_id],
                        ['class_students_list.status', 'Active'],
                    ])->leftJoin('class_students_list', 'class_students_list.student_id', '=', 'students.id')->orderBy('students.lastName', 'ASC')
                    ->get();
                // dd($subjectStudents, $allStudents);
            }
        } else {
            $allStudents = false;
        }

        $assessId = (array) DB::table('school_sections')->select('assessment_format_id')->where([
            ['school_id', $classroom->School->id],
            ['id', $classroom->SchoolSection->id],
        ])->first();
        // dd($assessFormat);
        $assessFormat = DB::table('assessment_formats')->where([
            ['school_id', $classroom->School->id],
            ['format_id', $assessId['assessment_format_id']],
        ])->orderBy('formatType', 'ASC')->get();

        // dd($assessId, $assessFormat);

        $oldScores = DB::table('scores')->where([
            ['academic_session_id', '=', $data['sessionId']],
            ['class_id', '=', $data['classId']],
            ['school_id', '=', $school_id],
            ['subject_id', '=', $data['subjectId']],
            ['term_id', '=', $data['termId']],
            ['status', '=', '1']
        ])->get();
        // dd($oldScores);

        return response()->json(['students' => $allStudents, 'assessFormat' => $assessFormat, 'oldScores' => $oldScores]);
    }

    public function insertScores(Request $request)
    {
        $jsonscores = (array) json_decode($request->input('data'));
        $scores = array();
        // dd($jsonscores);
        foreach ($jsonscores as $score) {
            $scores[] = (array) $score;
        }
        $checkExist = DB::table('scores')->where([
            ['academic_session_id', $scores[0]['academic_session_id']],
            ['class_id', $scores[0]['class_id']],
            ['subject_id', $scores[0]['subject_id']],
            ['school_id', $scores[0]['school_id']],
            ['term_id', $scores[0]['term_id']],
            ['status', '1']
        ])->exists();

        $insertScores = '';
        if (!$checkExist) {
            $insertScores = DB::table('scores')->insert($scores);
        }

        return response()->json(['exists' => $checkExist, 'insertScores' => $insertScores]);
    }

    public function updateStudentScores(Request $request)
    {
        $scores = (array) ($request->input('data'));

        // $studentDetails = Array(
        //     ['sessionId', '=', $scores[0]['sessionId']],
        //     ['studentId', '=', $scores[0]['studentId']],
        //     ['termId', '=', $scores[0]['termId']],
        //     ['subjectId', '=', $scores[0]['subjectId']],
        //     ['classId', '=', $scores[0]['classId']],
        //     ['schoolId', '=', $scores[0]['schoolId']]            
        // );

        // for($n = 0; $n < count($studentDetails); $n++){
        //     unset($scores[0][$studentDetails[$n][0]]);
        // }
        // // dd($studentDetails, $scores[0]);
        // // $scoreUpdate = 
        // DB::table('scores')->where($studentDetails)->update($scores[0]);

        $studentDetails = array(
            'academic_session_id' => $scores[0]['academic_session_id'],
            'student_id' => $scores[0]['student_id'],
            'term_id' => $scores[0]['term_id'],
            'subject_id' => $scores[0]['subject_id'],
            'class_id' => $scores[0]['class_id'],
            'school_id' => $scores[0]['school_id']
        );
        // dd($studentDetails, $scores[0]);
        $studentDetailsClean = ['academic_session_id', 'student_id', 'term_id', 'subject_id', 'class_id', 'school_id'];

        for ($n = 0; $n < count($studentDetailsClean); $n++) {
            unset($scores[0][$studentDetailsClean[$n]]);
        }
        // dd($studentDetails, $scores[0]);
        // $scoreUpdate = 
        DB::table('scores')->updateOrInsert($studentDetails, $scores[0]);

        $respo = array();
        // if($scoreUpdate){
        $respo['id'] = $studentDetails['student_id'];
        $respo['scores'] = $scores[0];
        // }
        return response()->json(['res' => $respo]);
    }
}

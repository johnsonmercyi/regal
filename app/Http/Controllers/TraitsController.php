<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use App\Classroom;
use DB;

class TraitsController extends Controller
{
    public function manageTrait(School $school){
        $traitCat = DB::table('trait_category')->get();
        $socialTraits = DB::table('trait_definition')->where('category_id', 1)->get();
        $motorTraits = DB::table('trait_definition')->where('category_id', 2)->get();
        $existTraits = (array) DB::table('school_traits')->select('trait_id')->where('school_id', $school->id)->first();
        if(count($existTraits) > 0){
            $existTraits = ((array) json_decode($existTraits['trait_id']))['traits'];
        } else {
            $existTraits = [];
        }
        // dd($existTraits);
        
        return view('traits.manage', compact('school', 'traitCat', 'socialTraits', 'motorTraits', 'existTraits'));
    }

    public function storeSchoolTrait(Request $request){
        $traitDataJson = (array) json_decode($request->input('data'));
        // dd($traitDataJson);
        // $traitData = [];
        // foreach($traitDataJson as $trait){
        //     $traitData[] = (array) $trait;
        // }
        // dd($traitData);
        $insertSuccess = DB::table('school_traits')->updateOrInsert(
                            ['school_id'=>$traitDataJson['schoolId']],
                            ['trait_id'=>$traitDataJson['traitId']]
                            );

        return response()->json(['successInfo' => $insertSuccess]);
    }

    public function createTraitFormat(School $school){
        return view('traits.format', compact('school'));
    }

    public function storeTraitFormat(Request $request){
        // dd($request->all());
        $traitFormatJson = (array) json_decode($request->input('data'));
        $traitFormat = [];
        foreach($traitFormatJson as $trait){
            $traitFormat[] = (array) $trait;
        }
        // dd($traitFormat);
        $insertSuccess = DB::table('school_trait_rating')->insert($traitFormat);

        return response()->json(['successInfo' => $insertSuccess]);
    }

    public function makeStudentTraitAssessment(School $school){
        $allClass = DB::table('school_classes')->where([
            ['school_id', $school->id],
            ['status', '1'],
            ])->get();
        $acadSession = DB::table('academic_sessions')->get();

        return view('traits.makeAssessment', compact('school', 'allClass', 'acadSession'));
    }

    /**
     * Function to select all students in a class for trait assessment.
     */
    public function getStudents(Request $request, School $school, $classroom) {
        $classroom = Classroom::where([['id', $classroom], ['school_id', $school->id]])->first();
        $data = (array) json_decode($request->input('data'));
        // dd($data);
        $allStudents = DB::table('students')->select('students.id', 'students.regNo', 'students.firstName', 'students.lastName', 'students.otherName')->
                        where([
                            ['class_students_list.academic_session_id', $data['sessionId']],
                            ['class_students_list.class_id', $data['classId']],
                            ['class_students_list.school_id', $school->id],
                            ['class_students_list.status', 'Active'],
                        ])->leftJoin('class_students_list', 'class_students_list.student_id', '=', 'students.id')
                        ->orderBy('students.lastName', 'ASC')->get();

        // DB::table('students')->select('id', 'firstName', 'lastName', 'otherName')->where('currentClassId', $data['classId'])->get();
                        // ->joinSub($subjectStudents, 'subStudents', function($join){
                        //     $join->on('students.id', '=', 'subStudents.studentId');
                        // })
        $traitFormat = DB::table('school_trait_rating')->select('description', 'rating')->where('school_id', $classroom->School->id)->get();

        $schoolTraits = (array) DB::table('school_traits')->select('trait_id')->where('school_id', $classroom->School->id)->first();
        $traitsIdArr = ((array) json_decode($schoolTraits['trait_id']))['traits'];
        $schoolTraits2 = DB::table('trait_definition')->select('name', 'id')->whereIn('id', $traitsIdArr)->get();

        $studentTraitAssess = DB::table('student_trait_assessments')->select('student_id', 'traitRating')->where([
                                ['class_id', $data['classId']],
                                ['school_id', $school->id],
                                ['academic_session_id', $data['sessionId']],
                                ['term_id', $data['termId']]
                                ])->get();
        
        // $oldScores = DB::table('scores')->where([
        //             ['sessionId', '=', $data['sessionId']],
        //             // ['classId', '=', $data['classId']],
        //             // ['subjectId', '=', $data['subjectId']],
        //             ['termId', '=', $data['termId']]
        //         ])->get();
        // dd($oldScores);

        // dd($studentTraitAssess);
        return response()->json(['students' => $allStudents, 
                                'traitFormat'=>$traitFormat, 
                                'schoolTraits'=>$schoolTraits2, 
                                'studentsOldTraits'=>$studentTraitAssess
                                ]);
    }

    public function storeStudentAssessment(Request $request){
        $data = (array) json_decode($request->input('data'));
        // dd($request->all());
        $traitDbInsert  = DB::table('student_trait_assessments')->updateOrInsert(
                                ['student_id'=>$data['studentId'], 
                                'class_id'=>$data['classId'], 
                                'academic_session_id'=>$data['sessionId'], 
                                'school_id'=>$data['schoolId'], 
                                'term_id'=>$data['termId']],
                                ['traitRating'=>$data['traitRating']]
                            );

        $responseTrait = [
            "student_id" => $data['studentId'],
            "traitRating" => $data['traitRating']
        ];
        return response()->json(['studentTrait'=>$responseTrait]);
    }

    public function index(School $school){
        $ratingFormat = DB::table('school_trait_rating')->where('school_id', $school->id)->orderBy('rating', 'desc')->get();

        return view('traits.index', compact('school', 'ratingFormat'));
    }

    public function update(Request $request){
        $edits = $request->all();

        try {
            foreach ($edits["traits"] as $key => $val) {
                DB::table('school_trait_rating')
                ->updateOrInsert(["id" => $val["id"], "school_id" => $edits["schoolId"]],
                [
                    "description" => $val["desc"],
                    "rating" => $val["rating"],
                ]);
            }
            return response()->json(['status' => true]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false]);
        }
        
    }
}

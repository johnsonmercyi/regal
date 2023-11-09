<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\School;
use App\Classroom;
use App\Student;
use Illuminate\Support\Facades\DB;

class ResultsController extends Controller
{
  public function selectPage(School $school)
  {
    $allClass = DB::table('school_classes')->where([
      ['school_id', $school->id],
      ['status', '1'],
    ])->orderBy('level', 'ASC')->get();
    $acadSession = DB::table('academic_sessions')->get();

    return view('results.broadsheet', compact('school', 'allClass', 'acadSession'));
  }

  public function studentSelectPage(School $school)
  {
    $allClass = DB::table('school_classes')->where([
      ['school_id', $school->id],
      ['status', '1'],
    ])->orderBy('level', 'ASC')->get();
    $acadSession = DB::table('academic_sessions')->get();

    return view('results.index', compact('school', 'allClass', 'acadSession'));
  }

  public function manageResultPage(School $school)
  {
    $allClass = DB::table('school_classes')->where([
      ['school_id', $school->id],
      ['status', '1'],
    ])->orderBy('level', 'ASC')->get();
    $acadSession = DB::table('academic_sessions')->get();

    return view('results.manage', compact('school', 'allClass', 'acadSession'));
  }

  public function fetchResults(Request $request, $classroom)
  {
    // dd($request->all());
    $formItems = $request->all();

    $school = School::where('id', $formItems['schoolId'])->first();
    $term_field = 'class_students_list.' . $school->term_column();
    $classroom = Classroom::where([['id', '=', $classroom], ['school_id', '=', $formItems['schoolId']]])->first();
    $allStudentsId = DB::table('scores')->select('scores.student_id')->leftJoin('class_students_list', 'class_students_list.student_id', '=', 'scores.student_id')->where([
        ['scores.academic_session_id', $formItems['sessionId']],
        ['scores.class_id', $formItems['classId']],
        ['scores.term_id', $formItems['termId']],
        ['scores.school_id', $formItems['schoolId']],
        [$term_field, '1'],
        ['class_students_list.school_id', $formItems['schoolId']],
        ['class_students_list.status', 'Active'],
        ['scores.status', '1']
      ])->groupBy('student_id')->get();

    $allSubjects = DB::table('scores')->select('subject_id')->where([
      ['academic_session_id', $formItems['sessionId']],
      ['school_id', $formItems['schoolId']],
      ['class_id', $formItems['classId']],
      ['term_id', $formItems['termId']]
    ])->groupBy('subject_id')->get();

    $allScores = DB::table('scores')->select('subject_id', 'student_id', 'TOTAL')->where([
      ['academic_session_id', $formItems['sessionId']],
      ['class_id', $formItems['classId']],
      ['term_id', $formItems['termId']],
      ['school_id', $formItems['schoolId']],
      ['status', '1']
    ])->get();

    //extract all the ids into array
    $idArray = [];
    foreach ($allStudentsId as $key => $stid) {
      foreach ((array)$stid as $key2 => $val) {
        $idArray[] = $val;
      }
    }

    //extract subject ids into array 
    $subjectArray = [];
    foreach ($allSubjects as $key => $sub) {
      foreach ((array)$sub as $key2 => $val) {
        $subjectArray[] = $val;
      }
    }

    $studentDetails = DB::table('students')->select('id', 'firstName', 'lastName', 'otherName', 'regNo')
      ->orderBy('lastName')
      ->where('school_id', $formItems['schoolId'])
      ->whereIn('id', $idArray)
      ->get();
    $subjectNames = DB::table('subjects')->select('id', 'title')->whereIn('id', $subjectArray)->get();

    return response()->json([
      'studentRes' => $studentDetails,
      'subjectRes' => $subjectNames,
      'resultRes' => $allScores
    ]);
  }

  public function fetchClassList(Request $request, $classroom)
  {
    // dd($request->all());
    $formItems = $request->all();

    $school = School::where('id', $formItems['schoolId'])->first();
    $term_field = 'class_students_list.' . $school->term_column();
    $classroom = Classroom::where([['id', '=', $classroom], ['school_id', '=', $formItems['schoolId']]])->first();

    $allStudentsId = DB::table('scores')->select('student_id')->where([
      ['academic_session_id', $formItems['sessionId']],
      ['school_id', $formItems['schoolId']],
      ['class_id', $formItems['classId']],
      ['term_id', $formItems['termId']],
      ['status', '1']
    ])->groupBy('student_id')->get();

    // Check for annual result
    if ($formItems['termId'] == '4') {
      $allStudentsId = DB::table('scores')->select('student_id')->where([
        ['academic_session_id', $formItems['sessionId']],
        ['school_id', $formItems['schoolId']],
        ['class_id', $formItems['classId']],
        ['status', '1']
      ])->groupBy('student_id')->get();
    }

    //extract all the ids into array
    $idArray = [];
    foreach ($allStudentsId as $key => $stid) {
      foreach ((array)$stid as $key2 => $val) {
        $idArray[] = $val;
      }
    }

    $studentDetails = DB::table('students')->select('id', 'firstName', 'lastName', 'otherName', 'regNo')->orderBy('lastName', 'ASC')
      ->where('school_id', $formItems['schoolId'])
      ->whereIn('id', $idArray)
      ->get();
    return response()->json(['studentRes' => $studentDetails]);
  }

  public function fetchStudentResult(Request $request, $student)
  {
    // dd($request->all());
    $formItems = $request->all();

    $school = School::where('id', $formItems['schoolId'])->first();
    $classroom = Classroom::where([['id', '=', $formItems['classId']], ['school_id', '=', $formItems['schoolId']]])->first();
    $student = Student::where([['school_id', '=', $school->id], ['id', '=', $student]])->first();

    // Check for Annual (4) result and redirect to Annual function
    if ($formItems['termId'] == 4) {
      return $this->fetchStudentAnnual($formItems, $student);
    }

    $selectArray1 = [
      ['academic_session_id', $formItems['sessionId']],
      ['class_id', $formItems['classId']],
      ['term_id', $formItems['termId']],
      ['school_id', $formItems['schoolId']],
      ['status', '1']
    ];


    $resultSelectArray = [
      ['academic_session_id', $formItems['sessionId']],
      ['class_id', $formItems['classId']],
      ['term_id', $formItems['termId']],
      ['school_id', $formItems['schoolId']],
      ['student_id', $student->id],
      ['status', '1']
    ];

    $allStudentsId = DB::table('scores')->select('student_id')->where($selectArray1)->groupBy('student_id')->get();

    $allSubjects = DB::table('scores')->select('subject_id')->where($selectArray1)->groupBy('subject_id')->get();

    $allScores = DB::table('scores')->select('subject_id', 'student_id', 'TOTAL')->where($selectArray1)->get();

    $studentScores = DB::table('scores')->select('scores.*', 'subjects.result_order')->where($resultSelectArray)->leftJoin('subjects', 'scores.subject_id', '=', 'subjects.id')->orderBy('subjects.result_order', 'ASC')->get();


    $allLevels =  DB::table('school_classes')->select('id')->where([['level', '=', $classroom->level], ['school_id', '=', $formItems['schoolId']]])->get();
    $overAllScores = [];
    foreach ($allLevels as $item) {
      foreach ($item as $croom) {
        $overAllScores[] = DB::table('scores')->select('subject_id', 'student_id', 'TOTAL')->where([
            ['academic_session_id', $formItems['sessionId']],
            ['class_id', $croom],
            ['term_id', $formItems['termId']],
            ['school_id', '=', $formItems['schoolId']],
            ['status', '1']
          ])->get();
      }
    }
    // dd($overAllScores);

    // dd($school);
    // $assessFormat = DB::table('assessmentformat')->where('schoolId', $school->id)->orderBy('formatType', 'ASC')->get();
    $assessId = (array) DB::table('school_sections')->select('assessment_format_id')->where([
      ['school_id', $classroom->School->id],
      ['id', $classroom->SchoolSection->id],
    ])->first();
    // dd($assessFormat);
    $assessFormat = DB::table('assessment_formats')->where([
      ['school_id', $classroom->School->id],
      ['format_id', $assessId['assessment_format_id']],
    ])->orderBy('formatType', 'ASC')->get();

    //extract all the ids into array
    $idArray = [];
    foreach ($allStudentsId as $key => $stid) {
      foreach ((array)$stid as $key2 => $val) {
        $idArray[] = $val;
      }
    }

    //extract subject ids into array 
    $subjectArray = [];
    foreach ($allSubjects as $key => $sub) {
      foreach ((array)$sub as $key2 => $val) {
        $subjectArray[] = $val;
      }
    }

    $gradeId = (array) DB::table('school_sections')->select('grading_format_id')->where([
      ['school_id', $classroom->School->id],
      ['id', $classroom->SchoolSection->id],
    ])->first();
    // dd($assessFormat);
    $gradingFormat = DB::table('grading_formats')->where([
      ['school_id', $classroom->School->id],
      ['format_id', $gradeId['grading_format_id']],
    ])->orderBy('minScore', 'DESC')->get();
    // $gradingFormat = DB::table('gradingformat')->where([
    //     ['schoolId', $school->id],
    //     // ['sectionId', $classroom->sectionID]
    //     ])->get();

    $resumeDate = DB::table('term_config')->select('startDate')->where([
      ['academic_session_id', $formItems['sessionId']],
      ['school_id', $school->id],
      ['term_id', $formItems['termId']],
    ])->get();

    $selectArray2 = [
      ['academic_session_id', $formItems['sessionId']],
      ['class_id', $formItems['classId']],
      ['term_id', $formItems['termId']],
      ['school_id', $formItems['schoolId']],
      ['student_id', $student->id],
    ];

    $stdComments = DB::table('termly_comments')->where($selectArray2)->get();
    $traitRating = DB::table('school_trait_rating')->select('description', 'rating')->where('school_id', $school->id)->get();

    // $studentDetails = DB::table('students')->select('id', 'firstName', 'lastName', 'otherName', 'regNo')->whereIn('id', $idArray)->get();
    $studentDetails = DB::table('students')->select('id', 'firstName', 'lastName', 'otherName', 'regNo')
      ->where([['id', $student->id], ['school_id', $school->id]])->get();
    // $subjectNames = DB::table('subjects')->select('id', 'title')->whereIn('id', $subjectArray)->orderBy('result_order', 'ASC')->get();
    $subjectNames = DB::table('subjects')->select('subjects.id', 'subjects.title', 'staff.signature', 'student_subjects.staff_id')
      ->leftJoin('student_subjects', 'student_subjects.subject_id', '=', 'subjects.id')
      ->leftJoin('staff', 'staff.id', '=', 'student_subjects.staff_id')
      ->where([
        ['student_subjects.class_id', $formItems['classId']],
        ['student_subjects.school_id', $school->id],
        ['student_subjects.academic_session_id', $formItems['sessionId']],
        ['staff.school_id', $school->id]
      ])
      ->whereIn('subjects.id', $subjectArray)
      ->orderBy('subjects.result_order', 'ASC')->get();

    $studentTraitAssess = DB::table('student_trait_assessments')->select('traitRating')->where($selectArray2)->get();
    // $schoolTraits = DB::table('schooltraits')->select('schooltraits.traitId', 'traitdefinition.name')->where('schoolId', $school->id)->leftJoin(
    //                 'traitdefinition', 'schooltraits.traitId', '=', 'traitdefinition.id')->get();

    $schoolTraits1 = (array) DB::table('school_traits')->select('trait_id')->where('school_id', $classroom->School->id)->first();
    $traitsIdArr = $schoolTraits1 ? ((array) json_decode($schoolTraits1['trait_id']))['traits'] : [];
    $schoolTraits = DB::table('trait_definition')->select('name', 'id')->whereIn('id', $traitsIdArr)->get();

    $formTeacher = DB::table('form_teachers')->select('form_teachers.staff_id', 'staff.lastName', 'staff.firstName')->leftJoin('staff', 'form_teachers.staff_id', '=', 'staff.id')->where([
        ['form_teachers.academic_session_id', $formItems['sessionId']],
        ['form_teachers.class_id', $formItems['classId']],
        ['form_teachers.school_id', $school->id],
        ['staff.school_id', $school->id],
      ])->get();
    // dd($formTeacher);
    $newsletter = [];

    // DB::table('termly_newsletter')->where([
    //                     'class_id' => $formItems['classId'],
    //                     'academic_session_id' => $formItems['sessionId'],
    //                     'term_id' => $formItems['termId']
    //                     ])
    //                     ->orWhere([
    //                         ['class_id', '=', 'All'],
    //                         ['academic_session_id', '=', $formItems['sessionId']],
    //                         ['term_id', '=', $formItems['termId']]
    //                         ])
    //                     ->get();
    $teacherDetails = ['schoolHead' => $classroom->SchoolSection->sectionHead, 'schoolHeadSign' => $classroom->SchoolSection->sectionHeadSign, 'formTeacher' => $formTeacher];

    $shwData = DB::select("SELECT h1, h2, w1, w2 FROM students_hw WHERE student_id=$student->id AND academic_session_id=$formItems[sessionId] AND term=$formItems[termId]");

    // dd($shwData);

    return response()->json([
      'studentRes' => $studentDetails,
      'subjectRes' => $subjectNames,
      'resultRes' => $allScores,
      'selectedStudent' => $studentScores,
      'overAllScores' => $overAllScores,
      'assessFormat' => $assessFormat,
      'schoolTraits' => $schoolTraits,
      'traitRating' => $traitRating,
      'studentTraits' => $studentTraitAssess,
      'studentComments' => $stdComments,
      'teacherDetails' => $teacherDetails,
      'gradingFormat' => $gradingFormat,
      'resumeDate' => $resumeDate,
      'shw' => $shwData
    ]);
  }


  public function fetchClassResult(Request $request, $classroom)
  {
    // dd($request->all());
    $formItems = $request->all();

    $school = School::where('id', $formItems['schoolId'])->first();
    $classroom = Classroom::where([['id', '=', $classroom], ['school_id', '=', $formItems['schoolId']]])->first();

    // Check for Annual (4) result and redirect to Annual function
    if ($formItems['termId'] == 4) {
      return $this->fetchClassAnnual($formItems, $classroom);
    }

    $selectArray1 = [
      ['academic_session_id', $formItems['sessionId']],
      ['class_id', $formItems['classId']],
      ['term_id', $formItems['termId']],
      ['school_id', $formItems['schoolId']],
      ['status', '1']
    ];
    $selectArray2 = [
      ['academic_session_id', $formItems['sessionId']],
      ['class_id', $formItems['classId']],
      ['term_id', $formItems['termId']],
      ['school_id', $formItems['schoolId']],
    ];

    $allStudentsId = DB::table('scores')->select('student_id')->where($selectArray1)->groupBy('student_id')->get();

    $allSubjects = DB::table('scores')->select('subject_id')->where($selectArray1)->groupBy('subject_id')->get();

    $allScores = DB::table('scores')->select('subject_id', 'student_id', 'TOTAL')->where($selectArray1)->get();

    // $classScores = DB::table('scores')->where($selectArray1)->get();
    $classScores = DB::table('scores')->select('scores.*', 'subjects.result_order')->where($selectArray1)->leftJoin('subjects', 'scores.subject_id', '=', 'subjects.id')->orderBy('subjects.result_order', 'ASC')->get();
    // $school = $classroom->School;

    $allLevels =  DB::table('school_classes')->select('id')->where([['level', $classroom->level], ['school_id', $formItems['schoolId']]])->get();
    $overAllScores = [];
    foreach ($allLevels as $item) {
      foreach ($item as $croom) {
        $overAllScores[] = DB::table('scores')->select('subject_id', 'student_id', 'TOTAL')->where([
            ['academic_session_id', $formItems['sessionId']],
            ['class_id', $croom],
            ['term_id', $formItems['termId']],
            ['school_id', $formItems['schoolId']],
            ['status', '1']
          ])->get();
      }
    }
    // dd($school);
    // $assessFormat = DB::table('assessment_formats')->where('schoolId', $school->id)->orderBy('formatType', 'ASC')->get();
    $assessId = (array) DB::table('school_sections')->select('assessment_format_id')->where([
      ['school_id', $classroom->School->id],
      ['id', $classroom->SchoolSection->id],
    ])->first();
    // dd($assessFormat);
    $assessFormat = DB::table('assessment_formats')->where([
      ['school_id', $classroom->School->id],
      ['format_id', $assessId['assessment_format_id']],
    ])->orderBy('formatType', 'ASC')->get();

    //extract all the ids into array
    $idArray = [];
    foreach ($allStudentsId as $key => $stid) {
      foreach ((array)$stid as $key2 => $val) {
        $idArray[] = $val;
      }
    }

    //extract subject ids into array 
    $subjectArray = [];
    foreach ($allSubjects as $key => $sub) {
      foreach ((array)$sub as $key2 => $val) {
        $subjectArray[] = $val;
      }
    }

    $gradeId = (array) DB::table('school_sections')->select('grading_format_id')->where([
      ['school_id', $classroom->School->id],
      ['id', $classroom->SchoolSection->id],
    ])->first();
    // dd($assessFormat);
    $gradingFormat = DB::table('grading_formats')->where([
      ['school_id', $classroom->School->id],
      ['format_id', $gradeId['grading_format_id']],
    ])->orderBy('minScore', 'DESC')->get();

    // $gradingFormat = DB::table('gradingformat')->where([
    //                         ['schoolId', $school->id],
    //                         // ['sectionId', $classroom->sectionID]
    //                         ])->get();


    $classComments = DB::table('termly_comments')->where($selectArray2)->get();
    $traitRating = DB::table('school_trait_rating')->select('description', 'rating')->where('school_id', $school->id)->get();

    $studentDetails = DB::table('students')->select('id', 'firstName', 'lastName', 'otherName', 'regNo')
      ->where('school_id', $formItems['schoolId'])
      ->whereIn('id', $idArray)
      ->orderBy('lastName', 'ASC')->get();

    $subjectNames = DB::table('subjects')->select('subjects.id', 'subjects.code', 'subjects.title', 'staff.signature', 'student_subjects.staff_id')
      ->leftJoin('student_subjects', 'student_subjects.subject_id', '=', 'subjects.id')
      ->leftJoin('staff', 'staff.id', '=', 'student_subjects.staff_id')
      ->where([
        ['student_subjects.class_id', $formItems['classId']],
        ['student_subjects.school_id', $formItems['schoolId']],
        ['student_subjects.academic_session_id', $formItems['sessionId']],
        ['staff.school_id', $formItems['schoolId']]
      ])
      ->whereIn('subjects.id', $subjectArray)
      ->orderBy('subjects.result_order', 'ASC')->get();

    $classTraitAssess = DB::table('student_trait_assessments')->select('student_id', 'traitRating')->where($selectArray2)->get();
    // $schoolTraits = DB::table('schooltraits')->select('schooltraits.traitId', 'traitdefinition.name')->where('schoolId', $school->id)->leftJoin(
    //                 'traitdefinition', 'schooltraits.traitId', '=', 'traitdefinition.id')->get();

    $schoolTraits1 = (array) DB::table('school_traits')->select('trait_id')->where('school_id', $classroom->School->id)->first();
    $traitsIdArr = $schoolTraits1 ? ((array) json_decode($schoolTraits1['trait_id']))['traits'] : [];
    $schoolTraits = DB::table('trait_definition')->select('name', 'id')->whereIn('id', $traitsIdArr)->get();

    $formTeacher = DB::table('form_teachers')->select('form_teachers.staff_id', 'staff.lastName', 'staff.firstName')->leftJoin('staff', 'form_teachers.staff_id', '=', 'staff.id')->where([
        ['form_teachers.academic_session_id', $formItems['sessionId']],
        ['form_teachers.class_id', $formItems['classId']],
        ['form_teachers.school_id', $formItems['schoolId']],
        ['staff.school_id', $formItems['schoolId']],
      ])->get();



    $resumeDate = DB::table('term_config')->select('startDate')->where([
      ['academic_session_id', $formItems['sessionId']],
      ['school_id', $school->id],
      ['term_id', $formItems['termId']],
    ])->get();

    // dd($schoolTraits);
    $teacherDetails = ['schoolHead' => $classroom->SchoolSection->sectionHead, 'schoolHeadSign' => $classroom->SchoolSection->sectionHeadSign, 'formTeacher' => $formTeacher];

    $shwData = DB::table('students_hw')->select('h1', 'h2', 'w1', 'w2')->where('school_id', $formItems['schoolId'])
    ->where('academic_session_id', $formItems['sessionId'])
    ->where('term', $formItems['termId'])
    ->whereIn('student_id', $idArray)->get();

    return response()->json([
      'studentRes' => $studentDetails,
      'subjectRes' => $subjectNames,
      'resultRes' => $allScores,
      'classScores' => $classScores,
      'overAllScores' => $overAllScores,
      'assessFormat' => $assessFormat,
      'schoolTraits' => $schoolTraits,
      'traitRating' => $traitRating,
      'classTraits' => $classTraitAssess,
      'classComments' => $classComments,
      'teacherDetails' => $teacherDetails,
      // 'classIdList'=>$idArray,
      'gradingFormat' => $gradingFormat,
      'resumeDate' => $resumeDate,
      'shw' => $shwData
    ]);
  }

  public function fetchStudentAnnual($formItems, $student)
  {


    $school = School::where('id', $formItems['schoolId'])->first();
    $classroom = Classroom::where([['id', '=', $formItems['classId']], ['school_id', '=', $formItems['schoolId']]])->first();

    $selectArray1 = [
      ['academic_session_id', $formItems['sessionId']],
      ['class_id', $formItems['classId']],
      ['school_id', $formItems['schoolId']],
      // ['term_id', $formItems['termId']],
      ['status', '1']
    ];

    $selectArray2 = [
      ['academic_session_id', $formItems['sessionId']],
      ['class_id', $formItems['classId']],
      ['school_id', $formItems['schoolId']],
      ['term_id', $formItems['termId']],
      ['student_id', $student->id],
    ];

    $resultSelectArray = [
      ['academic_session_id', $formItems['sessionId']],
      ['class_id', $formItems['classId']],
      ['school_id', $formItems['schoolId']],
      // ['term_id', $formItems['termId']],
      ['student_id', $student->id],
      ['status', '1']
    ];

    $allStudentsId = DB::table('scores')->select('student_id')->where($selectArray1)->groupBy('student_id')->get();

    $allSubjects = DB::table('scores')->select('subject_id')->where($selectArray1)->groupBy('subject_id')->get();

    $allScores = DB::table('scores')->select('subject_id', 'student_id', 'TOTAL')->where($selectArray1)->get();

    $studentScores = DB::table('scores')->select(
        'scores.term_id',
        'scores.subject_id',
        'scores.student_id',
        'scores.TOTAL',
        'subjects.result_order'
      )->where($resultSelectArray)->leftJoin('subjects', 'scores.subject_id', '=', 'subjects.id')->orderBy('subjects.result_order', 'ASC')->get();



    $allLevels =  DB::table('school_classes')->select('id')->where('level', $classroom->level)->get();
    $overAllScores = [];
    foreach ($allLevels as $item) {
      foreach ($item as $croom) {
        $overAllScores[] = DB::table('scores')->select('subject_id', 'student_id', 'TOTAL')->where([
            ['academic_session_id', $formItems['sessionId']],
            ['class_id', $croom],
            // ['term_id', $formItems['termId']],
            ['status', '1']
          ])->get();
      }
    }

    //extract all the ids into array
    $idArray = [];
    foreach ($allStudentsId as $key => $stid) {
      foreach ((array)$stid as $key2 => $val) {
        $idArray[] = $val;
      }
    }

    //extract subject ids into array 
    $subjectArray = [];
    foreach ($allSubjects as $key => $sub) {
      foreach ((array)$sub as $key2 => $val) {
        $subjectArray[] = $val;
      }
    }

    $gradeId = (array) DB::table('school_sections')->select('grading_format_id')->where([
      ['school_id', $classroom->School->id],
      ['id', $classroom->SchoolSection->id],
    ])->first();
    // dd($assessFormat);
    $gradingFormat = DB::table('grading_formats')->where([
      ['school_id', $classroom->School->id],
      ['format_id', $gradeId['grading_format_id']],
    ])->orderBy('minScore', 'DESC')->get();


    $stdComments = DB::table('termly_comments')->where($selectArray2)->get();

    $studentDetails = DB::table('students')->select('id', 'firstName', 'lastName', 'otherName', 'regNo')->where([['id', $student->id], ['school_id', $formItems['schoolId']]])->get();
    // $subjectNames = DB::table('subjects')->select('id', 'title')->
    //                 whereIn('id', $subjectArray)->orderBy('result_order', 'ASC')->get();
    $subjectNames = DB::table('subjects')->select('subjects.id', 'subjects.title', 'staff.signature', 'student_subjects.staff_id')
      ->leftJoin('student_subjects', 'student_subjects.subject_id', '=', 'subjects.id')
      ->leftJoin('staff', 'staff.id', '=', 'student_subjects.staff_id')
      ->where([
        ['student_subjects.class_id', $formItems['classId']],
        ['student_subjects.school_id', $formItems['schoolId']],
        ['student_subjects.academic_session_id', $formItems['sessionId']],
        ['staff.school_id', $formItems['schoolId']]
      ])
      ->whereIn('subjects.id', $subjectArray)
      ->orderBy('subjects.result_order', 'ASC')->get();

    $formTeacher = DB::table('form_teachers')->select('form_teachers.staff_id', 'staff.lastName', 'staff.firstName')->leftJoin('staff', 'form_teachers.staff_id', '=', 'staff.id')->where([
        ['form_teachers.academic_session_id', $formItems['sessionId']],
        ['form_teachers.class_id', $formItems['classId']],
        ['form_teachers.school_id', $formItems['schoolId']],
        ['staff.school_id', $formItems['schoolId']],
      ])->get();
    // dd($formTeacher);
    $teacherDetails = ['schoolHead' => $classroom->SchoolSection->sectionHead, 'schoolHeadSign' => $classroom->SchoolSection->sectionHeadSign, 'formTeacher' => $formTeacher];

    // $student = Student::where([['school_id', '=', $school->id], ['id', '=', $student]])->first();

    $shwData = DB::select("SELECT h1, h2, w1, w2 FROM students_hw WHERE student_id=$student[id] AND academic_session_id=$formItems[sessionId] AND term=$formItems[termId]");

    // dd($shwData, $student['id']);

    return response()->json([
      'studentRes' => $studentDetails,
      'subjectRes' => $subjectNames,
      'resultRes' => $allScores,
      'selectedStudent' => $studentScores,
      'overAllScores' => $overAllScores,
      'studentComments' => $stdComments,
      'teacherDetails' => $teacherDetails,
      'gradingFormat' => $gradingFormat,
      // 'resumeDate'=>$resumeDate,
      'shw' => $shwData
    ]);
  }


  public function fetchClassAnnual($formItems, $classroom)
  {
    // dd($request->all());

    $selectArray1 = [
      ['academic_session_id', $formItems['sessionId']],
      ['class_id', $formItems['classId']],
      ['school_id', $formItems['schoolId']],
      // ['term_id', $formItems['termId']],
      ['status', '1']
    ];
    $selectArray2 = [
      ['academic_session_id', $formItems['sessionId']],
      ['class_id', $formItems['classId']],
      ['school_id', $formItems['schoolId']],
      ['term_id', $formItems['termId']]
    ];

    $allStudentsId = DB::table('scores')->select('student_id')->where($selectArray1)->groupBy('student_id')->get();

    $allSubjects = DB::table('scores')->select('subject_id')->where($selectArray1)->groupBy('subject_id')->get();

    $allScores = DB::table('scores')->select('subject_id', 'student_id', 'TOTAL')->where($selectArray1)->get();

    // $classScores = DB::table('scores')->where($selectArray1)->get();
    $classScores = DB::table('scores')->select(
      'scores.term_id',
      'scores.subject_id',
      'scores.student_id',
      'scores.TOTAL',
      'subjects.result_order'
    )->where($selectArray1)->leftJoin('subjects', 'scores.subject_id', '=', 'subjects.id')->orderBy('subjects.result_order', 'ASC')->get();
    $school = $classroom->School;

    $allLevels =  DB::table('school_classes')->select('id')->where('level', $classroom->level)->get();
    $overAllScores = [];
    foreach ($allLevels as $item) {
      foreach ($item as $croom) {
        $overAllScores[] = DB::table('scores')->select('subject_id', 'student_id', 'TOTAL')->where([
            ['academic_session_id', $formItems['sessionId']],
            ['class_id', $croom],
            ['school_id', $school->id],
            // ['term_id', $formItems['termId']],
            ['status', '1']
          ])->get();
      }
    }
    // dd($school);
    // $assessFormat = DB::table('assessment_formats')->where('schoolId', $school->id)->orderBy('formatType', 'ASC')->get();
    $assessId = (array) DB::table('school_sections')->select('assessment_format_id')->where([
      ['school_id', $classroom->School->id],
      ['id', $classroom->SchoolSection->id],
    ])->first();
    // dd($assessFormat);

    //extract all the ids into array
    $idArray = [];
    foreach ($allStudentsId as $key => $stid) {
      foreach ((array)$stid as $key2 => $val) {
        $idArray[] = $val;
      }
    }

    //extract subject ids into array 
    $subjectArray = [];
    foreach ($allSubjects as $key => $sub) {
      foreach ((array)$sub as $key2 => $val) {
        $subjectArray[] = $val;
      }
    }

    $gradeId = (array) DB::table('school_sections')->select('grading_format_id')->where([
      ['school_id', $classroom->School->id],
      ['id', $classroom->SchoolSection->id],
    ])->first();
    // dd($assessFormat);
    $gradingFormat = DB::table('grading_formats')->where([
      ['school_id', $classroom->School->id],
      ['format_id', $gradeId['grading_format_id']],
    ])->orderBy('minScore', 'DESC')->get();

    // $gradingFormat = DB::table('gradingformat')->where([
    //                         ['schoolId', $school->id],
    //                         // ['sectionId', $classroom->sectionID]
    //                         ])->get();


    $classComments = DB::table('termly_comments')->where($selectArray2)->get();
    // $traitRating = DB::table('school_trait_rating')->select('description', 'rating')->where('school_id', $school->id)->get();

    $studentDetails = DB::table('students')->select('id', 'firstName', 'lastName', 'otherName', 'regNo')
      ->where('school_id', $formItems['schoolId'])
      ->whereIn('id', $idArray)
      ->orderBy('lastName', 'ASC')
      ->get();


    $shwData = DB::table('students_hw')->select('h1', 'h2', 'w1', 'w2')->where('school_id', $formItems['schoolId'])
      ->where('academic_session_id', $formItems['sessionId'])
      ->where('term', $formItems['termId'])
      ->whereIn('student_id', $idArray)->get();

    // dd($shwData);
    // $shwData = DB::select("SELECT h1, h2, w1, w2 FROM students_hw WHERE student_id=$student[id] AND academic_session_id=$formItems[sessionId] AND term=$formItems[termId]");

    // $subjectNames = DB::table('subjects')->select('id', 'title', 'code')->whereIn('id', $subjectArray)->orderBy('result_order', 'ASC')->get();
    $subjectNames = DB::table('subjects')->select('subjects.id', 'subjects.code', 'subjects.title', 'staff.signature', 'student_subjects.staff_id')
      ->leftJoin('student_subjects', 'student_subjects.subject_id', '=', 'subjects.id')
      ->leftJoin('staff', 'staff.id', '=', 'student_subjects.staff_id')
      ->where([
        ['student_subjects.class_id', $formItems['classId']],
        ['student_subjects.school_id', $school->id],
        ['student_subjects.academic_session_id', $formItems['sessionId']],
        ['staff.school_id', $school->id],
      ])
      ->whereIn('subjects.id', $subjectArray)
      ->orderBy('subjects.result_order', 'ASC')->get();
    // $classTraitAssess = DB::table('student_trait_assessments')->select('student_id', 'traitRating')->where($selectArray2)->get();
    // $schoolTraits = DB::table('schooltraits')->select('schooltraits.traitId', 'traitdefinition.name')->where('schoolId', $school->id)->leftJoin(
    //                 'traitdefinition', 'schooltraits.traitId', '=', 'traitdefinition.id')->get();

    $schoolTraits1 = (array) DB::table('school_traits')->select('trait_id')->where('school_id', $classroom->School->id)->first();
    // $traitsIdArr = $schoolTraits1 ? ((array) json_decode($schoolTraits1['trait_id']))['traits'] : [];
    // $schoolTraits = DB::table('trait_definition')->select('name', 'id')->whereIn('id', $traitsIdArr)->get();

    $formTeacher = DB::table('form_teachers')->select('form_teachers.staff_id', 'staff.lastName', 'staff.firstName')->leftJoin('staff', 'form_teachers.staff_id', '=', 'staff.id')->where([
        ['form_teachers.academic_session_id', $formItems['sessionId']],
        ['form_teachers.class_id', $formItems['classId']],
        ['form_teachers.school_id', $formItems['schoolId']],
        ['staff.school_id', $formItems['schoolId']],
      ])->get();



    // $resumeDate = DB::table('term_config')->select('startDate')->where([
    //                         ['academic_session_id', $formItems['sessionId']],
    //                         ['school_id', $school->id],
    //                         ['term_id', $formItems['termId']],
    //                     ])->get();

    // dd($schoolTraits);
    $teacherDetails = ['schoolHead' => $classroom->SchoolSection->sectionHead, 'schoolHeadSign' => $classroom->SchoolSection->sectionHeadSign, 'formTeacher' => $formTeacher];

    // dd("SHW: ", $shwData);

    return response()->json([
      'studentRes' => $studentDetails,
      'subjectRes' => $subjectNames,
      'resultRes' => $allScores,
      'classScores' => $classScores,
      'overAllScores' => $overAllScores,
      // 'assessFormat'=>$assessFormat,
      // 'schoolTraits'=>$schoolTraits,
      // 'traitRating'=>$traitRating,
      // 'classTraits'=>$classTraitAssess,
      'classComments' => $classComments,
      'teacherDetails' => $teacherDetails,
      // 'classIdList'=>$idArray,
      'gradingFormat' => $gradingFormat,
      // 'resumeDate'=>$resumeDate,
      'shw' => $shwData
    ]);
  }

  public function deleteStudentResult(Request $request, $student)
  {
    // dd($request->all());
    $formItems = $request->all();

    $userPassword = $request->user()->password;
    $inputPassword = $formItems["password"];

    $passwordCorrect = Hash::check($inputPassword, $userPassword);


    if ($passwordCorrect) {

      $school = School::where('id', $formItems['schoolId'])->first();
      $classroom = Classroom::where([['id', '=', $formItems['classId']], ['school_id', '=', $formItems['schoolId']]])->first();
      $student = Student::where([['school_id', '=', $school->id], ['id', '=', $student]])->first();

      $deleteResult = DB::table('scores')->where([
        'school_id' => $school->id,
        'class_id' => $classroom->id,
        'academic_session_id' => $formItems['sessionId'],
        'term_id' => $formItems['termId'],
        'student_id' => $student->id
      ])->update(["status" => '0']);

      // dd($deleteResult);

      return response()->json(["deleted" => $deleteResult]);
    } else {
      return response()->json(["unauthorized" => true]);
    }
  }
}

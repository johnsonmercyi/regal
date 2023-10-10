<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classroom;
use App\School;
use DB;

class SubjectsController extends Controller
{
    public function assignView(School $school)
    {
        // $subjects = DB::table('subjects')->get();
        $assignedSubjects = DB::table('student_subjects')->select('subject_id')->where([
            ['academic_session_id', $school->academic_session_id],
            ['school_id', $school->id],
        ])->groupBy('subject_id')->get();
        $subjectsArr = [];
        if (count($assignedSubjects) > 0) {
            foreach ($assignedSubjects as $subt) {
                $subjectsArr[] = ((array)$subt)['subject_id'];
            }
        }
        // dd(count($assignedSubjects));

        $subjects = DB::table('subjects')->whereIn('id', $subjectsArr)->orderBy('title', 'ASC')->get();
        $acadSession = DB::table('academic_sessions')->get();
        $allClass = DB::table('school_classes')->where([
            ['school_id', $school->id],
            ['status', '1'],
        ])->orderBy('level', 'ASC')->get();

        return view('subjects.assign', compact('school', 'subjects', 'acadSession', 'allClass'));
    }

    /** CHINWATAKWEAKU  FUNCTIONS STARTS */

    public function nurserySubjects(School $school)
    {

        $subjectCategory = DB::table('nursery_subjects_category')->select('*')->get();
        $nurserySubjects = DB::table('nursery_subjects')->select('*')->get();
        //dd($subjectCategory);

        return view('subjects.nurserySubjects', compact('school', 'subjectCategory', 'nurserySubjects'));
    }

    public function nurserySubjectCategory(Request $request)
    {
        $data = $request->all();
        // dd($data[0]); 
        $category = DB::table('nursery_subjects_category')->insert([
            'title' => $data[0]
        ]);
        return response()->json(['success' => $category]);
    }
    public function nurserySubject(Request $request)
    {
        $data = $request->all();
        //dd($data); 
        $nurserySubject = DB::table('nursery_subjects')->insert([
            'subject_category_id' => $data['categoryID'],
            'title' => $data['nurserysubject'],
        ]);
        return response()->json(['success' => $nurserySubject]);
    }

    /** CHINWATAKWEAKU INITIALIZATIONS FUNCTIONS ENDS */


    public function selectStudents(Request $request, $classroom)
    {
        $classroom = Classroom::where([['id', $classroom], ['school_id', $request->user()->school_id]])->first();
        $classForm = $request->all();
        // dd($request);
        $oldAssignedStudents = DB::table('student_subjects')->select('students', 'subjectType')->where([
            ['academic_session_id', $classForm['sessionId']],
            ['school_id', $classForm['schoolId']],
            // ['termId', $classForm['termId']],
            ['class_id', $classForm['classId']],
            ['subject_id', $classForm['subjectId']]
        ])->get();
        $students = DB::table('students')->select('students.id', 'students.firstName', 'students.lastName', 'students.otherName', 'students.regNo')->where([
                ['class_students_list.class_id', $classroom->id],
                ['class_students_list.school_id', $classForm['schoolId']],
                ['class_students_list.academic_session_id', $classForm['sessionId']],
                ['class_students_list.status', 'Active']
            ])->leftJoin('class_students_list', 'class_students_list.student_id', '=', 'students.id')->orderBy('lastName', 'ASC')->get();

        return response()->json(['students' => $students, 'oldMembers' => $oldAssignedStudents]);
    }

    public function assignStudentsStore(Request $request)
    {
        $data = $request->all();
        // $data['teacherId'] = 3;
        $checkExist = DB::table('student_subjects')->where(
            [
                'academic_session_id' => $data['sessionId'],
                // 'termId'=>$data['termId'],
                'class_id' => $data['classId'],
                'school_id' => $data['schoolId'],
                'subject_id' => $data['subjectId'], 'status' => '1'
            ]
        )->exists();

        /*********DEACTIVATE RESULTS FOR ANY REMOVED STUDENT********* */
        $removedStudents = ((array) json_decode($data['removed']))['removed'];
        if (count($removedStudents) > 0) {
            // dd($removedStudents, 'removed');
            foreach ($removedStudents as $rem) {
                $result = DB::table('scores')->where(
                    [
                        'academic_session_id' => $data['sessionId'],
                        'class_id' => $data['classId'],
                        'school_id' => $data['schoolId'],
                        'subject_id' => $data['subjectId'],
                        'student_id' => $rem
                    ]
                )->exists();
                if ($result) {
                    DB::table('scores')->where(
                        [
                            'academic_session_id' => $data['sessionId'],
                            'class_id' => $data['classId'],
                            'school_id' => $data['schoolId'],
                            'subject_id' => $data['subjectId'],
                            'student_id' => $rem
                        ]
                    )->update(["status" => '0']);
                }
            }
        }

        // dd(((array) json_decode($data['students']))['students']);
        if ($checkExist) {
            DB::table('student_subjects')->where(
                [
                    'academic_session_id' => $data['sessionId'],
                    // 'termId'=>$data['termId'],
                    'school_id' => $data['schoolId'],
                    'class_id' => $data['classId'],
                    'subject_id' => $data['subjectId']
                ]
            )->update(
                ['students' => $data['students']]
            );
        }
        // dd($insertStudents);
        return response()->json(['success' => $checkExist]);
    }

    public function assignClassStore(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $storeAssignedSubject = DB::table('student_subjects')->updateOrInsert(['academic_session_id' => $data['academic_session_id'], 'class_id' => $data['class_id'], 'school_id' => $data['school_id'], 'subject_id' => $data['subject_id']], $data);

        return response()->json(['storeSuccess' => $storeAssignedSubject]);
    }

    public function assignSubjectsClassStore(Request $request)
    {
        $dataArr = $request->all();
        // dd($dataArr);
        foreach ($dataArr as $data) {
            $storeAssignedSubject = DB::table('student_subjects')->updateOrInsert(['academic_session_id' => $data['academic_session_id'], 'class_id' => $data['class_id'], 'school_id' => $data['school_id'], 'subject_id' => $data['subject_id']], $data);
        }

        return response()->json(['storeSuccess' => $storeAssignedSubject]);
    }

    public function classAssignView(School $school, $classroom, $academicSession, $term)
    {

        try {

            $section_id = request()->input('section_id');
            $classroom = Classroom::where([['id', $classroom], ['school_id', $school->id]])->first();

            $acadSession = DB::table('academic_sessions')->get();
            $teachers = DB::table('staff')->select('id', 'firstName', 'lastName', 'otherNames')->where([
                ['school_id', $school->id],
                ['status', '1'],
            ])->orderBy('lastName', 'ASC')->get();

            $assignedSubjects = null;
            $unassignedSubjects = null;

            $assignedSubjectsId = DB::table('student_subjects')->select('subject_id')->where(
                [
                    'academic_session_id' => $academicSession,
                    'section_id' => $section_id,
                    'school_id' => $school->id,
                    'class_id' => $classroom->id, 'status' => '1'
                ]
            );

            if ($section_id === "1") {
                $assignedSubjects = DB::table('student_subjects')->select(
                    'student_subjects.subject_id',
                    'student_subjects.staff_id',
                    'student_subjects.subjectType',
                    'staff.lastName',
                    'staff.firstName',
                    'staff.otherNames',
                    'subjects.title'
                )
                    ->where(
                        [
                            'academic_session_id' => $academicSession,
                            // 'termId'=>$term,
                            'student_subjects.school_id' => $school->id,
                            'staff.school_id' => $school->id,
                            'class_id' => $classroom->id,
                            'student_subjects.status' => '1',
                            'section_id' => $section_id
                        ]
                    )
                    ->leftJoin('staff', 'student_subjects.staff_id', '=', 'staff.id')
                    ->leftJoin('subjects', 'student_subjects.subject_id', '=', 'subjects.id')
                    ->orderBy('title', 'ASC')
                    ->get();

                $unassignedSubjects = DB::table('subjects')->select('id', 'title')->whereNotIn('id', $assignedSubjectsId)->orderBy('title', 'ASC')->get();
            } else if ($section_id === "2") {
                // dd("Section Id: ", $section_id);
                $assignedSubjects = DB::table('student_subjects')->select(
                    'student_subjects.subject_id',
                    'student_subjects.staff_id',
                    'student_subjects.subjectType',
                    'staff.lastName',
                    'staff.firstName',
                    'staff.otherNames',
                    'nursery_subjects.title'
                )
                    ->where(
                        [
                            'academic_session_id' => $academicSession,
                            // 'termId'=>$term,
                            'student_subjects.school_id' => $school->id,
                            'staff.school_id' => $school->id,
                            'class_id' => $classroom->id,
                            'student_subjects.status' => '1',
                            'section_id' => $section_id
                        ]
                    )
                    ->leftJoin('staff', 'student_subjects.staff_id', '=', 'staff.id')
                    ->leftJoin('nursery_subjects', 'student_subjects.subject_id', '=', 'nursery_subjects.id')
                    ->orderBy('title', 'ASC')
                    ->get();


                // if (count($assignedSubjectsId) > 0) {

                // }
                $unassignedSubjects = DB::table('nursery_subjects')->select('id', 'title')->whereNotIn('id', $assignedSubjectsId)->orderBy('title', 'ASC')->get();

                // dd("outcome: ", $unassignedSubjects);
            }
        } catch (\Exception $e) {
            # code...
            dd($e->getMessage());
        }



        // dd($unassignedSubjects);

        return view('subjects.subjectTeacher', compact('classroom', 'school', 'acadSession', 'teachers', 'unassignedSubjects', 'assignedSubjects', 'section_id'));
    }

    public function classAssignSelect(School $school)
    {
        // $data = $classroom->School;
        // $acadSession = DB::table('academicsession')->get();
        // $teachers = DB::table('teachers')->select('id', 'firstName', 'lastName', 'otherNames')->where('schoolID', $school->id)->get();
        $allClass = DB::table('school_classes')->where([
            ['school_id', $school->id],
            ['status', '1'],
        ])->orderBy('level', 'ASC')->get();
        $schoolSections = DB::table('school_sections')->where([
            ['school_id', $school->id],
            ['status', 'Active'],
        ])->get();
        $teachers = DB::table('staff')->select('id', 'firstName', 'lastName', 'otherNames')->where([
            ['school_id', $school->id],
            ['status', '1'],
        ])->get();

        return view('subjects.classAssign', compact('allClass', 'school', 'schoolSections', 'teachers'));
    }

    public function classAssignList(School $school)
    {
        $allClass = DB::table('school_classes')->where([
            ['school_id', $school->id],
            ['status', '1'],
        ])->orderBy('level', 'ASC')->get();

        return view('subjects.classAssignList', compact('allClass', 'school'));
    }

    public function sectionClasses(Request $request)
    {
        $data = $request->all();
        $sectionClasses = DB::table('school_classes')->where(['section_id' => $data['sectionId'], 'status' => '1', 'school_id' => $request->user()->school_id])->orderBy('level', 'ASC')->get();
        return response()->json(['sectionClasses' => $sectionClasses]);
    }


    // public function sectionSubjects(Request $request){
    //     $data = $request->all();
    //     $subjects = DB::table('subjects')->get();
    //     $sectionSubjects = DB::table('sectionsubjects')->select('sectionsubjects.subjectId', 'sectionsubjects.teacherId')->where([
    //                     ['sessionId', $data['sessionId']],
    //                     ['sectionId', $data['sectionId']],
    //                     ['status', '1'],
    //                     ])->get();
    //     return response()->json(['sectionSubjects'=>$sectionSubjects, 'subjects'=>$subjects]);
    // }

    // public function sectionSubjectStore(Request $request){
    //     $data = $request->all();
    //     // dd($data);
    //     foreach($data['classArr'] as $croom){
    //         DB::table('student_subjects')->insert($croom);
    //     }
    //     $storeSuccess = DB::table('sectionsubjects')->insert($data['sectionObj']);

    //     return response()->json(["storeSuccess"=>$storeSuccess]);
    // }

    // public function updateSubjectAssign(Request $request){
    //     $data = $request->all();

    //     $data['status'] = '0';
    //     if(isset($data['sectionId'])){
    //         $updateSuccess = DB::table('sectionsubjects')->where(
    //             ['sessionId'=>$data['sessionId'], 
    //             'sectionId'=>$data['sectionId'],
    //             'subjectId'=>$data['subjectId'],
    //             'schoolId'=>$data['schoolId'],
    //             ])->update($data);
    //     } else if (isset($data['classId'])){
    //         $updateSuccess = DB::table('sectionsubjects')->where(
    //             ['sessionId'=>$data['sessionId'], 
    //             'classId'=>$data['classId'],
    //             'subjectId'=>$data['subjectId'],
    //             'schoolId'=>$data['schoolId'],
    //             ])->update($data);
    //     }

    //     return response()->json(['updated'=>$updateSuccess]);
    // }

    public function removeSubjectAssign(Request $request)
    {
        $data = $request->all();

        // $data['status'] = '0';
        if (isset($data['sectionId'])) {
            $updateSuccess = DB::table('sectionsubjects')->where(
                [
                    'academic_session_id' => $data['sessionId'],
                    'section_id' => $data['sectionId'],
                    'subject_id' => $data['subjectId'],
                    'school_id' => $data['schoolId'],
                ]
            )->update(['status' => '0']);
        } else if (isset($data['classId'])) {
            $updateSuccess = DB::table('student_subjects')->where(
                [
                    'academic_session_id' => $data['sessionId'],
                    'class_id' => $data['classId'],
                    'subject_id' => $data['subjectId'],
                    'school_id' => $data['schoolId'],
                ]
            )->update(['status' => '0']);
        }

        return response()->json(['updated' => $updateSuccess]);
    }

    public function subjectOrder(School $school)
    {
        $assignedSubjects = DB::table('student_subjects')->select('subject_id')->where([
            ['academic_session_id', $school->academic_session_id],
            ['school_id', $school->id],
        ])->groupBy('subject_id')->get();
        $subjectsArr = [];
        if (count($assignedSubjects) > 0) {
            foreach ($assignedSubjects as $subt) {
                $subjectsArr[] = ((array)$subt)['subject_id'];
            }
        }
        // dd(count($assignedSubjects));

        $subjects = DB::table('subjects')->whereIn('id', $subjectsArr)->orderBy('result_order', 'ASC')->get();

        return view('subjects.subjectOrder', compact('school', 'subjects'));
    }

    public function orderStore(Request $request)
    {
        $order = $request->all();

        foreach ($order as $item) {
            DB::table('subjects')->where('id', $item['id'])->update(['result_order' => $item['result_order']]);
        }

        return response()->json(['success' => 'success']);
    }
}

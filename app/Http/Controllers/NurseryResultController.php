<?php

namespace App\Http\Controllers;

use App\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NurseryResultController extends Controller
{
    public function showStudentsView(School $school)
    {
        $allClasses = DB::table('school_classes')->where([
            ['school_id', $school->id],
            ['section_id', '2'],
            ['status', '1'],
        ])->orderBy('level', 'ASC')->get();
        $academicSessions = DB::table('academic_sessions')->get();

        return view('nursery_result.index', compact('school', 'allClasses', 'academicSessions'));
    }

    public function showStudentResultView(School $school)
    {
        $studentId = request()->input('st_id');
        $regNo = request()->input('r_no');
        $classId = request()->input('cl_id');
        $sessionId = request()->input('se_id');
        $termId = request()->input('tm_id');

        $term = "";

        // Initialize term
        if ($termId === "1") {
            $term = "1st Term";
        } else if ($termId === "2") {
            $term = "2nd Term";
        } else if ($termId === "3") {
            $term = "3rd Term";
        }

        $school = DB::select("SELECT `name` FROM schools;");

        $shwData = DB::select("SELECT student_id, h1, h2, w1, w2 FROM students_hw WHERE academic_session_id=$sessionId AND term=$termId AND student_id=$studentId");

        // dd($shwData);

        // Fetch session
        $session = DB::select("SELECT sessionName FROM academic_sessions where id=$sessionId");

        $studentName = DB::select("SELECT concat(ifnull(firstName, ''), ' ', ifnull(lastName, ''), ' ', ifnull(otherName, '')) name FROM students WHERE id=$studentId AND regNo='$regNo'");

        $studentClass = DB::select("SELECT concat(ifnull(level, ''), ifnull(suffix, '')) student_class FROM school_classes WHERE id=$classId");

        $age = DB::select("SELECT (YEAR(current_date()) - YEAR(dob)) age FROM students WHERE id=$studentId");

        $subjectCategories = DB::select("SELECT id, title FROM nursery_subjects_category");

        $subjects = DB::select("SELECT
        concat(ifnull(students.firstName, ''), ' ', ifnull(students.lastName, ''), ifnull(students.otherName, '')) name,
        concat(ifnull(school_classes.level, ''), ' ', ifnull(school_classes.suffix, '')) student_class,
        school_sections.sectionName section,
        nursery_subjects.title,
        nursery_subjects.subject_category_id,
        scores.EXAM score
        
        FROM scores
        
        INNER JOIN students ON scores.student_id = students.id
        INNER JOIN school_classes ON scores.class_id = school_classes.id
        INNER JOIN school_sections ON school_classes.section_id = school_sections.id
        INNER JOIN nursery_subjects ON scores.subject_id = nursery_subjects.id
        
        WHERE student_id=$studentId
        AND term_id=$termId
        AND academic_session_id=$sessionId
        AND class_id=$classId
        AND school_sections.id=2");

        $startDate = DB::select("SELECT startDate FROM term_config WHERE academic_session_id=$sessionId AND term_id=$termId");

        $comments = DB::select("SELECT formTeacherComment, headTeacherComment FROM termly_comments
        WHERE academic_session_id=$sessionId
        AND term_id=$termId
        AND student_id=$studentId");

        $classTeachers = DB::select("SELECT concat(ifnull(firstName, ''), ' ', ifnull(lastName, ''), ' ', ifnull(otherNames, '')) name
        FROM form_teachers
        INNER JOIN staff ON form_teachers.staff_id = staff.id
        WHERE academic_session_id=$sessionId
        AND class_id=$classId");

        $sectionHeadData = DB::select("SELECT * FROM school_sections WHERE id=2");
        $sectionHeadName = $sectionHeadData[0]->sectionHead;
        $sectionHeadSign = $sectionHeadData[0]->sectionHeadSign;

        $filePath = 'images/RTNPS/photo/school/'.$sectionHeadSign;
        
        if (Storage::disk('public')->exists($filePath)) {
            $sectionHeadSignFile = Storage::disk('public')->get($filePath);
        } else {
            $sectionHeadSignFile = null;
        }

        // dd($startDate, $comments, $classTeacher);

        // foreach ($variable as $key => $value) {
        //     # code...
        // }

        // dd($term, $session, $studentName, $studentClass, $age);

        // substr_replace("", "", 0, 7);

        return view('nursery_result.result_page', compact('term', 'session', 'studentName', 'studentClass', 'age', 'subjectCategories', 'subjects', 'startDate', 'comments', 'classTeachers', 'shwData', 'school', 'sectionHeadName', 'sectionHeadSignFile'));
    }

    public function fetchStudents()
    {

        $school_id = request()->input('school_id');
        $term_id = request()->input('term_id');
        $session_id = request()->input('session_id');
        $class_id = request()->input('class_id');
        $data = null;
        $sql = "";
        $term = "";

        // dd($term_id);

        if ($term_id === "1") {
            $term = "first_term";
        } else if ($term_id === "2") {
            $term = "second_term";
        } else if ($term_id === "3") {
            $term = "third_term";
        }

        try {
            $sql = "SELECT
            students.id,
            students.school_id,
            concat(ifnull(firstName, ''), ' ', ifnull(lastName, ''), ' ', ifnull(otherName, '')) name,
            regNo,
            concat(ifnull(sc.level, ''), ' ', ifnull(sc.suffix, ' ')) student_class
            FROM students
    
            INNER JOIN class_students_list csl on students.id = csl.student_id
            INNER JOIN school_classes sc on csl.class_id = sc.id
    
            WHERE academic_session_id = $session_id AND sc.id = $class_id AND csl." . $term . "='1' AND students.school_id=$school_id";

            $data = DB::select($sql);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
        return response()->json(["data" => $data]);
    }
}

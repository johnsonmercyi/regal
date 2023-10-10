<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\School;
use Illuminate\Support\Facades\DB;

class ShwController extends Controller
{

    public function index(School $school, Request $request)
    {
        try {
            $academic_sessions = DB::select("SELECT * FROM academic_sessions;");
            $term_and_academic_session_ids = DB::select("SELECT academic_session_id, current_term_id FROM schools LIMIT 1;");
            $studentsHwData = DB::select(
                "SELECT students.id, regNo,
                concat(ifnull(firstName, ''), ' ', ifnull(lastName, ''), ' ', ifnull(otherName, '')) name,
                gender,
                csl.academic_session_id,
                csl.class_id,
                concat(ifnull(sc.level, ''), ifnull(sc.suffix, '')) student_class,
                ifnull(h1, 'N/A') h1, ifnull(h2, 'N/A') h2, ifnull(w1, 'N/A') w1, ifnull(w2, 'N/A') w2
                
                FROM students
                
                INNER JOIN class_students_list csl on students.id = csl.student_id
                INNER JOIN school_classes sc on csl.class_id = sc.id
                INNER JOIN students_hw sh on students.id = sh.student_id
                WHERE csl.academic_session_id=$school->academic_session_id
                AND sh.term=$school->current_term_id
                ORDER BY name ASC;"
            );

            $students = DB::select("SELECT students.id, regNo,
            concat(ifnull(firstName, ''), ' ', ifnull(lastName, ''), ' ', ifnull(otherName, '')) name
            FROM students WHERE students.id NOT IN (SELECT student_id FROM students_hw WHERE academic_session_id=$school->academic_session_id AND term=$school->current_term_id) ORDER BY name ASC;");
            
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
        // dd("works");
        return view('students.shw_index', compact('school', 'academic_sessions', 'studentsHwData', 'students', 'term_and_academic_session_ids'));
        // return response()->json(["status" => "i'm right here!"]);
    }

    public function create() {
        $data = request()->input("data");
        try {
            $outcome = DB::insert("INSERT INTO students_hw 
            (global_id, student_id, school_id, academic_session_id, term, h1, h2, w1, w2) 
            VALUES (NULL, $data[student_id], $data[school_id], $data[academic_session_id], $data[term], $data[h1], $data[h2], $data[w1], $data[w2]);");
        }catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
        return response()->json(["data" => $outcome]);
    }

    public function update() {
        $data = request()->input("data");
        try {
            $outcome = DB::update("UPDATE students_hw SET h1=$data[h1], h2=$data[h2], w1=$data[w1], w2=$data[w2] WHERE student_id=$data[student_id] AND academic_session_id=$data[academic_session_id] AND term=$data[term];");
        }catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
        return response()->json(["data" => $outcome]);
    }

    public function delete() {
        $data = request()->input("data");
        try {
            $outcome = DB::update("DELETE FROM students_hw WHERE student_id=$data[student_id] AND academic_session_id=$data[academic_session_id] AND term=$data[term];");
        }catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
        return response()->json(["data" => $outcome]);
    }

    public function search() {
        $data = request()->input("data");
        try {
            $term_and_academic_session_ids = DB::select("SELECT academic_session_id, current_term_id FROM schools LIMIT 1;");
            $studentsHwData = DB::select(
                "SELECT students.id, regNo,
                concat(ifnull(firstName, ''), ' ', ifnull(lastName, ''), ' ', ifnull(otherName, '')) name,
                gender,
                csl.academic_session_id,
                csl.class_id,
                concat(ifnull(sc.level, ''), ifnull(sc.suffix, '')) student_class,
                ifnull(h1, 'N/A') h1, ifnull(h2, 'N/A') h2, ifnull(w1, 'N/A') w1, ifnull(w2, 'N/A') w2
                
                FROM students
                
                INNER JOIN class_students_list csl on students.id = csl.student_id
                INNER JOIN school_classes sc on csl.class_id = sc.id
                INNER JOIN students_hw sh on students.id = sh.student_id
                WHERE csl.academic_session_id=$data[academic_session_id]
                AND sh.term=$data[term] AND concat(ifnull(firstName, ''), ' ', ifnull(lastName, ''), ' ', ifnull(otherName, '')) LIKE '%$data[search]%'
                ORDER BY name ASC;"
            );
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }

        return response()->json(["data" => $studentsHwData, "tasids" => $term_and_academic_session_ids]);
    }
}

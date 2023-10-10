<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
// use DB;
use App\Student;
use App\School;
use App\Guardian;
use App\Classroom;
use Illuminate\Support\Facades\DB;

class StudentsController extends Controller
{
    //
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(School $school)
    {
        // $students = Student::where('schoolId', $school->id);
        $students = DB::table('students')->select('students.id', 'students.firstName', 'students.lastName', 'students.otherName', 'students.gender',
                        'students.regNo', 'students.imgFile', 'school_classes.level', 'school_classes.suffix')->
                        leftJoin('class_students_list', 'students.id', '=', 'class_students_list.student_id')->
                        leftJoin('school_classes', 'school_classes.id', '=', 'class_students_list.class_id')->
                        where([
                            ['class_students_list.school_id', $school->id],
                            ['students.school_id', $school->id],
                            ['school_classes.school_id', $school->id],
                            ['class_students_list.academic_session_id', $school->academic_session_id],
                            ['class_students_list.status', 'Active']
                        ])->get();
        // dd($students);

        return view('students.index', compact('school', 'students'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create(School $school) {
        $states = DB::table('states')->get();
        $lgas = DB::table('lgas')->get();
        $student = new Student();
        $allClass = DB::table('school_classes')->where([
            ['school_id', $school->id],
            ['status', '1'],
            ])->orderBy('level', 'ASC')->orderBy('suffix', 'ASC')->get();
        $religions = DB::table('religions')->get();
        $allSchools = DB::table('schools')->get();
        $maritalStatus = DB::table('marital_statuses')->get();
        $denominations = DB::table('religious_denominations')->get();

        return view('students.create', compact('school', 'states', 'lgas', 'student', 'allClass', 'religions', 'allSchools', 'maritalStatus', 'denominations'));
    }

    public function store(Request $request)
    {
        $data = (array) json_decode($request->input('data'));
       
        // dd($data);
        DB::beginTransaction();
        try{
            $dataStudent = (array) $data['std'];
            $dataGuardian = (array) $data['prt'];
            $dataClassList = (array) $data['schDet'];
            $dataGuardian['school_id'] = $dataStudent['school_id'];

            // $school = School::find($dataStudent['school_id']);
            $school = School::find($request->user()->school_id);

            $pchallenge = $dataStudent['physicalchallenge'];
            $hchallenge = $dataStudent['healthchallenge'];
            $currClass = $dataStudent['current_class_id'];
            unset($dataStudent['physicalchallenge'], $dataStudent['healthchallenge'],
                $dataStudent['id'], $dataStudent['current_class_id']);
            
            $guardian = [];
            if(!isset($dataStudent['parent_id'])){
                $guardian = Guardian::create($dataGuardian);
            }

            $dataStudent['parent_id'] = isset($dataStudent['parent_id']) ? $dataStudent['parent_id'] : $guardian->id;
            $dataStudent['former_class_id'] = '1';
            $dataStudent['last_login_id'] = 0;
            $dataStudent['reg_session'] = $school->academic_session_id;
            $dataStudent['reg_term'] = $school->current_term_id;


            if($dataStudent['imgFile'] != ""){
                $fileNameToStore = $dataStudent['firstName'].'_'.$dataStudent['lastName'].'_'.date('h_i_s_d_m_Y').'.png';
                $imgdata = $dataStudent['imgFile'];
                list($type, $imgdata) = explode(';', $imgdata);
                list(, $imgdata) = explode(',', $imgdata);
                $imgdata = base64_decode($imgdata);
                file_put_contents(storage_path().'/app/public/images/'.$school->prefix.'/passports/'.$fileNameToStore, $imgdata);

                $dataStudent['imgFile'] = $fileNameToStore;
            } else {
                $dataStudent["imgFile"] = 'no_image.jpg';
            }


            $dataStudent['createdBy'] = 4;
            // dd($dataStudent);
            $student = Student::create($dataStudent);

            $regNo = $school->prefix."/STD/".$student->id;
            $student->update(['regNo'=>$regNo]);

            if($pchallenge != ""){
                DB::table("student_health")->insert([
                    "student_id" => $student->id,
                    "school_id" => $dataStudent['school_id'],
                    "challenge" => $pchallenge,
                    "category" => "physical"
                ]);
            }

            if($hchallenge != ""){
                DB::table("student_health")->insert([
                    "student_id" => $student->id,
                    "school_id" => $dataStudent['school_id'],
                    "challenge" => $hchallenge,
                    "category" => "health"
                ]);
            }

            $dataClassList['class_id'] = $currClass;
            $dataClassList['school_id'] = $dataStudent['school_id'];
            $dataClassList['student_id'] = $student->id;
            $dataClassList[$school->term_column()] = '1';
            $dataClassList['status'] = 'Active';

            DB::table('class_students_list')->insert($dataClassList);
            DB::commit();
            
            
            return response()->json([
                'id' => $student->id,
                'regNo' => $student->regNo,
                'firstName' => $student->firstName,
                'lastName' => $student->lastName,
                'otherName' => $student->otherName,
                'phoneNo' => $student->phoneNo,
                'img' => $student->imgFile,
                'classroom' => $student->classroom()->className(),
                'model' => 'student',
                'prefix' => $school->prefix
            ]);
        } catch(\Exception $e){
            // dd($e);
            DB::rollback();
            return response()->json(["status" => "Error"]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Guardian  $guardian
     * @return \Illuminate\Http\Response
     */
    public function edit(School $school, $student) {
        $student = Student::where([['id', $student], ['school_id', $school->id]])->first();
        $states = DB::table('states')->get();
        $lgas = DB::table('lgas')->get();
        $allClass = DB::table('school_classes')->where([
            ['school_id', $school->id],
            ['status', '1'],
            ])->get();
        $religions = DB::table('religions')->get();
        $allSchools = DB::table('schools')->get();
        $denominations = DB::table('religious_denominations')->get();

        return view('students.edit', compact('school', 'student', 'lgas', 'states', 'allClass', 'religions', 'allSchools', 'denominations'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $student) {
        $student = Student::where([['id', $student], ['school_id', $request->user()->school_id]])->first();

        /**Receives request data,converts it to array */
        $data = (array) json_decode($request->input('data'));
        $dataStudent = (array) $data['std'];        
        // dd($dataStudent['imgFile']);

        /**update a parent with the return data array*/
        $dataGuardian = (array) $data['prt'];      
        $guardian = null;

        if(!isset($dataStudent['parent_id']) && !$student->guardian){
            $dataGuardian['school_id'] = $dataStudent['school_id'];
            $guardian = Guardian::create($dataGuardian);
        } 
        else if($student->guardian) 
        {
            $student->guardian()->update($dataGuardian);
            $guardian = $student->guardian;
        } 
        // dd($guardian);

        $dataStudent['parent_id'] = isset($dataStudent['parent_id']) ? $dataStudent['parent_id'] : $guardian->id;
 
        // else{
        //     $dataGuardian['school_id'] = $dataStudent['school_id'];
        //     $guardian = Guardian::create($dataGuardian);
        //     $dataStudent['parent_id'] = $guardian->id;
        // }

        foreach($dataStudent as $key=>$dat){
            if($dat == ""){unset($dataStudent[$key]);}
        }

        if(isset($dataStudent['imgFile'])){            
            $fileNameToStore = $dataStudent['firstName'].'_'.$dataStudent['lastName'].'_'.date('h_i_s_d_m_Y').'.png';
            $imgdata = $dataStudent['imgFile'];
            list($type, $imgdata) = explode(';', $imgdata);
            list(, $imgdata) = explode(',', $imgdata);
            $imgdata = base64_decode($imgdata);
            file_put_contents(storage_path().'/app/public/images/'.$student->school->prefix.'/passports/'.$fileNameToStore, $imgdata);
    
            $dataStudent['imgFile'] = $fileNameToStore;
        } 

        /**update a student with the return data array*/
        $student->update($dataStudent);


        return response()->json(['resobj' => $student, 'status'=>'success', 'type'=>'student']);

    }

    public function studentProfile(School $school, $student){
        $student = Student::where([['id', $student], ['school_id', $school->id]])->first();

        // $std = $request->all();
        // dd($student->classroom());
        // $school = $student->school;
        if(Gate::denies('view-student-profile', $student)){
            return redirect()->route('login');
        }
        // dd(Auth::user());
        $stdSess = DB::table('class_students_list')->
                            select('class_students_list.class_id', 'class_students_list.academic_session_id', 'academic_sessions.sessionName', 'school_classes.level', 'school_classes.suffix')->
                            where([
                                ['student_id', $student->id], 
                                ['class_students_list.school_id', $school->id],
                                ['school_classes.school_id', $school->id],
                            ])->
                            leftJoin('academic_sessions', 'class_students_list.academic_session_id', '=', 'academic_sessions.id')->
                            leftJoin('school_classes', 'class_students_list.class_id', '=', 'school_classes.id')->
                            get();
        // $stdDetails = ['name'=> ($student->lastName.', '.$student->firstName.' '. $student->otherName), 'id'=> $student->id];
        // dd($stdDetails, $stdSessions);
        // dd($stdSess);
        return view('login.studentProfile', compact('stdSess', 'student', 'school'));
    }

    public function countFunc(Request $request, School $school){
        $data = $request->all();

        $students = DB::table('class_students_list')->where([
            ['school_id', $school->id],
            ['academic_session_id', $data['sessionId']],
            // ['term_id', $data['termId']],
            ['status', 'Active'],
            ])->count();
        
            
        $female = DB::table('class_students_list')
            ->where([
                ['class_students_list.school_id', $school->id],
                ['students.school_id', $school->id],
                ['class_students_list.academic_session_id', $data['sessionId']],
                // ['term_id', $data['termId']],
                ['students.gender', 'F'],
                ['class_students_list.status', 'Active'],
                ])
            ->leftJoin('students', 'students.id', '=', 'class_students_list.student_id')
            ->count();
        
        $male = DB::table('class_students_list')
            ->where([
                ['class_students_list.school_id', $school->id],
                ['students.school_id', $school->id],
                ['class_students_list.academic_session_id', $data['sessionId']],
                // ['term_id', $data['termId']],
                ['students.gender', 'M'],
                ['class_students_list.status', 'Active'],
                ])
            ->leftJoin('students', 'students.id', '=', 'class_students_list.student_id')
            ->count();

        $teachers = DB::table('staff')->where([
            ['school_id', $school->id],
            ['status', '1']
        ])->count();
        
        $parents = DB::table('student_parents')->where([
            ['school_id', $school->id],
            // ['status', '1']
        ])->count();

        return response()->json(['students'=>$students, 'teachers'=>$teachers, 'parents'=>$parents, 'male'=>$male, 'female'=>$female]);
    }
    
    public function details(School $school, $student){
        $student = Student::where([['id', $student], ['school_id', $school->id]])->first();

        $stdSess = DB::table('class_students_list')->
            select('class_students_list.class_id', 'class_students_list.academic_session_id', 'academic_sessions.sessionName', 'school_classes.level', 'school_classes.suffix')->
            where([
                ['student_id', $student->id],
                ['class_students_list.school_id', $school->id],
                ['school_classes.school_id', $school->id],
            ])->
            leftJoin('academic_sessions', 'class_students_list.academic_session_id', '=', 'academic_sessions.id')->
            leftJoin('school_classes', 'class_students_list.class_id', '=', 'school_classes.id')->
            get();
        // dd($student);
        $guardian = $student->guardian;
        return view('students.details', compact('student', 'school', 'stdSess', 'guardian'));
    }

    public function getGuardian(School $school, $student){
        $student = Student::where([['id', $student], ['school_id', $school->id]])->first();
        $guardian = $student->guardian;
        // dd($guardian);
        return response()->json(["guardian"=>$guardian]);
    }

    public function removeView(School $school){
        $allClass = DB::table('school_classes')->select('id', 'level', 'suffix')->where([
            ['school_id', $school->id],
            ['status', '1'],
            ])->orderBy('level', 'ASC')->get();

        return view('students.remove', compact('school', 'allClass'));
    }
    
    public function remove(Request $request){
        $data = $request->all();
        $removed = [];

        foreach($data as $stud){
            $rem = DB::table('class_students_list')->where([
                "academic_session_id" => $stud["sessionId"],
                "school_id" => $stud["schoolId"],
                "student_id" => $stud["studentId"]
            ])->update(["status" => 'Inactive']);

            if($rem){$removed[] = $stud["studentId"];}

        }
        return response()->json(["removed" => $removed]);
    }

    public function showCapture(School $school){
        $allClass = Classroom::where([
            ['school_id', $school->id],
            ['status', '1'],
            ])->orderBy('level', 'ASC')->orderBy('suffix', 'ASC')->get();

        return view('students.capture', compact('school', 'allClass'));
    }

    public function photoUpdate(Request $request, $student){
        $student = Student::where([['id', $student], ['school_id', $request->user()->school_id]])->first();

        $data = $request->all();
        // dd($data);

        try {
            //code...
            $fileNameToStore = $student->firstName.'_'.$student->lastName.'_photo'.date('h_i_s_d_m_Y').'.png';
            $imgdata = $data['dataURL'];
            list($type, $imgdata) = explode(';', $imgdata);
            list(, $imgdata) = explode(',', $imgdata);
            $imgdata = base64_decode($imgdata);
            file_put_contents(storage_path().'/app/public/images/'.$student->school->prefix.'/passports/'.$fileNameToStore, $imgdata);

            $student->imgFile = $fileNameToStore;
            $student->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // throw $e;
            return response()->json(['success' => false, 'error' => $e]);
        }
    }


    public function promotionView(School $school){
        $sessionId = $school->academic_session_id;
        $nextSessionId = ((int) $sessionId) + 1;

        $currentSession = DB::table('academic_sessions')->where('id', $sessionId)->first();
        $nextSession = DB::table('academic_sessions')->where('id', $nextSessionId)->first();

        $checkPromotion = DB::table('class_students_list')->where('academic_session_id', $nextSessionId)->exists();

        $allClass = DB::table('school_classes')
            ->select('id', 'level', 'suffix')
            ->where([
                ['school_id', '=', $school->id],
                ['status', '=', '1'],
            ])->orderBy('level', 'ASC')->orderBy('suffix', 'ASC')->get();

        $promotableClass = DB::table('school_classes')
        ->select('id', 'level', 'suffix')
        ->where([
            ['school_id', '=', $school->id],
            ['status', '=', '1'],
            ['level', '!=', 'SS3'],
            ['level', '!=', 'BASIC 6']
        ])->orderBy('level', 'ASC')->orderBy('suffix', 'ASC')->get();

        $students = [];

        foreach ($promotableClass as $key => $value) {
            $classStudents = DB::table('students')->select('students.id', 'students.firstName', 'students.lastName', 'students.otherName', 'class_students_list.class_id')->
                leftJoin('class_students_list', 'students.id', '=', 'class_students_list.student_id')->
                where([
                    ['class_students_list.school_id', $school->id],
                    ['students.school_id', $school->id],
                    ['class_students_list.class_id', $value->id],
                    ['class_students_list.academic_session_id', $school->academic_session_id],
                    ['class_students_list.status', 'Active']
                ])->get();
                            
            $students[$value->id] = $classStudents;
        }

        $students = json_encode($students);
        // dd($currentSession, $nextSession, $students, $allClass);
        return view('archive.promotion', compact('school', 'currentSession', 'nextSession', 'students', 'allClass', 'promotableClass', 'checkPromotion'));
    }


    public function promote(Request $request){
        $data = $request->all();
        $school = $request->user()->school;
        // dd($data);
        $studentsData = $data["students"];
        $schoolData = $data["school"];

        $alreadyPromoted = DB::table('class_students_list')->where('academic_session_id', $schoolData["academic_session_id"])->exists();

        if($alreadyPromoted){
            return response()->json(["status" => false]);
        }

        $school->update($schoolData);
        $promoted = DB::table('class_students_list')->insert($studentsData);
        //

        return response()->json(["status" => $promoted]);
    }
    

}

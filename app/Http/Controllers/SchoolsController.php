<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use App\Guardian;
use App\MaritalStatus;
use DB;
use Illuminate\Support\Facades\Hash;

class Schoolscontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $schools = School::all();

    //     return view('schools.index', compact('schools'));
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $schools = new School();

        $regions = DB::table('regions')->get();

        return view('schools.create', compact('schools','regions'));
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(School $school)
    {
        return view('schools.show', compact('school'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function studentCreate(School $school)
    {
        //
        return view('students.create', compact('school'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function guardian(School $school) {

        $guardians = Guardian::where('school_id', $school->id)->get();
        $marital_statuses = MaritalStatus::all();
        //  dd($guardians);

        return view('guardians.index', compact('guardians', 'school', 'marital_statuses'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Guardian  $guardian
     * @return \Illuminate\Http\Response
     */
    public function guardianEdit(School $school, Guardian $guardian) {

        $marital_statuses = MaritalStatus::all();
        // $marital_status = MaritalStatus::where('id', $guardian->maritalStatusId);

        return view('guardians.edit', compact('guardian', 'school', 'marital_statuses'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(School $school)
    {
        $resumeDate = DB::table('term_config')->select('startDate')->where([
            ['academic_session_id', $school->academic_session_id],
            ['school_id', $school->id],
            ['term_id', $school->academic_session_id],
        ])->first();
        // dd($resumeDate);
        $properSession = $school->AcademicSession->sessionName;
        
        if($school->academic_session_id == 3){
            $sessionArr = explode("/", $properSession);
            $properSession = ((int) $sessionArr[0] + 1)."/".((int) $sessionArr[0] + 2);
        }

        if($school->current_term_id <= 2){
            $nextTerm = $school->Term($school->current_term_id + 1);
        } else {
            $nextTerm = $school->Term(1);
        }

        $acadSession = DB::table('academic_sessions')->get();
        return view('schools.edit', compact('school', 'acadSession', 'resumeDate', 'properSession', 'nextTerm'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, School $school)
    {
        $data = $request->all();
        // dd($data);
        $updateCheck = false;

        if(isset($data['current_term_id'])){
            $updateCheck = $school->update($data);
            return response()->json(['success'=>$updateCheck]);
        }

        $updateCheck = DB::table('schools')->where('id', $school->id)->update([
            'name' => $data['schoolName'],
            'address' => $data['schoolAddress'],
            'head' => $data['schoolHead'],
            'motto' => $data['schoolMotto'],
        ]);

        if(isset($data['schoolLogo'])){
            
            $clientExt = $request->file('schoolLogo')->getClientOriginalExtension();
            $schoolLogo = 'schoolLogo_'.date('h_i_s_d_m_Y').'.'.$clientExt;
            $path = $request->file('schoolLogo')->storeAs('public/images/'.$school->prefix.'/photo/school', $schoolLogo);
    
            $data['schoolLogo'] = $schoolLogo;
            $updateCheck = DB::table('schools')->where('id', $school->id)->update([
                'logo' => $schoolLogo,
            ]);
        }

        if(isset($data['headSignature'])){
            
            $clientExt = $request->file('headSignature')->getClientOriginalExtension();
            $headSignature = 'headSignature_'.date('h_i_s_d_m_Y').'.'.$clientExt;
            $path = $request->file('headSignature')->storeAs('public/images/'.$school->prefix.'/photo/school', $headSignature);
    
            $data['headSignature'] = $headSignature;
            $updateCheck = DB::table('schools')->where('id', $school->id)->update([
                'headSignature' => $headSignature,
            ]);
        }

        
        return response()->json(['success'=>$updateCheck]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function resumeDate(Request $request){
        $dateObj = $request->all();

        $dateInsert = DB::table('term_config')->updateOrInsert([
                            'school_id'=>$dateObj['schoolId'],
                            'academic_session_id'=>$dateObj['sessionId'],
                            'term_id'=>$dateObj['termId'],
                            ], ['startDate'=>$dateObj['startDate']]);

        return response()->json(['updated'=>$dateInsert]);
    }

    public function newsletterView(School $school){
        $allClass = DB::table('school_classes')->where([
            ['school_id', $school->id],
            ['status', '1'],
            ])->orderBy('level', 'ASC')->get();
        return view('newsletter.create', compact('school', 'allClass'));
    }

    public function newsletterStore(Request $request){
        $data = $request->all();

        $klass = (array) json_decode($data['details']);
        // dd($klass);

        $newsletter = $data['newsletter']->getClientOriginalName();

        $clientExt = $data['newsletter']->getClientOriginalExtension();
        // dd($clientExt, $dataAssign);
        $fileNameToStore = explode('.', $newsletter)[0].'_'.date('h_i_s_d_m_Y').'.'.$clientExt;
        $path = $data['newsletter']->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);

        foreach ($klass['class_id'] as $key => $val) {
            $storeAssign = DB::table('termly_newsletter')->insert([
                'class_id' => $val,
                'school_id' => $klass['school_id'],
                'term_id' => $klass['term_id'],
                'academic_session_id' => $klass['session_id'],
                'file_name' => $fileNameToStore,
            ]);
            if(!$storeAssign){
                return response()->json(['failed' => $val]);
            }
        }
      
        return response()->json(['success' => 'Success']);
    }

    public function viewNewsletter(School $school){
        $newsletter = DB::table('termly_newsletter')->first();

        return view('newsletter.view', compact('school', 'newsletter'));
    }

    public function viewPasswordGen(School $school){
        $allClass = DB::table('school_classes')
        ->select('school_classes.id', 'school_classes.level', 'school_classes.suffix')
        ->where([
            ['school_classes.school_id', $school->id],
            ['school_classes.status', '1'],
        ])
        ->get();
        return view('passwords.generate', compact('school', 'allClass'));
    }

    public function generatePasswords(Request $request){
        $data = $request->all();
        $returnArr = [];

        $allStudents = DB::table('class_students_list')->select('students.regNo', 'students.lastName', 'students.firstName')
            ->leftJoin('students', 'class_students_list.student_id', '=', 'students.id')
            ->where([
                ['class_students_list.school_id', $data['school_id']],
                ['students.school_id', $data['school_id']],
                ['class_students_list.academic_session_id', $data['academic_session_id']],
                ['class_students_list.class_id', $data['class_id']],
                ['class_students_list.status', "Active"],
            ])
            ->orderBy('lastName', 'ASC')
            ->get()->toArray();
        
        foreach ($allStudents as $key => $value) {
            $newPin = $data['randPin'][$key];
            DB::table('users')->updateOrInsert(
                [
                    'school_id' => $data['school_id'],
                    'name' => $value->regNo
                ],
                [
                    'password' => Hash::make($newPin)
                ]
            );

            $returnArr[] = ['name'=> $value->lastName.' '.$value->firstName , 'regNo' => $value->regNo, 'pin' => $newPin];
        }
        

        // dd($allStudents[0]->regNo, $data);
        return ['success' => true, 'newPins'=>$returnArr];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use App\Staff;
use App\School;
use App\NextOfKin;
use DB;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(School $school)
    {
        //
        $allStaff = Staff::where([
                    ['school_id', $school->id],
                    ['status', '1'],
                    ])->get();
        return view('staff.index', compact('allStaff', 'school'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(School $school)
    {
        $states = DB::table('states')->get();
        $lgas = DB::table('lgas')->select('id', 'lga', 'state_id')->get();
        $stf_position = DB::table('staff_position')->get();
        $stf_category = DB::table('staff_category')->get();
        $grade_level = DB::table('grade_level')->get();
        $subjects = DB::table('subjects')->orderBy('title','ASC')->get();
        $religions = DB::table('religions')->get();
        $denominations = DB::table('religious_denominations')->get();

        return view('staff.create', compact('school', 'states', 'lgas', 'stf_position', 'stf_category', 'grade_level', 'subjects', 'religions', 'denominations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        // dd($_SERVER['DOCUMENT_ROOT']);
        /*
        Encountered a problem of null value, Use name for form instead
        of id. This stores in the DB using the name from the form
        */
        
        // $data = (array) json_decode($request->input('data'));
        $data = $request->all();
        // dd($request->all());
        // $school = School::find($dataStaff['school_id']);
        $school = School::find($request->user()->school_id);

        DB::beginTransaction();
        try{
            $nextOfK = NextOfKin::create([
                "school_id" => $data["stfschool_id"],
                "title" => $data["noktitle"],
                "firstName" => $data["nokfirstName"],
                "otherName" => $data["nokotherName"],
                "lastName" => $data["noklastName"],
                "address" => $data["nokaddress"],
                "relationship" => $data["nokrelationship"],
                "phoneNo" => $data["nokphoneNo"]
                ]);
            
            // $dataStaff = (array) $data['staff'];
            $dataStaff = [
                "status" => $data["stfstatus"],
                "school_id" => $data["stfschool_id"],
                "firstName" => $data["stffirstName"],
                "otherNames" => $data["stfotherNames"],
                "lastName" => $data["stflastName"],
                "title" => $data["stftitle"],
                "marital_status_id" => $data["stfmarital_status_id"],
                "dob" => $data["stfdob"],
                "gender" => $data["stfgender"],
                "state_of_origin_id" => $data["stfstate_of_origin_id"],
                "lga_of_origin_id" => $data["stflgaOfOriginId"],
                "homeTown" => $data["stfhomeTown"],
                "homeAddress" => $data["stfhomeAddress"],
                "appointmentDate" => $data["stfappointmentDate"],
                "category_id" => $data["stfcategory_id"],
                "position" => $data["stfposition"],
                // "salary_grade_id" => $data["stfsalary_grade_id"],
                "religion_id" => $data["stfreligion_id"],
                "denomination_id" => $data["stfdenomination_id"],
                "phoneNo" => $data["stfphoneNo"],
                "email" => $data["stfemail"],
                // "rank_id" => $data["stfrank_id"],
                'next_of_kin_id' => $nextOfK->id,
            ];

            if($data['stfimgFile'] != ""){
                $fileNameToStore = $dataStaff['firstName'].'_'.$dataStaff['lastName'].'_'.date('h_i_s_d_m_Y').'.png';
                $imgdata = $data['stfimgFile'];
                list($type, $imgdata) = explode(';', $imgdata);
                list(, $imgdata) = explode(',', $imgdata);
                $imgdata = base64_decode($imgdata);
                file_put_contents(storage_path().'/app/public/images/'.$school->prefix.'/passports/'.$fileNameToStore, $imgdata);

                $dataStaff['imgFile'] = $fileNameToStore;
            } else {
                $dataStaff['imgFile'] = 'no_image.jpg';
            }

            // Store staff signature
            if(isset($data['stfsignature']) && $data['stfsignature'] != ''){
                // $certName = "no_image.jpg";

                $clientExt = $data['stfsignature']->getClientOriginalExtension();
                $fileNameToStore = $data['stffirstName'].'sign_'.date('h_i_s_d_m_Y').'.'.$clientExt;
                $path = $data['stfsignature']->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);

                $dataStaff['signature'] = $fileNameToStore;
            }

            $staff = Staff::create($dataStaff);

            $regNo = $school->prefix."/STF/".$staff->id;
            $staff->update(['regNo'=>$regNo]);

            //handle qualifications
            if($data['stffslcinstitution'] != ''){
                $certName = "no_image.jpg";
                if(isset($data['stffslccert']) && $data['stffslccert'] != ''){
                    $clientExt = $data['stffslccert']->getClientOriginalExtension();
                    $fileNameToStore = $data['stffirstName'].'fslc_'.date('h_i_s_d_m_Y').'.'.$clientExt;
                    $path = $data['stffslccert']->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);
                    $certName = $fileNameToStore;
                }

                $fslc = DB::table('staff_qualification')->insert([
                    "staff_id" => $staff->id,
                    "institution" => $data['stffslcinstitution'],
                    "grade" => $data['stffslcgrade'],
                    "school_id" => $data["stfschool_id"],
                    "type" => "FSLC",
                    "year" => $data['stffslcyear'],
                    "certificate" => $certName, //****handle */
                ]);
            }
            
            if(isset($data['stfnysccert']) && $data['stfnysccert'] != ''){

                $clientExt = $data['stfnysccert']->getClientOriginalExtension();
                $fileNameToStore = $data['stffirstName'].'nysc_'.date('h_i_s_d_m_Y').'.'.$clientExt;
                $path = $data['stfnysccert']->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);

                $nysc = DB::table('staff_qualification')->insert([
                    "staff_id" => $staff->id,
                    "institution" => "National Youth Service Corps",
                    "school_id" => $data["stfschool_id"],
                    "type" => "NYSC",
                    "year" => $data['stfnyscyear'],
                    "certificate" => $fileNameToStore, //****handle */
                ]);
            }

            //INSERT EACH SSCE SUBJECT
            foreach ($data as $key => $val) {
                if(substr($key, 0, 10) == "stfsscesub"){
                    $num = substr($key, 10);
                    $gradekey = 'stfsscegrade'.$num;
                    DB::table('staff_grades')->insert([
                        "staff_id" => $staff->id,
                        "school_id" => $data["stfschool_id"],
                        "subject" => $val,
                        "grade" => $data[$gradekey]
                    ]);
                }
                
                //INSERT EACH HIGHER INSTITUTION CERTIFICATE
                if(substr($key, 0, 18) == "stfhigherinstitute" && $val != ''){
                    $num = substr($key, strlen($key)-1); //dd($num);
                    $qualkey = 'stfhigherinstqual'.$num;
                    $coursekey = 'stfhighercourse'.$num;
                    $gradekey = 'stfhigherinstgrade'.$num;
                    $yearkey = 'stfhigherinstyear'.$num;
                    $certkey = 'stfhigherinstcert'.$num; $certName = "no_image.jpg";

                    if($data[$certkey] != ''){
                        $clientExt = $data[$certkey]->getClientOriginalExtension();
                        $fileNameToStore = $data['stffirstName'].$num.'Qual_'.date('h_i_s_d_m_Y').'.'.$clientExt;
                        $path = $data[$certkey]->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);
                        $certName = $fileNameToStore;
                    }

                    DB::table('staff_qualification')->insert([
                        "staff_id" => $staff->id,
                        "institution" => $val,
                        "course" => $data[$coursekey],
                        "type" => $data[$qualkey],
                        "school_id" => $data["stfschool_id"],
                        "grade" => $data[$gradekey],
                        "year" => $data[$yearkey],
                        "certificate" => $certName,
                    ]);
                }
            }

            //HANDLE SSCE
            if(isset($data['stfsscecert']) && $data['stfsscecert'] != '' ){
                $certName = 'no_image.jpg';
                if($data['stfsscecert'] != ''){
                    $clientExt = $data['stfsscecert']->getClientOriginalExtension();
                    $fileNameToStore = $data['stffirstName'].$num.'ssce1_'.date('h_i_s_d_m_Y').'.'.$clientExt;
                    $path = $data['stfsscecert']->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);
                    $certName = $fileNameToStore;
                }

                DB::table('staff_qualification')->insert([
                    "staff_id" => $staff->id,
                    "school_id" => $data["stfschool_id"],
                    "institution" => $data['stfssceinstitution'],
                    "type" => "SSCE",
                    "year" => $data['stfssceyear'],
                    "body" => $data['stfsscebody'],
                    "certificate" => $certName, //****handle */
                ]);
            }

            //HANDLE SECOND SSCE
            if(isset($data['stfsscecert2']) && $data['stfsscecert2'] != ''){
                $certName = 'no_image.jpg';
                if($data['stfsscecert2'] != ''){
                    $clientExt = $data['stfsscecert2']->getClientOriginalExtension();
                    $fileNameToStore = $data['stffirstName'].$num.'ssce2_'.date('h_i_s_d_m_Y').'.'.$clientExt;
                    $path = $data['stfsscecert2']->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);
                    $certName = $fileNameToStore;
                }
                DB::table('staff_qualification')->insert([
                    "staff_id" => $staff->id,
                    "school_id" => $data["stfschool_id"],
                    "institution" => $data['stfssceinstitution'],
                    "type" => "SSCE",
                    "year" => $data['stfssceyear'],
                    "body" => $data['stfsscebody2'],
                    "certificate" => $certName, //****handle */
                ]);
            }

            // HANDLE STAFF GRADE LEVEL
            if($data['stfsalary_grade_id'] != '' && $data['stfsalary_grade_id'] != '0'){
                DB::table('staff_grade_level')->insert([
                    "school_id" => $data["stfschool_id"],
                    "staff_id" => $staff->id,
                    "grade_level_id" => $data["stfsalary_grade_id"],
                    "status" => '1',
                ]);
            }

            DB::commit();

            
            return response()->json([
                'id' => $staff->id,
                'regNo' => $staff->regNo,
                'firstName' => $staff->firstName,
                'lastName' => $staff->lastName,
                'otherNames' => $staff->otherNames,
                'phoneNo' => $staff->phoneNo,
                'img' => $staff->imgFile,
                'model' => 'staff',
                'prefix' => $school->prefix
            ]);
            
        } catch(\Exception $e){
            // dd($e);
            DB::rollback();
            return response()->json(["status" => "Error"]);
        }
    
        // return response()->json(['success'=>'Added new records.']);
        // return redirect('/'.$staff->schoolId.'/staff');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(School $school, $staff)
    {
        $staff = Staff::where([['id', $staff], ['school_id', $school->id]])->first();
        $certs = DB::table('staff_qualification')
            ->where([['staff_id', '=', $staff->id],['status', '=', '1'],['school_id', '=', $school->id]])->get()->toArray();
        $states = DB::table('states')->get();
        $lgas = DB::table('lgas')->get();
        $stf_position = DB::table('staff_position')->get();
        $stf_category = DB::table('staff_category')->get();
        $grade_level = DB::table('grade_level')->get();
        $subjects = DB::table('subjects')->orderBy('title','ASC')->get();
        $religions = DB::table('religions')->get();
        $allSchools = DB::table('schools')->get();
        $denominations = DB::table('religious_denominations')->get();

        $fslc = array_filter($certs, function($cert){
            return $cert->type == 'FSLC';
        });
                
        $ssce = array_filter($certs, function($cert){
            return $cert->type == 'SSCE';
        });
        $nysc = array_filter($certs, function($cert){
            return $cert->type == 'NYSC';
        });
        // dd($nysc);
        $tertiary = array_filter($certs, function($cert){
            return $cert->type != 'SSCE' && $cert->type != 'FSLC' && $cert->type != 'NYSC';
        });
        $sscegrade = DB::table('staff_grades')->select('staff_grades.*', 'subjects.title')
            ->leftJoin('subjects', 'subjects.id', '=', 'staff_grades.subject')
            ->where([
                ['staff_id','=',$staff->id],
                ['status','=','1'],
                ['school_id', '=', $school->id]
            ])->get();
        // dd($tertiary);

        return view('staff.edit', 
            compact('school', 'staff', 'lgas', 'states', 
                'fslc', 'ssce', 'sscegrade', 'tertiary',
                'nysc', 'stf_position', 'stf_category', 
                'grade_level', 'subjects', 'religions', 
                'denominations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $staff) {
        // dd($staff->nextOfKin);
        /**Recieves request data,converts it to array */
        $data = $request->all();
        $school = School::find($request->user()->school_id);
        $staff = Staff::where([['id', $staff], ['school_id', $school->id]])->first();
        // dd($data);
        // $dataStaff = (array) $data['staff'];        
        // dd($datastaff['imgFile']);

        /**update a parent with the return data array*/
        // $dataNOK = (array) $data['nok'];      
        // if($staff->nextOfKin)  {
        //     $staff->nextOfKin()->update($dataNOK);
        // }  
        // else{
        //     $nextOfKin = NextOfKin::create($dataNOK);
        //     $dataStaff['next_of_kin_id'] = $nextOfKin->id;
        // }

        // foreach($dataStaff as $key=>$dat){
        //     if($dat == ""){unset($dataStaff[$key]);}
        // }

        // if(isset($dataStaff['imgFile'])){            
        //     $fileNameToStore = $dataStaff['firstName'].'_'.$dataStaff['lastName'].'_'.date('h_i_s_d_m_Y').'.png';
        //     $imgdata = $dataStaff['imgFile'];
        //     list($type, $imgdata) = explode(';', $imgdata);
        //     list(, $imgdata) = explode(',', $imgdata);
        //     $imgdata = base64_decode($imgdata);
        //     file_put_contents(storage_path().'\app\public\images\passports\\'.$fileNameToStore, $imgdata);
    
        //     $dataStaff['imgFile'] = $fileNameToStore;
        // }
        DB::beginTransaction();
        try{
            $nok = $staff->nextOfKin;
            if(isset($data['nokid'])){
                // $nok->update([
                //     "school_id" => $data["stfschool_id"],
                //     "title" => $data["noktitle"],
                //     "firstName" => $data["nokfirstName"],
                //     "otherName" => $data["nokotherName"],
                //     "lastName" => $data["noklastName"],
                //     "address" => $data["nokaddress"],
                //     "relationship" => $data["nokrelationship"],
                //     "phoneNo" => $data["nokphoneNo"]
                //     ]);
                $nok = DB::table('next_of_kin')->where('id', $data['nokid'])
                    ->update([
                        "school_id" => $data["stfschool_id"],
                        "title" => $data["noktitle"],
                        "firstName" => $data["nokfirstName"],
                        "otherName" => $data["nokotherName"],
                        "lastName" => $data["noklastName"],
                        "address" => $data["nokaddress"],
                        "relationship" => $data["nokrelationship"],
                        "phoneNo" => $data["nokphoneNo"]
                        ]);            
                $nok = $staff->nextOfKin;
            }else{
                $nok = NextOfKin::create([
                    "school_id" => $data["stfschool_id"],
                    "title" => $data["noktitle"],
                    "firstName" => $data["nokfirstName"],
                    "otherName" => $data["nokotherName"],
                    "lastName" => $data["noklastName"],
                    "address" => $data["nokaddress"],
                    "relationship" => $data["nokrelationship"],
                    "phoneNo" => $data["nokphoneNo"]
                    ]);
                // DB::table('next_of_kin')->updateOrInsert();
            }
            
            // $dataStaff = (array) $data['staff'];
            $dataStaff = [
                "status" => $data["stfstatus"],
                "school_id" => $data["stfschool_id"],
                "firstName" => $data["stffirstName"],
                "otherNames" => $data["stfotherNames"],
                "lastName" => $data["stflastName"],
                "title" => $data["stftitle"],
                "marital_status_id" => $data["stfmarital_status_id"],
                "dob" => $data["stfdob"],
                "gender" => $data["stfgender"],
                "state_of_origin_id" => $data["stfstate_of_origin_id"],
                "lga_of_origin_id" => $data["stflgaOfOriginId"],
                "homeTown" => $data["stfhomeTown"],
                "homeAddress" => $data["stfhomeAddress"],
                "appointmentDate" => $data["stfappointmentDate"],
                "category_id" => $data["stfcategory_id"],
                "position" => $data["stfposition"],
                // "salary_grade_id" => $data["stfsalary_grade_id"],
                "religion_id" => $data["stfreligion_id"],
                "denomination_id" => $data["stfdenomination_id"],
                "phoneNo" => $data["stfphoneNo"],
                "email" => $data["stfemail"],
                // "rank_id" => $data["stfrank_id"],
                'next_of_kin_id' => $nok->id,
            ];


            if(isset($data['stfimgFile']) && $data['stfimgFile'] != ""){
                $fileNameToStore = $dataStaff['firstName'].'_'.$dataStaff['lastName'].'_'.date('h_i_s_d_m_Y').'.png';
                $imgdata = $data['stfimgFile'];
                list($type, $imgdata) = explode(';', $imgdata);
                list(, $imgdata) = explode(',', $imgdata);
                $imgdata = base64_decode($imgdata);
                file_put_contents(storage_path().'/app/public/images/'.$school->prefix.'/passports/'.$fileNameToStore, $imgdata);

                $dataStaff['imgFile'] = $fileNameToStore;
            }

            // Store staff signature
            if(isset($data['stfsignature']) && $data['stfsignature'] != ''){
                // $certName = "no_image.jpg";

                $clientExt = $data['stfsignature']->getClientOriginalExtension();
                $fileNameToStore = $data['stffirstName'].'sign_'.date('h_i_s_d_m_Y').'.'.$clientExt;
                $path = $data['stfsignature']->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);

                $dataStaff['signature'] = $fileNameToStore;
            }

            Staff::where([['id', $staff->id],["school_id", $data["stfschool_id"]]])->update($dataStaff);
            $staff = Staff::where([['id', $staff->id],["school_id", $data["stfschool_id"]]])->first();

            // $school = School::find($dataStaff['school_id']);

            $regNo = $school->prefix."/STF/".$staff->id;
            $staff->update(['regNo'=>$regNo]);

            //handle OLD qualifications
            if(isset($data['oldfslcinstitution']) && $data['oldfslcinstitution'] != ''){
                
                if(isset($data['oldfslccert'])){
                    $clientExt = $data['oldfslccert']->getClientOriginalExtension();
                    $fileNameToStore = $data['stffirstName'].'fslc_'.date('h_i_s_d_m_Y').'.'.$clientExt;
                    $path = $data['oldfslccert']->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);

                    $fslc = DB::table('staff_qualification')
                    ->where([['staff_id', '=', $staff->id], ["school_id", "=", $data["stfschool_id"]], ['type', '=', 'FSLC']])
                    ->update([
                        "certificate" => $fileNameToStore, //****handle */
                    ]);
                }

                $fslc = DB::table('staff_qualification')
                    ->where([['staff_id', '=', $staff->id], ["school_id", "=", $data["stfschool_id"]], ['type', '=', 'FSLC']])
                    ->update([
                        "institution" => $data['oldfslcinstitution'],
                        "grade" => $data['oldfslcgrade'],
                        "year" => $data['oldfslcyear'],
                    ]);
                
            }

            if(isset($data['oldnysccert']) && $data['oldnysccert'] != ''){

                $clientExt = $data['oldnysccert']->getClientOriginalExtension();
                $fileNameToStore = $data['stffirstName'].'nysc_'.date('h_i_s_d_m_Y').'.'.$clientExt;
                $path = $data['oldnysccert']->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);

                $nysc = DB::table('staff_qualification')
                ->where([["staff_id", '=', $staff->id], ["school_id", "=", $data["stfschool_id"]], ['type', '=', 'NYSC']])
                ->update([
                    "year" => $data['oldnyscyear'],
                    "certificate" => $fileNameToStore, //****handle */
                ]);
            }
            
            //handle qualifications
            if(isset($data['stffslcinstitution']) && $data['stffslcinstitution'] != ''){
                $certName = "no_image.jpg";
                if(isset($data['stffslccert']) && $data['stffslccert'] != ''){
                    $clientExt = $data['stffslccert']->getClientOriginalExtension();
                    $fileNameToStore = $data['stffirstName'].'fslc_'.date('h_i_s_d_m_Y').'.'.$clientExt;
                    $path = $data['stffslccert']->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);
                    $certName = $fileNameToStore;
                }

                $fslc = DB::table('staff_qualification')->insert([
                    "staff_id" => $staff->id,
                    "school_id" => $data["stfschool_id"],
                    "institution" => $data['stffslcinstitution'],
                    "grade" => $data['stffslcgrade'],
                    "type" => "FSLC",
                    "year" => $data['stffslcyear'],
                    "certificate" => $certName, //****handle */
                ]);
            }
            
            if(isset($data['stfnysccert']) && $data['stfnysccert'] != ''){

                $clientExt = $data['stfnysccert']->getClientOriginalExtension();
                $fileNameToStore = $data['stffirstName'].'nysc_'.date('h_i_s_d_m_Y').'.'.$clientExt;
                $path = $data['stfnysccert']->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);

                $nysc = DB::table('staff_qualification')->insert([
                    "staff_id" => $staff->id,
                    "school_id" => $data["stfschool_id"],
                    "institution" => "National Youth Service Corps",
                    "type" => "NYSC",
                    "year" => $data['stfnyscyear'],
                    "certificate" => $fileNameToStore, //****handle */
                ]);
            }

            //INSERT EACH SSCE SUBJECT
            foreach ($data as $key => $val) {
                if(substr($key, 0, 10) == "stfsscesub"){
                    $num = substr($key, 10);
                    $gradekey = 'stfsscegrade'.$num;
                    DB::table('staff_grades')->insert([
                        "staff_id" => $staff->id,
                        "school_id" => $data["stfschool_id"],
                        "subject" => $val,
                        "grade" => $data[$gradekey]
                    ]);
                }
                
                //INSERT EACH HIGHER INSTITUTION CERTIFICATE
                if(substr($key, 0, 18) == "stfhigherinstitute" && $val != ''){
                    $num = substr($key, strlen($key)-1); //dd($num);
                    $qualkey = 'stfhigherinstqual'.$num;
                    $coursekey = 'stfhighercourse'.$num;
                    $gradekey = 'stfhigherinstgrade'.$num;
                    $yearkey = 'stfhigherinstyear'.$num;
                    $certkey = 'stfhigherinstcert'.$num; $certName = "no_image.jpg";

                    if($data[$certkey] != ''){
                        $clientExt = $data[$certkey]->getClientOriginalExtension();
                        $fileNameToStore = $data['stffirstName'].$num.'Qual_'.date('h_i_s_d_m_Y').'.'.$clientExt;
                        $path = $data[$certkey]->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);
                        $certName = $fileNameToStore;
                    }

                    DB::table('staff_qualification')->insert([
                        "staff_id" => $staff->id,
                        "school_id" => $data["stfschool_id"],
                        "institution" => $val,
                        "course" => $data[$coursekey],
                        "type" => $data[$qualkey],
                        "grade" => $data[$gradekey],
                        "year" => $data[$yearkey],
                        "certificate" => $certName,
                    ]);
                }

                //HANDLE OLD HIGHER INSTITUTION
                if(substr($key, 0, 18) == "oldhigherinstitute" && $val != ''){
                    $num = substr($key, strlen($key)-1); //dd($num);
                    $qualkey = 'oldhigherinstqual'.$num;
                    $idkey = 'oldhigherid'.$num;
                    $coursekey = 'oldhighercourse'.$num;
                    $gradekey = 'oldhigherinstgrade'.$num;
                    $yearkey = 'oldhigherinstyear'.$num;
                    $certkey = 'oldhigherinstcert'.$num; 
                    // $certName = "no_image.jpg";

                    
                    if(isset($data[$certkey])){
                        $clientExt = $data[$certkey]->getClientOriginalExtension();
                        $fileNameToStore = $data['stffirstName'].$num.'higherinst1_'.date('h_i_s_d_m_Y').'.'.$clientExt;
                        $path = $data[$certkey]->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);

                        DB::table('staff_qualification')
                            ->where([['id', '=', $data[$idkey]], ['staff_id', '=', $staff->id]])
                            ->update([
                                "certificate" => $fileNameToStore, //****handle */
                            ]);
                    }

                    DB::table('staff_qualification')
                            ->where([['id', '=', $data[$idkey]], ["school_id", '=', $data["stfschool_id"]], ['staff_id', '=', $staff->id]])
                            ->update([
                                "institution" => $val,
                                "course" => $data[$coursekey],
                                "grade" => $data[$gradekey],
                                "type" => $data[$qualkey],
                                "year" => $data[$yearkey],
                            ]);
                }

                if(substr($key, 0, 18) == "oldssceinstitution" && $val != ''){
                    $num = substr($key, strlen($key)-1); //dd($num);
                    $idkey = 'oldssceid'.$num;
                    $bodykey = 'oldsscebody'.$num;
                    $yearkey = 'oldssceyear'.$num;
                    $certkey = 'oldsscecert'.$num; 

                    if(isset($data[$certkey])){
                        $clientExt = $data[$certkey]->getClientOriginalExtension();
                        $fileNameToStore = $data['stffirstName'].$num.'ssce_'.date('h_i_s_d_m_Y').'.'.$clientExt;
                        $path = $data[$certkey]->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);

                        DB::table('staff_qualification')
                        ->where([['id', '=', $data[$idkey]], ["school_id", '=', $data["stfschool_id"]], ['staff_id', '=', $staff->id]])
                        ->update([
                            "certificate" => $fileNameToStore, //****handle */
                        ]);
                    }
    
                    DB::table('staff_qualification')
                    ->where([['id', '=', $data[$idkey]], ["school_id", '=', $data["stfschool_id"]], ['staff_id', '=', $staff->id]])
                    ->update([
                        "institution" => $val,
                        "year" => $data[$yearkey],
                        "body" => $data[$bodykey],
                    ]);
                }
            }

            //HANDLE SSCE
            if(isset($data['stfsscecert']) && $data['stfsscecert'] != '' ){
                $certName = 'no_image.jpg';
                if($data['stfsscecert'] != ''){
                    $clientExt = $data['stfsscecert']->getClientOriginalExtension();
                    $fileNameToStore = $data['stffirstName'].$num.'ssce1_'.date('h_i_s_d_m_Y').'.'.$clientExt;
                    $path = $data['stfsscecert']->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);
                    $certName = $fileNameToStore;
                }

                DB::table('staff_qualification')->insert([
                    "staff_id" => $staff->id,
                    "school_id" => $data["stfschool_id"],
                    "institution" => $data['stfssceinstitution'],
                    "type" => "SSCE",
                    "year" => $data['stfssceyear'],
                    "body" => $data['stfsscebody'],
                    "certificate" => $certName, //****handle */
                ]);
            }


            //HANDLE SECOND SSCE
            if(isset($data['stfsscecert2']) && $data['stfsscecert2'] != ''){
                $certName = 'no_image.jpg';
                if($data['stfsscecert2'] != ''){
                    $clientExt = $data['stfsscecert2']->getClientOriginalExtension();
                    $fileNameToStore = $data['stffirstName'].$num.'ssce2_'.date('h_i_s_d_m_Y').'.'.$clientExt;
                    $path = $data['stfsscecert2']->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);
                    $certName = $fileNameToStore;
                }
                DB::table('staff_qualification')->insert([
                    "staff_id" => $staff->id,
                    "school_id" => $data["stfschool_id"],
                    "institution" => $data['stfssceinstitution'],
                    "type" => "SSCE",
                    "year" => $data['stfssceyear'],
                    "body" => $data['stfsscebody2'],
                    "certificate" => $certName, //****handle */
                ]);
            }

            // HANDLE STAFF GRADE LEVEL
            if(isset($data['stfsalary_grade_id']) && $data['stfsalary_grade_id'] != '' && $data['stfsalary_grade_id'] != '0'){
                DB::table('staff_grade_level')->updateOrInsert(
                    ["school_id" => $data["stfschool_id"], "staff_id" => $staff->id],
                    ["grade_level_id" => $data["stfsalary_grade_id"], "status" => '1']
                );
            }
            $staff->update($dataStaff);
            DB::commit();

            return response()->json([
                'id' => $staff->id,
                'regNo' => $staff->regNo,
                'firstName' => $staff->firstName,
                'lastName' => $staff->lastName,
                'otherNames' => $staff->otherNames,
                'phoneNo' => $staff->phoneNo,
                'img' => $staff->imgFile,
            ]);

        } catch(\Exception $e){
            // dd($e);
            DB::rollback();
            return response()->json(["status" => "Error"]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //This deletes the data from storage
        // Staff::find($id)->delete($id);
        
        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
        
    }

    public function staffProfile(School $school, $staff){
        // $std = $request->all();
        $staff = Staff::where([['id', $staff], ['school_id', $school->id]])->first();

        if(Gate::denies('view-staff-profile', $staff)){
            return redirect()->route('login');
        }
        // $school = $staff->school;
        $stfSubjects = DB::table('student_subjects')->
                            select('student_subjects.class_id', 'student_subjects.subject_id', 'subjects.title', 'school_classes.level', 'school_classes.suffix')->
                            where([
                                ['student_subjects.staff_id', $staff->id],
                                ['student_subjects.school_id', $school->id],
                                ['school_classes.school_id', $school->id],
                                ['student_subjects.academic_session_id', $school->academic_session_id],
                            ])->
                            leftJoin('subjects', 'student_subjects.subject_id', '=', 'subjects.id')->
                            leftJoin('school_classes', 'student_subjects.class_id', '=', 'school_classes.id')->
                            get();
        // $stfDetails = ['name'=> ($staff->lastName.', '.$staff->firstName.' '. $staff->otherNames), 'id'=> $staff->id];
        // dd($stfSubjects, $stfDetails);
        // dd($school->Term($school->current_term_id), $school->AcademicSession->sessionName);
        return view('login.staffProfile', compact('stfSubjects', 'staff', 'school'));
    }
    
    public function details(School $school, $staff){
        $staff = Staff::where([['id', $staff], ['school_id', $school->id]])->first();

        $school = $staff->school;
        $certs = DB::table('staff_qualification')->where([['staff_id', '=', $staff->id],['status', '=', '1']])->orderBy('year', 'ASC')->get();
        $ssce = DB::table('staff_grades')->select('staff_grades.subject', 'staff_grades.grade', 'subjects.title')
            ->leftJoin('subjects', 'subjects.id', '=', 'staff_grades.subject')
            ->where([
                ['staff_id', $staff->id],
                ['staff_grades.school_id', $school->id]
            ])->get();
        $nextOfKin = $staff->nextOfKin;
        $grade_level = DB::table('staff_grade_level')->select('staff_grade_level.*', "grade_level.level", "grade_level.rank")
        ->leftJoin("grade_level", "grade_level.id", "=", "staff_grade_level.grade_level_id")
        ->where([
            "school_id" => $school->id,
            "staff_id" => $staff->id,
            "status" => '1'
        ])->first();
        // dd($grade_level);
        return view('staff.details', compact('staff', 'school', 'certs', 'nextOfKin', 'ssce', 'grade_level'));
    }

    public function captureShow(School $school){
        $staff = Staff::where([
            ['school_id', $school->id],
            ['status', '1'],
            ])->get();
        return view('staff.capture', compact('school', 'staff'));
    }

    public function signatureUpdate(Request $request, $staff){
        $data = $request->all();
        // dd($data);
        $school = School::find($request->user()->school_id);
        $staff = Staff::where([['id', $staff], ['school_id', $school->id]])->first();

        try {
            //code...
            $fileNameToStore = $staff->firstName.'_'.$staff->lastName.'_sign'.date('h_i_s_d_m_Y').'.jpeg';
            $imgdata = $data['dataURL'];
            list($type, $imgdata) = explode(';', $imgdata);
            list(, $imgdata) = explode(',', $imgdata);
            $imgdata = base64_decode($imgdata);
            file_put_contents(storage_path().'/app/public/images/'.$school->prefix.'/photo/school/'.$fileNameToStore, $imgdata);

            $staff->signature = $fileNameToStore;
            $staff->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json(['success' => false, 'error' => $e]);
        }
    }
    

    public function photoUpdate(Request $request, $staff){
        $data = $request->all();
        // dd($data);
        $school = School::find($request->user()->school_id);
        $staff = Staff::where([['id', $staff], ['school_id', $school->id]])->first();

        try {
            //code...
            $fileNameToStore = $staff->firstName.'_'.$staff->lastName.'_photo'.date('h_i_s_d_m_Y').'.png';
            $imgdata = $data['dataURL'];
            list($type, $imgdata) = explode(';', $imgdata);
            list(, $imgdata) = explode(',', $imgdata);
            $imgdata = base64_decode($imgdata);
            file_put_contents(storage_path().'/app/public/images/'.$school->prefix.'/passports/'.$fileNameToStore, $imgdata);

            $staff->imgFile = $fileNameToStore;
            $staff->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json(['success' => false, 'error' => $e]);
        }
    }

    public function removeView(School $school){
        $allStaff = Staff::where([
                ['school_id', $school->id],
                ['status', '1'],
            ])->orderBy('lastName', 'ASC')->get();

        return view('staff.remove', compact('school', 'allStaff'));
    }

    public function remove(Request $request){
        $data = $request->all();
        // dd($data);
        $removed = [];

        foreach($data['staff'] as $staff){
            $rem = DB::table('staff')->where([
                "school_id" => $data["schoolId"],
                "id" => $staff
            ])->update(["status" => '0']);

            if($rem){$removed[] = $staff;}
        }

        return response()->json(["removed" => $removed]);
    }

}

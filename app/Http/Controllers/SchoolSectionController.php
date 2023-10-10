<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use DB;

class SchoolSectionController extends Controller
{

    public function index(School $school){
        $schoolSections = DB::table('school_sections')->where([
                            ['school_id', $school->id],
                            ['status', 'Active'],
                            ])->get();
        return view('sections.index', compact('school', 'schoolSections'));
    }
    /**
     * Show the form for creating a new student.
     */
    public function create(School $school) {
        $sectionType = DB::table('schoolsectiontypes')->get();
        return view('sections.create', compact('school', 'sectionType'));
    }

    // public function store(Request $request)
    // {
    //     $dataSection = $request->all();
        
    //     // $schoolName = School::find($dataSection['schoolId'])->schoolName;
    //     // dd($dataSection, $schoolName);
    //     // $schoolName = str_replace("'", '', $schoolName);
    //     // $schoolName = str_replace(" ", '_', $schoolName);
        
    //     $sectionHeadName = str_replace(" ", '_', $dataSection['sectionHead']);
    //     $clientExt = $request->file('sectionHeadSign')->getClientOriginalExtension();
    //     $fileNameToStore = $sectionHeadName.'_'.date('h_i_s_d_m_Y').'.'.$clientExt;
    //     $path = $request->file('sectionHeadSign')->storeAs('public/images/photo/school', $fileNameToStore);
    //     // $imgdata = $dataSection['sectionHeadSign'];
    //     // file_put_contents(storage_path().'\app\public\images\schools\photo\\'.$fileNameToStore, $imgdata);

    //     $dataSection['sectionHeadSign'] = $fileNameToStore;
    //     $schoolSection = DB::table('school_sections')->insert($dataSection);

      
    //     return response()->json(['resobj' => $schoolSection]);
    // }

    public function store(Request $request){
        $data = $request->all();
        // dd($dataSection);
        $school = School::find($request->user()->school_id);

        $dataSection = [
            "sectionName" => $data['sectionName'],
            "school_id" => $data['schoolId'],
            "shortName" => $data['shortName'],
            "sectionHead" => $data['sectionHead'],
            "status" => "Active"
        ];
        $fileNameToStore = 'no_image.jpg';

        if(isset($data['sectionHeadSign'])){
            $sectionHeadName = str_replace(" ", '_', $dataSection['sectionHead']);
            $clientExt = $request->file('sectionHeadSign')->getClientOriginalExtension();
            $fileNameToStore = $sectionHeadName.'_'.date('h_i_s_d_m_Y').'.'.$clientExt;
            $path = $request->file('sectionHeadSign')->storeAs('public/images/'.$school->prefix.'/photo/school', $fileNameToStore);
    
            $dataSection['sectionHeadSign'] = $fileNameToStore;
        }

        $schoolSection = DB::table('school_sections')->insert($dataSection);

      
        return response()->json(['success' => true]);
    }

    /**
     * Show the form for creating a new student.
     */
    public function edit(Request $request, $school, $sectionId) {
        $school = School::find($request->user()->school_id);
        $schoolSection = DB::table('school_sections')->where([['id', $sectionId], ['school_id', $school->id]])->first();
        return view('sections.edit', compact('school', 'schoolSection'));
    }

    public function update(Request $request, $sectionId)
    {
        $dataSection = $request->all();
        $schoolSection = DB::table('school_sections')->find($sectionId);
        dd($schoolSection);
       
        if($request->hasFile('sectionHeadSign')){
            $sectionHeadName = str_replace(" ", '_', $dataSection['sectionHead']);
            $clientExt = $request->file('sectionHeadSign')->getClientOriginalExtension();
            $fileNameToStore = $sectionHeadName.'_'.date('h_i_s_d_m_Y').'.'.$clientExt;
            $path = $request->file('sectionHeadSign')->storeAs('public/images/photo/school', $fileNameToStore);
        } else {
            $fileNameToStore = "no_image.jpg";
        }
        // $imgdata = $dataSection['sectionHeadSign'];
        // file_put_contents(storage_path().'\app\public\images\schools\photo\\'.$fileNameToStore, $imgdata);

        $dataSection['sectionHeadSign'] = $fileNameToStore;
        dd($dataSection);
        $schoolSectionUpdate = DB::table('school_sections')->where('id', $schoolSection->id)->update($dataSection);

      
        return response()->json(['resobj' => $schoolSectionUpdate]);
    }
}

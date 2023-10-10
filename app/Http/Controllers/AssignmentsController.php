<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Storage;
use App\School;

class AssignmentsController extends Controller
{
    //
    public function create(School $school){
        $allClass = DB::table('school_classes')->where([
            ['school_id', $school->id],
            ['status', '1'],
            ])->orderBy('level', 'ASC')->get();
        $acadSession = DB::table('academic_sessions')->get();

        $assignedSubjects = DB::table('student_subjects')->select('subject_id')->where([
                            ['academic_session_id', $school->academic_session_id],
                            ['school_id', $school->id],
                        ])->groupBy('subject_id')->get();
        $subjectsArr = [];
        if(count($assignedSubjects) > 0){
            foreach($assignedSubjects as $subt){
                $subjectsArr[] = ((array)$subt)['subject_id'];
            }
        }
        // dd(count($assignedSubjects));

        $allSubj = DB::table('subjects')->whereIn('id', $subjectsArr)->orderBy('title', 'ASC')->get();

        return view('assignments.create', compact('school', 'allClass', 'allSubj'));
    }

    public function store(Request $request)
    {
        $dataAssign = (array) json_decode($request->input('details'));
        $assignFile = $request->file('assignmentFile')->getClientOriginalName();

        // dd($assignFile);
        // $sectionHeadName = str_replace(" ", '_', $dataSection['sectionHead']);
        $clientExt = $request->file('assignmentFile')->getClientOriginalExtension();
        // dd($clientExt, $dataAssign);
        $fileNameToStore = explode('.', $assignFile)[0].'_'.date('h_i_s_d_m_Y').'.'.$clientExt;
        $path = $request->file('assignmentFile')->storeAs('public/images/photo/school', $fileNameToStore);
        // $imgdata = $dataSection['sectionHeadSign'];
        // file_put_contents(storage_path().'\app\public\images\schools\photo\\'.$fileNameToStore, $imgdata);

        foreach ($dataAssign['class_id'] as $key => $val) {
            $storeAssign = DB::table('class_assignments')->insert([
                'class_id' => $val,
                'subject_id' => $dataAssign['subject_id'],
                'school_id' => $dataAssign['school_id'],
                'date_created' => $dataAssign['date_created'],
                'date_of_submission' => $dataAssign['date_of_submission'],
                'file_name' => $fileNameToStore,
            ]);
            if(!$storeAssign){
                return response()->json(['failed' => $val]);
            }
        }
      
        return response()->json(['success' => 'Success']);
    }

    public function view(School $school){
        $assignments = DB::table('class_assignments')->
                select('class_assignments.*', 'school_classes.level', 'school_classes.suffix',
                'subjects.title')->
                leftJoin('school_classes', 'class_assignments.class_id', '=', 'school_classes.id')->
                leftJoin('subjects', 'class_assignments.subject_id', '=', 'subjects.id')->
                where([
                    ['class_assignments.school_id',  $school->id],
                    ['school_classes.school_id', $school->id]
                ])->get();

        return view('assignments.view', compact('school', 'assignments'));
    }

    public function download($fileName){
        
        return Storage::download('public/images/photo/school/'.$fileName);
    }

    // public function download(Request $request){
    //     $fileName = $request->all();

    //     // dd($fileName);
    //     return Storage::download('public/images/photo/school/'.$fileName['file_name']);
    // }
}

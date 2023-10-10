<?php
namespace App\Http\Controllers;

ini_set('max_execution_time', 0);

use Illuminate\Http\Request;
use App\School;
use DB;

class SyncController extends Controller
{
    public function index(School $school){
        $tables = json_encode([
            "assessment_formats",
            "academic_sessions",
            "class_students_list",
            "fingerprint",
            "form_teachers",
            "grading_formats",
            "grade_level",//TEMP
            // "lgas", //temp****
            "next_of_kin",
            // "religions",//
            // "religious_denominations",//
            "schools",
            "schoolsectiontypes",
            "school_classes",
            "school_sections",
            "school_traits",
            "school_trait_rating",
            "scores",
            "sectionsubjects",
            "staff",
            "staff_grades",
            // "staff_category", //temp
            // "staff_position", //temp
            "staff_grade_level",
            "staff_qualification",
            // "states", //temp***
            "students",
            "student_health",
            // "subjects",//temp
            "student_parents",
            "student_subjects",
            "student_trait_assessments",
            "termly_comments",
            "termly_newsletter",
            "term_config",
            "users",
        ]);
        return view('sync.index', compact('school', 'tables'));
    }

    public function offlineSys(Request $request){
        $req = $request->all();

        // $unsync = DB::table($req['table'])->where('sync_status', '0')->get()->toArray();
        $unsync = DB::table($req['table'])->where('sync_status', '0')->limit(500)->get()->toArray();

        return response()->json(['data' => $unsync, 'table' => $req['table']]);
    }

    // public function offlineUpdate(Request $request){
    //     $req = $request->all();
        
    //     foreach ($req["returnVal"] as $key => $val) {
    //         if(isset($val['global_id'])){
    //             $sync = DB::table($req['table'])->where('id', $val['id'])
    //                 ->update(['sync_status'=>'2', 'global_id'=>$val['global_id']]);
    //         } else {
    //             $sync = DB::table($req['table'])->where('id', $val['id'])
    //                 ->update(['sync_status'=>'2']);
    //         }
    //     }

    //     return response()->json(['success' => true, 'table' => $req['table']]);
    // }

    public function offlineUpdate(Request $request){
        $req = $request->all();
        
        foreach ($req["returnVal"] as $key => $val) {
            if(isset($val['global_id'])){
                $sync = DB::table($req['table'])->where('id', $val['id'])
                    ->update(['sync_status'=>'2', 'global_id'=>$val['global_id']]);
            } else {
                $sync = DB::table($req['table'])->where('id', $val['id'])
                    ->update(['sync_status'=>'2']);
            }
        }

        $unsync = DB::table($req['table'])->where('sync_status', '0')->limit(500)->get()->toArray();

        return response()->json(['success' => true, 'data' => $unsync, 'table' => $req['table']]);
    }

    public function onlineSys(Request $request){
        $req = $request->all();

        $returnArr = [];

        DB::beginTransaction();
        try{

            foreach ($req['data'] as $key => $val) {
                unset($val['global_id']);
                $val['sync_status'] = '2';
                $whereVal = [];
                $noSchoolId = ['schools', 'academic_sessions', 'subjects', 'states', 'lgas', 'religions', 'religious_denominations', "staff_category", "staff_position",];

                if(in_array($req['table'], $noSchoolId)){
                    $whereVal = [['id', '=', $val['id']]];
                } else {
                    $whereVal = [['id', '=', $val['id']], ['school_id', '=', $val['school_id']]];
                }

                
                $exists = DB::table($req['table'])->where($whereVal)->exists();

                if($exists){
                    // dd($exists->global_id);
                    $exists = DB::table($req['table'])->where($whereVal)->first();
                    DB::table($req['table'])->where($whereVal)->update($val);
                    $returnArr[] = ['id' => $val['id'], 'global_id' => $exists->global_id];
                } else {
                    $global_id = DB::table($req['table'])->insertGetId($val);
                    $returnArr[] = ['id' => $val['id'], 'global_id' => $global_id];
                }

                // DB::table($req['table'])->updateOrInsert(
                //     ['id'=>$val['id'], 'school_id'=>$val['school_id']],
                //     $val
                // );
            }

            DB::commit();
            return response()->json(["status" => "Success", "returnVal"=>$returnArr, 'table' => $req['table']]);
        } catch(\Exception $e){
            // dd($e);
            DB::rollback();
            return response()->json(["status" => "Error",  'table' => $req['table']]);
        }
    }

    public function onlinePixCheck(Request $request, School $school){
        $fileNm = storage_path().'/app/public/images/'.$school->prefix.'/photo/school';
        $filePass = storage_path().'/app/public/images/'.$school->prefix.'/passports';
        $fileNmArr = ['certificates'=>[], 'passports'=>[]];

        if(is_dir($fileNm)){
            if($fileArr = opendir($fileNm)){
                while (($file = readdir($fileArr)) !== false) {
                    if(!is_dir($file)){
                        $fileNmArr['certificates'][] = $file;
                    }
                }
            }
        }

        if(is_dir($filePass)){
            if($fileArr = opendir($filePass)){
                while (($file = readdir($fileArr)) !== false) {
                    if(!is_dir($file)){
                        $fileNmArr['passports'][] = $file;
                    }
                }
            }
        }

        // dd($fileNmArr);
        return response()->json(['offline' => $fileNmArr, '1'=>$fileNm, 'prefix'=>$school->prefix]);
    }

    public function onlinePixUpload(Request $request, School $school){
        $data = $request->all();
        $fileCert = storage_path().'/app/public/images/'.$school->prefix.'/photo/school';
        $filePass = storage_path().'/app/public/images/'.$school->prefix.'/passports';

        if(!is_dir($fileCert)){mkdir($fileCert, null, true);}
        if(!is_dir($filePass)){mkdir($filePass, null, true);}
        
        try {
            foreach ($data as $key => $val) {
                $fileNameToStore = $val['imgname'];
                $imgdata = $val['img'];
                list($type, $imgdata) = explode(';', $imgdata);
                list(, $imgdata) = explode(',', $imgdata);
                $imgdata = base64_decode($imgdata);
                if($val['folder'] == 'certificates'){
                    try {
                        //code...
                        file_put_contents(storage_path().'/app/public/images/'.$school->prefix.'/photo/school/'.$fileNameToStore, $imgdata);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                } else {
                    try {
                        //code...
                        file_put_contents(storage_path().'/app/public/images/'.$school->prefix.'/passports/'.$fileNameToStore, $imgdata);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
                
            }
           
            // $schoolFiles = checkFile($school);
            return response()->json(['success' => true]);
            // return response()->json($schoolFiles);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json(['success' => false, 'error' => $e]);
        }
    }

    public function offlinePix(Request $request, School $school){
        $data = $request->all();
        $fileNm = storage_path().'/app/public/images/'.$school->prefix.'/photo/school';
        $filePass = storage_path().'/app/public/images/'.$school->prefix.'/passports';
        $fileNmArr = [];

        if(is_dir($fileNm)){
            if($fileArr = opendir($fileNm)){
                while (($file = readdir($fileArr)) !== false && count($fileNmArr) < 20) {
                    if(!in_array($file, $data['offline']['certificates'])){
                        if(!is_dir($file)){
                            $type = pathinfo($file, PATHINFO_EXTENSION);
                            if(in_array($type, ['png', 'jpg', 'jpeg'])){
                                $fileNmArr[] = ['filefolder'=> 'certificates', 'filename'=> $file];
                            }
                        }
                    }
                }
            }
        }
        
        if(is_dir($filePass)){
            if($fileArr = opendir($filePass)){
                while (($file = readdir($fileArr)) !== false && count($fileNmArr) < 20) {
                    if(!in_array($file, $data['offline']['passports'])){
                        if(!is_dir($file)){
                            $type = pathinfo($file, PATHINFO_EXTENSION);
                            if(in_array($type, ['png', 'jpg', 'jpeg'])){
                                $baseImg = 'data:image/'.$type.';base64,'.base64_encode(file_get_contents($filePass.'/'.$file));
                                $fileNmArr[] = ['filefolder'=> 'passport', 'filename'=>$file, 'baseImg'=>$baseImg];
                            }
                        }
                    }
                }
            }
        }
        
        // dd($fileNmArr);
        return response()->json(['notOnline' => $fileNmArr, 'prefix'=>$school->prefix]);
    }

    public function checkFile($school){
        $fileNm = storage_path().'/app/public/images/'.$school->prefix.'/photo/school';
        $filePass = storage_path().'/app/public/images/'.$school->prefix.'/passports';
        $fileNmArr = ['certificates'=>[], 'passports'=>[]];

        if(is_dir($fileNm)){
            if($fileArr = opendir($fileNm)){
                while (($file = readdir($fileArr)) !== false) {
                    if(!is_dir($file)){
                        $fileNmArr['certificates'][] = $file;
                    }
                }
            }
        }

        if(is_dir($filePass)){
            if($fileArr = opendir($filePass)){
                while (($file = readdir($fileArr)) !== false) {
                    if(!is_dir($file)){
                        $fileNmArr['passports'][] = $file;
                    }
                }
            }
        }

        // dd($fileNmArr);
        return ['offline' => $fileNmArr, '1'=>$fileNm, 'prefix'=>$school->prefix];
    }
}

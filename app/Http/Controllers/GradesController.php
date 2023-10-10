<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use App\SchoolSection;
use DB;

class GradesController extends Controller
{
    //
    public function create(School $school){
        $existFormat = DB::table('grading_formats')->where('school_id', $school->id)->select('format_id')->groupBy('format_id')->get();
        $allFormats = [];
        
        foreach($existFormat as $format){
            $allFormats[] = DB::table('grading_formats')->where([
                            ['school_id', $school->id],
                            ['format_id', $format->format_id],
                        ])->orderBy('minScore', 'DESC')->get()->toArray();
        }
        // dd($allFormats, $existFormat);
        $schoolSections = DB::table('school_sections')->where([
                            ['school_id', $school->id],
                            ['status', 'Active'],
                            ])->get();

        return view('gradeformat.create', compact('school', 'allFormats', 'existFormat', 'schoolSections'));
    }

    public function store(School $school, Request $request){
        // dd($request->all());
        $formatData = $request->all();

        $lastFormatId = DB::table('grading_formats')->select('format_id')->where('school_id', $school->id)->max('format_id');
        
        if($lastFormatId == null){
            $lastFormatId = 1;
        } else {
            $lastFormatId += 1;
        }

        // dd($lastFormatId);
        
        foreach($formatData as $format){
            $format['format_id'] = $lastFormatId;
            $newFormat[] = $format;
        }
        // $formatDataJson = (array) json_decode($request->input('data'));
        // $formatData = [];
        // foreach($formatDataJson as $format){
        //     $formatData[] = (array) $format;
        // }
        // dd($newFormat);
        $formatInsert = DB::table('grading_formats')->insert($newFormat);

        return response()->json(['successInfo' => $formatInsert]);

    }

    public function index(School $school){
        $gradeFormat = DB::table('grading_formats')->where([
            ['school_id', $school->id],
            ['status', '1']
            ])->get();

        $schoolSections = DB::table('school_sections')->where('school_id', $school->id)->get();

        return view('gradeformat.index', compact('gradeFormat', 'schoolSections', 'school'));
    }

    public function edit(School $school, SchoolSection $section){
        $gradeFormat = DB::table('grading_formats')->where([
            ['school_id', $school->id],
            ['section_id', $section->id],
            ['status', '1']
        ])->get();

        return view('gradeformat.edit', compact('gradeFormat', 'school'));
    }

    public function assignGradeFormat(Request $request){
        $data = $request->all();
        // dd($data);
        $assignSuccess = DB::table('school_sections')->where([
                            ['id', $data['sectionId']],
                            ['school_id', $data['schoolId']],
                            ])->update(['grading_format_id'=>$data['gradingId']]);

        return response()->json(['updated'=>$assignSuccess]);
    }
}

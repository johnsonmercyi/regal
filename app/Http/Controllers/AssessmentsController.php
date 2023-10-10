<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use DB;

class AssessmentsController extends Controller
{
    public function create(School $school){
        $existFormat = DB::table('assessment_formats')->where('school_id', $school->id)->select('format_id')->groupBy('format_id')->get();
        $allFormats = [];
        
        foreach($existFormat as $format){
            $allFormats[] = DB::table('assessment_formats')->where([
                            ['school_id', $school->id],
                            ['format_id', $format->format_id],
                        ])->orderBy('formatType', 'ASC')->get()->toArray();
        }
        // dd($allFormats, $existFormat);
        $schoolSections = DB::table('school_sections')->where([
                            ['school_id', $school->id],
                            ['status', 'Active'],
                            ])->get();

        return view('assessmentformat.create', compact('school', 'allFormats', 'existFormat', 'schoolSections'));
    }

    public function store(School $school, Request $request){
        $formatDataJson = (array) json_decode($request->input('data'));
        $formatData = [];
        $lastformat_id = DB::table('assessment_formats')->select('format_id')->where('school_id', $school->id)->max('format_id');
        if($lastformat_id == null){
            $lastformat_id = 1;
        } else {
            $lastformat_id += 1;
        }

        // dd($lastformat_id);
        foreach($formatDataJson as $format){
            $format = (array) $format;
            $format['format_id'] = $lastformat_id;
            $formatData[] = $format;
        }
        // dd($formatData);
        $formatInsert = DB::table('assessment_formats')->insert($formatData);

        return response()->json(['successInfo' => $formatInsert]);

    }

    public function assignAssessFormat(Request $request){
        $data = $request->all()['data'];
        // dd($data);
        $assignSuccess = DB::table('school_sections')->where([
                            ['id', $data['sectionId']],
                            ['school_id', $data['schoolId']],
                            ])->update(['assessment_format_id'=>$data['assessmentId']]);

        return response()->json(['updated'=>$assignSuccess]);
    }
}

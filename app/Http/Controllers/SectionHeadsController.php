<?php

namespace App\Http\Controllers;

use App\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SectionHeadsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(School $school, Request $request) {
    try {
      $sections = DB::Select("SELECT id, sectionName FROM school_sections;");
      $sectionHeads = DB::select("SELECT sh.name, ss.sectionName section_name FROM section_heads sh
      INNER JOIN school_sections ss ON sh.section_id = ss.id");
    } catch (\Throwable $e) {
      return $request->json(["error" => $e->getMessage()]);
    }
    return view('section_heads.index', compact('school', 'sectionHeads', 'sections'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(School $school, Request $request) {
    $data = $request->all();
    $sectionId = $data['sectionSelect'];
    $sectionHead = $data['sectionHead'];
    $sectionHeadSign = $data['sectionHeadSign'];
    
    // dd("Data: ", $data);
    // $sectionHead = null;
    try {

      // dd($data);

      
      if (isset($sectionHeadSign)) {
        $sectionHeadName = str_replace(" ", '_', $sectionHead);
        $clientExt = $request->file('sectionHeadSign')->getClientOriginalExtension();
        $fileNameToStore = $sectionHeadName . '_' . date('h_i_s_d_m_Y') . '.' . $clientExt;
        $path = $request->file('sectionHeadSign')->storeAs('public/images/' . $school->prefix . '/photo/school', $fileNameToStore);

        $sectionHeadSign = $fileNameToStore;

        $sectionHead = DB::insert("INSERT INTO section_heads (section_id, school_id, name, signature) VALUES ($sectionId, $school->id, '$sectionHead', '$sectionHeadSign')");
      }
    } catch (\Throwable $e) {
      return response()->json(["error" => $e->getMessage()]);
    }

    return response()->json(["data" => $sectionHead]);
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
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
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
}

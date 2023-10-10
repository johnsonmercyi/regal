<?php

namespace App\Http\Controllers;

use App\Guardian;
use Illuminate\Http\Request;
use App\MaritalStatus;
use App\School;

class GuardianController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $guardians = Guardian::all();

        return view('guardians.index', compact('guardians'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $marital_statuses = MaritalStatus::all();
        $guardian = new Guardian();

        return view('guardians.create', compact('marital_statuses', 'guardian'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        /**Recieves request data,converts it to array */
        $data = (array) json_decode($request->input('data'));
        // dd($data);
        /**Create a customer with the return data array*/
        $guardian = Guardian::create((array)$data['guardian']);
        // dd($guardian);
        /***
         * Supposed to redirect to show view but nope
         * i am using a dialog on the front end to display
         * the created record details returned through a response
         * from an Ajax request
         */
        //return redirect('customers');

        /**Returns a response to the calling Ajax Post
         * Note:
         *      This gave us a mass assignment error
         *      and to fix it with had to tell laravel
         *      how many fields it should always expect
         *      and fill. That was done in the Customer
         *      model class using the protected $fillable
         *      array variable.
         */
        return response()->json(['resobj' => $guardian]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Guardian  $guardian
     * @return \Illuminate\Http\Response
     */
    public function show(School $school, Guardian $guardian) {
        $students = $guardian->student;
        // dd($guardian->student);
        return view('guardians.view', compact('school', 'guardian', 'students'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Guardian  $guardian
     * @return \Illuminate\Http\Response
     */
    public function edit(Guardian $guardian) {

        $marital_statuses = MaritalStatus::all();
        // $marital_status = MaritalStatus::where('id', $guardian->maritalStatusId);

        return view('guardians.edit', compact('guardian', 'marital_statuses'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Guardian  $guardian
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Guardian $guardian) {

        /**Recieves request data,converts it to array */
        $data = (array) json_decode($request->input('data'));
        $dataGuardian = (array) $data['guardian'];
        
        /**Create a customer with the return data array*/
        $guardian->update($dataGuardian);

        return response()->json(['resobj' => $guardian]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Guardian  $guardian
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guardian $guardian) {

        $deleted = $guardian->delete();

        return response()->json(['deleted' => $deleted]);

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use App\Student;
use App\School;
use Illuminate\Support\Facades\DB as FacadesDB;

class LoginPageController extends Controller
{
    //
    public function index(){
        // if(isset($_SESSION['user']))
        // {session_destroy();}
        Auth::logout();
        return view('login.loginPage');
    }
    
    public function logout(){
        // if(isset($_SESSION['user']))
        // {session_destroy();}
        Auth::logout();
        return view('login.loginPage');
    }

    public function mainLogin(Request $request){
        $userDetails = $request->all();

        $username = $userDetails['uname'];
        $password = $userDetails['pword'];
        $nameSplit = explode('/', $username);

        if(Auth::attempt(['name'=> $username, 'password' => $password])){
            // dd($request->user()->name);
            
            if($nameSplit[0] == 'admin'){
                return response()->json([
                    'success' => true, 
                    'location' => '/schools/'.$request->user()->school_id
                ]);
            }
            if($nameSplit[1] == 'STF'){
                return response()->json([
                    'success' => true, 
                    'location' => '/'.$request->user()->school_id.'/staff/profile/'.$nameSplit[2]
                ]);
            }
            if($nameSplit[1] == 'STD'){
                return response()->json([
                    'success' => true, 
                    'location' => '/'.$request->user()->school_id.'/student/profile/'.$nameSplit[2]
                ]);
            }
        } else {
            // dd($request->user());
            return response()->json(['error' => true, 'cred'=>$userDetails]);
        }

        // if(count($userDetails['uname']) === 1){
        //     $user = (array) DB::table('users')->where([
        //                     ['name', $userDetails['uname'][0]],
        //                     ['password', $userDetails['pword']],
        //                 ])->first();
        // } else {
        //     $user = (array) DB::table('users')->where([
        //         ['name', $userDetails['uname'][2]],
        //         ['role', strtoupper($userDetails['uname'][1])],
        //         ['password', $userDetails['pword']],
        //     ])->first();
        // }
        // // dd($userDetails, $user);
        // if(empty($user)){
        //     return response()->json(["Authenticated"=>false]);
        // } else {
        //     $_SESSION['user'] = $user['name'];
        //     $_SESSION['role'] = $user['role'];
            
        //     // session(["user"=>$user['name']]);
        //     // $request->session()->put("user", $user['name']);
        //     return response()->json(["Authenticated"=>true, 'id'=>$user['name'], 'role'=>$user['role'], 'school'=>$user['school_id']]);
        // }

    }

    public function changePassword(Request $request, School $school){
        return view('archive.password', compact('school'));
    }

    public function repassword(Request $request){
        $data = $request->all();

        // $updatePass = DB::table('users')
        // ->where([['name', '=', $data['uname']], ['school_id', '=', $data['schoolId']]])
        // ->update(['password' => Hash::make($data['pword'])]);

        $updatePass = FacadesDB::table('scores')->updateOrInsert(
            ['name' => $data['uname'], 'school_id' => $data['schoolId']],
            ['password' => Hash::make($data['pword'])]
        );

        if(!$updatePass){
            return response()->json(['error' => true]);
        }

        return response()->json(['success' => true]);

    }
}

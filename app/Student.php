<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Student extends Model
{
    //
    protected $guarded = [];

    public function school(){
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function classroom() {
        $classroom = DB::table('class_students_list')->select('class_id')->where([
            ['academic_session_id', $this->school->academic_session_id],
            ['student_id', $this->id]
            ])->first();
        // return $this->belongsTo(Classroom::class);
        if(empty($classroom)){return null;}
        return (Classroom::find($classroom->class_id));
    }

    public function guardian() {
        return $this->belongsTo(Guardian::class, 'parent_id', 'id');
    }

    public function stateOfOrigin(){
        return $this->state_of_origin_id != '' ? 
            DB::table('states')->where('id', $this->state_of_origin_id)->first()->state
            : 'N/A'
            ;
    }
    public function lgaOfOrigin(){
        $lga =  $this->lga_id != '' && is_integer($this->lga_id) ? 
            DB::table('lgas')->where('id', $this->lga_id)->first()->lga
            : 'N/A'
            ;
        
        return $lga ? $lga : '';
    }
    
    public function religion(){
        return $this->religion_id != '' ? 
            DB::table('religions')->where('id', $this->religion_id)->first()->religion
            : 'N/A'
            ;
    }
    public function denomination(){
        return $this->denomination_id != '' ? 
            DB::table('religious_denominations')->where('id', $this->denomination_id)->first()->name
            : 'N/A'
            ;
    }
    public function getGender(){
        return $this->gender == 'M' ? 'MALE' : 'FEMALE';
    }
}

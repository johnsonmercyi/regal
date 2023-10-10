<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Staff extends Model
{

    protected $table = 'staff';
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function school(){
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function nextOfKin() {
        return $this->hasOne(NextOfKin::class, 'id', 'next_of_kin_id');
    }

    public function stateOfOrigin(){
        return $this->state_of_origin_id != '' ? 
            DB::table('states')->where('id', $this->state_of_origin_id)->first()->state
            : ''
            ;
    }
    
    public function lgaOfOrigin(){
        $lga =  $this->lga_of_origin_id != '' ? 
            DB::table('lgas')->where('id', $this->lga_of_origin_id)->first()
            : ''
            ;
        return $lga ? $lga->lga : '';
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

    public function staffCategory(){
        return $this->category_id != '' ? 
            DB::table('staff_category')->where('id', $this->category_id)->first()->name
            : 'N/A'
            ;
    }
    
    public function staffPosition(){
        return is_numeric($this->position) ? 
            DB::table('staff_position')->where('id', $this->position)->first()->position
            : 'N/A'
            ;
    }

    public function getGender(){
        return $this->gender == 'M' ? 'MALE' : 'FEMALE';
    }

    public function gradeLevel(){

        $grade_level = DB::table('staff_grade_level')->select('staff_grade_level.*', "grade_level.level", "grade_level.id as gid", "grade_level.rank")
        ->leftJoin("grade_level", "grade_level.id", "=", "staff_grade_level.grade_level_id")
        ->where([
            "school_id" => $this->school_id,
            "staff_id" => $this->id,
            "status" => '1'
        ])->first();

        return $grade_level;
    }
}

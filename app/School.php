<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'schools';
    protected $guarded = [];
    
    public function Classroom(){
        return $this->hasMany(Classroom::class, 'school_id', 'id');
    }

    public function AcademicSession(){
        return $this->hasOne(AcademicSession::class, 'id', 'academic_session_id');
    }

    public function Term($term){
        $termName = [1=>'First Term', 2=>'Second Term', 3=>'Third Term'];
        return $termName[$term];        
    }
    
    public function term_field($term){
        $columnName = ['1'=>'first_term', '2'=>'second_term', '3'=>'third_term'];
        return $columnName[$term];        
    }

    public function term_column(){
        $columnName = [1=>'first_term', 2=>'second_term', 3=>'third_term'];
        return $columnName[$this->current_term_id];
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    //
    protected $table = 'school_classes';
    protected $guarded = [];

    public function School(){
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function SchoolSection(){
        return $this->belongsTo(SchoolSection::class, 'section_id', 'id');
    }

    public function className(){
        return $this->level.' '.$this->suffix;
    }

}

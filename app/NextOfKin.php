<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NextOfKin extends Model
{
    
    //
    protected $guarded = [];
    protected $table = 'next_of_kin';

    public function staff() {
        return $this->belongsTo(Staff::class, 'id', 'next_of_kin_id');
    }
}

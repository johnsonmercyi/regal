<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model {

    protected $table = 'student_parents';

    protected $guarded = [];
    // protected $fillable = [
    //     'title',
    //     'firstName',
    //     'lastName',
    //     'otherName',
    //     'Email',
    //     'schoolId',
    //     'phoneNo',
    //     'occupation',
    //     'officePhone',
    //     'officeAddress',
    //     'maritalStatusId'
    // ];

    public function student() {
        return $this->hasMany(Student::class, 'parent_id', 'id');
    }
}

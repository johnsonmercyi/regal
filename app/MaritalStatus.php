<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaritalStatus extends Model {

    /**
     * This tells Laravel to save data only on this fields only
     */
    protected $table = 'marital_statuses';
    protected $fillable = [
        'marital_status'
    ];

    /**
     *An Eloquent function that relates `maritalStatus` to `Parent` using the Laravel Eloquent's `belongsTo()` method
     */
    public function guardians () {
        return $this->hasMany(Guardian::class);
    }

}
